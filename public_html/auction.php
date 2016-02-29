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

  $query = "select * from user where user_id='".$auction['seller_id']."'";
  $result = mysqli_query($connection, $query);
  $seller = mysqli_fetch_array($result);

  $query = "select * from item where item_id='".$auction['item_id']."'";
  $result = mysqli_query($connection, $query);
  $item = mysqli_fetch_array($result);

  $query = "select * from bid where auction_id='".$auction['auction_id']."' order by price desc";
  $result = mysqli_query($connection, $query);
  if ($highest_bid = mysqli_fetch_array($result)) {
    $current_price = $highest_bid['price'];
  } else {
    $current_price = $auction['start_price'];
  }

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
    <span class="auction-info">Seller: <?php echo "<a href='/auction/public_html/user.php?user=".$seller['user_id']."'>".$seller['name']."</a>";?></span>
    <span class="auction-info">Description: <?php echo $item['description'];?></span>
    <span class="auction-info">End Date: <?php echo $auction['end_date'];?></span>
    <span class="auction-info">Start Price: &#163; <?php echo $auction['start_price']?></span>
    <span class="auction-info">Current Price: &#163; <?php echo $current_price?></span>
  </div>
</div>
<?php
  if ($_SESSION['user_type'] == "buyer") {
    echo "<a id='bid-link' href='/auction/public_html/new_bid.php?auction=".$auction['auction_id']."'>Bid on this auction</a>";
  }
?>
<?php
  if ($highest_bid) {
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
          <span class='bid-info' id='bidder-info'>Bid by <a href='/auction/public_html/user.php?user=".$bidder['user_id']."'>".$bidder['name']."</a></span>
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
