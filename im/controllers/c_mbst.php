<?php 
if($action == "mbst") {
  $template["group"] = "Transaction";
  $template["menu"] = "Material Document Reversal";
  
  $cMatDoc = new MaterialDocument();
  $cStock = new Stock();
  $cPlant = new Plant();
  $cSloc = new StoreLocation();
  $cMara = new Material();
  
  if(isset($_GET["check"])) {
    $mblnr = $_GET["mblnr"];
    $mjahr = $_GET["mjahr"];
    $data = $cMatDoc->getById($mblnr, $mjahr);
    if(!empty($data["mkpf"])) {
      require( TEMPLATE_PATH . "/t_mbst_2.php" );
    } else {
      header("Location: ?action=" . $action . "&error=No%20Data%20Found!");
    }
  } else if(isset($_POST["save"])){
    $mblnr = $_POST["mblnr"];
    $mjahr = $_POST["mjahr"];
    $get_data = $cMatDoc->getById($mblnr, $mjahr);
    //cek dulu apakah dokumen ini merupakan dokumen reversal
    if(!empty($get_data["mkpf"]["xblnr"])) {
      $message = urlencode("Reversal Document Cannot be Reversed Again!");
      header("Location: ?action=$action&error=$message");
      die();
    }
    
    //cek dulu apakah dokumen ini sudah di reversal
    $cek_reversed = $cMatDoc->isReversed($mblnr, $mjahr);
    if($cek_reversed > 0) {
      $message = urlencode("Material Document $mblnr - $mjahr Already Reversed!");
      header("Location: ?action=$action&error=$message");
      die();
    }
    if(!empty($get_data["mseg"])) {
      //cek dulu stok per line apakah cukup
      foreach($get_data["mseg"] as $row) {
        $mchb_cek = [];
        $mchb_cek["matnr"] = $row["matnr"];
        $mchb_cek["werks"] = $row["werks"];
        $mchb_cek["lgort"] = $row["lgort"];
        $mchb_cek["charg"] = $row["charg"];
        $mchb_cek["clabs"] = $row["menge"]; //unrest stock
        $mchb_cek["cinsm"] = 0; //QA Stock
        if($row["shkzg"] == "C") {
          $cek_stock = $cStock->checkDeficitStock($mchb_cek);
          if($cek_stock["status"] === false) {
            $message = $cek_stock["message"];
            header("Location: ?action=$action&error=$message");
            die();
          }
        }
          
      }
      // jika aman, lanjut
      $mkpf = [];
      $mblnr_new = $cMatDoc->generateID();
      $mjahr_new = $mjahr;
      $mkpf["mblnr"] = $mblnr_new;
      $mkpf["mjahr"] = $mjahr_new;    
      $mkpf["budat"] = $_POST["budat"];
      $mkpf["crt_by"] = $_SESSION[LOGIN_SESSION];
      $mkpf["xblnr"] = $mblnr; //simpan nomor material document yang di reverse
      //insert header    
      $save = $cMatDoc->insertHeader($mkpf);
      if($save["status"] == true){
        $i = 0;
        foreach($get_data["mseg"] as $row) {
          //coba tambah atau kurangi stok dulu
          $mchb["matnr"] = $row["matnr"];
          $mchb["werks"] = $row["werks"];
          $mchb["lgort"] = $row["lgort"];
          $mchb["charg"] = $row["charg"];
          $mchb["clabs"] = $row["menge"]; //unrest stock
          $mchb["cinsm"] = 0; //QA Stock
          $mchb["chg_by"] = $_SESSION[LOGIN_SESSION];
          $mchb["crt_by"] = $_SESSION[LOGIN_SESSION];
          if($row["shkzg"] == "C") {
            $add_stock = $cStock->reduceStock($mchb); 
            $shkzg_new = "D";
          } else if($row["shkzg"] == "D") {
            $add_stock = $cStock->addStock($mchb);
            $shkzg_new = "C";
          }
          //create reversal movement type
          $bwart_lc = substr($row["bwart"],-1);
          $bwart_fc = substr($row["bwart"],0,2);
          $bwart_new = "";
          if($bwart_lc == "1") {
            $bwart_new = $bwart_fc."2";
          } else if($bwart_lc == "2") {
            $bwart_new = $bwart_fc."1";
          }
          if($add_stock["status"] == true) {
            $mseg["mblnr"] = $mblnr_new;
            $mseg["mjahr"] = $mjahr_new;
            $mseg["mblpo"] = $i+1;
            $mseg["matnr"] = $row["matnr"];
            $mseg["bwart"] = $bwart_new;
            $mseg["shkzg"] = $shkzg_new;
            $mseg["menge"] = $row["menge"];
            $mseg["werks"] = $row["werks"];
            $mseg["lgort"] = $row["lgort"];
            $mseg["charg"] = $row["charg"];
            $mseg["ebeln"] = $row["ebeln"];
            $mseg["aufnr"] = $row["aufnr"];
            $insert_mseg = $cMatDoc->insertItem($mseg);
            if($insert_mseg["status"] == true) {
              //continue process
            } else {
              $cMatDoc->rollBackMaterialDocument($mblnr, $mjahr);
              if($row["shkzg"] == "C") {
                $cStock->addStock($mchb);
              }else if($row["shkzg"] == "D") {
                $cStock->reduceStock($mchb); 
              }
              
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
        $message = "SUCCESS : Reversal done, Material Document $mblnr_new - $mjahr_new created";
        header("Location: ?action=$action&success=$message");
        die();
      } else {
        $message = $save["message"];
        header("Location: ?action=$action&error=$message");
        die();
      }
        
    }
  } else {
    require( TEMPLATE_PATH . "/t_mbst_1.php" );
  }
}
?>