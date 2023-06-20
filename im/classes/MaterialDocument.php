<?php

class MaterialDocument {
  //put your code here
  public function generateID() {
    $return = null;
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT CAST (COALESCE (MAX (mblnr), to_char(current_timestamp, 'YYYYMMDD')||'0000000') AS bigint)+1 AS lastid 
            FROM wms.t_mkpf WHERE mblnr LIKE to_char(current_timestamp, 'YYYYMMDD')||'%'";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute()) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $return = $row["lastid"];
      }
    }
    $stmt = null;
    $conn = null;
    return $return;
  }
  
  public function getList($budat_from = null, $budat_to = null, $matnr = null, $werks = null, $lgort = null) {
    $return = [];
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT TO_CHAR(a.budat,'DD-MM-YYYY') as posting_date, TO_CHAR(a.crt_dt,'HH24:MI:SS') as posting_time, a.*, i.*, b.name1 as maktx, c.name1 as plant_name, d.name1 as sloc_name FROM wms.t_mkpf a "
            . "INNER JOIN wms.t_mseg i ON i.mblnr = a.mblnr AND i.mjahr = a.mjahr "
            . "INNER JOIN wms.m_mara b ON b.matnr = i.matnr "
            . "INNER JOIN wms.m_werks c ON c.werks = i.werks "
            . "INNER JOIN wms.m_lgort d ON d.werks = i.werks AND d.lgort = i.lgort "
            . "WHERE 1=1 ";
    if(!empty($budat_from)&&!empty($budat_to)) {
      $sql .= " AND a.budat between '$budat_from' AND '$budat_to' ";
    }
    if(!empty($matnr)) {
      $sql .= " AND i.matnr = '$matnr' ";
    }
    if(!empty($werks)) {
      $sql .= " AND i.werks = '$werks' ";
    }
    if(!empty($lgort)) {
      $sql .= " AND i.lgort = '$lgort' ";
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
  
  public function insertHeader($param = array()) {
    $return = [];
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "INSERT INTO wms.t_mkpf (mblnr,mjahr,budat,crt_by,crt_dt,xblnr) "
            . "VALUES (:mblnr,:mjahr,:budat,:crt_by,CURRENT_TIMESTAMP,:xblnr)";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":mblnr", $param["mblnr"], PDO::PARAM_STR);
    $stmt->bindValue(":mjahr", $param["mjahr"], PDO::PARAM_STR);
    $stmt->bindValue(":budat", $param["budat"], PDO::PARAM_STR);
    $stmt->bindValue(":crt_by", $param["crt_by"], PDO::PARAM_STR);
    $stmt->bindValue(":xblnr", $param["xblnr"], PDO::PARAM_STR);
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
  
  public function insertItem($param = array()) {
    $return = [];
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "INSERT INTO wms.t_mseg (mblnr,mjahr,mblpo,matnr,bwart,shkzg,menge,werks,lgort,charg,ebeln,aufnr, vbeln, lifnr) "
            . "VALUES (:mblnr,:mjahr,:mblpo,:matnr,:bwart,:shkzg,:menge,:werks,:lgort,:charg,:ebeln,:aufnr, '".$param["vbeln"]."','".$param["lifnr"]."')";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":mblnr", $param["mblnr"], PDO::PARAM_STR);
    $stmt->bindValue(":mjahr", $param["mjahr"], PDO::PARAM_STR);
    $stmt->bindValue(":mblpo", $param["mblpo"], PDO::PARAM_STR);
    $stmt->bindValue(":matnr", $param["matnr"], PDO::PARAM_STR);
    $stmt->bindValue(":bwart", $param["bwart"], PDO::PARAM_STR);
    $stmt->bindValue(":shkzg", $param["shkzg"], PDO::PARAM_STR);
    $stmt->bindValue(":menge", $param["menge"], PDO::PARAM_STR);
    $stmt->bindValue(":werks", $param["werks"], PDO::PARAM_STR);
    $stmt->bindValue(":lgort", $param["lgort"], PDO::PARAM_STR);
    $stmt->bindValue(":charg", $param["charg"], PDO::PARAM_STR);
    $stmt->bindValue(":ebeln", $param["ebeln"], PDO::PARAM_STR);
    $stmt->bindValue(":aufnr", $param["aufnr"], PDO::PARAM_STR);
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
  
  public function rollBackMaterialDocument($mblnr,$mjahr) {
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);    
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, 1);
    
    $sql = "DELETE FROM wms.t_mkpf WHERE mblnr = '$mblnr' AND mjahr = '$mjahr'; "
            . "DELETE FROM wms.t_mseg WHERE mblnr = '$mblnr' AND mjahr = '$mjahr'; ";
    $conn->exec($sql);
  }
  
  public function getById($mblnr, $mjahr) {
    //select header
    $return = [];
    $mkpf = [];
    $mseg = [];
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT * FROM wms.t_mkpf WHERE mblnr = '$mblnr' AND mjahr = '$mjahr'";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute()) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $mkpf = $row;
      }
    }
    
    $sql = "SELECT a.*, b.name1 as maktx, c.name1 as plant_name, d.name1 as sloc_name FROM wms.t_mseg a "
            . "INNER JOIN wms.m_mara b ON b.matnr = a.matnr "
            . "INNER JOIN wms.m_werks c ON c.werks = a.werks "
            . "INNER JOIN wms.m_lgort d ON d.werks = a.werks AND d.lgort = a.lgort "
            . "WHERE a.mblnr = '$mblnr' AND a.mjahr = '$mjahr'";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute()) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $mseg[] = $row;
      }
    }
    
    $return["mkpf"] = $mkpf;
    $return["mseg"] = $mseg;
    unset($mkpf);
    unset($mseg);
    $stmt = null;
    $conn = null;
    return $return;
  }
  
  public function isReversed($mblnr,$mjahr) {
    $return = 0;
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT count(*) as cnt FROM wms.t_mkpf WHERE xblnr = '$mblnr' AND mjahr = '$mjahr'";
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
}

?>