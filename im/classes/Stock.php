<?php

class Stock {
  //put your code here
  public function getList($matnr = null, $werks = null, $lgort = null, $type = null) {
    $return = [];
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT a.*, b.*, b.name1 as maktx, c.name1 as plant_name, d.name1 as sloc_name FROM wms.t_mchb a "
            . "INNER JOIN wms.m_mara b ON b.matnr = a.matnr "
            . "INNER JOIN wms.m_werks c ON c.werks = a.werks "
            . "INNER JOIN wms.m_lgort d ON d.werks = a.werks AND d.lgort = a.lgort "
            . "WHERE 1=1 ";
    if(!empty($matnr)) {
      $sql .= " AND a.matnr = '$matnr' ";
    }
    if(!empty($werks)) {
      $sql .= " AND a.werks = '$werks' ";
    }
    if(!empty($lgort)) {
      $sql .= " AND a.lgort = '$lgort' ";
    }
    if(!empty($type)) {
      $sql .= " AND b.mtart = '$type' ";
    }
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
  
  public function addStock($param = array()) {
    $return = [];
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    //cek dulu apakah stok sudah ada
    $sql = "SELECT count(*) as cnt FROM wms.t_mchb WHERE matnr = :matnr AND werks = :werks AND lgort = :lgort AND charg = :charg ";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":matnr", $param["matnr"], PDO::PARAM_STR);
    $stmt->bindValue(":werks", $param["werks"], PDO::PARAM_STR);
    $stmt->bindValue(":lgort", $param["lgort"], PDO::PARAM_STR);
    $stmt->bindValue(":charg", $param["charg"], PDO::PARAM_STR);
    $is_exist = 0;
    if($stmt->execute()) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $is_exist = $row["cnt"];
      }
    } else {
      $error = $stmt->errorInfo();
      $return["status"] = false;
      $return["message"] = trim(str_replace("\n", " ", $error[2]));
      error_log($error[2]);
      return $return;
    }
    if($is_exist > 0) {
      $sql = "UPDATE wms.t_mchb SET clabs = clabs + :clabs, cinsm = cinsm + :cinsm, chg_by = :chg_by, chg_dt = CURRENT_TIMESTAMP WHERE matnr = :matnr AND werks = :werks AND lgort = :lgort AND charg = :charg ";
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(":clabs", $param["clabs"], PDO::PARAM_STR);
      $stmt->bindValue(":cinsm", $param["cinsm"], PDO::PARAM_STR);
      $stmt->bindValue(":matnr", $param["matnr"], PDO::PARAM_STR);
      $stmt->bindValue(":werks", $param["werks"], PDO::PARAM_STR);
      $stmt->bindValue(":lgort", $param["lgort"], PDO::PARAM_STR);
      $stmt->bindValue(":charg", $param["charg"], PDO::PARAM_STR);
      $stmt->bindValue(":chg_by", $param["chg_by"], PDO::PARAM_STR);
    } else {
      $sql = "INSERT INTO wms.t_mchb (matnr,werks,lgort,charg,clabs,cinsm,meins,crt_by,crt_dt) "
              . "VALUES (:matnr,:werks,:lgort,:charg,:clabs,:cinsm,(select meins FROM wms.m_mara WHERE matnr = :matnr2),:crt_by,CURRENT_TIMESTAMP)";
      $stmt = $conn->prepare($sql);      
      $stmt->bindValue(":matnr", $param["matnr"], PDO::PARAM_STR);
      $stmt->bindValue(":werks", $param["werks"], PDO::PARAM_STR);
      $stmt->bindValue(":lgort", $param["lgort"], PDO::PARAM_STR);
      $stmt->bindValue(":charg", $param["charg"], PDO::PARAM_STR);
      $stmt->bindValue(":clabs", $param["clabs"], PDO::PARAM_STR);
      $stmt->bindValue(":cinsm", $param["cinsm"], PDO::PARAM_STR);
      $stmt->bindValue(":matnr2", $param["matnr"], PDO::PARAM_STR);
      $stmt->bindValue(":crt_by", $param["crt_by"], PDO::PARAM_STR);
    }
    if($stmt->execute()) {
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
  
  public function reduceStock($param = array()) {
    $return = [];
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    //cek dulu apakah stok sudah ada
    $sql = "SELECT coalesce(clabs,0) as clabs, coalesce(cinsm,0) as cinsm FROM wms.t_mchb WHERE matnr = :matnr AND werks = :werks AND lgort = :lgort AND charg = :charg ";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":matnr", $param["matnr"], PDO::PARAM_STR);
    $stmt->bindValue(":werks", $param["werks"], PDO::PARAM_STR);
    $stmt->bindValue(":lgort", $param["lgort"], PDO::PARAM_STR);
    $stmt->bindValue(":charg", $param["charg"], PDO::PARAM_STR);
    $clabs = 0;
    $cinsm = 0;
    if($stmt->execute()) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $clabs = $row["clabs"];
        $cinsm = $row["cinsm"];
      }
    } else {
      $error = $stmt->errorInfo();
      $return["status"] = false;
      $return["message"] = trim(str_replace("\n", " ", $error[2]));
      error_log($error[2]);
      return $return;
    }
    $clabs = $clabs - $param["clabs"];
    $cinsm = $cinsm - $param["cinsm"];
    
    if($clabs < 0 || $cinsm < 0) {
      $return["status"] = false;
      $return["message"] = "Deficit Unrestricted Stock of $clabs or Quality Stock of $cinsm [".$param["matnr"].", ".$param["werks"].", ".$param["lgort"].", ".$param["charg"]."]";
      return $return;
    } else {
      $sql = "UPDATE wms.t_mchb SET clabs = :clabs, cinsm = :cinsm, chg_by = :chg_by WHERE matnr = :matnr AND werks = :werks AND lgort = :lgort AND charg = :charg ";
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(":clabs", $clabs, PDO::PARAM_STR);
      $stmt->bindValue(":cinsm", $cinsm, PDO::PARAM_STR);
      $stmt->bindValue(":matnr", $param["matnr"], PDO::PARAM_STR);
      $stmt->bindValue(":werks", $param["werks"], PDO::PARAM_STR);
      $stmt->bindValue(":lgort", $param["lgort"], PDO::PARAM_STR);
      $stmt->bindValue(":charg", $param["charg"], PDO::PARAM_STR);
      $stmt->bindValue(":chg_by", $param["chg_by"], PDO::PARAM_STR);
      if($stmt->execute()) {
        $return["status"] = true;
      } else {
        $error = $stmt->errorInfo();
        $return["status"] = false;
        $return["message"] = trim(str_replace("\n", " ", $error[2]));
        error_log($error[2]);
      }
    }
    
    $stmt = null;
    $conn = null;
    return $return;
  }
  
  public function checkDeficitStock($param = array()) {
    $return = [];
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    //cek dulu apakah stok sudah ada
    $sql = "SELECT coalesce(clabs,0) as clabs, coalesce(cinsm,0) as cinsm FROM wms.t_mchb WHERE matnr = :matnr AND werks = :werks AND lgort = :lgort AND charg = :charg ";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":matnr", $param["matnr"], PDO::PARAM_STR);
    $stmt->bindValue(":werks", $param["werks"], PDO::PARAM_STR);
    $stmt->bindValue(":lgort", $param["lgort"], PDO::PARAM_STR);
    $stmt->bindValue(":charg", $param["charg"], PDO::PARAM_STR);
    $clabs = 0;
    $cinsm = 0;
    if($stmt->execute()) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $clabs = $row["clabs"];
        $cinsm = $row["cinsm"];
      }
    } else {
      $error = $stmt->errorInfo();
      $return["status"] = false;
      $return["message"] = trim(str_replace("\n", " ", $error[2]));
      error_log($error[2]);
      return $return;
    }
    $clabs = $clabs - $param["clabs"];
    $cinsm = $cinsm - $param["cinsm"];
    
    if($clabs < 0 || $cinsm < 0) {
      $return["status"] = false;
      $return["message"] = "Deficit Unrestricted Stock of $clabs or Quality Stock of $cinsm [".$param["matnr"].", ".$param["werks"].", ".$param["lgort"].", ".$param["charg"]."]";
    } else {
      $return["status"] = true;
    }
    $stmt = null;
    $conn = null;
    return $return;
  }
  
  public function generateBatchNumber() {
    $return = null;
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT CAST (COALESCE (MAX (charg), to_char(current_timestamp, 'YYYYMMDD')||'0000000') AS bigint)+1 AS last_id ".
           "FROM wms.t_mchb WHERE charg LIKE to_char(current_timestamp, 'YYYYMMDD')||'%'";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute()) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $return = $row["last_id"];
      }
    }
    $stmt = null;
    $conn = null;
    return $return;
  }
  
  public function getStockDetail($werks,$lgort,$matnr,$charg) {
    $return = [];
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT * FROM wms.t_mchb WHERE matnr = '$matnr' AND werks = '$werks' AND lgort = '$lgort' AND charg = '$charg' ";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute()) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $return = $row;
      }
    }
    $stmt = null;
    $conn = null;
    return $return;
  }
}

?>