<?php 
if($action == "kanban_rfid") {
  $template["group"] = "Master Data";
  $template["menu"] = "Kanban RFID Customer";
  $class = new KanbanRFID();
  
  $data["list"] = $class->getList();
  require( TEMPLATE_PATH . "/t_kbrf_list.php" );
}
?>