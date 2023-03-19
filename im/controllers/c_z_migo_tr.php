<?php 

if($action == "z_migo_tr_1") {
  $template["group"] = "Z Transaction";
  $template["menu"] = "Stock Transfer S001 to S002";
  $cMatDoc = new MaterialDocument();
  $cStock = new Stock();
  $cPlant = new Plant();
  $cSloc = new StoreLocation();
  $cMara = new Material();
  
  if(isset($_POST["save"])) {
    //cek stock dulu
    $mchb1 = [];
    $mchb1["matnr"] = $_POST["matnr"];
    $mchb1["werks"] = $_POST["werks"];
    $mchb1["lgort"] = $_POST["lgort"];
    $mchb1["charg"] = $_POST["charg"];
    $mchb1["clabs"] = $_POST["menge"];    
    $mchb1["cinsm"] = 0;
    $mchb1["chg_by"] = $_SESSION[LOGIN_SESSION];
    $mchb1["crt_by"] = $_SESSION[LOGIN_SESSION];
    $cek_stock = $cStock->checkDeficitStock($mchb1);
    if($cek_stock["status"] === false) {
      $message = $cek_stock["message"];
      header("Location: ?action=$action&error=$message");
      die();
    }
    
    $mchb2 = [];
    $mchb2["matnr"] = $_POST["matnr2"];
    $mchb2["werks"] = $_POST["werks2"];
    $mchb2["lgort"] = $_POST["lgort2"];
    $mchb2["charg"] = $_POST["charg2"];
    $mchb2["clabs"] = $_POST["menge"];    
    $mchb2["cinsm"] = 0;
    $mchb2["chg_by"] = $_SESSION[LOGIN_SESSION];
    $mchb2["crt_by"] = $_SESSION[LOGIN_SESSION];
    
    $mblnr = $cMatDoc->generateID();
    $mjahr = date("Y");
    $mkpf["mblnr"] = $mblnr;
    $mkpf["mjahr"] = $mjahr;    
    $mkpf["budat"] = $_POST["budat"];
    $mkpf["crt_by"] = $_SESSION[LOGIN_SESSION];
    $save = $cMatDoc->insertHeader($mkpf);
    if($save["status"] == true){
      $mseg = [];
      $reduce_stock = $cStock->reduceStock($mchb1);
      if($reduce_stock["status"] == true) {
        $mseg["mblnr"] = $mblnr;
        $mseg["mjahr"] = $mjahr;
        $mseg["mblpo"] = "1";
        $mseg["matnr"] = $mchb1["matnr"];
        $mseg["bwart"] = "311";
        $mseg["shkzg"] = "D";
        $mseg["menge"] = $mchb1["clabs"];
        $mseg["werks"] = $mchb1["werks"];
        $mseg["lgort"] = $mchb1["lgort"];
        $mseg["charg"] = $mchb1["charg"];
        $mseg["ebeln"] = "";
        $mseg["aufnr"] = "";
        $insert_mseg = $cMatDoc->insertItem($mseg);
        if($insert_mseg["status"] == true) {
          //continue process
        } else {
          $cMatDoc->rollBackMaterialDocument($mblnr, $mjahr);
          $cStock->addStock($mchb1);
          $message = $insert_mseg["message"];
          header("Location: ?action=$action&error=$message");
          die();
        }
      }
      
      $mseg = [];
      $add_stock = $cStock->addStock($mchb2);
      if($add_stock["status"] == true) {
        $mseg["mblnr"] = $mblnr;
        $mseg["mjahr"] = $mjahr;
        $mseg["mblpo"] = "2";
        $mseg["matnr"] = $mchb2["matnr"];
        $mseg["bwart"] = "311";
        $mseg["shkzg"] = "C";
        $mseg["menge"] = $mchb2["clabs"];
        $mseg["werks"] = $mchb2["werks"];
        $mseg["lgort"] = $mchb2["lgort"];
        $mseg["charg"] = $mchb2["charg"];
        $mseg["ebeln"] = "";
        $mseg["aufnr"] = "";
        $insert_mseg = $cMatDoc->insertItem($mseg);
        if($insert_mseg["status"] == true) {
          //continue process
        } else {
          $cMatDoc->rollBackMaterialDocument($mblnr, $mjahr);
          $cStock->reduceStockStock($mchb2);
          $message = $insert_mseg["message"];
          header("Location: ?action=$action&error=$message");
          die();
        }
      }
      
      $message = "SUCCESS : Transfer Posting done, Material Document $mblnr - $mjahr created";
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
  require( TEMPLATE_PATH . "/t_z_migo_tr_1.php" );
}
?>