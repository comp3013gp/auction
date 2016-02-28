$(document).ready(function() {
  $("#to-signup").click(function() {
    $("#login-header").css('display', 'none');
    $("#signup-header").css('display', 'block');
    $("#login-form").css('display','none');
    $("#signup-form").css('display', 'block');
    return false;
  });
  $("#to-login").click(function() {
    $("#signup-header").css('display', 'none');
    $("#login-header").css('display', 'block');
    $("#signup-form").css('display','none');
    $("#login-form").css('display', 'block');
    return false;
  });
});

