<?php

if ($action == "pergantian_part") {
  $template["group"] = "Maintenance Activity";
  $template["menu"] = "Pergantian Part";
  $data["list"];
  $class = new PergantianPart();
  $dies = new Dies();
  if (isset($_GET["id"])) {
    $id = $_GET["id"];
    if (isset($_POST["save"])) {
      $param = $_POST;
      $param["pchid"] = $id;
      $param["crt_by"] = $_SESSION[LOGIN_SESSION];
      $param["chg_by"] = $_SESSION[LOGIN_SESSION];

      $core_f = $_POST["text_f"];

      $core_m = $_POST["text_m"];

      $remarks = $_POST["remarks"];

      $save = array();
      $item = $param["item"];
      $i = 0;
      $data_item = [];
      foreach ($item as $key => $val) {
        $data_item[$i]["part_id"] = $key;
        if ($val == "on") {
          $item[$key] = "Y";
          $data_item[$i]["part_text"] = "Y";
        } else {
          $data_item[$i]["part_text"] = $val;
        }
        $data_item[$i]["remarks"] = $remarks[$key];
        $i++;
      }

      $param["item"] = $data_item;

      if ($id == "0") {
        $save = $class->insert($param);
      } else {
        $save = $class->update($param);
      }

      if ($save["status"] == true) {
        $last_id = $id;
        if ($id == "0") {
          $last_id = $save["last_id"];
        }
        //save core F
        $class->insertCorePin($last_id, '1.3.1.1', $core_f);
        //save core M
        $class->insertCorePin($last_id, '1.3.2.1', $core_m);
        header("Location: ?action=" . $action . "&success=Data%20Saved");
      } else {
        header("Location: ?action=" . $action . "&error=" . $save["message"]);
      }
    } else {
      $group_list = $dies->getDiesGroup();
      $count_m = 0;
      $count_f = 0;
      if ($id == "0") {
        $template["submenu"] = "New";
        $model_list = $dies->getDiesModel(null, $group_list[0]["pval1"]);
        $dies_list = $dies->getListDies(null, null, $group_list[0]["pval1"], $model_list[0]["model_id"]);
      } else {
        $template["submenu"] = "Edit";
        $data["data"] = $class->getById($id);
        $data["item"] = $class->getItem($id);
        $model_list = $dies->getDiesModel(null, $data["data"]["group_id"]);
        $dies_list = $dies->getListDies(null, null, $data["data"]["group_id"], $data["data"]["model_id"]);
        $count_f = $class->countCorePin($id, '1.3.1.1') + 1;
        $count_m = $class->countCorePin($id, '1.3.2.1') + 1;
        $data_core_pin = $class->getCorePin($id);
      }
      $part_list = $class->getPartList();
      require(TEMPLATE_PATH . "/t_m_pergantian_part_edit.php");
    }
  } else {

    $template["submenu"] = "List";
    $data["list"] = $class->getList();
    require(TEMPLATE_PATH . "/t_m_pergantian_part.php");
  }
}
