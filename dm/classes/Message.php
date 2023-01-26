<?php

class Message {
  //put your code here
  public function getHeaderById($id) {
    $return = array();
    $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
    $sql = "SELECT * FROM t_dm_msg_h WHERE msg_id = '$id'";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":id", strtoupper($id), PDO::PARAM_STR);
    if($stmt->execute()) {
      while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
        $return = $row;
      }
    }
    $stmt = null;
    $conn = null;
    return $return;
  }
  
  public function getHeaderList($date_from = null, $date_to = null) {
    $return = array();
    $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
    $sql = "SELECT a.*, TO_CHAR(crt_dt,'DD-MM-YYYY HH24:MI') as fdate FROM t_dm_msg_h a "
            . "WHERE 1=1 ";
    if(!empty($date_from) && !empty($date_to)) {
      $sql .= " AND TO_CHAR(a.crt_dt,'YYYYMMDD') between '$date_from' AND '$date_to' ";
    }
    $sql .= " ORDER by msg_id ASC ";
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
  
  public function insertHeader($param = array()) {
    $return = array();
    if(empty($param)) {
      $return["status"] = false;
      $return["message"] = "Data Empty";
    } else {
      $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
      $sql = "INSERT INTO t_dm_msg_h (subject,crt_by,crt_dt) "
              . "values (:subject, :crt_by, CURRENT_TIMESTAMP) ";
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(":subject", $param["subject"], PDO::PARAM_STR);
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
  
  public function insertItem($param = array()) {
    $return = array();
    if(empty($param)) {
      $return["status"] = false;
      $return["message"] = "Data Empty";
    } else {
      $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
      $sql = "INSERT INTO t_dm_msg_i (msg_id, msg_itm, msg_txt, crt_by, crt_dt) "
              . "values (:msg_id, (select coalesce(max(msg_itm),0) + 1 FROM t_dm_msg_i where msg_id = :msg_id), :msg_txt, :crt_by, CURRENT_TIMESTAMP) ";
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(":msg_id", $param["msg_id"], PDO::PARAM_STR);
      $stmt->bindValue(":msg_txt", $param["msg_txt"], PDO::PARAM_STR);
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
  
  public function getItemList($id) {
    $return = array();
    $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
    $sql = "SELECT *, TO_CHAR(crt_dt, 'DD-MM-YYYY HH24:MI') as fdate FROM t_dm_msg_i WHERE msg_id = '$id' ORDER by msg_itm ASC";
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