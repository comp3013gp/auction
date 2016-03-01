<?php
  session_start();

  require_once(realpath(dirname(__FILE__) . "/../resources/dbconnection.php"));
  require_once(realpath(dirname(__FILE__) . "/../resources/config.php"));

  if (!isset($_SESSION['user_id'])) {
    header("Location: /auction/public_html/login.php");
  }

  $query = "select * from rating where rated_by='".$_SESSION['user_id']."' and is_pending='1'";
  $result = mysqli_query($connection, $query);
  if ($rating = mysqli_fetch_array($result)) {
    header("Location: /auction/public_html/rating.php?id=".$rating['rating_id']);
  }

  if (!isset($_GET['user']) || $_GET['user'] == '') {
    header("Location: /auction/public_html/search.php");
  }

  if ($_SESSION['user_id'] == $_GET['user']) {
    $user_self = true;
  } else {
    $user_self = false;
  }

  $query = "select * from user where user_id='".$_GET['user']."'";
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
