<?php
if ($action == "api_get_shift_time") {
    $shift = $_REQUEST["shift"];

    $class = new ContentStopShift();
    $data_model = $class->getListTime($shift);
    echo json_encode($data_model);
}

if ($action == "api_get_shift") {
    $param = new Param();

    $shiftlist = $param->getListShift();
    echo json_encode($shiftlist);
}
