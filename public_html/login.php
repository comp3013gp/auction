<?php
  session_start();

  if (isset($_SESSION['user_id'])) {
    header("Location: main.php");
  }

  $message = '';

  require_once("../resources/dbconnection.php");
  require_once("../resources/config.php");

  if (isset($_POST['action'])) {
    if ($_POST['action']=='login') {
      if (!empty($_POST['email_address']) && !empty($_POST['password'])) {
        $email_address = mysqli_real_escape_string($connection,$_POST['email_address']);
        $password = mysqli_real_escape_string($connection,$_POST['password']);
        $user_type = mysqli_real_escape_string($connection,$_POST['user_type']);
        $query = "select * from user where email_address='".$email_address."' and password='".$password."' and user_type='".$user_type."'";
        $result = mysqli_query($connection, $query);
        $result_num = mysqli_num_rows($result);
        if($result_num>=1) {
          $query = mysqli_query($connection, "select * from user where email_address='".$email_address."' and user_type='".$user_type."'");
          $user = mysqli_fetch_array($query);
          $_SESSION['user_id'] = $user['user_id']; 
          $_SESSION['user_type'] = $user['user_type']; 
          header("Location: main.php");
        } else {
          $message .= 'You put invalid email, password, or user type.';
        }
      } else {
        $message .= 'You put invalid email, password, or user type.';
      }
      if ($message != '') {
        echo "<script type='text/javascript'>alert('$message');</script>";
      }
    } elseif($_POST['action']=='signup') {
      if (empty($_POST['user_type'])) {
        $message .= 'You have to choose to be a buyer or a seller.\n';
      } else {
        $user_type = mysqli_real_escape_string($connection, $_POST['user_type']);
      } if (empty($_POST['name'])) {
        $message .= 'Name is required.\n';
      } else {
        $name = mysqli_real_escape_string($connection, $_POST['name']);
        $query = "select name from user where name='".$name."' and user_type='".$user_type."'";
        $result = mysqli_query($connection,$query);
        $result_num = mysqli_num_rows($result);
        if ($result_num>=1) {
          $message .= 'Username is already used by a ' . $user_type . '.\n';
        }
      }
      if (empty($_POST['email_address'])) {
        $message .= 'Email address is required.\n';
      } elseif (!filter_var($_POST['email_address'], FILTER_VALIDATE_EMAIL)) {
        $message .= 'Enter a valid email address.\n';
      } else {
        $email_address = mysqli_real_escape_string($connection, $_POST['email_address']);
        $query = "select email_address from user where email_address='".$email_address."' and user_type='".$user_type."'";
        $result = mysqli_query($connection,$query);
        $result_num = mysqli_num_rows($result);
        if ($result_num>=1) {
          $message .= 'Email is already used by a ' . $user_type . '.\n';
        }
      } 
      if (empty($_POST['password'])) {
        $message .= 'Password is required.\n';
      } elseif (strlen($_POST["password"]) < '8') {
        $message .= 'Password must contain at least 8 characters.\n';
      } else {
        $password = mysqli_real_escape_string($connection, $_POST['password']);
      }
      if ($message == '') {
        mysqli_query($connection, "insert into user(name,email_address,password,user_type,created_at) values('".$name."','".$email_address."','".$password."','".$user_type."',NULL)");
        $query = mysqli_query($connection, "select * from user where email_address='".$email_address."' and user_type='".$user_type."'");
        $user = mysqli_fetch_array($query);
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_type'] = $user['user_type']; 
        header("Location: main.php");
      } else {
        echo "<script type='text/javascript'>alert('$message');</script>";
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
  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="clearfix" id="login">
    <div class="input_group">
      <input name="email_address" type="text" class="form-control" placeholder="Email address">
    </div>
    <div class="input_group">
      <input name="password" type="password" class="form-control" placeholder="Password">
    </div>
    <div class="radio">
      <label>
        <input type="radio" name="user_type" value="buyer" checked>
        Log in as a buyer.
      </label>
    </div>
    <div class="radio">
      <label>
        <input type="radio" name="user_type" value="seller">
        Log in as a seller.
      </label>
    </div>
    <input name="action" type="hidden" value="login">
    <input type="submit" value="Submit" name="submit" class="btn btn-default pull-right">
  </form>
  <span class="form-changer" id="to-signup">Want to sign up? Click here!</span>
</div>
<div class="center-block col-xs-6" id="signup-form">
  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="clearfix" id="signup">
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
    <input type="submit" value="Submit" name="submit" class="btn btn-default pull-right">
  </form>
  <span class="form-changer" id="to-login">Already have an account? Log in from here!</span>
</div>
<?php
  require_once(TEMPLATES_PATH . '/bottom_bar.php');
?>
