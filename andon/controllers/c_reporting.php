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

    $data_header = $class2->getHeaderById($line_id, $prd_dt, $shift);
    $data["list"] = $class->getList2($line_id, $prd_dt, $shift);
    $data2["list"] = $class->getList3($line_id, $prd_dt, $shift);
    $data3["list"] = $class->getPrdTime($line_id, $prd_dt, $shift);
    $mats_list = $class->getMatsExcel($line_id, $prd_dt, $shift);

    require_once 'vendor/autoload.php';
    $spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();

    $spreadsheet->getActiveSheet()->mergeCells('A1:K1');

    $spreadsheet->getActiveSheet()->setCellValue('A1', "DAILY PRODUCTION REPORT");
    // $spreadsheet->getActiveSheet()->setCellValue('A2', date_format(new DateTime($prd_dt), 'd-m-Y'));

    $spreadsheet->getActiveSheet()->setCellValue('A4', "Line : " . $data_header["line_name"]);
    $spreadsheet->getActiveSheet()->setCellValue('A6', "Shift : " . $data_header["shift_name"]);
    $spreadsheet->getActiveSheet()->setCellValue('B4', "Cycle Time : " . $data["list"][0]["cctime"]);

    $spreadsheet->getActiveSheet()->setCellValue('D4', "Leader : " . $data_header["ld_name"]);
    $spreadsheet->getActiveSheet()->setCellValue('D5', "JP       : " . $data_header["jp_name"]);
    $spreadsheet->getActiveSheet()->setCellValue('D6', "Pos 1 : " . $data_header["op1_name"]);
    $spreadsheet->getActiveSheet()->setCellValue('D7', "Pos 2 : " . $data_header["op2_name"]);
    $spreadsheet->getActiveSheet()->setCellValue('D8', "Pos 3 : " . $data_header["op3_name"]);
    $spreadsheet->getActiveSheet()->setCellValue('E4', "Pos 4 : " . $data_header["op4_name"]);
    $spreadsheet->getActiveSheet()->setCellValue('E5', "Pos 5 : " . $data_header["op5_name"]);
    $spreadsheet->getActiveSheet()->setCellValue('E6', "Pos 6 : " . $data_header["op6_name"]);
    $spreadsheet->getActiveSheet()->setCellValue('E7', "Pos 7 : " . $data_header["op7_name"]);
    $spreadsheet->getActiveSheet()->setCellValue('E8', "Pos 8 : " . $data_header["op8_name"]);

    $spreadsheet->getActiveSheet()->setCellValue('A9', "Production Date : " . date_format(new DateTime($prd_dt), 'd-m-Y'));

    $spreadsheet->getActiveSheet()->setCellValue('G4', "Part Name");
    foreach ($mats_list as $key => $value) {
      $spreadsheet->getActiveSheet()->setCellValue('G' . ($key + 5), $value["name1"] . " (" . $value["backno"] . ") ");
    }
    $spreadsheet->getActiveSheet()->setCellValue('H4', "Qty Planning");
    foreach ($mats_list as $key => $value) {
      $spreadsheet->getActiveSheet()->setCellValue('H' . ($key + 5), $value["pln_qty"]);
    }

    $spreadsheet->getActiveSheet()->setCellValue('A11', "Material");
    $spreadsheet->getActiveSheet()->setCellValue('B11', "Cycle Time");
    $spreadsheet->getActiveSheet()->setCellValue('C11', "Time");
    $spreadsheet->getActiveSheet()->setCellValue('D11', "Nett Operation");
    $spreadsheet->getActiveSheet()->setCellValue('E11', "Target / Actual");
    $spreadsheet->getActiveSheet()->setCellValue('F11', "Detail NG");
    $spreadsheet->getActiveSheet()->setCellValue('G11', "NG Qty");
    $spreadsheet->getActiveSheet()->setCellValue('H11', "Abnormal Content");
    $spreadsheet->getActiveSheet()->setCellValue('I11', "Duration Stop");
    $spreadsheet->getActiveSheet()->setCellValue('J11', "Countermeasures");
    $spreadsheet->getActiveSheet()->setCellValue('K11', "PIC");
    $spreadsheet->getActiveSheet()->setCellValue('L11', "Target (O/X)");
    $spreadsheet->getActiveSheet()->setCellValue('M11', "JP Sign");
    $spreadsheet->getActiveSheet()->setCellValue('N11', "Ldr Sign");
    $spreadsheet->getActiveSheet()->setCellValue('O11', "SPV Sign");


    $i = 0;
    foreach ($data["list"] as $head) {
      $i++;
      $spreadsheet->getActiveSheet()->setCellValue('A' . ($i + 11), $head["name1"] . " (" . $head["backno"] . ") ");
      $spreadsheet->getActiveSheet()->setCellValue('B' . ($i + 11), $head["cctime"]);
      $spreadsheet->getActiveSheet()->setCellValue('C' . ($i + 11), $head["time_start"] . ' - ' . $head["time_end"]);
      $spreadsheet->getActiveSheet()->setCellValue('D' . ($i + 11), $head["prd_time"]);
      $spreadsheet->getActiveSheet()->setCellValue('E' . ($i + 11), $head["pln_qty"] . ' / ' . $head["prd_qty"]);
      $data_ng = $class->getNGList($line_id, $prd_dt, $shift, $head["prd_seq"]);
      $ngs = [];
      foreach ($data_ng as $ng) {
        $ngs[] = $ng["name1"] . " (" . $ng["ng_qty"] . ")";
      }
      $spreadsheet->getActiveSheet()->setCellValue('F' . ($i + 11), implode(", ", $ngs));
      $spreadsheet->getActiveSheet()->setCellValue('G' . ($i + 11), $head["tot_ng"]);
      // $seq1 = $i + 100;
      $data_stop = $class2->getStopList($line_id, $prd_dt, $shift, $head["prd_seq"]);
      $stop_exe = $class2->getStopExeReport($line_id, $prd_dt, $shift, $head["prd_seq"]);
      foreach ($data_stop as $detail) {
        $exec = array();
        $spreadsheet->getActiveSheet()->setCellValue('H' . ($i + 11), $detail["start_time"] . " - " . $detail["stop_name"]);
        if ($detail["stop_type"] == "P") {
          $spreadsheet->getActiveSheet()->setCellValue('I' . ($i + 11), "(" . $detail["stop_time"] . ")");
        } else {
          $spreadsheet->getActiveSheet()->setCellValue('I' . ($i + 11), $detail["stop_time"]);
        }
        // $spreadsheet->getActiveSheet()->setCellValue('G' . ($i + 11), $detail["stop_time"]);
        $spreadsheet->getActiveSheet()->setCellValue('J' . ($i + 11), ($detail["action_name"] == null) ? $detail["remarks"] : $detail["action_name"]);
        foreach ($stop_exe as $exe) {
          if ($exe["stop_seq"] == $detail["stop_seq"] && $exe["prd_seq"] == $detail["prd_seq"]) {
            $exec[] = $exe["name1"];
            // $spreadsheet->getActiveSheet()->setCellValue('J' . ($i + 11), $detail["stop_name"] . " - " . $exe["member_name"]);
          }
        }
        $spreadsheet->getActiveSheet()->setCellValue('K' . ($i + 11), implode(", ", $exec));
        $i++;
      }
    }

    $spreadsheet->getActiveSheet()->mergeCells("A" . ($i + 13) . ":A" . ($i + 14) . "")->setCellValue('A' . ($i + 13), "Material");
    $spreadsheet->getActiveSheet()->mergeCells("B" . ($i + 13) . ":B" . ($i + 14) . "")->setCellValue('B' . ($i + 13), "Waktu Kerja/Shift");
    $spreadsheet->getActiveSheet()->mergeCells("C" . ($i + 13) . ":C" . ($i + 14) . "")->setCellValue('C' . ($i + 13), "Losstime Terplanning");
    $spreadsheet->getActiveSheet()->mergeCells("D" . ($i + 13) . ":D" . ($i + 14) . "")->setCellValue('D' . ($i + 13), "Nett Time");
    $spreadsheet->getActiveSheet()->mergeCells("E" . ($i + 13) . ":E" . ($i + 14) . "")->setCellValue('E' . ($i + 13), "Loss Time (menit)");
    $spreadsheet->getActiveSheet()->mergeCells("F" . ($i + 13) . ":F" . ($i + 14) . "")->setCellValue('F' . ($i + 13), "Total Qty Produksi");
    $spreadsheet->getActiveSheet()->mergeCells("G" . ($i + 13) . ":G" . ($i + 14) . "")->setCellValue('G' . ($i + 13), "Total NG");
    $spreadsheet->getActiveSheet()->mergeCells("H" . ($i + 13) . ":H" . ($i + 14) . "")->setCellValue('H' . ($i + 13), "Effisiensi");
    $spreadsheet->getActiveSheet()->mergeCells("I" . ($i + 13) . ":I" . ($i + 14) . "")->setCellValue('I' . ($i + 13), "Loss Time (%)");
    $spreadsheet->getActiveSheet()->mergeCells("J" . ($i + 13) . ":J" . ($i + 14) . "")->setCellValue('J' . ($i + 13), "RIL Ratio (%)");

    foreach ($data2["list"] as $key => $value) {
      $spreadsheet->getActiveSheet()->setCellValue('A' . ($i + 15 + $key), $value["name1"] . " (" . $head["backno"] . ") ");
      $spreadsheet->getActiveSheet()->setCellValue('B' . ($i + 15 + $key), $value["pln_qty"]);
      $spreadsheet->getActiveSheet()->setCellValue('C' . ($i + 15 + $key), $value["loss_time_p"]);
      $spreadsheet->getActiveSheet()->setCellValue('D' . ($i + 15 + $key), $value["prd_time"]);
      $spreadsheet->getActiveSheet()->setCellValue('E' . ($i + 15 + $key), $value["loss_time"]);
      $spreadsheet->getActiveSheet()->setCellValue('F' . ($i + 15 + $key), $value["prd_qty"]);
      $spreadsheet->getActiveSheet()->setCellValue('G' . ($i + 15 + $key), $value["ng_ril"]);
      $spreadsheet->getActiveSheet()->setCellValue('H' . ($i + 15 + $key), $value["total%"]);
      $spreadsheet->getActiveSheet()->setCellValue('I' . ($i + 15 + $key), $value["loss%"]);
      $spreadsheet->getActiveSheet()->setCellValue('J' . ($i + 15 + $key), $value["ril%"]);
    }

    $spreadsheet->getActiveSheet()->getStyle('A1')->getFont()->setBold(true)->setSize(24);
    $spreadsheet->getActiveSheet()->getStyle('A11:O11')->getFont()->setBold(true);
    $spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal('center');
    $spreadsheet->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal('center');
    // $spreadsheet->getActiveSheet()->getStyle('AB')->getAlignment()->setHorizontal('center');
    $spreadsheet->getActiveSheet()->getStyle('G4:H8')->getAlignment()->setHorizontal('center');
    // $spreadsheet->getActiveSheet()->getStyle('I' . ($i + 13))->getAlignment()->setHorizontal('center');
    // $spreadsheet->getActiveSheet()->getStyle('I12:I' . ($i + 11))->getAlignment()->setWrapText(true);
    $spreadsheet->getActiveSheet()->getStyle('A11:O' . ($i + 16))->getAlignment()->setVertical('center')->setHorizontal('center');
    $spreadsheet->getActiveSheet()->getStyle('A4:B6')->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $spreadsheet->getActiveSheet()->getStyle('D4:E8')->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $spreadsheet->getActiveSheet()->getStyle('A12:O' . ($i + 11))->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $spreadsheet->getActiveSheet()->getStyle('A12:O' . ($i + 11))->getBorders()->getVertical()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $spreadsheet->getActiveSheet()->getStyle('A' . ($i + 13) . ':J' . ($i + 14))->getFont()->setBold(true);
    $spreadsheet->getActiveSheet()->getStyle('A' . ($i + 13) . ':J' . ($i + 14))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $spreadsheet->getActiveSheet()->getStyle('A11:O11')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $spreadsheet->getActiveSheet()->getStyle('G4:H8')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $spreadsheet->getActiveSheet()->getStyle('A12:O' . ($i + 16))->getAlignment()->setWrapText(true);

    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getRowDimension(11)->setRowHeight(35);
    $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
    // $spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(40);
    $spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(25);
    $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(25);
    $spreadsheet->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(25);
    $spreadsheet->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('W')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('X')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('Y')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('Z')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('AA')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('AB')->setWidth(20);

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="DailyProductionReport_' . $prd_dt . '_' . $shift . '_' . $data_header["line_name"] . '.xlsx"');
    header('Cache-Control: max-age=0');
    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $writer->save('php://output');
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

        $list_ng_type = $class2->getNGType($line);
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
  $time_start = $_GET["start_time"];
  $time_end = $_GET["end_time"];

  $shiftlist = $param->getListShift();
  $line = $dies->getLineByType();
  $time = $report->getTimeShift();
  $data["list"] = $report->getReportDetail($date_from, $date_to, $shift, $line_id, $ldid, $jpid, $time_start, $time_end);
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

if ($action == "r_temp") {
  $template["group"] = "Report";
  $template["menu"] = "Temperature";
  $report = new Reporting();

  $date_from = date('Ymd');
  if (!empty($_GET["date_from"])) {
    $date_from = $_GET["date_from"];
  }

  $date_to = date('Ymd');
  if (!empty($_GET["date_to"])) {
    $date_to = $_GET["date_to"];
  }

  $data["list"] = $report->getTemp($date_from, $date_to);
  require(TEMPLATE_PATH . "/t_report_temp.php");
}