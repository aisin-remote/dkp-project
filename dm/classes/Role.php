<?php

class Role
{
  //put your code here
  public function getList()
  {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT * FROM m_role WHERE app_id = '" . APP . "'";
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

  public function getById($id)
  {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT * FROM m_role WHERE UPPER(roleid) = :id AND app_id = '" . APP . "'";
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

  public function insert($param = array())
  {
    $return = array();
    if (empty($param)) {
      $return["status"] = false;
      $return["message"] = "Data Empty";
    } else {
      $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
      $sql = "INSERT INTO m_role (roleid,name1,crt_by,crt_dt,app_id) values (:roleid,:name1,:crt_by,CURRENT_TIMESTAMP,'" . APP . "') ";
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(":roleid", strtoupper($param["roleid"]), PDO::PARAM_STR);
      $stmt->bindValue(":name1", $param["name1"], PDO::PARAM_STR);
      $stmt->bindValue(":crt_by", strtoupper($param["crt_by"]), PDO::PARAM_STR);
      if ($stmt->execute()) {
        $return["status"] = true;
        if (!empty($param["rolemenu"])) {
          $this->insertRoleMenu($param["roleid"], $param["rolemenu"]);
        }
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
      $sql = "UPDATE m_role SET name1 = :name1, chg_by = :chg_by, chg_dt = CURRENT_TIMESTAMP "
        . "WHERE roleid = :roleid AND app_id = '" . APP . "'";
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(":roleid", strtoupper($param["roleid"]), PDO::PARAM_STR);
      $stmt->bindValue(":name1", $param["name1"], PDO::PARAM_STR);
      $stmt->bindValue(":chg_by", strtoupper($param["chg_by"]), PDO::PARAM_STR);
      if ($stmt->execute()) {
        $return["status"] = true;
        $this->deleteRoleMenu($param["roleid"]);
        if (!empty($param["rolemenu"])) {
          $this->insertRoleMenu($param["roleid"], $param["rolemenu"]);
        }
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

  public function insertRoleMenu($roleid, $menus = array())
  {
    $return = array();
    if (empty($roleid) || empty($menus)) {
      $return["status"] = false;
      $return["msg"] = "One of Parameter Empty";
    } else {
      $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
      $sql = "INSERT INTO m_role_menu (roleid, menuid, app_id) VALUES ";
      $arr_insert = array();
      $userid = strtoupper($userid);
      foreach ($menus as $menu) {
        $arr_insert[] = "('$roleid','$menu','" . APP . "')";
      }
      $sql .= implode(",", $arr_insert);
      $stmt = $conn->prepare($sql);
      if ($stmt->execute()) {
        $return["status"] = true;
      } else {
        $return["status"] = false;
        $error = $stmt->errorInfo();
        $return["message"] = trim(str_replace("\n", " ", $error[2]));
      }
      $stmt = null;
      $conn = null;
    }
    return $return;
  }

  public function deleteRoleMenu($roleid)
  {
    $return = array();
    if (empty($roleid)) {
      $return["status"] = false;
      $return["msg"] = "ID Empty";
    } else {
      $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
      $sql = "DELETE FROM m_role_menu WHERE roleid = '" . strtoupper($roleid) . "' AND app_id = '" . APP . "'";
      $stmt = $conn->prepare($sql);
      if ($stmt->execute()) {
        $return["status"] = true;
      } else {
        $return["status"] = false;
        $error = $stmt->errorInfo();
        $return["message"] = trim(str_replace("\n", " ", $error[2]));
      }
      $stmt = null;
      $conn = null;
    }
    return $return;
  }

  public function getRoleMenu($id)
  {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT * FROM m_role_menu WHERE UPPER(roleid) = :id AND app_id = '" . APP . "'";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":id", strtoupper($id), PDO::PARAM_STR);
    if ($stmt->execute()) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $return[] = $row["menuid"];
      }
    }
    $stmt = null;
    $conn = null;
    return $return;
  }
}
