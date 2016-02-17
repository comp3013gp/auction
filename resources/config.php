<?php
  if(!mysqli_connect("localhost","comp3013-gp-dev","phphphp", "auction_site"))
    {die('connection problem - '.mysql_error());}

  defined("CSS_PATH")
    or define("CSS_PATH", realpath(dirname(__FILE__) . '../public_html/css/'));
  defined("JS_PATH")
    or define("JS_PATH", realpath(dirname(__FILE__) . '../public_html/js/'));
  defined("LIBRARY_PATH")
    or define("LIBRARY_PATH", realpath(dirname(__FILE__) . '/library/'));
  defined("TEMPLATES_PATH")
    or define("TEMPLATES_PATH", realpath(dirname(__FILE__) . '/templates/'));

  ini_set("error_reporting", "true");
  error_reporting(E_ALL|E_STRCT);
?>
