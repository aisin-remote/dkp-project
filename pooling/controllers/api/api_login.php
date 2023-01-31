<?php 
if($action == "api_login_mobile") {
  $usrid = trim(explode("|",$_REQUEST["usrid"])[0]);
  $class = new User();
  $login = $class->loginMobile($usrid);
  echo json_encode($login);
}
?>