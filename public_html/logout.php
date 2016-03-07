<?php
  session_start();

  if (isset($_GET['logout'])) {
    $_SESSION = array();
    if (isset($_COOKIE[session_name()])) {
      setcookie(session_name(), '', time() - 3600);
    }
    session_destroy();
    header("Location: login.php");
  }

  if (isset($connection)) {
    mysqli_close($connection);
  }
?>
