<?php 
if($action == "api_wms_get_sloc") {
  $class = new StoreLocation();
  $werks = $_REQUEST["werks"];
  $data = [];
  if(!empty($werks)) {
    $data = $class->getList($werks);
  }
  echo json_encode($data);
  unset($data);
}
?>