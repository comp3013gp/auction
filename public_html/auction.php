<?php
  session_start();

  require_once("../resources/dbconnection.php");
  require_once("../resources/config.php");

  if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
  }

  $query = "select * from rating where rated_by='".$_SESSION['user_id']."' and is_pending='1'";
  $result = mysqli_query($connection, $query);
  if ($rating = mysqli_fetch_array($result)) {
    header("Location: rating.php?id=".$rating['rating_id']);
  }

  if (!isset($_GET['auction']) || $_GET['auction'] == '') {
    header("Location: search.php");
  }

  $query = "select * from auction where auction_id='".$_GET['auction']."'";
  $result = mysqli_query($connection, $query);
  $auction = mysqli_fetch_array($result);

  $new_view_count = $auction['view_count'] + 1;

  mysqli_query($connection, $query = "update auction set view_count=".$new_view_count." where auction_id=".$auction['auction_id']."");

  $current_price = $auction['current_price'];

  $query = "select * from user where user_id='".$auction['seller_id']."'";
  $result = mysqli_query($connection, $query);
  $seller = mysqli_fetch_array($result);

  $query = "select * from item where item_id='".$auction['item_id']."'";
  $result = mysqli_query($connection, $query);
  $item = mysqli_fetch_array($result);

  $date = time();
  $date = date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $date)));

  require_once(TEMPLATES_PATH . '/top_bar.php');
?>
<h1 id="auction-h1">
  <?php echo $item['name']; ?>
</h1>
<div class="panel panel-default" id="auction-detail">
  <div class="panel-heading">
    <h3 class="panel-title">Auction Detail</h3>
  </div>
  <div class="panel-body">
    <span class="auction-info">Seller: <?php echo "<a href='user.php?user=".$seller['user_id']."'>".$seller['name']."</a>";?></span>
    <span class="auction-info">Description: <?php echo $item['description'];?></span>
    <span class="auction-info">End Date: <?php echo $auction['end_date']; if ($auction['has_ended'] == '1') {echo ' (Already Ended)';}?></span>
    <span class="auction-info">Start Price: &#163; <?php echo $auction['start_price']?></span>
    <span class="auction-info">Current Price: &#163; <?php echo $current_price?></span>
  </div>
</div>
<?php
  if ($_SESSION['user_type'] == "buyer" && !$auction['has_ended'] == '1') {
    echo "<a id='bid-link' href='new_bid.php?auction=".$auction['auction_id']."'>Bid on this auction</a>";
  }
?>
<?php
  if ($auction['start_price'] != $current_price) {
    echo "
      <span id='bid-list-span'>Bids placed on this auction (in price descending order):</span>
      <ul id='bid-list' class='list-group'>
    ";
    $query = "select * from bid where auction_id='".$auction['auction_id']."' order by price desc";
    $bid_result = mysqli_query($connection, $query);
    while ($bid = mysqli_fetch_array($bid_result)) {
      $query = "select * from user where user_id='".$bid['bidder_id']."'";
      $bidder_result = mysqli_query($connection, $query);
      $bidder = mysqli_fetch_array($bidder_result);
      echo "
        <li class='list-group-item clearfix'>
          <span class='bid-info' id='bid-price'>&#163; ".$bid['price']."</span>
          <span class='bid-info' id='bid-time'>at ".$bid['created_at']."</span>
          <span class='bid-info' id='bidder-info'>Bid by <a href='user.php?user=".$bidder['user_id']."'>".$bidder['name']."</a></span>
        </li>
      ";
    }
  } else {
    echo "
      <span id='no-bids-span'>No bids placed on this auction yet.</span>
    ";
  }
?>
<?php
  require_once(TEMPLATES_PATH . '/bottom_bar.php');
?>
