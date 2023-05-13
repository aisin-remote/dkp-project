<?php
if ($action == "t_loading_list") {
    $template["group"] = "Transaction";
    $template["menu"] = "Loading List";
    $class = new Transaction();

    $lddat_from = date('Ymd', strtotime(date('Y-m-d') . '-90 day'));
    if (!empty($_GET["date_from"])) {
        $lddat_from = $_GET["date_from"];
    }

    $lddat_to = date('Ymd');
    if (!empty($_GET["date_to"])) {
        $lddat_to = $_GET["date_to"];
    }

    if (isset($_POST["chg_status"])) {
        $all_id = $_POST["chk_id"];
        $extract_id = implode("','", $all_id);

        $update = $class->updateStatus($extract_id);
        $update2 = $class->updateStatus2($extract_id);
        $update3 = $class->updateStatus3($extract_id);

        if ($update["status"] == true) {
            header("Location: ?action=" . $action . "&success=Status%20updated");
        } else {
            header("Location: ?action=" . $action . "?error=" . $update["message"]);
        }
    }

    $lifnr = $_GET["customer"];

    $customer = $class->getCustomer();
    $list = $class->getList($lddat_from, $lddat_to, $lifnr);
    require(TEMPLATE_PATH . "/t_loading_list.php");
}
?>