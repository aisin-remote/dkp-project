<?php

if (!isset($_SESSION[LOGIN_SESSION])) {
  $action = "login";
} else {
  $user = new User();
  $_SESSION["USERMENU"] = $user->getMenuByUser($_SESSION[LOGIN_SESSION]);
  $_SESSION["MENUGROUP"] = $user->getMenuGroupByUser($_SESSION[LOGIN_SESSION]);
  //$_SESSION["LIFNR"] = $user->getVendorID($_SESSION[LOGIN_SESSION]);
  /* cek auth */
  $cek_auth = false;
  foreach ($_SESSION["USERMENU"] as $menu) {
    if (strtoupper($menu["menuid"]) == strtoupper($action)) {
      $cek_auth = true;
      $menu_group = $menu["groupid"];
      break;
    }
  }
  if ($action == "logout" || $action == "home" || $action == "profile" || $action == "login") {
    $cek_auth = true;
  }
  if ($cek_auth == false) {
    $error = "You don't have Authorization to view $action Page, please call System Administrator";
    require( TEMPLATE_PATH . "/t_error.php" );
    die();
  }
  //check license
  $class_license = new License();
  $cek_license = $class_license->isSoftwareActivated();
}

foreach (glob(CONTROLLER_PATH . "/*.php") as $filename) {
  include $filename;
}

?>