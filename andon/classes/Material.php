<?php

class Material
{

  public function getListMaterial()
  {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT * FROM wms.m_mara WHERE mtart = 'FIN'";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute()) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $return[] = $row;
      }
    }
    return $return;
  }

  public function getListRaw()
  {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT * FROM wms.m_mara WHERE mtart = 'RAW'";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute()) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $return[] = $row;
      }
    }
    return $return;
  }

  public function getList()
  {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT * FROM wms.m_mara";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute()) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $return[] = $row;
      }
    }
    return $return;
  }

  public function getById($id)
  {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT a.* FROM wms.m_mara a "
      . "WHERE matnr = :id ";
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

  public function getListMara()
  {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT a.*, b.name1 as mat_type, c.name1 as mat_group FROM wms.m_mara a "
      . " LEFT JOIN wms.m_mtart b ON b.mtart = a.mtart "
      . " LEFT JOIN wms.m_matkl c ON c.matkl = a.matkl "
      . "WHERE 1=1 ";
    $sql .= " ORDER BY matnr ASC ";
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
      $sql = "INSERT INTO wms.m_mara (matnr, mtart, name1, meins, ematn, crt_by, crt_dt, matkl, cctime, lgort, backno) "
        . "values (:matnr, :mtart, :name1, :meins, :ematn, :crt_by, CURRENT_TIMESTAMP, :matkl, :cctime, :lgort, :backno) ";
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(":matnr", strtoupper(trim($param["matnr"])), PDO::PARAM_STR);
      $stmt->bindValue(":mtart", strtoupper(trim($param["mtart"])), PDO::PARAM_STR);
      $stmt->bindValue(":name1", $param["name1"], PDO::PARAM_STR);
      $stmt->bindValue(":meins", $param["meins"], PDO::PARAM_STR);
      $stmt->bindValue(":ematn", $param["ematn"], PDO::PARAM_STR);
      $stmt->bindValue(":crt_by", $param["crt_by"], PDO::PARAM_STR);
      $stmt->bindValue(":matkl", strtoupper(trim($param["matkl"])), PDO::PARAM_STR);
      $stmt->bindValue(":cctime", $param["cctime"], PDO::PARAM_STR);
      $stmt->bindValue(":lgort", $param["lgort"], PDO::PARAM_STR);
      $stmt->bindValue(":backno", $param["backno"], PDO::PARAM_STR);

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
      $sql = "UPDATE wms.m_mara SET mtart = :mtart, matkl = :matkl, name1 = :name1, "
        . "meins = :meins, ematn = :ematn, chg_by = :chg_by, chg_dt = CURRENT_TIMESTAMP, "
        . "cctime = :cctime, lgort = :lgort, backno = :backno "
        . "WHERE matnr = :matnr";
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(":matnr", strtoupper(trim($param["matnr"])), PDO::PARAM_STR);
      $stmt->bindValue(":mtart", strtoupper(trim($param["mtart"])), PDO::PARAM_STR);
      $stmt->bindValue(":name1", trim($param["name1"]), PDO::PARAM_STR);
      $stmt->bindValue(":meins", strtoupper(trim($param["meins"])), PDO::PARAM_STR);
      $stmt->bindValue(":ematn", strtoupper(trim($param["ematn"])), PDO::PARAM_STR);
      $stmt->bindValue(":chg_by", $param["chg_by"], PDO::PARAM_STR);
      $stmt->bindValue(":matkl", strtoupper(trim($param["matkl"])), PDO::PARAM_STR);
      $stmt->bindValue(":cctime", $param["cctime"], PDO::PARAM_STR);
      $stmt->bindValue(":lgort", $param["lgort"], PDO::PARAM_STR);
      $stmt->bindValue(":backno", $param["backno"], PDO::PARAM_STR);

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

  public function isExist($id)
  {
    $return = 0;
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT count(*) as cnt FROM wms.m_mara a "
      . "WHERE matnr = :id ";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":id", strtoupper($id), PDO::PARAM_STR);
    if ($stmt->execute()) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $return = floatval($row["cnt"]);
      }
    }
    $stmt = null;
    $conn = null;
    return $return;
  }

  public function delete($id)
  {
    $return = array();
    if (empty($id)) {
      $return["status"] = false;
      $return["message"] = "ID Empty";
    } else {
      $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
      $sql = "DELETE FROM wms.m_mara WHERE matnr = :id";
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(":id", strtoupper(trim($id)), PDO::PARAM_STR);

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

  public function isUsed($id)
  {
    $return = 0;
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT count(*) as cnt FROM wms.t_mseg "
      . "WHERE matnr = :id ";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":id", strtoupper($id), PDO::PARAM_STR);
    if ($stmt->execute()) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $return = floatval($row["cnt"]);
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
    $sql = "SELECT a.* FROM wms.m_mtart a "
      . "WHERE 1=1 ORDER BY mtart ASC";

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

  public function getGroup()
  {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT a.* FROM wms.m_matkl a "
      . "WHERE 1=1 ORDER BY matkl ASC";

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

  public function getMatsPrd($line_id)
  {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT a.*, b.name1 as mat_type, c.name1 as mat_group FROM wms.m_mara a "
      . " LEFT JOIN wms.m_mtart b ON b.mtart = a.mtart "
      . " LEFT JOIN wms.m_matkl c ON c.matkl = a.matkl "
      . "WHERE 1=1 and c.matkl = '$line_id' and a.mtart = 'FIN' ";
    $sql .= " ORDER BY matnr ASC ";
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

?>