<?php 
if($action == "api_login_mobile") {
  $usrid = $_REQUEST["usrid"];
  $class = new User();
  $login = $class->loginMobile($usrid);
  echo json_encode($login);
}
?>