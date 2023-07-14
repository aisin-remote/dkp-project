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
  $class = new LoadingList();
  
  $data = $cRfid->getById($rfid);
  $data["is_scanned"] = false;
  
  //cek apakah rfid sudah pernah di scan di loading list yang sama
  if(isset($_REQUEST["ldnum"])) {
    $ldnum = trim($_REQUEST["ldnum"]);
    $is_scanned = $class->isKanbanRFIDScanned($ldnum, $rfid);
    if($is_scanned > 0) {
      $data["is_scanned"] = true;
    }
  }
  echo json_encode($data);
}
?>