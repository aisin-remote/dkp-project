<?php 
if($action == "home") {
  $template["group"] = "Home";
  $template["menu"] = "Dashboard";
    
  require( TEMPLATE_PATH . "/t_home.php" );
}
?>