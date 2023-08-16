<?php
if ($action == "content_stop_per_shift") {
    $template["group"] = "Master Data";
    $template["menu"] = "Content Stop per Shift";
    $data["list"];
    $class = new ContentStopShift();
    if (isset($_GET["id"])) {
        $id = $_GET["id"];
        $id2 = $_GET["id2"];
        $id3 = $_GET["id3"];
        if (isset($_POST["save"])) {
            $param = $_POST;
            $param["crt_by"] = $_SESSION[LOGIN_SESSION];
            $param["chg_by"] = $_SESSION[LOGIN_SESSION];
            $time_start = $param["time_start"];
            $time_end = $param["time_end"];
            $start = DateTime::createFromFormat('H:i', $time_start);
            $end = DateTime::createFromFormat('H:i', $time_end);
            $diff_in_minutes = ($end->getTimestamp() - $start->getTimestamp()) / 60;
            $param["stop_time"] = $diff_in_minutes;
            $param["id"] = $id;
            $save = array();
            if ($id == "0") {
                // var_dump($param);
                // die();
                $save = $class->insert($param);
            } else {
                $save = $class->update($param);
            }
            if ($save["status"] == true) {
                header("Location: ?action=" . $action . "&success=Data%20Saved");
            } else {
                header("Location: ?action=" . $action . "&id=" . $id . "&error=" . $save["message"]);
            }
        } else {
            if ($id == "0") {
                $data["data"] = array();
            } else {
                $data["data"] = $class->getById($id, $id2, $id3);
                $time_list = $class->getListTime($data["data"]["shift_id"]);
            }
            // $device_types = $class->getDeviceType();

            $shift_list = $class->getListShift();
            $srna_list = $class->getSrna();

            require(TEMPLATE_PATH . "/t_content_stop_shift_edit.php");
        }
    } else {
        $data["list"] = $class->getList();
        require(TEMPLATE_PATH . "/t_content_stop_shift.php");
    }
}
