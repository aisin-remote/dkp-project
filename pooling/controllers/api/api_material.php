<?php 
if($action == "api_get_material") {
  $matnr = trim($_REQUEST["matnr"]);
  $class = new Material();
  $data = $class->getById($matnr);
  
  echo json_encode($data);
}

if($action == "api_check_rfid_kanban") {
  $rfid = trim($_REQUEST["rfid"]);
  $cRfid = new KanbanRFID();
  
  $data = $cRfid->getById($rfid);
  echo json_encode($data);
}
?>