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
  
  $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
  $sql = "select pval1 as dev_min, pval2 as dev_max from m_param WHERE pid = 'QASANT_STDDEV' AND seq = '1'";
  
  $stmt = $conn->prepare($sql);
  
  if($stmt->execute()) {
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
      $data["dev_min"] = floatVal($row["dev_min"]);
      $data["dev_max"] = floatVal($row["dev_max"]);
    }
  }
  
  echo json_encode($data);
}
?>