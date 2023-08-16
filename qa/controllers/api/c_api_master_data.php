<?php
if ($action == "api_insert_grup") {
    $grup = $_POST["grup"];
    $partno = $_POST["partno"];
    $image = $_FILES["image"];
    $param = new Param();

    $grupid = $param->generateGroupId($partno);

    $img = base64_encode(file_get_contents($image["tmp_name"]));

    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "INSERT INTO qas.m_tmpl_i (partno, grupid, desc1, img) VALUES ('" . $partno . "', '" . $grupid[0]["grupid"] . "', '" . $grup . "', '" . $img . "')";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute()) {
        $return["status"] = true;
        $return["message"] = "Data saved successfully";
    } else {
        $return["status"] = false;
        $return["message"] = "Error: " . $sql . "<br>" . $conn->errorInfo();
    }

    echo json_encode($return);
}
if ($action == "api_insert_field") {
    $partno = $_POST["partno"];
    $grupid = $_POST["grupid"];
    $desc = $_POST["desc"];
    $field = $_POST["field"];
    $cellid = $_POST["cellid"];
    $templ = new TempModel();
    $list_field = $templ->getListItemById($partno);

    foreach ($list_field as $key => $value) {
        if ($value["cellid"] == $cellid) {
            $return["status"] = "false1";
            $return["message"] = "Nomor already exist!";
            echo json_encode($return);
            die();
        }
        if ($value["cell_map"] == $field) {
            $return["status"] = "false2";
            $return["message"] = "Field already exist!";
            echo json_encode($return);
            die();
        }
    }

    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "INSERT INTO qas.m_tmpl_map (partno, grupid, cellid, desc1, cell_map) VALUES ('" . $partno . "', '" . $grupid . "', '" . $cellid . "', '" . $desc . "', '" . $field . "')";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute()) {
        $return["status"] = true;
        $return["message"] = "Data saved successfully";
    } else {
        $return["status"] = false;
        $return["message"] = "Error: " . $sql . "<br>" . $conn->errorInfo();
    }

    echo json_encode($return);
}
if ($action == "api_delete_grup") {
    $partno = $_POST["partno"];
    $grupid = $_POST["grupid"];

    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "DELETE FROM qas.m_tmpl_i WHERE partno = '" . $partno . "' AND grupid = '" . $grupid . "'";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute()) {
        $sql = "DELETE FROM qas.m_tmpl_map WHERE partno = '" . $partno . "' AND grupid = '" . $grupid . "'";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute()) {
            $return["status"] = true;
            $return["message"] = "Data deleted successfully";
        } else {
            $return["status"] = false;
            $return["message"] = "Error: " . $sql . " - " . $conn->errorInfo();
        }
    } else {
        $return["status"] = false;
        $return["message"] = "Error: " . $sql . " - " . $conn->errorInfo();
    }

    echo json_encode($return);
}
if ($action == "api_delete_field") {
    $partno = $_POST["partno"];
    $grupid = $_POST["grupid"];
    $cellid = $_POST["cellid"];

    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "DELETE FROM qas.m_tmpl_map WHERE partno = '" . $partno . "' AND grupid = '" . $grupid . "' AND cellid = '" . $cellid . "'";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute()) {
        $return["status"] = true;
        $return["message"] = "Data deleted successfully";
    } else {
        $return["status"] = false;
        $return["message"] = "Error: " . $sql . "<br>" . $conn->errorInfo();
    }

    echo json_encode($return);
}
if ($action == "api_insert_field_excel") {
    $class = new TempModel();
    require_once 'vendor/autoload.php';

    $return = array();

    $spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
    $fileName = $_FILES["excel"]["tmp_name"];
    $grupid = $_POST["grupid"];
    $partno = $_POST["partno"];
    $templ = new TempModel();
    $list_field = $templ->getListItemById($partno);

    // echo $fileName . "+" . $grupid . "+" . $partno;
    // die();
    try {
        //code...
        $sheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($fileName);

        $activeSheetData = $sheet->getActiveSheet()->toArray(null, true, true, true);

        $success = 0;
        $fail = 0;
        $processed = 0;
        $i = 0;

        foreach ($activeSheetData as $row) {
            if ($i > 0) {
                //check if exist
                $param = array();
                if (empty($row["A"])) {
                    break;
                }

                foreach ($list_field as $key => $value) {
                    if ($value["cellid"] == $row["A"]) {
                        $return["status"] = "false1";
                        $return["message"] = "Nomor already exist!";
                        echo json_encode($return);
                        die();
                    }
                    if ($value["cell_map"] == $row["C"]) {
                        $return["status"] = "false2";
                        $return["message"] = "Field already exist!";
                        echo json_encode($return);
                        die();
                    }
                }

                $param["cellid"] = $row["A"];
                $param["desc1"] = $row["B"];
                $param["field"] = $row["C"];

                if ($class->mapIsExist($partno, $grupid, $row["cellid"])) {
                    $save = $class->updateMap($param, $partno, $grupid);
                } else {
                    $save = $class->insertMap($param, $partno, $grupid);
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

        $return["status"] = true;
        $return["message"] = "Upload Complete [$success] data success, [$fail] data failed, [$processed] data processed";
    } catch (\Throwable $th) {
        //throw $th;
        $return["status"] = false;
        $return["message"] = "Error: " . $th->getMessage();
    }

    echo json_encode($return);
}
?>