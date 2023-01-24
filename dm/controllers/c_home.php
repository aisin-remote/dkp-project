<?php 
if($action == "home") {
  $template["group"] = "Home";
  $template["menu"] = "Dashboard";
  $dies = new Dies();
  $data_group = $dies->getDiesGroup();
  $data_dies = $dies->getListDies();
  
  if(!empty($data_dies)) {
    $i = 0;
    foreach($data_dies as $row) {
      $data_dies[$i]["bg_color"] = "table-dark-blue";
      if(floatval($row["stkrun"]) >= floatval($row["ewstk"])) {
        $data_dies[$i]["bg_color"] = "bg-warning";
      }
      
      if(floatval($row["stkrun"]) >= 2000) {
        $data_dies[$i]["bg_color"] = "bg-danger";
      }
      $i++;
    }
  }
  require( TEMPLATE_PATH . "/t_home.php" );
}
?>