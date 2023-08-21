<?php
if ($action == "parameter") {
    $template["group"] = "Settings";
    $template["menu"] = "Parameter";
    $parameter = new Parameter();

    if (isset($_POST["save"])) {
        $param = $_POST;
        if ($param["min_temp"] > $param["max_temp"]) {
            header("Location: ?action=parameter&error=Min Temp. must be less than Max Temp.");
            die();
        } else if ($param["min_humid"] > $param["max_humid"]) {
            header("Location: ?action=parameter&error=Min Humid. must be less than Max Humid.");
            die();
        } else {
            $param["shift"] = "STSHF" . $_POST["shift_temp"] . $_POST["shift_humid"];
            $save = $parameter->updateShift($param);
            $save1 = $parameter->updateRange($param);
            if ($save["status"] && $save1["status"]) {
                header("Location: ?action=parameter&success=Data Saved");
            } else {
                header("Location: ?action=parameter&error=" . $save["message"][2]);
            }
        }
    } else {
        $data = $parameter->getParam();
        $temp = substr($data["shift"]["pval1"], 5, 5);
        $humid = substr($data["shift"]["pval1"], 10, 5);
        require(TEMPLATE_PATH . "/t_parameter.php");
    }
}
?>