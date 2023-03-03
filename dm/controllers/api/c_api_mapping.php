<?php
if ($action == "api_mapping_dm") {
    $dies = new Dies();
    $zona = new Zona();
    $model = new Home();
    $data_dies = $dies->getListDies(null, 'A', null, null);
    $data_zona = $zona->getList();
    $data_model = $model->getDiesModel();

    $return["data_dies"] = $data_dies;
    $return["data_zona"] = $data_zona;
    $return["data_model"] = $data_model;

    echo json_encode($return);
}
?>