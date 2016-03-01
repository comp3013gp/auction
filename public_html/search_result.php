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

  if (!isset($_GET['sort'])) {
    $sort = 'a.created_at desc';
    $sort_by = '';
  } elseif ($_GET['sort'] == 'ending-soon') {
    $sort = 'a.end_date asc';
    $sort_by = $_GET['sort'];
  } elseif ($_GET['sort'] == 'price-asc') {
    $sort = 'a.current_price asc';
    $sort_by = $_GET['sort'];
  } elseif ($_GET['sort'] == 'price-desc') {
    $sort = 'a.current_price desc';
    $sort_by = $_GET['sort'];
  } else {
    $sort = 'a.created_at desc';
    $sort_by = '';
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
  $query = "select i.item_id, i.name, i.description
            from item as i
            left join auction as a
            on i.item_id=a.item_id
            where i.category_id='".$_GET['category']."' and a.has_ended='0'
            order by ".$sort."";
  $item_result = mysqli_query($connection, $query);
  if (mysqli_num_rows($item_result) > 0) {
    echo "
      <span id='sort-by'>Sort by:</span>
      <select id='sort-selector' onchange='window.location = this.options[this.selectedIndex].value'>
        <option id='newly-added'
                value='/auction/public_html/search_result.php?category=".$_GET['category']."'>
          Newly Added
        </option>
        <option id='price-asc'
                value='/auction/public_html/search_result.php?category=".$_GET['category']."&sort=price-asc'
                ";if ($sort_by == 'price-asc') {echo 'selected';} echo ">
          Price: Low to High
        </option>
        <option id='price-desc'
                value='/auction/public_html/search_result.php?category=".$_GET['category']."&sort=price-desc'
                ";if ($sort_by == 'price-desc') {echo 'selected';} echo ">
          Price: High to Low
        </option>
        <option id='ending-soon'
                value='/auction/public_html/search_result.php?category=".$_GET['category']."&sort=ending-soon'
                ";if ($sort_by == 'ending-soon') {echo 'selected';} echo ">
          Ending Soon
        </option>
      </select>  
    ";
    echo "<ul class='list-group' id='result-list'>";
    while ($item = mysqli_fetch_array($item_result)) {
      $query = "select * from auction where item_id='".$item['item_id']."'";
      $auction_result = mysqli_query($connection, $query);
      $auction = mysqli_fetch_array($auction_result);
      $current_price = $auction['current_price'];
      $query = "select * from user where user_id='".$auction['seller_id']."'";
      $seller_result = mysqli_query($connection, $query);
      $seller = mysqli_fetch_array($seller_result);
      $query = "select * from bid where auction_id='".$auction['auction_id']."' order by price desc";
      $bid_result = mysqli_query($connection, $query);
      echo "
        <li class='list-group-item result-item'>
          <a class='item-name' href='/auction/public_html/auction.php?auction=".$auction['auction_id']."'>".$item['name']."</a> 
          <span class='seller-info'>(sold by <a class='seller-name' href='/auction/public_html/user.php?user=".$seller['user_id']."'>".$seller['name']."</a>)</span> <!--TODO-->
          <span class='auction-info'>".$item['description']."</span>
          <span class='auction-info'>End Date: ".$auction['end_date']."</span>
          <span class='auction-info'>Current Price: &#163; ".$current_price."</span>
        </li>
      "; 
    }
    echo "</ul>";
  } else {
    echo "<span id='not-found-message'>Sorry, no auction found.</span>";
  }
?>
<a id="back-to-search" href="/auction/public_html/search.php">Choose Other Category</a>
<?php
  require_once(TEMPLATES_PATH . '/bottom_bar.php');
?>
