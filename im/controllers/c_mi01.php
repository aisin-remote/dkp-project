<?php 
if($action == "mi01") {
  $template["group"] = "Transaction";
  $template["menu"] = "Stock Adjustment";
  $cMatDoc = new MaterialDocument();
  $cStock = new Stock();
  $cPlant = new Plant();
  $cSloc = new StoreLocation();
  $cMara = new Material();
  
  if(isset($_POST["save"])) {
    $werks = $_POST["werks"];
    $lgort = $_POST["lgort"];
    $matnr = $_POST["matnr"];
    $charg = $_POST["charg"];
    $menge1 = $_POST["menge1"];    
    $menge2 = $_POST["menge2"];
    $menge3 = $_POST["menge3"];
    if(empty($menge1)) {
      $get_detail = $cStock->getStockDetail($werks, $lgort, $matnr, $charg);
      $menge1 = $get_detail["clabs"];
      $menge3 = $menge2 - $menge1;
    }
    $menge = abs($menge3);
    //generate material document
    $mkpf = [];
    $mblnr = $cMatDoc->generateID();
    $mjahr = date("Y");
    $mkpf["mblnr"] = $mblnr;
    $mkpf["mjahr"] = $mjahr;    
    $mkpf["budat"] = $_POST["budat"];
    $mkpf["crt_by"] = $_SESSION[LOGIN_SESSION];
    $save = $cMatDoc->insertHeader($mkpf);
    if($save["status"] == true){
      $mseg = [];
      $mchb = [];
      $mchb["matnr"] = $matnr;
      $mchb["werks"] = $werks;
      $mchb["lgort"] = $lgort;
      $mchb["charg"] = $charg;
      $mchb["clabs"] = $menge; //unrest stock
      $mchb["cinsm"] = 0; //QA Stock
      $mchb["chg_by"] = $_SESSION[LOGIN_SESSION];
      $mchb["crt_by"] = $_SESSION[LOGIN_SESSION];
      $adjust = [];
      if($menge3 < 0) {
        //reduce stock
        $adjust = $cStock->reduceStock($mchb);
        $shkzg = "D";
        $bwart = "702";
      } else {
        //add stock
        $adjust = $cStock->addStock($mchb);
        $shkzg = "C";
        $bwart = "701";
      }
      if($adjust["status"] == true) {
        $mseg["mblnr"] = $mblnr;
        $mseg["mjahr"] = $mjahr;
        $mseg["mblpo"] = $i+1;
        $mseg["matnr"] = $matnr;
        $mseg["bwart"] = $bwart;
        $mseg["shkzg"] = $shkzg;
        $mseg["menge"] = $menge;
        $mseg["werks"] = $werks;
        $mseg["lgort"] = $lgort;
        $mseg["charg"] = $charg;
        $mseg["ebeln"] = "";
        $mseg["aufnr"] = "";
        $insert_mseg = $cMatDoc->insertItem($mseg);
        if($insert_mseg["status"] == true) {
            //continue process
        } else {
          $cMatDoc->rollBackMaterialDocument($mblnr, $mjahr);
          if($menge3 < 0) {
            $cStock->addStock($mchb);
          } else {
            $cStock->reduceStock($mchb);
          }
            
          $message = $insert_mseg["message"];
          header("Location: ?action=$action&error=$message");
          die();
        }
      } else {
        $cMatDoc->rollBackMaterialDocument($mblnr, $mjahr);
        $message = $adjust["message"];
        header("Location: ?action=$action&error=$message");
        die();
      }
      $message = "SUCCESS : Stock Adjustment done, Material Document $mblnr - $mjahr created";
      header("Location: ?action=$action&success=$message");
      die();
    } else {
      $message = $save["message"];
      header("Location: ?action=$action&error=$message");
      die();
    }
    
  }
  
  $data["plants"] = $cPlant->getList();
  $data["materials"] = $cMara->getList();
  require( TEMPLATE_PATH . "/t_mi01.php" );
}
?>