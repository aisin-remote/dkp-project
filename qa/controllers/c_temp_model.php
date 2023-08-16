<?php
if ($action == "temp_model") {
    $template["group"] = "Master Data";
    $template["menu"] = "Template Model";
    $templ = new TempModel();

    if (isset($_GET["id"])) {
        $id = $_GET["id"];

        if (isset($_GET["download"])) {
            $header = $templ->getHeaderById($id);

            $decoded = base64_decode($header[0]["tmpfl"]);
            file_put_contents("media/" . $id . ".xlsx", $decoded);
            $fileName = "media/" . $id . ".xlsx";

            require_once 'vendor/autoload.php';
            $spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
            try {
                //code...
                $sheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($fileName);

                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="' . $id . '.xlsx"');
                header('Cache-Control: max-age=0');
                
                $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($sheet);
                ob_end_clean();
                $writer->save('php://output');
                unlink($fileName);
                exit;
            } catch (\Throwable $th) {
                //throw $th;
                header("Location: ?action=" . $action . "&error=" . $th->getMessage());
            }
        }

        if ($id == "0") {
            if (isset($_POST["save"])) {
                $param = $_POST;
                $excel = $_FILES["excel"]["tmp_name"];

                $param["excel"] = base64_encode(file_get_contents($excel));

                $save = $templ->insertHeader($param);

                if ($save["status"] == true) {
                    header("Location: ?action=" . $action . "&id=" . $save["id"] . "&success=Data saved successfully");
                } else {
                    // header("Location: ?action=" . $action . "&id=0&error=Header: " . $save["message"]);
                    header("Location: ?action=" . $action . "&id=0&error=Header: Part Number already exist!");
                }
            }

            require(TEMPLATE_PATH . "/t_tmpl_model_edit.php");
        } else {
            $header = $templ->getHeaderById($id);
            $grup = $templ->getGrupById($id);
            $field = $templ->getListItemById($id);

            if (isset($_POST["btn_save"])) {
                $param = $_POST;
                $excel = $_FILES["excel"]["tmp_name"];

                if (!empty($excel)) {
                    $param["excel"] = base64_encode(file_get_contents($excel));
                } else {
                    $param["excel"] = $param["excel1"];
                }

                $save = $templ->updateHeader($param, $id);

                // if (!empty($param["sign"])) {
                //     $param["field"] = $param["sign"];
                //     $param["desc1"] = "Signature";
                //     $param["cellid"] = "0a";
                //     $param["partno"] = $id;
                //     $param["grupid"] = $id;
                //     $save = $templ->updateFieldMap($param);
                //     if ($save["status"] == true) {
                //         header("Location: ?action=" . $action . "&id=" . $id . "&success=Data saved successfully");
                //     } else {
                //         header("Location: ?action=" . $action . "&id=" . $id . "&error=Header: " . $save["message"]);
                //     }
                // }

                // if (!empty($param["rasio"])) {
                //     $param["field"] = $param["rasio"];
                //     $param["desc1"] = "Rasio";
                //     $param["cellid"] = "0b";
                //     $param["partno"] = $id;
                //     $param["grupid"] = $id;
                //     $save = $templ->updateFieldMap($param);
                //     if ($save["status"] == true) {
                //         header("Location: ?action=" . $action . "&id=" . $id . "&success=Data saved successfully");
                //     } else {
                //         header("Location: ?action=" . $action . "&id=" . $id . "&error=Header: " . $save["message"]);
                //     }
                // }

                if ($save["status"] == true) {
                    header("Location: ?action=" . $action . "&id=" . $id . "&success=Data saved successfully");
                } else {
                    header("Location: ?action=" . $action . "&id=" . $id . "&error=Header: " . $save["message"]);
                }
            }

            require(TEMPLATE_PATH . "/t_tmpl_model_edit2.php");
        }

        // if (isset($_GET["grup"])) {
        //     $grupid = $_GET["grup"];

        //     // $grup = $templ->getGrup($grupid, $id);
        //     // $item = $templ->getItemById($grupid, $id);
        //     require(TEMPLATE_PATH . "/t_tmpl_model_edit2.php");
        // } else {
        //     if (isset($_POST["save"])) {
        //         $param = $_POST;
        //         $excel = $_FILES["excel"]["tmp_name"];
        //         // $img = $_FILES["image"]["tmp_name"];

        //         if (!empty($excel)) {
        //             $param["excel"] = base64_encode(file_get_contents($excel));
        //         } else {
        //             $param["excel"] = $param["excel1"];
        //         }

        //         // foreach ($img as $key => $value) {
        //         //     if (!empty($value)) {
        //         //         $param["image"][$key] = base64_encode(file_get_contents($value));
        //         //     } else {
        //         //         $param["image"][$key] = $param["image1"][$key];
        //         //     }
        //         // }

        //         // echo json_encode($param["grup"]);
        //         // print_r($param);
        //         // die();
        //         if ($id == "0") {
        //             $save = $templ->insertHeader($param);

        //             if ($save["status"] == true) {
        //                 header("Location: ?action=" . $action . "&id=" . $save["id"] . "&success=Data saved successfully");
        //             } else {
        //                 header("Location: ?action=" . $action . "&error=Header: " . $save["message"]);
        //             }
        //         } else {
        //             $save = $templ->updateHeader($param, $id);

        //             if ($save["status"] == true) {
        //                 header("Location: ?action=" . $action . "&id=" . $id . "&success=Data saved successfully");
        //             } else {
        //                 header("Location: ?action=" . $action . "&id=" . $id . "&error=Header: " . $save["message"]);
        //             }
        //         }
        //     }

        //     // $header = $templ->getHeaderById($id);
        //     // $grup = $templ->getGrupById($id);
        //     // $cntGrup = $templ->countGrup($id);
        //     // foreach ($grup as $row) {
        //     //     $item = $templ->getItemById($row["grupid"], $id);
        //     // }
        //     require(TEMPLATE_PATH . "/t_tmpl_model_edit.php");
        // }
    } else {

        $list = $templ->getList();
        require(TEMPLATE_PATH . "/t_temp_model.php");
    }
}
?>