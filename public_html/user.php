<?php
  session_start();

  require_once(realpath(dirname(__FILE__) . "/../resources/dbconnection.php"));
  require_once(realpath(dirname(__FILE__) . "/../resources/config.php"));

  if (!isset($_SESSION['user_id'])) {
    header("Location: /auction/public_html/login.php");
  }

  if (!isset($_GET['id'])) {
    header("Location: /auction/public_html/search.php");
  }

  $query = "select * from user where user_id='".$_GET['id']."'";
  $result = mysqli_query($connection, $query);
  $user = mysqli_fetch_array($result);

  require_once(TEMPLATES_PATH . '/top_bar.php');
?>
<h1 id="user-h1">
  <?php echo $user['name'];?>
</h1>
<h2 id="user-h2">
  (registered as <?php echo $user['user_type'];?>)
</h2>
<!-- TODO a user's ratings are shown in this page -->
<?php
  require_once(TEMPLATES_PATH . '/bottom_bar.php');
?>
