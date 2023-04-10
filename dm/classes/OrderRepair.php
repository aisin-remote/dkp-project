<?php

class OrderRepair
{
  //put your code here
  public function getList($stat = null)
  {
    $return = array();
    // echo gettype($stat);
    // die();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT a.*, b.model_id, b.group_id, b.dies_no, TO_CHAR(a.ori_dt, 'DD-MM-YYYY') as ori_date, c.pval2 as ori_type, a.ori_a3, a.stats FROM t_dm_ori a "
      . "INNER JOIN m_dm_dies_asset b ON b.dies_id = a.dies_id  "
      . "INNER JOIN m_param c ON c.pid = 'ORI_TYPE' and c.pval1 = a.ori_typ "
      . "WHERE 1=1 ";
    if ($stat != null) {
      $sql .= "AND a.stats = '$stat' ";
    }
    $sql .= " ORDER by a.ori_id DESC ";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute()) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if ($row["stats"] == '1') {
          $row["stats"] = "Completed";
        } else {
          $row["stats"] = "Uncompleted";
        }
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
    $sql = "SELECT a.*, b.group_id, b.model_id FROM t_dm_ori a "
      . "INNER JOIN m_dm_dies_asset b ON b.dies_id = a.dies_id "
      . "WHERE ori_id = :id ";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":id", $id, PDO::PARAM_STR);

    if ($stmt->execute()) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $return = $row;
      }
    }
    $stmt = null;
    $conn = null;
    return $return;
  }

  public function getType()
  {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT pval1 as ori_typ, pval2 as name1 FROM m_param WHERE pid = 'ORI_TYPE' ORDER by seq asc";
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
      // print_r($param);
      // die();
      $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
      if ($param["status"] == 'on') {
        $sql2 = "UPDATE m_dm_dies_asset SET zona_id = '" . $param["zona2"] . "' WHERE dies_id = " . $param["dies_id"] . " ";
      } else {
        $sql2 = "UPDATE m_dm_dies_asset SET zona_id = '" . $param["zona1"] . "' WHERE dies_id = " . $param["dies_id"] . " ";
      }
      $sql = "INSERT INTO t_dm_ori (dies_id, ori_typ, ori_dt, remarks, ori_doc, ori_a3, stats, zona1, zona2, crt_dt, crt_by) "
        . "values (:dies_id, :ori_typ, :ori_dt, :remarks, :ori_doc, :ori_a3, :stats, :zona1, :zona2, CURRENT_TIMESTAMP, :crt_by )";
      $stmt = $conn->prepare($sql);
      $stmt2 = $conn->prepare($sql2);
      $stmt->bindValue(":dies_id", $param["dies_id"], PDO::PARAM_STR);
      $stmt->bindValue(":ori_typ", $param["ori_typ"], PDO::PARAM_STR);
      $stmt->bindValue(":ori_dt", $param["ori_dt"], PDO::PARAM_STR);
      $stmt->bindValue(":remarks", $param["remarks"], PDO::PARAM_STR);
      $stmt->bindValue(":ori_doc", $param["ori_doc"], PDO::PARAM_STR);
      $stmt->bindValue(":ori_a3", $param["ori_a3"], PDO::PARAM_STR);
      $stmt->bindValue(":stats", $param["stats"], PDO::PARAM_STR);
      $stmt->bindValue(":zona1", $param["zona1"], PDO::PARAM_STR);
      $stmt->bindValue(":zona2", $param["zona2"], PDO::PARAM_STR);
      $stmt->bindValue(":crt_by", $param["crt_by"], PDO::PARAM_STR);

      if ($stmt->execute() && $stmt2->execute()) {
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
      if ($param["status"] == 'on') {
        $sql2 = "UPDATE m_dm_dies_asset SET zona_id = '" . $param["zona2"] . "' WHERE dies_id = " . $param["dies_id"] . " ";
      } else {
        $sql2 = "UPDATE m_dm_dies_asset SET zona_id = '" . $param["zona1"] . "' WHERE dies_id = " . $param["dies_id"] . " ";
      }
      $sql = "UPDATE t_dm_ori SET dies_id=:dies_id, ori_typ=:ori_typ, ori_dt=:ori_dt, remarks=:remarks, ori_doc=:ori_doc, ori_a3 = :ori_a3, stats = :stats, zona1 = :zona1, zona2 = :zona2, chg_dt=CURRENT_TIMESTAMP, chg_by=:chg_by "
        . "WHERE ori_id = :ori_id ";
      $stmt = $conn->prepare($sql);
      $stmt2 = $conn->prepare($sql2);
      $stmt->bindValue(":ori_id", $param["ori_id"], PDO::PARAM_STR);
      $stmt->bindValue(":dies_id", $param["dies_id"], PDO::PARAM_STR);
      $stmt->bindValue(":ori_typ", $param["ori_typ"], PDO::PARAM_STR);
      $stmt->bindValue(":ori_dt", $param["ori_dt"], PDO::PARAM_STR);
      $stmt->bindValue(":remarks", $param["remarks"], PDO::PARAM_STR);
      $stmt->bindValue(":ori_doc", $param["ori_doc"], PDO::PARAM_STR);
      $stmt->bindValue(":ori_a3", $param["ori_a3"], PDO::PARAM_STR);
      $stmt->bindValue(":stats", $param["stats"], PDO::PARAM_STR);
      $stmt->bindValue(":zona1", $param["zona1"], PDO::PARAM_STR);
      $stmt->bindValue(":zona2", $param["zona2"], PDO::PARAM_STR);
      $stmt->bindValue(":chg_by", $param["chg_by"], PDO::PARAM_STR);

      if ($stmt->execute() && $stmt2->execute()) {
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
}