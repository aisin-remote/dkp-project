<?php

class DiesStrokeHistory {
  //put your code here
  public function insert($param = array()) {
    $return = array();
    if(empty($param)) {
      $return["status"] = false;
      $return["message"] = "Data Empty";
    } else {
      $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
      $sql = "INSERT INTO t_dm_stk_hist (sthty, dies_id, stkrun, crt_dt, crt_by) "
              . "values (:sthty, :dies_id, (SELECT coalesce(stkrun,0) as stkrun FROM m_dm_dies_asset WHERE dies_id = '".$param["dies_id"]."'), CURRENT_TIMESTAMP, :crt_by) ";
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(":sthty", $param["sthty"], PDO::PARAM_STR);
      $stmt->bindValue(":dies_id", $param["dies_id"], PDO::PARAM_STR);
      $stmt->bindValue(":crt_by", $param["crt_by"], PDO::PARAM_STR);
      
      if($stmt->execute()) {
        $return["status"] = true;
        $return["id"] = $conn->lastInsertId();
      } else {
        $error = $stmt->errorInfo();
        $return["status"] = false;
        $return["message"] = trim(str_replace("\n", " ", $error[2]));
        error_log($error[2]);
      }
      $stmt = null;
      $conn = null;
    }
    return $return;
  }
  
  public function getList($date_from = null, $date_to = null, $dies_id = null) {
    $return = array();
    $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
    $sql = "SELECT *, TO_CHAR(crt_dt, 'DD-MM-YYYY HH24:MI') as fdate FROM t_dm_stk_hist WHERE 1=1 ";
    if(!empty($date_from) && !empty($date_to)) {
      $sql .= " AND TO_CHAR(crt_dt, 'YYYYMMDD') BETWEEN '$date_from' AND '$date_to' ";
    }
    
    if(!empty($dies_id)) {
      $sql .= "AND dies_id = '$dies_id' ";
    }
    
    $sql .= " ORDER BY sthid DESC ";
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
  
  public function setComplete($dies_id) {
    $return = array();
    $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
    $sql = "UPDATE t_dm_stk_hist SET stats = 'C' WHERE dies_id = '$dies_id' ";
    $stmt = $conn->prepare($sql);
    if($stmt->execute()) {
      $return["status"] = true;
    } else {
      $error = $stmt->errorInfo();
      $return["status"] = false;
      $return["message"] = trim(str_replace("\n", " ", $error[2]));
      error_log($error[2]);
    }
    $stmt = null;
    $conn = null;
    return $return;
  }
}

?>