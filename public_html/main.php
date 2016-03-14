<?php
  session_start();

  if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
  }

  require_once("../resources/dbconnection.php");
  require_once("../resources/config.php");
  
  $query = "select * from rating where rated_by='".$_SESSION['user_id']."' and is_pending='1'";
  $result = mysqli_query($connection, $query);
  if ($rating = mysqli_fetch_array($result)) {
    header("Location: rating.php?id=".$rating['rating_id']);
  }

  require_once(TEMPLATES_PATH . '/top_bar.php');
?>
<h1>
  Home
</h1>
<?php
  if ($_SESSION['user_type'] == "buyer") {
    echo "<a class='main-page' href='search.php'>Search Auction</a>";
  } else {
    echo "<a class='main-page' href='new_auction.php'>Create Auction</a>";
  }
?>

<h2>
  My Active Auctions
</h2>
<?php
  if ($_SESSION['user_type'] == "seller") {
    $query = "SELECT MAX(bid.price) as MaxBid, bid.bidder_id, item.name, item.description, category.name as cName,
                    auction.auction_id, auction.reserve_price, auction.end_date, auction.view_count
                  FROM bid
                    JOIN auction ON auction.auction_id = bid.auction_id
                    JOIN item ON item.item_id = auction.item_id
                    JOIN category ON category.category_id = item.category_id
                  WHERE auction.has_ended = '0' AND auction.seller_id = ".$_SESSION['user_id']."
                  GROUP BY bid.auction_id
              UNION
              SELECT null as MaxBid, null as bidder_id, item.name, item.description, category.name as cName,
                    auction.auction_id, auction.reserve_price, auction.end_date, auction.view_count
                  FROM auction
                    JOIN item ON item.item_id = auction.item_id
                    JOIN category ON category.category_id = item.category_id
                  WHERE auction.has_ended = '0' AND auction.seller_id = ".$_SESSION['user_id']." AND NOT EXISTS (SELECT * from bid WHERE bid.auction_id = auction.auction_id)
                  ORDER BY end_date desc";
    $result = mysqli_query($connection, $query);
    $row = mysqli_fetch_array($result);

    if (!$row) {
      echo "<span id='not-found-message'>You don't have any active auctions.</span>";
    }
    else {
      echo "<ul class='list-group' id='result-list'>";
      do {
        $current_price = "No bids have been placed on this auction";
        if ($row['MaxBid'] != null) $current_price = "Latest Price: &#163;".$row['MaxBid']." (Reserve Price &#163;".$row['reserve_price'].")";

        echo "
        <li class='list-group-item result-item'>
          <a class='item-name' href='auction.php?auction=".$row['auction_id']."'>".$row['name']."</a>
          <span class='auction-info'>".$row['description']."</span>
          <span class='auction-info'>Category: ".$row['cName']."</span>
          <span class='auction-info'>".$current_price."</span>
          <span class='auction-info'>End Date: ".$row['end_date']."</span>
          <span class='auction-info'>View Count: ".$row['view_count']."</span>
        </li>
        ";
      } while ($row = mysqli_fetch_array($result));
      echo "</ul>";
    }
  }
  else {
    $query = "SELECT MAX(bid.price) as MaxBid, item.name, item.description, category.name as cName,
                auction.auction_id, auction.current_price, auction.end_date
              FROM bid
                JOIN auction ON auction.auction_id = bid.auction_id
                JOIN item ON item.item_id = auction.item_id
                JOIN category ON category.category_id = item.category_id
              WHERE auction.has_ended = '0' AND bid.bidder_id = ".$_SESSION['user_id']."
              GROUP BY bid.auction_id
              ORDER BY auction.end_date desc";
    $result = mysqli_query($connection, $query);
    $row = mysqli_fetch_array($result);

    if (!$row) {
      echo "<span id='not-found-message'>You aren't participating in any active auctions.</span>";
    }
    else {
      echo "<ul class='list-group' id='result-list'>";
      do {
        $winning_or_losing = " (you're winning the auction!)";
        if ($row['current_price'] > $row['MaxBid']) $winning_or_losing = " (you've been outbid!)";

        echo "
        <li class='list-group-item result-item'>
          <a class='item-name' href='auction.php?auction=".$row['auction_id']."'>".$row['name']."</a>
          <span class='auction-info'>".$row['description']."</span>
          <span class='auction-info'>Category: ".$row['cName']."</span>
          <span class='auction-info'>Your Bid: &#163;".$row['MaxBid']."</span>
          <span class='auction-info'>Latest Price: &#163;".$row['current_price'].$winning_or_losing."</span>
          <span class='auction-info'>End Date: ".$row['end_date']."</span>
        </li>
        ";
      } while ($row = mysqli_fetch_array($result));
      echo "</ul>";
    }
  }
?>

<h2>
  My Past Auctions
</h2>
<?php
  if ($_SESSION['user_type'] == "seller") {
    $query = "SELECT MAX(bid.price) as MaxBid, bid.bidder_id, user.name as BidderName, item.name, item.description, category.name as cName,
                    auction.auction_id, auction.reserve_price, auction.end_date, auction.view_count
                  FROM bid
                    JOIN auction ON auction.auction_id = bid.auction_id
                    JOIN item ON item.item_id = auction.item_id
                    JOIN category ON category.category_id = item.category_id
                    JOIN user ON bid.bidder_id = user.user_id
                  WHERE auction.has_ended = '1' AND auction.seller_id = ".$_SESSION['user_id']."
                  GROUP BY bid.auction_id
              UNION
              SELECT null as MaxBid, null as bidder_id, null as BidderName, item.name, item.description, category.name as cName,
                    auction.auction_id, auction.reserve_price, auction.end_date, auction.view_count
                  FROM auction
                    JOIN item ON item.item_id = auction.item_id
                    JOIN category ON category.category_id = item.category_id
                  WHERE auction.has_ended = '1' AND auction.seller_id = ".$_SESSION['user_id']." AND NOT EXISTS (SELECT * from bid WHERE bid.auction_id = auction.auction_id)
                  ORDER BY end_date desc";
    $result = mysqli_query($connection, $query);
    $row = mysqli_fetch_array($result);

    if (!$row) {
      echo "<span id='not-found-message'>You don't have any past auctions.</span>";
    }
    else {
      echo "<ul class='list-group' id='result-list'>";
      do {
        $outcome = "Auction ended without any bids";
        if ($row['MaxBid'] != null) $outcome = "Latest Price: &#163;".$row['MaxBid']." (Reserve Price &#163;".$row['reserve_price'].")";
        echo "
        <li class='list-group-item result-item'>
          <a class='item-name' href='auction.php?auction=".$row['auction_id']."'>".$row['name']."</a>
          <span class='auction-info'>".$row['description']."</span>
          <span class='auction-info'>Category: ".$row['cName']."</span>
          <span class='auction-info'>".$outcome."</span>
        ";
        if ($row['MaxBid'] != null && $row['MaxBid'] >= $row['reserve_price']) {
          echo "
          <span class='auction-info'>Auction won by <a href='user.php?user=".$row['bidder_id']."'>".$row['BidderName']."</a></span>
          ";
        }
        else {
          echo "
          <span class='auction-info'>Item was not sold</span>
          ";
        }
        echo "
          <span class='auction-info'>End Date: ".$row['end_date']."</span>
          <span class='auction-info'>View Count: ".$row['view_count']."</span>
        </li>
        ";
      } while ($row = mysqli_fetch_array($result));
      echo "</ul>";
    }
  }
  else {
    $query = "SELECT MAX(bid.price) as MaxBid, item.name, item.description, category.name as cName,
                auction.auction_id, auction.current_price, auction.end_date, auction.reserve_price
              FROM bid
                JOIN auction ON auction.auction_id = bid.auction_id
                JOIN item ON item.item_id = auction.item_id
                JOIN category ON category.category_id = item.category_id
              WHERE auction.has_ended = '1' AND bid.bidder_id = ".$_SESSION['user_id']."
              GROUP BY bid.auction_id
              ORDER BY auction.end_date desc";
    $result = mysqli_query($connection, $query);
    $row = mysqli_fetch_array($result);

    if (!$row) {
      echo "<span id='not-found-message'>You don't have any past auctions.</span>";
    }
    else {
      echo "<ul class='list-group' id='result-list'>";
      do {
        $winning_or_losing = " (you won the auction!)";
        if ($row['reserve_price'] > $row['current_price']) $winning_or_losing = " (this auction ended without a winner)";
        else if ($row['current_price'] > $row['MaxBid']) $winning_or_losing = " (you were outbid!)";

        echo "
        <li class='list-group-item result-item'>
          <a class='item-name' href='auction.php?auction=".$row['auction_id']."'>".$row['name']."</a>
          <span class='auction-info'>".$row['description']."</span>
          <span class='auction-info'>Category: ".$row['cName']."</span>
          <span class='auction-info'>Your Bid: &#163;".$row['MaxBid']."</span>
          <span class='auction-info'>Latest Price: &#163;".$row['current_price'].$winning_or_losing."</span>
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
