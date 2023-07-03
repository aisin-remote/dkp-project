<?php

if ($action == "mara") {
  $template["group"] = "Master Data";
  $template["menu"] = "Master Material";
  $class = new Material();
  $cSloc = new StoreLocation();
  if (isset($_GET["id"])) {
    $id = $_GET["id"];
    if (isset($_POST["save"])) {
      $param = $_POST;
      $param["crt_by"] = $_SESSION[LOGIN_SESSION];
      $param["chg_by"] = $_SESSION[LOGIN_SESSION];
      if(empty($param["cctime"])) {
        $param["cctime"] = 0;
      }
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
        $save["message"] = "Material sudah dipakai transaksi!";
      } else {
        $save = $class->delete($id);
      }

      if ($save["status"] == true) {
        header("Location: ?action=" . $action . "&success=Material%20Deleted");
      } else {
        header("Location: ?action=" . $action . "&error=" . $save["message"]);
      }
    } else {
      if ($id == "0") {
        $data["data"] = array();
      } else {
        $data["data"] = $class->getById($id);
      }
      $data["mtarts"] = $class->getType();
      $data["matkls"] = $class->getGroup();
      $data["lgorts"] = $cSloc->getList("JE10");
      require( TEMPLATE_PATH . "/t_mara_edit.php" );
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
          $param["mtart"] = trim($row["A"]);
          $param["matkl"] = trim($row["B"]);
          $param["matnr"] = trim($row["C"]);
          $param["ematn"] = trim($row["D"]);
          $param["name1"] = trim($row["E"]);
          $param["meins"] = trim($row["F"]);
          $param["cctime"] = trim($row["G"]);
          $param["crt_by"] = $_SESSION[LOGIN_SESSION];
          $param["chg_by"] = $_SESSION[LOGIN_SESSION];

          if ($class->isExist($param["matnr"])) {
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
    $data["list"] = $class->getListMara();
    require( TEMPLATE_PATH . "/t_mara_list.php" );
  }
}
?>