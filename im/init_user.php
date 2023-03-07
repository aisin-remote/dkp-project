<?php 
/*include "config.php";

$role = new Role();
$param = [];
$param["roleid"] = "ADMIN";
$param["name1"] = "Role for System Administrator";
$param["crt_by"] = "SYS";
$role_menu = ["USER","MENU","ROLE"];
$param["rolemenu"] = $role_menu;
$save = $role->insert($param);
if($save["status"] == true) {
  echo "Initial Role Added".PHP_EOL;
} else {
  echo $save["message"].PHP_EOL;
}
$user = new User();

$data = [];
$data["usrid"] = "admin";
$data["usrpw"] = "admin";
$data["name1"] = "System Administrator";
$data["phone"] = "0";
$data["stats"] = "A";
$data["crt_by"] = "SYS";
$data["lifnr"] = "";
$user_role = [];
$user_role[0] = "ADMIN";
$data["user_role"] = $user_role;
$save = $user->insert($data);
if($save["status"] == true) {
  echo "Initial User Added".PHP_EOL;
} else {
  echo $save["message"].PHP_EOL;
}
 * 
 */
?>