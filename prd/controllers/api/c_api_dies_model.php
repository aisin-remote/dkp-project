<?php 
if($action == "api_get_dies_model") {
  $group = $_REQUEST["group"];
  $class = new Dies();
  $data_model = $class->getDiesModel(null, $group);
  echo json_encode($data_model);
}

if($action == "api_get_default_cctime") {
  $dies_id = $_REQUEST["dies_id"];
  $class = new Dies();
  $data_dies = $class->getDiesById($dies_id, "A");
  $return = [];
  $return["cctime"] = $data_dies["ctsec"];
  
  echo json_encode($return);
}

?>