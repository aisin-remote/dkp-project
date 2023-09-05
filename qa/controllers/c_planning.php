<?php
if ($action == "planning") {
    $template["group"] = "Master Data";
    $template["menu"] = "Planning";
    $class = new Planning();

    if (isset($_GET["id"])) {
        $id = $_GET["id"];

        if (isset($_POST["save"])) {
            $param = $_POST;
            if ($id == 0) {
                $save = $class->insert($param);
                if ($save["status"] == "success") {
                    header("Location: ?action=" . $action . "&success=" . $save["message"]);
                } else {
                    header("Location: ?action=" . $action . "&error=" . $save["message"]);
                }
            } else {
                $save = $class->update($param);
                if ($save["status"] == "success") {
                    header("Location: ?action=" . $action . "&success=" . $save["message"]);
                } else {
                    header("Location: ?action=" . $action . "&error=" . $save["message"]);
                }
            }
        } else {
            $data["data"] = $class->getPlanById($id, $_GET["month"]);
            require(TEMPLATE_PATH . "/t_planning_edit.php");
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
                        $param["partno"] = $row["A"];
                        $param["month"] = $row["B"];

                        if ($class->isExist($row["A"], $row["B"])) {
                            $save = $class->update($param);
                        } else {
                            $save = $class->insert($param);
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
        $data["list"] = $class->getList();
        require(TEMPLATE_PATH . "/t_planning_list.php");
    }
}
?>