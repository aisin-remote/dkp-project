<?php 
if($action == "api_reg_pallet_rfid") {
  $rfid_pallet = explode(";",$_REQUEST["rfid_pallet"]);
  $userid = $_REQUEST["userid"];
  $class = new PalletRFID();
  
  $save = $class->massInsert($rfid_pallet,$userid);
  
  echo json_encode($save);
}
?>