<?php 
if($action == "api_check_connection") {
  $data = [];
  $data["status"] = true;
  $data["message"] = "Device Connected";
  echo json_encode($data);
}
?>