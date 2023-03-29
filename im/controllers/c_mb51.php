<?php 
if($action == "mb51") {
  $template["group"] = "Report";
  $template["menu"] = "Material Document List";
  $class = new MaterialDocument();
  $cPlant = new Plant();
  $cSloc = new StoreLocation();
  $cMara = new Material();
  
  $data["plants"] = $cPlant->getList();
  //$data["lgorts"] = $cSloc->getList($data["plants"][0]["werks"]);
  $data["materials"] = $cMara->getList();
  
  $budat_from = date("Y-m-d");
  if(isset($_GET["budat_from"])) {
    $budat_from = $_GET["budat_from"];
  }
  $budat_to = date("Y-m-d");
  if(isset($_GET["budat_to"])) {
    $budat_to = $_GET["budat_to"];
  }
  $matnr = null;
  if(isset($_GET["matnr"])) {
    $matnr = $_GET["matnr"];
  }
  $werks = null;
  if(isset($_GET["werks"])) {
    $werks = $_GET["werks"];
    $data["lgorts"] = $cSloc->getList($werks);
  }
  $lgort = null;
  if(isset($_GET["lgort"])) {
    $lgort = $_GET["lgort"];
  }
  
  $data["list"] = $class->getList($budat_from, $budat_to, $matnr, $werks, $lgort);
  require( TEMPLATE_PATH . "/t_mb51.php" );
  unset($data["list"]);
}
?>