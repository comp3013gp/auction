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

  require_once(TEMPLATES_PATH . '/top_bar.php');
?>
<h1>
  Search Result 
</h1>
<?php
  require_once(TEMPLATES_PATH . '/bottom_bar.php');
?>
