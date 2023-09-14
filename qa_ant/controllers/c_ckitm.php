<?php 
if($action == "check_item") {
  $template["group"] = "Master Data";
  $template["menu"] = "Check Item";
  $class = new ChecklistItem();
  $group = new ChecklistGroup();
  $device = new MeasureDevice();
  
  if(isset($_POST["save"])) {
    $param = $_POST;
    $param["itm_no"] = $param["itm_id"];
    if($param["save"] == "I") {
      $save = $class->insert($param);
    } else {
      $save = $class->update($param);
    }
    if($save["status"] == true) {
      header("Location: ?action=".$action."&success=Data%20Saved.");
    } else {
      $error = $save["message"];
      header("Location: ?action=".$action."&error=$error.");
    }    
  }
  
  if(isset($_GET["delete"])) {
    $save = $class->delete($_GET["id"]);
    if($save["status"] == true) {
      header("Location: ?action=".$action."&success=Data%20Deleted.");
    } else {
      $error = $save["message"];
      header("Location: ?action=".$action."&error=$error.");
    } 
  }
  
  $data["list"] = $class->getList();
  $group_list = $group->getList();
  $dev_list = $device->getList();
  require(TEMPLATE_PATH . "/t_ckitm.php");
}
?>