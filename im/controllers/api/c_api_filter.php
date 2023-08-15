<?php
if ($action == "api_get_material") {
    $type = $_REQUEST["type"];
    $class = new Material();
    $data = $class->getListByType($type);
    echo json_encode($data);
}
?>