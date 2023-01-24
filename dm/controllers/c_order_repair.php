<?php

if ($action == "order_repair") {
  $template["group"] = "Maintenance Activity";
  $template["menu"] = "Order Repair & Improvement";
  $data["list"];
  $class = new OrderRepair();
  $dies = new Dies();
  if (isset($_GET["id"])) {
    $id = $_GET["id"];
    if (isset($_POST["save"])) {
      $param = $_POST;
      $param["ori_id"] = $id;
      $param["crt_by"] = $_SESSION[LOGIN_SESSION];
      $param["chg_by"] = $_SESSION[LOGIN_SESSION];
      
      $photo = $_FILES["ori_doc"];
      $param["ori_doc"] = $_POST["ori_docx"];
      //var_dump($param); die();
      if (!empty($photo["tmp_name"])) {
        $outputImage = "media/images/foto_order_repair.jpg";
        if (file_exists($outputImage)) {
          unlink($outputImage);
        }
        if ($photo["type"] == "image/jpeg" || $photo["type"] == "image/jpg" || $photo["type"] == "image/png") {
          $maxDim = 1080;
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
          $param["ori_doc"] = $img64;
        } else {
          $go_save = false;
          $message = "Gambar harus dalam format JPEG atau PNG";
          header("Location: " . $action . "?id=" . $id . "&error=" . $message);
          die();
        }
      }
      $save = array();
      
      if ($id == "0") {
        $save = $class->insert($param);
      } else {
        $save = $class->update($param);
      }

      if ($save["status"] == true) {
        header("Location: " . $action . "?success=Data%20Saved");
      } else {
        header("Location: " . $action . "?error=" . $save["message"]);
      }
    } else {
      $type_list = $class->getType();
      $group_list = $dies->getDiesGroup();
      
      if($id == "0") {
        $template["submenu"] = "New";
        $model_list = $dies->getDiesModel(null, $group_list[0]["pval1"]);
        $dies_list = $dies->getListDies(null,$model_list[0]["model_id"]);
      } else {        
        $template["submenu"] = "Edit";
        $data["data"] = $class->getById($id);
        $model_list = $dies->getDiesModel(null, $data["data"]["group_id"]);
        $dies_list = $dies->getListDies(null,$data["data"]["model_id"]);
      }
      
      require(TEMPLATE_PATH . "/t_m_order_repair_edit.php");
    }
  } else {
    $template["submenu"] = "List";
    $data["list"] = $class->getList();
    require(TEMPLATE_PATH . "/t_m_order_repair.php");
  }
}
