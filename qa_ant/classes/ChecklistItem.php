<?php

class ChecklistItem {
  //put your code here
  function getList() {
    $return = array();
    $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
    $sql = "SELECT * FROM qas_ant.m_ckitm ORDER BY itm_id ASC ";
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
  
  function getById($id) {
    $return = array();
    $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
    $sql = "SELECT * FROM qas_ant.m_ckitm WHERE itm_id = :id ";
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
  
  function insert($param = []) {
    $return = array();
    if(empty($param)) {
      $return["status"] = false;
      $return["message"] = "Data Empty";
    } else {
      $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
      $sql = "INSERT INTO qas_ant.m_ckitm (itm_id, grp_id, mdev_id, itm_no, name1, std_min, std_max, std_uom, crt_dt, crt_by) "
              . "values (:itm_id, :grp_id, :mdev_id, :itm_no, :name1, :std_min, :std_max, :std_uom, CURRENT_TIMESTAMP, :crt_by) ";
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(":itm_id", $param["itm_id"], PDO::PARAM_STR);
      $stmt->bindValue(":grp_id", $param["grp_id"], PDO::PARAM_STR);
      $stmt->bindValue(":mdev_id", $param["mdev_id"], PDO::PARAM_STR);
      $stmt->bindValue(":itm_no", $param["itm_no"], PDO::PARAM_STR);
      $stmt->bindValue(":name1", $param["name1"], PDO::PARAM_STR);
      $stmt->bindValue(":std_min", $param["std_min"], PDO::PARAM_STR);
      $stmt->bindValue(":std_max", $param["std_max"], PDO::PARAM_STR);
      $stmt->bindValue(":std_uom", $param["std_uom"], PDO::PARAM_STR);
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
    }
    return $return;
  }
  
  function update($param = []) {
    $return = array();
    if(empty($param)) {
      $return["status"] = false;
      $return["message"] = "Data Empty";
    } else {
      $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
      $sql = "UPDATE qas_ant.m_ckitm SET grp_id=:grp_id, mdev_id=:mdev_id, itm_no=:itm_no, name1=:name1, std_min=:std_min, std_max=:std_max, std_uom=:std_uom, crt_by=:crt_by "
              . "WHERE itm_id = :itm_id ";
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(":itm_id", $param["itm_id"], PDO::PARAM_STR);
      $stmt->bindValue(":grp_id", $param["grp_id"], PDO::PARAM_STR);
      $stmt->bindValue(":mdev_id", $param["mdev_id"], PDO::PARAM_STR);
      $stmt->bindValue(":itm_no", $param["itm_no"], PDO::PARAM_STR);
      $stmt->bindValue(":name1", $param["name1"], PDO::PARAM_STR);
      $stmt->bindValue(":std_min", $param["std_min"], PDO::PARAM_STR);
      $stmt->bindValue(":std_max", $param["std_max"], PDO::PARAM_STR);
      $stmt->bindValue(":std_uom", $param["std_uom"], PDO::PARAM_STR);
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
    }
    return $return;
  }
  
  function delete($id) {
    $return = array();
    $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
    $sql = "DELETE FROM qas_ant.m_ckitm WHERE itm_id = '$id' "; 
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