<?php
if ($action == "user") {
  $template["group"] = "Settings";
  $template["menu"] = "User";
  $class = new User();
  $role = new Role();
  $data["list"];

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
          $param["usrid"] = $row["A"];
          $param["name1"] = $row["B"];
          $param["usrpw"] = $row["C"];
          $param["user_role"] = explode(",", $row["D"]);
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

  if (isset($_GET["id"])) {
    $id = $_GET["id"];
    if (isset($_POST["save"])) {
      $param = $_POST;
      $param["user_role"] = $_POST["roles"];
      $param["user_device"] = $_POST["devices"];
      $save = array();

      if (isset($_POST["stats"])) {
        $param["stats"] = "A";
      } else {
        $param["stats"] = "I";
      }

      if (!empty($_POST["password1"])) {
        if ($_POST["password1"] == $_POST["password2"]) {
          $param["usrpw"] = $_POST["password1"];
        } else {
          header("Location: ?action=" . $action . "&id=" . $id . "&error=Password%20Missmatch");
          die();
        }
      }

      $photo = $_FILES["foto_ktp"];

      if (empty($photo)) {
        $param["foto_ktp"] = $_POST["foto_ktp_ori"];
      }

      if (!empty($photo["tmp_name"])) {
        $outputImage = "media/foto_ktp.jpg";
        if (file_exists($outputImage)) {
          unlink($outputImage);
        }
        if ($photo["type"] == "image/jpeg" || $photo["type"] == "image/jpg" || $photo["type"] == "image/png") {
          $maxDim = 800;
          $img64 = "";
          $file_name = $photo["tmp_name"];
          list($width, $height, $type, $attr) = getimagesize($file_name);
          if ($width > $maxDim || $height > $maxDim) {
            $target_filename = $file_name;
            $ratio = $width / $height;
            if ($ratio > 1) {
              $new_width = $maxDim;
              $new_height = $maxDim / $ratio;
            } else {
              $new_width = $maxDim * $ratio;
              $new_height = $maxDim;
            }
            $src = imagecreatefromstring(file_get_contents($file_name));
            $dst = imagecreatetruecolor($new_width, $new_height);
            imagecopyresampled($dst, $src, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
            imagedestroy($src);
            imagejpeg($dst, $outputImage, 60); // adjust format as needed
            imagedestroy($dst);
            $img64 = base64_encode(file_get_contents($outputImage));
            unlink($outputImage);
          } else {
            $file_name = $photo["tmp_name"];
            $src = imagecreatefromstring(file_get_contents($file_name));
            imagejpeg($src, $outputImage, 60);
            imagedestroy($src);
            $img64 = base64_encode(file_get_contents($outputImage));
            unlink($outputImage);
          }
          $param["foto_ktp"] = $img64;
        } else {
          $go_save = false;
          $message = "Gambar harus dalam format JPEG atau PNG";
        }
      }

      if ($id == "0") {
        $param["crt_by"] = $_SESSION[LOGIN_SESSION];
        $save = $class->insert($param);
      } else {
        $param["chg_by"] = $_SESSION[LOGIN_SESSION];
        $save = $class->update($param);
      }
      if ($save["status"] == true) {
        header("Location: ?action=" . $action . "&success=Data%20Saved");
      } else {
        header("Location: ?action=" . $action . "&id=" . $id . "&error=" . $save["message"]);
      }
    } else {
      $data["user_role"] = array();
      if ($id == "0") {
        $data["data"] = array();
      } else {
        $data["data"] = $class->getById($id);
        $data["user_role"] = $class->getUserRole($id);
      }
      $data["role"] = $role->getList();
      require(TEMPLATE_PATH . "/t_user_edit.php");
    }
  } else {
    $data["list"] = $class->getList();
    require(TEMPLATE_PATH . "/t_user_list.php");
  }
}
