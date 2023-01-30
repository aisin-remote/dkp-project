<?php 
set_time_limit(0);
session_start();
require( "config.php" );
$media = isset($_GET["media"]) ? $_GET["media"] : "display";

/*
 * 
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$arr_path = explode("/",$path);
$action = strtolower($arr_path[(count($arr_path)-1)]);

if(empty($action)) {
  $action =  $arr_path[(count($arr_path)-2)];
}
if($action == APP_DIR) {
  $action = "home";
}
 * 
 */

$action = isset($_GET["action"]) ? strtolower($_GET["action"]) : "home";

$api_list = Menu::getListApi();
$in_api = array_search($action,$api_list,true);
if($in_api === false) {
  include "web.php";
} else {
  include "api.php";
}


?>