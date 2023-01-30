<?php 
if($action == "api_reg_kanban_rfid") {
  $lifnr = $_REQUEST["lifnr"];
  $matnr = $_REQUEST["matnr"];
  $rfid_kanban = explode(";",$_REQUEST["rfid_kanban"]);
  $userid = $_REQUEST["userid"];
  $class = new KanbanRFID();
  
  $save = $class->massInsert($lifnr,$matnr,$rfid_kanban,$userid);
  
  echo json_encode($save);
}
?>