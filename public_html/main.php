<?php
  session_start();

  if (!isset($_SESSION['user'])) {
    header("Location: /auction/public_html/login.php");
  }
  require_once(realpath(dirname(__FILE__) . "/../resources/config.php"));
  require_once(TEMPLATES_PATH . '/top_bar.php');
?>
<h1>
  Main page
</h1>
<span>Hi user <?php echo $_SESSION["user"]?></span>
<?php
  require_once(TEMPLATES_PATH . '/bottom_bar.php');
?>
