<?php 
if($action == "home") {
  $template["group"] = "Home";
  $template["menu"] = "Dashboard";
  $production = new Production();
  $today = date("Y-m-d");
  
  $data = array();
  $data["list"] = $production->getSummaryByDate($today);
  
  require( TEMPLATE_PATH . "/t_home.php" );
}
?>