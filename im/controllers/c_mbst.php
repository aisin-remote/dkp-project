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
      $message = urlencode("Reversal Document Cannot be Reversed Again!!!");
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
        $cek_stock = $cStock->checkDeficitStock($mchb_cek);
        if($cek_stock["status"] === false) {
          $message = $cek_stock["message"];
          header("Location: ?action=$action&error=$message");
          die();
        }
      }
       
    }
  } else {
    require( TEMPLATE_PATH . "/t_mbst_1.php" );
  }
}
?>