<?php 
if($action == "menu") {
  $template["group"] = "Settings";
  $template["menu"] = "Menu";
  $data["list"];
  $class = new Menu();
  if(isset($_GET["id"])) {
    $id = $_GET["id"];
    if(isset($_POST["save"])) {
      $param = $_POST;
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
      $data["menugroup"] = $class->getMenuGroup();
      require( TEMPLATE_PATH . "/t_menu_edit.php" );
    }
  } else {
    $data["list"] = $class->getList();
    require( TEMPLATE_PATH . "/t_menu_list.php" );
  }
  
}
?>