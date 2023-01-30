<?php 
if($action == "device") {
  $template["group"] = "Settings";
  $template["menu"] = "Device Setting";
  $data["list"];
  $class = new Device();
  if(isset($_GET["id"])) {
    $id = $_GET["id"];
    if(isset($_POST["save"])) {
      $param = $_POST;
      $save = array();
      if($id == "0") {
        //check license
        $dl = $class->getDeviceLicense();
        if($dl["devices"] < $dl["lic_vol"]) {
          $save = $class->insert($param);
        } else {
          $save["status"] = false;
          $save["message"] = "Device cannot be added, insufficient license";
        }        
      } else {
        $save = $class->update($param);
      }
      if($save["status"] == true) {
        header("Location: ?action=".$action);
      } else {
        header("Location: ?action=".$action."&id=".$id."&error=".$save["message"]);
      }
    } else {
      if($id == "0") {
        $data["data"] = array();
      } else {
        $data["data"] = $class->getById($id);
      }      
      require( TEMPLATE_PATH . "/t_device_edit.php" );
    }
  } else {
    $data["list"] = $class->getList();
    require( TEMPLATE_PATH . "/t_device_list.php" );
  }
  
}
?>