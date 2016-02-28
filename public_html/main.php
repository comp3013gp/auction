<?php
  session_start();

  if (!isset($_SESSION['user_id'])) {
    header("Location: /auction/public_html/login.php");
  }
  require_once(realpath(dirname(__FILE__) . "/../resources/config.php"));
  require_once(TEMPLATES_PATH . '/top_bar.php');
?>
<h1>
  Main page
</h1>
<span>Hi user <?php echo $_SESSION["user_id"]?></span>
<a href="/auction/public_html/new_auction.php">Create Auction</a>
<?php
  require_once(TEMPLATES_PATH . '/bottom_bar.php');
?>
