<?php

class KanbanRFID {
  //put your code here
  public function getList() {
    $return = array();
    $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
    $sql = "SELECT a.*, TO_CHAR(a.crt_dt, 'DD-MM-YYYY') as reg_dt, b.name1 as cust_name, c.name1 as mat_name FROM m_io_kanban_rfid a "
            . "INNER JOIN m_io_lfa1 b ON b.lifnr = a.lifnr "
            . "INNER JOIN m_io_mara c ON c.matnr = a.matnr "
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
    $sql = "SELECT a.*, b.matn1, b.name1, c.name1 as cust_name FROM m_io_kanban_rfid a "
            . "left join m_io_mara b ON b.matnr = a.matnr "
            . "left join m_io_lfa1 c ON c.lifnr = a.lifnr "
            . "WHERE a.rfid_tag = :id";
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
      $sql = "INSERT INTO m_io_kanban_rfid (rfid_tag, lifnr, matnr, crt_by, crt_dt) "
              . "values (:rfid_tag, :lifnr, :matnr, :crt_by, CURRENT_TIMESTAMP) ";
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(":matnr", strtoupper(trim($param["matnr"])), PDO::PARAM_STR);
      $stmt->bindValue(":lifnr", trim($param["lifnr"]), PDO::PARAM_STR);
      $stmt->bindValue(":rfid_tag", $param["rfid_tag"], PDO::PARAM_STR);
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
  
  public function massInsert($lifnr,$matnr,$rfid_kanban = array(),$crt_by) {
    $return = array();
    if (empty($rfid_kanban)) {
      $return["status"] = false;
      $return["message"] = "Data Empty";
    } else {
      $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
      $sql = "INSERT INTO m_io_kanban_rfid (rfid_tag,lifnr,matnr,crt_by,crt_dt) "
              . "values ";
      
      $insertQuery = array();
      $insertData = array();
      
      foreach ($rfid_kanban as $row) {
        $insertQuery[] = "(?,?,?,?,CURRENT_TIMESTAMP)";
        $insertData[] = trim($row);
        $insertData[] = $lifnr;
        $insertData[] = $matnr;
        $insertData[] = $crt_by;
      }
      
      if (!empty($insertQuery)) {
        $sql .= implode(', ', $insertQuery)." CONFLICT(rfid_tag) DO UPDATE SET lifnr = EXCLUDED.lifnr, matnr = EXCLUDED.matnr, crt_by = EXCLUDED.crt_by, crt_dt = CURRENT_TIMESTAMP";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute($insertData)) {
          $return["status"] = true;
        } else {
          $error = $stmt->errorInfo();
          $return["status"] = false;
          $return["message"] = trim(str_replace("\n", " ", $error[2]));
          error_log($error[2]);
        }
        $stmt = null;
        $conn = null;
      } else {
        $return["status"] = false;
        $return["message"] = "Data Empty";
      }
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
      $sql = "UPDATE m_io_kanban_rfid SET lifnr = :lifnr, matnr = :matnr, chg_by = :chg_by, chg_dt = CURRENT_TIMESTAMP "
              . "WHERE rfid_tag = :rfid_tag";
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(":matnr", strtoupper(trim($param["matnr"])), PDO::PARAM_STR);
      $stmt->bindValue(":lifnr", trim($param["lifnr"]), PDO::PARAM_STR);
      $stmt->bindValue(":rfid_tag", $param["rfid_tag"], PDO::PARAM_STR);
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
  
  public function getRfidList($matnr = array()) {
    $return = array();
    $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
    $sql = "SELECT * FROM m_io_kanban_rfid WHERE matnr in ('".implode("','",$matnr)."')";
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