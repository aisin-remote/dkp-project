<?php 
if($action == "scn_incomplete") {  
  $template["group"] = "Transaction";
  $template["menu"] = "Incomplete Loading List";
  $class = new Transaction();
  $cls_rep = new Report();
  $cls_ldlist = new LoadingList();
  
  if(isset($_POST["set_complete"])) {
    $remarks = $_POST["remarks"];
    $ldnum = $_POST["ldnum"];
    $data_hdr = $class->getById($ldnum);
    $data_itm = $cls_rep->getList2($ldnum);
    if(!empty($data_itm)) {
      foreach($data_itm as $itm) {
        $sisa = intval($itm["menge"]) - intval($itm["wmeng"]);
        for($i = 0; $i<$sisa; $i++) {
          //buat record baru di t_io_ldlist_dtl
          $param_dtl = [];
          $param_dtl[0]["kanban_i"] = $remarks;
          $param_dtl[0]["kanban_e"] = "000";
          $param_dtl[0]["matnr"] = $itm["matnr"];
          $param_dtl[0]["matn1"] = $itm["matn1"];
          $param_dtl[0]["crt_by"] = $_SESSION[LOGIN_SESSION];
          $param_dtl[0]["pallet_id"] = "";
          $param_dtl[0]["cycle1"] = $data_hdr["cycle1"];
          $param_dtl[0]["kanban_i_srl"] = "000";
          $param_dtl[0]["is_rfid"] = "";
          $save = $cls_ldlist->insertDetail($itm["ldnum"], $itm["ldseq"], $param_dtl);
          //update wmeng loading list i
          $cls_ldlist->updateWmeng($itm["ldnum"],$itm["ldseq"], 1);
        }
        //close loading list i
        $cls_ldlist->updateStatusItem($itm["ldnum"],$itm["ldseq"], "C");
        //cek apakah semua qty sudah terpenuhi        
      } 
      $data_header = $cls_ldlist->getHeaderById($ldnum);
      if(floatval($data_header["wmeng"]) >= floatval($data_header["menge"])) {
        $cls_ldlist->updateStatus($ldnum, "C");
      }
    }
    
    $message = "Loading List Completed Manually";
    header("Location: ?action=".$action."&id=".$ldnum."&success=".$message);
  }
  
  if(isset($_GET["id"])) {
    if(isset($_GET["seq"])) {
      $list = $cls_rep->getList3($_GET["id"], $_GET["seq"]);
      require(TEMPLATE_PATH . "/t_rep_incomplete3.php");
    } else {      
      $list = $cls_rep->getList2($_GET["id"]);
      require(TEMPLATE_PATH . "/t_rep_incomplete2.php");
    }
  } else {
    $lddat_from = date('Ymd', strtotime(date('Y-m-d') . '-90 day'));
    if (!empty($_GET["date_from"])) {
      $lddat_from = $_GET["date_from"];
    }

    $lddat_to = date('Ymd');
    if (!empty($_GET["date_to"])) {
      $lddat_to = $_GET["date_to"];
    }
    
    $fil_cust = $_GET["customer"];
    
    $customer = $class->getCustomer();
    $list = $class->getIncompleteScan($lddat_from, $lddat_to, $fil_cust);
    require(TEMPLATE_PATH . "/t_rep_incomplete.php");
  }
}
?>