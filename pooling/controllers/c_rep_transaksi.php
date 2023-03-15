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

        if (isset($_GET["seq"])) {
            $seq = $_GET["seq"];
            $list = $class->getList3($id);
            require(TEMPLATE_PATH . "/t_rep_trans3.php");
        } else {
            $list = $class->getList2($id);
            require(TEMPLATE_PATH . "/t_rep_trans2.php");
        }
    } else {

        $customer = $class->getCustomer();
        $list = $class->getList();
        require(TEMPLATE_PATH . "/t_rep_trans.php");
    }
}

if ($action == "r_transaksi_scanner_detail") {
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

    $customer = $class->getCustomer();
    $list = $class->getListDetail();
    require(TEMPLATE_PATH . "/t_rep_trans_detail.php");
}

if ($action == "r_transaksi_scanner_kanban") {
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

    $customer = $class->getCustomer();
    $list = $class->getListDetail2();
    require(TEMPLATE_PATH . "/t_rep_trans_detail2.php");
}