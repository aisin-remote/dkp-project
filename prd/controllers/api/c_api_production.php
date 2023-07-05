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
  $dies = new Dies();
  $param = $_REQUEST;
  if (empty($param["ng_qty"])) {
    $param["ng_qty"] = 0;
  }
  $prev_data = $class->getItemById($param["line_id"], str_replace("-", "", $param["prd_dt"]), $param["shift"], $param["prd_seq"]);
  $prev_dies_id = $prev_data["dies_id"];
  //var_dump($prev_data); die();
  $save = array();
  if (!empty($param["line_id"])) {
    $save = $class->insertNG($param);
    if($save == true) {
      $dies->updateStroke($prev_dies_id, $prev_dies_id, 0, $param["ng_qty"]);
    }
  }
  echo json_encode($save);
}

if ($action == "api_delete_daily_ng") {
  $class = new Production();
  $dies = new Dies();
  $line_id = $_REQUEST["line_id"];
  $prd_dt = $_REQUEST["prd_dt"];
  $shift = $_REQUEST["shift"];
  $prd_seq = $_REQUEST["prd_seq"];
  $ng_seq = $_REQUEST["ng_seq"];
  
  $get_prev_ng = $class->getNGByID($line_id, $prd_dt, $shift, $prd_seq, $ng_seq);
  //var_dump($get_prev_ng);die();
  $del = array();
  $del = $class->deleteNG($line_id, $prd_dt, $shift, $prd_seq, $ng_seq);
  if($del["status"] == true) {
    $dies->updateStroke($get_prev_ng["dies_id"], $get_prev_ng["dies_id"], $get_prev_ng["ng_qty"], 0);
  }
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

if ($action == "api_get_qty") {
  $return = array();
  $prd_dt = $_REQUEST["date"];
  // echo $prd_dt;
  // die();
  $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
  // $query = "select t.line_name, TO_CHAR(t.prd_dt, 'DD-MM-YYYY') as date, t.pval1 as shift, t.ok_qty,
  // (select coalesce(sum(ng_qty),0) as ng_qty FROM t_prd_daily_ng WHERE prd_dt = t.prd_dt AND shift = t.shift AND line_id = t.line_id), 
  // round((t.ok_qty * t.cctime / 60 / t.prd_time * 100)::numeric, 2) as eff from 
  // (select TO_CHAR(a.prd_dt, 'YYYY') as prd_year, TO_CHAR(a.prd_dt,'MM') as prd_month, f.pval1,
  // a.prd_dt, a.line_id, b.name1 as line_name, a.shift, d.name1 as ld_name, e.name1 as jp_name,
  // AVG(a.cctime) as cctime, sum(a.prd_time) as prd_time, sum(a.pln_qty) as pln_qty, sum(a.prd_qty) as ok_qty
  // from t_prd_daily_i a 
  // inner join m_prd_line b ON b.line_id = a.line_id and b.line_ty = 'DM'
  // inner join t_prd_daily_h c on c.prd_dt = a.prd_dt and c.line_id = a.line_id and c.shift = a.shift
  // left join m_prd_operator d on d.empid = c.ldid
  // left join m_prd_operator e on e.empid = c.jpid
  // inner join m_param f on f.pid = 'SHIFT' and f.seq = a.shift
  // where 1=1 and TO_CHAR(a.prd_dt, 'DD-MM-YYYY') = '$prd_dt'
  // group by 1,2,3,4,5,6,7,8,9) t";
  $query = "select line_name, date, coalesce(sum(ok_qty),0) as ok_qty, coalesce(sum(ng_qty),0) as ng_qty FROM ( 
  select b.name1 as line_name, a.prd_seq, TO_CHAR(a.prd_dt, 'DD-MM-YYYY') as date, a.prd_qty as ok_qty, 
  (select sum(ng_qty) from t_prd_daily_ng where line_id = a.line_id and prd_dt = a.prd_dt and prd_seq = a.prd_seq) as ng_qty 
  from t_prd_daily_i a 
  inner join m_prd_line b ON b.line_id = a.line_id and b.line_ty = 'DM' 
  where a.prd_dt = '$prd_dt') t group by 1,2 
  group by 1,2";
  $stmt = $conn->prepare($query);
  if ($stmt->execute()) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $data[] = $row;
    }
    if (!empty($data)) {
      $return["status"] = true;
      $return["data"] = $data;
    } else {
      $return["status"] = false;
      $return["message"] = "No data Found";
    }
  } else {
    $return["status"] = false;
    $error = $stmt->errorInfo();
    $return["message"] = trim(str_replace("\n", " ", $error[2]));
  }
  $stmt = null;
  $conn = null;
  echo json_encode($return);
}
