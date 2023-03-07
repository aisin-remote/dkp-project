<?php 
class Menu {
  function getList() {
    $return = array();
    $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
    $sql = "SELECT a.*, b.pval2 as group_name FROM m_menu a 
            INNER JOIN m_param b ON b.pid = 'MENUGROUP' and b.pval3 = '".APP."' and b.pval1 = a.groupid 
            WHERE a.app_id = '".APP."'";
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
    $sql = "SELECT * FROM m_menu WHERE UPPER(menuid) = :id AND app_id = '".APP."'";
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
  
  function insert($param) {
    $return = array();
    if(empty($param)) {
      $return["status"] = false;
      $return["message"] = "Data Empty";
    } else {
      $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
      $sql = "INSERT INTO m_menu (menuid,name1,groupid,sort1,app_id) values (:menuid,:name1,:groupid,:sort1,'".APP."') ";
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(":menuid", strtoupper($param["menuid"]), PDO::PARAM_STR);
      $stmt->bindValue(":name1", $param["name1"], PDO::PARAM_STR);
      $stmt->bindValue(":groupid", $param["groupid"], PDO::PARAM_STR);
      $stmt->bindValue(":sort1", $param["sort1"], PDO::PARAM_STR);
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
  
  function update($param) {
    $return = array();
    if(empty($param)) {
      $return["status"] = false;
      $return["message"] = "Data Empty";
    } else {
      $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
      $sql = "UPDATE m_menu SET name1 = :name1, groupid = :groupid, sort1 = :sort1 "
              . "WHERE menuid = :menuid AND app_id = '".APP."'";
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(":menuid", strtoupper($param["menuid"]), PDO::PARAM_STR);
      $stmt->bindValue(":name1", $param["name1"], PDO::PARAM_STR);
      $stmt->bindValue(":groupid", $param["groupid"], PDO::PARAM_STR);
      $stmt->bindValue(":sort1", $param["sort1"], PDO::PARAM_STR);
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
  
  function delete($menuid) {
    $return = array();
    $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
    $sql = "DELETE FROM m_role_menu WHERE app_id = '".APP."' AND menuid = '$menuid' "; 
    $stmt = $conn->prepare($sql);
    if($stmt->execute()) {
      $sql = "DELETE FROM m_menu WHERE app_id = '".APP."' AND menuid = '$menuid'";
      $stmt = $conn->prepare($sql);
      if($stmt->execute()) {
        $return["status"] = true;
      } else {
        $error = $stmt->errorInfo();
        $return["status"] = false;
        $return["message"] = trim(str_replace("\n", " ", $error[2]));
        error_log($error[2]);
        return $return;
      }      
    } else {
      $error = $stmt->errorInfo();
      $return["status"] = false;
      $return["message"] = trim(str_replace("\n", " ", $error[2]));
      error_log($error[2]);
      return $return;
    }
    $stmt = null;
    $conn = null;
    return $return;
  }
  
  function getMenuGroup($group = array()) {
    $return = array();
    $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
    $sql = "SELECT pval1 as groupid, pval2 as groupdsc FROM m_param WHERE pid = 'MENUGROUP' AND pval3 = '".APP."'";
    if(!empty($group)) {
      $sql .= " AND pval1 = ('".implode(",",$group)."') ";
    }
    $sql .= " ORDER BY seq ASC ";
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
  
  function getListApi() {
    $return = array();
    $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
    $sql = "SELECT api_id FROM m_api WHERE app_id = '".APP."'";
    $stmt = $conn->prepare($sql);
    if($stmt->execute()) {
      while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
        $return[] = $row["api_id"];
      }
    }
    $stmt = null;
    $conn = null;
    return $return;
  }
}
?>