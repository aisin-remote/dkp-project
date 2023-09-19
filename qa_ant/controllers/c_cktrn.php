<?php

if ($action == "cheksheet_trans") {
  $template["group"] = "Transaction";
  $template["menu"] = "Checksheet";
  $class = new Checksheet();
  $ckitm = new ChecklistItem();
  $msdev = new MeasureDevice();
  $shift_list = $class->getShiftList();
  $type_list = $class->getTypeList();
  $filter_date1 = date("Y-m-d");
  if (!empty($_REQUEST["date1"])) {
    $filter_date1 = $_REQUEST["date1"];
  }
  $filter_shift = "1";
  if (!empty($_REQUEST["shift"])) {
    $filter_shift = $_REQUEST["shift"];
  }
  $filter_type1 = $_REQUEST["type1"];
  
  if(isset($_POST["save"])) {
    $param = $_POST;
    $param_itm = [];
    $ic = 0;
    foreach($param["actual"] as $key=>$value) {
      $param_itm[$ic]["itm_id"] = $key;
      $param_itm[$ic]["actual"] = $value;
      $param_itm[$ic]["result1"] = $param["result1"][$key];
      $ic++;
    }
    
    if($param["save_type"] == "I") {
      $param_hdr["date1"] = $param["date1"];
      $param_hdr["shift"] = $param["shift"];
      $param_hdr["type1"] = $param["type1"];
      $param_hdr["part_no"] = "484120-11180<br>484120-11190";
      $param_hdr["crt_by"] = $_SESSION[LOGIN_SESSION];
      $save_hdr = $class->insertHeader($param_hdr);
    }
    
    $save = $class->insertItem($param["date1"], $param["shift"], $param["type1"], $param_itm);
    if($save["status"] == true) {
      header("Location: ?action=".$action."&date1=".$param["date1"]."&shift=".$param["shift"]."&type1=".$param["type1"]."&success=Data%20Saved.");
    } else {
      $error = $save["message"];
      header("Location: ?action=".$action."&date1=".$param["date1"]."&shift=".$param["shift"]."&type1=".$param["type1"]."&error=$error.");
    }
    die();
  }
  
  if (!empty($filter_type1)) {
    //cek data apakah ada, jika ada tampilkan data checksheet
    //jika belum ada tampilkan page create checksheet
    $data_hdr = $class->getHdrById($filter_date1, $filter_shift, $filter_type1);
    $data_itm = [];
    if(!empty($data_hdr)) {
      $save_type = "U";
      $data_itm = $class->getItmById($filter_date1, $filter_shift, $filter_type1);
      $template["sub_menu"] = "Edit / ".$data_hdr["date1"]." / ".$data_hdr["shift_name"]." / ".$data_hdr["type_text"]."";
    } else {
      $save_type = "I";
      $template["sub_menu"] = "New";
    }
    $itm_list = $ckitm->getList();
    $dev_list = $msdev->getList();
    require(TEMPLATE_PATH . "/t_cktrn_02.php");
  } else {
    $data_hdr = [];
    if (!empty($filter_date1) && !empty($filter_shift)) {
      $data_hdr = $class->getHdrByDate($filter_date1, $filter_shift);
    }

    if (!empty($data_hdr)) {
      for ($i = 0; $i < count($type_list); $i++) {
        $type_list[$i]["bg_color"] = "btn-secondary";
        foreach ($data_hdr as $hdr) {
          if ($hdr["type1"] == $type_list[$i]["type1"]) {
            $type_list[$i]["bg_color"] = "btn-primary";
            break;
          }
        }
      }
    } else {
      for ($i = 0; $i < count($type_list); $i++) {
        $type_list[$i]["bg_color"] = "btn-secondary";
      }
    }
    $template["sub_menu"] = "Overview";
    require(TEMPLATE_PATH . "/t_cktrn_01.php");
  }
}
?>