<?php 
if($action == "api_dashboard_im") {
  $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
  $sql = "SELECT b.mtart, SUM(a.clabs) as clabs FROM wms.t_mchb a 
          inner join wms.m_mara b On b.matnr = a.matnr 
          GROUP BY mtart";
  $stmt = $conn->prepare($sql);
  $data_sum = [];
  if ($stmt->execute()) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      if($row["mtart"] == "RAW") {
        $data_sum["RAW"] = $row["clabs"];
      } else if($row["mtart"] == "FIN") {
        $data_sum["FIN"] = $row["clabs"];
      }
      
    }
  }
  
  $sql = "SELECT a.lgort, 
          (select coalesce(sum(b.clabs),0) as fin FROM wms.t_mchb b INNER JOIN wms.m_mara c ON c.matnr = b.matnr WHERE c.mtart = 'FIN' and b.lgort = a.lgort),
          (select coalesce(sum(b.clabs),0) as raw FROM wms.t_mchb b INNER JOIN wms.m_mara c ON c.matnr = b.matnr WHERE c.mtart = 'RAW' and b.lgort = a.lgort)
          FROM wms.m_lgort a WHERE a.werks = 'JE10' ";
  $stmt = $conn->prepare($sql);
  $data_per_sloc = [];
  $sloc = [];
  if ($stmt->execute()) {
    $i = 0;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $data_per_sloc["FIN"][$i] = $row["fin"];
      $data_per_sloc["RAW"][$i] = $row["raw"];
      $sloc[] = $row["lgort"];
      $i++;
    }
  }
  
  $return = [];
  $return["summary"] = $data_sum;
  $return["chart01_categories"] = $sloc;
  $return["chart01_data"] = $data_per_sloc;
  
  echo json_encode($return);
}
?>