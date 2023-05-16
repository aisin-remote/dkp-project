<?php
if ($action == "home") {
  $template["group"] = "Home";
  $template["menu"] = "Dashboard";
  //$production = new Production();
  $today = date("Y-m-d");
  $jam_now = intval(date("H"));
  if($jam_now >= 0 && $jam_now <= 6) {
    $today = date("Y-m-d",strtotime($today." -1 days"));
  }
  $min_now = intval(date("i"));
  // $now = date("Y-m-d H:i:s");
  $jam_end = date("H", strtotime('-1 hours'));
  // echo $jam_end;
  // die();
  /*if($min_now > 0) {
  $jam_now += 1;
  }*/
  // $jam_end = str_pad($jam_now, 2, "0", STR_PAD_LEFT);
  $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
  /*$query = "select a.line_id, b.name1 as line_name, a.dies_id, c.gstat, CONCAT(c.group_id,' ',c.model_id,' ',c.dies_no) as dies_name, a.cctime, a.pln_qty, a.prd_time, coalesce(a.prd_qty,0) as prd_qty, 
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
            where a.prd_dt = '$today' and TO_CHAR(TO_TIMESTAMP(a.prd_dt||' '||a.time_end,'YYYY-MM-DD HH24:MI'),'HH24') = '$jam_end' and a.stats = 'A' AND a.line_id = g.line_id ";
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
  // echo $query;
  // die();
  $stmt = $conn->prepare($query);
  $data_per_jam = [];
  $data_ril = [];
  $data_rol = [];
  $data_line_name = [];
  $data_eff = [];
  $i = 0;
  if ($stmt->execute()) {
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
      if ($row["gstat"] == 'P') {
        $data_line_name[$i]["dies_color"] = 'text-danger';
      }
      $data_eff[$i] = $row["eff"];
      $i++;
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

  $shift = "0";
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

  if (empty($data_line_name)) {
    $query = "SELECT name1 FROM m_prd_line WHERE line_ty = 'DM' ORDER by line_id ASC";
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
      $data_line_name_sum[$i]["line"] = $row["line_name"];
      $i++;
    }
  }
  require(TEMPLATE_PATH . "/t_home.php");
}