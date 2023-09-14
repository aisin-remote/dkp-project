<?php

class MeasureDevice {
  //put your code here
  function getList() {
    $return = array();
    $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
    $sql = "SELECT * FROM qas_ant.m_mdev ORDER BY sort1 ASC ";
    $stmt = $conn->prepare($sql);
    if($stmt->execute()) {
      while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
        $return[] = $row;
      }
    }
    $stmt = null;
    $conn = null;
    return $return;
  }
}

?>