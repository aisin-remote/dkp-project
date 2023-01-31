<?php 
if($action == "api_dashboard_pooling") {
  $cLdList = new LoadingList();
  $conn_sql_srv = new PDO(SQLSRV_DSN,SQLSRV_USERNAME,SQLSRV_PASSWORD);
  $today = date("Ymd");
  //$today = "20230127"; //buat testing doang
  $sql = "SELECT DISTINCT 
            chr_cod_sykmno AS ldnum,             
            chr_ngp_syukka AS shipping_date, 
            chr_tim_syukka AS shipping_time, 
            CHR_COD_TOKISAKI AS customer_code, 
            CHR_MEI_NOUNYU AS customer_name             
            FROM tt_gig_sykmeisai 
            WHERE chr_ngp_syukka = '".$today."' 
            ORDER BY shipping_time asc ";
  $stmt = $conn_sql_srv->prepare($sql);
  $data_main = [];
  $i = 0;
  $js_ms = 1000;
  $jam_now = (strtotime(date("Y-m-d H:i"))*$js_ms);
  $jam_3p = ((strtotime(date("Y-m-d H:i")) + (60*60*3)) * $js_ms);
  $jam_3m = ((strtotime(date("Y-m-d H:i")) - (60*60*3)) * $js_ms);
  if($stmt->execute() or die($sql)) {
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      //cek apakah loading list sudah pernah ada
      $str_dat = substr($row["shipping_date"], 0, 4)."-"
              .substr($row["shipping_date"], 4, 2)."-"
              .substr($row["shipping_date"], 6, 2)." "
              .substr($row["shipping_time"], 0, 2).":"
              .substr($row["shipping_time"], 2, 2).":"
              .substr($row["shipping_time"], 4, 2)."";
      $js_time1 = strtotime($str_dat) * $js_ms;
      $js_time2 = ((strtotime($str_dat)+(60*5)) * $js_ms);
      
      $color = "#cfcfcf";
      $cek_ldlist = $cLdList->getHeaderById($row["ldnum"]);
      if(!empty($cek_ldlist)) {
        $color = "#cfcfcf";
        if($cek_ldlist["stats"] == "C") {
          $color = "#00E396";
        }
        
        if($cek_ldlist["stats"] == "N" && $jam_now > $js_time1) {
          $color = "#FF4560";
        }
        if($cek_ldlist["stats"] == "N" && $jam_now < $js_time1 && $jam_now > $jam_3m) {
          $color = "#FEB019";
        }
      } else {
        if($jam_now > $js_time1) {
          $color = "#FF4560";
        }
        if($jam_now < $js_time1 && $jam_now > $jam_3m) {
          $color = "#FEB019";
        }
      }
      $data_main[$i]["x"] = $row["customer_name"];
      $data_main[$i]["y"] = [$js_time1,$js_time2];
      $data_main[$i]["fillColor"] = $color;
      $i++;
    }
  }
  echo json_encode($data_main);  
}
?>