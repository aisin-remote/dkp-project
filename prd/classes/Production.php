<?php

class Production
{

  public function getList()
  {
  }

  public function getListLine()
  {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "select a.* from m_prd_line a WHERE 1=1 AND line_ty = 'DM' ";

    $sql .= " ORDER by a.line_id ASC ";
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

  public function getLineById($id)
  {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "select * from m_prd_line WHERE line_id = '$id' ";
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

  public function getListShift()
  {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT * FROM m_param "
      . "WHERE pid = 'SHIFT' ";

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

  public function getShiftCount($shift)
  {
    $return = 0;
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT count(*) as cnt FROM m_prd_shift "
      . "WHERE shift_id = '$shift' ";
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

  public function insertHeader($param = array())
  {
    $return = array();
    if (empty($param)) {
      $return["status"] = false;
      $return["message"] = "Data Empty";
    } else {
      $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
      $sql = "INSERT INTO t_prd_daily_h (line_id,prd_dt,shift,ldid,jpid,op1id,op2id,op3id,op4id,cctime) "
        . "values (:line_id,TO_DATE(:prd_dt,'YYYYMMDD'),:shift,:ldid,:jpid,:op1id,:op2id,:op3id,:op4id,:cctime) ";
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(":line_id", $param["line_id"], PDO::PARAM_STR);
      $stmt->bindValue(":prd_dt", $param["prd_dt"], PDO::PARAM_STR);
      $stmt->bindValue(":shift", $param["shift"], PDO::PARAM_STR);
      $stmt->bindValue(":ldid", $param["ldid"], PDO::PARAM_STR);
      $stmt->bindValue(":jpid", $param["jpid"], PDO::PARAM_STR);
      $stmt->bindValue(":op1id", $param["op1id"], PDO::PARAM_STR);
      $stmt->bindValue(":op2id", $param["op2id"], PDO::PARAM_STR);
      $stmt->bindValue(":op3id", $param["op3id"], PDO::PARAM_STR);
      $stmt->bindValue(":op4id", $param["op4id"], PDO::PARAM_STR);
      $stmt->bindValue(":cctime", $param["cctime"], PDO::PARAM_STR);

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

  public function rollBackHeader($line, $date, $shift)
  {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "DELETE FROM t_prd_daily_h WHERE line_id = '$line' AND TO_CHAR(prd_dt,'YYYYMMDD') = '$date' AND shift = '$shift'";
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

  public function insertItem($param = array())
  {
    $return = array();
    if (empty($param)) {
      $return["status"] = false;
      $return["message"] = "Data Empty";
    } else {
      $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
      $sql = "INSERT INTO t_prd_daily_i (line_id,prd_dt,shift,prd_seq,dies_id,time_start,time_end,cctime,pln_qty,prd_time) "
        . "values ";

      $insertQuery = array();
      $insertData = array();

      foreach ($param as $row) {
        $insertQuery[] = "(?, TO_DATE(?,'YYYYMMDD'), ?, ?, ?, ?, ?, ?, ?, ?)";
        $insertData[] = $row["line_id"];
        $insertData[] = $row["prd_dt"];
        $insertData[] = $row["shift"];
        $insertData[] = $row["prd_seq"];
        $insertData[] = $row["dies_id"];
        $insertData[] = $row["time_start"];
        $insertData[] = $row["time_end"];
        $insertData[] = $row["cctime"];
        $insertData[] = $row["pln_qty"];
        $insertData[] = $row["prd_time"];
      }

      if (!empty($insertQuery)) {
        $sql .= implode(', ', $insertQuery);
        $stmt = $conn->prepare($sql);
        if ($stmt->execute($insertData)) {
          $return["status"] = true;
        } else {
          $error = $stmt->errorInfo();
          $return["status"] = false;
          $return["message"] = trim(str_replace("\n", " ", $error[2]));
          error_log($error[2]);
        }
        $stmt = null;
        $conn = null;
      } else {
        $return["status"] = false;
        $return["message"] = "Data Empty";
      }
    }
    return $return;
  }

  public function updateItem($param = array())
  {
    $return = array();
    if (empty($param)) {
      $return["status"] = false;
      $return["message"] = "Data Empty";
    } else {
      $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
      $sql = "UPDATE t_prd_daily_i SET dies_id = :dies_id, prd_qty = :prd_qty, prd_time = :prd_time, "
        . "detail_text = :detail_text, dcqcp = :dcqcp, qaqcp = :qaqcp, scn_qty_ok = :scn_qty_ok, scn_qty_ng = :scn_qty_ng,"
        . " cctime = :cctime, pln_qty = :pln_qty "
        . "WHERE line_id = :line_id AND prd_dt = :prd_dt AND shift = :shift AND prd_seq = :prd_seq ";
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(":dies_id", $param["dies_id"], PDO::PARAM_STR);
      $stmt->bindValue(":line_id", $param["line_id"], PDO::PARAM_STR);
      $stmt->bindValue(":prd_dt", $param["prd_dt"], PDO::PARAM_STR);
      $stmt->bindValue(":shift", $param["shift"], PDO::PARAM_STR);
      $stmt->bindValue(":prd_seq", $param["prd_seq"], PDO::PARAM_STR);
      $stmt->bindValue(":prd_qty", $param["prd_qty"], PDO::PARAM_STR);
      $stmt->bindValue(":prd_time", $param["prd_time"], PDO::PARAM_STR);
      $stmt->bindValue(":detail_text", $param["detail_text"], PDO::PARAM_STR);
      $stmt->bindValue(":dcqcp", $param["dcqcp"], PDO::PARAM_STR);
      $stmt->bindValue(":qaqcp", $param["qaqcp"], PDO::PARAM_STR);
      $stmt->bindValue(":scn_qty_ok", $param["scn_qty_ok"], PDO::PARAM_STR);
      $stmt->bindValue(":scn_qty_ng", $param["scn_qty_ng"], PDO::PARAM_STR);
      $stmt->bindValue(":cctime", $param["cctime"], PDO::PARAM_STR);
      $stmt->bindValue(":pln_qty", $param["pln_qty"], PDO::PARAM_STR);
      if ($stmt->execute($insertData)) {
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

  public function getHeaderById($line, $date, $shift)
  {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT a.*, TO_CHAR(a.prd_dt, 'DD-MM-YYYY') as prod_date, b.name1 as line_name, c.pval1 as shift_name, "
      . "ld.name1 as ld_name, jp.name1 as jp_name, op1.name1 as op1_name, op2.name1 as op2_name, op3.name1 as op3_name, op4.name1 as op4_name "
      . "FROM t_prd_daily_h a "
      . "LEFT JOIN m_prd_line b ON b.line_id = a.line_id "
      . "LEFT JOIN m_param c ON c.pid = 'SHIFT' and c.seq = a.shift "
      . "LEFT JOIN m_prd_operator ld ON ld.empid = a.ldid "
      . "LEFT JOIN m_prd_operator jp ON jp.empid = a.jpid "
      . "LEFT JOIN m_prd_operator op1 ON op1.empid = a.op1id "
      . "LEFT JOIN m_prd_operator op2 ON op2.empid = a.op2id "
      . "LEFT JOIN m_prd_operator op3 ON op3.empid = a.op3id "
      . "LEFT JOIN m_prd_operator op4 ON op4.empid = a.op4id "
      . "WHERE a.line_id = '$line' AND a.prd_dt = '$date' AND a.shift = '$shift'";
    // echo $sql;
    // die();
    $stmt = $conn->prepare($sql);
    $count = 0;
    if ($stmt->execute()) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $return = $row;
      }
    }
    $stmt = null;
    $conn = null;
    return $return;
  }

  public function getListItemById($line, $date, $shift)
  {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT a.*, TO_CHAR(a.prd_dt, 'YYYYMMDD') as xdate, CONCAT(b.model_id, ' ', b.group_id, ' ', b.name1) as dies_name, "
      . "(select count(*) as stop_count from t_prd_daily_stop where line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift and prd_seq = a.prd_seq), "
      . "(select name1 from m_user where usrid = a.apr_by) as apr_name "
      . "FROM t_prd_daily_i a "
      . "INNER JOIN m_dm_dies_asset b ON b.dies_id = CAST(a.dies_id as bigint) "
      . "WHERE a.line_id = '$line' AND TO_CHAR(a.prd_dt,'YYYYMMDD') = '$date' AND a.shift = '$shift' "
      . "ORDER BY prd_seq asc ";

    $stmt = $conn->prepare($sql);
    $count = 0;
    if ($stmt->execute()) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $return[] = $row;
      }
    }
    $stmt = null;
    $conn = null;
    return $return;
  }

  public function getItemById($line, $date, $shift, $seq)
  {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT a.*, TO_CHAR(a.prd_dt, 'YYYYMMDD') as xdate, TO_CHAR(a.prd_dt, 'DD-MM-YYYY') as prod_date, CONCAT(x.model_id, ' ', x.group_id, ' ', x.name1) as dies_name, "
      . "b.name1 as line_name, c.pval1 as shift_name, "
      . "ld.name1 as ld_name, jp.name1 as jp_name, op1.name1 as op1_name, op2.name1 as op2_name, op3.name1 as op3_name, op4.name1 as op4_name "
      . "FROM t_prd_daily_i a "
      . "INNER JOIN m_dm_dies_asset x ON x.dies_id = CAST(a.dies_id as bigint) "
      . "INNER JOIN m_prd_line b ON b.line_id = a.line_id "
      . "INNER JOIN m_param c ON c.pid = 'SHIFT' and c.seq = a.shift "
      . "INNER JOIN t_prd_daily_h h ON h.line_id = a.line_id AND h.prd_dt = a.prd_dt AND h.shift = a.shift "
      . "INNER JOIN m_prd_operator ld ON ld.empid = h.ldid "
      . "INNER JOIN m_prd_operator jp ON jp.empid = h.jpid "
      . "LEFT JOIN m_prd_operator op1 ON op1.empid = h.op1id "
      . "LEFT JOIN m_prd_operator op2 ON op2.empid = h.op2id "
      . "LEFT JOIN m_prd_operator op3 ON op3.empid = h.op3id "
      . "LEFT JOIN m_prd_operator op4 ON op4.empid = h.op4id "
      . "WHERE a.line_id = '$line' AND TO_CHAR(a.prd_dt,'YYYYMMDD') = '$date' AND a.shift = '$shift' AND a.prd_seq = '$seq' "
      . "";
    $stmt = $conn->prepare($sql);
    $count = 0;
    if ($stmt->execute()) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if (empty($row["prd_qty"])) {
          $row["prd_qty"] = "0";
        }
        if (empty($row["prd_time"])) {
          $row["prd_time"] = "0";
        }
        if (empty($row["scn_qty_ok"])) {
          $row["scn_qty_ok"] = "0";
        }
        if (empty($row["scn_qty_ng"])) {
          $row["scn_qty_ng"] = "0";
        }
        $return = $row;
      }
    } else {
      error_log($stmt->errorInfo()[2]);
    }
    $stmt = null;
    $conn = null;
    return $return;
  }

  public function isDailyHeaderExist($line, $date, $shift)
  {
    $return = false;
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT count(*) as cnt FROM t_prd_daily_h WHERE line_id = '$line' AND TO_CHAR(prd_dt,'YYYYMMDD') = '$date' AND shift = '$shift'";
    $stmt = $conn->prepare($sql);
    $count = 0;
    if ($stmt->execute()) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $count = floatval($row["cnt"]);
      }
    }
    if ($count > 0) {
      $return = true;
    }
    $stmt = null;
    $conn = null;
    return $return;
  }

  public function getItemTemplateByShift($shift)
  {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT * from m_prd_shift where shift_id = '$shift' AND app_id = '" . APP . "' order by shift_id asc, time_id asc ";
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

  public function getStopList($line_id, $prd_dt, $shift, $prd_seq)
  {
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT a.*, b.name1 as stop_name, c.name1 as action_name, d.name1 as exe_name, b.type2 as stop_type "
      . "FROM t_prd_daily_stop a "
      . "LEFT JOIN m_prd_stop_reason_action b ON b.srna_id = a.stop_id AND b.app_id = 'AISIN_PRD' "
      . "LEFT JOIN m_prd_stop_reason_action c ON c.srna_id = a.action_id AND c.app_id = 'AISIN_PRD' "
      . "LEFT JOIN m_prd_operator d ON d.empid = a.exe_empid "
      . "WHERE a.line_id = '$line_id' AND a.prd_dt = '$prd_dt' AND a.shift = '$shift' AND a.prd_seq = '$prd_seq'";
    // echo $sql;
    // die();
    $stmt = $conn->prepare($sql);
    if ($stmt->execute() or die($stmt->errorInfo()[2])) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $return[] = $row;
      }
    }
    $stmt = null;
    $conn = null;
    return $return;
  }

  public function insertStop($param)
  {
    $return = array();
    if (empty($param)) {
      $return["status"] = false;
      $return["message"] = "Data Empty";
    } else {
      $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
      $sql = "INSERT INTO t_prd_daily_stop (line_id, prd_dt, shift, prd_seq, stop_seq, start_time, end_time, stop_time, qty_stc, stop_id, remarks, action_id, exe_empid) "
        . "values (:line_id, :prd_dt, :shift, :prd_seq, (select coalesce(max(stop_seq),0)+1 as stop_seq FROM t_prd_daily_stop where line_id = :line_id2 AND prd_dt = :prd_dt2 AND shift = :shift2 AND prd_seq = :prd_seq2), :start_time, :end_time, :stop_time, :qty_stc, :stop_id, :remarks, :action_id, :exe_empid) ";
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(":line_id", $param["line_id"], PDO::PARAM_STR);
      $stmt->bindValue(":prd_dt", $param["prd_dt"], PDO::PARAM_STR);
      $stmt->bindValue(":shift", $param["shift"], PDO::PARAM_STR);
      $stmt->bindValue(":prd_seq", $param["prd_seq"], PDO::PARAM_STR);
      $stmt->bindValue(":line_id2", $param["line_id"], PDO::PARAM_STR);
      $stmt->bindValue(":prd_dt2", $param["prd_dt"], PDO::PARAM_STR);
      $stmt->bindValue(":shift2", $param["shift"], PDO::PARAM_STR);
      $stmt->bindValue(":prd_seq2", $param["prd_seq"], PDO::PARAM_STR);
      $stmt->bindValue(":start_time", $param["start_time"], PDO::PARAM_STR);
      $stmt->bindValue(":end_time", $param["end_time"], PDO::PARAM_STR);
      $stmt->bindValue(":stop_time", $param["stop_time"], PDO::PARAM_STR);
      $stmt->bindValue(":qty_stc", $param["qty_stc"], PDO::PARAM_STR);
      $stmt->bindValue(":stop_id", $param["stop_id"], PDO::PARAM_STR);
      $stmt->bindValue(":remarks", $param["remarks"], PDO::PARAM_STR);
      $stmt->bindValue(":action_id", $param["action_id"], PDO::PARAM_STR);
      $stmt->bindValue(":exe_empid", $param["exe_empid"], PDO::PARAM_STR);

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

  public function deleteStop($line, $date, $shift, $prd_seq, $stop_seq)
  {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "DELETE FROM t_prd_daily_stop WHERE line_id = '$line' AND prd_dt = '$date' AND shift = '$shift' AND prd_seq = '$prd_seq' AND stop_seq = '$stop_seq'";

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

  public function getPrdStop($line, $date, $shift, $prd_seq, $stop_seq)
  {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT a.*, b.type1, b.type2 FROM t_prd_daily_stop a "
      . "INNER JOIN m_prd_stop_reason_action b ON b.srna_id = a.stop_id "
      . "WHERE a.line_id = '$line' AND a.prd_dt = '$date' AND a.shift = '$shift' AND a.prd_seq = '$prd_seq' AND a.stop_seq = '$stop_seq' ";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute() or die($stmt->errorInfo()[2])) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $return = $row;
      }
    }
    $stmt = null;
    $conn = null;
    return $return;
  }

  public function getPersonById($line, $date, $shift)
  {
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "select b.empid, b.name1 from t_prd_daily_h a 
            inner join m_prd_operator b ON b.empid = a.ldid 
            where a.line_id = '$line' AND TO_CHAR(a.prd_dt,'YYYYMMDD') = '$date' AND a.shift = '$shift' 
            union 
            select b.empid, b.name1 from t_prd_daily_h a 
            inner join m_prd_operator b ON b.empid = a.jpid 
            where a.line_id = '$line' AND TO_CHAR(a.prd_dt,'YYYYMMDD') = '$date' AND a.shift = '$shift' 
            union 
            select b.empid, b.name1 from t_prd_daily_h a 
            inner join m_prd_operator b ON b.empid = a.op1id 
            where a.line_id = '$line' AND TO_CHAR(a.prd_dt,'YYYYMMDD') = '$date' AND a.shift = '$shift' 
            union 
            select b.empid, b.name1 from t_prd_daily_h a 
            inner join m_prd_operator b ON b.empid = a.op2id 
            where a.line_id = '$line' AND TO_CHAR(a.prd_dt,'YYYYMMDD') = '$date' AND a.shift = '$shift' 
            union 
            select b.empid, b.name1 from t_prd_daily_h a 
            inner join m_prd_operator b ON b.empid = a.op3id 
            where a.line_id = '$line' AND TO_CHAR(a.prd_dt,'YYYYMMDD') = '$date' AND a.shift = '$shift' 
            union 
            select b.empid, b.name1 from t_prd_daily_h a 
            inner join m_prd_operator b ON b.empid = a.op4id 
            where a.line_id = '$line' AND TO_CHAR(a.prd_dt,'YYYYMMDD') = '$date' AND a.shift = '$shift' 
            order by name1 asc";
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

  public function getNGType()
  {
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT pval1 as ng_type, pval2 as name1, pval3 as ng_group FROM m_param WHERE pid = 'NG_TYPE'";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute() or die($stmt->errorInfo()[2])) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $return[] = $row;
      }
    }
    $stmt = null;
    $conn = null;
    return $return;
  }

  public function getNGList($line_id, $prd_dt, $shift, $prd_seq)
  {
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT a.*, b.pval2 as ng_type_text, c.name1 as crt_by_name "
      . "FROM t_prd_daily_ng a "
      . "LEFT JOIN m_param b ON b.pid = 'NG_TYPE' and b.pval1 = a.ng_type "
      . "LEFT JOIN m_user c ON c.usrid = a.crt_by "
      . "WHERE a.line_id = '$line_id' AND TO_CHAR(a.prd_dt,'YYYYMMDD') = '$prd_dt' AND a.shift = '$shift' AND a.prd_seq = '$prd_seq'";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute() or die($stmt->errorInfo()[2])) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $return[] = $row;
      }
    }
    $stmt = null;
    $conn = null;
    return $return;
  }

  public function insertNG($param = array())
  {
    $return = array();
    if (empty($param)) {
      $return["status"] = false;
      $return["message"] = "Data Empty";
    } else {
      $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
      $sql = "INSERT INTO t_prd_daily_ng (line_id, prd_dt, shift, prd_seq, ng_seq, ng_type, ng_qty, loc_x, loc_y, crt_by) "
        . "values (:line_id, :prd_dt, :shift, :prd_seq, (select coalesce(max(ng_seq),0)+1 as ng_seq FROM t_prd_daily_ng where line_id = :line_id2 AND prd_dt = :prd_dt2 AND shift = :shift2 AND prd_seq = :prd_seq2), :ng_type, :ng_qty, :loc_x, :loc_y, :crt_by) ";
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(":line_id", $param["line_id"], PDO::PARAM_STR);
      $stmt->bindValue(":prd_dt", $param["prd_dt"], PDO::PARAM_STR);
      $stmt->bindValue(":shift", $param["shift"], PDO::PARAM_STR);
      $stmt->bindValue(":prd_seq", $param["prd_seq"], PDO::PARAM_STR);
      $stmt->bindValue(":line_id2", $param["line_id"], PDO::PARAM_STR);
      $stmt->bindValue(":prd_dt2", $param["prd_dt"], PDO::PARAM_STR);
      $stmt->bindValue(":shift2", $param["shift"], PDO::PARAM_STR);
      $stmt->bindValue(":prd_seq2", $param["prd_seq"], PDO::PARAM_STR);

      $stmt->bindValue(":ng_type", $param["ng_type"], PDO::PARAM_STR);
      $stmt->bindValue(":ng_qty", $param["ng_qty"], PDO::PARAM_STR);
      $stmt->bindValue(":loc_x", $param["loc_x"], PDO::PARAM_STR);
      $stmt->bindValue(":loc_y", $param["loc_y"], PDO::PARAM_STR);
      $stmt->bindValue(":crt_by", $param["crt_by"], PDO::PARAM_STR);

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

  public function deleteNG($line_id, $date, $shift, $prd_seq, $ng_seq)
  {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "DELETE FROM t_prd_daily_ng WHERE line_id = '$line_id' AND prd_dt = '$date' AND shift = '$shift' AND prd_seq = '$prd_seq' AND ng_seq = '$ng_seq'";

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

  public function getNGByID($line_id, $date, $shift, $prd_seq, $ng_seq)
  {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT a.*, b.dies_id FROM t_prd_daily_ng a, t_prd_daily_i b "
      . "WHERE a.line_id = b.line_id "
      . "AND a.prd_dt = b.prd_dt "
      . "AND a.shift = b.shift "
      . "AND a.prd_seq = b.prd_seq "
      . "AND a.line_id = b.line_id "
      . "AND a.prd_dt = '$date' "
      . "AND a.shift = '$shift' "
      . "AND a.prd_seq = '$prd_seq' "
      . "AND a.ng_seq = '$ng_seq' ";

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

  public function getSummaryByDate($prd_dt)
  {
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "select a.line_id, a.shift, b.name1 as line_name, c.pval1 as shift_name, 
            (select coalesce(sum(pln_qty),0) as pln_qty from t_prd_daily_i where line_id = a.line_id and prd_dt = a.prd_dt and shift = a.shift), 
            (select coalesce(sum(prd_qty),0) as prd_qty from t_prd_daily_i where line_id = a.line_id and prd_dt = a.prd_dt and shift = a.shift),
            (select coalesce(sum(ng_qty),0) as ng_qty from t_prd_daily_ng where line_id = a.line_id and prd_dt = a.prd_dt and shift = a.shift) 
            from t_prd_daily_h a 
            inner join m_prd_line b ON b.line_id = a.line_id 
            inner join m_param c ON c.pid = 'SHIFT' and c.seq = a.shift 
            where a.prd_dt = '$prd_dt'";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute() or die($stmt->errorInfo()[2])) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $row["tot_qty"] = $row["prd_qty"] + $row["ng_qty"];
        $row["efficiency"] = round(($row["prd_qty"] / $row["pln_qty"]) * 100, 2);
        $return[] = $row;
      }
    }
    $stmt = null;
    $conn = null;
    return $return;
  }

  public function updateTargetDailyI($line, $date, $shift, $seq, $prd_time, $pln_qty)
  {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "UPDATE t_prd_daily_i SET pln_qty = '$pln_qty', prd_time = '$prd_time' "
      . "WHERE line_id = '$line' AND prd_dt = '$date' AND shift = '$shift' AND prd_seq = '$seq'";

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

  public function getDataScan($line_name, $date_time_start, $date_time_end)
  {
    $curl = curl_init();
    $url = str_replace(" ", "%20", "http://avicenna-dev:8081/trace/api/getqty/" . $line_name . "/" . $date_time_start . "/" . $date_time_end);

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec($curl);
    curl_close($curl);
    $data = json_decode($result, 1);

    return $data;
  }
}