<?php 
if($action == "api_get_dies_model") {
  $group = $_REQUEST["group"];
  $class = new Dies();
  $data_model = $class->getDiesModel(null, $group);
  echo json_encode($data_model);
}

if($action == "api_get_dies_list") {
  $model = $_REQUEST["model"];
  $group = $_REQUEST["group_id"];
  $class = new Dies();
  $data_dies = $class->getListDies(null, "A", $group, $model);
  echo json_encode($data_dies);
}

?>