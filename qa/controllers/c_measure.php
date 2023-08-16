<?php
if ($action == "measure") {
    $template["group"] = "Transaction";
    $template["menu"] = "Inspection";
    $tmp = new TempModel();
    $param = new Param();
    $operator = new Member();
    $measure = new Measure();
    $report = new Report();
    $user = new User();

    if (isset($_GET["id"])) {
        $id = $_GET["id"];

        if ($id == "0") {
            if (isset($_POST["save"])) {
                $id = $_POST["partid"];
                $date = $_POST["date"];
                $shift = $_POST["shift"];
                $operator = $_POST["inspector"];

                $grup_list = $tmp->getGrupById($id);
                $item_list = $tmp->getListItemById($id);
                $head = $tmp->getHeaderById($id);
                $cekHeader = $measure->getListGrupById($id);

                $doc_no = $measure->generateDocNo();

                $grup_item = array();
                $i = 0;
                foreach ($grup_list as $grup) {
                    $grup_item["grupid"][$i] = $grup["grupid"];
                    $i++;
                }

                if (!empty($grup_list)) {
                    // if (!empty($item_list)) {
                    $save = $measure->insertHeader($id, $date, $shift, $operator, $doc_no, $head[0]["sign_pos"], $head[0]["rasio_pos"]);
                    // } else {
                    //     header("Location: ?action=" . $action . "&error=Field in Model ".$id." doesnt exist!");
                    //     die();
                    // }
                    // $isExist = "true";
                    // foreach ($item_list as $row) {
                    //      if (empty($row["cellid"])) {
                    //          $isExist = "false";
                    //          break;
                    //      }
                    // }

                    // if ($isExist == "true") {
                    //     $save = $measure->insertHeader($id, $date, $shift, $operator, $doc_no);
                    // } else {
                    //     header("Location: ?action=" . $action . "&error=Field in Model ".$id." doesnt exist!");
                    //     die();
                    // }
                } else {
                    header("Location: ?action=" . $action . "&error=Group in Model " . $id . " doesnt exist!");
                    die();
                }
                if ($save["status"] == true) {
                    $save_grup = $measure->insertGrup($id, $date, $shift, $operator, $grup_item, $doc_no);
                    if ($save_grup["status"] == false) {
                        header("Location: ?action=" . $action . "&error=grup: " . $save_grup["message"]);
                        die();
                    } else {
                        $save_item = $measure->insertItem($id, $date, $shift, $operator, $item_list, $doc_no);
                        if ($save_item["status"] == false) {
                            header("Location: ?action=" . $action . "&error=item: " . $save_item["message"]);
                            die();
                        }
                    }
                    header("Location: ?action=" . $action . "&id=" . $save["doc_no"] . "&date=" . $date . "&shift=" . $shift . "&success=Data saved successfully");
                } else {
                    header("Location: ?action=" . $action . "&error=Header: " . $save["message"]);
                }
            } else {
                $template["submenu"] = "New";
                $prd_date = Date("Ymd");
                $shift_ori = $measure->getShiftOri();
                $shift = $shift_ori[0]["seq"];
                if (isset($_GET["shift"])) {
                    $shift = $_GET["shift"];
                }
                $list_tmpl = $tmp->getList();
                $operator_list = $operator->getList();
                $shift_list = $param->getListShift();
                require(TEMPLATE_PATH . "/t_measure.php");
            }
        } else {
            $date = $_GET["date"];
            $shift = $_GET["shift"];
            $data_header = $measure->getHeaderById($id);
            $doc_no = $measure->generateDocNo();

            if (isset($_GET["grup"])) {
                $grup = $_GET["grup"];

                if (isset($_POST["save"])) {
                    $param = $_POST;
                    $header = $tmp->getHeaderById($id);
                    $item_list = $tmp->getItemById($grup, $param["partid"]);
                    // print_r($param);
                    // die();
                    $save_value = $measure->updateValue($param["partid"], $shift, $date, $grup, $param["empid"], $param["value"], $item_list, $id);
                    if ($save_value["status"] == true) {
                        $map_list = $measure->getListMap($id);
                        $listitem = $measure->getListMapByGrup($id, $grup);
                        $isComplete = "true";
                        $changeBg = "true";
                        foreach ($map_list as $row) {
                            if (empty($row["value"])) {
                                $isComplete = "false";
                                $measure->updateStatus($id, 'N');
                                break;
                            }
                        }

                        if ($isComplete == "true") {
                            $measure->updateStatus($id, 'C');
                        }

                        foreach ($listitem as $row) {
                            if (empty($row["value"])) {
                                $changeBg = "false";
                                $measure->updateBgcolor($id, $grup, 'bg-primary');
                                break;
                            }
                        }

                        if ($changeBg == "true") {
                            $measure->updateBgcolor($id, $grup, 'bg-success');
                        }
                        sleep(1);
                        header("Location: ?action=" . $action . "&id=" . $id . "&date=" . $date . "&shift=" . $shift . "&success=Data saved successfully");
                    } else {
                        header("Location: ?action=" . $action . "&id=" . $id . "&date=" . $date . "&shift=" . $shift . "&error=Value: " . $save_value["message"]);
                    }
                } else {
                    $data_grup = $measure->getGrupById($id, $grup);
                    $data_map = $measure->getMapByGrup($id, $grup);
                    require(TEMPLATE_PATH . "/t_measure2.php");
                }
            } else {
                if (isset($_GET["approve"])) {
                    $id = $_GET["id"];
                    $date = $_GET["date"];
                    $shift = $_GET["shift"];
                    // $excel = $_POST["excel"];
                    $header = $measure->getHeaderById($id);
                    $cell = $measure->getListMap($id);
                    $data = array();

                    $decoded = base64_decode($header["tmpfl"]);

                    file_put_contents("media/" . $id . "_" . $shift . ".xlsx", $decoded);
                    $fileName = "media/" . $id . "_" . $shift . ".xlsx";

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

                        // $sheet->getActiveSheet()->setCellValue('AL4', '=COUNTIF(AH2:AH85;"O")');

                        // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                        // header('Content-Disposition: attachment;filename="' . $id . '_' . $shift . '.xlsx"');
                        // header('Cache-Control: max-age=0');

                        // $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($sheet);
                        // ob_end_clean();
                        // $writer->save('php://output');

                        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($sheet);
                        $writer->save($fileName);
                        $calc = \PhpOffice\PhpSpreadsheet\Calculation\Calculation::getInstance($sheet);
                        $calc->disableCalculationCache();
                        // $reader = \PhpOffice\PhpSpreadsheet\IOFactory::load($fileName);
                        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
                        // $reader->setReadDataOnly(true);
                        $load = $reader->setReadDataOnly(true)->load($fileName);
                        $load->getActiveSheet()->setCellValue('AL4', '=COUNTIF(AH2:AH85,"O")');
                        $data["al5"] = $load->getActiveSheet()->getCell('AL5')->getFormattedValue();
                        $data["al4"] = $load->getActiveSheet()->getCell('AL4')->getFormattedValue();
                        $data["al4form"] = $load->getActiveSheet()->getCell('AL4')->getValue();
                        $data["al3"] = $load->getActiveSheet()->getCell('AL3')->getFormattedValue();
                        $data["al3form"] = $load->getActiveSheet()->getCell('AL3')->getValue();
                        $data["akurasi"] = $load->getActiveSheet()->getCell($header["rasio_pos"])->getFormattedValue();
                        unlink($fileName);
                        // die();
                    } catch (\Throwable $th) {
                        //throw $th;
                        header("Location: ?action=" . $action . "&error=" . $th->getMessage());
                    }
                    // echo "Formula AL4: " . $data["al4form"] . "<br>";
                    // echo "Value: " . $data["al4"] . "<br>";
                    // echo "Formula AL3: " . $data["al3form"] . "<br>";
                    // echo "Value: " . $data["al3"] . "<br>";
                    // echo "Akurasi: " . $data["akurasi"] * 100 . "<br>";
                    // die();
                    $ttd = $user->getById($_SESSION[LOGIN_SESSION]);
                    $accur = $data["akurasi"] * 100;
                    $approve = $measure->approve($id, $ttd["ttd"], $accur);
                    if ($approve["status"] == true) {
                        header("Location: ?action=" . $action . "&success=Data approved successfully");
                    } else {
                        header("Location: ?action=" . $action . "&error=Approve: " . $approve["message"]);
                    }
                }

                $data_grup = $measure->getListGrupById($id);
                require(TEMPLATE_PATH . "/t_measure1.php");
            }
        }
    } else {
        // if (!empty($_GET["date_from"])) {
        //     $date_from = $_GET["date_from"];
        // } else {
        //     $date_from = "*";
        // }

        // if (!empty($_GET["date_to"])) {
        //     $date_to = $_GET["date_to"];
        // } else {
        //     $date_to = "*";
        // }
        $op_role = "USER";
        $cek_user = $user->getUserRole($_SESSION[LOGIN_SESSION]);
        if (!empty($cek_user)) {
            foreach ($cek_user as $usr) {
                if ($usr == "ADMIN") {
                    $op_role = "ADMIN";
                    break;
                }
                if ($usr == "LEADER") {
                    $op_role = "LEADER";
                    break;
                }
            }
        }

        $list_header = $report->getListHeader('*', '*', '*');
        $data_part = $tmp->getList();
        $data_shift = $param->getListShift();
        require(TEMPLATE_PATH . "/t_measure_list.php");
    }
}
?>