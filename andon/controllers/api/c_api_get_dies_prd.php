<?php 
if($action == "api_get_dies_stroke_daily") {
  $return = array();
  $date = $_REQUEST["date"];
  $dies_id = $_REQUEST["dies_id"];
  
  $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
  $sql = "select a.dies_id, b.group_id, b.model_id, b.dies_no, sum(a.prd_qty) as stroke from t_prd_daily_i a 
          inner join m_dm_dies_asset b ON b.dies_id = a.dies_id::int
          where a.prd_dt = '$date' ";
  if(!empty($dies_id)) {
    $sql .= " AND a.dies_id = '$dies_id' ";
  }
  $sql .= "group by 1,2,3,4";
  $stmt = $conn->prepare($sql);
  if ($stmt->execute()) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $return[] = $row;
    }
  }
  header("Content-Type: application/json");
  echo json_encode($return);
}
?>