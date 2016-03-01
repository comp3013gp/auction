<?php
  session_start();

  require_once(realpath(dirname(__FILE__) . "/../resources/dbconnection.php"));
  require_once(realpath(dirname(__FILE__) . "/../resources/config.php"));

  if (!isset($_SESSION['user_id'])) {
    header("Location: /auction/public_html/login.php");
  }

  require_once(TEMPLATES_PATH . '/top_bar.php');
?>
<h1>
  Rating
</h1>
<?php
  require_once(TEMPLATES_PATH . '/bottom_bar.php');
?>
