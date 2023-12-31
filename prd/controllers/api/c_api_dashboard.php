<?php
if ($action == "api_dashboard_prd") {
  $today = date("Y-m-d");
  //$today = "2023-01-26";
  $jam_now = intval(date("H"));
  if($jam_now >= 0 && $jam_now <= 6) {
    $today = date("Y-m-d",strtotime($today." -1 days"));
  }
  $min_now = intval(date("i"));
  /*if($min_now > 0) {
  $jam_now += 1;
  }*/
  // $jam_end = str_pad($jam_now, 2, "0", STR_PAD_LEFT);
  $jam_end = date("H", strtotime('-1 hours'));
  //$jam_end = "16";
  $message = [];
  $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
  /*$query = "select a.line_id, b.name1 as line_name, c.gstat, CONCAT(c.group_id,' ',c.model_id,' ',c.dies_no) as dies_name, a.cctime, a.pln_qty, a.prd_time, coalesce(a.prd_qty,0) as prd_qty, 
            (select coalesce(sum(ng_qty),0) as ril_qty from t_prd_daily_ng 
            WHERE line_id = a.line_id and prd_dt = a.prd_dt and shift = a.shift and prd_seq = a.prd_seq and SUBSTRING(ng_type,1,3) = 'RIL'), 
            (select coalesce(sum(ng_qty),0) as rol_qty from t_prd_daily_ng 
            WHERE line_id = a.line_id and prd_dt = a.prd_dt and shift = a.shift and prd_seq = a.prd_seq and SUBSTRING(ng_type,1,3) = 'ROL') 
            from t_prd_daily_i a 
            inner join m_prd_line b ON b.line_id = a.line_id  
            left join m_dm_dies_asset c on c.dies_id = a.dies_id::int
            LEFT JOIN ( 
              SELECT line_id, line_ty 
              FROM m_prd_line 
              WHERE line_ty = 'DM' 
            ) g ON a.line_id = g.line_id 
            where a.prd_dt = '$today' and TO_CHAR(TO_TIMESTAMP(a.prd_dt||' '||a.time_end,'YYYY-MM-DD HH24:MI'),'HH24') = '$jam_end' AND a.stats = 'A' AND a.line_id = g.line_id ";
  //$query .= "and a.stats = 'A' ";
  $query .= "ORDER BY line_name ASC";*/
  $query = "select t.line_id, t.line_name, t.type, t.gstat, string_agg(t.dies_name,' - ') as dies_name, 
            avg(t.cctime) as cctime,  
            sum(pln_qty) as pln_qty, sum(prd_qty) as prd_qty, sum(prd_time) as prd_time, sum(ril_qty) as ril_qty, sum(rol_qty) as rol_qty 
            from (select a.line_id, a.name1 as line_name, a.line_ty as type, 
            coalesce(i.dies_id,'-') as dies_id, coalesce(CONCAT(c.group_id,' ',c.model_id,' ',c.dies_no),'-') as dies_name, c.gstat, 
            coalesce(i.cctime,0) as cctime, coalesce(i.pln_qty,0) as pln_qty, 
            coalesce(i.prd_time,0) as prd_time, coalesce(i.prd_qty,0) as prd_qty, 
            (select coalesce(sum(ng_qty),0) as ril_qty from t_prd_daily_ng 
            WHERE line_id = a.line_id and prd_dt = i.prd_dt and shift = i.shift and prd_seq = i.prd_seq and SUBSTRING(ng_type,1,3) = 'RIL'), 
            (select coalesce(sum(ng_qty),0) as rol_qty from t_prd_daily_ng 
            WHERE line_id = a.line_id and prd_dt = i.prd_dt and shift = i.shift and prd_seq = i.prd_seq and SUBSTRING(ng_type,1,3) = 'ROL') 
            from m_prd_line a 
            LEFT JOIN t_prd_daily_i i 
            ON i.line_id = a.line_id 
            AND i.prd_dt = '$today' 
            AND TO_CHAR(TO_TIMESTAMP(i.prd_dt||' '||i.time_start,'YYYY-MM-DD HH24:MI'),'HH24') = '$jam_end' 
            AND i.stats = 'A' 
            left join m_dm_dies_asset c on c.dies_id = i.dies_id::int 
            where a.line_ty = 'DM' 
            ORDER BY line_id asc) t 
            group by 1,2,3,4";
  $stmt = $conn->prepare($query);
  $return = [];
  $data_per_jam = [];
  $data_ril = [];
  $data_rol = [];
  $data_line_name = [];
  $data_eff = [];
  if ($stmt->execute()) {
    $i = 0;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $row["eff"] = 0;
      $data_ril[$i] = 0; 
      $data_rol[$i] = 0;
      if($row["prd_time"] != 0) {
        $row["eff"] = round((($row["prd_qty"] * $row["cctime"] / 60) / $row["prd_time"]) * 100, 2);
        $data_ril[$i] = round((($row["ril_qty"] * $row["cctime"] / 60) / $row["prd_time"]) * 100, 2);
        $data_rol[$i] = round((($row["rol_qty"] * $row["cctime"] / 60) / $row["prd_time"]) * 100, 2);
      }        
      $data_per_jam[] = $row;          
      
      $data_line_name[$i]["line"] = $row["line_name"];
      $data_line_name[$i]["dies"] = $row["dies_name"];
      if(empty(trim($data_line_name[$i]["dies"]))) {
        $data_line_name[$i]["dies"] = "-";
      }
      if ($row["gstat"] == 'P') {
        $data_line_name[$i]["dies_color"] = 'text-danger';
      }
      $data_eff[$i] = $row["eff"];
      $i++;
    }
  } else {
    $error = $stmt->errorInfo();
    $message[] = trim(str_replace("\n", " ", $error[2]));
  }
  if (empty($data_line_name)) {
    $query = "SELECT name1 FROM m_prd_line ORDER by line_id ASC";
    $stmt = $conn->prepare($query);
    if ($stmt->execute()) {
      $i = 0;
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $data_line_name[$i]["line"] = $row["name1"];
        $data_line_name[$i]["dies"] = "-";
        $i++;
      }
    }
  }

  //cek shift
  $shift1_s = strtotime(date("Y-m-d") . " 07:00");
  $shift1_e = strtotime(date("Y-m-d") . " 14:59");

  $shift2_s = strtotime(date("Y-m-d") . " 15:00");
  $shift2_e = strtotime(date("Y-m-d") . " 22:59");

  $shift3_s = strtotime(date("Y-m-d") . " 23:00");
  $shift3_e = strtotime(date("Y-m-d") . " 23:59");

  $shift4_s = strtotime(date("Y-m-d") . " 00:00");
  $shift4_e = strtotime(date("Y-m-d") . " 06:59");

  $current_time = strtotime(date("Y-m-d H:i"));

  $shift = "1";
  if ($current_time >= $shift1_s && $current_time <= $shift1_e) {
    $shift = "1";
  } else if ($current_time >= $shift2_s && $current_time <= $shift2_e) {
    $shift = "2";
  } else if ($current_time >= $shift3_s && $current_time <= $shift3_e) {
    $shift = "3";
  } else if ($current_time >= $shift4_s && $current_time <= $shift4_e) {
    $shift = "3";
    $today = date(strtotime($today . "- 1 days"), "Y-m-d");
  }

  $query_sum = "select line_id, line_name, avg(cctime) as cctime, sum(pln_qty) as pln_qty, sum(prd_time) as prd_time, sum(prd_qty) as prd_qty, sum(ril_qty) as ril_qty, sum(rol_qty) as rol_qty, sum(per_jam) as per_jam from ( 
    select a.line_id, b.name1 as line_name, a.cctime, a.pln_qty, a.prd_time, coalesce(a.prd_qty,0) as prd_qty, 
    (select coalesce(sum(ng_qty),0) from t_prd_daily_ng 
    WHERE line_id = a.line_id and prd_dt = a.prd_dt and shift = a.shift 
     and prd_seq = a.prd_seq and SUBSTRING(ng_type,1,3) = 'RIL') as ril_qty, 
    (select coalesce(sum(ng_qty),0) from t_prd_daily_ng 
    WHERE line_id = a.line_id and prd_dt = a.prd_dt and shift = a.shift 
     and prd_seq = a.prd_seq and SUBSTRING(ng_type,1,3) = 'ROL') as rol_qty, 
    60 as per_jam 
    from t_prd_daily_i a 
    inner join m_prd_line b ON b.line_id = a.line_id 
	  INNER JOIN m_param p ON p.pid = 'SHIFT' AND a.shift = p.seq 
    LEFT JOIN ( 
      SELECT line_id, line_ty 
      FROM m_prd_line 
      WHERE line_ty = 'DM' 
    ) g ON a.line_id = g.line_id 
    where a.prd_dt = '$today' and p.pval4 = '$shift' and a.stats = 'A' AND a.line_id = g.line_id ) t 
    group by 1,2 order by 1 asc";

  $stmt = $conn->prepare($query_sum);
  $data_eff_sum = [];
  $data_ril_sum = [];
  $data_rol_sum = [];
  $data_line_name_sum = [];
  if ($stmt->execute()) {
    $i = 0;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $eff_sum = round((($row["prd_qty"] * $row["cctime"] / 60) / $row["prd_time"]) * 100, 2);
      $data_eff_sum[$i] = $eff_sum;
      $data_ril_sum[$i] = round((($row["ril_qty"] * $row["cctime"] / 60) / $row["prd_time"]) * 100, 2);
      $data_rol_sum[$i] = round((($row["rol_qty"] * $row["cctime"] / 60) / $row["prd_time"]) * 100, 2);
      $data_line_name_sum[$i] = $row["line_name"];
      $i++;
    }
  } else {
    $error = $stmt->errorInfo();
    $message[] = trim(str_replace("\n", " ", $error[2]));
  }
  //$return["data_per_jam"] = $data_per_jam;
  $return["data_ril"] = $data_ril;
  $return["data_rol"] = $data_rol;
  $return["data_line_name"] = $data_line_name;
  $return["data_eff"] = $data_eff;

  $return["data_ril_sum"] = $data_ril_sum;
  $return["data_rol_sum"] = $data_rol_sum;
  $return["data_line_name_sum"] = $data_line_name_sum;
  $return["data_eff_sum"] = $data_eff_sum;
  $return["message"] = $message;

  echo json_encode($return);
}

if ($action == "api_dashboard_prd_single") {
  $line_id = $_REQUEST["line_id"];
  $today = date("Y-m-d");
  /*$today = "2023-01-25";*/
  $jam_now = intval(date("H"));
  $min_now = intval(date("i"));
  $line_name = "???";
  /*if($min_now > 0) {
  $jam_now += 1;
  }*/
  $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
  $query = "SELECT name1 FROM m_prd_line WHERE line_id = '$line_id'";
  $stmt = $conn->prepare($query);
  if ($stmt->execute() or die($stmt->errorInfo()[2])) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $line_name = $row["name1"];
    }
  }

  // $jam_end = str_pad($jam_now, 2, "0", STR_PAD_LEFT);
  $jam_end = date("H", strtotime('-1 hours'));
  /*$jam_end = "18";*/
  $query = "select a.line_id, b.name1 as line_name, a.cctime, a.pln_qty, a.prd_time, coalesce(a.prd_qty,0) as prd_qty, 
            (select coalesce(sum(ng_qty),0) as ril_qty from t_prd_daily_ng 
            WHERE line_id = a.line_id and prd_dt = a.prd_dt and shift = a.shift and prd_seq = a.prd_seq and SUBSTRING(ng_type,1,3) = 'RIL'), 
            (select coalesce(sum(ng_qty),0) as rol_qty from t_prd_daily_ng 
            WHERE line_id = a.line_id and prd_dt = a.prd_dt and shift = a.shift and prd_seq = a.prd_seq and SUBSTRING(ng_type,1,3) = 'ROL'),
            (select sum(b.stop_time)
            from t_prd_daily_i a
            inner join t_prd_daily_stop b on b.line_id = a.line_id and b.prd_dt = a.prd_dt and b.shift = a.shift and b.prd_seq = a.prd_seq
            inner join m_prd_stop_reason_action c on c.srna_id = b.stop_id and c.type3 = 'MESIN'
            where a.line_id = '$line_id' AND a.prd_dt = '$today' and TO_CHAR(TO_TIMESTAMP(a.prd_dt||' '||a.time_end,'YYYY-MM-DD HH24:MI'),'HH24') = '$jam_end' 
            AND a.stats = 'A' and app_id = '".APP."') as stop_mesin,
            (select sum(b.stop_time)
            from t_prd_daily_i a
            inner join t_prd_daily_stop b on b.line_id = a.line_id and b.prd_dt = a.prd_dt and b.shift = a.shift and b.prd_seq = a.prd_seq
            inner join m_prd_stop_reason_action c on c.srna_id = b.stop_id and c.type3 = 'DIES'
            where a.line_id = '$line_id' AND a.prd_dt = '$today' and TO_CHAR(TO_TIMESTAMP(a.prd_dt||' '||a.time_end,'YYYY-MM-DD HH24:MI'),'HH24') = '$jam_end' 
            AND a.stats = 'A' and app_id = '".APP."') as stop_dies
            from t_prd_daily_i a 
            inner join m_prd_line b ON b.line_id = a.line_id AND b.line_ty = 'DM'
            where a.line_id = '$line_id' AND a.prd_dt = '$today' and TO_CHAR(TO_TIMESTAMP(a.prd_dt||' '||a.time_end,'YYYY-MM-DD HH24:MI'),'HH24') = '$jam_end' AND a.stats = 'A' ";
  // echo $query;
  // die();
  $stmt = $conn->prepare($query);
  $pln_qty = 0;
  $prd_qty = 0;
  $balance = 0;
  $achieve = 0;
  $cctime = 0;
  $stop_dies = 0;
  $stop_mesin = 0;
  $eff = 0;
  $ril = 0;
  $rol = 0;

  if ($stmt->execute() or die($stmt->errorInfo()[2])) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      //$line_name = $row["line_name"];
      $pln_qty = $row["pln_qty"];
      $prd_qty = $row["prd_qty"];
      $balance = $row["pln_qty"] - $row["prd_qty"];
      $achieve = round($row["prd_qty"] / $row["pln_qty"] * 100, 2);
      $cctime = $row["cctime"];
      $stop_dies = $row["stop_dies"];
      $stop_mesin = $row["stop_mesin"];
      $eff = round((($row["prd_qty"] * $row["cctime"] / 60) / $row["prd_time"]) * 100, 2);
      $ril = round((($row["ril_qty"] * $row["cctime"] / 60) / $row["prd_time"]) * 100, 2);
      $rol = round((($row["rol_qty"] * $row["cctime"] / 60) / $row["prd_time"]) * 100, 2);
    }
  }
  $return = [];
  $return["line_name"] = $line_name;
  $return["pln_qty"] = number_format($pln_qty);
  $return["prd_qty"] = number_format($prd_qty);
  $return["balance"] = number_format($balance);
  $return["achieve"] = $achieve;
  $return["cctime"] = number_format($cctime);
  $return["stop_dies"] = number_format($stop_dies);
  $return["stop_mesin"] = number_format($stop_mesin);
  $return["eff"] = $eff;
  $return["ril"] = $ril;
  $return["rol"] = $rol;

  echo json_encode($return);
}

if ($action == "dashboard_line") {
  $template["group"] = "Dashboard";
  $template["menu"] = "Production Line";
  $lines = [];
  $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
  $line_name = "???";
  $query_line = "SELECT * FROM m_prd_line WHERE line_ty = 'DM' ORDER BY line_id asc";
  $stmt = $conn->prepare($query_line);
  if ($stmt->execute()) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $lines[] = $row;
    }
  }

  if (isset($_GET["line_id"])) {
    $line_id = $_GET["line_id"];
    foreach ($lines as $row) {
      if ($row["line_id"] == $line_id) {
        $line_name = $row["name1"];
        break;
      }
    }
  } else {
    $line_id = $lines[0]["line_id"];
    $line_name = $lines[0]["name1"];
  }


  $today = date("Y-m-d");
  //$today = "2023-01-25";
  $jam_now = intval(date("H"));
  $min_now = intval(date("i"));
  /*if($min_now > 0) {
  $jam_now += 1;
  }*/

  // $jam_end = str_pad($jam_now, 2, "0", STR_PAD_LEFT);
  $jam_end = date("H", strtotime('-1 hours'));
  //$jam_end = "18";
  $query = "select a.line_id, b.name1 as line_name, a.cctime, a.pln_qty, a.prd_time, coalesce(a.prd_qty,0) as prd_qty, 
            (select coalesce(sum(ng_qty),0) as ril_qty from t_prd_daily_ng 
            WHERE line_id = a.line_id and prd_dt = a.prd_dt and shift = a.shift and prd_seq = a.prd_seq and SUBSTRING(ng_type,1,3) = 'RIL'), 
            (select coalesce(sum(ng_qty),0) as rol_qty from t_prd_daily_ng 
            WHERE line_id = a.line_id and prd_dt = a.prd_dt and shift = a.shift and prd_seq = a.prd_seq and SUBSTRING(ng_type,1,3) = 'ROL'),
            (select sum(b.stop_time)
            from t_prd_daily_i a
            inner join t_prd_daily_stop b on b.line_id = a.line_id and b.prd_dt = a.prd_dt and b.shift = a.shift and b.prd_seq = a.prd_seq
            inner join m_prd_stop_reason_action c on c.srna_id = b.stop_id and c.type3 = 'MESIN'
            where a.line_id = '$line_id' AND a.prd_dt = '$today' and TO_CHAR(TO_TIMESTAMP(a.prd_dt||' '||a.time_end,'YYYY-MM-DD HH24:MI'),'HH24') = '$jam_end' 
            AND a.stats = 'A' and app_id = '".APP."') as stop_mesin,
            (select sum(b.stop_time)
            from t_prd_daily_i a
            inner join t_prd_daily_stop b on b.line_id = a.line_id and b.prd_dt = a.prd_dt and b.shift = a.shift and b.prd_seq = a.prd_seq
            inner join m_prd_stop_reason_action c on c.srna_id = b.stop_id and c.type3 = 'DIES'
            where a.line_id = '$line_id' AND a.prd_dt = '$today' and TO_CHAR(TO_TIMESTAMP(a.prd_dt||' '||a.time_end,'YYYY-MM-DD HH24:MI'),'HH24') = '$jam_end' 
            AND a.stats = 'A' and app_id = '".APP."') as stop_dies
            from t_prd_daily_i a 
            inner join m_prd_line b ON b.line_id = a.line_id AND b.line_ty = 'DM'
            where a.line_id = '$line_id' AND a.prd_dt = '$today' and TO_CHAR(TO_TIMESTAMP(a.prd_dt||' '||a.time_end,'YYYY-MM-DD HH24:MI'),'HH24') = '$jam_end' AND a.stats = 'A'";
  $stmt = $conn->prepare($query);
  $pln_qty = 0;
  $prd_qty = 0;
  $balance = 0;
  $achieve = 0;
  $cctime = 0;
  $stop_dies = 0;
  $stop_mesin = 0;
  $eff = 0;
  $ril = 0;
  $rol = 0;

  if ($stmt->execute() or die($stmt->errorInfo()[2])) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      //$line_name = $row["line_name"];
      $pln_qty = $row["pln_qty"];
      $prd_qty = $row["prd_qty"];
      $balance = $row["pln_qty"] - $row["prd_qty"];
      $achieve = round($row["prd_qty"] / $row["pln_qty"] * 100, 2);
      $cctime = $row["cctime"];
      $stop_dies = $row["stop_dies"];
      $stop_mesin = $row["stop_mesin"];
      $eff = round((($row["prd_qty"] * $row["cctime"] / 60) / $row["prd_time"]) * 100, 2);
      $ril = round((($row["ril_qty"] * $row["cctime"] / 60) / $row["prd_time"]) * 100, 2);
      $rol = round((($row["rol_qty"] * $row["cctime"] / 60) / $row["prd_time"]) * 100, 2);
    }
  }

  require(TEMPLATE_PATH . "/t_dashboard_line.php");
}