<?php
ini_set("error_reporting", E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED & ~E_WARNING);
ini_set("error_log", "log/php-error.log");
ini_set("display_errors", true);

date_default_timezone_set("Asia/Jakarta");  // http://www.php.net/manual/en/timezones.php

define("CLIENT", "001");
define("APP", "AISIN_WMS");
define("MACHINE", gethostname());
define("LOGIN_SESSION", "aisin-id");
define("PAGE_TITLE", "INVENTORY MANAGEMENT SYSTEM");
define("FOOTER", "2022 &copy; PT. Aisin Indonesia Automotive");
define("APP_DIR", "wms");

$dbhost = "localhost";
$dbpass = "AisinBisaBanget@2023";
define("DB_DSN", "pgsql:host=$dbhost;port=5432;dbname=prd_report");
define("DB_USERNAME", "postgres");
define("DB_PASSWORD", $dbpass);
//10.80.27.196\\in_GIE,21433
//guest_in
//in_1402!asn
define("SQLSRV_DSN", "sqlsrv:Server=10.80.27.196\\in_GIE,21433;Database=J922");
define("SQLSRV_USERNAME", "guest_in");
define("SQLSRV_PASSWORD", "in_1402!asn");

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
