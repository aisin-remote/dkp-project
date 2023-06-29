<?php
if ($action == "api_dashboard_pooling") {
  $return = [];
  $cLdList = new LoadingList();
  $conn_sql_srv = new PDO(SQLSRV_DSN, SQLSRV_USERNAME, SQLSRV_PASSWORD);
  $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
  $sql = "SELECT pval1 FROM m_param WHERE pid = 'PL_LEADTIME' and seq = '1'";
  $stmt = $conn->prepare($sql);
  $lead_time = 1;
  if ($stmt->execute() or die($sql)) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $lead_time = floatval($row["pval1"]);
    }
  }
  $return["lead_time"] = $lead_time;
  $tanggal = date("Y-m-d");
  $today = date("Ymd");
  $next_day = date("Ymd", strtotime(date("Y-m-d") . " +1 day"));
  if (isset($_REQUEST["tanggal"])) {
    $tanggal = $_REQUEST["tanggal"];
    $today = date("Ymd", strtotime(date($_REQUEST["tanggal"])));
    $next_day = date("Ymd", strtotime(date($_REQUEST["tanggal"]) . " +1 day"));
  }

  $shift = "1";

  $current_time = floatval(date("Hi"));
  if ($current_time >= 600 && $current_time <= 1359) {
    $shift = "1";
  } else if ($current_time >= 1400 && $current_time <= 2159) {
    $shift = "2";
  } else {
    $shift = "3";
  }

  if (isset($_REQUEST["shift"])) {
    $shift = $_REQUEST["shift"];
  }

  $time_start = "060000";
  $time_end = "145900";
  if ($shift == "1") {
    $next_day = $today;
    $time_start = "060000";
    $time_end = "135959";
  } else if ($shift == "2") {
    $next_day = $today;
    $time_start = "140000";
    $time_end = "215959";
  } else {
    $time_start = "220000";
    $time_end = "055959";
  }
  //$today = "20230127"; //buat testing doang
  $sql = "SELECT DISTINCT "
    . "chr_cod_sykmno AS ldnum, "
    . "chr_ngp_syukka AS shipping_date, "
    . "chr_tim_syukka AS shipping_time, "
    . "CHR_COD_TOKISAKI AS customer_code, "
    . "CHR_MEI_NOUNYU AS customer_name, "
    . "chr_ngp_syukka+''+chr_tim_syukka AS dt_time "
    . "FROM tt_gig_sykmeisai "
    . "WHERE chr_ngp_syukka+''+chr_tim_syukka BETWEEN '" . $today . $time_start . "' AND '" . $next_day . $time_end . "' "
    . "ORDER BY dt_time asc ";
  $stmt = $conn_sql_srv->prepare($sql);
  //$return["sql"] = $sql;
  $data_main = [];
  $i = 0;
  $js_ms = 1000;
  $jam_now = (strtotime(date("Y-m-d H:i")) * $js_ms);
  //jam lead time dalam javascript
  $jam_3p = ((strtotime(date("Y-m-d H:i")) + (60 * $lead_time)) * $js_ms);
  $jam_3m = ((strtotime(date("Y-m-d H:i")) - (60 * $lead_time)) * $js_ms);
  if ($stmt->execute() or die($sql)) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      //cek apakah loading list sudah pernah ada
      $str_dat = substr($row["shipping_date"], 0, 4) . "-"
        . substr($row["shipping_date"], 4, 2) . "-"
        . substr($row["shipping_date"], 6, 2) . " "
        . substr($row["shipping_time"], 0, 2) . ":"
        . substr($row["shipping_time"], 2, 2) . ":"
        . substr($row["shipping_time"], 4, 2) . "";
      $js_time1 = strtotime($str_dat) * $js_ms;
      $js_time2 = ((strtotime($str_dat) + (60 * 2)) * $js_ms);

      $color = "#cfcfcf";
      $cek_ldlist = $cLdList->getHeaderById($row["ldnum"]);
      if (!empty($cek_ldlist)) {
        $color = "#b5b5b5";
        //jika loading list complete, warna hijau
        if ($cek_ldlist["stats"] == "C") {
          $color = "#00E396";
        }

        //jika loading list masih N dan tanggal jam loading list lebih dari jam sekarang maka merah
        if ($cek_ldlist["stats"] == "N" && $jam_now > $js_time1) {
          $color = "#FF4560";
        }

        //jika jam diantara jam sekarang dan lead time dan status loading list masih N maka kuning
        if ($cek_ldlist["stats"] == "N" && $jam_now < $js_time1 && $js_time1 < $jam_3p) {
          $color = "#FEB019";
        }

        //jika jam ldlist > jam sekarang dan > lead time dan status loading list masih N maka abu2
        if ($cek_ldlist["stats"] == "N" && $jam_now < $js_time1 && $js_time1 > $jam_3p) {
          $color = "#b5b5b5";
        }
        //jika sudah delivery biru
        if ($cek_ldlist["dstats"] == "D") {
          $color = "#03adfc";
        }
      } else {
        //jika belum di scan
        //jika jam sekarang lebih besar dari jam loading list maka merah
        if ($jam_now > $js_time1) {
          $color = "#FF4560";
        }

        //jika jam diantara jam sekarang dan lead time maka kuning
        if ($js_time1 > $jam_now && $js_time1 < $jam_3p) {
          $color = "#FEB019";
        }

        //jika jam ldlist > jam sekarang dan > lead time maka abu2
        if ($js_time1 > $jam_now && $js_time1 > $jam_3p) {
          $color = "#b5b5b5";
        }
      }
      $data = [];
      $data[0]["x"] = $row["customer_name"];
      $data[0]["y"] = [$js_time1, $js_time2];
      $data[0]["fillColor"] = $color;
      $data_main[$i]["name"] = $row["ldnum"];
      $data_main[$i]["data"] = $data;
      $i++;
    }
  }
  $return["data_chart"] = $data_main;

  echo json_encode($return);
}

if ($action == "api_dashboard_realtime") {
  $return = [];
  $cLdList = new LoadingList();
  $conn_sql_srv = new PDO(SQLSRV_DSN, SQLSRV_USERNAME, SQLSRV_PASSWORD);
  $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
  $sql = "SELECT pval1 FROM m_param WHERE pid = 'PL_LEADTIME' and seq = '1'";
  $stmt = $conn->prepare($sql);
  $lead_time = 1;
  if ($stmt->execute() or die($sql)) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $lead_time = floatval($row["pval1"]);
    }
  }
  $return["lead_time"] = $lead_time;
  $today = date("Ymd");
  // $today = "20230127";

  // $sql = "SELECT DISTINCT 
  //           chr_cod_sykmno AS ldnum,             
  //           chr_ngp_syukka AS shipping_date, 
  //           chr_tim_syukka AS shipping_time, 
  //           CHR_COD_TOKISAKI AS customer_code, 
  //           CHR_MEI_NOUNYU AS customer_name             
  //           FROM tt_gig_sykmeisai 
  //           WHERE chr_ngp_syukka = '" . $today . "' 
  //           ORDER BY shipping_time asc ";
  $sql = "SELECT DISTINCT "
    . "chr_cod_sykmno AS ldnum, "
    . "chr_ngp_syukka AS shipping_date, "
    . "chr_tim_syukka AS shipping_time, "
    . "CHR_COD_TOKISAKI AS customer_code, "
    . "CHR_MEI_NOUNYU AS customer_name, "
    . "chr_ngp_syukka+''+chr_tim_syukka AS dt_time "
    . "FROM tt_gig_sykmeisai "
    . "WHERE chr_ngp_syukka = '" . $today . "' "
    . "ORDER BY dt_time asc ";
  $stmt = $conn_sql_srv->prepare($sql);
  $data_main = [];
  $i = 0;
  $js_ms = 1000;
  $jam_now = (strtotime(date("Y-m-d H:i")) * $js_ms);
  //jam lead time dalam javascript
  $jam_3p = ((strtotime(date("Y-m-d H:i")) + (60 * $lead_time)) * $js_ms);
  $jam_3m = ((strtotime(date("Y-m-d H:i")) - (60 * $lead_time)) * $js_ms);
  if ($stmt->execute() or die($sql)) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      //cek apakah loading list sudah pernah ada
      $str_dat = substr($row["shipping_date"], 0, 4) . "-"
        . substr($row["shipping_date"], 4, 2) . "-"
        . substr($row["shipping_date"], 6, 2) . " "
        . substr($row["shipping_time"], 0, 2) . ":"
        . substr($row["shipping_time"], 2, 2) . ":"
        . substr($row["shipping_time"], 4, 2) . "";
      $js_time1 = strtotime($str_dat) * $js_ms;
      $js_time2 = ((strtotime($str_dat) + (60 * 2)) * $js_ms);

      $color = "#cfcfcf";
      $cek_ldlist = $cLdList->getHeaderById($row["ldnum"]);
      if (!empty($cek_ldlist)) {
        $color = "#b5b5b5";
        //jika loading list complete, warna hijau
        if ($cek_ldlist["stats"] == "C") {
          $color = "#00E396";
        }

        //jika loading list masih N dan tanggal jam loading list lebih dari jam sekarang maka merah
        if ($cek_ldlist["stats"] == "N" && $jam_now > $js_time1) {
          $color = "#FF4560";
        }

        //jika jam diantara jam sekarang dan lead time dan status loading list masih N maka kuning
        if ($cek_ldlist["stats"] == "N" && $jam_now < $js_time1 && $js_time1 < $jam_3p) {
          $color = "#FEB019";
        }

        //jika jam ldlist > jam sekarang dan > lead time dan status loading list masih N maka abu2
        if ($cek_ldlist["stats"] == "N" && $jam_now < $js_time1 && $js_time1 > $jam_3p) {
          $color = "#b5b5b5";
        }
        //jika sudah delivery biru
        if ($cek_ldlist["dstats"] == "D") {
          $color = "#03adfc";
        }
      } else {
        //jika belum di scan
        //jika jam sekarang lebih besar dari jam loading list maka merah
        if ($jam_now > $js_time1) {
          $color = "#FF4560";
        }

        //jika jam diantara jam sekarang dan lead time maka kuning
        if ($js_time1 > $jam_now && $js_time1 < $jam_3p) {
          $color = "#FEB019";
        }

        //jika jam ldlist > jam sekarang dan > lead time maka abu2
        if ($js_time1 > $jam_now && $js_time1 > $jam_3p) {
          $color = "#b5b5b5";
        }
      }
      // $data_main[$i]["x"] = $row["customer_name"];
      // $data_main[$i]["y"] = [$js_time1,$js_time2];
      // $data_main[$i]["fillColor"] = $color;
      // $i++;
      $data = [];
      $data[0]["x"] = $row["customer_name"];
      $data[0]["y"] = [$js_time1, $js_time2];
      $data[0]["fillColor"] = $color;
      $data_main[$i]["name"] = $row["ldnum"];
      $data_main[$i]["data"] = $data;
      $i++;
    }
  }
  $return["data_chart"] = $data_main;

  echo json_encode($return);
}
?>