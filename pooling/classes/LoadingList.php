<?php

class LoadingList {
  //put your code here
  public function getHeaderList($date_from = null, $date_to = null) {
    $return = array();
    $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
    $sql = "SELECT * FROM t_io_ldlist_h WHERE 1=1";
    $stmt = $conn->prepare($sql);
    if($stmt->execute()) {
      while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
        $return[] = $row;
      }
    }
    $stmt = null;
    $conn = null;
    return $return;
  }
  
  public function getHeaderById($id) {
    $return = array();
    $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
    $sql = "SELECT a.*, b.name1 as cust_name, TO_CHAR(a.lddat,'DD-MM-YYYY') as shipping_date, TO_CHAR(a.lddat,'HH24:MI') as shipping_time, 
            (select coalesce(sum(menge),0) as menge from t_io_ldlist_i where ldnum = a.ldnum), 
            (select coalesce(sum(wmeng),0) as wmeng from t_io_ldlist_i where ldnum = a.ldnum) 
            FROM t_io_ldlist_h a "
            . "LEFT JOIN m_io_lfa1 b ON b.lifnr = a.lifnr "
            . "WHERE a.ldnum = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":id", trim($id), PDO::PARAM_STR);
    if($stmt->execute()) {
      while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
        $return = $row;
      }
    }
    $stmt = null;
    $conn = null;
    return $return;
  }
  
  public function insertHeader($param = array()) {
    $return = array();
    if(empty($param)) {
      $return["status"] = false;
      $return["message"] = "Data Empty";
    } else {
      $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
      $sql = "INSERT INTO t_io_ldlist_h (ldnum, lifnr, lddat, werks, rcvar, stats, crt_by, crt_dt, cycle1, pdsno, contract) "
              . "values (:ldnum, :lifnr, TO_TIMESTAMP(:lddat,'YYYYMMDD HH24MISS'), :werks, :rcvar, 'N', :crt_by, CURRENT_TIMESTAMP, :cycle1, :pdsno, :contract) ";
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(":ldnum", $param["ldnum"], PDO::PARAM_STR);
      $stmt->bindValue(":lifnr", $param["lifnr"], PDO::PARAM_STR);
      $stmt->bindValue(":lddat", $param["lddat"], PDO::PARAM_STR);
      $stmt->bindValue(":werks", $param["werks"], PDO::PARAM_STR);
      $stmt->bindValue(":rcvar", $param["rcvar"], PDO::PARAM_STR);
      $stmt->bindValue(":crt_by", $param["crt_by"], PDO::PARAM_STR);
      $stmt->bindValue(":cycle1", $param["cycle1"], PDO::PARAM_STR);
      $stmt->bindValue(":pdsno", $param["pdsno"], PDO::PARAM_STR);
      $stmt->bindValue(":contract", $param["contract"], PDO::PARAM_STR);
      
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
    }
    return $return;
  }
  
  public function insertItem($ldnum, $param = array()) {
    $return = array();
    if(empty($param)) {
      $return["status"] = false;
      $return["message"] = "Data Empty";
    } else {
      $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
      $sql = "INSERT INTO t_io_ldlist_i (ldnum, ldseq, matnr, matn1, menge, wmeng, stats, perpack, totqty) "
              . "values ";
      $i = 1;
      foreach($param as $row) {
        $arr_insert[] = "('$ldnum','".$i."','".$row["matnr"]."','".$row["matn1"]."','".$row["menge"]."','0','N', '".$row["perpack"]."', '".$row["totqty"]."')";
        $i++;
      }
      $sql .= implode(",",$arr_insert);
      $stmt = $conn->prepare($sql);
      if($stmt->execute()) {
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
  
  public function getItemById($id, $ldseq = null) {
    $return = array();
    $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
    $sql = "SELECT * FROM t_io_ldlist_i WHERE ldnum = :id ";
    if(!empty($ldseq)) {
      $sql .= " AND ldseq = '$ldseq' ";
    }
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":id", $id, PDO::PARAM_STR);
    if($stmt->execute()) {
      while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
        if($row["stats"] == "N") {
          $row["row_color"] = "";
          $row["status_tx"] = "<span class='text-danger'>INCOMPLETE</span>";
        } else if($row["stats"] == "C") {
          $row["row_color"] = "table-success";
          $row["status_tx"] = "<span class='text-success'>COMPLETE</span>";
        }
        
        if($row["dstat"] == "D") {
          $row["row_color"] = "table-info";
          $row["status_tx"] = "<span class='text-success'>DELIVERED</span>";
        }
        $return[] = $row;
      }
    }
    $stmt = null;
    $conn = null;
    return $return;
  }
  
  public function insertDetail($ldnum, $ldseq, $param = array()) {
    $return = array();
    if(empty($param)) {
      $return["status"] = false;
      $return["message"] = "Data Empty";
    } else {
      $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
      
      $sql_cek = "SELECT coalesce( max(trseq),0 ) as last_seq FROM t_io_ldlist_dtl WHERE ldnum = '$ldnum' AND ldseq = '$ldseq' ";
      $stmt = $conn->prepare($sql_cek);
      $last_seq = 0;
      if($stmt->execute()) {
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
          $last_seq = $row["last_seq"];
        }
      }
      
      $sql = "INSERT INTO t_io_ldlist_dtl (ldnum, ldseq, trseq, kanban_i, kanban_e, matnr, matn1, crt_by, crt_dt, pallet_id, cycle1, kanban_i_srl, is_rfid) "
              . "values ";
      $i = $last_seq + 1;
      foreach($param as $row) {
        $arr_insert[] = "('$ldnum','".$ldseq."','".$i."','".$row["kanban_i"]."','".$row["kanban_e"]."','".$row["matnr"]."','".$row["matn1"]."','".$row["crt_by"]."',CURRENT_TIMESTAMP, '".$row["pallet_id"]."', '".$row["cycle1"]."', '".$row["kanban_i_srl"]."', '".$row["is_rfid"]."')";
        $i++;
      }
      $sql .= implode(",",$arr_insert);
      $stmt = $conn->prepare($sql);
      if($stmt->execute()) {
        $return["status"] = true;
      } else {
        $return["status"] = false;
        $error = $stmt->errorInfo();
        $return["message"] = "Loading List[".$ldnum."], Part No[".$param[0]["matnr"]."], ".trim(str_replace("\n", " ", $error[2]));
      }
      $stmt = null;
      $conn = null;
    }
    return $return;
  }
  
  public function getLoadingListFromERP($ldnum) {
    $return = [];
    $conn = new PDO(SQLSRV_DSN,SQLSRV_USERNAME,SQLSRV_PASSWORD);
    $sql = "SELECT 
            chr_cod_plant AS plant, 
            chr_cod_sykmno AS ldnum, 
            int_nub_mseq AS seq, 
            int_nub_noubin AS cycle, 
            chr_cod_keiyakusaki AS contract, 
            chr_cod_ukeire AS recvar, 
            CHR_NGP_NOUNYU AS delivery_date, 
            chr_ngp_syukka AS shipping_date, 
            chr_tim_syukka AS shipping_time, 
            CHR_COD_TOKISAKI AS customer_code, 
            CHR_MEI_NOUNYU AS customer_name, 
            CHR_COD_TKS_NOUBAN AS pds_number, 
            CHR_NUB_HINMOKU_TOK AS c_matnr, 
            CHR_NUB_HMK_HYP AS i_matnr, 
            CHR_MEI_HINMEI AS maktx, 
            CHR_COD_SEBANGOU_TOK AS c_part_id, 
            CHR_COD_SEBANGOU AS i_part_id, 
            INT_SUR_NOUSIJI as kanban_qty, 
            INT_SUR_SYUUYOU AS perpack_qty, 
            INT_SUR_JYUCYUU AS total_qty 
            FROM dbo.tt_gig_sykmeisai 
            WHERE CHR_COD_SYKMNO = '$ldnum'";
    $stmt = $conn->prepare($sql);
    if($stmt->execute()) {
      while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
        $return[] = $row;
      }
    }
    $stmt = null;
    $conn = null;
    return $return;
  }
  
  public function isExist($id) {
    $return = 0;
    $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
    $sql = "SELECT count(*) as cnt FROM t_io_ldlist_h WHERE ldnum = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":id", $id, PDO::PARAM_STR);
    if($stmt->execute()) {
      while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
        $return = $row["cnt"];
      }
    }
    $stmt = null;
    $conn = null;
    return $return;
  }
  
  public function updateWmeng($ldnum, $ldseq, $wmeng) {
    $return = [];
    $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
    //cek dulu sebelum update, apakah wmeng melebihi target
    $sql = "SELECT wmeng, menge, matnr FROM t_io_ldlist_i WHERE ldnum = '$ldnum' AND ldseq = '$ldseq' ";
    $stmt = $conn->prepare($sql);
    $cek_wmeng = 0;
    $cek_menge = 0;
    $cek_matnr = "";
    if($stmt->execute()) {
      while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
        $cek_wmeng = $row["wmeng"];
        $cek_menge = $row["menge"];
        $cek_matnr = $row["matnr"];
      }
    }
    $cek_wmeng += $wmeng;
    if($cek_wmeng > $cek_menge) {
      $return["status"] = false;
      $return["message"] = "Loading List[".$ldnum."], Part No[".$cek_matnr."], Qty Scan[$cek_wmeng] melebihi Qty Target[$cek_menge]";
      return $return;
    }
    
    $sql = "UPDATE t_io_ldlist_i SET wmeng = ( wmeng + '$wmeng' ) WHERE ldnum = '$ldnum' AND ldseq = '$ldseq' ";
    $stmt = $conn->prepare($sql);
    if($stmt->execute()) {
      $return["status"] = true;
    } else {
      $return["status"] = false;
      $error = $stmt->errorInfo();
      $return["message"] = "Loading List[".$ldnum."], Part No[".$cek_matnr."], ".trim(str_replace("\n", " ", $error[2]));
    }    
    return $return;
  }
  
  public function updateStatus($ldnum, $stats) {
    $return = [];
    $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
    $sql = "UPDATE t_io_ldlist_h SET stats = '$stats' ";
    if($stats == "C") {
      $sql .= ", cdats = CURRENT_TIMESTAMP ";
    }
    $sql .= " WHERE ldnum = '$ldnum'";
    $stmt = $conn->prepare($sql);
    $stmt = $conn->prepare($sql);
    if($stmt->execute()) {
      $return["status"] = true;
    } else {
      $return["status"] = false;
      $error = $stmt->errorInfo();
      $return["message"] = trim(str_replace("\n", " ", $error[2]));
    }    
    return $return;
  }
  
  public function updateStatusItem($ldnum, $ldseq, $stats) {
    $return = [];
    $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
    $sql = "UPDATE t_io_ldlist_i SET stats = '$stats' ";
    
    $sql .= " WHERE ldnum = '$ldnum' AND ldseq = '$ldseq' ";
    $stmt = $conn->prepare($sql);
    $stmt = $conn->prepare($sql);
    if($stmt->execute()) {
      $return["status"] = true;
    } else {
      $return["status"] = false;
      $error = $stmt->errorInfo();
      $return["message"] = trim(str_replace("\n", " ", $error[2]));
    }    
    return $return;
  }
  
  public function isDetailExist($ldnum, $ldseq, $kanban_i) {
    $return = 0;
    $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
    $sql = "SELECT count(*) as cnt, crt_dt FROM t_io_ldlist_dtl WHERE ldnum = :ldnum AND ldseq = :ldseq AND kanban_i = :kanban_i "
            . " group by crt_dt "
            . " order by crt_dt asc ";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":ldnum", $ldnum, PDO::PARAM_STR);
    $stmt->bindValue(":ldseq", $ldseq, PDO::PARAM_STR);
    $stmt->bindValue(":kanban_i", $kanban_i, PDO::PARAM_STR);
    if($stmt->execute()) {
      while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
        $return = $row;
      }
    }
    $stmt = null;
    $conn = null;
    return $return;
  }
  
  public function isKanbanInternalScanned($ldnum, $kanban_i_srl) {
    $return = 0;
    $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
    $sql = "SELECT count(*) as cnt FROM t_io_ldlist_dtl WHERE ldnum = :ldnum AND kanban_i_srl = :kanban_i_srl";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":ldnum", $ldnum, PDO::PARAM_STR);
    $stmt->bindValue(":kanban_i_srl", $kanban_i_srl, PDO::PARAM_STR);
    if($stmt->execute()) {
      while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
        $return = $row["cnt"];
      }
    }
    $stmt = null;
    $conn = null;
    return $return;
  }
  
  public function getDetailList($ldnum, $ldseq) {
    $return = [];
    $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
    $sql = "SELECT * FROM t_io_ldlist_dtl WHERE ldnum = :ldnum AND ldseq = :ldseq ORDER BY trseq ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":ldnum", $ldnum, PDO::PARAM_STR);
    $stmt->bindValue(":ldseq", $ldseq, PDO::PARAM_STR);
    if($stmt->execute()) {
      while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
        if($row["is_rfid"] == "X") {
          $row["row_color"] = "table-light";
          $row["kbn_type"] = "RFID";
        } else {
          $row["row_color"] = "table-white";
          $row["kbn_type"] = "BARCODE";
        }
        $return[] = $row;
      }
    }
    $stmt = null;
    $conn = null;
    return $return;
  }
  
  public function isKanbanRFIDScanned($ldnum, $kanban_e) {
    $return = 0;
    $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
    $sql = "SELECT count(*) as cnt FROM t_io_ldlist_dtl WHERE ldnum = :ldnum AND kanban_e = :kanban_e";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":ldnum", $ldnum, PDO::PARAM_STR);
    $stmt->bindValue(":kanban_e", $kanban_e, PDO::PARAM_STR);
    if($stmt->execute()) {
      while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
        $return = intval($row["cnt"]);
      }
    }
    $stmt = null;
    $conn = null;
    return $return;
  }
}

?>