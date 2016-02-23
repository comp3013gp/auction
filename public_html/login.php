<?php
  session_start();

  if (isset($_SESSION['user'])) {
    header("Location: /auction/public_html/main.php");
  }

  require_once(realpath(dirname(__FILE__) . "/../resources/dbconnection.php"));
  require_once(realpath(dirname(__FILE__) . "/../resources/config.php"));

  if (isset($_POST['action'])) {
    if ($_POST['action']=="login") {
      $email_address = mysqli_real_escape_string($connection,$_POST['email_address']);
      $password = mysqli_real_escape_string($connection,$_POST['password']);
      $query = "select * from user where email_address='".$email_address."' and password='".$password."'";
      $result = mysqli_query($connection, $query);
      $result_num = mysqli_num_rows($result);
      if($result_num>=1) {
        $query = mysqli_query($connection, "select * from user where email_address='$email_address'");
        $user = mysqli_fetch_array($query);
        $_SESSION['user'] = $user['user_id']; 
        header("Location: /auction/public_html/main.php");
      } else {
        ?><script>alert('You put invalid email or password.');</script><?php
      }
    } elseif($_POST['action']=="signup") {
      $name = mysqli_real_escape_string($connection, $_POST['name']);
      $email_address = mysqli_real_escape_string($connection, $_POST['email_address']);
      $password = mysqli_real_escape_string($connection, $_POST['password']);
      $user_type = mysqli_real_escape_string($connection, $_POST['user_type']);
      $query = "select email_address from user where email_address='".$email_address."'";
      $result = mysqli_query($connection,$query);
      $result_num = mysqli_num_rows($result);
      $message = "";
      if ($result_num>=1) {
        $message . "Email already exists.\n";
      }
      $message . $name . "\n";
      if ($message == "") {
        mysqli_query($connection, "insert into user(name,email_address,password,user_type) values('".$name."','".$email_address."','".$password."','".$user_type."')");
        $query = mysqli_query($connection, "select * from user where email_address='$email_address'");
        $user = mysqli_fetch_array($query);
        $_SESSION['user'] = $user['user_id']; 
        header("Location: /auction/public_html/main.php");
      } else {
        ?><script>alert(<?php echo $message;?>);</script><?php
      }
    }
  }

  require_once(TEMPLATES_PATH . '/top_bar.php');
?>
<h1 id="login-header">
  LogIn
</h1>
<h1 id="signup-header">
  SignUp
</h1>
<div class="center-block col-xs-6" id="login-form">
  <form method="post" action="<?=$_SERVER['PHP_SELF']?>" class="clearfix">
    <div class="input_group">
      <input name="email_address" type="text" class="form-control" placeholder="Email address">
    </div>
    <div class="input_group">
      <input name="password" type="password" class="form-control" placeholder="Password">
    </div>
    <input name="action" type="hidden" value="login">
    <button type="submit" class="btn btn-default pull-right">Submit</button>
  </form>
  <span class="form-changer" id="to-signup">Want to sign up? Click here!</span>
  <!--<span class="error-message"><?php echo $message?></span>-->
</div>
<div class="center-block col-xs-6" id="signup-form">
  <form method="post" action="<?=$_SERVER['PHP_SELF']?>" class="clearfix">
    <div class="input_group">
      <input name="name" type="text" class="form-control" placeholder="Username">
    </div>
    <div class="input_group">
      <input name="email_address" type="text" class="form-control" placeholder="Email address">
    </div>
    <div class="input_group">
      <input name="password" type="password" class="form-control" placeholder="Password">
    </div>
    <div class="radio">
      <label>
        <input type="radio" name="user_type" value="buyer" checked>
        Sign up as a buyer.
      </label>
    </div>
    <div class="radio">
      <label>
        <input type="radio" name="user_type" value="seller">
        Sign up as a seller.
      </label>
    </div>
    <input name="action" type="hidden" value="signup">
    <button type="submit" class="btn btn-default pull-right">Submit</button>
  </form>
  <span class="form-changer" id="to-login">Already have an account? Log in from here!</span>
</div>
<?php
  require_once(TEMPLATES_PATH . '/bottom_bar.php');
?>
