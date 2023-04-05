<?php

class PergantianPart
{

  public function getList()
  {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT a.*, c.desc as zona_mt, d.desc as zona_park, b.model_id, b.group_id, b.dies_no, TO_CHAR(a.pcdat, 'DD-MM-YYYY') as pcdate FROM t_dm_pc_h a 
            INNER JOIN m_dm_dies_asset b ON b.dies_id = CAST(a.dies_id as bigint)
            left JOIN m_zona c ON c.zona_id = a.zona1
            left JOIN m_zona d ON d.zona_id = a.zona2
            WHERE 1=1 ";

    $sql .= " ORDER by pchid ASC ";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute()) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if ($row["status"] == "1") {
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
      // print_r($param["item"]);
      // die();
      $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
      if ($param["stats"] == 1) {
        $sql2 = "UPDATE m_dm_dies_asset SET zona_id = '" . $param["zona2"] . "' WHERE dies_id = " . $param["dies_id"] . " ";
      } else {
        $sql2 = "UPDATE m_dm_dies_asset SET zona_id = '" . $param["zona1"] . "' WHERE dies_id = " . $param["dies_id"] . " ";
      }
      $sql = "INSERT INTO t_dm_pc_h (dies_id, desc1, pcdat, crt_by, crt_dt, status, zona1, zona2) "
        . "values (:dies_id, :desc1, :pcdat, :crt_by, CURRENT_TIMESTAMP, '0', :zona1, :zona2 )";
      $stmt = $conn->prepare($sql);
      $stmt2 = $conn->prepare($sql2);
      $stmt->bindValue(":dies_id", $param["dies_id"], PDO::PARAM_STR);
      $stmt->bindValue(":desc1", $param["desc1"], PDO::PARAM_STR);
      $stmt->bindValue(":pcdat", $param["pcdat"], PDO::PARAM_STR);
      $stmt->bindValue(":crt_by", $param["crt_by"], PDO::PARAM_STR);
      $stmt->bindValue(":zona1", $param["zona1"], PDO::PARAM_STR);
      $stmt->bindValue(":zona2", $param["zona2"], PDO::PARAM_STR);

      if ($stmt->execute() && $stmt2->execute()) {
        $pchid = $conn->lastInsertId();
        $this->insertItem($pchid, $param["item"]);
        $return["status"] = true;
        $return["last_id"] = $pchid;
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
      if ($param["stats"] == 1) {
        $sql2 = "UPDATE m_dm_dies_asset SET zona_id = '" . $param["zona2"] . "' WHERE dies_id = " . $param["dies_id"] . " ";
      } else {
        $sql2 = "UPDATE m_dm_dies_asset SET zona_id = '" . $param["zona1"] . "' WHERE dies_id = " . $param["dies_id"] . " ";
      }
      $sql = "UPDATE t_dm_pc_h SET dies_id = :dies_id, desc1 = :desc1, pcdat = :pcdat, chg_by = :chg_by, chg_dt = CURRENT_TIMESTAMP, status = :stats, zona1 = :zona1, zona2 = :zona2 "
        . "WHERE pchid = :pchid ";
      $stmt = $conn->prepare($sql);
      $stmt2 = $conn->prepare($sql2);
      $stmt->bindValue(":pchid", $param["pchid"], PDO::PARAM_STR);
      $stmt->bindValue(":dies_id", $param["dies_id"], PDO::PARAM_STR);
      $stmt->bindValue(":desc1", $param["desc1"], PDO::PARAM_STR);
      $stmt->bindValue(":pcdat", $param["pcdat"], PDO::PARAM_STR);
      $stmt->bindValue(":chg_by", $param["chg_by"], PDO::PARAM_STR);
      $stmt->bindValue(":stats", $param["stats"], PDO::PARAM_STR);
      $stmt->bindValue(":zona1", $param["zona1"], PDO::PARAM_STR);
      $stmt->bindValue(":zona2", $param["zona2"], PDO::PARAM_STR);

      if ($stmt->execute() && $stmt2->execute()) {
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
    // print_r($item);
    // die();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $conn->exec("DELETE FROM t_dm_pc_i WHERE pchid = '$pchid'");

    $sql = "INSERT INTO t_dm_pc_i (pchid, part_id, part_text, remarks) VALUES ";
    foreach ($item as $row) {
      if (!empty($row["part_text"])) {
        $arr_insert[] = "('$pchid','" . $row["part_id"] . "','" . $row["part_text"] . "','" . $row["remarks"] . "')";
      }
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

  public function countCorePin($pchid, $part_id)
  {
    $return = 0;
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT count(*) as cnt FROM t_dm_pc_core WHERE pchid = '$pchid' AND part_id = '$part_id'";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute()) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $return = $row["cnt"];
      }
    }
    $stmt = null;
    $conn = null;
    return $return;
  }

  public function insertCorePin($pch_id, $part_id, $data = array())
  {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $conn->exec("DELETE FROM t_dm_pc_core WHERE pchid = '$pch_id' and part_id = '$part_id'");
    if (!empty($data)) {
      $sql = "INSERT INTO t_dm_pc_core (pchid, part_id, seqno, text1, text2, text3) VALUES ";
      $i = 1;
      $arr_insert = [];
      foreach ($data as $row) {
        $arr_insert[] = "('$pch_id','$part_id','$i','$row[1]','$row[2]','$row[3]')";
        $i++;
      }

      $sql .= implode(",", $arr_insert);
      $stmt = $conn->prepare($sql);
      if ($stmt->execute() or die($sql)) {
        $return["status"] = true;
      } else {
        $return["status"] = false;
        $error = $stmt->errorInfo();
        $return["message"] = trim(str_replace("\n", " ", $error[2]));
      }
    } else {
      $return["status"] = false;
      $return["message"] = "Data Empty";
    }
    $stmt = null;
    $conn = null;
    return $return;
  }

  public function getCorePin($pch_id)
  {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT * FROM t_dm_pc_core WHERE pchid = '$pch_id' ORDER by part_id asc, seqno asc";
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
}