<?php
if ($action == "r_stroke_total_dies") {
    $template["group"] = "Reporting";
    $template["menu"] = "Stroke Total Dies";
    $data["list"];
    $class = new Reporting();
    $dies = new Dies();


    $group_id = $_GET["group_id"];
    $model_id = $_GET["model_id"];
    $dies_no = $_GET["dies_id"];

    // var_dump($group_id, $model_id, $dies_no);
    // die();

    $group_list = $dies->getDiesGroup();
    $model_list = $dies->getDiesModel(null, $group_list[0]["pval1"]);
    $diesid_list = $dies->getListDies(null, "A", $group_list[0]["pval1"], $model_list[0]["model_id"]);
    $data["list"] = $class->getListStroke($group_id, $model_id, $dies_no);
    require(TEMPLATE_PATH . "/t_rep_stroke_total_dies.php");
}