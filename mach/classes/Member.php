<?php

class Member
{

  public function getById($id)
  {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT * FROM mach.m_prd_operator WHERE empid = :id AND app_id = 'AISIN_MACH' ";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":id", strtoupper($id), PDO::PARAM_STR);
    if ($stmt->execute()) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $return = $row;
      }
    }
    $stmt = null;
    $conn = null;
    return $return;
  }

  public function getList($role = null, $stats = null)
  {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT a.*, b.pval2 as role_name FROM mach.m_prd_operator a "
      . "LEFT JOIN mach.m_param b ON b.pid = 'OPR_ROLE' and b.pval1 = a.role1 "
      . "WHERE a.app_id = 'AISIN_MACH' ";
    if (!empty($role)) {
      $sql .= " AND a.role1 = '$role' ";
    }

    if (!empty($stats)) {
      $sql .= " AND a.stats = '$stats' ";
    }
    $sql .= " ORDER by empid ASC ";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute()) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if ($row["stats"] == "A") {
          $row["stats"] = "Active";
        } elseif ($row["stats"] == "I") {
          $row["stats"] = "Inactive";
        }
        $return[] = $row;
      }
    }
    $stmt = null;
    $conn = null;
    return $return;
  }

  public function getListRole()
  {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT * FROM mach.m_param "
      . "WHERE pid = 'OPR_ROLE' ";

    $sql .= " ORDER by seq ASC ";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute()) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $return[] = $row;
      }
    }
    $stmt = null;
    $conn = null;
    return $return;
  }

  public function insert($param = array())
  {
    $return = array();
    if (empty($param)) {
      $return["status"] = false;
      $return["message"] = "Data Empty";
    } else {
      $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
      $sql = "INSERT INTO mach.m_prd_operator (empid, name1, role1, stats, app_id) "
        . "values (:empid, :name1, :role1, 'A', 'AISIN_MACH') ";
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(":empid", strtoupper(trim($param["empid"])), PDO::PARAM_STR);
      $stmt->bindValue(":name1", $param["name1"], PDO::PARAM_STR);
      $stmt->bindValue(":role1", $param["role1"], PDO::PARAM_STR);

      if ($stmt->execute()) {
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

  public function update($param = array())
  {
    $return = array();
    if (empty($param)) {
      $return["status"] = false;
      $return["message"] = "Data Empty";
    } else {
      $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
      $sql = "UPDATE mach.m_prd_operator SET empid = :empid, name1 = :name1, role1 = :role1 "
        . "WHERE empid = :empid AND app_id = 'AISIN_MACH' ";
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(":empid", strtoupper($param["empid"]), PDO::PARAM_STR);
      $stmt->bindValue(":name1", $param["name1"], PDO::PARAM_STR);
      $stmt->bindValue(":role1", $param["role1"], PDO::PARAM_STR);

      if ($stmt->execute()) {
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

  public function updateStatus($extract_id)
  {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "UPDATE mach.m_prd_operator SET stats = "
      . "(CASE "
      . "WHEN stats = 'A' THEN 'I' "
      . "WHEN stats = 'I' THEN 'A' "
      . "ELSE stats "
      . "END) "
      . "WHERE empid IN('$extract_id') AND app_id = 'AISIN_MACH' ";

    $stmt = $conn->prepare($sql);
    if ($stmt->execute()) {
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

  public function isExist($usrid)
  {
    $return = false;
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT count(*) as cnt FROM mach.m_prd_operator WHERE UPPER(empid) = :id AND app_id = 'AISIN_MACH' ";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":id", strtoupper($usrid), PDO::PARAM_STR);
    $count = 0;
    if ($stmt->execute()) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $count = intval($row["cnt"]);
      }
    }
    if ($count > 0) {
      $return = true;
    }
    $stmt = null;
    $conn = null;
    return $return;
  }
}