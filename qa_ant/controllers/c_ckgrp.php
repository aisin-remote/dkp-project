<?php 
if($action == "process_group") {
  $template["group"] = "Master Data";
  $template["menu"] = "Process Name";
  $class = new ChecklistGroup();
  
  if(isset($_POST["save"])) {
    $param = $_POST;
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
  require(TEMPLATE_PATH . "/t_ckgrp.php");
}
?>