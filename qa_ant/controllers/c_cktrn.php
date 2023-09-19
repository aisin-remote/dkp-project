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