<?php
if ($action == "dies_model") {
  $template["group"] = "Master Data";
  $template["menu"] = "Dies Model";
  $data["list"];
  $class = new Dies();
  $production = new Production();
  if (isset($_GET["id"])) {
    $id = $_GET["id"];
    if (isset($_POST["save"])) {
      $param = $_POST;
      $param["crt_by"] = $_SESSION[LOGIN_SESSION];
      $param["chg_by"] = $_SESSION[LOGIN_SESSION];

      $photo = $_FILES["img01"];
      $param["img"] = $_POST["img01x"];

      if (!empty($photo["tmp_name"])) {
        $outputImage = "media/images/foto_dies_model.jpg";
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
          $param["img"] = $img64;
        } else {
          $go_save = false;
          $message = "Gambar harus dalam format JPEG atau PNG";
        }
      }

      $save = array();
      if ($id == "0") {
        $save = $class->insertModel($param);
      } else {
        $save = $class->updateModel($param);
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

        $group_id = $_GET["id2"];
        $data["data"] = $class->getModelById($id, $group_id);
      }
      // $device_types = $class->getDeviceType();
      $model_id = $_GET["id"];
      $group_id = $_GET["id2"];
      $group_list = $class->getDiesGroup();
      $line_list = $production->getListLine();
      $data["line"] = $class->getListLine();
      $data["dies_line"] = $class->getDiesLine($model_id, $group_id);
      $data["line_name"] = $class->getLineName();

      // var_dump($data["line_name"]);
      // die();
      require(TEMPLATE_PATH . "/t_dies_model_edit.php");
    }
  } else {
    $data["list"] = $class->getListModel();
    require(TEMPLATE_PATH . "/t_dies_model_list.php");
  }
}
