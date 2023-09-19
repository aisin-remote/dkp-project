<?php 
if($action == "api_get_trn_mon") {
  $bulan = $_REQUEST["imonth"];
  $itm_id = $_REQUEST["itm_id"];
  $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
  $sql = "select actual, (TO_CHAR(date1,'DD'))::int as tgl, type1, shift from qas_ant.t_trn_itm 
          WHERE TO_CHAR(date1,'YYYY-MM') = '$bulan' 
          AND itm_id = '$itm_id' 
          ORDER BY shift asc, type1 ASC, tgl ASC";
  $stmt = $conn->prepare($sql);
  $data_shift_1_f = [];
  $data_shift_1_l = [];
  $data_shift_2_f = [];
  $data_shift_2_l = [];
  if($stmt->execute()) {
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
      if($row["shift"] == "1") {
        if($row["type1"] == "F") {
          $data_shift_1_f[] = $row;
        } else if($row["type1"] == "L") {
          $data_shift_1_l[] = $row;
        }
      } else if($row["shift"] == "2") {
        if($row["type1"] == "F") {
          $data_shift_2_f[] = $row;
        } else if($row["type1"] == "L") {
          $data_shift_2_l[] = $row;
        }
      }
    }
  }
  
  $data_chart_1_f = [];
  $data_chart_1_l = [];
  $data_chart_2_f = [];
  $data_chart_2_l = [];
  
  for($i = 0; $i < 31; $i++) {
    $data_chart_1_f[$i] = null;
    $data_chart_1_l[$i] = null;
    $data_chart_2_f[$i] = null;
    $data_chart_2_l[$i] = null;
    foreach($data_shift_1_f as $row) {
      if(intval($row["tgl"])-1 == $i) {
        $data_chart_1_f[$i] = intval($row["actual"]);
        break;
      }
    }
    
    foreach($data_shift_1_l as $row) {
      if(intval($row["tgl"])-1 == $i) {
        $data_chart_1_l[$i] = intval($row["actual"]);
        break;
      }
    }
    
    foreach($data_shift_2_f as $row) {
      if(intval($row["tgl"])-1 == $i) {
        $data_chart_2_f[$i] = intval($row["actual"]);
        break;
      }
    }
    
    foreach($data_shift_2_l as $row) {
      if(intval($row["tgl"])-1 == $i) {
        $data_chart_2_l[$i] = intval($row["actual"]);
        break;
      }
    }    
  }
  
  $data["chart_1_f"] = $data_chart_1_f;
  $data["chart_1_l"] = $data_chart_1_l;
  $data["chart_2_f"] = $data_chart_2_f;
  $data["chart_2_l"] = $data_chart_2_l;
  
  echo json_encode($data);
}
?>