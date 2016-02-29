<?php
  session_start();

  require_once(realpath(dirname(__FILE__) . "/../resources/dbconnection.php"));
  require_once(realpath(dirname(__FILE__) . "/../resources/config.php"));

  if (!isset($_SESSION['user_id'])) {
    header("Location: /auction/public_html/login.php");
  } elseif ($_SESSION['user_type'] == "seller") {
    header("Location: /auction/public_html/main.php");
  }

  if (!isset($_GET['category'])) {
    header("Location: /auction/public_html/search.php");
  }

  $query = "select * from item where category_id='".$_GET['category']."'";
  $item_result = mysqli_query($connection, $query);
  if ($item = mysqli_fetch_array($item_result)) {
    $auction_exists = true;
  } else {
    $auction_exists = false;
  }
  

  require_once(TEMPLATES_PATH . '/top_bar.php');
?>
<h1 id="search-result-h1">
  Search Result 
</h1>
<h2 id="search-result-h2">
<?php
  $query = "select * from category where category_id='".$_GET['category']."'";
  $result = mysqli_query($connection, $query);
  $category = mysqli_fetch_array($result);
  $category_name = $category['name'];
?>
  (Showing the results for "<?php echo $category_name; ?>")
</h2>
<?php
  $query = "select * from item where category_id='".$_GET['category']."'";
  $item_result = mysqli_query($connection, $query);
  if ($auction_exists) {
    echo "
      <span id='sort-by'>Sort by:</span>
      <select>
        <option>Newly Added</option>
        <option>Price: Low to High</option>
        <option>Price: High to Low</option>
        <option>Ending Soon</option>
      </select>  
    ";
  }
  if (!$auction_exists) {
    echo "<span id='not-found-message'>Sorry, no auction found.</span>";
  }
  echo "<ul class='list-group' id='result-list'>";
  while ($item = mysqli_fetch_array($item_result)) {
    $query = "select * from auction where item_id='".$item['item_id']."'";
    $auction_result = mysqli_query($connection, $query);
    $auction = mysqli_fetch_array($auction_result);
    $query = "select * from user where user_id='".$auction['seller_id']."'";
    $seller_result = mysqli_query($connection, $query);
    $seller = mysqli_fetch_array($seller_result);
    echo "
      <li class='list-group-item result-item'>
        <a class='item-name' href='/auction/public_html/auction.php?id=".$auction['auction_id']."'>".$item['name']."</a> 
        <span class='seller-info'>(sold by <a class='seller-name' href='/auction/public_html/user.php?id=".$seller['user_id']."'>".$seller['name']."</a>)</span> <!--TODO-->
        <span class='auction-info'>".$item['description']."</span>
        <span class='auction-info'>End Date: ".$auction['end_date']."</span>
        <span class='auction-info'>Current Price: &#163; </span> <!-- TODO-->
      </li>
    "; 
  }
  echo "</ul>";
?>
<a id="back-to-search" href="/auction/public_html/search.php">Choose Other Category</a>
<?php
  require_once(TEMPLATES_PATH . '/bottom_bar.php');
?>
