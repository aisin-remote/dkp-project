<?php
if ($action == "r_inspection") {
    $template["group"] = "Report";
    $template["menu"] = "Inspection";
    $tmpl = new TempModel();
    $report = new Report();
    $measure = new Measure();
    $param = new Param();

    $date_from = date('Ymd');
    if (!empty($_GET["date_from"])) {
        $date_from = $_GET["date_from"];
    }

    $date_to = date('Ymd');
    if (!empty($_GET["date_to"])) {
        $date_to = $_GET["date_to"];
    }

    $shift = "*";
    if (!empty($_GET["shift"])) {
        $shift = $_GET["shift"];
    }

    $partid = "*";
    if (!empty($_GET["partname"])) {
        $partid = $_GET["partname"];
    }

    $status = "*";
    if (!empty($_GET["status"])) {
        $status = $_GET["status"];
    }

    if (isset($_GET["export"])) {
        $id = $_GET["id"];
        $date = $_GET["date"];
        $shift = $_GET["shift"];
        // $excel = $_POST["excel"];
        $header = $measure->getHeaderById($id);
        $cell = $measure->getListMap($id);

        $decoded = base64_decode($header["tmpfl"]);
        $ttd = base64_decode($header["ttd"]);
        $bin = imagecreatefromstring($ttd);

        // print_r($header);
        // die();

        if ($bin === false) {
            echo "Error loading the image.";
            exit;
        }

        header('Content-Type: image/jpeg');
        imagejpeg($bin, "media/" . $id . "_" . $shift . ".jpeg");

        imagedestroy($bin);

        file_put_contents("media/" . $id . "_" . $shift . ".xlsx", $decoded);
        // imagejpeg($bin, "media/" . $id . "_" . $shift . ".jpg");
        $fileName = "media/" . $id . "_" . $shift . ".xlsx";
        $ttdName = "media/" . $id . "_" . $shift . ".jpeg";
        // die();
        require_once 'vendor/autoload.php';
        $spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
        try {
            //code...
            $sheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($fileName);

            $sheet->getActiveSheet()->setCellValue('S1', 'Doc No    : ' . $id);
            $sheet->getActiveSheet()->setCellValue('Q4', 'Inspection Date : ' . date_format(new DateTime($date), 'd-m-Y'));
            $sheet->getActiveSheet()->setCellValue('Q5', 'Inspector             : ' . $header["inspector"]);

            foreach ($cell as $row) {
                $sheet->getActiveSheet()->setCellValue($row["cell_map"], $row["value"]);
            }

            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawing->setName('Logo');
            $drawing->setDescription('Logo');
            $drawing->setPath($ttdName);
            $drawing->setCoordinates($header["sign_pos"]);
            $drawing->setHeight(55);
            $drawing->setWorksheet($sheet->getActiveSheet());

            $sheet->getActiveSheet()->getStyle($header["sign_pos"])->getAlignment()->setHorizontal('center');
            // $sheet->getActiveSheet()->getPageSetup()->setFitToPage(true);
            // $sheet->getActiveSheet()->getPageSetup()->setFitToWidth(1);
            // $sheet->getActiveSheet()->getPageSetup()->setFitToHeight(0);
            // $sheet->getActiveSheet()->getPageSetup()->setPrintArea("A1:Y65");

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $id . '_' . $shift . '.xlsx"');
            header('Cache-Control: max-age=0');

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($sheet);
            // header('Content-Type: application/pdf');
            // header('Content-Disposition: attachment;filename="' . $id . '_' . $shift . '.pdf"');
            // header('Cache-Control: max-age=0');

            // // $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($sheet, "Tcpdf");
            // $writer = new \PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf($sheet);
            // $writer->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A3);
            // $writer->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
            // $writer->setSheetIndex(0);
            ob_end_clean();
            $writer->save('php://output');
            unlink($fileName);
            unlink($ttdName);
            die();
        } catch (\Throwable $th) {
            //throw $th;
            header("Location: ?action=" . $action . "&error=" . $th->getMessage());
        }
    } else {
        $data_part = $tmpl->getList();
        $data_shift = $param->getListShift();
        $data_header = $report->getListHeader($date_from, $date_to, $shift, $partid, 'Y');
        $data_trans = $report->getListTrans($date_from, $date_to, $shift, $partid, 'Y');

        if (isset($_GET["download"])) {
            // print_r($data_trans);
            // die();
            foreach ($data_trans as $key => $value) {
                echo $value["doc_no"];
                die();
            }
        }
        require(TEMPLATE_PATH . "/t_report.php");
    }
}
?>