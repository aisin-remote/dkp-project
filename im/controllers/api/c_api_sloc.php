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

if($action == "api_wms_get_default_sloc") {
  $matnr = $_REQUEST["matnr"];
  $class = new Material();
  $data = $class->getById($matnr);
  echo json_encode($data);
  unset($data);
}
?>