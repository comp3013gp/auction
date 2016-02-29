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
<?php
  require_once(TEMPLATES_PATH . '/bottom_bar.php');
?>
