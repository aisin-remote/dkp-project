<?php 
header("Access-Control-Allow-Origin: *");
$data = array();
$string = file_get_contents("version.json");
$json_a = json_decode($string, true);
$data["version_number"] = $json_a["version_number"];
$data["version_code"] = $json_a["version_code"];

echo json_encode($data);
?>