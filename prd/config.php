<?php
ini_set("error_reporting", E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED & ~E_WARNING);
ini_set("error_log", "log/php-error.log");
ini_set("display_errors", true);

date_default_timezone_set("Asia/Jakarta");  // http://www.php.net/manual/en/timezones.php

define("CLIENT", "001");
define("APP", "AISIN_PRD");
define("MACHINE", gethostname());
define("LOGIN_SESSION", "aisin-id");
define("PAGE_TITLE", "PRODUCTION DIGITALIZATION");
define("FOOTER", "2022 &copy; PT. Aisin Indonesia Automotive");
define("APP_DIR", "prd");

$dbhost = "localhost";
$dbpass = "***";
define("DB_DSN", "pgsql:host=$dbhost;port=5432;dbname=db_aisin_main");
define("DB_USERNAME", "postgres");
define("DB_PASSWORD", $dbpass);

define("CLASS_PATH", "classes");
define("CONTROLLER_PATH", "controllers");
define("TEMPLATE_PATH", "templates");

foreach (glob(CLASS_PATH . "/*.php") as $filename) {
  require($filename);
}

function handleException($exception)
{
  $data["error"] = $exception->getMessage();
  require(TEMPLATE_PATH . "/t_error.php");
  error_log($exception->getMessage());
}

set_exception_handler('handleException');
