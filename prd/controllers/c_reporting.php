<?php
if ($action == "daily_production") {
  $template["group"] = "Report";
  $template["menu"] = "Daily Production";
  $template["submenu"] = "View Summary";
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

  if (isset($_GET["print"])) {
    $line_id = $_GET["id"];
    $prd_dt = $_GET["id2"];
    $shift = $_GET["id3"];

    $data_header = $class2->getHeaderById($line_id, $prd_dt, $shift);
    $data["list"] = $class->getList2($line_id, $prd_dt, $shift);
    $data2["list"] = $class->getList3($line_id, $prd_dt, $shift);
    $data3["list"] = $class->getPrdTime($line_id, $prd_dt, $shift);
    $dies_list = $class->getDiesExcel($line_id, $prd_dt, $shift);

    require_once 'vendor/autoload.php';
    $spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();

    $spreadsheet->getActiveSheet()->mergeCells('A1:K1');
    $spreadsheet->getActiveSheet()->mergeCells('A2:K2');

    $spreadsheet->getActiveSheet()->setCellValue('A1', "DAILY PRODUCTION REPORT");
    $spreadsheet->getActiveSheet()->setCellValue('A2', date_format(new DateTime($prd_dt), 'd-m-Y'));

    $spreadsheet->getActiveSheet()->setCellValue('A4', "Line");
    $spreadsheet->getActiveSheet()->setCellValue('B4', ": " . $data_header["line_name"]);
    $spreadsheet->getActiveSheet()->setCellValue('A6', "Shift");
    $spreadsheet->getActiveSheet()->setCellValue('B6', ": " . $data_header["shift_name"]);
    $spreadsheet->getActiveSheet()->setCellValue('C4', "Cycle Time");
    $spreadsheet->getActiveSheet()->setCellValue('D4', ": " . $data["list"][0]["cctime"]);

    $spreadsheet->getActiveSheet()->setCellValue('F4', "JP");
    $spreadsheet->getActiveSheet()->setCellValue('G4', ": " . $data_header["jp_name"]);
    $spreadsheet->getActiveSheet()->setCellValue('F5', "Lastman");
    $spreadsheet->getActiveSheet()->setCellValue('G5', ": " . $data_header["ld_name"]);
    $spreadsheet->getActiveSheet()->setCellValue('F6', "Pos 1");
    $spreadsheet->getActiveSheet()->setCellValue('G6', ": " . $data_header["op1_name"]);
    $spreadsheet->getActiveSheet()->setCellValue('F7', "Pos 2");
    $spreadsheet->getActiveSheet()->setCellValue('G7', ": " . $data_header["op2_name"]);
    $spreadsheet->getActiveSheet()->setCellValue('F8', "Pos 3");
    $spreadsheet->getActiveSheet()->setCellValue('G8', ": " . $data_header["op3_name"]);
    $spreadsheet->getActiveSheet()->setCellValue('F9', "Pos 4");
    $spreadsheet->getActiveSheet()->setCellValue('G9', ": " . $data_header["op4_name"]);

    $spreadsheet->getActiveSheet()->setCellValue('I4', "Model");
    foreach ($dies_list as $key => $value) {
      $spreadsheet->getActiveSheet()->setCellValue('I' . ($key + 5), $value["dies_id"]);
    }
    $spreadsheet->getActiveSheet()->setCellValue('J4', "Qty Target Terplanning");
    foreach ($dies_list as $key => $value) {
      $spreadsheet->getActiveSheet()->setCellValue('J' . ($key + 5), $value["pln_qty"]);
    }

    $spreadsheet->getActiveSheet()->setCellValue('A11', "Production Time");
    $spreadsheet->getActiveSheet()->setCellValue('B11', "Nett Operasi");
    $spreadsheet->getActiveSheet()->setCellValue('C11', "Qty Planning");
    $spreadsheet->getActiveSheet()->setCellValue('D11', "Qty Production");
    $spreadsheet->getActiveSheet()->setCellValue('E11', "Dies");
    $spreadsheet->getActiveSheet()->setCellValue('F11', "Konten Stop");
    $spreadsheet->getActiveSheet()->setCellValue('G11', "Stop Time");
    $spreadsheet->getActiveSheet()->setCellValue('H11', "Konten Penanganan");
    $spreadsheet->getActiveSheet()->setCellValue('I11', "Eksekutor");


    $i = 0;
    foreach ($data["list"] as $head) {
      $i++;
      $spreadsheet->getActiveSheet()->setCellValue('A' . ($i + 11), $head["time_start"] . " - " . $head["time_end"]);
      $spreadsheet->getActiveSheet()->setCellValue('B' . ($i + 11), $head["prd_time"]);
      $spreadsheet->getActiveSheet()->setCellValue('C' . ($i + 11), $head["pln_qty"] . ' / ' . $head["tot_pln_qty"]);
      $spreadsheet->getActiveSheet()->setCellValue('D' . ($i + 11), $head["prd_qty"] . ' / ' . $head["tot_prd_qty"]);
      $spreadsheet->getActiveSheet()->setCellValue('E' . ($i + 11), $head["name1"]);
      // $seq1 = $i + 100;
      $data_stop = $class2->getStopList($line_id, $prd_dt, $shift, $head["prd_seq"]);
      $stop_exe = $class2->getStopExeReport($line_id, $prd_dt, $shift, $head["prd_seq"]);
      foreach ($data_stop as $detail) {
        $exec = array();
        $spreadsheet->getActiveSheet()->setCellValue('F' . ($i + 11), $detail["start_time"] . " - " . $detail["stop_name"]);
        if ($detail["stop_type"] == "P") {
          $spreadsheet->getActiveSheet()->setCellValue('G' . ($i + 11), "(".$detail["stop_time"].")");
        } else {
          $spreadsheet->getActiveSheet()->setCellValue('G' . ($i + 11), $detail["stop_time"]);
        }
        // $spreadsheet->getActiveSheet()->setCellValue('G' . ($i + 11), $detail["stop_time"]);
        $spreadsheet->getActiveSheet()->setCellValue('H' . ($i + 11), $detail["action_name"]);
        foreach ($stop_exe as $exe) {
          if ($exe["stop_seq"] == $detail["stop_seq"] && $exe["prd_seq"] == $detail["prd_seq"]) {
            $exec[] = $exe["name1"];
            // $spreadsheet->getActiveSheet()->setCellValue('J' . ($i + 11), $detail["stop_name"] . " - " . $exe["member_name"]);
          }
        }
        $spreadsheet->getActiveSheet()->setCellValue('I' . ($i + 11), implode(", ", $exec));
        $i++;
      }
    }

    $spreadsheet->getActiveSheet()->setCellValue("K11", "NG RIL (PCS)");
    $spreadsheet->getActiveSheet()->setCellValue("L11", "Crack");
    $spreadsheet->getActiveSheet()->setCellValue("M11", "Yujiwa");
    $spreadsheet->getActiveSheet()->setCellValue("N11", "Kajiri");
    $spreadsheet->getActiveSheet()->setCellValue("O11", "Yogore");
    $spreadsheet->getActiveSheet()->setCellValue("P11", "Dent");
    $spreadsheet->getActiveSheet()->setCellValue("Q11", "NG Dial");
    $spreadsheet->getActiveSheet()->setCellValue("R11", "Core Pin Patah")->getStyle("R11")->getAlignment()->setWrapText(true);
    $spreadsheet->getActiveSheet()->setCellValue("S11", "Tool Injury")->getStyle("S11")->getAlignment()->setWrapText(true);
    $spreadsheet->getActiveSheet()->setCellValue("T11", "Yakitsuki");
    $spreadsheet->getActiveSheet()->setCellValue("U11", "Scratch");
    $spreadsheet->getActiveSheet()->setCellValue("V11", "NG Marking");
    $spreadsheet->getActiveSheet()->setCellValue("W11", "Mikui");
    $spreadsheet->getActiveSheet()->setCellValue("X11", "Gompal");
    $spreadsheet->getActiveSheet()->setCellValue("Y11", "Die Crack");
    $spreadsheet->getActiveSheet()->setCellValue("Z11", "Mengelupas");
    $spreadsheet->getActiveSheet()->setCellValue("AA11", "Over Kikir");
    $spreadsheet->getActiveSheet()->setCellValue("AB11", "GAP Tebal (Thickness NG)")->getStyle("AB11")->getAlignment()->setWrapText(true);

    $j = 0;
    foreach ($data2["list"] as $key => $value) {
      $spreadsheet->getActiveSheet()->setCellValue('K' . ($key + $j + 12), $value["group_id"] . " " . $value["model_id"] . " " . $value["dies_no"]);
      $spreadsheet->getActiveSheet()->setCellValue('L' . ($key + $j + 12), $value["ng_ril1"]);
      $spreadsheet->getActiveSheet()->setCellValue('M' . ($key + $j + 12), $value["ng_ril2"]);
      $spreadsheet->getActiveSheet()->setCellValue('N' . ($key + $j + 12), $value["ng_ril3"]);
      $spreadsheet->getActiveSheet()->setCellValue('O' . ($key + $j + 12), $value["ng_ril4"]);
      $spreadsheet->getActiveSheet()->setCellValue('P' . ($key + $j + 12), $value["ng_ril5"]);
      $spreadsheet->getActiveSheet()->setCellValue('Q' . ($key + $j + 12), $value["ng_ril6"]);
      $spreadsheet->getActiveSheet()->setCellValue('R' . ($key + $j + 12), $value["ng_ril7"]);
      $spreadsheet->getActiveSheet()->setCellValue('S' . ($key + $j + 12), $value["ng_ril8"]);
      $spreadsheet->getActiveSheet()->setCellValue('T' . ($key + $j + 12), $value["ng_ril9"]);
      $spreadsheet->getActiveSheet()->setCellValue('U' . ($key + $j + 12), $value["ng_ril10"]);
      $spreadsheet->getActiveSheet()->setCellValue('V' . ($key + $j + 12), $value["ng_ril11"]);
      $spreadsheet->getActiveSheet()->setCellValue('W' . ($key + $j + 12), $value["ng_ril12"]);
      $spreadsheet->getActiveSheet()->setCellValue('X' . ($key + $j + 12), $value["ng_ril13"]);
      $spreadsheet->getActiveSheet()->setCellValue('Y' . ($key + $j + 12), $value["ng_ril14"]);
      $spreadsheet->getActiveSheet()->setCellValue('Z' . ($key + $j + 12), $value["ng_ril15"]);
      $spreadsheet->getActiveSheet()->setCellValue('AA' . ($key + $j + 12), $value["ng_ril16"]);
      $spreadsheet->getActiveSheet()->setCellValue('AB' . ($key + $j + 12), $value["ng_ril17"]);
      $j++;
    }

    // $dataRange = "K11:V22";
    // $data = $spreadsheet->getActiveSheet()->rangeToArray($dataRange, null, true, true, true);
    // $transposedData = array_map(null, ...$data);
    // $transposedRange = "K11:" . $spreadsheet->getActiveSheet()->getCellByColumnAndRow(count($transposedData), count($transposedData[0]))->getCoordinate();
    // $spreadsheet->getActiveSheet()->fromArray($transposedData, null, "K11");

    $spreadsheet->getActiveSheet()->mergeCells("A" . ($i + 13) . ":A" . ($i + 14) . "")->setCellValue('A' . ($i + 13), "Model");
    $spreadsheet->getActiveSheet()->mergeCells("B" . ($i + 13) . ":B" . ($i + 14) . "")->setCellValue('B' . ($i + 13), "Dies");
    $spreadsheet->getActiveSheet()->mergeCells("C" . ($i + 13) . ":C" . ($i + 14) . "")->setCellValue('C' . ($i + 13), "Waktu Kerja/Shift");
    $spreadsheet->getActiveSheet()->mergeCells("D" . ($i + 13) . ":D" . ($i + 14) . "")->setCellValue('D' . ($i + 13), "Losstime Terplanning");
    $spreadsheet->getActiveSheet()->mergeCells("E" . ($i + 13) . ":E" . ($i + 14) . "")->setCellValue('E' . ($i + 13), "Nett Operasi");
    $spreadsheet->getActiveSheet()->mergeCells("F" . ($i + 13) . ":F" . ($i + 14) . "")->setCellValue('F' . ($i + 13), "Losstime");
    $spreadsheet->getActiveSheet()->mergeCells("G" . ($i + 13) . ":G" . ($i + 14) . "")->setCellValue('G' . ($i + 13), "Qty Produksi Mesin");
    $spreadsheet->getActiveSheet()->mergeCells("H" . ($i + 13) . ":H" . ($i + 14) . "")->setCellValue('H' . ($i + 13), "Qty OK Lastman");
    $spreadsheet->getActiveSheet()->mergeCells("I" . ($i + 13) . ":I" . ($i + 14) . "")->setCellValue('I' . ($i + 13), "RIL");
    $spreadsheet->getActiveSheet()->mergeCells("J" . ($i + 13) . ":T" . ($i + 13) . "")->setCellValue('J' . ($i + 13), "ROL");
    $spreadsheet->getActiveSheet()->setCellValue('J' . ($i + 14), "CMM");
    $spreadsheet->getActiveSheet()->setCellValue('K' . ($i + 14), "Trial Machining");
    $spreadsheet->getActiveSheet()->setCellValue('L' . ($i + 14), "Steuchi Setup");
    $spreadsheet->getActiveSheet()->setCellValue('M' . ($i + 14), "Steuchi Trouble");
    $spreadsheet->getActiveSheet()->setCellValue('N' . ($i + 14), "Steuchi Dandori");
    $spreadsheet->getActiveSheet()->setCellValue('O' . ($i + 14), "Lot Out");
    $spreadsheet->getActiveSheet()->setCellValue('P' . ($i + 14), "Produk Jatuh");
    $spreadsheet->getActiveSheet()->setCellValue('Q' . ($i + 14), "Produk Numpuk");
    $spreadsheet->getActiveSheet()->setCellValue('R' . ($i + 14), "Sample");
    $spreadsheet->getActiveSheet()->setCellValue('S' . ($i + 14), "Kekotanso");
    $spreadsheet->getActiveSheet()->setCellValue('T' . ($i + 14), "DLL");
    $spreadsheet->getActiveSheet()->mergeCells('U' . ($i + 13) . ':U' . ($i + 14) . '')->setCellValue('U' . ($i + 13), "WIP");
    $spreadsheet->getActiveSheet()->mergeCells('V' . ($i + 13) . ':V' . ($i + 14) . '')->setCellValue('V' . ($i + 13), "Efficiency %");
    $spreadsheet->getActiveSheet()->mergeCells('W' . ($i + 13) . ':W' . ($i + 14) . '')->setCellValue('W' . ($i + 13), "Losstime %");
    $spreadsheet->getActiveSheet()->mergeCells('X' . ($i + 13) . ':X' . ($i + 14) . '')->setCellValue('X' . ($i + 13), "RIL %");
    $spreadsheet->getActiveSheet()->mergeCells('Y' . ($i + 13) . ':Y' . ($i + 14) . '')->setCellValue('Y' . ($i + 13), "ROL %");
    $spreadsheet->getActiveSheet()->mergeCells('Z' . ($i + 13) . ':Z' . ($i + 14) . '')->setCellValue('Z' . ($i + 13), "WIP %");
    $spreadsheet->getActiveSheet()->mergeCells('AA' . ($i + 13) . ':AA' . ($i + 14) . '')->setCellValue('AA' . ($i + 13), "Total %");

    foreach ($data2["list"] as $key => $value) {
      $spreadsheet->getActiveSheet()->setCellValue('A' . ($i + 15 + $key), $value["group_id"] . " " . $value["model_id"]);
      $spreadsheet->getActiveSheet()->setCellValue('B' . ($i + 15 + $key), $value["dies_no"]);
      $spreadsheet->getActiveSheet()->setCellValue('C' . ($i + 15 + $key), $value["waktu_shift"]);
      $spreadsheet->getActiveSheet()->setCellValue('D' . ($i + 15 + $key), $value["loss_time_p"]);
      $spreadsheet->getActiveSheet()->setCellValue('E' . ($i + 15 + $key), $value["prd_time"]);
      $spreadsheet->getActiveSheet()->setCellValue('F' . ($i + 15 + $key), $value["loss_time"]);
      $spreadsheet->getActiveSheet()->setCellValue('G' . ($i + 15 + $key), $value["tot_qty"]);
      $spreadsheet->getActiveSheet()->setCellValue('H' . ($i + 15 + $key), $value["prd_qty"]);
      $spreadsheet->getActiveSheet()->setCellValue('I' . ($i + 15 + $key), $value["ng_ril"]);
      $spreadsheet->getActiveSheet()->setCellValue('J' . ($i + 15 + $key), $value["ng_rol1"]);
      $spreadsheet->getActiveSheet()->setCellValue('K' . ($i + 15 + $key), $value["ng_rol2"]);
      $spreadsheet->getActiveSheet()->setCellValue('L' . ($i + 15 + $key), $value["ng_rol6"]);
      $spreadsheet->getActiveSheet()->setCellValue('M' . ($i + 15 + $key), $value["ng_rol4"]);
      $spreadsheet->getActiveSheet()->setCellValue('N' . ($i + 15 + $key), $value["ng_rol5"]);
      $spreadsheet->getActiveSheet()->setCellValue('O' . ($i + 15 + $key), $value["ng_rol3"]);
      $spreadsheet->getActiveSheet()->setCellValue('P' . ($i + 15 + $key), $value["ng_rol7"]);
      $spreadsheet->getActiveSheet()->setCellValue('Q' . ($i + 15 + $key), $value["ng_rol8"]);
      $spreadsheet->getActiveSheet()->setCellValue('R' . ($i + 15 + $key), $value["ng_rol9"]);
      $spreadsheet->getActiveSheet()->setCellValue('S' . ($i + 15 + $key), $value["ng_rol10"]);
      $spreadsheet->getActiveSheet()->setCellValue('U' . ($i + 15 + $key), $value["wip"]);
      $spreadsheet->getActiveSheet()->setCellValue('V' . ($i + 15 + $key), $value["eff"]);
      $spreadsheet->getActiveSheet()->setCellValue('W' . ($i + 15 + $key), $value["loss%"]);
      $spreadsheet->getActiveSheet()->setCellValue('X' . ($i + 15 + $key), $value["ril%"]);
      $spreadsheet->getActiveSheet()->setCellValue('Y' . ($i + 15 + $key), $value["rol%"]);
      $spreadsheet->getActiveSheet()->setCellValue('Z' . ($i + 15 + $key), $value["wip%"]);
      $spreadsheet->getActiveSheet()->setCellValue('AA' . ($i + 15 + $key), $value["total%"]);
      $spreadsheet->getActiveSheet()->getStyle('A' . ($i + 15 + $key) . ':AA' . ($i + 15 + $key))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    }

    $spreadsheet->getActiveSheet()->getStyle('A1')->getFont()->setBold(true)->setSize(24);
    $spreadsheet->getActiveSheet()->getStyle('A11:I11')->getFont()->setBold(true);
    $spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal('center');
    $spreadsheet->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal('center');
    $spreadsheet->getActiveSheet()->getStyle('AB')->getAlignment()->setHorizontal('center');
    $spreadsheet->getActiveSheet()->getStyle('I4:J8')->getAlignment()->setHorizontal('center');
    $spreadsheet->getActiveSheet()->getStyle('I' . ($i + 13))->getAlignment()->setHorizontal('center');
    $spreadsheet->getActiveSheet()->getStyle('I12:I' . ($i + 11))->getAlignment()->setWrapText(true);
    $spreadsheet->getActiveSheet()->getStyle('A11:AA' . ($i + 16))->getAlignment()->setVertical('center')->setHorizontal('center');
    $spreadsheet->getActiveSheet()->getStyle('A4:D6')->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $spreadsheet->getActiveSheet()->getStyle('F4:G9')->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $spreadsheet->getActiveSheet()->getStyle('A12:I' . ($i + 11))->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $spreadsheet->getActiveSheet()->getStyle('A12:I' . ($i + 11))->getBorders()->getVertical()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $spreadsheet->getActiveSheet()->getStyle('K12:AB' . ($i + 11))->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $spreadsheet->getActiveSheet()->getStyle('K12:AB' . ($i + 11))->getBorders()->getVertical()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $spreadsheet->getActiveSheet()->getStyle('A' . ($i + 13) . ':AA' . ($i + 14))->getFont()->setBold(true);
    $spreadsheet->getActiveSheet()->getStyle('A' . ($i + 13) . ':AA' . ($i + 14))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $spreadsheet->getActiveSheet()->getStyle('A11:I11')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $spreadsheet->getActiveSheet()->getStyle('K11:AB11')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $spreadsheet->getActiveSheet()->getStyle('I4:J8')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getRowDimension(11)->setRowHeight(35);
    $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
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
    $spreadsheet->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
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

    // $data_header = $class2->getHeaderById($line_id, $prd_dt, $shift);
    // $data["list"] = $class->getList2($line_id, $prd_dt, $shift);
    // $data2["list"] = $class->getList3($line_id, $prd_dt, $shift);
    // $data3["list"] = $class->getPrdTime($line_id, $prd_dt, $shift);

    // $time_start = $data["list"][0]["time_start"];
    // $time_end = $data["list"][7]["time_end"];

    // // $start = DateTime::createFromFormat('H:i', $time_start);
    // // $end = DateTime::createFromFormat('H:i', $time_end);

    // // if ($end < $start) {
    // //   $end->modify('+1 day');
    // // }

    // // $diff_in_minutes = ($end->getTimestamp() - $start->getTimestamp()) / 60;
    // $nett_opr = $data3["list"][0]["nett_opr"];
    // $tot_prd = $data["list"][7]["tot_prd_qty"];
    // $tot_ng = $data["list"][7]["tot_ng2"];
    // $qty_lastman = $tot_prd + $tot_ng;
    // $loss_time = $data2["list"][0]["loss_time"];
    // $loss_timep = $data2["list"][0]["loss_time_p"];
    // $cctime = $data2["list"][0]["cctime"];

    // $prd_time = array();
    // foreach ($data["list"] as $list) {
    //   $prd_time[] = $list["prd_time"];
    // }
    // $prdtime = array_sum($prd_time);
    // $efficiency = (($tot_prd - $tot_ng) * $cctime / 60) / $prdtime;
    // $roundEff2 = round($efficiency, 3);
    // $totalEff2 = $roundEff2 * 100;
    // $losstime_persen = $loss_time / $nett_opr * 100;
    // $roundloss = round($losstime_persen, 2);


    // $ril = $data2["list"][0]["ril"];
    // $persen_ril = $ril * $cctime / 60 / $nett_opr * 100;
    // $roundril = round($persen_ril, 2);

    // $rol = $data2["list"][0]["rol"];
    // $persen_rol = $rol * $cctime / 60 / $nett_opr * 100;
    // $roundrol = round($persen_rol, 2);

    // $wip = $data2["list"][0]["wip"];
    // $persen_wip = $wip * $cctime / 60 / $nett_opr * 100;
    // $roundwip = round($persen_wip, 2);

    // $total = $totalEff2 + $roundloss + $roundril + $roundrol + $roundwip;

    // require(TEMPLATE_PATH . "/t_print_report.php");
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
        $efficiency = (($tot_prd - $tot_ng) * $cctime / 60) / $prdtime * 100;
        $roundEff2 = round($efficiency, 2);
        // $totalEff2 = $roundEff2 * 100;

        $wip = $data2["list"][0]["wip"];
        $persen_wip = $wip * $cctime / 60 / $nett_opr * 100;
        $roundwip = round($persen_wip, 2);

        $total = $totalEff2 + $roundloss + $roundril + $roundrol + $roundwip;
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

    $data["list"] = $class->getList($date_from, $date_to, $prd_year, $prd_month, $shift, $line_id, $ldid, $jpid);
    $line = $dies->getLineByType();
    $shiftlist = $class->getListShift();
    require(TEMPLATE_PATH . "/t_report_daily_production.php");
  }
}

if ($action == "report_detail") {
  $template["group"] = "Report";
  $template["menu"] = "Daily Production";
  $template["submenu"] = "View Details";
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


  $shift = $_GET["shift"];
  if (empty($shift)) {
    $shift = "*";
  }
  $line_id = $_GET["line_id"];
  if (empty($line_id)) {
    $line_id = "*";
  }
  $group_id = $_GET["group_id"];
  if (empty($group_id)) {
    $group_id = "*";
  }
  $model_id = $_GET["model_id"];
  if (empty($model_id)) {
    $model_id = "*";
  }
  $dies_no = $_GET["dies_id"];
  if (empty($dies_no)) {
    $dies_no = "*";
  }

  $group_list = $dies->getDiesGroup();
  $model_list = $dies->getDiesModel(null, $group_list[0]["pval1"]);
  $diesid_list = $dies->getListDies(null, "A", $group_list[0]["pval1"], $model_list[0]["model_id"]);

  $line = $dies->getLineByType();
  $shiftlist = $report->getListShift();
  $report_detail = $report->getReportDetail($date_from, $date_to, $shift, $line_id, $group_id, $model_id, $dies_no);
  // print_r($report_detail);
  // die();
  require(TEMPLATE_PATH . "/t_report_detail.php");
}

if ($action == "report_stop") {
  $template["group"] = "Report";
  $template["menu"] = "Daily Production";
  $template["submenu"] = "View Details Stop";
  $report = new Reporting();
  $dies = new Dies();
  $class = new Production();

  $date_from = date('Ymd');
  if (!empty($_GET["date_from"])) {
    $date_from = $_GET["date_from"];
  }

  $date_to = date('Ymd');
  if (!empty($_GET["date_to"])) {
    $date_to = $_GET["date_to"];
  }

  $shift = $_GET["shift"];
  if (empty($shift)) {
    $shift = "*";
  }
  $line_id = $_GET["line_id"];
  if (empty($line_id)) {
    $line_id = "*";
  }
  $group_id = $_GET["group_id"];
  if (empty($group_id)) {
    $group_id = "*";
  }
  $model_id = $_GET["model_id"];
  if (empty($model_id)) {
    $model_id = "*";
  }
  $dies_no = $_GET["dies_id"];
  if (empty($dies_no)) {
    $dies_no = "*";
  }

  $group_list = $dies->getDiesGroup();
  $model_list = $dies->getDiesModel(null, $group_list[0]["pval1"]);
  $diesid_list = $dies->getListDies(null, "A", $group_list[0]["pval1"], $model_list[0]["model_id"]);

  $line = $dies->getLineByType();
  $shiftlist = $report->getListShift();
  $data["list"] = $report->getReportStop($date_from, $date_to, $shift, $line_id, $group_id, $model_id, $dies_no);
  require(TEMPLATE_PATH . "/t_report_stop.php");
}