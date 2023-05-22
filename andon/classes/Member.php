<?php

class Member
{

  public function getById($id)
  {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT * FROM m_prd_operator WHERE empid = :id";
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
    $sql = "SELECT a.*, b.pval2 as role_name FROM m_prd_operator a "
      . "LEFT JOIN m_param b ON b.pid = 'OPR_ROLE' and b.pval1 = a.role1 "
      . "WHERE a.app_id = '" . APP . "' ";
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
    $sql = "SELECT * FROM m_param "
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
      $sql = "INSERT INTO m_prd_operator (empid, name1, role1, stats, app_id) "
        . "values (:empid, :name1, :role1, 'A', '" . APP . "') ";
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
      $sql = "UPDATE m_prd_operator SET empid = :empid, name1 = :name1, role1 = :role1 "
        . "WHERE empid = :empid";
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
    $sql = "UPDATE m_prd_operator SET stats = "
      . "(CASE "
      . "WHEN stats = 'A' THEN 'I' "
      . "WHEN stats = 'I' THEN 'A' "
      . "ELSE stats "
      . "END) "
      . "WHERE empid IN('$extract_id')";

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

  public function getListGroup()
  {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "select DISTINCT(a.group_id), a.line_id, b.name1 as line from m_group_operator a
    left join m_prd_line b on b.line_id = a.line_id ";
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

  public function getGroupById($line, $group)
  {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "select a.*, b.name1, c.name1 as line from m_group_operator a
    left join m_prd_operator b on b.empid = a.empid
    left join m_prd_line c on c.line_id = a.line_id
    where a.line_id = '$line' and a.group_id = '$group' ";
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

  public function insertGroup($param = array())
  {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "INSERT INTO m_group_operator (group_id, line_id, empid) values ";
    $arr_insert = array();
    foreach ($param["empid"] as $row) {
      $arr_insert[] = "('" . $param["group"] . "', '" . $param["line"] . "', '" . $row . "')";
    }
    $sql .= implode(",", $arr_insert);
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

  public function updateGroup($param = array())
  {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $conn->exec("DELETE FROM m_group_operator WHERE line_id = '" . $param["line1"] . "' AND group_id = '" . $param["group1"] . "'");
    $sql = "INSERT INTO m_group_operator (group_id, line_id, empid) values ";
    $arr_insert = array();
    foreach ($param["empid"] as $row) {
      $arr_insert[] = "('" . $param["group"] . "', '" . $param["line"] . "', '" . $row . "')";
    }
    $sql .= implode(",", $arr_insert);
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
}