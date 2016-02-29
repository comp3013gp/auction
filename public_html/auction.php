<?php
  session_start();

  require_once(realpath(dirname(__FILE__) . "/../resources/dbconnection.php"));
  require_once(realpath(dirname(__FILE__) . "/../resources/config.php"));

  if (!isset($_SESSION['user_id'])) {
    header("Location: /auction/public_html/login.php");
  }

  if (!isset($_GET['auction']) || $_GET['auction'] == '') {
    header("Location: /auction/public_html/search.php");
  }

  $query = "select * from auction where auction_id='".$_GET['auction']."'";
  $result = mysqli_query($connection, $query);
  $auction = mysqli_fetch_array($result);

  $query = "select * from item where item_id='".$auction['item_id']."'";
  $result = mysqli_query($connection, $query);
  $item = mysqli_fetch_array($result);

  require_once(TEMPLATES_PATH . '/top_bar.php');
?>
<h1 id="auction-h1">
  <?php echo $item['name']; ?>
</h1>
<h2 id="auction-h2">
  <?php echo $item['description']; ?>
</h2>
<?php
  if ($_SESSION['user_type'] == "buyer") {
    echo "<a href='/auction/public_html/new_bid.php?auction=".$auction['auction_id']."'>Bid on this auction</a>";
  }
?>
<?php
  require_once(TEMPLATES_PATH . '/bottom_bar.php');
?>
