<?php 
if($action == "migo_gi_au") {
  $template["group"] = "Transaction";
  $template["menu"] = "Good Issue to Production";
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
    $charg = $_POST["charg"];
    $menge = $_POST["menge"];
    $werks = $_POST["werks"];
    $ebeln = "";
    $aufnr = $_POST["aufnr"];
    //cek ketersediaan stock dahulu
    $i = 0;
    foreach($matnr as $row) {
      $mchb_cek = [];
      $mchb_cek["matnr"] = $row;
      $mchb_cek["werks"] = $_POST["werks"];
      $mchb_cek["lgort"] = $lgort[$i];
      $mchb_cek["charg"] = $charg[$i];
      $mchb_cek["clabs"] = $menge[$i]; //unrest stock
      $mchb_cek["cinsm"] = 0; //QA Stock
      $cek_stock = $cStock->checkDeficitStock($mchb_cek);
      if($cek_stock["status"] === false) {
        $message = $cek_stock["message"];
        header("Location: ?action=$action&error=$message");
        die();
      }
      $i++;
    }
    //insert header    
    $save = $cMatDoc->insertHeader($mkpf);
    if($save["status"] == true){
      $mseg = [];
      $mchb = [];
      $batch_number = "";
      $i = 0;
      foreach($matnr as $row) {
        //coba masukkan stok dulu
        //$charg = $cStock->generateBatchNumber(); //batch number di generate otomatis by system
        $mchb["matnr"] = $row;
        $mchb["werks"] = $_POST["werks"];
        $mchb["lgort"] = $lgort[$i];
        $mchb["charg"] = $charg[$i];
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
          $mseg["aufnr"] = $aufnr;
          $insert_mseg = $cMatDoc->insertItem($mseg);
          if($insert_mseg["status"] == true) {
            //continue process
          } else {
            $cMatDoc->rollBackMaterialDocument($mblnr, $mjahr);
            $cStock->addStock($mchb);
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
  $data["materials"] = $cMara->getList();
  require( TEMPLATE_PATH . "/t_migo_gi_au.php" );
}
?>