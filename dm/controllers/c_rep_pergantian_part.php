<?php

if ($action == "r_pergantian_part") {
    $template["group"] = "Reporting";
    $template["menu"] = "Pergantian Part";
    $data["list"];
    $class = new Reporting();

    $date_from = date('Ymd', strtotime(date('Y-m-d') . '-30 day'));
    if (!empty($_GET["date_from"])) {
        $date_from = $_GET["date_from"];
    }

    $date_to = date('Ymd');
    if (!empty($_GET["date_to"])) {
        $date_to = $_GET["date_to"];
    }

    $group_id = $_GET["group_id"];
    $model_id = $_GET["model_id"];
    $dies_no = $_GET["dies_no"];

    $data["list"] = $class->getListPergantianPart($date_from, $date_to, $group_id, $model_id, $dies_no);
    require(TEMPLATE_PATH . "/t_rep_pergantian_part.php");
}
