<?php

class Dies
{

  public function getListLine()
  {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT * FROM m_prd_line ";
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

  public function getDiesLine($id)
  {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT * FROM m_prd_dies_model_line WHERE UPPER(model_id) = :id";
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

  public function getModelById($id)
  {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT * FROM m_dm_dies_model WHERE model_id = :id";
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

  public function getListModel()
  {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT a.*, b.name1 as line_name, (select string_agg(line_id, ', ') from m_prd_dies_model_line where model_id = a.model_id) as lines FROM m_dm_dies_model a "
      . "LEFT JOIN m_prd_line b ON b.line_id = a.line_id "
      . "WHERE 1=1 ";

    $sql .= " ORDER by model_id ASC ";
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

  public function getDiesGroup()
  {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT * FROM m_param "
      . "WHERE pid = 'DIES_GROUP' ";

    $sql .= " ORDER by pid ASC ";
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

  public function getDiesModel($line_id = null, $group = null)
  {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT * FROM m_dm_dies_model "
      . "WHERE 1=1 ";
    if (!empty($line_id)) {
      $sql .= " AND line_id = '$line_id' ";
    }

    if (!empty($group)) {
      $sql .= " AND group_id = '$group' ";
    }
    $sql .= " ORDER by model_id ASC ";
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

  public function insertModel($param = array())
  {
    $return = array();
    if (empty($param)) {
      $return["status"] = false;
      $return["message"] = "Data Empty";
    } else {
      $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
      $sql = "INSERT INTO m_dm_dies_model (model_id, name1, group_id) "
        . "values (:model_id, :name1, :group_id) ";
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(":model_id", strtoupper(trim($param["model_id"])), PDO::PARAM_STR);
      $stmt->bindValue(":name1", $param["name1"], PDO::PARAM_STR);
      $stmt->bindValue(":group_id", $param["group_id"], PDO::PARAM_STR);

      if ($stmt->execute()) {
        $return["status"] = true;
        // var_dump($param["lines"]);
        // die();
        if (!empty($param["lines"])) {
          $this->insertDiesLine($param["model_id"], $param["lines"]);
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

  public function updateModel($param = array())
  {
    $return = array();
    if (empty($param)) {
      $return["status"] = false;
      $return["message"] = "Data Empty";
    } else {
      $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
      $sql = "UPDATE m_dm_dies_model SET name1 = :name1, group_id = :group_id "
        . "WHERE model_id = :model_id";
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(":model_id", strtoupper($param["model_id"]), PDO::PARAM_STR);
      $stmt->bindValue(":name1", $param["name1"], PDO::PARAM_STR);
      $stmt->bindValue(":group_id", $param["group_id"], PDO::PARAM_STR);

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

  public function insertDiesLine($model_id, $param)
  {
    $return = array();
    if (empty($model_id) || empty($param)) {
      $return["status"] = false;
      $return["msg"] = "One of Parameter Empty";
    } else {
      $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
      $sql = "INSERT INTO m_prd_dies_model_line (model_id, line_id) VALUES ";
      $arr_insert = array();
      $model_id = strtoupper($model_id);
      foreach ($param as $line) {
        $arr_insert[] = "('$model_id','" . trim($line) . "')";
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

  public function getListDies($line = null, $model_id = null)
  {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT a.dies_id, a.dies_no, a.model_id, a.group_id, a.name1, b.line_id, c.name1 as line_name, a.stktot, a.stkrun, a.ewstk FROM m_dm_dies_asset a "
      . "INNER JOIN m_dm_dies_model b ON b.model_id = a.model_id "
      . "LEFT JOIN m_prd_line c ON c.line_id = b.line_id "
      . "WHERE 1=1 ";
    if (!empty($line)) {
      $sql .= " AND b.line_id = '$line' ";
    }

    if (!empty($model_id)) {
      $sql .= " AND a.model_id = '$model_id' ";
    }
    $sql .= " ORDER by dies_id ASC ";
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

  public function getDiesById($id)
  {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT * FROM m_dm_dies_asset WHERE dies_id = :id ";
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

  public function insertDies($param = array())
  {
    $return = array();
    if (empty($param)) {
      $return["status"] = false;
      $return["message"] = "Data Empty";
    } else {
      $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
      $sql = "INSERT INTO m_dm_dies_asset (dies_no, model_id, group_id, name1, ctsec, ewstk, img01, stktot, stkrun, stk2k, stk2k_stat, stk6k, stk6k_stat) "
        . "values (:dies_no, :model_id, :group_id, :name1, :ctsec, :ewstk, :img01, '0', '0', '0','N','0','N') ";
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(":dies_no", $param["dies_no"], PDO::PARAM_STR);
      $stmt->bindValue(":model_id", $param["model_id"], PDO::PARAM_STR);
      $stmt->bindValue(":group_id", $param["group_id"], PDO::PARAM_STR);
      $stmt->bindValue(":name1", $param["name1"], PDO::PARAM_STR);
      $stmt->bindValue(":ctsec", $param["ctsec"], PDO::PARAM_STR);
      $stmt->bindValue(":ewstk", $param["ewstk"], PDO::PARAM_STR);
      $stmt->bindValue(":img01", $param["img01"], PDO::PARAM_STR);
      $stmt->bindValue(":stktot", $param["stktot"], PDO::PARAM_STR);

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

  public function updateDies($param = array())
  {
    $return = array();
    if (empty($param)) {
      $return["status"] = false;
      $return["message"] = "Data Empty";
    } else {
      $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
      $sql = "UPDATE m_dm_dies_asset SET dies_no = :dies_no, model_id = :model_id, group_id = :group_id, name1 = :name1, ctsec = :ctsec, ewstk = :ewstk, img01 = :img01 "
        . "WHERE dies_id = :dies_id ";
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(":dies_id", $param["dies_id"], PDO::PARAM_STR);
      $stmt->bindValue(":dies_no", $param["dies_no"], PDO::PARAM_STR);
      $stmt->bindValue(":model_id", $param["model_id"], PDO::PARAM_STR);
      $stmt->bindValue(":group_id", $param["group_id"], PDO::PARAM_STR);
      $stmt->bindValue(":name1", $param["name1"], PDO::PARAM_STR);
      $stmt->bindValue(":ctsec", $param["ctsec"], PDO::PARAM_STR);
      $stmt->bindValue(":ewstk", $param["ewstk"], PDO::PARAM_STR);
      $stmt->bindValue(":img01", $param["img01"], PDO::PARAM_STR);

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

  public function updateDiesStatus($dies_id, $gstat)
  {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "UPDATE m_dm_dies_asset SET gstat = :gstat "
      . "WHERE dies_id = :dies_id ";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":dies_id", $param["dies_id"], PDO::PARAM_STR);
    $stmt->bindValue(":gstat", $param["gstat"], PDO::PARAM_STR);
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
