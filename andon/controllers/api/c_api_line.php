<?php
if ($action == "api_get_line") {
  $line = new Line();

  $listline = $line->getLine();
  echo json_encode($listline);
}

if ($action == "api_update_buzzjp") {
  $line_id = $_REQUEST["line_id"];
  $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
  $query = "UPDATE m_prd_line SET buzzjp = 0 WHERE line_id = '$line_id' ";
  $stmt = $conn->prepare($query);
  if ($stmt->execute()) {
    $return["status"] = "success";
  } else {
    $return["status"] = "fail";
  }
  
  echo json_encode($return);
}