<?php

class KanbanBarcode {
  //put your code here
  public function getList() {
    $return = array();
    $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
    $sql = "SELECT a.*, b.name1 FROM m_io_kanban_barcode a "
            . "inner join m_io_lfa1 b ON b.lifnr = a.lifnr "
            . "WHERE 1=1 ";
    
    $sql .= " ORDER by lifnr ASC ";
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
  
  public function getById($id) {
    $return = array();
    $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
    $sql = "SELECT * FROM m_io_kanban_barcode WHERE lifnr = :id";
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
  
  public function insert($param = array()) {
    $return = array();
    if(empty($param)) {
      $return["status"] = false;
      $return["message"] = "Data Empty";
    } else {
      $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
      $sql = "INSERT INTO m_io_kanban_barcode (lifnr, mat_start, mat_end, crt_by, crt_dt) "
              . "values (:lifnr, :mat_start, :mat_end, :crt_by, CURRENT_TIMESTAMP) ";
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(":lifnr", trim($param["lifnr"]), PDO::PARAM_STR);
      $stmt->bindValue(":mat_start", $param["mat_start"], PDO::PARAM_STR);
      $stmt->bindValue(":mat_end", $param["mat_end"], PDO::PARAM_STR);
      $stmt->bindValue(":crt_by", $param["crt_by"], PDO::PARAM_STR);
      
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
    }
    return $return;
  }
  
  public function update($param = array()) {
    $return = array();
    if(empty($param)) {
      $return["status"] = false;
      $return["message"] = "Data Empty";
    } else {
      $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
      $sql = "UPDATE m_io_kanban_barcode SET mat_start = :mat_start, mat_end = :mat_end, chg_by = :chg_by, chg_dt = CURRENT_TIMESTAMP "
              . "WHERE lifnr = :lifnr";
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(":lifnr", trim($param["lifnr"]), PDO::PARAM_STR);
      $stmt->bindValue(":mat_start", $param["mat_start"], PDO::PARAM_STR);
      $stmt->bindValue(":mat_end", $param["mat_end"], PDO::PARAM_STR);
      $stmt->bindValue(":chg_by", $param["chg_by"], PDO::PARAM_STR);
      
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
    }
    return $return;
  }
}

?>