<?php
if ($action == "daily_production") {
  $template["group"] = "Report";
  $template["menu"] = "Daily Production";
  $template["submenu"] = "View Summary";
  $data["list"];
  $class = new Reporting();
  $param = new Param();
  $class2 = new Production();
  $member = new Member();
  $dies = new Dies();
  $stop = new Stop();
  $material = new Material();

  $date_from = date('Ymd');
  if (!empty($_GET["date_from"])) {
    $date_from = $_GET["date_from"];
  }

  $date_to = date('Ymd');
  if (!empty($_GET["date_to"])) {
    $date_to = $_GET["date_to"];
  }

  if (isset($_GET["print"])) {
    $line_id = $_GET["id"];
    $prd_dt = $_GET["id2"];
    $shift = $_GET["id3"];

    $date = str_replace("-", "", $prd_dt);

    $data_header = $class2->getHeaderById($line_id, $date, $shift);
    $data["list"] = $class->getList2($line_id, $prd_dt, $shift);
    $data2["list"] = $class->getList3($line_id, $prd_dt, $shift);
    $data3["list"] = $class->getPrdTime($line_id, $prd_dt, $shift);

    $time_start = $data["list"][0]["time_start"];
    $time_end = $data["list"][7]["time_end"];

    // $start = DateTime::createFromFormat('H:i', $time_start);
    // $end = DateTime::createFromFormat('H:i', $time_end);

    // if ($end < $start) {
    //   $end->modify('+1 day');
    // }

    // $diff_in_minutes = ($end->getTimestamp() - $start->getTimestamp()) / 60;
    $nett_opr = $data3["list"][0]["nett_opr"];
    $tot_prd = $data["list"][7]["tot_prd_qty"];
    $tot_ng = $data["list"][7]["tot_ng2"];
    $qty_lastman = $tot_prd - $tot_ng;
    $loss_time = $data2["list"][0]["loss_time"];
    $cctime = $data2["list"][0]["cctime"];

    $prd_time = array();
    foreach ($data["list"] as $list) {
      $prd_time[] = $list["prd_time"];
    }
    $prdtime = array_sum($prd_time);
    $efficiency = (($tot_prd - $tot_ng) * $cctime / 60) / $prdtime;
    $roundEff2 = round($efficiency, 3);
    $totalEff2 = $roundEff2 * 100;
    $losstime_persen = $loss_time / $nett_opr * 100;
    $roundloss = round($losstime_persen, 2);

    $ril = $data2["list"][0]["ril"];
    $persen_ril = $ril * $cctime / 60 / $nett_opr * 100;
    $roundril = round($persen_ril, 2);

    $rol = $data2["list"][0]["rol"];
    $persen_rol = $rol * $cctime / 60 / $nett_opr * 100;
    $roundrol = round($persen_rol, 2);


    $total = $totalEff2 + $roundloss + $roundril + $roundrol;

    require(TEMPLATE_PATH . "/t_print_report.php");
  } elseif (isset($_GET["id"])) {
    $step = $_GET["step"];
    if (isset($_GET["step"])) {
      if ($step == "2") {
        if ($id == "0") {
          $data["data"] = array();
        } else {
          $line_id = $_GET["id"];
          $prd_dt = $_GET["id2"];
          $shift = $_GET["id3"];
          $data["data"] = $class->getById($line_id, $prd_dt, $shift);
        }
        $line_id = $_GET["id"];
        $prd_dt = $_GET["id2"];
        $shift = $_GET["id3"];

        $date = str_replace("-", "", $prd_dt);

        $data["list"] = $class->getList2($line_id, $prd_dt, $shift);
        $data2["list"] = $class->getList3($line_id, $prd_dt, $shift);
        $data3["list"] = $class->getPrdTime($line_id, $prd_dt, $shift);

        $time_start = $data["list"][0]["time_start"];
        $time_end = $data["list"][7]["time_end"];

        // $start = DateTime::createFromFormat('H:i', $time_start);
        // $end = DateTime::createFromFormat('H:i', $time_end);

        // if ($end < $start) {
        //   $end->modify('+1 day');
        // }

        // $diff_in_minutes = ($end->getTimestamp() - $start->getTimestamp()) / 60;
        // $diff_in_hours = $diff_in_minutes / 60;

        $loss_time = $data2["list"][0]["loss_time"];
        $loss_tpln = ($total_waktu - floatval($loss_time));

        $tot_prd = $data["list"][7]["tot_prd_qty"];

        $tot_ng = $data["list"][7]["tot_ng2"];

        $qty_lastman = $tot_prd - $tot_ng;

        $nett_opr = $data3["list"][0]["nett_opr"];

        $data_header = $class2->getHeaderById($line_id, $date, $shift);

        $cctime = $data2["list"][0]["cctime"];

        $losstime_persen = $loss_time / $nett_opr * 100;
        $roundloss = round($losstime_persen, 2);

        $ril = $data2["list"][0]["ril"];
        $persen_ril = $ril * $cctime / 60 / $nett_opr * 100;
        $roundril = round($persen_ril, 2);

        $rol = $data2["list"][0]["rol"];
        $persen_rol = $rol * $cctime / 60 / $nett_opr * 100;
        $roundrol = round($persen_rol, 2);

        // echo $rol;
        // echo '<br>';
        // echo $loss_time;
        // die();

        $prd_time = array();
        foreach ($data["list"] as $list) {
          $prd_time[] = $list["prd_time"];
        }
        $prdtime = array_sum($prd_time);
        $efficiency = (($tot_prd - $tot_ng) * $cctime / 60) / $prdtime;
        $roundEff2 = round($efficiency, 3);
        $totalEff2 = $roundEff2 * 100;

        $total = $totalEff2 + $roundloss + $roundril + $roundrol;
        require(TEMPLATE_PATH . "/t_report_daily_production2.php");
      } elseif ($step == "detail") {
        $line = $_GET["id"];
        $date = $_GET["id2"];
        $shift = $_GET["id3"];
        $seq = $_GET["id4"];

        $prd_dt = str_replace("-", "", $date);

        $list_stop = $stop->getList('S', "U");
        $list_action = $stop->getList('A');
        $list_person = $class2->getPersonById($line, $prd_dt, $shift);
        $data_item_dtl = $class2->getItemById($line, $prd_dt, $shift, $seq);
        $data_stop = $class2->getStopList($line, $prd_dt, $shift, $seq);

        $list_ng_type = $class2->getNGType();
        $data_ng = $class2->getNGList($line, $prd_dt, $shift, $seq);
        $template["submenu"] = $data_item_dtl["line_name"];

        $dies_list = $dies->getListDies($line);
        $matlist = $material->getListMaterial();
        $exe_stop = $class2->getStopExeReport($line, $date, $shift, $seq);
        require(TEMPLATE_PATH . "/t_report_daily_production_detail.php");
      }
    }
  } else {
    $prd_year = $_GET["prd_year"];
    $prd_month = $_GET["prd_month"];
    $shift = $_GET["shift"];
    $line_id = $_GET["line_id"];
    $ldid = $_GET["ldid"];
    $jpid = $_GET["jpid"];

    $shiftlist = $param->getListShift(); 
    $line = $dies->getLineByType();
    $data["list"] = $class->getList($date_from, $date_to, $prd_year, $prd_month, $shift, $line_id, $ldid, $jpid);
    require(TEMPLATE_PATH . "/t_report_daily_production.php");
  }
}

if ($action == "report_detail") {
  $template["group"] = "Report";
  $template["menu"] = "Daily Production";
  $template["submenu"] = "View Details";
  $report = new Reporting();
  $param = new Param();
  $dies = new Dies();

  $date_from = date('Ymd');
  if (!empty($_GET["date_from"])) {
    $date_from = $_GET["date_from"];
  }

  $date_to = date('Ymd');
  if (!empty($_GET["date_to"])) {
    $date_to = $_GET["date_to"];
  }

  $shift = $_GET["shift"];
  $line_id = $_GET["line_id"];
  $ldid = $_GET["ldid"];
  $jpid = $_GET["jpid"];

  $shiftlist = $param->getListShift();  
  $line = $dies->getLineByType();
  $data["list"] = $report->getReportDetail($date_from, $date_to, $shift, $line_id, $ldid, $jpid);
  require(TEMPLATE_PATH . "/t_report_detail.php");
}

if ($action == "report_stop") {
  $template["group"] = "Report";
  $template["menu"] = "Daily Production";
  $template["submenu"] = "View Details Stop";
  $report = new Reporting();
  $param = new Param();
  $dies = new Dies();

  $date_from = date('Ymd');
  if (!empty($_GET["date_from"])) {
    $date_from = $_GET["date_from"];
  }

  $date_to = date('Ymd');
  if (!empty($_GET["date_to"])) {
    $date_to = $_GET["date_to"];
  }

  $shift = $_GET["shift"];
  $line_id = $_GET["line_id"];
  $ldid = $_GET["ldid"];
  $jpid = $_GET["jpid"];

  $shiftlist = $param->getListShift(); 
  $line = $dies->getLineByType();
  $data["list"] = $report->getReportStop($date_from, $date_to, $shift, $line_id, $ldid, $jpid);
  require(TEMPLATE_PATH . "/t_report_stop.php");
}

if ($action == "r_losstime") {
  $template["group"] = "Report";
  $template["menu"] = "Losstime";
  $report = new Reporting();
  $dies = new Dies();

  $date_from = date('Ymd');
  if (!empty($_GET["date_from"])) {
    $date_from = $_GET["date_from"];
  }

  $date_to = date('Ymd');
  if (!empty($_GET["date_to"])) {
    $date_to = $_GET["date_to"];
  }

  $line = $dies->getLineByType();
  $data["list"] = $report->getLossTime($date_from, $date_to);
  require(TEMPLATE_PATH . "/t_report_losstime.php");
}