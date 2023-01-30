<?php 
if($action == "api_get_material") {
  $matnr = trim($_REQUEST["matnr"]);
  $class = new Material();
  $data = $class->getById($matnr);
  
  echo json_encode($data);
}
?>