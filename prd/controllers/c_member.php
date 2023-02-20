<?php
if ($action == "member_operator") {
  $template["group"] = "Master Data";
  $template["menu"] = "Member Operator";
  $template["submenu"] = "New";
  $data["list"];
  $class = new Member();

  if (isset($_GET['upload'])) {
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
          $param["empid"] = $row["A"];
          $param["name1"] = $row["B"];
          $param["role1"] = $row["C"];
          $param["crtby"] = $_SESSION[LOGIN_SESSION];
          $param["chgby"] = $_SESSION[LOGIN_SESSION];

          if ($class->isExist($row["A"])) {
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
  }

  if (isset($_POST["chg_status"])) {
    $all_id = $_POST["chk_id"];
    $extract_id = implode("','", $all_id);

    $update = $class->updateStatus($extract_id);

    if ($update["status"] == true) {
      header("Location: ?action=" . $action . "&success=Status%20Updated");
    } else {
      header("Location: ?action=" . $action . "&error=" . $update["message"]);
    }
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

      $role_list = $class->getListRole();
      $empid = $_GET["empid"];

      require(TEMPLATE_PATH . "/t_member_operator_edit.php");
    }
  } else {
    $data["list"] = $class->getList();
    require(TEMPLATE_PATH . "/t_member_operator.php");
  }
}
