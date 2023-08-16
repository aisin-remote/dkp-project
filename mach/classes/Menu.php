<?php 
class Menu {
  function getList() {
    $return = array();
    $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
    $sql = "SELECT * FROM mach.m_menu WHERE app_id = 'AISIN_MACH'";
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
    $sql = "SELECT * FROM mach.m_menu WHERE UPPER(menuid) = :id AND app_id = 'AISIN_MACH'";
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
      $sql = "INSERT INTO mach.m_menu (menuid,name1,groupid,sort1,app_id) values (:menuid,:name1,:groupid,:sort1,'AISIN_MACH') ";
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
      $sql = "UPDATE mach.m_menu SET name1 = :name1, groupid = :groupid, sort1 = :sort1 "
              . "WHERE menuid = :menuid AND app_id = 'AISIN_MACH'";
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
    $sql = "DELETE FROM mach.m_role_menu WHERE app_id = 'AISIN_MACH' AND menuid = '$menuid' "; 
    $stmt = $conn->prepare($sql);
    if($stmt->execute()) {
      $sql = "DELETE FROM mach.m_menu WHERE app_id = 'AISIN_MACH' AND menuid = '$menuid'";
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
    $sql = "SELECT pval1 as groupid, pval2 as groupdsc FROM mach.m_param WHERE pid = 'MENUGROUP' AND pval3 = 'AISIN_MACH'";
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
    $sql = "SELECT api_id FROM mach.m_api WHERE app_id = 'AISIN_MACH'";
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