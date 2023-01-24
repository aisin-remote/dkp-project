<?php
if ($action == "dies_model") {
  $template["group"] = "Master Data";
  $template["menu"] = "Dies Model";
  $data["list"];
  $class = new Dies();
  if (isset($_GET["id"])) {
    $id = $_GET["id"];
    if (isset($_POST["save"])) {
      $param = $_POST;
      $param["crt_by"] = $_SESSION[LOGIN_SESSION];
      $param["chg_by"] = $_SESSION[LOGIN_SESSION];
      $save = array();
      if ($id == "0") {
        // var_dump($param);
        // die();
        $save = $class->insertModel($param);
      } else {
        $save = $class->updateModel($param);
      }
      if ($save["status"] == true) {
        header("Location: " . $action . "?success=Data%20Saved");
      } else {
        header("Location: " . $action . "?id=" . $id . "&error=" . $save["message"]);
      }
    } else {
      if ($id == "0") {
        $data["data"] = array();
      } else {
        $data["data"] = $class->getModelById($id);
      }
      // $device_types = $class->getDeviceType();
      $group_list = $class->getDiesGroup();
      $model_id = $_GET["id"];
      $group_id = $_GET["id2"];
      $data["line"] = $class->getListLine();
      $data["dies_line"] = $class->getDiesLine($id);
      require(TEMPLATE_PATH . "/t_dies_model_edit.php");
    }
  } else {
    $data["list"] = $class->getListModel();
    require(TEMPLATE_PATH . "/t_dies_model_list.php");
  }
}
