<?php
  session_start();

  if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['user_id']);
    header("Location: login.php");
  }

  if (isset($connection)) {
    mysqli_close($connection);
  }
?>
