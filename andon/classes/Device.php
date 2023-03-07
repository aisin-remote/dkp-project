<?php

class Device {
  //put your code here
  public function getById($id) {
    $return = array();
    $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
    $sql = "SELECT * FROM m_device WHERE UPPER(device_id) = :id";
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
  
  public function getList() {
    $return = array();
    $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
    $sql = "SELECT a.* FROM m_device a "
            . "WHERE 1=1 ";
    
    $sql .= " ORDER by device_id ASC ";
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
  
  public function insert($param = array()) {
    $return = array();
    if(empty($param)) {
      $return["status"] = false;
      $return["message"] = "Data Empty";
    } else {
      $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
      $sql = "INSERT INTO m_device (device_id,name1,crt_by,crt_dt) "
              . "values (:device_id, :name1, :crt_by, CURRENT_TIMESTAMP) ";
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(":device_id", strtoupper(trim($param["device_id"])), PDO::PARAM_STR);
      $stmt->bindValue(":name1", $param["name1"], PDO::PARAM_STR);
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
      $sql = "UPDATE m_device SET name1 = :name1, chg_by = :chg_by, chg_dt = CURRENT_TIMESTAMP "
              . "WHERE device_id = :device_id";
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(":device_id", strtoupper($param["device_id"]), PDO::PARAM_STR);
      $stmt->bindValue(":name1", $param["name1"], PDO::PARAM_STR);
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
  
  public function delete($device_id) {
    $return = array();
    $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
    $sql = "DELETE FROM m_device WHERE device_id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":id", strtoupper($device_id), PDO::PARAM_STR);
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
  
  public function isExist($device_id) {
    $return = false;
    $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
    $sql = "SELECT count(*) as cnt FROM m_device WHERE device_id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":id", strtoupper($device_id), PDO::PARAM_STR);
    $count = 0;
    if($stmt->execute()) {      
      while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
        $count = $row["cnt"];
      }
    }
    
    if($count > 0) {
      $return = true;
    }
    $stmt = null;
    $conn = null;
    return $return;
  }
  
  public function getDeviceLicense() {
    $return = array();
    $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
    $sql = "select sum(lic_vol) as lic_vol, (select count(*) as cnt from m_device) as devices from m_license where lic_type = 'DEVICE'";
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
}

?>