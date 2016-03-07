<!DOCTYPE html>
<?php
  define('CSSPATH', '../../public_html/css/');
  define('JSPATH', '../../public_html/js/');
  $css_file =  basename($_SERVER["SCRIPT_FILENAME"], '.php') . '.css';
  $js_file =  basename($_SERVER["SCRIPT_FILENAME"], '.php') . '.js';
?>
<html>
<head>
  <title>COMP3013gp</title>
  <link rel="stylesheet" type="text/css" href="../../resources/library/bootstrap-3.3.6-dest/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="<?php echo (CSSPATH . 'application.css'); ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo (CSSPATH . 'top_bar.css'); ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo (CSSPATH . $css_file); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<?php
  require_once("../config.php"));
?>
<nav class="navbar">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="/auction/public_html/main.php">comp3013gp-auction</a>
    </div>
    <?php
      if (basename($_SERVER["SCRIPT_FILENAME"]) != "login.php") {
        echo ' 
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav navbar-right">
          <li><a class="nav-item" href="item_list.php">MyItems</a></li>
          <li><a class="nav-item" href="user.php?user='.$_SESSION['user_id'].'">MyAccount</a></li>
          <li><a class="nav-item" href="logout.php?logout">SignOut</a></li>
        </ul>
        </div>';
      }
    ?>
  </div><!-- /.container-fluid -->
</nav>
<div class="body-container">
