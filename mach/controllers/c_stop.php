<?php
if ($action == "stop_reason_action") {
  $template["group"] = "Master Data";
  $template["menu"] = "Stop Reason Action";
  $template["submenu"] = "New";
  $data["list"];
  $class = new Stop();
  if (isset($_GET["upload"])) {
    require_once 'vendor/autoload.php';
    $spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();

    $fileName = $_FILES["excel"]["tmp_name"];
    try {
      $sheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($fileName);
    } catch (Exception $e) {
      header("Location: ?action=" . $action . "&error=" . $e->getMessage());
      exit();
    }
    $activeSheetData = $sheet->getActiveSheet()->toArray(null, true, true, true);
    $success = 0;
    $fail = 0;
    $processed = 0;
    $i = 0;
    // print("<pre>" . print_r($activeSheetData, true) . "</pre>");;
    // die();
    if (!empty($activeSheetData)) {
      foreach ($activeSheetData as $row) {
        if ($i > 0) {
          //check if exist
          $param = array();
          if (empty($row["A"])) {
            break;
          }
          $param["srna_id"] = $row["A"];
          $param["type1"] = $row["B"];
          $param["type2"] = $row["C"];
          $param["name1"] = $row["D"];
          $param["app_id"] = $row["E"];
          $param["type3"] = $row["F"];
          $save = $class->insertMass($param);

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
  }

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
    } else {
      if ($id == "0") {
        $data["data"] = array();
      } else {
        $data["data"] = $class->getById($id);
      }
      // $device_types = $class->getDeviceType();
      $type_list = $class->getListType();
      $type2_list = $class->getListType2();

      require(TEMPLATE_PATH . "/t_stop_reason_edit.php");
    }
  } else {
    $data["list"] = $class->getList();
    require(TEMPLATE_PATH . "/t_stop_reason.php");
  }
}
?>