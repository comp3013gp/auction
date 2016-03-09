<?php
  session_start();

  require_once("../resources/dbconnection.php");
  require_once("../resources/config.php");

  if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
  }

  $query = "select * from rating where rating_id='".$_GET['id']."'";
  $result = mysqli_query($connection, $query);
  $rating = mysqli_fetch_array($result);

  if ($_SESSION['user_id'] != $rating['rated_by']) {
    header("Location: main.php");
  }

  $query = "select * from user where user_id='".$rating['user_id']."'";
  $result = mysqli_query($connection, $query);
  $rate_target = mysqli_fetch_array($result);

  $query = "select * from auction where auction_id='".$rating['auction_id']."'";
  $result = mysqli_query($connection, $query);
  $auction = mysqli_fetch_array($result);

  $query = "select * from user where user_id='".$auction['seller_id']."'";
  $result = mysqli_query($connection, $query);
  $seller = mysqli_fetch_array($result);

  $query = "select * from item where item_id='".$auction['item_id']."'";
  $result = mysqli_query($connection, $query);
  $item = mysqli_fetch_array($result);

  $query = "select * from user where user_id='".$item['owner_id']."'";
  $result = mysqli_query($connection, $query);
  $buyer = mysqli_fetch_array($result);

  $message = '';
    
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST['inlineRadioOptions'])) {
      $message .= 'You need to rate '.$rate_target['name'].' out of 5.\n';
    } else {
      $rating_val = mysqli_real_escape_string($connection, $_POST['inlineRadioOptions']);
    }
    if (empty($_POST['rating_comment'])) {
      $message .= 'You need to leave a comments.\n';
    } elseif (strlen($_POST['rating_comment']) < 10) {
      $message .= 'Your comment needs to be at least 10 characters.\n';
    } else {
      $rating_comment = mysqli_real_escape_string($connection, $_POST['rating_comment']);
    }
    if ($message != '') {
      echo "<script type='text/javascript'>alert('$message');</script>";
    } else {
      mysqli_query($connection, "update rating set rating='".$rating_val."', comment='".$rating_comment."', is_pending='0' where rating_id='".$_GET['id']."'");
      header('Location: main.php');
      echo "<script type='text/javascript'>alert('Thanks for rating!');</script>";
    }
  }

  require_once(TEMPLATES_PATH . '/top_bar.php');
?>
<h1>
  Rating
</h1>
<div class="panel panel-default" id="auction-detail">
  <div class="panel-heading">
    <h3 class="panel-title">Auction Detail</h3>
  </div>
  <div class="panel-body">
    <span class="auction-info">Sold By: <?php echo $seller['name'];?></span>
    <span class="auction-info">Bought By: <?php echo $buyer['name'];?></span>
    <span class="auction-info">Item: <?php echo $item['name'];?></span>
    <span class="auction-info">Description: <?php echo $item['description'];?></span>
    <span class="auction-info">End Date: <?php echo $auction['end_date'];?></span>
    <span class="auction-info">Start Price: &#163; <?php echo $auction['start_price']?></span>
    <span class="auction-info">End Price: &#163; <?php echo $auction['current_price'];?></span>
  </div>
</div>
<div class="center-block col-xs-6" id="rating-form">
  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]).'?id='.$_GET['id'];?>" class="clearfix" id="rating">
    <div class="input_group">
      <span id='rate-warning'>You have no access to any other page until you submit ratings.</span>
      <span id='rate-span'>Rate <?php echo $rate_target['name'];?> :&nbsp;&nbsp;</span>
      <label class="radio-inline">
        <input type="radio" name="inlineRadioOptions" value="1"> 1
      </label>
      <label class="radio-inline">
        <input type="radio" name="inlineRadioOptions" value="2"> 2
      </label>
      <label class="radio-inline">
        <input type="radio" name="inlineRadioOptions" value="3"> 3
      </label>
      <label class="radio-inline">
        <input type="radio" name="inlineRadioOptions" value="4"> 4
      </label>
      <label class="radio-inline">
        <input type="radio" name="inlineRadioOptions" value="5"> 5
      </label>
    </div>
    <div class="input_group">
      <textarea form="rating" rows="5" id="rating_comment" name="rating_comment" type="text" class="form-control" placeholder="How was the auction with <?php echo $rate_target['name'];?>?"></textarea>
    </div>
    <input name="action" type="hidden" value="rating">
    <input type="submit" value="submit" name="submit" class="btn btn-default pull-right">
  </form> 
</div>
<?php
  require_once(TEMPLATES_PATH . '/bottom_bar.php');
?>
