<?php

class PergantianPart
{

  public function getList()
  {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT a.*, b.model_id, b.group_id, b.dies_no, TO_CHAR(a.pcdat, 'DD-MM-YYYY') as pcdate FROM t_dm_pc_h a "
      . "INNER JOIN m_dm_dies_asset b ON b.dies_id = CAST(a.dies_id as bigint) "
      . "WHERE 1=1 ";
    $sql .= " ORDER by pchid ASC ";
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
    $sql = "SELECT a.*, b.group_id, b.model_id FROM t_dm_pc_h a "
      . "INNER JOIN m_dm_dies_asset b ON b.dies_id = CAST(a.dies_id as bigint) "
      . "WHERE pchid = :pchid ";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":pchid", $id, PDO::PARAM_STR);

    if ($stmt->execute()) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $return = $row;
      }
    }
    $stmt = null;
    $conn = null;
    return $return;
  }

  public function getItem($id)
  {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT * from t_dm_pc_i WHERE pchid = :pchid";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":pchid", $id, PDO::PARAM_STR);

    if ($stmt->execute()) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $return[] = $row;
      }
    }
    $stmt = null;
    $conn = null;
    return $return;
  }

  public function getPartList()
  {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT a.* FROM m_dm_dies_part a "
      . "WHERE 1=1 ";

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
      $sql = "INSERT INTO t_dm_pc_h (dies_id, desc1, pcdat, crt_by, crt_dt) "
        . "values (:dies_id, :desc1, :pcdat, :crt_by, CURRENT_TIMESTAMP )";
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(":dies_id", $param["dies_id"], PDO::PARAM_STR);
      $stmt->bindValue(":desc1", $param["desc1"], PDO::PARAM_STR);
      $stmt->bindValue(":pcdat", $param["pcdat"], PDO::PARAM_STR);
      $stmt->bindValue(":crt_by", $param["crt_by"], PDO::PARAM_STR);

      if ($stmt->execute()) {
        $pchid = $conn->lastInsertId();
        $this->insertItem($pchid, $param["item"]);
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
      $sql = "UPDATE t_dm_pc_h SET dies_id = :dies_id, desc1 = :desc1, pcdat = :pcdat, chg_by = :chg_by, chg_dt = CURRENT_TIMESTAMP "
        . "WHERE pchid = :pchid ";
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(":pchid", $param["pchid"], PDO::PARAM_STR);
      $stmt->bindValue(":dies_id", $param["dies_id"], PDO::PARAM_STR);
      $stmt->bindValue(":desc1", $param["desc1"], PDO::PARAM_STR);
      $stmt->bindValue(":pcdat", $param["pcdat"], PDO::PARAM_STR);
      $stmt->bindValue(":chg_by", $param["chg_by"], PDO::PARAM_STR);

      if ($stmt->execute()) {
        $pchid = $param["pchid"];
        $this->insertItem($pchid, $param["item"]);
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

  public function insertItem($pchid, $item)
  {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $conn->exec("DELETE FROM t_dm_pc_i WHERE pchid = '$pchid'");

    $sql = "INSERT INTO t_dm_pc_i (pchid, part_id, part_text) VALUES ";
    foreach ($item as $row => $val) {
      $arr_insert[] = "('$pchid','$row','$val')";
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
    return $return;
  }
}
