<?php
if ($action == "bom") {
    $template["group"] = "Master Data";
    $template["menu"] = "BOM";
    $material = new Material();
    $bom = new Bom();

    if (isset($_GET['upload'])) {
        require_once 'classes/PHPExcel.php';
        require_once 'classes/PHPExcel/IOFactory.php';
        $fileName = $_FILES["excel"]["tmp_name"];
        try {
            $objPHPExcel = PHPExcel_IOFactory::load($fileName);
        } catch (Exception $e) {
            header("Location: ?action=" . $action . "&error=" . $e->getMessage());
            exit();
        }
        $activeSheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
        $success = 0;
        $fail = 0;
        $processed = 0;
        $i = 0;
        // print("<pre>" . print_r($activeSheetData, true) . "</pre>");;
        // die();
        if (!empty($activeSheetData)) {
            $header = array();
            $detail = array();
            foreach ($activeSheetData as $row) {
                if ($i > 0) {
                    //check if exist
                    if (!empty($row["A"]) && !empty($row["B"]) && !empty($row["C"])) {
                        $header["matnr"] = $row["A"];
                        $header["menge_h"] = $row["C"];
                        $header["meins"] = "PCS";
                        $detail["matnr"] = $header["matnr"];
                        $detail["matn1"] = $row["D"];
                        $detail["menge"] = $row["F"];
                        $detail["meins"] = "PCS";
                        $bom->insertHeader($header);
                        $save = $bom->insertDetail($detail);
                    } else {
                        $detail["matnr"] = $header["matnr"];
                        $detail["matn1"] = $row["D"];
                        $detail["menge"] = $row["F"];
                        $detail["meins"] = "PCS";
                        $save = $bom->insertDetail($detail);
                    }

                    if ($save["status"] == true) {
                        $success += 1;
                    } else {
                        $fail += 1;
                    }
                    $processed++;
                }
                $i++;
            }
        }
        $message = "Upload Complete [$success] data success, [$fail] data failed, [$processed] data processed";
        header("Location: ?action=" . $action . "&success=$message");
    }

    if (isset($_GET["id"])) {
        $id = $_GET["id"];
        if (isset($_POST["save"])) {
            if ($id == "0") {
                $param = $_POST;
                $matn1 = $_POST["matn1"];
                $menge = $_POST["menge"];
                $status = array();
                $i = 0;
                foreach ($matn1 as $row) {
                    $meins = $bom->getDetailPerMats($matn1[$i]);
                    $data["matnr"] = $param["matnr"];
                    $data["matn1"] = $row;
                    $data["menge"] = $menge[$i];
                    $data["meins"] = $meins["uom"];
                    $status = $bom->insertDetail($data);
                    if ($status["status"] == true) {
                        header("Location: ?action=" . $action . "&success=Data%20Saved");
                    } else {
                        header("Location: ?action=" . $action . "&error=" . $status["message"]);
                    }
                    $i++;
                }
                $save = array();
                $save = $bom->insertHeader($param);
                if ($save == true) {
                    header("Location: ?action=" . $action . "&success=Data%20Saved");
                } else {
                    header("Location: ?action=" . $action . "&error=" . $save["message"]);
                }
            } else {
                $param = $_POST;
                $data["matn1"] = $param["matn1"];
                $data["menge"] = $param["menge"];
                $data["meins"] = $param["meins"];
                $item_data = [];
                $i = 0;
                foreach ($data["matn1"] as $row) {
                    $item_data[$i]["matn1"] = $row;
                    $item_data[$i]["menge"] = $data["menge"][$i];
                    $item_data[$i]["meins"] = $data["meins"];
                    $i++;
                }
                $status = array();
                $save = array();
                $save = $bom->updateItem($id, $item_data);
                if ($save == true) {
                    header("Location: ?action=" . $action . "&success=Data%20Saved");
                } else {
                    header("Location: ?action=" . $action . "&error=" . $save["message"]);
                }
            }
        }
        $bomheader = $bom->getHeader($id);
        $bomdetail = $bom->getDetail($id);
        $matlist = $material->getListMaterial();
        $matlistall = $material->getList();
        require(TEMPLATE_PATH . "/t_bom_edit.php");
    } else {
        $bomlist = $bom->getListHeader();
        require(TEMPLATE_PATH . "/t_bom_list.php");
    }
}
?>