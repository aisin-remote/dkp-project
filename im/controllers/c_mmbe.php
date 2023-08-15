<?php 
if($action == "mmbe") {
  $template["group"] = "Report";
  $template["menu"] = "Report Stock";
  $class = new Stock();
  $cPlant = new Plant();
  $cSloc = new StoreLocation();
  $cMara = new Material();
  
  $data["plants"] = $cPlant->getList();
  $data["lgorts"] = $cSloc->getList($data["plants"][0]["werks"]);
  $data["materials"] = $cMara->getList();
  
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
  $type = null;
  if(isset($_GET["type"])) {
    $type = $_GET["type"];
  }
  
  $data["list"] = $class->getList($matnr, $werks, $lgort, $type);
  require( TEMPLATE_PATH . "/t_mmbe.php" );
}
?>