<?php
$check_device = array();
$device = new Device();
$device_id = $_REQUEST["device_id"];
if($device_id == $_SERVER["REMOTE_ADDR"]) {
  //no need to verify localhost
} else {
  
  if(empty($device_id)) {
    $check_device["status"] = false;
    $check_device["message"] = "Device is not registered!";
    echo json_encode($check_device);
    die();
  } else {
    $cek_dev = $device->isExist($device_id);
    if($cek_dev == false) {
      $check_device["status"] = false;
      $check_device["message"] = "Device is not registered!";
      echo json_encode($check_device);
      die();
    }
  }
}
foreach (glob(CONTROLLER_PATH . "/api/*.php") as $filename) {
  include $filename;
}
?>