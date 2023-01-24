<?php 
if($action == "api_insert_daily_stop") {
  $class = new Production();
  
  $param = $_REQUEST;
  if(empty($param["qty_stc"])) {
    $param["qty_stc"] = "0";
  }
  $save = array();
  if(!empty($param["line_id"])) {
    $save = $class->insertStop($param);
  }
  echo json_encode($save);
}

if($action == "api_delete_daily_stop") {
  $class = new Production();
  $line_id = $_REQUEST["line_id"];
  $prd_dt = $_REQUEST["prd_dt"];
  $shift = $_REQUEST["shift"];
  $prd_seq = $_REQUEST["prd_seq"];
  $stop_seq = $_REQUEST["stop_seq"];
  $del = array();
  $del = $class->deleteStop($line_id, $prd_dt, $shift, $prd_seq, $stop_seq);
  echo json_encode($del);
}

if($action == "api_insert_daily_ng") {
  $class = new Production();
  $param = $_REQUEST;
  if(empty($param["ng_qty"])) {
    $param["ng_qty"] = 0;
  }
  $save = array();
  if(!empty($param["line_id"])) {
    $save = $class->insertNG($param);
  }
  echo json_encode($save);
}

if($action == "api_delete_daily_ng") {
  $class = new Production();
  $line_id = $_REQUEST["line_id"];
  $prd_dt = $_REQUEST["prd_dt"];
  $shift = $_REQUEST["shift"];
  $prd_seq = $_REQUEST["prd_seq"];
  $ng_seq = $_REQUEST["ng_seq"];
  $del = array();
  $del = $class->deleteNG($line_id, $prd_dt, $shift, $prd_seq, $ng_seq);
  echo json_encode($del);
}
?>