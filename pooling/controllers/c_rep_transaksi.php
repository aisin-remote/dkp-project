<?php
if ($action == "r_transaksi_scanner") {
    $template["group"] = "Report";
    $template["menu"] = "Report Transaction Scanner";
    $class = new Report();


    $lddat_from = date('Ymd', strtotime(date('Y-m-d') . '-30 day'));
    if (!empty($_GET["date_from"])) {
        $lddat_from = $_GET["date_from"];
    }

    $lddat_to = date('Ymd');
    if (!empty($_GET["date_to"])) {
        $lddat_to = $_GET["date_to"];
    }

    $fil_cust = $_GET["customer"];

    if (isset($_GET["id"])) {
        $id = $_GET["id"];

        $list = $class->getList2($id);
        require(TEMPLATE_PATH . "/t_rep_trans2.php");
    } else {

        $customer = $class->getCustomer();
        $list = $class->getList($lddat_from, $lddat_to, $fil_cust);
        require(TEMPLATE_PATH . "/t_rep_trans.php");
    }
}
