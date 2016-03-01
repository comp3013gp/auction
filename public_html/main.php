<?php
  session_start();

  if (!isset($_SESSION['user_id'])) {
    header("Location: /auction/public_html/login.php");
  }
  
  require_once(realpath(dirname(__FILE__) . "/../resources/dbconnection.php"));
  require_once(realpath(dirname(__FILE__) . "/../resources/config.php"));

  require_once(TEMPLATES_PATH . '/top_bar.php');
?>
<h1>
  Home
</h1>
<?php
  if ($_SESSION['user_type'] == "buyer") {
    echo "<a class='main-page' href='/auction/public_html/search.php'>Search Auction</a>";
  } else {
    echo "<a class='main-page' href='/auction/public_html/new_auction.php'>Create Auction</a>";
  }
?>

<h1>
  My Active Auctions
</h1>
<?php
  if ($_SESSION['user_type'] == "seller") {
    $query = "SELECT auction.current_price, auction.reserve_price, auction.end_date, auction.view_count,
                  auction.auction_id, item.name, item.description, category.name as cName
              FROM auction
                JOIN item ON item.item_id = auction.item_id
                JOIN category ON category.category_id = item.category_id
              WHERE auction.has_ended = '0' AND auction.seller_id = ".$_SESSION['user_id']."
              ORDER BY auction.end_date desc";
    $result = mysqli_query($connection, $query);
    $row = mysqli_fetch_array($result);

    if (!$row) {
      echo "<span id='not-found-message'>You don't have any active auctions.</span>";
    }
    else {
      echo "<ul class='list-group' id='result-list'>";
      do {
        echo "
        <li class='list-group-item result-item'>
          <a class='item-name' href='/auction/public_html/auction.php?auction=".$row['auction_id']."'>".$row['name']."</a>
          <span class='auction-info'>".$row['description']."</span>
          <span class='auction-info'>Category: ".$row['cName']."</span>
          <span class='auction-info'>Current Price: &#163;".$row['current_price']." (Reserve Price &#163;".$row['reserve_price'].")</span>
          <span class='auction-info'>End Date: ".$row['end_date']."</span>
          <span class='auction-info'>View Count: ".$row['view_count']."</span>
        </li>
        ";
      } while ($row = mysqli_fetch_array($result));
      echo "</ul>";
    }
  }
  else {
    $query = "SELECT auction.auction_id, auction.current_price, auction.end_date,
                  item.name, item.description, category.name as cName
              FROM auction
                JOIN item ON item.item_id = auction.item_id
                JOIN category ON category.category_id = item.category_id
              WHERE auction.has_ended = '0'
              ORDER BY auction.end_date desc";
    $result = mysqli_query($connection, $query);
    $row = mysqli_fetch_array($result);

    if (!$row) {
      echo "<span id='not-found-message'>You aren't participating in any active auctions.</span>";
    }
    else {
      echo "<ul class='list-group' id='result-list'>";
      do {
        $bids_query = "SELECT bid.price
                      FROM bid
                      WHERE bid.bidder_id = ".$_SESSION['user_id']." AND bid.auction_id = ".$row['auction_id']."
                      ORDER BY bid.updated_at desc";
        $bids_result = mysqli_query($connection, $bids_query);
        $most_recent_bid = mysqli_fetch_array($bids_result);
        $winning_or_losing = " (you're winning the auction!)";
        if ($row['current_price'] > $most_recent_bid['price']) $winning_or_losing = " (you've been outbid!)";

        echo "
        <li class='list-group-item result-item'>
          <a class='item-name' href='/auction/public_html/auction.php?auction=".$row['auction_id']."'>".$row['name']."</a>
          <span class='auction-info'>".$row['description']."</span>
          <span class='auction-info'>Category: ".$row['cName']."</span>
          <span class='auction-info'>Your Bid: &#163;".$most_recent_bid['price']."</span>
          <span class='auction-info'>Current Price: &#163;".$row['current_price'].$winning_or_losing."</span>
          <span class='auction-info'>End Date: ".$row['end_date']."</span>
        </li>
        ";
      } while ($row = mysqli_fetch_array($result));
      echo "</ul>";
    }
  }
?>

<h1>
  My Past Auctions
</h1>
<?php
  if ($_SESSION['user_type'] == "seller") {
    $query = "SELECT auction.current_price, auction.end_date, auction.view_count,
                  auction.auction_id, item.name, item.description, category.name as cName
              FROM auction
                JOIN item ON item.item_id = auction.item_id
                JOIN category ON category.category_id = item.category_id
              WHERE auction.has_ended = '1' AND auction.seller_id = ".$_SESSION['user_id']."
              ORDER BY auction.end_date desc";
    $result = mysqli_query($connection, $query);
    $row = mysqli_fetch_array($result);

    if (!$row) {
      echo "<span id='not-found-message'>You don't have any past auctions.</span>";
    }
    else {
      echo "<ul class='list-group' id='result-list'>";
      do {
        echo "
        <li class='list-group-item result-item'>
          <a class='item-name' href='/auction/public_html/auction.php?auction=".$row['auction_id']."'>".$row['name']."</a>
          <span class='auction-info'>".$row['description']."</span>
          <span class='auction-info'>Category: ".$row['cName']."</span>
          <span class='auction-info'>Sale Price: &#163;".$row['current_price']."</span>
          <span class='auction-info'>End Date: ".$row['end_date']."</span>
          <span class='auction-info'>View Count: ".$row['view_count']."</span>
        </li>
        ";
      } while ($row = mysqli_fetch_array($result));
      echo "</ul>";
    }
  }
  else {
    $query = "SELECT auction.auction_id, auction.current_price, auction.end_date,
                  item.name, item.description, category.name as cName
              FROM auction
                JOIN item ON item.item_id = auction.item_id
                JOIN category ON category.category_id = item.category_id
              WHERE auction.has_ended = '1'
              ORDER BY auction.end_date desc";
    $result = mysqli_query($connection, $query);
    $row = mysqli_fetch_array($result);

    if (!$row) {
      echo "<span id='not-found-message'>You don't have any past auctions.</span>";
    }
    else {
      echo "<ul class='list-group' id='result-list'>";
      do {
        $bids_query = "SELECT bid.price
                      FROM bid
                      WHERE bid.bidder_id = ".$_SESSION['user_id']." AND bid.auction_id = ".$row['auction_id']."
                      ORDER BY bid.updated_at desc";
        $bids_result = mysqli_query($connection, $bids_query);
        $most_recent_bid = mysqli_fetch_array($bids_result);
        $winning_or_losing = " (you won the auction!)";
        if ($row['current_price'] > $most_recent_bid['price']) $winning_or_losing = " (you were outbid!)";

        echo "
        <li class='list-group-item result-item'>
          <a class='item-name' href='/auction/public_html/auction.php?auction=".$row['auction_id']."'>".$row['name']."</a>
          <span class='auction-info'>".$row['description']."</span>
          <span class='auction-info'>Category: ".$row['cName']."</span>
          <span class='auction-info'>Your Bid: &#163;".$most_recent_bid['price']."</span>
          <span class='auction-info'>Sale Price: &#163;".$row['current_price'].$winning_or_losing."</span>
          <span class='auction-info'>End Date: ".$row['end_date']."</span>
        </li>
        ";
      } while ($row = mysqli_fetch_array($result));
      echo "</ul>";
    }
  }
?>

<?php
  require_once(TEMPLATES_PATH . '/bottom_bar.php');
?>
