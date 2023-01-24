<?php 
if($action == "profile") {
  $template["group"] = "Settings";
  $template["menu"] = "Profile";
  $class = new User();
  if(isset($_POST["save"])) {
    $param = $_POST;
    $param["chg_by"] = $_SESSION[LOGIN_SESSION];
    $go_save = true;
    if(!empty($_POST["password1"])) {
      if($_POST["password1"] == $_POST["password2"]) {           
        $go_save = true;
        $param["usrpw"] = $_POST["password1"];
      } else {
        $error = "Password Missmatch";
        $go_save = false;
      }
    }
    
    if($go_save == true) {
      $save = $class->updateProfile($param);
      if($save["status"] == true) {
        $success = "Profile Updated";
      } else {
        $error = $save["message"];
      }
    }
  }
  $data["data"] = $class->getById($_SESSION[LOGIN_SESSION]);
  require( TEMPLATE_PATH . "/t_user_profile.php" );
}
?>