<?php

class Checksheet
{

  public function getList()
  {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT a.*, b.group_id, b.model_id, b.dies_no, b.name1, TO_CHAR(a.pmtdt, 'DD-MM-YYYY') as pmt_date FROM t_dm_cs_h a "
      . "INNER JOIN m_dm_dies_asset b on b.dies_id = a.dies_id "
      . "WHERE 1=1 ";

    $sql .= " ORDER by pmtid ASC ";
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

  public function getChecksheetById($id)
  {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT a.*, TO_CHAR(a.pmtdt, 'DD-MM-YYYY') as pmt_date, b.name1 as pmtby_name, c.pval2 as pm_type "
      . "FROM t_dm_cs_h a "
      . "INNER JOIN m_user b ON b.usrid = a.pmtby "
      . "INNER JOIN m_param c ON c.pid = 'PM_TYPE' and c.pval1 = a.pmtype "
      . "WHERE a.pmtid = :id";
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

  public function generateID()
  {
    $return = null;
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT CAST (COALESCE (MAX (pmtid), to_char(current_timestamp, 'YYYYMM')||'0000') AS integer)+1 AS lastid "
      . " FROM t_dm_cs_h WHERE pmtid LIKE to_char(current_timestamp, 'YYYYMM')||'%'";
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

  public function insert($param = array())
  {
    $return = array();
    if (empty($param)) {
      $return["status"] = false;
      $return["message"] = "Data Empty";
    } else {
      $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
      $sql = "INSERT INTO t_dm_cs_h (pmtid, pmtdt, pmtstk, pmtype, dies_id, pmtby) "
        . "values (:pmtid, CURRENT_TIMESTAMP, (SELECT stkrun FROM m_dm_dies_asset WHERE dies_id = :dies_id), :pmtype, :dies_id, :pmtby) ";
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(":pmtid", $param["pmtid"], PDO::PARAM_STR);
      $stmt->bindValue(":model_id", $param["model_id"], PDO::PARAM_STR);
      $stmt->bindValue(":dies_id", $param["dies_id"], PDO::PARAM_STR);
      $stmt->bindValue(":pmtby", $param["pmtby"], PDO::PARAM_STR);
      $stmt->bindValue(":pmtype", $param["pmtype"], PDO::PARAM_STR);

      if ($stmt->execute()) {
        $return["status"] = true;
        if ($param["pmtype"] == "2K") {
          $conn->exec("UPDATE m_dm_dies_asset SET stk2k = '0' WHERE dies_id = '" . $param["dies_id"] . "'");
        } else if ($param["pmtype"] == "6K") {
          $conn->exec("UPDATE m_dm_dies_asset SET stk2k = '0', stk6k = '0' WHERE dies_id = '" . $param["dies_id"] . "'");
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

  public function updateChecksheet($param = array())
  {
    $return = array();
    if (empty($param)) {
      $return["status"] = false;
      $return["message"] = "Data Empty";
    } else {
      $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
      $sql = "UPDATE t_dm_cs_h SET "
        . "c11100 = :c11100, "
        . "c11110 = :c11110, "
        . "c11120 = :c11120, "
        . "c11211 = :c11211, "
        . "c11212 = :c11212, "
        . "c11213 = :c11213, "
        . "c11213_c1 = :c11213_c1, "
        . "c11213_c2 = :c11213_c2, "
        . "c11213_c3 = :c11213_c3, "
        . "c11213_c4 = :c11213_c4, "
        . "c11213_c5 = :c11213_c5, "
        . "c11221 = :c11221, "
        . "c11222 = :c11222, "
        . "c11231 = :c11231, "
        . "c11232 = :c11232, "
        . "c11233 = :c11233, "
        . "c11241 = :c11241, "
        . "c11242 = :c11242, "
        . "c11243 = :c11243, "
        . "c11243_c1 = :c11243_c1, "
        . "c11243_c2 = :c11243_c2, "
        . "c11243_c3 = :c11243_c3, "
        . "c11243_c4 = :c11243_c4, "
        . "c11243_c5 = :c11243_c5, "
        . "c11251 = :c11251, "
        . "c11252 = :c11252, "
        . "c11311 = :c11311, "
        . "c11312 = :c11312, "
        . "c11313 = :c11313, "
        . "c11314 = :c11314, "
        . "c11315 = :c11315, "
        . "c11316 = :c11316, "
        . "c113211 = :c113211, "
        . "c113212 = :c113212, "
        . "c11322 = :c11322, "
        . "c11323 = :c11323, "
        . "c1141 = :c1141, "
        . "c1142 = :c1142, "
        . "c1143 = :c1143, "
        . "c1143_c1 = :c1143_c1, "
        . "c1143_c2 = :c1143_c2, "
        . "c1143_c3 = :c1143_c3, "
        . "c1143_c4 = :c1143_c4, "
        . "c1143_c5 = :c1143_c5, "
        . "c1151 = :c1151, "
        . "c1152 = :c1152, "
        . "c1152_c1 = :c1152_c1, "
        . "c1152_c2 = :c1152_c2, "
        . "c1152_c3 = :c1152_c3, "
        . "c1152_c4 = :c1152_c4, "
        . "c1152_c5 = :c1152_c5, "
        . "c1153 = :c1153, "
        . "c1161 = :c1161, "
        . "c11611 = :c11611, "
        . "c11612 = :c11612, "
        . "c1162 = :c1162, "
        . "c11621 = :c11621, "
        . "c11622 = :c11622, "
        . "c117 = :c117, "
        . "c1181 = :c1181, "
        . "c1182 = :c1182, "
        . "c1183 = :c1183, "
        . "c1184 = :c1184, "
        . "c1185 = :c1185, "
        . "c119 = :c119, "
        . "c11911 = :c11911, "
        . "c11912 = :c11912, "
        . "c11913 = :c11913, "
        . "c11914 = :c11914, "
        . "c11921 = :c11921, "
        . "c11922 = :c11922, "
        . "c11923 = :c11923, "
        . "c11924 = :c11924 "
        . "WHERE pmtid = :pmtid ";
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(":pmtid", strtoupper(trim($param["pmtid"])), PDO::PARAM_STR);
      $stmt->bindValue(":c11100", $param["c11100"], PDO::PARAM_STR); //gambar
      $stmt->bindValue(":c11110", $param["c11110"], PDO::PARAM_STR);
      $stmt->bindValue(":c11120", $param["c11120"], PDO::PARAM_STR);
      $stmt->bindValue(":c11211", $param["c11211"], PDO::PARAM_STR);
      $stmt->bindValue(":c11212", $param["c11212"], PDO::PARAM_STR);
      $stmt->bindValue(":c11213", $param["c11213"], PDO::PARAM_STR);
      $stmt->bindValue(":c11213_c1", $param["c11213_c1"], PDO::PARAM_STR);
      $stmt->bindValue(":c11213_c2", $param["c11213_c2"], PDO::PARAM_STR);
      $stmt->bindValue(":c11213_c3", $param["c11213_c3"], PDO::PARAM_STR);
      $stmt->bindValue(":c11213_c4", $param["c11213_c4"], PDO::PARAM_STR);
      $stmt->bindValue(":c11213_c5", $param["c11213_c5"], PDO::PARAM_STR);
      $stmt->bindValue(":c11221", $param["c11221"], PDO::PARAM_STR);
      $stmt->bindValue(":c11222", $param["c11222"], PDO::PARAM_STR);
      $stmt->bindValue(":c11231", $param["c11231"], PDO::PARAM_STR);
      $stmt->bindValue(":c11232", $param["c11232"], PDO::PARAM_STR);
      $stmt->bindValue(":c11233", $param["c11233"], PDO::PARAM_STR);
      $stmt->bindValue(":c11241", $param["c11241"], PDO::PARAM_STR);
      $stmt->bindValue(":c11242", $param["c11242"], PDO::PARAM_STR);
      $stmt->bindValue(":c11243", $param["c11243"], PDO::PARAM_STR);
      $stmt->bindValue(":c11243_c1", $param["c11243_c1"], PDO::PARAM_STR);
      $stmt->bindValue(":c11243_c2", $param["c11243_c2"], PDO::PARAM_STR);
      $stmt->bindValue(":c11243_c3", $param["c11243_c3"], PDO::PARAM_STR);
      $stmt->bindValue(":c11243_c4", $param["c11243_c4"], PDO::PARAM_STR);
      $stmt->bindValue(":c11243_c5", $param["c11243_c5"], PDO::PARAM_STR);
      $stmt->bindValue(":c11251", $param["c11251"], PDO::PARAM_STR);
      $stmt->bindValue(":c11252", $param["c11252"], PDO::PARAM_STR);
      $stmt->bindValue(":c11311", $param["c11311"], PDO::PARAM_STR);
      $stmt->bindValue(":c11312", $param["c11312"], PDO::PARAM_STR);
      $stmt->bindValue(":c11313", $param["c11313"], PDO::PARAM_STR);
      $stmt->bindValue(":c11314", $param["c11314"], PDO::PARAM_STR);
      $stmt->bindValue(":c11315", $param["c11315"], PDO::PARAM_STR);
      $stmt->bindValue(":c11316", $param["c11316"], PDO::PARAM_STR);
      $stmt->bindValue(":c113211", $param["c113211"], PDO::PARAM_STR);
      $stmt->bindValue(":c113212", $param["c113212"], PDO::PARAM_STR);
      $stmt->bindValue(":c11322", $param["c11322"], PDO::PARAM_STR);
      $stmt->bindValue(":c11323", $param["c11323"], PDO::PARAM_STR);
      $stmt->bindValue(":c1141", $param["c1141"], PDO::PARAM_STR);
      $stmt->bindValue(":c1142", $param["c1142"], PDO::PARAM_STR);
      $stmt->bindValue(":c1143", $param["c1143"], PDO::PARAM_STR);
      $stmt->bindValue(":c1143_c1", $param["c1143_c1"], PDO::PARAM_STR);
      $stmt->bindValue(":c1143_c2", $param["c1143_c2"], PDO::PARAM_STR);
      $stmt->bindValue(":c1143_c3", $param["c1143_c3"], PDO::PARAM_STR);
      $stmt->bindValue(":c1143_c4", $param["c1143_c4"], PDO::PARAM_STR);
      $stmt->bindValue(":c1143_c5", $param["c1143_c5"], PDO::PARAM_STR);
      $stmt->bindValue(":c1151", $param["c1151"], PDO::PARAM_STR);
      $stmt->bindValue(":c1152", $param["c1152"], PDO::PARAM_STR);
      $stmt->bindValue(":c1152_c1", $param["c1152_c1"], PDO::PARAM_STR);
      $stmt->bindValue(":c1152_c2", $param["c1152_c2"], PDO::PARAM_STR);
      $stmt->bindValue(":c1152_c3", $param["c1152_c3"], PDO::PARAM_STR);
      $stmt->bindValue(":c1152_c4", $param["c1152_c4"], PDO::PARAM_STR);
      $stmt->bindValue(":c1152_c5", $param["c1152_c5"], PDO::PARAM_STR);
      $stmt->bindValue(":c1153", $param["c1153"], PDO::PARAM_STR);
      $stmt->bindValue(":c1161", $param["c1161"], PDO::PARAM_STR); //gambar
      $stmt->bindValue(":c11611", $param["c11611"], PDO::PARAM_STR);
      $stmt->bindValue(":c11612", $param["c11612"], PDO::PARAM_STR);
      $stmt->bindValue(":c1162", $param["c1162"], PDO::PARAM_STR); //gambar
      $stmt->bindValue(":c11621", $param["c11621"], PDO::PARAM_STR);
      $stmt->bindValue(":c11622", $param["c11622"], PDO::PARAM_STR);
      $stmt->bindValue(":c117", $param["c117"], PDO::PARAM_STR);
      $stmt->bindValue(":c1181", $param["c1181"], PDO::PARAM_STR);
      $stmt->bindValue(":c1182", $param["c1182"], PDO::PARAM_STR);
      $stmt->bindValue(":c1183", $param["c1183"], PDO::PARAM_STR);
      $stmt->bindValue(":c1184", $param["c1184"], PDO::PARAM_STR);
      $stmt->bindValue(":c1185", $param["c1185"], PDO::PARAM_STR);
      $stmt->bindValue(":c119", $param["c119"], PDO::PARAM_STR); //gambar
      $stmt->bindValue(":c11911", $param["c11911"], PDO::PARAM_STR);
      $stmt->bindValue(":c11912", $param["c11912"], PDO::PARAM_STR);
      $stmt->bindValue(":c11913", $param["c11913"], PDO::PARAM_STR);
      $stmt->bindValue(":c11914", $param["c11914"], PDO::PARAM_STR);
      $stmt->bindValue(":c11921", $param["c11921"], PDO::PARAM_STR);
      $stmt->bindValue(":c11922", $param["c11922"], PDO::PARAM_STR);
      $stmt->bindValue(":c11923", $param["c11923"], PDO::PARAM_STR);
      $stmt->bindValue(":c11924", $param["c11924"], PDO::PARAM_STR);

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
}
