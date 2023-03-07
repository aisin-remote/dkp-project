<?php 
if($action == "api_dies_stroke") {
  $result = [];
  $tanggal = $_REQUEST["date"]; //format Y-m-d
  $dies_id = $_REQUEST["dies_id"];
  $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
  
  $sql = "SELECT sum(prd_qty) AS stroke FROM t_prd_daily_i WHERE prd_dt = '$tanggal' AND dies_id = '$dies_id'";
  $stmt = $conn->prepare($sql);
  
  if($stmt->execute()) {
    $result["status"] = true;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $result["stroke"] = $row["stroke"];
    }
    $result["date"] = $tanggal;
    $result["dies_id"] = $dies_id;
    $result["message"] = "Q-OK";
  } else {
    $result["status"] = false;
    $result["stroke"] = null;
    $result["date"] = $tanggal;
    $result["dies_id"] = $dies_id;
    $error = $stmt->errorInfo();
    $result["message"] = "Q-ERROR : ".trim(str_replace("\n", " ", $error[2]));
  }
  
  echo json_encode($result);
}
?>