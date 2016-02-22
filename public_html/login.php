<?php
  require_once(realpath(dirname(__FILE__) . "/../resources/config.php"));
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
      <input type="text" class="form-control" placeholder="Email address">
    </div>
    <div class="input_group">
      <input type="text" class="form-control" placeholder="Password">
    </div>
    <button type="submit" class="btn btn-default pull-right">Submit</button>
  </form>
  <span class="form-changer" id="to-signup">Want to sign up? Click here!</span>
</div>
<div class="center-block col-xs-6" id="signup-form">
  <form method="post" action="<?=$_SERVER['PHP_SELF']?>" class="clearfix">
    <div class="input_group">
      <input type="text" class="form-control" placeholder="Username">
    </div>
    <div class="input_group">
      <input type="text" class="form-control" placeholder="Email address">
    </div>
    <div class="input_group">
      <input type="text" class="form-control" placeholder="Password">
    </div>
    <div class="radio">
      <label>
        <input type="radio" name="optionsRadios" id="optionsRadios1" value="buyer" checked>
        Sign up as a buyer.
      </label>
    </div>
    <div class="radio">
      <label>
        <input type="radio" name="optionsRadios" id="optionsRadios2" value="seller">
        Sign up as a seller.
      </label>
    </div>
    <button type="submit" class="btn btn-default pull-right">Submit</button>
  </form>
  <span class="form-changer" id="to-login">Already have an account? Log in from here!</span>
</div>
<?php
  require_once(TEMPLATES_PATH . '/bottom_bar.php');
?>
