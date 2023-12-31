<?php
if ($action == "migo_gr_po") {
  $template["group"] = "Transaction";
  $template["menu"] = "Good Receipt from PO";
  $cMatDoc = new MaterialDocument();
  $cStock = new Stock();
  $cPlant = new Plant();
  $cSloc = new StoreLocation();
  $cMara = new Material();
  if (isset($_POST["save"])) {
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
    $ng = $_POST["ng"];
    $werks = $_POST["werks"];
    $ebeln = $_POST["ebeln"];
    if (empty($ebeln)) {
      $ebeln = date("ymdHis");
    }
    $chargs = $_POST["charg"];
    //insert header    
    $save = $cMatDoc->insertHeader($mkpf);
    if ($save["status"] == true) {
      $mseg = [];
      $mchb = [];
      $batch_number = "";
      $i = 0;
      foreach ($matnr as $row) {
        //coba masukkan stok dulu
        if (empty($chargs[$i])) {
          $charg = $row;
        } else {
          $charg = $chargs[$i];
        }

        if (empty($ng[$i])) {
          $mchb["matnr"] = $row;
          $mchb["werks"] = $_POST["werks"];
          $mchb["lgort"] = $lgort[$i];
          $mchb["charg"] = $charg;
          $mchb["clabs"] = $menge[$i]; //unrest stock
          $mchb["cinsm"] = 0; //QA Stock
          $mchb["chg_by"] = $_SESSION[LOGIN_SESSION];
          $mchb["crt_by"] = $_SESSION[LOGIN_SESSION];
          $add_stock = $cStock->addStock($mchb);
          //setelah masukkan stok insert mseg
          if ($add_stock["status"] == true) {
            $mseg["mblnr"] = $mblnr;
            $mseg["mjahr"] = $mjahr;
            $mseg["mblpo"] = $i + 1;
            $mseg["matnr"] = $mchb["matnr"];
            $mseg["bwart"] = "101";
            $mseg["shkzg"] = "C";
            $mseg["menge"] = $mchb["clabs"];
            $mseg["werks"] = $mchb["werks"];
            $mseg["lgort"] = $mchb["lgort"];
            $mseg["charg"] = $mchb["charg"];
            $mseg["ebeln"] = $ebeln;
            $mseg["aufnr"] = "";
            $insert_mseg = $cMatDoc->insertItem($mseg);
            if ($insert_mseg["status"] == true) {
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
        } else {
          $mchb["matnr"] = $row;
          $mchb["werks"] = $_POST["werks"];
          $mchb["lgort"] = $lgort[$i];
          $mchb["charg"] = $charg;
          $mchb["clabs"] = $menge[$i] - $ng[$i]; //unrest stock
          $mchb["cinsm"] = 0; //QA Stock
          $mchb["chg_by"] = $_SESSION[LOGIN_SESSION];
          $mchb["crt_by"] = $_SESSION[LOGIN_SESSION];

          $add_stock = $cStock->addStock($mchb);
          // print_r($mchb);
          // $die();
          
          $mats = $row . "-NG";
          $mchb1["matnr"] = $mats;
          $mchb1["werks"] = $_POST["werks"];
          $mchb1["lgort"] = $lgort[$i];
          $mchb1["charg"] = $charg."NG";
          $mchb1["clabs"] = $ng[$i]; //unrest stock
          $mchb1["cinsm"] = 0; //QA Stock
          $mchb1["chg_by"] = $_SESSION[LOGIN_SESSION];
          $mchb1["crt_by"] = $_SESSION[LOGIN_SESSION];

          $add_stock1 = $cStock->addStock($mchb1);
          //setelah masukkan stok insert mseg
          if ($add_stock["status"] == true && $add_stock1["status"] == true) {
            $mseg["mblnr"] = $mblnr;
            $mseg["mjahr"] = $mjahr;
            $mseg["mblpo"] = $i + 1;
            $mseg["matnr"] = $mchb["matnr"];
            $mseg["bwart"] = "101";
            $mseg["shkzg"] = "C";
            $mseg["menge"] = $mchb["clabs"];
            $mseg["werks"] = $mchb["werks"];
            $mseg["lgort"] = $mchb["lgort"];
            $mseg["charg"] = $mchb["charg"];
            $mseg["ebeln"] = $ebeln;
            $mseg["aufnr"] = "";
            $insert_mseg = $cMatDoc->insertItem($mseg);

            $mseg1["mblnr"] = $mblnr."NG";
            $mseg1["mjahr"] = $mjahr;
            $mseg1["mblpo"] = $i + 1;
            $mseg1["matnr"] = $mchb1["matnr"];
            $mseg1["bwart"] = "101";
            $mseg1["shkzg"] = "C";
            $mseg1["menge"] = $mchb1["clabs"];
            $mseg1["werks"] = $mchb["werks"];
            $mseg1["lgort"] = $mchb["lgort"];
            $mseg1["charg"] = $mchb1["charg"];
            $mseg1["ebeln"] = $ebeln;
            $mseg1["aufnr"] = "";
            $insert_mseg1 = $cMatDoc->insertItem($mseg1);
            if ($insert_mseg["status"] == true && $insert_mseg1["status"] == true) {
              //continue process
            } else {
              $cMatDoc->rollBackMaterialDocument($mblnr, $mjahr);
              $cStock->reduceStock($mchb);
              $message = $insert_mseg1["message"];
              header("Location: ?action=$action&error=Material Document: $message");
              die();
            }
          } else {
            $cMatDoc->rollBackMaterialDocument($mblnr, $mjahr);
            $message = $add_stock["message"];
            header("Location: ?action=$action&error=Stock: $message");
            die();
          }
        }

        $i++;
      }
      $message = "SUCCESS : Good Receipt done, Material Document $mblnr - $mjahr created";
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
  require(TEMPLATE_PATH . "/t_migo_gr_po.php");
}

if ($action == "migo_gr_au") {
  $template["group"] = "Transaction";
  $template["menu"] = "Good Receipt from Production";
  $cMatDoc = new MaterialDocument();
  $cStock = new Stock();
  $cPlant = new Plant();
  $cSloc = new StoreLocation();
  $cMara = new Material();
  if (isset($_POST["save"])) {
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
    $ebeln = "";
    $aufnr = $_POST["aufnr"];
    //insert header    
    $save = $cMatDoc->insertHeader($mkpf);
    if ($save["status"] == true) {
      $mseg = [];
      $mchb = [];
      $batch_number = "";
      $i = 0;
      foreach ($matnr as $row) {
        //coba masukkan stok dulu
        $charg = $cStock->generateBatchNumber(); //batch number di generate otomatis by system
        $mchb["matnr"] = $row;
        $mchb["werks"] = $_POST["werks"];
        $mchb["lgort"] = $lgort[$i];
        $mchb["charg"] = $charg;
        $mchb["clabs"] = $menge[$i]; //unrest stock
        $mchb["cinsm"] = 0; //QA Stock
        $mchb["chg_by"] = $_SESSION[LOGIN_SESSION];
        $mchb["crt_by"] = $_SESSION[LOGIN_SESSION];

        $add_stock = $cStock->addStock($mchb);
        //setelah masukkan stok insert mseg
        if ($add_stock["status"] == true) {
          $mseg["mblnr"] = $mblnr;
          $mseg["mjahr"] = $mjahr;
          $mseg["mblpo"] = $i + 1;
          $mseg["matnr"] = $mchb["matnr"];
          $mseg["bwart"] = "Z01";
          $mseg["shkzg"] = "C";
          $mseg["menge"] = $mchb["clabs"];
          $mseg["werks"] = $mchb["werks"];
          $mseg["lgort"] = $mchb["lgort"];
          $mseg["charg"] = $mchb["charg"];
          $mseg["ebeln"] = $ebeln;
          $mseg["aufnr"] = $aufnr;
          $insert_mseg = $cMatDoc->insertItem($mseg);
          if ($insert_mseg["status"] == true) {
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
      $message = "SUCCESS : Good Receipt done, Material Document $mblnr - $mjahr created";
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
  require(TEMPLATE_PATH . "/t_migo_gr_au.php");
}

if ($action == "migo_gr_rt") {
  $template["group"] = "Transaction";
  $template["menu"] = "Return to Warehouse";
  $cMatDoc = new MaterialDocument();
  $cStock = new Stock();
  $cPlant = new Plant();
  $cSloc = new StoreLocation();
  $cMara = new Material();
  if (isset($_POST["save"])) {
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
    $ebeln = "";
    $aufnr = "";
    //insert header    
    $save = $cMatDoc->insertHeader($mkpf);
    if ($save["status"] == true) {
      $mseg = [];
      $mchb = [];
      $batch_number = "";
      $i = 0;
      foreach ($matnr as $row) {
        //coba masukkan stok dulu
        $charg = $cStock->generateBatchNumber(); //batch number di generate otomatis by system
        $mchb["matnr"] = $row;
        $mchb["werks"] = $_POST["werks"];
        $mchb["lgort"] = $lgort[$i];
        $mchb["charg"] = $charg;
        $mchb["clabs"] = $menge[$i]; //unrest stock
        $mchb["cinsm"] = 0; //QA Stock
        $mchb["chg_by"] = $_SESSION[LOGIN_SESSION];
        $mchb["crt_by"] = $_SESSION[LOGIN_SESSION];

        $add_stock = $cStock->addStock($mchb);
        //setelah masukkan stok insert mseg
        if ($add_stock["status"] == true) {
          $mseg["mblnr"] = $mblnr;
          $mseg["mjahr"] = $mjahr;
          $mseg["mblpo"] = $i + 1;
          $mseg["matnr"] = $mchb["matnr"];
          $mseg["bwart"] = "501";
          $mseg["shkzg"] = "C";
          $mseg["menge"] = $mchb["clabs"];
          $mseg["werks"] = $mchb["werks"];
          $mseg["lgort"] = $mchb["lgort"];
          $mseg["charg"] = $mchb["charg"];
          $mseg["ebeln"] = $ebeln;
          $mseg["aufnr"] = $aufnr;
          $insert_mseg = $cMatDoc->insertItem($mseg);
          if ($insert_mseg["status"] == true) {
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
      $message = "SUCCESS : Good Receipt done, Material Document $mblnr - $mjahr created";
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
  require(TEMPLATE_PATH . "/t_migo_gr_rt.php");
}
?>