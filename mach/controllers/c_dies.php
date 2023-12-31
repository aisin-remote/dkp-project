<?php
if ($action == "dies_asset") {
  $template["group"] = "Master Data";
  $template["menu"] = "Dies Asset";
  $data["list"];
  $class = new Dies();

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

      $photo = $_FILES["img01"];
      $param["img01"] = $_POST["img01x"];

      if (!empty($photo["tmp_name"])) {
        $outputImage = "media/images/foto_dies.jpg";
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
            imagejpeg($dst, $outputImage, 100); // adjust format as needed
            imagedestroy($dst);
            $img64 = base64_encode(file_get_contents($outputImage));
            unlink($outputImage);
          } else {
            $file_name = $photo["tmp_name"];
            $src = imagecreatefromstring(file_get_contents($file_name));
            imagejpeg($src, $outputImage, 100);
            imagedestroy($src);
            $img64 = base64_encode(file_get_contents($outputImage));
            unlink($outputImage);
          }
          $param["img01"] = $img64;
        } else {
          $go_save = false;
          $message = "Gambar harus dalam format JPEG atau PNG";
        }
      }

      $save = array();
      if ($id == "0") {
        $save = $class->insertDies($param);
      } else {
        $save = $class->updateDies($param);
      }
      if ($save["status"] == true) {
        header("Location: ?action=" . $action . "&success=Data%20Saved");
      } else {
        header("Location: ?action=" . $action . "&id=" . $id . "&error=" . $save["message"]);
      }
    } else if (isset($_GET["delete"])) {
      $save = $class->delete($id);
      if ($save["status"] == true) {
        header("Location: ?action=" . $action . "&success=Asset%20Deleted");
      } else {
        header("Location: ?action=" . $action . "&error=" . $save["message"]);
      }
    } else {
      $group_list = $class->getDiesGroup();
      if ($id == "0") {
        $model_list = $class->getDiesModel(null, $group_list[0]["pval1"]);
        $data["data"] = array();
      } else {
        $data["data"] = $class->getDiesById($id);
        $model_list = $class->getDiesModel(null, $data["data"]["group_id"]);
      }
      $model_id = $_GET["model_id"];

      require(TEMPLATE_PATH . "/t_dies_edit.php");
    }
  } else {
    $data["list"] = $class->getListDies();
    require(TEMPLATE_PATH . "/t_dies_list.php");
  }
}

if ($action == "mach") {
  if (isset($_GET["id"])) {
    $id = $_GET["id"];

  } else {
    $data["list"] = $class->getAllMach();
    require(TEMPLATE_PATH . "/t_mach_list.php");
  }
}
