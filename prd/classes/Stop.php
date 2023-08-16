<?php

class Stop
{

  public function getById($id)
  {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT * FROM m_prd_stop_reason_action WHERE srna_id = :id AND app_id = '" . APP . "' ";
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

  public function getList($type = null, $type2 = null)
  {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT a.*, b.pval2 as type1_text, c.pval2 as type2_text FROM m_prd_stop_reason_action a "
      . "LEFT JOIN m_param b ON b.pval1 = a.type1 AND b.pid = 'SRNA_TYPE' "
      . "LEFT JOIN m_param c ON c.pval1 = a.type2 AND c.pid = 'SRNA_TYPE2' ";
    if (!empty($type)) {
      $sql .= " AND a.type1 = '$type' ";
    }
    if (!empty($type2)) {
      $sql .= " AND a.type2 = '$type2' ";
    }
    $sql .= " ORDER by a.srna_id ASC ";
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

  public function getListType()
  {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT pval1 as type, pval2 as name1 FROM m_param "
      . "WHERE pid = 'SRNA_TYPE' ";

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

  public function getListType2()
  {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT pval1 as type, pval2 as name1 FROM m_param "
      . "WHERE pid = 'SRNA_TYPE2' ";

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
      $sql = "INSERT INTO m_prd_stop_reason_action (srna_id, type1, type2, name1, app_id, type3, type4) "
        . "values (:srna_id, :type1, :type2, :name1, '" . APP . "',:type3, :type4) ";
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(":srna_id", strtoupper(trim($param["srna_id"])), PDO::PARAM_STR);
      $stmt->bindValue(":type1", $param["type1"], PDO::PARAM_STR);
      $stmt->bindValue(":type2", $param["type2"], PDO::PARAM_STR);
      $stmt->bindValue(":name1", $param["name1"], PDO::PARAM_STR);
      $stmt->bindValue(":type3", $param["type3"], PDO::PARAM_STR);
      $stmt->bindValue(":type4", $param["type4"], PDO::PARAM_STR);
      /*$stmt->bindValue(":time1", $param["time1"], PDO::PARAM_STR);
      $stmt->bindValue(":time2", $param["time2"], PDO::PARAM_STR);
      $stmt->bindValue(":time3", $param["time3"], PDO::PARAM_STR);
      $stmt->bindValue(":time1l", $param["time1l"], PDO::PARAM_STR);
      $stmt->bindValue(":time3l", $param["time3l"], PDO::PARAM_STR);
      $stmt->bindValue(":timen", $param["timen"], PDO::PARAM_STR);*/

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
      $sql = "UPDATE m_prd_stop_reason_action SET type1 = :type1, type2 = :type2, type3 = :type3, type4 = :type4, name1 = :name1, app_id = '" . APP . "' "
        . "WHERE srna_id = :srna_id AND app_id = '" . APP . "'";
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(":srna_id", $param["srna_id"], PDO::PARAM_STR);
      $stmt->bindValue(":type1", $param["type1"], PDO::PARAM_STR);
      $stmt->bindValue(":type2", $param["type2"], PDO::PARAM_STR);
      $stmt->bindValue(":name1", $param["name1"], PDO::PARAM_STR);
      $stmt->bindValue(":type3", $param["type3"], PDO::PARAM_STR);
      $stmt->bindValue(":type4", $param["type4"], PDO::PARAM_STR);
      /*$stmt->bindValue(":time1", $param["time1"], PDO::PARAM_STR);
      $stmt->bindValue(":time2", $param["time2"], PDO::PARAM_STR);
      $stmt->bindValue(":time3", $param["time3"], PDO::PARAM_STR);
      $stmt->bindValue(":time1l", $param["time1l"], PDO::PARAM_STR);
      $stmt->bindValue(":time3l", $param["time3l"], PDO::PARAM_STR);
      $stmt->bindValue(":timen", $param["timen"], PDO::PARAM_STR);*/

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

  public function getPlannedStopByShift($shift_id)
  {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT * FROM m_prd_shift_stop WHERE shift_id = '$shift_id' AND app_id = '" . APP . "' ";

    $sql .= " ORDER BY shift_id ASC, time_id ASC, srna_id ASC  ";
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

  public function insertMass($param = array()) {
    // print_r("<pre>" . print_r($param, true) . "</pre>");;
    // die();
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "INSERT into m_prd_stop_reason_action (srna_id, type1, type2, name1, app_id, type3)
    values ('".$param["srna_id"]."', '".$param["type1"]."', '".$param["type2"]."', '".$param["name1"]."', '".$param["app_id"]."', '".$param["type3"]."')";
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

?>