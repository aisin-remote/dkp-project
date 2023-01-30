<?php 
if($action == "mara") {
  $template["group"] = "Master Data";
  $template["menu"] = "Material";
  
  $class = new Material();
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
      require( TEMPLATE_PATH . "/t_mara_edit.php" );
    }
  } else {
    $data["list"] = $class->getList();
    require( TEMPLATE_PATH . "/t_mara_list.php" );
  }
}
?>