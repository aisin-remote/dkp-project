<?php
if ($action == "api_get_dies_model") {
  $group = $_REQUEST["group"];
  $class = new Dies();
  $data_model = $class->getDiesModel(null, $group);
  echo json_encode($data_model);
}

if ($action == "api_get_dies_list") {
  $model = $_REQUEST["model"];
  $group = $_REQUEST["group_id"];
  $return = array();
  $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
  $sql = "SELECT a.dies_id, a.dies_no, a.model_id, a.group_id, a.name1, a.stktot, a.stkrun, a.stats FROM mach.m_dm_dies_asset a
  INNER JOIN mach.m_dm_dies_model b ON b.model_id = a.model_id AND b.group_id = a.group_id
  WHERE 1=1 and a.model_id = '$model' and a.group_id = '$group' and a.stats = 'A' ";
  $stmt = $conn->prepare($sql);
  if ($stmt->execute()) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $return[] = $row;
    }
  }
  // $class = new Dies();
  // $data_dies = $class->getListDies(null, "A", $group, $model);
  echo json_encode($return);
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
  $sql = "SELECT gstat FROM mach.m_dm_dies_asset WHERE dies_id = '$dies_id' ";
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