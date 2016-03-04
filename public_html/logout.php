<?php
  session_start();

  if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['user_id']);
    header("Location: login.php");
  }

  mysqli_close($connection);
?>
