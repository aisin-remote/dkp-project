<?php
if ($action == "r_stroke_total_dies") {
    $template["group"] = "Reporting";
    $template["menu"] = "Stroke Total Dies";
    $data["list"];
    $class = new Reporting();

    $group_id = $_GET["group_id"];
    $model_id = $_GET["model_id"];
    $dies_no = $_GET["dies_no"];

    // var_dump($group_id, $model_id, $dies_no);
    // die();

    $data["list"] = $class->getListStroke($group_id, $model_id, $dies_no);
    require(TEMPLATE_PATH . "/t_rep_stroke_total_dies.php");
}
