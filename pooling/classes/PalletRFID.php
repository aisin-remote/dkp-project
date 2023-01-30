<?php

class PalletRFID {
  //put your code here
  public function getList() {
    $return = array();
    $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
    $sql = "SELECT a.*, TO_CHAR(a.crt_dt, 'DD-MM-YYYY') as reg_dt FROM m_io_pallet_rfid a "
            . " WHERE 1=1 ";
    
    $sql .= " ORDER by pallet_id ASC ";
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
    $sql = "SELECT * FROM m_io_pallet_rfid WHERE pallet_id = :id";
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
      $sql = "INSERT INTO m_io_pallet_rfid (pallet_id, crt_by, crt_dt) "
              . "values (:pallet_id, :crt_by, CURRENT_TIMESTAMP) ";
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(":pallet_id", trim($param["pallet_id"]), PDO::PARAM_STR);
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
  
  public function massInsert($rfid_pallet = array(),$crt_by) {
    $return = array();
    if (empty($rfid_pallet)) {
      $return["status"] = false;
      $return["message"] = "Data Empty";
    } else {
      $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
      $sql = "INSERT INTO m_io_pallet_rfid (pallet_id, crt_by, crt_dt) "
              . "values ";
      
      $insertQuery = array();
      $insertData = array();
      
      foreach ($rfid_pallet as $row) {
        $insertQuery[] = "(?,?,CURRENT_TIMESTAMP)";
        $insertData[] = $row;
        $insertData[] = $crt_by;
      }
      
      if (!empty($insertQuery)) {
        $sql .= implode(', ', $insertQuery);
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
}

?>