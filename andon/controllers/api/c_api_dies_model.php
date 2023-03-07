<?php
if ($action == "api_get_dies_model") {
  $group = $_REQUEST["group"];
  $class = new Dies();
  $data_model = $class->getDiesModel(null, $group);
  echo json_encode($data_model);
}

if ($action == "api_get_default_cctime") {
  $dies_id = $_REQUEST["dies_id"];
  $class = new Dies();
  $data_dies = $class->getDiesById($dies_id, "A");
  $return = [];
  $return["cctime"] = $data_dies["ctsec"];

  echo json_encode($return);
}

if ($action == "api_get_dies_preventive") {
  $dies_id = $_GET['dies_id'];
  $return = array();
  $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
  $sql = "SELECT gstat FROM m_dm_dies_asset WHERE dies_id = '$dies_id' ";
  $stmt = $conn->prepare($sql);
  if ($stmt->execute()) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $return = $row;
    }
  }
  $data = array(
    'gstat' => $return['gstat'],
  );

  echo json_encode($data);
}
