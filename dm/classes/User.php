<?php

class User
{
  //put your code here
  public function getById($usrid)
  {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT * FROM m_user a WHERE UPPER(a.usrid) = :usrid";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":usrid", strtoupper($usrid), PDO::PARAM_STR);
    if ($stmt->execute()) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $return = $row;
      }
    }
    $stmt = null;
    $conn = null;
    return $return;
  }

  public function getList()
  {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT a.*, (select string_agg(roleid, ' , ') from m_user_role where usrid = a.usrid and app_id = '" . APP . "') as roles FROM m_user a";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute()) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if ($row["usrid"] == "ADMIN" || $row["usrid"] == "MASTER") {
          // jangan tampilkan user id admin/master
        } else {
          if ($row["stats"] == "A") {
            $row["status_text"] = "Active";
          } else {
            $row["status_text"] = "Inactive";
          }
          $return[] = $row;
        }
      }
    }
    $stmt = null;
    $conn = null;
    return $return;
  }

  public function login($userid, $userpw)
  {
    $return = array();
    if (empty($userid) || empty($userpw)) {
      $return["status"] = false;
      $return["message"] = "Parameter empty";
    } else {
      $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
      $sql = "SELECT * FROM m_user a WHERE UPPER(a.usrid) = :usrid";
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(":usrid", strtoupper($userid), PDO::PARAM_STR);
      if ($stmt->execute()) {
        $hashpass = null;
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          $hashpass = $row["usrpw"];

          if (password_verify($userpw, $hashpass)) {
            //check if user is inactive
            if ($row["stats"] == "A") {
              $return["status"] = true;
              $return["data"] = $row;
            } else {
              $return["status"] = false;
              $return["message"] = "Account disabled, please contact System Administrator";
            }
          } else {
            $return["status"] = false;
            $return["message"] = "Wrong password!";
          }
        } else {
          $return["status"] = false;
          $return["message"] = "User Not Found!";
        }
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

  public function insert($data = array())
  {
    $return = array();
    if (empty($data)) {
      $return["status"] = false;
      $return["message"] = "Data Empty";
    } else {
      $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
      $sql = "INSERT INTO m_user (usrid,usrpw,name1,phone,stats,crt_by,crt_dt,lifnr) "
        . "values (:usrid,:usrpw,:name1,:phone,'A',:crt_by,CURRENT_TIMESTAMP,:lifnr)";
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(":usrid", strtoupper($data["usrid"]), PDO::PARAM_STR);
      $stmt->bindValue(":usrpw", password_hash($data["usrpw"], PASSWORD_DEFAULT), PDO::PARAM_STR);
      $stmt->bindValue(":name1", $data["name1"], PDO::PARAM_STR);
      $stmt->bindValue(":phone", $data["phone"], PDO::PARAM_STR);
      $stmt->bindValue(":crt_by", strtoupper($data["crt_by"]), PDO::PARAM_STR);
      $stmt->bindValue(":lifnr", $data["lifnr"], PDO::PARAM_STR);

      if ($stmt->execute()) {
        $return["status"] = true;
        if (!empty($data["user_role"])) {
          $this->insertUserRole($data["usrid"], $data["user_role"]);
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

  public function update($data = array())
  {
    $return = array();
    if (empty($data["usrid"])) {
      $return["status"] = false;
      $return["message"] = "ID of User data blank";
    } else {
      $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
      $sql = "UPDATE m_user SET usrid = '" . strtoupper($data["usrid"]) . "' ";
      if (!empty($data["name1"])) {
        $sql .= " , name1 = '" . $data["name1"] . "' ";
      }
      if (!empty($data["usrpw"])) {
        $sql .= " , usrpw = '" . password_hash($data["usrpw"], PASSWORD_DEFAULT) . "' ";
      }
      if (!empty($data["stats"])) {
        $sql .= " , stats = '" . $data["stats"] . "' ";
      }

      $sql .= " , lifnr = '" . $data["lifnr"] . "' , phone='" . $data["phone"] . "', chg_by = '" . $data["chg_by"] . "', chg_dt = CURRENT_TIMESTAMP ";
      $sql .= " WHERE usrid = '" . strtoupper($data["usrid"]) . "'";
      $stmt = $conn->prepare($sql);

      if ($stmt->execute()) {
        $return["status"] = true;
        $this->deleteUserRole($data["usrid"]);
        if (!empty($data["user_role"])) {
          $this->insertUserRole($data["usrid"], $data["user_role"]);
        }
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

  public function updateProfile($data = array())
  {
    $return = array();
    if (empty($data["usrid"])) {
      $return["status"] = false;
      $return["message"] = "ID of User data blank";
    } else {
      $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
      $sql = "UPDATE m_user SET usrid = '" . strtoupper($data["usrid"]) . "' ";
      if (!empty($data["name1"])) {
        $sql .= " , name1 = '" . $data["name1"] . "' ";
      }
      if (!empty($data["usrpw"])) {
        $sql .= " , usrpw = '" . password_hash($data["usrpw"], PASSWORD_DEFAULT) . "' ";
      }
      $sql .= " , chg_by = '" . $data["chg_by"] . "', chg_dt = CURRENT_TIMESTAMP, phone='" . $data["phone"] . "' ";
      $sql .= " WHERE usrid = '" . strtoupper($data["usrid"]) . "'";
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

  public function insertUserRole($userid, $data)
  {
    $return = array();
    if (empty($userid) || empty($data)) {
      $return["status"] = false;
      $return["msg"] = "One of Parameter Empty";
    } else {
      $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
      $sql = "INSERT INTO m_user_role (usrid, roleid, app_id) VALUES ";
      $arr_insert = array();
      $userid = strtoupper(trim($userid));
      foreach ($data as $role) {
        $role = trim($role);
        $arr_insert[] = "('$userid','$role','" . APP . "')";
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

  public function deleteUserRole($userid)
  {
    $return = array();
    if (empty($userid)) {
      $return["status"] = false;
      $return["msg"] = "ID Empty";
    } else {
      $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
      $sql = "DELETE FROM m_user_role WHERE UPPER(usrid) = '" . strtoupper($userid) . "' AND app_id = '" . APP . "'";
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

  public function getMenuByUser($usrid)
  {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "select distinct a.menuid, b.name1, b.groupid, b.sort1, c.usrid 
            from m_role_menu a 
            inner join m_menu b on b.menuid = a.menuid AND b.app_id = '" . APP . "' 
            inner join m_user_role c on c.roleid = a.roleid AND c.app_id = '" . APP . "' 
            where UPPER(c.usrid) = '" . strtoupper($usrid) . "' AND a.app_id = '" . APP . "' 
            ORDER by sort1 ASC";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute()) {
      $menu_temp = null;
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $return[] = $row;
      }
    }
    $stmt = null;
    $conn = null;
    return $return;
  }

  public function getMenuGroupByUser($usrid)
  {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "select pval1 as groupid, pval2 as groupdsc from m_param 
            where pid = 'MENUGROUP' AND pval3 = '" . APP . "' and pval1 in (select b.groupid 
            from m_role_menu a 
            inner join m_menu b on b.menuid = a.menuid AND b.app_id = '" . APP . "' 
            inner join m_user_role c on c.roleid = a.roleid AND c.app_id = '" . APP . "' 
            where UPPER(c.usrid) = '" . strtoupper($usrid) . "' ORDER by seq ASC) 
           ";
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

  public function getUserRole($id)
  {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT * FROM m_user_role WHERE UPPER(usrid) = :id and app_id = '" . APP . "' ";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":id", strtoupper($id), PDO::PARAM_STR);
    if ($stmt->execute()) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $return[] = $row["roleid"];
      }
    }
    $stmt = null;
    $conn = null;
    return $return;
  }

  public function isExist($usrid)
  {
    $return = false;
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT count(*) as cnt FROM m_user WHERE UPPER(usrid) = :id";
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

  /*public function getVendorID($id) {
    $return = null;
    $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
    $sql = "SELECT lifnr FROM m_user a WHERE UPPER(a.usrid) = :usrid";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":usrid", strtoupper($id), PDO::PARAM_STR);
    if($stmt->execute()) {
      while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
        $return = $row["lifnr"];
      }
    }
    $stmt = null;
    $conn = null;
    return $return;
  }*/
}
