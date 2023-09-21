<?php

class Checksheet {
  //put your code here
  function getTypeList() {
    $return = array();
    $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
    $sql = "SELECT * FROM qas_ant.m_cktyp ORDER BY type1 ASC ";
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
  
  function getShiftList() {
    $return = array();
    $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
    $sql = "SELECT * FROM qas_ant.m_shift ORDER BY shift ASC ";
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
  
  function getHdrByDate($date, $shift) {
    $return = array();
    $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
    $sql = "SELECT * FROM qas_ant.t_trn_hdr WHERE date1 = '$date' AND shift = '$shift' ";
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
  
  function getHdrById($date, $shift, $type) {
    $return = array();
    $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
    $sql = "SELECT a.*, b.name1 as type_text, c.name1 as shift_name FROM qas_ant.t_trn_hdr a 
            INNER JOIN qas_ant.m_cktyp b ON b.type1 = a.type1 
            INNER JOIN qas_ant.m_shift c ON c.shift = a.shift 
            WHERE a.date1 = '$date' AND a.shift = '$shift' AND a.type1 = '$type' ";
    $stmt = $conn->prepare($sql);
    if($stmt->execute()) {
      while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
        $return = $row;
      }
    }
    $stmt = null;
    $conn = null;
    return $return;
  }
  
  function getItmById($date, $shift, $type) {
    $return = array();
    $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
    $sql = "SELECT * FROM qas_ant.t_trn_itm WHERE date1 = '$date' AND shift = '$shift' AND type1 = '$type' ";
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
  
  function insertHeader($param) {
    $return = array();
    $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
    
    $sql = "INSERT INTO qas_ant.t_trn_hdr (date1, shift, type1, part_no, stats1, stats2, crt_dt, crt_by) VALUES (:date1,:shift,:type1,:part_no,'N','N',CURRENT_TIMESTAMP,:crt_by)";    
    $stmt = $conn->prepare($sql);    
    $stmt->bindValue(":date1", $param["date1"], PDO::PARAM_STR);   
    $stmt->bindValue(":shift", $param["shift"], PDO::PARAM_STR);   
    $stmt->bindValue(":type1", $param["type1"], PDO::PARAM_STR);   
    $stmt->bindValue(":part_no", $param["part_no"], PDO::PARAM_STR); 
    $stmt->bindValue(":crt_by", $param["crt_by"], PDO::PARAM_STR);
    if($stmt->execute()) {
      $return["status"] = true;
    } else {
      $error = $stmt->errorInfo();
      $return["status"] = false;
      $return["message"] = trim(str_replace("\n", " ", $error[2]));
    }
    $stmt = null;
    $conn = null;
    return $return;
  }
  
  function insertItem($date, $shift, $type, $itm_param) {
    $return = array();
    $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
    $sql = "DELETE FROM qas_ant.t_trn_itm WHERE date1 = '$date' AND shift = '$shift' AND type1 = '$type'";
    $conn->exec($sql);
    $sql = "INSERT INTO qas_ant.t_trn_itm VALUES ";
    $value_insert = [];
    foreach($itm_param as $itm) {
      $value_insert[] = "('".$date."','".$shift."','".$type."','".$itm["itm_id"]."','".$itm["actual"]."','".$itm["result1"]."')";
    }
    $sql .= implode(",",$value_insert);
    $stmt = $conn->prepare($sql);
    if($stmt->execute()) {
      $return["status"] = true;
    } else {
      $error = $stmt->errorInfo();
      $return["status"] = false;
      $return["message"] = trim(str_replace("\n", " ", $error[2]));
    }
    $stmt = null;
    $conn = null;
    return $return;
  }
}

?>