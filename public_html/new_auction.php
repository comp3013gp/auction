<?php
  session_start();

  require_once(realpath(dirname(__FILE__) . "/../resources/dbconnection.php"));
  require_once(realpath(dirname(__FILE__) . "/../resources/config.php"));

  if (!isset($_SESSION['user_id'])) {
    header("Location: /auction/public_html/login.php");
  } else {
    $query = "select * from user where user_id='".$_SESSION['user_id']."'";
    $result = mysqli_query($connection, $query);
    $user = mysqli_fetch_array($result);
    if ($user['user_type'] == "buyer") {
      header("Location: /auction/public_html/main.php");
    }
  }

  $message = '';

  function validate_price($price) {
    $pattern = '/^[1-9][0-9]*\.[0-9]{2}$/';
    return preg_match($pattern, $price);
  }
 
  if (isset($_POST['action']) == 'new-auction') {
    if (empty($_POST['item_name'])) {
      $message .= 'Item name is required.\n';
    } else {
      $item_name = mysqli_real_escape_string($connection, $_POST['item_name']);
    }
    if (empty($_POST['item_desc'])) {
      $message .= 'Item description is required.\n';
    } else {
      $item_desc = mysqli_real_escape_string($connection, $_POST['item_desc']);
    }
    if (empty($_POST['category'])) {
      $message .= 'Category is required.\n';
    } else {
      $item_category = mysqli_real_escape_string($connection, $_POST['category']);
    }
    if (empty($_POST['start_price'])) {
      $message .= 'Start price is required.\n';
    } elseif (!validate_price($_POST['start_price'])) {
      $message .= 'Enter start price in valid format. (e.g. 50.00)\n';
    } else {
      $start_price = mysqli_real_escape_string($connection, $_POST['start_price']);
    }
    if (empty($_POST['reserve_price'])) {
      $message .= 'Reserve price is required.\n';
    } elseif (!validate_price($_POST['reserve_price'])) {
      $message .= 'Enter reserve price in valid format. (e.g. 50.00)\n';
    } else {
      $reserve_price = mysqli_real_escape_string($connection, $_POST['reserve_price']);
    }
    if (empty($_POST['end-year'])
      ||empty($_POST['end-month'])
      ||empty($_POST['end-day'])
      ||empty($_POST['end-time'])) {
      $message .= 'End date is required.\n';
    } else {
      $end_date_input = $_POST['end-year'] . '-' . $_POST['end-month'] . '-' . $_POST['end-day'] . ' ' . $_POST['end-time'];
      $end_date = mysqli_real_escape_string($connection, $end_date_input);
    }

    if ($message != '') {
      echo "<script type='text/javascript'>alert('$message');</script>";
    } else {
      mysqli_query($connection, "insert into item(owner_id, category_id, name, description) values('".$_SESSION['user_id']."','".$item_category."','".$item_name."','".$item_desc."')");
      $query = mysqli_query($connection, "select * from item where name='$item_name'");
      $item = mysqli_fetch_array($query);
      mysqli_query($connection, "insert into auction(seller_id, item_id, start_price, reserve_price, end_date) values('".$_SESSION['user_id']."','".$item['item_id']."','".$start_price."','".$reserve_price."', '".$end_date."')");
      echo "<script type='text/javascript'>alert('New auction created successfully.');</script>";
    }
  }
  require_once(TEMPLATES_PATH . '/top_bar.php');
?>
<h1>
  Create a New Auction
</h1>
<div class="center-block col-xs-6" id="auction-form">
  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="clearfix" id="add-auction">
    <div class="input_group">
      <input name="item_name" type="text" class="form-control" placeholder="Item name">
    </div>
    <div class="input_group">
      <textarea form="add-auction" rows="5" id="item_desc" name="item_desc" type="text" class="form-control" placeholder="Item description"></textarea>
    </div>
    <input name="action" type="hidden" value="item">
    <select name="category" class="form-control">
      <option value="">Select Category:</option>
      <?php
        $query = "select * from category";
        $result = mysqli_query($connection, $query);
        while ($category = mysqli_fetch_array($result)) {
          echo "<option value='".$category['category_id']."'>".$category['name']."</option>";
        }
      ?>
    </select>
    <div class='input_group'>
      <input name="start_price" type="text" class="form-control" placeholder="Start price (e.g. 50.00)">
    </div>
    <div class='input_group'>
      <input name="reserve_price" type="text" class="form-control" placeholder="Reserve price (e.g. 50.00)">
    </div>
    <span id="end-date-input">Auction End Date:</span> 
    <div class='input_group'>
      <select name="end-year" class="form-control end-date" id="end-year">
        <option value="" class="default-op">Year</option>
      </select>
      <select name="end-month" class="form-control end-date" id="end-month">
        <option value="" class="default-op">Month</option>
      </select>
      <select name="end-day" class="form-control end-date" id="end-day">
        <option value="" class="default-op">Day</option>
      </select>
      <select name="end-time" class="form-control end-date" id="end-time">
        <option value="" class="default-op">Time</option>
      </select>
    </div>
    <input name="action" type="hidden" value="new-auction">
    <input id="submit-new-auction" type="submit" value="submit" name="submit" class="btn btn-default pull-right">
  </form> 
</div>
<?php
  require_once(TEMPLATES_PATH . '/bottom_bar.php');
?>
