<?php

if ($action == "sloc") {
  $template["group"] = "Master Data";
  $template["menu"] = "Storage Location";
  $class = new StoreLocation();
  $plant = new Plant();
  if (isset($_GET["id"])) {
    $id = $_GET["id"];
    if (isset($_POST["save"])) {
      $param = $_POST;
      $param["crt_by"] = $_SESSION[LOGIN_SESSION];
      $param["chg_by"] = $_SESSION[LOGIN_SESSION];
      $save = array();
      if ($id == "0") {
        $save = $class->insert($param);
      } else {
        $save = $class->update($param);
      }
      if ($save["status"] == true) {
        header("Location: ?action=" . $action . "&success=Data%20Saved");
      } else {
        header("Location: ?action=" . $action . "&id=" . $id . "&error=" . $save["message"]);
      }
    } else if (isset($_GET["delete"])) {
      $save = array();
      //cek dulu apakah sudah dipakai di transaksi
      $cek = $class->isUsed($id);
      if ($cek > 0) {
        $save["status"] = false;
        $save["message"] = "Store Location sudah dipakai transaksi!";
      } else {
        $save = $class->delete($id);
      }

      if ($save["status"] == true) {
        header("Location: ?action=" . $action . "&success=Store Location%20Deleted");
      } else {
        header("Location: ?action=" . $action . "&error=" . $save["message"]);
      }
    } else {
      if ($id == "0") {
        $data["data"] = array();
      } else {
        $data["data"] = $class->getById($id);
      }
      $data["plants"] = $plant->getList();
      require( TEMPLATE_PATH . "/t_sloc_edit.php" );
    }
  } else if (isset($_GET['upload'])) {
    require_once 'classes/PHPExcel.php';
    require_once 'classes/PHPExcel/IOFactory.php';
    $fileName = $_FILES["excel"]["tmp_name"];
    try {
      $objPHPExcel = PHPExcel_IOFactory::load($fileName);
    } catch (Exception $e) {
      header("Location: ?action=" . $action . "&error=" . $e->getMessage());
    }
    $activeSheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
    $success = 0;
    $fail = 0;
    $processed = 0;
    $i = 0;
    if (!empty($activeSheetData)) {
      foreach ($activeSheetData as $row) {
        if ($i > 0) {
          //check if exist
          $param = array();
          if (empty($row["A"])) {
            break;
          }
          $param["werks"] = trim($row["A"]);
          $param["lgort"] = trim($row["B"]);
          $param["name1"] = trim($row["C"]);         
          $param["crt_by"] = $_SESSION[LOGIN_SESSION];
          $param["chg_by"] = $_SESSION[LOGIN_SESSION];

          if ($class->isExist($param["lgort"])) {
            $save = $class->update($param);
          } else {
            $save = $class->insert($param);
          }

          if ($save["status"] == true) {
            $success += 1;
          } else {
            $fail += 1;
          }
          $processed++;
        }
        $i++;
      }
    }
    $message = "Upload Complete [$success] data success, [$fail] data failed, [$processed] data processed";
    header("Location: ?action=" . $action . "&success=$message");
  } else {
    $data["list"] = $class->getList();
    require( TEMPLATE_PATH . "/t_sloc_list.php" );
  }
}
?>