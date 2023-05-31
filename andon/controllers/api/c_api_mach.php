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

  // if (isset($_GET["line_id"])) {
  // $query = "SELECT line_st from m_prd_line where line_id = '$line_id' ";
  // $stmt = $conn->prepare($query);
  // if ($stmt->execute()) {
  //   while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  //     $line_st = $row["line_st"];
  //   }
  // }
  // // echo $line_st;
  // // die();
  // if ($line_st == 5) {
  //   $conn->exec("UPDATE m_prd_mach SET stats = 5 WHERE line_id = '$line_id' ");
  // } 
  // else {
  //   $conn->exec("UPDATE m_prd_mach SET stats = 0 WHERE line_id = '$line_id' ");
  // }
  // }

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
  // $query = "SELECT a.*, c.pval1 from t_prd_daily_h a
  //           inner join t_prd_daily_i b ON b.line_id = a.line_id and b.prd_dt = a.prd_dt and b.shift = a.shift
  //           inner join m_param c ON a.shift = c.seq
  //           where c.pid = 'SHIFT' AND a.line_id = '$line_id' AND a.prd_dt = '$now' AND TO_CHAR(TO_TIMESTAMP(b.real_dt||' '||b.time_start,'YYYY-MM-DD HH24:MI'),'HH24') = '$jam_end' ";
  $query = "SELECT a.*, b.* from t_prd_daily_i a
            inner join m_param b on a.shift = b.seq and b.pid = 'SHIFT'
            WHERE a.line_id = '$line_id' 
            AND TO_CHAR(a.real_dt,'YYYY-MM-DD') = '$now'  
            AND TO_CHAR(TO_TIMESTAMP(a.real_dt||' '||a.time_start,'YYYY-MM-DD HH24:MI'),'HH24') = '$jam_end' ";
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
                WHERE a.line_id = '$line_id'
                ORDER BY mach_id ASC ";
  $stmt = $conn->prepare($query_line);
  if ($stmt->execute()) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $header["mach"][] = $row;
    }
  }

  // if (isset($_REQUEST["mach"])) {
  //   foreach ($lines as $row) {
  //     $i = 0;
  //     foreach ($mach as $url) {
  //       if ($row["mach_id"] == $url) {
  //         $header["mach"][$i]["machid"] = $row["mach_id"];
  //         $header["mach"][$i]["machname"] = $row["mach_name"];
  //         $header["mach"][$i]["lineid"] = $row["line_id"];
  //         $header["mach"][$i]["linename"] = $row["line_name"];
  //         $header["mach"][$i]["andonid"] = $row["andon_id"];
  //         $header["mach"][$i]["bgcolor"] = $row["bgcolor"];
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

  // if (isset($_GET["line_id"])) {
  // $query = "SELECT line_st from m_prd_line where line_id = '$line_id' ";
  // $stmt = $conn->prepare($query);
  // if ($stmt->execute()) {
  //   while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  //     $line_st = $row["line_st"];
  //   }
  // }
  // // echo $line_st;
  // // die();
  // if ($line_st == 5) {
  //   $conn->exec("UPDATE m_prd_mach SET stats = 5 WHERE line_id = '$line_id' ");
  // } else {
  //   $conn->exec("UPDATE m_prd_mach SET stats = 0 WHERE line_id = '$line_id' ");
  // }
  // }

  $query = "SELECT * FROM m_prd_mach_btn ORDER BY mach_id, line_id, andon_id ASC ";
  $stmt = $conn->prepare($query);
  $btns = [];
  if ($stmt->execute()) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $header["btn"][] = $row;
    }
  }
  $query = "select buzzred, buzzgreen, buzzyellow, buzzjp from m_prd_line where line_id = '$line_id' ";
  $stmt = $conn->prepare($query);
  if ($stmt->execute()) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $header["buzz"] = $row;
    }
  }
  $header["status"] = $line->getListStatus();
  echo json_encode($header);
}

if ($action == "api_update_stats") {
  $mach_id = $_REQUEST["mach_id"];
  $andon_id = $_REQUEST["andon_id"];
  $isBtnOn = $_REQUEST["btn_on"];
  $line_id = $_REQUEST["line_id"];

  $return = array();
  $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

  // off scw
  // if ($andon_id == 4 && $isBtnOn == 1) {
  //   $conn->exec("UPDATE m_prd_mach_btn SET btn_sts = 0
  //           WHERE line_id = '$line_id' AND andon_id = 8 ");
  // }
  // off andon id 4 if andon id 1,2,3 is on
  // if (($andon_id == 1 || $andon_id == 2 || $andon_id == 3) && $isBtnOn == 1) {
  //   $conn->exec("UPDATE m_prd_mach_btn SET btn_sts = 0
  //           WHERE mach_id = '$mach_id' AND andon_id = 4 ");
  // }
  if ($isBtnOn == 1) {
    $conn->exec("UPDATE m_prd_mach SET stats = $andon_id
            WHERE mach_id = '$mach_id' ");
  } 
  if ($isBtnOn == 0) {
    $conn->exec("UPDATE m_prd_mach set stats = (select andon_id from m_prd_mach_btn where mach_id = '$mach_id' AND btn_sts = '1' order by andon_id desc limit 1 )
      where mach_id = '$mach_id' ");
  }

  $query = "UPDATE m_prd_mach_btn SET btn_sts = $isBtnOn
            WHERE mach_id = '$mach_id' AND andon_id = $andon_id ";
  // echo $sum;
  // die();
  $stmt = $conn->prepare($query);
  if ($stmt->execute()) {
    $sum = "SELECT sum(a.btn_sts) as cnt FROM m_prd_mach_btn a WHERE a.line_id = '$line_id' ";
    $stmt = $conn->prepare($sum);
    if ($stmt->execute()) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $return["sum"] = $row["cnt"];
      }
    }

    if ($return["sum"] == 0) {
      $conn->exec("UPDATE m_prd_line SET line_st = 5 WHERE line_id = '$line_id' ");
    }

    $sum1 = "SELECT sum(a.btn_sts) as cnt FROM m_prd_mach_btn a WHERE a.mach_id = '$mach_id' ";
    $stmt1 = $conn->prepare($sum1);
    if ($stmt1->execute()) {
      while ($row = $stmt1->fetch(PDO::FETCH_ASSOC)) {
        $return["sum1"] = $row["cnt"];
      }
    }

    if ($return["sum1"] == 0) {
      $conn->exec("UPDATE m_prd_mach SET stats = 5 WHERE mach_id = '$mach_id' ");
    }
    // else {
    //   $conn->exec("UPDATE m_prd_line SET line_st = $andon_id WHERE line_id = '$line_id' ");
    // }

    $return["status"] = true;
  } else {
    $return["status"] = false;
    $return["message"] = trim(str_replace("\n", " ", $error[2]));
    error_log($error[2]);
  }

  echo json_encode($return);
}

?>