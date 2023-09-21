<?php 
if($action == "videotron") {
  $dies = new Dies();
  $class = new Home();
  $zona = new Zona();
  $data_group = $dies->getDiesGroup();
  $data_dies = $dies->getListDies(null, 'A');
  $data_model = $class->getDiesModel();
  $dies_prod = $dies->getDiesProd1();
  $list_zona = $zona->getList();
  
  $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
  $sql = "SELECT pval1 as spd FROM m_param "
      . "WHERE pid = 'VTRONSPD' AND seq = '1'";
  $vtron_spd = 3000;
  $stmt = $conn->prepare($sql);
  if ($stmt->execute()) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $vtron_spd = intval($row["spd"]);
    }
  }  
  $stmt = null;
  $conn = null;

  if (!empty($data_dies)) {
    $i = 0;
    foreach ($data_dies as $row) {
      $data_dies[$i]["bg_color"] = "";
      if (floatval($row["stkrun"]) >= floatval($row["ewstk"])) {
        $data_dies[$i]["bg_color"] = "bg-yellow";
      }

      if (floatval($row["stkrun"]) >= 2000) {
        $data_dies[$i]["bg_color"] = "bg-danger";
      }

      if ($row["gstat"] == 'P') {
        $data_dies[$i]["bg_color"] = "bg-blink";
        foreach ($dies_prod as $dies) {
          if ($dies["dies_id"] == $row["dies_id"]) {
            $data_dies[$i]["bg_color"] = "bg-red-blink";
          }
        }
      }
      if ($row["gstat"] == 'R') {
        $data_dies[$i]["bg_color"] = "bg-blink";
        foreach ($dies_prod as $dies) {
          if ($dies["dies_id"] == $row["dies_id"]) {
            $data_dies[$i]["bg_color"] = "bg-red-blink";
          }
        }
      }
      if ($row["iostat"] == 'Maker') {
        $data_dies[$i]["bg_color"] = "bg-amber";
      }
      $i++;
    }
  }
  require(TEMPLATE_PATH . "/t_video_tron.php");  
}
?>