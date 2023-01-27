<?php 
if($action == "api_dashboard_prd") {
  $today = date("Y-m-d");
  $jam_now = intval(date("H"));
  $min_now = intval(date("i"));
  /*if($min_now > 0) {
    $jam_now += 1;
  }*/
  $jam_end = str_pad($jam_now, 2, "0", STR_PAD_LEFT);
  $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
  $query = "select a.line_id, b.name1 as line_name, a.cctime, a.pln_qty, a.prd_time, coalesce(a.prd_qty,0) as prd_qty, 
            (select coalesce(sum(ng_qty),0) as ril_qty from t_prd_daily_ng 
            WHERE line_id = a.line_id and prd_dt = a.prd_dt and shift = a.shift and prd_seq = a.prd_seq and SUBSTRING(ng_type,1,3) = 'RIL'), 
            (select coalesce(sum(ng_qty),0) as rol_qty from t_prd_daily_ng 
            WHERE line_id = a.line_id and prd_dt = a.prd_dt and shift = a.shift and prd_seq = a.prd_seq and SUBSTRING(ng_type,1,3) = 'ROL') 
            from t_prd_daily_i a 
            inner join m_prd_line b ON b.line_id = a.line_id 
            where a.prd_dt = '$today' and TO_CHAR(TO_TIMESTAMP(a.prd_dt||' '||a.time_end,'YYYY-MM-DD HH24:MI'),'HH24') = '$jam_end' ";
  //$query .= "and a.stats = 'A' ";
  $query .= "ORDER BY line_name ASC";
  $stmt = $conn->prepare($query);
  $return = [];
  $data_per_jam = [];
  $data_ril = [];
  $data_rol = [];
  $data_line_name = [];
  $data_eff = [];
  if($stmt->execute()) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {      
      $row["eff"] = round((($row["prd_qty"] * $row["cctime"] / 60 ) / $row["prd_time"]) * 100,2);      
      //$data_per_jam[] = $row;
      $data_ril[] = round((($row["ril_qty"] * $row["cctime"] / 60) / $row["prd_time"] ) * 100,2);
      $data_rol[] = round((($row["rol_qty"] * $row["cctime"] / 60) / $row["prd_time"] ) * 100,2);
      //$data_line_name[] = $row["line_name"];
      $data_eff[] = $row["eff"];
    }
  }
  //$return["data_per_jam"] = $data_per_jam;
  $return["data_ril"] = $data_ril;
  $return["data_rol"] = $data_rol;
  //$return["data_line_name"] = $data_line_name;
  $return["data_eff"] = $data_eff;
  
  echo json_encode($return);
}

if($action == "api_dashboard_prd_single") {
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
  if($stmt->execute() or die($stmt->errorInfo()[2])) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
      $line_name = $row["name1"];
    }
  }
  
  $jam_end = str_pad($jam_now, 2, "0", STR_PAD_LEFT);
  /*$jam_end = "18";*/
  $query = "select a.line_id, b.name1 as line_name, a.cctime, a.pln_qty, a.prd_time, coalesce(a.prd_qty,0) as prd_qty, 
            (select coalesce(sum(ng_qty),0) as ril_qty from t_prd_daily_ng 
            WHERE line_id = a.line_id and prd_dt = a.prd_dt and shift = a.shift and prd_seq = a.prd_seq and SUBSTRING(ng_type,1,3) = 'RIL'), 
            (select coalesce(sum(ng_qty),0) as rol_qty from t_prd_daily_ng 
            WHERE line_id = a.line_id and prd_dt = a.prd_dt and shift = a.shift and prd_seq = a.prd_seq and SUBSTRING(ng_type,1,3) = 'ROL') 
            from t_prd_daily_i a 
            inner join m_prd_line b ON b.line_id = a.line_id 
            where a.line_id = '$line_id' AND a.prd_dt = '$today' and TO_CHAR(TO_TIMESTAMP(a.prd_dt||' '||a.time_end,'YYYY-MM-DD HH24:MI'),'HH24') = '$jam_end'";
  $stmt = $conn->prepare($query);
  $eff = 0;
  $ril = 0;
  $rol = 0;
  
  if($stmt->execute() or die($stmt->errorInfo()[2])) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
      //$line_name = $row["line_name"];
      $eff = round((($row["prd_qty"] * $row["cctime"] / 60 ) / $row["prd_time"]) * 100,2); 
      $ril = round((($row["ril_qty"] * $row["cctime"] / 60) / $row["prd_time"] ) * 100,2);
      $rol = round((($row["rol_qty"] * $row["cctime"] / 60) / $row["prd_time"] ) * 100,2);
    }
  }
  $return = [];
  $return["line_name"] = $line_name;
  $return["eff"] = $eff;
  $return["ril"] = $ril;
  $return["rol"] = $rol;
  
  echo json_encode($return);
}

if($action == "dashboard_line") {
  $template["group"] = "Dashboard";
  $template["menu"] = "Production Line";
  $lines = [];
  $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
  $line_name = "???";
  $query_line = "SELECT * FROM m_prd_line ORDER BY line_id asc";
  $stmt = $conn->prepare($query_line);
  if($stmt->execute()) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {      
      $lines[] = $row;
    }
  }
  
  if(isset($_GET["line_id"])) {
    $line_id = $_GET["line_id"];
    foreach($lines as $row) {
      if($row["line_id"] == $line_id) {
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
  
  $jam_end = str_pad($jam_now, 2, "0", STR_PAD_LEFT);
  //$jam_end = "18";
  $query = "select a.line_id, b.name1 as line_name, a.cctime, a.pln_qty, a.prd_time, coalesce(a.prd_qty,0) as prd_qty, 
            (select coalesce(sum(ng_qty),0) as ril_qty from t_prd_daily_ng 
            WHERE line_id = a.line_id and prd_dt = a.prd_dt and shift = a.shift and prd_seq = a.prd_seq and SUBSTRING(ng_type,1,3) = 'RIL'), 
            (select coalesce(sum(ng_qty),0) as rol_qty from t_prd_daily_ng 
            WHERE line_id = a.line_id and prd_dt = a.prd_dt and shift = a.shift and prd_seq = a.prd_seq and SUBSTRING(ng_type,1,3) = 'ROL') 
            from t_prd_daily_i a 
            inner join m_prd_line b ON b.line_id = a.line_id 
            where a.line_id = '$line_id' AND a.prd_dt = '$today' and TO_CHAR(TO_TIMESTAMP(a.prd_dt||' '||a.time_end,'YYYY-MM-DD HH24:MI'),'HH24') = '$jam_end'";
  $stmt = $conn->prepare($query);
  $eff = 0;
  $ril = 0;
  $rol = 0;
  
  if($stmt->execute() or die($stmt->errorInfo()[2])) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
      //$line_name = $row["line_name"];
      $eff = round((($row["prd_qty"] * $row["cctime"] / 60 ) / $row["prd_time"]) * 100,2); 
      $ril = round((($row["ril_qty"] * $row["cctime"] / 60) / $row["prd_time"] ) * 100,2);
      $rol = round((($row["rol_qty"] * $row["cctime"] / 60) / $row["prd_time"] ) * 100,2);
    }
  }
  
  require( TEMPLATE_PATH . "/t_dashboard_line.php" );
}