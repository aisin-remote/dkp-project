<?php

if ($action == "order_repair") {
  $template["group"] = "Maintenance Activity";
  $template["menu"] = "Order Repair & Improvement";
  $data["list"];
  $class = new OrderRepair();
  $dies = new Dies();
  $zona = new Zona();
  $dstk = new DiesStrokeHistory();
  if (isset($_GET["id"])) {
    $id = $_GET["id"];
    if (isset($_POST["save"])) {
      $param = $_POST;
      $param["ori_id"] = $id;
      $param["crt_by"] = $_SESSION[LOGIN_SESSION];
      $param["chg_by"] = $_SESSION[LOGIN_SESSION];

      $photo = $_FILES["ori_doc"];
      $photo1 = $_FILES["ori_a3"];
      $param["ori_doc"] = $_POST["ori_docx"];
      $param["ori_a3"] = $_POST["ori_a3x"];
      // print_r($photo1);
      // die();
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
          header("Location: ?action=" . $action . "&id=" . $id . "&error=" . $message);
          die();
        }
      }

      if (!empty($photo1["tmp_name"])) {
        $outputImage1 = "media/images/foto_order_repair.jpg";
        if (file_exists($outputImage1)) {
          unlink($outputImage1);
        }
        if ($photo1["type"] == "image/jpeg" || $photo1["type"] == "image/jpg" || $photo1["type"] == "image/png") {
          $maxDim = 1080;
          $img641 = "";
          $file_name1 = $photo1["tmp_name"];
          list($width, $height, $type, $attr) = getimagesize($file_name1);
          if ($width > $maxDim || $height > $maxDim) {
            $target_filename1 = $file_name1;
            $ratio = $width / $height;
            if ($ratio > 1) {
              $new_width = $maxDim;
              $new_height = $maxDim / $ratio;
            } else {
              $new_width = $maxDim * $ratio;
              $new_height = $maxDim;
            }
            $src1 = imagecreatefromstring(file_get_contents($file_name1));
            $dst1 = imagecreatetruecolor($new_width, $new_height);
            imagecopyresampled($dst1, $src1, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
            imagedestroy($src1);
            imagejpeg($dst1, $outputImage1, 100); // adjust format as needed
            imagedestroy($dst1);
            $img641 = base64_encode(file_get_contents($outputImage1));
            unlink($outputImage1);
          } else {
            $file_name1 = $photo1["tmp_name"];
            $sr1c = imagecreatefromstring(file_get_contents($file_name1));
            imagejpeg($src1, $outputImage1, 100);
            imagedestroy($src1);
            $img641 = base64_encode(file_get_contents($outputImage1));
            unlink($outputImage1);
          }
          $param["ori_a3"] = $img641;
        } else {
          $go_save = false;
          $message = "Gambar harus dalam format JPEG atau PNG";
          header("Location: ?action=" . $action . "&id=" . $id . "&error=" . $message);
          die();
        }
      }

      // print_r($param);
      // die();
      $zone_used = $zona->isUsed($param["zona1"], $param["dies_id"]);
      if ($zone_used["count"] > 0) {
        header("Location: ?action=" . $action . "&id=" . $id . "&error=Zona Maintenance [" . $zone_used["desc"] . "] Sedang Dipakai!");
        die();
      }
      $save = array();
      $param["stats"] = (isset($_POST["status"])) ? 1 : 0;
      $diesGstat = $dies->getDiesById($param["dies_id"]);

      if ($id == "0") {
        // echo $diesGstat;
        // die();
        if ($diesGstat["gstat"] == "P") {
          header("Location: ?action=" . $action . "&error=Dies%20sedang%20dipreventive%20maintenance!");
          die();
        } else if ($diesGstat["gstat"] == "PC") {
          header("Location: ?action=" . $action . "&error=Dies%20sedang%20pergantian%20part!");
          die();
        } else {
          $dies->updateDiesGStat($param["dies_id"], "R");
          $save = $class->insert($param);
          if($save["status"] == true) {
            $param_hist = [];
            $param_hist["sthty"] = "R";
            $param_hist["dies_id"] = $param["dies_id"];
            $param_hist["crt_by"] = $_SESSION[LOGIN_SESSION];
            $insert_hist = $dstk->insert($param_hist);
          }
        }
      } else {

        if ($param["stats"] == 1) {
          $dies->updateDiesGStat($param["dies_id"], "N");
          $save = $class->update($param);
        } else {
          $save = $class->update($param);
        }

      }

      if ($save["status"] == true) {
        header("Location: ?action=" . $action . "&success=Data%20Saved");
      } else {
        header("Location: ?action=" . $action . "&error=" . $save["message"]);
      }
    } else {
      $type_list = $class->getType();
      $group_list = $dies->getDiesGroup();

      if ($id == "0") {
        $template["submenu"] = "New";
        $model_list = $dies->getDiesModel(null, $group_list[0]["pval1"]);
        $dies_list = $dies->getListDies(null, $model_list[0]["model_id"]);
        $list_zona = $zona->getList();
        require(TEMPLATE_PATH . "/t_m_order_repair_edit.php");
      } else {
        $template["submenu"] = "Edit";
        $data["data"] = $class->getById($id);

        $model_list = $dies->getDiesModel(null, $data["data"]["group_id"]);
        $dies_list = $dies->getListDies(null, null, $data["data"]["group_id"], $data["data"]["model_id"]);
        $list_zona = $zona->getList();
        require(TEMPLATE_PATH . "/t_m_order_repair_edit.php");
      }

    }
  } else {
    $template["submenu"] = "List";
    $data["list"] = $class->getList(0);
    require(TEMPLATE_PATH . "/t_m_order_repair.php");
  }
}