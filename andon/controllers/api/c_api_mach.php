<?php
if ($action == "mach_stats") {
  $line_id = $_REQUEST["line_id"];
  $line = new Line();
  $lines = [];
  $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
  $result = array();
  $query_line = "SELECT a.*, a.name1 as mach_name, b.*, b.name1 as line_name, c.* from m_prd_mach a
                left JOIN m_prd_line b ON b.line_id = a.line_id
                left JOIN m_andon_status c on c.andon_id = a.stats
                where a.line_id = '$line_id'
                ORDER BY mach_id ASC ";
  $stmt = $conn->prepare($query_line);
  if ($stmt->execute()) {
    $i = 0;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $result[$i]["machid"] = $row["mach_id"];
      $result[$i]["machname"] = $row["mach_name"];
      $result[$i]["lineid"] = $row["line_id"];
      $result[$i]["linename"] = $row["line_name"];
      $result[$i]["andonid"] = $row["andon_id"];
      $result[$i]["bgcolor"] = $row["bgcolor"];

      $i++;
    }
  }

  // if (isset($_GET["mach"])) {
  //   $mach = $_GET["mach"];
  //   foreach ($lines as $row) {
  //     $i = 0;
  //     foreach ($mach as $url) {
  //       if ($row["mach_id"] == $url) {
  //         $result[$i]["machid"] = $row["mach_id"];
  //         $result[$i]["machname"] = $row["mach_name"];
  //         $result[$i]["lineid"] = $row["line_id"];
  //         $result[$i]["linename"] = $row["line_name"];
  //         $result[$i]["andonid"] = $row["andon_id"];
  //         $result[$i]["bgcolor"] = $row["bgcolor"];
  //       }
  //       $i++;
  //     }
  //   }
  // } else {
  //   $line_id = $lines[0]["line_id"];
  //   $line_name = $lines[0]["line_name"];
  //   $mach_id = $lines[0]["mach_id"];
  //   $mach_name = $lines[0]["mach_name"];
  //   $bgcolor = $lines[0]["bgcolor"];
  // }

  $query = "SELECT * FROM m_prd_mach_btn ORDER BY mach_id, line_id, andon_id ASC ";
  $stmt = $conn->prepare($query);
  $btns = [];
  if ($stmt->execute()) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $btns[] = $row;
    }
  }

  $ng = [];
  $query = "SELECT * FROM m_prd_ng_type WHERE app_id = 'AISIN_ADN' ";
  $stmt = $conn->prepare($query);
  if ($stmt->execute()) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $ng[] = $row;
    }
  }

  $status = $line->getListStatus();
  require(TEMPLATE_PATH . "/t_line_status.php");
}

if ($action == "api_mach_status") {
  $line = new Line();
  $line_id = $_REQUEST["line_id"];
  $shift = $_REQUEST["shift"];

  $now = date("Y-m-d");
  $jam_now = intval(date("H"));
  $min_now = intval(date("i"));
  $jam_end = str_pad($jam_now, 2, "0", STR_PAD_LEFT);

  $header = array();
  $ng = array();
  $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
  $query_ng = "";
  // $query = "SELECT * from t_prd_daily_h
  //           where line_id = '$line_id' and prd_dt = '$now' and shift = '$shift' ";
  $query = "SELECT a.*, c.pval1 from t_prd_daily_h a
            inner join t_prd_daily_i b ON b.line_id = a.line_id and b.prd_dt = a.prd_dt and b.shift = a.shift
            inner join m_param c ON a.shift = c.seq
            where c.pid = 'SHIFT' AND a.line_id = '$line_id' AND a.prd_dt = '$now' AND TO_CHAR(TO_TIMESTAMP(b.prd_dt||' '||b.time_start,'YYYY-MM-DD HH24:MI'),'HH24') = '$jam_end' ";
  $stmt = $conn->prepare($query);
  $header["head"] = [];
  if ($stmt->execute() or die($stmt->errorInfo()[2])) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $header["head"] = $row;
    }
  }
  $query_line = "SELECT a.*, a.name1 as mach_name, b.*, b.name1 as line_name, c.* from m_prd_mach a
                left JOIN m_prd_line b ON b.line_id = a.line_id
                left JOIN m_andon_status c on c.andon_id = a.stats
                ORDER BY mach_id ASC ";
  $stmt = $conn->prepare($query_line);
  if ($stmt->execute()) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $lines[] = $row;
    }
  }

  if (isset($_REQUEST["mach"])) {
    $mach = $_REQUEST["mach"];
    foreach ($lines as $row) {
      $i = 0;
      foreach ($mach as $url) {
        if ($row["mach_id"] == $url) {
          $header["mach"][$i]["machid"] = $row["mach_id"];
          $header["mach"][$i]["machname"] = $row["mach_name"];
          $header["mach"][$i]["lineid"] = $row["line_id"];
          $header["mach"][$i]["linename"] = $row["line_name"];
          $header["mach"][$i]["andonid"] = $row["andon_id"];
          $header["mach"][$i]["bgcolor"] = $row["bgcolor"];
        }
        $i++;
      }
    }
  } else {
    $line_id = $lines[0]["line_id"];
    $line_name = $lines[0]["line_name"];
    $mach_id = $lines[0]["mach_id"];
    $mach_name = $lines[0]["mach_name"];
    $bgcolor = $lines[0]["bgcolor"];
  }

  $query = "SELECT * FROM m_prd_mach_btn ORDER BY mach_id, line_id, andon_id ASC ";
  $stmt = $conn->prepare($query);
  $btns = [];
  if ($stmt->execute()) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $header["btn"][] = $row;
    }
  }
  $header["status"] = $line->getListStatus();
  echo json_encode($header);
}

if ($action == "api_update_stats") {
  $mach_id = $_REQUEST["mach_id"];
  $andon_id = $_REQUEST["andon_id"];
  $isBtnOn = $_REQUEST["btn_on"];

  $return = array();
  $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
  if (($andon_id == 1 || $andon_id == 2 || $andon_id == 3) && $isBtnOn == 1) {
    $conn->exec("UPDATE m_prd_mach_btn SET btn_sts = 0
            WHERE mach_id = '$mach_id' AND andon_id = 4 ");
  }
  $query = "UPDATE m_prd_mach_btn SET btn_sts = $isBtnOn
            WHERE mach_id = '$mach_id' AND andon_id = $andon_id ";
  // echo $query;
  // die();
  $stmt = $conn->prepare($query);
  if ($stmt->execute() or die($stmt->errorInfo()[2])) {
    $return["status"] = true;
  } else {
    $return["status"] = false;
    $return["message"] = trim(str_replace("\n", " ", $error[2]));
    error_log($error[2]);
  }

  echo json_encode($return);
}

?>