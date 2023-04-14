<?php
if ($action == "api_insert_daily_stop") {
  $class = new Production();
  $stop = new Stop();
  $param = $_REQUEST;
  if (empty($param["qty_stc"])) {
    $param["qty_stc"] = "0";
  }
  $save = array();
  //recalculate stop_time agar jika ada salah parameter dari post js bisa dibetulkan di sini
  $start_time = strtotime($param["prd_dt"]." ".$param["start_time"].":00");
  $end_time = strtotime($param["prd_dt"]." ".$param["end_time"].":00");
  $menit = round(abs($end_time - $start_time) / 60,2);
  $param["stop_time"] = $menit;
  //end of recalculate stop_time
  if (!empty($param["line_id"])) {
    //cek dulu tipe stop apakah planned atau unplanned,
    //jika planned maka recalculate production time dan planned qty
    $cek_data = $stop->getById($param["stop_id"]);
    if ($cek_data["type2"] == "P") {
      $fdate = str_replace("-", "", $param["prd_dt"]);
      $daily_i = $class->getItemById($param["line_id"], $fdate, $param["shift"], $param["prd_seq"]);
      $cctime = $daily_i["cctime"];
      $prd_time = floatval($daily_i["prd_time"]);
      $prd_time -= floatval($param["stop_time"]);
      $target_per_jam = ($prd_time * 60) / floatval($cctime);
      $pln_qty = round($target_per_jam, 0, PHP_ROUND_HALF_UP);
      $save = $class->updateTargetDailyI($param["line_id"], $param["prd_dt"], $param["shift"], $param["prd_seq"], $prd_time, $pln_qty);
      if ($save["status"] == true) {
        $save = $class->insertStop($param);
      } else {
        echo json_encode($save);
        die();
      }
    } else {
      $save = $class->insertStop($param);
    }
  }
  echo json_encode($save);
}

if ($action == "api_delete_daily_stop") {
  $del = array();
  $class = new Production();
  $line_id = $_REQUEST["line_id"];
  $prd_dt = $_REQUEST["prd_dt"];
  $shift = $_REQUEST["shift"];
  $prd_seq = $_REQUEST["prd_seq"];
  $stop_seq = $_REQUEST["stop_seq"];
  //cek dulu sebelum delete type planned atau unplanned
  //jika tipe planned maka re-calculate production time dan planning Qty
  $cek_data = $class->getPrdStop($line_id, $prd_dt, $shift, $prd_seq, $stop_seq);
  if ($cek_data["type2"] == "P") {
    //e-calculate production time dan planning Qty
    $fdate = str_replace("-", "", $prd_dt);
    $daily_i = $class->getItemById($line_id, $fdate, $shift, $prd_seq);
    $cctime = $daily_i["cctime"];
    $prd_time = floatval($daily_i["prd_time"]);
    $prd_time += floatval($cek_data["stop_time"]);
    $target_per_jam = ($prd_time * 60) / floatval($cctime);
    $pln_qty = round($target_per_jam, 0, PHP_ROUND_HALF_UP);
    $del = $class->updateTargetDailyI($line_id, $prd_dt, $shift, $prd_seq, $prd_time, $pln_qty);
    if ($del["status"] == true) {
      $del = $class->deleteStop($line_id, $prd_dt, $shift, $prd_seq, $stop_seq);
      $del = $class->deleteExeStop($line_id, $prd_dt, $shift, $prd_seq, $stop_seq);
    } else {
      echo json_encode($del);
      die();
    }
  } else {
    $del = $class->deleteStop($line_id, $prd_dt, $shift, $prd_seq, $stop_seq);
    $del = $class->deleteExeStop($line_id, $prd_dt, $shift, $prd_seq, $stop_seq);
  }

  echo json_encode($del);
}

if ($action == "api_insert_daily_ng") {
  $class = new Production();
  $param = $_REQUEST;
  if (empty($param["ng_qty"])) {
    $param["ng_qty"] = 0;
  }
  $save = array();
  if (!empty($param["line_id"])) {
    $save = $class->insertNG($param);
  }
  echo json_encode($save);
}

if ($action == "api_delete_daily_ng") {
  $class = new Production();
  $line_id = $_REQUEST["line_id"];
  $prd_dt = $_REQUEST["prd_dt"];
  $shift = $_REQUEST["shift"];
  $prd_seq = $_REQUEST["prd_seq"];
  $ng_seq = $_REQUEST["ng_seq"];
  $del = array();
  $del = $class->deleteNG($line_id, $prd_dt, $shift, $prd_seq, $ng_seq);
  echo json_encode($del);
}

if ($action == "api_get_time_stop") {
  $shift = $_REQUEST["shift"];
  $srna_id = $_REQUEST["srna_id"];
  $time_id = $_REQUEST["time_id"];
  $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
  $sql = "select * from m_prd_shift_stop where srna_id = '$srna_id' and shift_id = '$shift' AND time_id = '$time_id' LIMIT 1";
  $stmt = $conn->prepare($sql);
  $return = [];
  $data_stop = [];
  if ($stmt->execute()) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $data_stop = $row;
    }
  }
  if (!empty($data_stop)) {
    $return["status"] = true;
    $return["data"] = $data_stop;
  } else {
    $return["status"] = false;
    $return["message"] = "No data Found";
  }
  $stmt = null;
  $conn = null;
  echo json_encode($return);
}

if ($action == "api_approve_daily_i") {
  $return = [];
  $line_id = $_REQUEST["line_id"];
  $prd_dt = $_REQUEST["prd_dt"];
  $shift = $_REQUEST["shift"];
  $prd_seq = $_REQUEST["prd_seq"];
  $apr_by = $_SESSION[LOGIN_SESSION];
  $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
  $query = "UPDATE t_prd_daily_i SET stats = 'A', apr_by = '$apr_by', apr_dt = CURRENT_TIMESTAMP WHERE line_id = '$line_id' AND prd_dt = '$prd_dt' AND shift = '$shift' AND prd_seq = '$prd_seq'";
  $stmt = $conn->prepare($query);
  if ($stmt->execute()) {
    $return["status"] = true;
  } else {
    $return["status"] = false;
    $error = $stmt->errorInfo();
    $return["message"] = trim(str_replace("\n", " ", $error[2]));
  }
  $stmt = null;
  $conn = null;
  echo json_encode($return);
}
