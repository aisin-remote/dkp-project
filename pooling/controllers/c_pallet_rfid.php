<?php 
if($action == "pallet_rfid") {
  $template["group"] = "Master Data";
  $template["menu"] = "Pallet RFID";
  $class = new PalletRFID();
  $data = [];
  $data["list"] = $class->getList();
  require( TEMPLATE_PATH . "/t_pallet_list.php" );
}
?>