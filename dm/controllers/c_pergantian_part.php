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
      
      $save = array();
      $item = $param["item"];
      foreach($item as $key=>$val) {
        if($val == "on") {
          $item[$key] = "Y";
        }
      }
      $param["item"] = $item;
      //var_dump($item); die();
      if ($id == "0") {
        $save = $class->insert($param);
      } else {
        $save = $class->update($param);
      }

      if ($save["status"] == true) {
        header("Location: " . $action . "?success=Data%20Saved");
      } else {
        header("Location: " . $action . "?error=" . $save["message"]);
      }
    } else {
      $group_list = $dies->getDiesGroup();
      
      if($id == "0") {
        $template["submenu"] = "New";
        $model_list = $dies->getDiesModel(null, $group_list[0]["pval1"]);
        $dies_list = $dies->getListDies(null,$model_list[0]["model_id"]);
      } else {        
        $template["submenu"] = "Edit";
        $data["data"] = $class->getById($id);
        $data["item"] = $class->getItem($id);
        $model_list = $dies->getDiesModel(null, $data["data"]["group_id"]);
        $dies_list = $dies->getListDies(null,$data["data"]["model_id"]);
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
