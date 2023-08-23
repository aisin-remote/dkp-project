<?php
if ($action == "ng") {
    $template["group"] = "Master Data";
    $template["menu"] = "NG";
    $ng = new Production();

    if (isset($_GET["id"])) {
        $id = $_GET["id"];
        if (isset($_POST["save"])) {
            $param = $_POST;
            if ($id == "0") {
                $save = $ng->insertNGType($param);
                if ($save == true) {
                    header("location: ?action=ng&success=Data berhasil disimpan!");
                } else {
                    header("location: ?action=ng&error=" . $save["message"] . "");
                }
            } else {
                $save = $ng->updateNGType($param);
                if ($save == true) {
                    header("location: ?action=ng&success=Data berhasil diupdate!");
                } else {
                    header("location: ?action=ng&error=" . $save["message"] . "");
                }
            }
        } else {
            $data["data"] = $ng->getNGId($id);
            require(TEMPLATE_PATH . "/t_ng_edit.php");
        }
    } else {
        $data["ng"] = $ng->getNGType();
        require(TEMPLATE_PATH . "/t_ng_list.php");
    }
}

if ($action == "ng_pos") {
    $template["group"] = "Master Data";
    $template["menu"] = "NG Position";
    $ng = new Production();
    $dies = new Dies();

    if (isset($_GET["id"])) {
        $id = $_GET["id"];
        if (isset($_POST["save"])) {
            $param = $_POST;
            if ($id == "0") {
                $save = $ng->insertNGPos($param);
                if ($save == true) {
                    header("location: ?action=ng_pos&success=Data berhasil disimpan!");
                } else {
                    header("location: ?action=ng_pos&error=" . $save["message"] . "");
                }
            } else {
                $save = $ng->updateNGPos($param);
                if ($save == true) {
                    header("location: ?action=ng_pos&success=Data berhasil diupdate!");
                } else {
                    header("location: ?action=ng_pos&error=" . $save["message"] . "");
                }
            }
        } else {
            // if ($id == "1") {
            //     $group = $_GET["group"];
            //     $model = $_GET["model"];
            //     $no = $_GET["no"];
            // }
            $group_list = $dies->getDiesGroup();
            $model_list = $dies->getAllModel();
            $data["data"] = $ng->getNGPosById($id);
            require(TEMPLATE_PATH . "/t_ng_pos_edit.php");
        }
    } else {
        if (isset($_GET['upload'])) {
            require_once 'classes/PHPExcel.php';
            require_once 'classes/PHPExcel/IOFactory.php';
            $fileName = $_FILES["excel"]["tmp_name"];
            try {
                $objPHPExcel = PHPExcel_IOFactory::load($fileName);
            } catch (Exception $e) {
                header("Location: ?action=" . $action . "&error=" . $e->getMessage());
            }
            $activeSheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
            $success = 0;
            $fail = 0;
            $processed = 0;
            $i = 0;
            if (!empty($activeSheetData)) {
                foreach ($activeSheetData as $row) {
                    if ($i > 0) {
                        //check if exist
                        $param = array();
                        if (empty($row["A"])) {
                            break;
                        }
                        $param["ng_id"] = $row["A"];
                        $param["group"] = $row["B"];
                        $param["model"] = $row["C"];
                        $param["no"] = $row["D"];
                        $param["desc"] = $row["E"];

                        if ($ng->isExist($row["A"])) {
                            $save = $ng->updateNGPos($param);
                        } else {
                            $save = $ng->insertNGPos($param);
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
        $data["ng"] = $ng->getNGPos();
        require(TEMPLATE_PATH . "/t_ng_pos_list.php");
    }
}
?>