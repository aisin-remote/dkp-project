<?php
if ($action == "dies_model") {
  $template["group"] = "Master Data";
  $template["menu"] = "Dies Model";
  $data["list"];
  $class = new Dies();
  $production = new Production();
  if (isset($_GET["id"])) {
    $id = $_GET["id"];
    if (isset($_POST["save"])) {
      $param = $_POST;
      $param["crt_by"] = $_SESSION[LOGIN_SESSION];
      $param["chg_by"] = $_SESSION[LOGIN_SESSION];
      $save = array();
      if ($id == "0") {
        $save = $class->insertModel($param);
      } else {
        $save = $class->updateModel($param);
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

        $group_id = $_GET["id2"];
        $data["data"] = $class->getModelById($id, $group_id);
      }
      // $device_types = $class->getDeviceType();
      $model_id = $_GET["id"];
      $group_id = $_GET["id2"];
      $group_list = $class->getDiesGroup();
      $line_list = $production->getListLine();
      $data["line"] = $class->getListLine();
      $data["dies_line"] = $class->getDiesLine($model_id, $group_id);
      $data["line_name"] = $class->getLineName();

      // var_dump($data["line_name"]);
      // die();
      require(TEMPLATE_PATH . "/t_dies_model_edit.php");
    }
  } else {
    $data["list"] = $class->getListModel();
    require(TEMPLATE_PATH . "/t_dies_model_list.php");
  }
}
