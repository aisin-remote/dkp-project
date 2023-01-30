<?php
if ($action == "daily_production") {
  $template["group"] = "Report";
  $template["menu"] = "Daily Production";
  $template["submenu"] = "View Details";
  $data["list"];
  $class = new Reporting();
  $class2 = new Production();
  $member = new Member();
  $dies = new Dies();
  $stop = new Stop();

  $date_from = date('Ymd');
  if (!empty($_GET["date_from"])) {
    $date_from = $_GET["date_from"];
  }

  $date_to = date('Ymd');
  if (!empty($_GET["date_to"])) {
    $date_to = $_GET["date_to"];
  }

  if (isset($_GET["id"])) {
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

        $time_start = strtotime($time_start);
        $time_end = strtotime($time_end);

        $diff = $time_end - $time_start;

        $total_waktu = $diff / 60;

        $loss_time = $data2["list"][0]["loss_time"];

        $loss_tpln = ($total_waktu - floatval($loss_time));

        $tot_prd = $data["list"][7]["tot_prd_qty"];

        $tot_ng = $data["list"][7]["tot_ng2"];

        $qty_lastman = $tot_prd - $tot_ng;

        $nett_opr = $data3["list"][0]["nett_opr"];

        $data_header = $class2->getHeaderById($line_id, $date, $shift);

        // var_dump($line_id, $prd_dt, $shift);
        // die();

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

    $data["list"] = $class->getList($date_from, $date_to, $prd_year, $prd_month, $shift, $line_id, $ldid, $jpid);
    require(TEMPLATE_PATH . "/t_report_daily_production.php");
  }
}
