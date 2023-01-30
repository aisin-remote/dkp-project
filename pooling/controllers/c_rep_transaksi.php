<?php
if ($action == "r_transaksi_scanner") {
    $template["group"] = "Report";
    $template["menu"] = "Report Transaction Scanner";
    $class = new Report();

    // $date_from = date('Ymd');
    // if (!empty($_GET["date_from"])) {
    //     $date_from = $_GET["date_from"];
    // }

    // $date_to = date('Ymd');
    // if (!empty($_GET["date_to"])) {
    //     $date_to = $_GET["date_to"];
    // }

    // $rfid_id = $_GET["rfid_id"];

    // $device_id = $_GET["device_name"];

    // $device_list = $device->getList();

    $list = $class->getList();

    require(TEMPLATE_PATH . "/t_rep_trans.php");
}
