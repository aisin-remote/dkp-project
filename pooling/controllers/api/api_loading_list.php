<?php 
if($action == "api_get_ldlist") {
  if(strlen($_REQUEST["ldnum"]) > 11) {
    $ldnum = substr($_REQUEST["ldnum"], 0, -1);
  } else {
    $ldnum = $_REQUEST["ldnum"];
  }
  $crt_by = $_REQUEST["crt_by"];
  $return = [];
  $class = new LoadingList();
  $c_mara = new Material();
  $c_cust = new Customer();
  $c_rfid = new KanbanRFID();
  $c_barc = new KanbanBarcode();
  
  //cek dulu apakah loading list sudah pernah ada di system
  $cek_ld = $class->isExist($ldnum);
  if($cek_ld > 0) {
    //getData yang diperlukan oleh handheld
    //matnr, matn1, target_qty(menge), current_qty(wmeng), kode customer, nama customer, semua nomor rfid by matnr
    $data_hdr = $class->getHeaderById($ldnum);
    $data_itm = $class->getItemById($ldnum);
    $master_barcode = $c_barc->getById($data_hdr["lifnr"]);
    $matnr = [];
    foreach($data_itm as $itm) {
      $matnr[] = trim($itm["matnr"]);
    }
    $filtered_matnr = array_unique($matnr);
    $master_rfid = [];//$c_rfid->getRfidList($filtered_matnr);
    $return["status"] = true;
    $return["data_hdr"] = $data_hdr;
    $return["data_itm"] = $data_itm;
    $return["master_barcode"] = $master_barcode;
    $return["rfid_list"] = $master_rfid;
  } else {
    $data = $class->getLoadingListFromERP($ldnum);
    if(!empty($data)) {
      //cek customer apakah sudah ada
      $cek_cust = $c_cust->isExist($data[0]["customer_code"]);
      if($cek_cust > 0) {

      } else {
        //insert customer
        $param_cust = [];
        $param_cust["lifnr"] = trim($data[0]["customer_code"]);
        $param_cust["name1"] = trim($data[0]["customer_name"]);
        $param_cust["crt_by"] = $crt_by;
        $c_cust->insert($param_cust);
      }
      //cek material apakah sudah ada
      foreach($data as $row) {
        $cek_mat = $c_mara->isExist($row["i_matnr"]);
        if($cek_mat == 0) {
          $param_mat = [];
          $param_mat["matnr"] = trim($row["i_matnr"]);
          $param_mat["matn1"] = trim($row["c_matnr"]);
          $param_mat["name1"] = trim($row["maktx"]);
          $param_mat["crt_by"] = $crt_by;
          $c_mara->insert($param_mat);
        } else {
          
        }
      }
      
      //insert loading list header and item
      $param_hdr = [];
      $param_hdr["ldnum"] = $data[0]["ldnum"];
      $param_hdr["lifnr"] = $data[0]["customer_code"];
      $param_hdr["lddat"] = $data[0]["shipping_date"]." ".$data[0]["shipping_time"];
      $param_hdr["werks"] = $data[0]["plant"];
      $param_hdr["rcvar"] = $data[0]["recvar"];
      $param_hdr["crt_by"] = $crt_by;
      $param_hdr["cycle1"] = $data[0]["cycle"];
      $param_hdr["pdsno"] = $data[0]["pds_number"];
      $param_hdr["contract"] = $data[0]["contract"];
      $class->insertHeader($param_hdr);

      $param_itm = [];
      $i = 0;
      foreach($data as $ld) {
        $param_itm[$i]["matnr"] = trim($ld["i_matnr"]);
        $param_itm[$i]["matn1"] = trim($ld["c_matnr"]);
        $param_itm[$i]["menge"] = $ld["kanban_qty"];
        $param_itm[$i]["perpack"] = $ld["perpack_qty"];
        $param_itm[$i]["totqty"] = $ld["total_qty"];
        $i++;
      }
      $class->insertItem($ldnum, $param_itm);      
      
      //terakhir baru getData yang diperlukan oleh handheld
      //matnr, matn1, target_qty(menge), current_qty(wmeng), kode customer, nama customer, semua nomor rfid by matnr
      $data_hdr = $class->getHeaderById($ldnum);
      $data_itm = $class->getItemById($ldnum);
      $master_barcode = $c_barc->getById($data_hdr["lifnr"]);
      $matnr = [];
      foreach($data_itm as $itm) {
        $matnr[] = trim($itm["matnr"]);
      }
      $filtered_matnr = array_unique($matnr);
      $master_rfid = [];//$c_rfid->getRfidList($filtered_matnr);
      $return["status"] = true;
      $return["data_hdr"] = $data_hdr;
      $return["data_itm"] = $data_itm;
      $return["master_barcode"] = $master_barcode;
      $return["rfid_list"] = $master_rfid;
    } else {
      $return["status"] = false;
      $return["message"] = "Loading [$ldnum] List tidak ditemukan!";
    }
  }
  
  echo json_encode($return);
}

if($action == "api_get_ldlist_dtl") {
  $ldnum = $_REQUEST["ldnum"];
  $ldseq = $_REQUEST["ldseq"];
  $class = new LoadingList();
  $return = [];
  $data = $class->getDetailList($ldnum, $ldseq);
  if(empty($data)) {
    $return["status"] = false;
    $return["message"] = "No Data Found";
    $return["data"] = $data;
  } else {
    $return["status"] = true;
    $return["message"] = "Data Found";
    $return["data"] = $data;
  }
  
  echo json_encode($return);
}
/*
 * API untuk save batched data
 */
if($action == "api_save_ldlist") {
  set_time_limit(0);
  $class = new LoadingList();
  $cAvic = new Avicenna();
  $data_scanning = json_decode($_REQUEST["data_scanning"],true);
  //pilah pilah dulu data per loading list
  $data_loading_list = [];
  $i = 0;
  $message = "";
  foreach($data_scanning as $row) {
    //insert 1 1 aja
    $i++;
    $data_itm = [];
    $data_itm["ldnum"] = $row["ldnum"];
    $data_itm["ldseq"] = $row["ldseq"];
    $data_itm["matnr"] = $row["matnr"];
    $data_itm["wmeng"] = 1;
    //update item
    $update_itm = $class->updateWmeng($data_itm["ldnum"], $data_itm["ldseq"], $data_itm["wmeng"]);
    //insert dtl
    $param_dtl = [];
    if($update_itm["status"] == true) {
      
      $data_header = $class->getHeaderById($data_itm["ldnum"]);
      
      $param_dtl[0]["kanban_i"] = $row["kanban_i"];
      $param_dtl[0]["kanban_e"] = $row["kanban_e"];
      $param_dtl[0]["matnr"] = $row["matnr"];
      $param_dtl[0]["matn1"] = $row["matn1"];
      $param_dtl[0]["crt_by"] = $row["crt_by"];
      $param_dtl[0]["pallet_id"] = $row["pallet_id"];
      $param_dtl[0]["cycle1"] = $data_header["cycle1"];
      
      $insert_dtl = $class->insertDetail($data_itm["ldnum"], $data_itm["ldseq"], $param_dtl);
      if($insert_dtl["status"] == true) {
        $cek_item = $class->getItemById($data_itm[$i]["ldnum"]);
        $cek_menge = 0;
        $cek_wmeng = 0;
        foreach ($cek_item as $ci) {
          $cek_menge += intval($ci["menge"]);
          $cek_wmeng += intval($ci["wmeng"]);
        }
        if($cek_menge == $cek_wmeng) {
          //jika wmeng sudah memenuhi menge, maka close
          $class->updateStatus($data_itm[$i]["ldnum"], 'C');
        }
        //mulai insert avicenna
        $interlock = $cAvic->getInterlockAvicenna();
          if($interlock == "1") {
          $data_kanban = $cAvic->explodeKanbanInternal($row["kanban_i"]);

          $param_avc["kanban_serial"] = substr($data_kanban[10], -4);
          $param_avc["back_number"] = $data_kanban[9];
          $param_avc["cycle"] = $data_header["cycle1"];
          $param_avc["customer"] = $data_header["lifnr"];
          $param_avc["npk"] = $row["crt_by"];
          $param_avc["access_token"] = $class->getAuthToken($row["crt_by"]);
          $cAvic->insertAvicenna($param_avc);
        }
        //end of insert avicenna
      } else {
        $message .= "Data ke $i Gagal Insert Kanban Ke System";
      }
    } else {
      $message .= "Data ke $i Gagal Update Qty Ke System";
    }
  }
  $return["status"] = true;
  
  if(empty($message)) {
    $return["message"] = "Kanban OK, data scan tersimpan [server]";
    $return["xstatus"] = true;
  } else {
    $return["xstatus"] = false;
    $return["message"] = $message;
  }
  echo json_encode($return);   
}
/*
 * Save per scan
 */
if($action == "api_save_ldlist_s") {
  $return = [];
  $class = new LoadingList(); 
  $cAvic = new Avicenna();
  if(empty($_REQUEST["data_kanban"])) {
    $return["status"] = false;
    $return["message"] = "Data Kanban Kosong";
    echo json_encode($return); die();
  }
  //cek dulu apakah di loading_list_dtl sudah penuh?
  $data_kanban = json_decode($_REQUEST["data_kanban"],true);
  $loading_list = $class->getItemById($data_kanban["ldnum"],$data_kanban["ldseq"]);
  if($loading_list[0]["wmeng"] >= $loading_list[0]["menge"]) {
    $return["status"] = false;
    $return["message"] = "Loading List [".$data_kanban["ldnum"]."], Item No [".$data_kanban["ldseq"]."], Part Number [".$loading_list[0]["matnr"]."]<BR>Quantity sudah terpenuhi!";
    echo json_encode($return); die();
  }
  //insert avicenna 
  $interlock = $cAvic->getInterlockAvicenna();
  $str_kanban = $data_kanban["kanban_i"];
  $str_kanban = str_replace(";"," ",$str_kanban);
  $str_kanban = str_replace(".","",$str_kanban);
  $data_kanban_i = $cAvic->explodeKanbanInternal($str_kanban);
  
  if($interlock == "1") {

    $param_avc["kanban_serial"] = substr($data_kanban_i[10], -4);
    $param_avc["back_number"] = $data_kanban_i[9];
    $param_avc["cycle"] = $data_header["cycle1"];
    $param_avc["customer"] = $data_header["lifnr"];
    $param_avc["npk"] = $data_kanban["crt_by"];
    $param_avc["access_token"] = $cAvic->getAuthToken($data_kanban["crt_by"]);
    $cAvic->insertAvicenna($param_avc);
  }
  //end of insert avicenna
  //insert detail
  $param_dtl = [];
  $param_dtl[0]["kanban_i"] = $data_kanban["kanban_i"];
  $param_dtl[0]["kanban_e"] = $data_kanban["kanban_e"];
  $param_dtl[0]["matnr"] = $data_kanban["matnr"];
  $param_dtl[0]["matn1"] = $data_kanban["matn1"];
  $param_dtl[0]["crt_by"] = $data_kanban["crt_by"];
  $param_dtl[0]["pallet_id"] = $data_kanban["pallet_id"];
  $param_dtl[0]["cycle1"] = $data_kanban["cycle"];
  $param_dtl[0]["kanban_i_srl"] = $data_kanban_i[10];
  $param_dtl[0]["is_rfid"] = $data_kanban["is_rfid"];
  
  $is_exist = $class->isDetailExist($data_kanban["ldnum"],$data_kanban["ldseq"], $data_kanban["kanban_i"]);
  if($is_exist > 0) {
    $return["status"] = false;
    $return["message"] = "Kanban Internal Sudah Pernah Di Scan!";
    echo json_encode($return); die();
  }
  
  if($data_kanban["is_rfid"] == "X") {
    $is_scanned = $class->isKanbanRFIDScanned($data_kanban["ldnum"], $data_kanban["kanban_e"]);
    if($is_scanned > 0) {
      $return["status"] = false;
      $return["message"] = "Kanban RFID Customer Sudah Pernah Di Scan!";
      echo json_encode($return); die();
    }
  }
  $insert_detail = $class->insertDetail($data_kanban["ldnum"],$data_kanban["ldseq"], $param_dtl);
  
  if($insert_detail["status"] == true) {
    //lanjut
  } else {
    $return = $insert_detail;
    echo json_encode($return);
    die();
  }
  
  //update loading list item wmeng
  $update_wmeng = $class->updateWmeng($data_kanban["ldnum"],$data_kanban["ldseq"], 1);
  if($update_wmeng["status"] == true) {
    //lanjut
  } else {
    $return = $update_wmeng;
    echo json_encode($return);
    die();
  }
  
  //cek apakah qty loading list item sudah tepenuhi
  $data_detail = $class->getItemById($data_kanban["ldnum"],$data_kanban["ldseq"]);
  if($data_detail[0]["wmeng"] >= $data_detail[0]["menge"]) {
    $class->updateStatusItem($data_kanban["ldnum"],$data_kanban["ldseq"], "C");
  }
  
  //cek apakah semua qty sudah terpenuhi
  $data_header = $class->getHeaderById($data_kanban["ldnum"]);
  
  if($data_header["wmeng"] >= $data_header["menge"]) {
    $class->updateStatus($data_kanban["ldnum"], "C");
  }
  
  //setelah semua OK kembalikan return true dan quantity total
  $return["status"] = true;
  $return["menge"] = $data_header["menge"];
  $return["wmeng"] = $data_header["wmeng"];
  $return["message"] = "Data Save OK [Server]";
  echo json_encode($return); die();
}

if($action == "api_check_kanban_i") {
  $return = [];
  $ldnum = $_REQUEST["ldnum"];
  $kanban_i = $_REQUEST["kanban_i"];
  $class = new LoadingList();
  $cAvc = new Avicenna();
  
  $str_kanban = $kanban_i;
  $str_kanban = str_replace(";"," ",$str_kanban);
  $str_kanban = str_replace(".","",$str_kanban);
  $data_kanban_i = $cAvc->explodeKanbanInternal($str_kanban);
  
  $kanban_i_srl = $data_kanban_i[10];
  $matnr = ltrim($data_kanban_i[4], '0');;
  
  $is_scanned = $class->isKanbanInternalScanned($ldnum, $kanban_i_srl);
  $return["is_scanned"] = $is_scanned;
  $return["ldnum"] = $ldnum;
  $return["srl_no"] = $kanban_i_srl;
  if($is_scanned > 0) {
    $return["status"] = false;
    $return["message"] = "Kanban Internal Sudah Pernah Di Scan!";
  } else {
    //cek apakah quantity sudah memenuhi 
    $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
    $query = "SELECT ldseq, menge, wmeng FROM t_io_ldlist_i WHERE ldnum = '$ldnum' AND matnr = '$matnr'";
    $stmt = $conn->prepare($query);
    $menge = 0;
    $wmeng = 0;
    $ldseq = 0;
    if($stmt->execute()) {
      while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
        $ldseq = $row["ldseq"];
        $menge = intval($row["menge"]);
        $wmeng = intval($row["wmeng"]);
      }
    }
    if ($wmeng >= $menge) {
      $return["status"] = false;
      $return["message"] = "Loading List [$ldnum], Item No [$ldseq], Part Number [$matnr].<BR>Quantity sudah terpenuhi!";
    } else {
      $return["status"] = true;
      $return["message"] = "Kanban Internal OK";
    }
      
  }
  echo json_encode($return);
}

if($action == "api_ldlist_check_qty") {
  
}

if($action == "api_check_rfid_matnr") {
  $return = [];
  $matnr = trim($_REQUEST["matnr"]);
  $rfid = trim($_REQUEST["rfid"]);
  
  $cRfid = new KanbanRFID();
  
  $get_material = $cRfid->getById($rfid);
  
  if(empty($get_material)) {
    $return["status"] = false;
    $return["message"] = "Kanban RFID [$rfid] NG<br>Tidak Terdaftar!";
    echo json_encode($return);
    die();
  }
  
  if($get_material["matnr"] == $matnr) {
    $return["status"] = true;
    $return["message"] = "Kanban RFID [$rfid] OK";
    $return["matnr"] = $get_material["matnr"];
  } else {
    $return["status"] = false;
    $return["message"] = "Kanban RFID [$rfid] NG<br>Incorrect Part No. [".$get_material["matnr"]."] Scanned!";
  }
  echo json_encode($return);
}
?>