<?php
  session_start();

  require_once(realpath(dirname(__FILE__) . "/../resources/dbconnection.php"));
  require_once(realpath(dirname(__FILE__) . "/../resources/config.php"));

  if (!isset($_SESSION['user_id'])) {
    header("Location: /auction/public_html/login.php");
  } else {
    if ($_SESSION['user_type'] == "seller") {
      header("Location: /auction/public_html/main.php");
    }
  }

  if (!isset($_GET['auction']) || $_GET['auction'] == '') {
    header("Location: /auction/public_html/search.php");
  }

  function validate_price($price) {
    $pattern = '/^[1-9][0-9]*\.[0-9]{2}$/';
    return preg_match($pattern, $price);
  }

  $query = "select * from auction where auction_id='".$_GET['auction']."'";
  $result = mysqli_query($connection, $query);
  $auction = mysqli_fetch_array($result);

  $current_price = $auction['current_price'];

  $query = "select * from user where user_id='".$auction['seller_id']."'";
  $result = mysqli_query($connection, $query);
  $seller = mysqli_fetch_array($result);

  $query = "select * from item where item_id='".$auction['item_id']."'";
  $result = mysqli_query($connection, $query);
  $item = mysqli_fetch_array($result);

  $message = '';

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST['bid-price'])) {
      $message .= 'You need to set bid price.\n';
    } elseif (!validate_price($_POST['bid-price'])) {
      $message .= 'Enter bid price in valid format. (e.g. 50.00)';
    } elseif ($_POST['bid-price'] <= $current_price) {
      $message .= 'You need to bid more than the current price.';
    } elseif ($_POST['bid-price'] > 99999999.99) {
      $message .= 'You cannot bid more than \u00A399999999.99';
    } else {
      $bid_price = mysqli_real_escape_string($connection, $_POST['bid-price']);
    }
    if ($message != '') {
      echo "<script type='text/javascript'>alert('$message');</script>";
    } else {
      mysqli_query($connection, "insert into bid(bidder_id, auction_id, price, created_at) values('".$_SESSION['user_id']."','".$auction['auction_id']."','".$bid_price."',NULL)");
      mysqli_query($connection, "update auction set current_price=".$bid_price." where auction_id=".$auction['auction_id']."");
      echo "<script type='text/javascript'>alert('Your bid placed successfully.');</script>";
    }
  }
  require_once(TEMPLATES_PATH . '/top_bar.php');
?>
<h1>
  Place a Bid
</h1>
<div class="panel panel-default" id="auction-detail">
  <div class="panel-heading">
    <h3 class="panel-title">Auction Detail</h3>
  </div>
  <div class="panel-body">
    <span class="auction-info">Seller: <?php echo "<a href='/auction/public_html/user.php?user=".$seller['user_id']."'>".$seller['name']."</a>";?></span>
    <span class="auction-info">Item: <?php echo "<a href='/auction/public_html/auction.php?auction=".$auction['auction_id']."'>".$item['name']."</a>";?></span>
    <span class="auction-info">Description: <?php echo $item['description'];?></span>
    <span class="auction-info">End Date: <?php echo $auction['end_date'];?></span>
    <span class="auction-info">Start Price: &#163; <?php echo $auction['start_price']?></span>
    <span class="auction-info">Current Price: &#163; <?php echo $current_price;?></span>
  </div>
</div>
<div class="center-block col-xs-6" id="bid-form">
  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]).'?auction='.$auction['auction_id'];?>" class="clearfix" id="add-bid">
    <span>You need to bid more than the current price.</span>
    <div class="input_group">
      <input name="bid-price" type="text" class="form-control" placeholder="How much do you want to bid? (e.g. 50.00)">
    </div>
    <input name="action" type="hidden" value="new-bid">
    <input type="submit" value="submit" name="submit" class="btn btn-default pull-right">
  </form> 
</div>

<?php
  require_once(TEMPLATES_PATH . '/bottom_bar.php');
?>
