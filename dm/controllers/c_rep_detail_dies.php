<?php 

if($action == "detail_dies_stroke") {
    $template["group"] = "Report";
    $template["menu"] = "Detail Dies Stroke";
    $data["list"];
    $class = new Dies();
    require( TEMPLATE_PATH . "/t_detail_dies_stroke.php" );
  }

?>