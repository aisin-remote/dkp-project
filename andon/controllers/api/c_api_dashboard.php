<?php
if ($action == "api_dashboard_adn") {
  $today = date("Y-m-d");
  //$today = "2023-01-26";
  $jam_now = intval(date("H"));
  $min_now = intval(date("i"));
  /*if($min_now > 0) {
  $jam_now += 1;
  }*/
  $jam_end = str_pad($jam_now, 2, "0", STR_PAD_LEFT);
  //$jam_end = "16";
  $message = [];
  $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
  /*$query = "select a.line_id, b.name1 as line_name, c.gstat, CONCAT(c.group_id,' ',c.model_id,' ',c.dies_no) as dies_name, a.cctime, a.pln_qty, a.prd_time, coalesce(a.prd_qty,0) as prd_qty, 
            (select coalesce(sum(ng_qty),0) as ril_qty from t_prd_daily_ng 
            WHERE line_id = a.line_id and prd_dt = a.prd_dt and shift = a.shift and prd_seq = a.prd_seq and SUBSTRING(ng_type,1,3) = 'RIL'), 
            (select coalesce(sum(ng_qty),0) as rol_qty from t_prd_daily_ng 
            WHERE line_id = a.line_id and prd_dt = a.prd_dt and shift = a.shift and prd_seq = a.prd_seq and SUBSTRING(ng_type,1,3) = 'ROL') 
            from t_prd_daily_i a 
            inner join m_prd_line b ON b.line_id = a.line_id AND b.line_ty = 'ECU' 
            left join m_dm_dies_asset c on c.dies_id = a.dies_id::int
            where a.prd_dt = '$today' and TO_CHAR(TO_TIMESTAMP(a.prd_dt||' '||a.time_end,'YYYY-MM-DD HH24:MI'),'HH24') = '$jam_end' AND a.stats = 'A' ";
  //$query .= "and a.stats = 'A' ";
  $query .= "ORDER BY line_name ASC";*/
  $query = "select a.line_id, a.name1 as line_name, a.line_ty as type, 
            coalesce(i.dies_id,'-') as dies_id, coalesce(m.name1,'-') as dies_name, 
            coalesce(i.cctime,0) as cctime, coalesce(i.pln_qty,0) as pln_time, 
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
            left join wms.m_mara m on m.matnr = i.dies_id 
            where a.line_ty = 'ECU' 
            ORDER BY line_id asc";
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
      if ($row["prd_time"] != 0) {
        $row["eff"] = round((($row["prd_qty"] * $row["cctime"] / 60) / $row["prd_time"]) * 100, 2);
        $data_ril[$i] = round((($row["ril_qty"] * $row["cctime"] / 60) / $row["prd_time"]) * 100, 2);
        $data_rol[$i] = round((($row["rol_qty"] * $row["cctime"] / 60) / $row["prd_time"]) * 100, 2);
      }
      $data_per_jam[] = $row;

      $data_line_name[$i]["line"] = $row["line_name"];
      $data_line_name[$i]["dies"] = $row["dies_name"];
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
  //var_dump($data_line_name);
  /*if (empty($data_line_name)) {
    $query = "SELECT * FROM m_prd_line WHERE line_ty = 'ECU' ORDER by seq ASC";
    $stmt = $conn->prepare($query);
    if ($stmt->execute()) {
      $i = 0;
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $data_line_name[$i]["line"] = $row["name1"];
        $data_line_name[$i]["dies"] = "-";
        $i++;
      }
    }
  }*/

  //cek shift
  $shift1_s = strtotime(date("Y-m-d") . " 06:00");
  $shift1_e = strtotime(date("Y-m-d") . " 13:59");

  $shift2_s = strtotime(date("Y-m-d") . " 14:00");
  $shift2_e = strtotime(date("Y-m-d") . " 21:59");

  $shift3_s = strtotime(date("Y-m-d") . " 22:00");
  $shift3_e = strtotime(date("Y-m-d") . " 23:59");

  $shift4_s = strtotime(date("Y-m-d") . " 00:00");
  $shift4_e = strtotime(date("Y-m-d") . " 05:59");

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
    $today = date("Y-m-d", strtotime($today . "- 1 days"));
  }

  $query_sum = "select line_id, line_name, sum(cctime) as cctime, sum(pln_qty) as pln_qty, sum(prd_time) as prd_time, sum(prd_qty) as prd_qty, sum(ril_qty) as ril_qty, sum(rol_qty) as rol_qty, sum(per_jam) as per_jam from ( 
    select a.line_id, b.name1 as line_name, a.cctime, a.pln_qty, a.prd_time, coalesce(a.prd_qty,0) as prd_qty, 
    (select coalesce(sum(ng_qty),0) from t_prd_daily_ng 
    WHERE line_id = a.line_id and prd_dt = a.prd_dt and shift = a.shift 
     and prd_seq = a.prd_seq and SUBSTRING(ng_type,1,3) = 'RIL') as ril_qty, 
    (select coalesce(sum(ng_qty),0) from t_prd_daily_ng 
    WHERE line_id = a.line_id and prd_dt = a.prd_dt and shift = a.shift 
     and prd_seq = a.prd_seq and SUBSTRING(ng_type,1,3) = 'ROL') as rol_qty, 
    60 as per_jam 
    from t_prd_daily_i a 
    inner join m_prd_line b ON b.line_id = a.line_id AND b.line_ty = 'ECU'
    where a.prd_dt = '$today' and a.shift = '$shift' and a.stats = 'A' ) t 
    group by 1,2 order by 1 asc";

  $stmt = $conn->prepare($query_sum);
  $data_eff_sum = [];
  $data_ril_sum = [];
  $data_rol_sum = [];
  $data_line_name_sum = [];
  if ($stmt->execute() or die(print_r($stmt->errorInfo()))) {
    $i = 0;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $eff_sum = round((($row["prd_qty"] * $row["cctime"] / $row["per_jam"]) / $row["prd_time"]) * 100, 2);
      $data_eff_sum[] = $eff_sum;
      $data_ril_sum[] = round((($row["ril_qty"] * $row["cctime"] / $row["per_jam"]) / $row["prd_time"]) * 100, 2);
      $data_rol_sum[] = round((($row["rol_qty"] * $row["cctime"] / $row["per_jam"]) / $row["prd_time"]) * 100, 2);
      $data_line_name_sum[$i] = $row["line_name"];
      $i++;
    }
  } else {
    $error = $stmt->errorInfo();
    $message[] = trim(str_replace("\n", " ", $error[2]));
  }
  /*echo $query_sum;*/
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

if ($action == "dashboard_line") {
  $template["group"] = "Dashboard";
  $template["menu"] = "Production Line";
  $lines = [];
  $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
  $line_name = "???";
  $query_line = "SELECT * FROM m_prd_line WHERE line_ty = 'ECU' ORDER BY line_id asc";
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
  $prd_dt = $today;
  //$today = "2023-01-25";
  $jam_now = intval(date("H"));
  $min_now = intval(date("i"));
  /*if($min_now > 0) {
  $jam_now += 1;
  }*/
  //cek shift
  $shift1_s = strtotime(date("Y-m-d") . " 06:00");
  $shift1_e = strtotime(date("Y-m-d") . " 13:59");

  $shift2_s = strtotime(date("Y-m-d") . " 14:00");
  $shift2_e = strtotime(date("Y-m-d") . " 21:59");

  $shift3_s = strtotime(date("Y-m-d") . " 22:00");
  $shift3_e = strtotime(date("Y-m-d") . " 23:59");

  $shift4_s = strtotime(date("Y-m-d") . " 00:00");
  $shift4_e = strtotime(date("Y-m-d") . " 05:59");

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
    $today = date( "Y-m-d", strtotime($today . " - 1 days"));
  }

  $jam_end = str_pad($jam_now, 2, "0", STR_PAD_LEFT);
  //$jam_end = "18";
  $query = "select a.prd_dt, a.line_id, a.shift, a.cctime, a.prd_seq, b.name1, b.backno from t_prd_daily_i a 
            left join wms.m_mara b ON b.matnr = a.dies_id 
            where a.line_id = '$line_id'
            AND TO_CHAR(a.prd_dt,'YYYY-MM-DD') = '$today' 
            AND TO_CHAR(TO_TIMESTAMP(a.real_dt||' '||a.time_start,'YYYY-MM-DD HH24:MI'),'HH24') = '$jam_end'";

  $stmt = $conn->prepare($query);
  $shift = "0"; //initialize shift
  $material_name = "";
  $cctime = 0;
  if ($stmt->execute() or die($stmt->errorInfo()[2] . PHP_EOL . "Full Query : " . $query)) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $shift = $row["shift"];
      $cctime = $row["cctime"];
      $material_name = $row["name1"] . "(" . $row["backno"] . ")";
      $prd_dt = $row["prd_dt"];
    }
  }
  //initialize variable
  $pln_qty = 0;
  $prd_qty = 0;
  $balance = 0;
  $achieve = 0;
  $stop_dies = 0;
  $stop_mesin = 0;
  $dandori = 0;
  $ril = 0;
  $eff = 0;
  if (!empty($shift)) {
    //dapatkan prd_seq yang pertama
    $query = "select min(a.prd_seq) as prd_seq from t_prd_daily_i a 
              where a.line_id = '$line_id' 
              AND a.prd_dt = '$prd_dt' 
              AND a.shift = '$shift' ";

    $stmt = $conn->prepare($query);
    $prd_seq = "0";
    if ($stmt->execute() or die($stmt->errorInfo()[2] . PHP_EOL . "Full Query : " . $query)) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $prd_seq = $row["prd_seq"];
      }
    }

    $query = "SELECT a.prd_dt, a.time_start FROM t_prd_daily_i a WHERE a.line_id = '$line_id' AND a.prd_dt = '$prd_dt' AND a.shift = '$shift' AND a.prd_seq = '$prd_seq' ";
    $stmt = $conn->prepare($query);
    $time_start = "";
    $prd_dt = "";
    if ($stmt->execute() or die("Query 3 " . $stmt->errorInfo()[2] . PHP_EOL . "Full Query : " . $query)) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $time_start = $row["time_start"];
        $prd_dt = $row["prd_dt"];
      }
    }

    $start_time = strtotime($prd_dt . " " . $time_start . ":00");
    $time_now = strtotime(date("Y-m-d H:i:s"));
    $diff = $time_now - $start_time;
    //get production qty
    $query = "select coalesce(sum(a.prd_qty),0) as prd_qty FROM t_prd_daily_i a 
              where a.line_id = '$line_id' 
              AND a.prd_dt = '$prd_dt'  
              AND a.shift = '$shift'";
    $stmt = $conn->prepare($query);
    $prd_qty = 0;
    if ($stmt->execute() or die($stmt->errorInfo()[2] . PHP_EOL . "Full Query : " . $query)) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $prd_qty = $row["prd_qty"];
      }
    }

    //get planned stop
    $query = "select a.prd_dt, a.stop_id, a.start_time, a.end_time, a.stop_time, b.type1, b.type2, c.real_dt from t_prd_daily_stop a 
              inner join m_prd_stop_reason_action b ON b.srna_id = a.stop_id 
              inner join t_prd_daily_i c ON c.line_id = a.line_id AND c.prd_dt = a.prd_dt AND c.shift = a.shift AND c.prd_seq = a.prd_seq 
              where a.line_id = '$line_id' and a.prd_dt = '$prd_dt' AND a.shift = '$shift' and b.type2 = 'P' and b.app_id = '" . APP . "'";

    $pengurang_detik = 0;
    $stmt = $conn->prepare($query);
    if ($stmt->execute() or die($stmt->errorInfo()[2] . PHP_EOL . "Full Query : " . $query)) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $pengurang_detik = 0;
        $str_time = strtotime($row["real_dt"] . " " . $row["start_time"] . ":00");
        $end_time = strtotime($row["real_dt"] . " " . $row["end_time"] . ":00");
        if ($current_time >= $end_time) {
          $pengurang_detik = $row["stop_time"] * 60;
        } else if ($current_time >= $str_time && $current_time < $end_time) {
          $pengurang_detik = $current_time - $str_time;
        }
        $diff -= $pengurang_detik;
      }
    }

    $pln_qty = 0;
    $pln_qty = round(($diff / floatval($cctime)), 0, PHP_ROUND_HALF_UP);

    //get NG
    $query = "select coalesce(sum(a.ng_qty),0) as ng_qty FROM t_prd_daily_ng a 
              where a.line_id = '$line_id' 
              AND a.prd_dt = '$prd_dt'  
              AND a.shift = '$shift'";
    $stmt = $conn->prepare($query);
    $ng_qty = 0;
    if ($stmt->execute() or die($stmt->errorInfo()[2] . PHP_EOL . "Full Query : " . $query)) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $ng_qty = $row["ng_qty"];
      }
    }
    $ril = round((($ng_qty * ($cctime / 60)) / ($diff / 60)) * 100, 1);
    ;
    $rol = 0;

    $balance = $pln_qty - ($prd_qty + $ng_qty);
    $achieve = round((($prd_qty + $ng_qty) / $pln_qty * 100), 1);

    //get stop part
    $query = "select coalesce(sum(b.stop_time),0) as stop_part 
              from t_prd_daily_i a
              inner join t_prd_daily_stop b on b.line_id = a.line_id and b.prd_dt = a.prd_dt and b.shift = a.shift and b.prd_seq = a.prd_seq
              inner join m_prd_stop_reason_action c on c.srna_id = b.stop_id and c.type3 = 'PART'
              where a.line_id = '$line_id' AND a.prd_dt = '$prd_dt' and a.shift = '$shift'   
              and c.app_id = '" . APP . "'";
    $stmt = $conn->prepare($query);
    $stop_dies = 0;
    if ($stmt->execute() or die($stmt->errorInfo()[2] . PHP_EOL . "Full Query : " . $query)) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $top_dies = $row["stop_part"] * 60;
      }
    }
    //get stop mesin
    $query = "select coalesce(sum(b.stop_time),0) as stop_mesin 
              from t_prd_daily_i a
              inner join t_prd_daily_stop b on b.line_id = a.line_id and b.prd_dt = a.prd_dt and b.shift = a.shift and b.prd_seq = a.prd_seq
              inner join m_prd_stop_reason_action c on c.srna_id = b.stop_id and c.type3 = 'MESIN'
              where a.line_id = '$line_id' AND a.prd_dt = '$prd_dt' and a.shift = '$shift'  
              and c.app_id = '" . APP . "'";
    $stmt = $conn->prepare($query);
    $stop_mesin = 0;
    if ($stmt->execute() or die($stmt->errorInfo()[2] . PHP_EOL . "Full Query : " . $query)) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $stop_mesin = $row["stop_mesin"] * 60;
      }
    }
    //get stop quality
    $query = "select coalesce(sum(b.stop_time),0) as stop_qas 
              from t_prd_daily_i a
              inner join t_prd_daily_stop b on b.line_id = a.line_id and b.prd_dt = a.prd_dt and b.shift = a.shift and b.prd_seq = a.prd_seq
              inner join m_prd_stop_reason_action c on c.srna_id = b.stop_id and b.stop_id = '2002'
              where a.line_id = '$line_id' AND a.prd_dt = '$prd_dt' and a.shift = '$shift'   
              and c.app_id = '" . APP . "'";
    $stmt = $conn->prepare($query);
    $stop_qas = 0;
    if ($stmt->execute() or die($stmt->errorInfo()[2] . PHP_EOL . "Full Query : " . $query)) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $stop_qas = $row["stop_qas"] * 60;
      }
    }
    //get stop dandori
    $query = "select coalesce(sum(b.stop_time),0) as stop_dandori 
              from t_prd_daily_i a
              inner join t_prd_daily_stop b on b.line_id = a.line_id and b.prd_dt = a.prd_dt and b.shift = a.shift and b.prd_seq = a.prd_seq
              inner join m_prd_stop_reason_action c on c.srna_id = b.stop_id and b.stop_id = '2005'
              where a.line_id = '$line_id' AND a.prd_dt = '$prd_dt' and a.shift = '$shift'   
              and c.app_id = '" . APP . "'";
    $stmt = $conn->prepare($query);
    $dandori = 0;
    $stop_dandori = 0;
    if ($stmt->execute() or die($stmt->errorInfo()[2] . PHP_EOL . "Full Query : " . $query)) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $stop_dandori = $row["stop_dandori"];
      }
    }
    $dandori = $stop_dandori * 60;
    $eff = round((($prd_qty * $cctime / 60) / ($diff / 60)) * 100, 1);
  } else {
    //berarti production belum dibuat
  }


  $sql_get_andon = "SELECT a.*, b.name1 as mach_name, c.\"desc\", c.bgcolor, c.text_color FROM public.m_prd_mach_btn a 
                    inner join public.m_prd_mach b ON b.line_id = a.line_id and b.mach_id = a.mach_id 
                    inner join public.m_andon_status c ON c.andon_id = a.andon_id 
                    WHERE a.line_id = '$line_id' and a.app_id = '" . APP . "' 
                    ORDER by mach_id, andon_id";

  $stmt = $conn->prepare($sql_get_andon);
  $data_andon_status = [];
  if ($stmt->execute() or die($stmt->errorInfo()[2])) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $data_andon_status[] = $row;
    }
  }
  require(TEMPLATE_PATH . "/t_dashboard_line.php");
}

if ($action == "api_dashboard_adn_single") {
  $line_id = $_REQUEST["line_id"];
  $today = date("Y-m-d");
  $prd_dt = $today;
  /*$today = "2023-01-25";*/
  $jam_now = intval(date("H"));
  $min_now = intval(date("i"));
  $line_name = "???";
  /*if($min_now > 0) {
  $jam_now += 1;
  }*/
  $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
  $query = "SELECT name1 FROM m_prd_line WHERE line_id = '$line_id' AND line_ty = 'ECU'";
  $stmt = $conn->prepare($query);
  if ($stmt->execute() or die($stmt->errorInfo()[2])) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $line_name = $row["name1"];
    }
  }
  //cek shift
  $shift1_s = strtotime(date("Y-m-d") . " 06:00");
  $shift1_e = strtotime(date("Y-m-d") . " 13:59");

  $shift2_s = strtotime(date("Y-m-d") . " 14:00");
  $shift2_e = strtotime(date("Y-m-d") . " 21:59");

  $shift3_s = strtotime(date("Y-m-d") . " 22:00");
  $shift3_e = strtotime(date("Y-m-d") . " 23:59");

  $shift4_s = strtotime(date("Y-m-d") . " 00:00");
  $shift4_e = strtotime(date("Y-m-d") . " 05:59");

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
    $today = date( "Y-m-d", strtotime($today . " - 1 days"));
  }
  $jam_end = str_pad($jam_now, 2, "0", STR_PAD_LEFT);

  $query = "select a.prd_dt, a.line_id, a.shift, a.cctime, a.prd_seq, b.name1, b.backno from t_prd_daily_i a 
            left join wms.m_mara b ON b.matnr = a.dies_id 
            where a.line_id = '$line_id'
            AND TO_CHAR(a.prd_dt,'YYYY-MM-DD') = '$today' 
            AND TO_CHAR(TO_TIMESTAMP(a.real_dt||' '||a.time_start,'YYYY-MM-DD HH24:MI'),'HH24') = '$jam_end'";
  $stmt = $conn->prepare($query);
  $shift = "0"; //initialize shift
  $material_name = "";
  $cctime = 0;
  if ($stmt->execute() or die($stmt->errorInfo()[2])) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $shift = $row["shift"];
      $cctime = $row["cctime"];
      $material_name = $row["name1"] . " (" . $row["backno"] . ")";
      $prd_dt = $row["prd_dt"];
    }
  }
  //initialize variable
  $pln_qty = 0;
  $prd_qty = 0;
  $balance = 0;
  $achieve = 0;
  $stop_dies = 0;
  $stop_mesin = 0;
  $dandori = 0;
  $ril = 0;
  $eff = 0;
  if (!empty($shift)) {
    //dapatkan prd_seq yang pertama
    $query = "select min(a.prd_seq) as prd_seq from t_prd_daily_i a 
              where a.line_id = '$line_id' 
              AND a.prd_dt = '$today' 
              AND a.shift = '$shift'";
    $stmt = $conn->prepare($query);
    $prd_seq = "0";
    if ($stmt->execute() or die($stmt->errorInfo()[2])) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $prd_seq = $row["prd_seq"];
      }
    }

    $query = "SELECT a.prd_dt, a.time_start FROM t_prd_daily_i a WHERE a.line_id = '$line_id' AND a.prd_dt = '$today' AND a.shift = '$shift' AND a.prd_seq = '$prd_seq' ";
    $stmt = $conn->prepare($query);
    $time_start = "";
    $prd_dt = "";
    if ($stmt->execute() or die($stmt->errorInfo()[2])) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $time_start = $row["time_start"];
        $prd_dt = $row["prd_dt"];
      }
    }

    $start_time = strtotime($prd_dt . " " . $time_start . ":00");
    $time_now = strtotime(date("Y-m-d H:i:s"));
    $diff = $time_now - $start_time;
    //get production qty
    $query = "select coalesce(sum(a.prd_qty),0) as prd_qty FROM t_prd_daily_i a 
              where a.line_id = '$line_id' 
              AND a.prd_dt = '$prd_dt'  
              AND a.shift = '$shift'";
    $stmt = $conn->prepare($query);
    $prd_qty = 0;
    if ($stmt->execute() or die($stmt->errorInfo()[2])) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $prd_qty = $row["prd_qty"];
      }
    }
    //get planned stop
    $query = "select a.prd_dt, a.stop_id, a.start_time, a.end_time, a.stop_time, b.type1, b.type2, c.real_dt from t_prd_daily_stop a 
              inner join m_prd_stop_reason_action b ON b.srna_id = a.stop_id 
              inner join t_prd_daily_i c ON c.line_id = a.line_id AND c.prd_dt = a.prd_dt AND c.shift = a.shift AND c.prd_seq = a.prd_seq 
              where a.line_id = '$line_id' and a.prd_dt = '$prd_dt' AND a.shift = '$shift' and b.type2 = 'P' and b.app_id = '" . APP . "'";

    $pengurang_detik = 0;
    $total_pengurang = 0;
    $stmt = $conn->prepare($query);
    if ($stmt->execute() or die($stmt->errorInfo()[2] . PHP_EOL . "Full Query : " . $query)) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $pengurang_detik = 0;
        $str_time = strtotime($row["real_dt"] . " " . $row["start_time"] . ":00");
        $end_time = strtotime($row["real_dt"] . " " . $row["end_time"] . ":00");
        if ($current_time >= $end_time) {
          $pengurang_detik = $row["stop_time"] * 60;
        } else if ($current_time >= $str_time && $current_time < $end_time) {
          $pengurang_detik = $current_time - $str_time;
        }
        $total_pengurang += $pengurang_detik;
        $diff -= $pengurang_detik;
      }
    }

    $pln_qty = 0;
    $pln_qty = round(($diff / floatval($cctime)), 0, PHP_ROUND_HALF_UP);

    //get NG
    $query = "select coalesce(sum(a.ng_qty),0) as ng_qty FROM t_prd_daily_ng a 
              where a.line_id = '$line_id' 
              AND a.prd_dt = '$prd_dt'  
              AND a.shift = '$shift'";
    $stmt = $conn->prepare($query);
    $ng_qty = 0;
    if ($stmt->execute() or die($stmt->errorInfo()[2])) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $ng_qty = $row["ng_qty"];
      }
    }
    $ril = round((($ng_qty * ($cctime / 60)) / ($diff / 60)) * 100, 1);
    ;
    $rol = 0;

    $balance = $pln_qty - ($prd_qty + $ng_qty);
    $achieve = round((($prd_qty + $ng_qty) / $pln_qty * 100), 1);

    //get stop part
    $query = "select coalesce(sum(b.stop_time),0) as stop_part 
              from t_prd_daily_i a
              inner join t_prd_daily_stop b on b.line_id = a.line_id and b.prd_dt = a.prd_dt and b.shift = a.shift and b.prd_seq = a.prd_seq
              inner join m_prd_stop_reason_action c on c.srna_id = b.stop_id and c.type3 = 'PART'
              where a.line_id = '$line_id' AND a.prd_dt = '$prd_dt' and a.shift = '$shift'   
              and c.app_id = '" . APP . "'";
    $stmt = $conn->prepare($query);
    $stop_dies = 0;
    if ($stmt->execute() or die($stmt->errorInfo()[2])) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $top_dies = $row["stop_part"] * 60;
      }
    }
    //get stop mesin
    $query = "select coalesce(sum(b.stop_time),0) as stop_mesin 
              from t_prd_daily_i a
              inner join t_prd_daily_stop b on b.line_id = a.line_id and b.prd_dt = a.prd_dt and b.shift = a.shift and b.prd_seq = a.prd_seq
              inner join m_prd_stop_reason_action c on c.srna_id = b.stop_id and c.type3 = 'MESIN'
              where a.line_id = '$line_id' AND a.prd_dt = '$prd_dt' and a.shift = '$shift'  
              and c.app_id = '" . APP . "'";
    $stmt = $conn->prepare($query);
    $stop_mesin = 0;
    if ($stmt->execute() or die($stmt->errorInfo()[2])) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $stop_mesin = $row["stop_mesin"] * 60;
      }
    }
    //get stop quality
    $query = "select coalesce(sum(b.stop_time),0) as stop_qas 
              from t_prd_daily_i a
              inner join t_prd_daily_stop b on b.line_id = a.line_id and b.prd_dt = a.prd_dt and b.shift = a.shift and b.prd_seq = a.prd_seq
              inner join m_prd_stop_reason_action c on c.srna_id = b.stop_id and b.stop_id = '2002'
              where a.line_id = '$line_id' AND a.prd_dt = '$prd_dt' and a.shift = '$shift'   
              and c.app_id = '" . APP . "'";
    $stmt = $conn->prepare($query);
    $stop_qas = 0;
    if ($stmt->execute() or die($stmt->errorInfo()[2])) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $stop_qas = $row["stop_qas"] * 60;
      }
    }
    //get stop dandori
    $query = "select coalesce(sum(b.stop_time),0) as stop_dandori 
              from t_prd_daily_i a
              inner join t_prd_daily_stop b on b.line_id = a.line_id and b.prd_dt = a.prd_dt and b.shift = a.shift and b.prd_seq = a.prd_seq
              inner join m_prd_stop_reason_action c on c.srna_id = b.stop_id and b.stop_id = '2005'
              where a.line_id = '$line_id' AND a.prd_dt = '$prd_dt' and a.shift = '$shift'   
              and c.app_id = '" . APP . "'";
    $stmt = $conn->prepare($query);
    $dandori = 0;
    $stop_dandori = 0;
    if ($stmt->execute() or die($stmt->errorInfo()[2])) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $stop_dandori = $row["stop_dandori"];
      }
    }
    $dandori = $stop_dandori * 60;
    $eff = round((($prd_qty * $cctime / 60) / ($diff / 60)) * 100, 1);
  }
  $query_dt = "select TO_CHAR(current_timestamp, 'Day, DD-MM-YYYY') as date, TO_CHAR(current_timestamp, 'HH24.MI AM') as time";
  $stmt = $conn->prepare($query_dt);
  if ($stmt->execute()) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $date = $row["date"];
      $time = $row["time"];
    }
  }
  $return = [];
  $return["line_name"] = $line_name;
  $return["material_name"] = $material_name;
  $return["pln_qty"] = number_format($pln_qty);
  $return["prd_qty"] = number_format($prd_qty);
  $return["balance"] = number_format($balance);
  $return["achieve"] = $achieve;
  $return["cctime"] = number_format($cctime);
  $return["stop_dies"] = number_format($stop_dies);
  $return["stop_mesin"] = number_format($stop_mesin);
  $return["stop_qas"] = number_format($stop_qas);
  $return["eff"] = $eff;
  $conn->exec("UPDATE m_prd_line SET eff = '$eff' WHERE line_id = '$line_id'");
  $return["ril"] = $ril;
  $return["rol"] = $rol;
  $return["dandori"] = $dandori;
  $return["date"] = $date;
  $return["time"] = $time;

  echo json_encode($return);
}

if ($action == "api_get_andon_status") {
  $line_id = $_GET["line_id"];
  $sql = "SELECT a.*, TO_CHAR(a.start_time,'YYYY-MM-DD HH24:MI:SS') as start_timex, b.name1 as mach_name, c.\"desc\" as andon_desc, c.bgcolor, c.text_color 
          FROM public.t_stop_time a 
          INNER JOIN public.m_prd_mach b ON b.line_id = a.line_id and b.mach_id = a.mach_id 
          INNER JOIN public.m_andon_status c ON c.andon_id = a.andon_id 
          WHERE a.line_id = '$line_id' AND a.status IN ('N','P')";
  $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
  $stmt = $conn->prepare($sql);
  $result = [];
  $data = [];
  if ($stmt->execute() or die(json_encode($stmt->errorInfo()))) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $start_date = new DateTime($row["start_timex"]);
      $since_start = $start_date->diff(new DateTime(date("Y-m-d H:i:s")));
      $diff = $since_start->h . ":" . $since_start->i . ":" . $since_start->s;
      $row["diff"] = $diff;
      $data[] = $row;
    }
  }
  if (!empty($data)) {
    $result["status"] = true;
    $result["data"] = $data;
    $data = null;
  } else {
    $result["status"] = false;
    $result["message"] = "No Data Found";
  }
  echo json_encode($result);
}