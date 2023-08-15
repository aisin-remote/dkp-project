<?php 
if($action == "vl01n") {
  $template["group"] = "Transaction";
  $template["menu"] = "Outbond Delivery";
  $cMatDoc = new MaterialDocument();
  $cStock = new Stock();
  $cPlant = new Plant();
  $cSloc = new StoreLocation();
  $cMara = new Material();
  if(isset($_POST["save"])) {
    $mkpf = [];
    $mblnr = $cMatDoc->generateID();
    $mjahr = date("Y");
    $mkpf["mblnr"] = $mblnr;
    $mkpf["mjahr"] = $mjahr;    
    $mkpf["budat"] = $_POST["budat"];
    $mkpf["crt_by"] = $_SESSION[LOGIN_SESSION];
    $matnr = $_POST["matnr"];
    $lgort = $_POST["lgort"];
    $menge = $_POST["menge"];
    $werks = $_POST["werks"];
    $ebeln = $_POST["ebeln"];
    $vbeln = $_POST["vbeln"];
    $lifnr = $_POST["lifnr"];
    if(empty($ebeln)) {
      $ebeln = date("ymdHis");
    }
    $chargs = $_POST["charg"];
    //insert header    
    $save = $cMatDoc->insertHeader($mkpf);
    if($save["status"] == true){
      $mseg = [];
      $mchb = [];
      $batch_number = "";
      $i = 0;
      foreach($matnr as $row) {
        //coba masukkan stok dulu
        if(empty($chargs[$i])) {
          $charg = $cStock->generateBatchNumber(); //batch number di generate otomatis by system
        } else {
          $charg = $chargs[$i];
        }
        
        $mchb["matnr"] = $row;
        $mchb["werks"] = $_POST["werks"];
        $mchb["lgort"] = $lgort[$i];
        $mchb["charg"] = $charg;
        $mchb["clabs"] = $menge[$i]; //unrest stock
        $mchb["cinsm"] = 0; //QA Stock
        $mchb["chg_by"] = $_SESSION[LOGIN_SESSION];
        $mchb["crt_by"] = $_SESSION[LOGIN_SESSION];
        
        $add_stock = $cStock->reduceStock($mchb);
        //setelah masukkan stok insert mseg
        if($add_stock["status"] == true) {
          $mseg["mblnr"] = $mblnr;
          $mseg["mjahr"] = $mjahr;
          $mseg["mblpo"] = $i+1;
          $mseg["matnr"] = $mchb["matnr"];
          $mseg["bwart"] = "261";
          $mseg["shkzg"] = "D";
          $mseg["menge"] = $mchb["clabs"];
          $mseg["werks"] = $mchb["werks"];
          $mseg["lgort"] = $mchb["lgort"];
          $mseg["charg"] = $mchb["charg"];
          $mseg["ebeln"] = $ebeln;
          $mseg["aufnr"] = "";
          $mseg["vbeln"] = $vbeln;
          $mseg["lifnr"] = $lifnr;
          $insert_mseg = $cMatDoc->insertItem($mseg);
          if($insert_mseg["status"] == true) {
            //continue process
          } else {
            $cMatDoc->rollBackMaterialDocument($mblnr, $mjahr);
            $cStock->reduceStock($mchb);
            $message = $insert_mseg["message"];
            header("Location: ?action=$action&error=$message");
            die();
          }
        } else {
          $cMatDoc->rollBackMaterialDocument($mblnr, $mjahr);
          $message = $add_stock["message"];
          header("Location: ?action=$action&error=$message");
          die();
        }
        $i++;
      }
      $message = "SUCCESS : Good Issue done, Material Document $mblnr - $mjahr created";
      header("Location: ?action=$action&success=$message");
      die();
    } else {
      $message = $save["message"];
      header("Location: ?action=$action&error=$message");
      die();
    }
  } 
  
  $data["plants"] = $cPlant->getList();
  $data["materials"] = $cMara->getListByType("FIN");
  require( TEMPLATE_PATH . "/t_migo_gi_do.php" );
}
?>