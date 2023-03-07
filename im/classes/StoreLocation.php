<?php

class StoreLocation {
  //put your code here
  public function getList($werks = null) {
    $return = array();
    $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
    $sql = "SELECT a.*, b.name1 as plant_name FROM wms.m_lgort a "
          . " LEFT JOIN wms.m_werks b ON b.werks = a.werks "
            . "WHERE 1=1 ";
    if(!empty($werks)) {
      $sql .= " AND a.werks = '$werks' ";
    }
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
    $sql = "SELECT a.* FROM wms.m_lgort a "
            . "WHERE lgort = :id ";
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
      $sql = "INSERT INTO wms.m_lgort (lgort, werks, name1, crt_by, crt_dt) "
              . "values (:lgort, :werks, :name1, :crt_by, CURRENT_TIMESTAMP) ";
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(":lgort", strtoupper(trim($param["lgort"])), PDO::PARAM_STR);
      $stmt->bindValue(":werks", strtoupper(trim($param["werks"])), PDO::PARAM_STR);
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
      $sql = "UPDATE wms.m_lgort SET werks = :werks, name1 = :name1, chg_by = :chg_by, chg_dt = CURRENT_TIMESTAMP "
              . " WHERE lgort = :lgort";
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(":lgort", strtoupper(trim($param["lgort"])), PDO::PARAM_STR);
      $stmt->bindValue(":werks", strtoupper(trim($param["werks"])), PDO::PARAM_STR);
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
  
  public function isExist($id) {
    $return = 0;
    $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
    $sql = "SELECT count(*) as cnt FROM wms.m_lgort a "
            . "WHERE lgort = :id ";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":id", strtoupper($id), PDO::PARAM_STR);
    if($stmt->execute()) {
      while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
        $return = floatval($row["cnt"]);
      }
    }
    $stmt = null;
    $conn = null;
    return $return;
  }
  
  public function delete($id) {
    $return = array();
    if(empty($id)) {
      $return["status"] = false;
      $return["message"] = "ID Empty";
    } else {
      $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
      $sql = "DELETE FROM wms.m_lgort WHERE werks = :id";
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(":id", strtoupper(trim($id)), PDO::PARAM_STR);
      
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
  
  public function isUsed($id) {
    $return = 0;
    $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
    $sql = "SELECT count(*) as cnt FROM wms.t_mseg "
            . "WHERE lgort = :id ";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":id", strtoupper($id), PDO::PARAM_STR);
    if($stmt->execute()) {
      while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
        $return = floatval($row["cnt"]);
      }
    }
    $stmt = null;
    $conn = null;
    return $return;
  }
}

?>