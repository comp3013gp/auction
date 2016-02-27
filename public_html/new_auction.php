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
  require_once(TEMPLATES_PATH . '/top_bar.php');
?>
<h1>
  Create a New Auction
</h1>
<div class="center-block col-xs-6" id="auction-form">
  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="clearfix" class="add-auction">
    <div class="input_group">
      <input name="item_name" type="text" class="form-control" placeholder="Item name">
    </div>
    <div class="input_group">
    <textarea form="add-auction" rows="5" id="item_desc" name="item_desc" type="text" class="form-control" placeholder="Item description"></textarea>
    </div>
    <input name="action" type="hidden" value="item">
    <select class="form-control">
      <option value="0">Select Category:</option>
      <?php
        $query = "select * from category";
        $result = mysqli_query($connection, $query);
        while ($category = mysqli_fetch_array($result)) {
          echo "<option value=".$category['category_id'].">".$category['name']."</option>";
        }
      ?>
    </select>
  </form>
  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="clearfix" class="add-auction">
    <div class="input_group">
      <input name="item_name" type="text" class="form-control" placeholder="Item name">
    </div>
    <div class="input_group">
    <textarea form="add-auction" rows="5" id="item_desc" name="item_desc" type="text" class="form-control" placeholder="Item description"></textarea>
    </div>
    <div class='input-group date' id='datetimepicker1'>
      <input type='text' class="form-control" />
      <span class="input-group-addon">
        <span class="glyphicon glyphicon-calendar"></span>
      </span>
      </div>
    </div>
    <script type="text/javascript">
      $(function () {
          $('#datetimepicker1').datetimepicker();
      });
    </script>
    <input name="action" type="hidden" value="item">
  </form> 
</div>
<?php
  require_once(TEMPLATES_PATH . '/bottom_bar.php');
?>
