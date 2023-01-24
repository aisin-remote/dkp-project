<?php 
if($action == "customer") {
  $template["group"] = "Master Data";
  $template["menu"] = "Customer";
  
  $class = new Customer();
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
        header("Location: ".$action);
      } else {
        header("Location: ".$action."?id=".$id."&error=".$save["message"]);
      }
    } else {
      if($id == "0") {
        $data["data"] = array();
      } else {
        $data["data"] = $class->getById($id);
      }      
      require( TEMPLATE_PATH . "/t_lfa1_edit.php" );
    }
  } else {
    $data["list"] = $class->getList();
    require( TEMPLATE_PATH . "/t_lfa1_list.php" );
  }
}
?>