<?php
  defined("LIBRARY_PATH")
    or define("LIBRARY_PATH", realpath(dirname(__FILE__) . '/library/'));
  defined("TEMPLATES_PATH")
    or define("TEMPLATES_PATH", realpath(dirname(__FILE__) . '/templates/'));

  date_default_timezone_set('Europe/London');

  ini_set("error_reporting", "true");
  error_reporting(E_ALL|E_STRCT);
?>
