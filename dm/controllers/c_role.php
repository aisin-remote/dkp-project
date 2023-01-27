<?php 
if($action == "role") {
  $template["group"] = "Settings";
  $template["menu"] = "Role";
  $class = new Role();
  $menu = new Menu();
  $data["list"];
  if(isset($_GET["id"])) {
    $id = $_GET["id"];
    if(isset($_POST["save"])) {
      $param = $_POST;
      $param["rolemenu"] = $_POST["menus"];
      
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
      $data["rolemenu"] = array();
      if($id == "0") {
        $data["data"] = array();
      } else {
        $data["data"] = $class->getById($id);        
        $data["rolemenu"] = $class->getRoleMenu($id);
      }
      $data["menu"] = $menu->getList();
      require( TEMPLATE_PATH . "/t_role_edit.php" );
    }
  } else {
    $data["list"] = $class->getList();
    require( TEMPLATE_PATH . "/t_role_list.php" );
  }
}
