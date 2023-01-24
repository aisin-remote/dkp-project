<?php 
if($action == "stop_reason_action") {
  $template["group"] = "Master Data";
  $template["menu"] = "Stop Reason Action";
  $template["submenu"] = "New";
  $data["list"];
  $class = new Stop();
  if(isset($_GET["id"])) {
    $id = $_GET["id"];
    if(isset($_POST["save"])) {
      $param = $_POST;
      $param["crt_by"] = $_SESSION[LOGIN_SESSION];
      $param["chg_by"] = $_SESSION[LOGIN_SESSION];
      $save = array();
      if($id == "0") {
        $save = $class->insert($param);
      } else {
        $save = $class->update($param);
      }
      if($save["status"] == true) {
        header("Location: ".$action."?success=Data%20Saved");
      } else {
        header("Location: ".$action."?id=".$id."&error=".$save["message"]);
      }
    } else {
      if($id == "0") {
        $data["data"] = array();
      } else {
        $data["data"] = $class->getById($id);
      }
      // $device_types = $class->getDeviceType();
      $type_list = $class->getListType();
      $type2_list = $class->getListType2();

      require( TEMPLATE_PATH . "/t_stop_reason_edit.php" );
    }
  } else {
    $data["list"] = $class->getList();
    require( TEMPLATE_PATH . "/t_stop_reason.php" );
  }
}
?>