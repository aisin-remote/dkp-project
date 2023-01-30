<?php 
if($action == "api_get_ldlist") {
  $ldnum = substr($_REQUEST["ldnum"], 0, -1);
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
      $matnr[] = $itm["matnr"];
    }
    $filtered_matnr = array_unique($matnr);
    $master_rfid = $c_rfid->getRfidList($filtered_matnr);
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
        $param_cust["lifnr"] = $data[0]["customer_code"];
        $param_cust["name1"] = $data[0]["customer_name"];
        $param_cust["crt_by"] = $crt_by;
        $c_cust->insert($param_cust);
      }
      //cek material apakah sudah ada
      $cek_mat = $c_mara->isExist($data[0]["i_matnr"]);
      if($cek_mat > 0) {

      } else {
        //insert material
        $param_mat = [];
        $param_mat["matnr"] = $data[0]["i_matnr"];
        $param_mat["matn1"] = $data[0]["e_matnr"];
        $param_mat["name1"] = $data[0]["maktx"];
        $param_mat["crt_by"] = $crt_by;
        $c_mara->insert($param_mat);
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
        $matnr[] = $itm["matnr"];
      }
      $filtered_matnr = array_unique($matnr);
      $master_rfid = $c_rfid->getRfidList($filtered_matnr);
      $return["status"] = true;
      $return["data_hdr"] = $data_hdr;
      $return["data_itm"] = $data_itm;
      $return["master_barcode"] = $master_barcode;
      $return["rfid_list"] = $master_rfid;
    } else {
      $return["status"] = false;
      $return["message"] = "Loading List tidak ditemukan!";
    }
  }
  
  echo json_encode($return);
}

if($action == "api_save_ldlist") {
  $class = new LoadingList();
  $data_scanning = json_decode($_REQUEST["data_scanning"],true);
  //pilah pilah dulu data per loading list
  $data_loading_list = [];
  foreach($data_scanning as $row) {
    $data_loading_list[] = $row["ldnum"];
  }
  $ldlist = array_unique($data_loading_list);
  
  //pilah2 data per loading list item
  $message = "";
  $xstatus = true;
  foreach($ldlist as $ldnum) {
    $data_itm = [];
    $i = 0;
    foreach($data_scanning as $row) {
      if($row["ldnum"] == $ldnum) {
        $data_itm[$i]["ldnum"] = $ldnum;
        $data_itm[$i]["ldseq"] = $row["ldseq"];
        $data_itm[$i]["matnr"] = $row["matnr"];
        $param_dtl = [];
        $x = 0;
        foreach($data_scanning as $xrow) {
          if($xrow["ldnum"] == $data_itm[$i]["ldnum"]) {
            $data_itm[$i]["wmeng"] += 1;
            $param_dtl[$x]["kanban_i"] = $xrow["kanban_i"];
            $param_dtl[$x]["kanban_e"] = $xrow["kanban_e"];
            $param_dtl[$x]["matnr"] = $xrow["matnr"];
            $param_dtl[$x]["matn1"] = $xrow["matn1"];
            $param_dtl[$x]["crt_by"] = $xrow["crt_by"];
            $param_dtl[$x]["pallet_id"] = $xrow["pallet_id"];
            $x++;
          }
        }
        
        //insert detail
        $insert_dtl = $class->insertDetail($data_itm[$i]["ldnum"], $data_itm[$i]["ldseq"], $param_dtl);
        if($insert_dtl["status"] == true) {
          //update data wmeng itm
          $update_itm = $class->updateWmeng($data_itm[$i]["ldnum"], $data_itm[$i]["ldseq"], $data_itm[$i]["wmeng"]);
          if($update_itm["status"] == true) {
            $message .= "Loading List[".$data_itm[$i]["ldnum"]."], Part No[".$data_itm[$i]["matnr"]."], OK [Server]<br>";
          } else {
            $message .= $update_itm["message"]."<br>";
            $xstatus = false;
          }            
        } else {
          $message .= $insert_dtl["message"]."<br>";
          $xstatus = false;
        }
          
      }
    }
  }
  
  $return["status"] = true;
  $return["xstatus"] = $xstatus;
  if(empty($message)) {
    $return["message"] = "Kanban OK, data scan tersimpan [server]";
  } else {
    $return["message"] = $message;
  }
  echo json_encode($return); 
}
?>