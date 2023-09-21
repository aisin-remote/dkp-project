<?php

class ChecklistGroup {
  //put your code here
  function getList() {
    $return = array();
    $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
    $sql = "SELECT * FROM qas_ant.m_ckgrp ORDER BY grp_id ASC ";
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
    $sql = "SELECT * FROM qas_ant.m_ckgrp WHERE grp_id = :id ";
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
      $sql = "INSERT INTO qas_ant.m_ckgrp (grp_id, grp_no, name1) values (:grp_id, :grp_no, :name1) ";
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(":grp_id", strtoupper($param["grp_id"]), PDO::PARAM_STR);
      $stmt->bindValue(":grp_no", $param["grp_no"], PDO::PARAM_STR);
      $stmt->bindValue(":name1", $param["name1"], PDO::PARAM_STR);
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
      $sql = "UPDATE qas_ant.m_ckgrp SET grp_no=:grp_no, name1=:name1 "
              . "WHERE grp_id = :grp_id ";
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(":grp_id", strtoupper($param["grp_id"]), PDO::PARAM_STR);
      $stmt->bindValue(":grp_no", $param["grp_no"], PDO::PARAM_STR);
      $stmt->bindValue(":name1", $param["name1"], PDO::PARAM_STR);
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
    $sql = "DELETE FROM qas_ant.m_ckgrp WHERE grp_id = '$id' "; 
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