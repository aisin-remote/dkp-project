<?php 
if($action == "api_check_item") {
  $grp_id = $_REQUEST["grp_id"];
  $ckItm = new ChecklistItem();
  $data = $ckItm->getList($grp_id);
  
  echo json_encode($data);
}

if($action == "api_check_item_det") {
  $itm_id = $_REQUEST["itm_id"];
  $ckItm = new ChecklistItem();
  $data = $ckItm->getById($itm_id);
  
  echo json_encode($data);
}
?>