<?php
if ($action == "r_checksheet_preventive") {
  $template["group"] = "Reporting";
  $template["menu"] = "Checksheet Preventive";
  $data["list"];
  $dies = new Dies();
  $class = new Reporting();
  $zona = new Zona();

  $date_from = date('Ymd', strtotime(date('Y-m-d') . '-30 day'));
  if (!empty($_GET["date_from"])) {
    $date_from = $_GET["date_from"];
  }

  $date_to = date('Ymd');
  if (!empty($_GET["date_to"])) {
    $date_to = $_GET["date_to"];
  }

  $pmtid = $_GET["pmtid"];
  $group_id = $_GET["group_id"];
  $model_id = $_GET["model_id"];
  $dies_no = $_GET["dies_id"];
  $pmtype = $_GET["pmtype"];
  $pmstat = $_GET["pmstat"];

  if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $step = $_GET["step"];
    if (isset($_GET["step"])) {

      if ($step == "2") {
        if ($id == "0") {
          $data["data"] = array();
        } else {
          $data["data"] = $class->getById($id);
        }
        $list_zona = $zona->getList();
        // $data["list"] = $class->getList2($id);
        require(TEMPLATE_PATH . "/t_rep_checksheet2.php");
      }
    }
  } else {

    $data["list"] = $class->getListChecksheet($date_from, $date_to, $pmtid, $group_id, $model_id, $dies_no, $pmtype, 'C');
    $group_list = $dies->getDiesGroup();
    $model_list = $dies->getDiesModel(null, $group_list[0]["pval1"]);
    $diesid_list = $dies->getListDies(null, "A", $group_id, $model_id);
    require(TEMPLATE_PATH . "/t_rep_checksheet.php");
  }
}

if ($action == "r_checksheet_prev_detail") {
  $template["group"] = "Reporting";
  $template["menu"] = "Checksheet Preventive Detail";

  $dies = new Dies();
  $class = new Reporting();
  $zona = new Zona();

  $date_from = date('Ymd', strtotime(date('Y-m-d') . '-30 day'));
  if (!empty($_GET["date_from"])) {
    $date_from = $_GET["date_from"];
  }

  $date_to = date('Ymd');
  if (!empty($_GET["date_to"])) {
    $date_to = $_GET["date_to"];
  }

  $pmtid = $_GET["pmtid"];
  $group_id = $_GET["group_id"];
  $model_id = $_GET["model_id"];
  $dies_no = $_GET["dies_id"];
  $pmtype = $_GET["pmtype"];
  $pmstat = $_GET["pmstat"];

  $data["list"] = $class->getListChecksheet($date_from, $date_to, $pmtid, $group_id, $model_id, $dies_no, $pmtype, 'C');
  $group_list = $dies->getDiesGroup();
  $model_list = $dies->getDiesModel(null, $group_list[0]["pval1"]);
  $diesid_list = $dies->getListDies(null, "A", $group_id, $model_id);
  require(TEMPLATE_PATH . "/t_rep_checksheet_detail.php");
}