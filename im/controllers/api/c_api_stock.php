<?php 
if($action == "api_get_batch_stock") {
  $werks = $_REQUEST["werks"];
  $lgort = $_REQUEST["lgort"];
  $matnr = $_REQUEST["matnr"];
  $charg = $_REQUEST["charg"];
  
  $class = new Stock();
  $data = $class->getStockDetail($werks, $lgort, $matnr, $charg);
  
  echo json_encode($data);
  unset($data);
}
?>