<?php
if ($action == "checksheet_preventive") {
  $template["group"] = "Maintenance Activity";
  $template["menu"] = "Preventive Maintenance";
  $data["list"];
  $dies = new Dies();
  $class = new Checksheet();

  if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $step = $_GET["step"];

    if ($step == "1") {
      if (isset($_POST["save"])) {
        $param = $_POST;
        $param["pmtby"] = $_SESSION[LOGIN_SESSION];
        //cek dulu apakah dies sudah saatnya maintenance
        $cek_dies = $dies->getDiesById($param["dies_id"]);
        //pengecekan jika belum 2000
        /*if(floatval($cek_dies["stk2k"]) <= 2000) {
          $error = "Dies [".$cek_dies["group_id"]." | ".$cek_dies["model_id"]." | ".$cek_dies["dies_no"]." | ".str_replace("#"," ",$cek_dies["name1"])."] masih belum saatnya untuk dilakukan Preventive Maintenance";
          header("Location: " . $action . "?id=" . $id . "&step=1" . "&error=" . $error);
          die();
        }*/

        /*if(floatval($cek_dies["stk6k"]) >= 6000) {
          $param["pmtype"] = "6K";
        } else {
          $param["pmtype"] = "2K";
        }*/

        //cek apakah dies masih di preventive
        if ($cek_dies["gstat"] == "P") {
          header("Location: " . $action . "?id=" . $id . "&step=1" . "&error=Dies Masih Dalam Preventive Maintenance!");
          die();
        } else {
          //update status dies menjadi P
          $dies->updateDiesGStat($param["dies_id"], "P");
        }
        $save = array();
        $param["pmtid"] = $class->generateID();

        $save = $class->insert($param);

        if ($save["status"] == true) {
          header("Location: " . $action . "?id=" . $param["pmtid"] . "&step=2" . "&success=Data%20Saved");
          die();
        } else {
          header("Location: " . $action . "?id=" . $id . "&step=1" . "&error=" . $save["message"]);
          die();
        }
      } else {
        $group_list = $dies->getDiesGroup();
        $model_list = $dies->getDiesModel(null, $group_list[0]["pval1"]);
        $diesid_list = $dies->getListDies(null, "A", $group_list[0]["pval1"], $model_list[0]["model_id"]);

        $group_id = null;
        if (isset($_GET["group_id"])) {
          $group_id = $_GET["group_id"];
          $model_list = $dies->getDiesModel(null, $group_id);
        }
        $model_id = null;
        if (isset($_GET["model_id"])) {
          $model_id = $_GET["model_id"];
          $diesid_list = $dies->getListDies(null, "A", $group_id, $model_id);
        }
        $dies_id = null;
        if (isset($_GET["dies_id"])) {
          $dies_id = $_GET["dies_id"];
        }

        require(TEMPLATE_PATH . "/t_checksheet_step1.php");
      }
    } elseif ($step == "2") {
      if (isset($_POST["save"])) {
        $param["pmtid"] = $_POST["pmtid"];
        //process foto c11100
        $photo = $_FILES["c11100"];
        $param["group_id"] = $_POST["group_id"];
        $param["c11100"] = $_POST["c11100_x"];

        if (!empty($photo["tmp_name"])) {
          $outputImage = "media/images/foto_c11100.jpg";
          if (file_exists($outputImage)) {
            unlink($outputImage);
          }
          if ($photo["type"] == "image/jpeg" || $photo["type"] == "image/jpg" || $photo["type"] == "image/png") {
            $img64 = "";
            $file_name = $photo["tmp_name"];

            $file_name = $photo["tmp_name"];
            $src = imagecreatefromstring(file_get_contents($file_name));
            imagejpeg($src, $outputImage, 100);
            imagedestroy($src);
            $img64 = base64_encode(file_get_contents($outputImage));
            unlink($outputImage);

            $param["c11100"] = $img64;
          } else {
            $message = "Gambar [1.1.1] harus dalam format JPEG atau PNG";
            header("Location: " . $action . "?id=" . $id . "&step=2" . "&error=" . $save["message"]);
            die();
          }
        }
        //process foto c1161
        $photo = $_FILES["c1161"];
        $param["c1161"] = $_POST["c1161_x"];
        if (!empty($photo["tmp_name"])) {
          $outputImage = "media/images/foto_c1161.jpg";
          if (file_exists($outputImage)) {
            unlink($outputImage);
          }
          if ($photo["type"] == "image/jpeg" || $photo["type"] == "image/jpg" || $photo["type"] == "image/png") {
            $img64 = "";
            $file_name = $photo["tmp_name"];

            $file_name = $photo["tmp_name"];
            $src = imagecreatefromstring(file_get_contents($file_name));
            imagejpeg($src, $outputImage, 100);
            imagedestroy($src);
            $img64 = base64_encode(file_get_contents($outputImage));
            unlink($outputImage);

            $param["c1161"] = $img64;
          } else {
            $message = "Gambar [1.1.6.1	Check Flow Power Cool] harus dalam format JPEG atau PNG";
            header("Location: " . $action . "?id=" . $id . "&step=2" . "&error=" . $save["message"]);
            die();
          }
        }
        //process foto c1162
        $photo = $_FILES["c1162"];
        $param["c1162"] = $_POST["c1162_x"];
        if (!empty($photo["tmp_name"])) {
          $outputImage = "media/images/foto_c1162.jpg";
          if (file_exists($outputImage)) {
            unlink($outputImage);
          }
          if ($photo["type"] == "image/jpeg" || $photo["type"] == "image/jpg" || $photo["type"] == "image/png") {
            $img64 = "";
            $file_name = $photo["tmp_name"];

            $file_name = $photo["tmp_name"];
            $src = imagecreatefromstring(file_get_contents($file_name));
            imagejpeg($src, $outputImage, 100);
            imagedestroy($src);
            $img64 = base64_encode(file_get_contents($outputImage));
            unlink($outputImage);

            $param["c1162"] = $img64;
          } else {
            $message = "Gambar [1.1.6.2	Check Flow Main Cool] harus dalam format JPEG atau PNG";
            header("Location: " . $action . "?id=" . $id . "&step=2" . "&error=" . $save["message"]);
            die();
          }
        }
        //process foto c119
        $photo = $_FILES["c119"];
        $param["c119"] = $_POST["c119_x"];
        if (!empty($photo["tmp_name"])) {
          $outputImage = "media/images/foto_c119.jpg";
          if (file_exists($outputImage)) {
            unlink($outputImage);
          }
          if ($photo["type"] == "image/jpeg" || $photo["type"] == "image/jpg" || $photo["type"] == "image/png") {
            $img64 = "";
            $file_name = $photo["tmp_name"];

            $file_name = $photo["tmp_name"];
            $src = imagecreatefromstring(file_get_contents($file_name));
            imagejpeg($src, $outputImage, 100);
            imagedestroy($src);
            $img64 = base64_encode(file_get_contents($outputImage));
            unlink($outputImage);

            $param["c119"] = $img64;
          } else {
            $message = "Gambar [1.1.9	Check Bocor] harus dalam format JPEG atau PNG";
            header("Location: " . $action . "?id=" . $id . "&step=2" . "&error=" . $save["message"]);
            die();
          }
        }
        $param["dies_id"] = $_POST["dies_id"];
        $param["pmtype"] = $_POST["pmtype"];
        $param["pmtby"] = $_SESSION[LOGIN_SESSION];
        $param["jml_total"] = 0;
        $param["c11110"] = (isset($_POST["c11110"])) ? 1 : 0;
        $param["jml_total"] += $param["c11110"];
        $param["c11120"] = (isset($_POST["c11120"])) ? 1 : 0;
        $param["jml_total"] += $param["c11120"];
        $param["c11211"] = (isset($_POST["c11211"])) ? 1 : 0;
        $param["jml_total"] += $param["c11211"];
        $param["c11212"] = (isset($_POST["c11212"])) ? 1 : 0;
        $param["jml_total"] += $param["c11212"];
        $param["c11213"] = (isset($_POST["c11213"])) ? 1 : 0;
        $param["jml_total"] += $param["c11213"];
        $param["c11213_c1"] = (isset($_POST["c11213_c1"])) ? 1 : 0;
        $param["c11213_c2"] = (isset($_POST["c11213_c2"])) ? 1 : 0;
        $param["c11213_c3"] = (isset($_POST["c11213_c3"])) ? 1 : 0;
        $param["c11213_c4"] = (isset($_POST["c11213_c4"])) ? 1 : 0;
        $param["c11213_c5"] = (isset($_POST["c11213_c5"])) ? 1 : 0;
        $param["c11221"] = (isset($_POST["c11221"])) ? 1 : 0;
        $param["jml_total"] += $param["c11221"];
        $param["c11222"] = (isset($_POST["c11222"])) ? 1 : 0;
        $param["jml_total"] += $param["c11222"];
        $param["c11231"] = (isset($_POST["c11231"])) ? 1 : 0;
        $param["jml_total"] += $param["c11231"];
        $param["c11232"] = (isset($_POST["c11232"])) ? 1 : 0;
        $param["jml_total"] += $param["c11232"];
        $param["c11233"] = (isset($_POST["c11233"])) ? 1 : 0;
        $param["jml_total"] += $param["c11233"];
        $param["c11241"] = (isset($_POST["c11241"])) ? 1 : 0;
        $param["jml_total"] += $param["c11241"];
        $param["c11242"] = (isset($_POST["c11242"])) ? 1 : 0;
        $param["jml_total"] += $param["c11242"];
        $param["c11243"] = (isset($_POST["c11243"])) ? 1 : 0;
        $param["jml_total"] += $param["c11243"];
        $param["c11243_c1"] = (isset($_POST["c11243_c1"])) ? 1 : 0;
        $param["c11243_c2"] = (isset($_POST["c11243_c2"])) ? 1 : 0;
        $param["c11243_c3"] = (isset($_POST["c11243_c3"])) ? 1 : 0;
        $param["c11243_c4"] = (isset($_POST["c11243_c4"])) ? 1 : 0;
        $param["c11243_c5"] = (isset($_POST["c11243_c5"])) ? 1 : 0;
        $param["c11251"] = (isset($_POST["c11251"])) ? 1 : 0;
        $param["jml_total"] += $param["c11251"];
        $param["c11252"] = (isset($_POST["c11252"])) ? 1 : 0;
        $param["jml_total"] += $param["c11252"];
        $param["c11311"] = (isset($_POST["c11311"])) ? 1 : 0;
        $param["jml_total"] += $param["c11311"];
        $param["c11312"] = (isset($_POST["c11312"])) ? 1 : 0;
        $param["jml_total"] += $param["c11312"];
        $param["c11313"] = (isset($_POST["c11313"])) ? 1 : 0;
        $param["jml_total"] += $param["c11313"];
        $param["c11314"] = (isset($_POST["c11314"])) ? 1 : 0;
        $param["jml_total"] += $param["c11314"];
        $param["c11315"] = (isset($_POST["c11315"])) ? 1 : 0;
        $param["jml_total"] += $param["c11315"];
        $param["c11316"] = (isset($_POST["c11316"])) ? 1 : 0;
        $param["jml_total"] += $param["c11316"];
        $param["c113211"] = (isset($_POST["c113211"])) ? 1 : 0;
        $param["jml_total"] += $param["c113211"];
        $param["c113212"] = (isset($_POST["c113212"])) ? 1 : 0;
        $param["jml_total"] += $param["c113212"];
        $param["c11322"] = (isset($_POST["c11322"])) ? 1 : 0;
        $param["jml_total"] += $param["c11322"];
        $param["c11323"] = (isset($_POST["c11323"])) ? 1 : 0;
        $param["jml_total"] += $param["c11323"];
        $param["c1141"] = (isset($_POST["c1141"])) ? 1 : 0;
        $param["jml_total"] += $param["c1141"];
        $param["c1142"] = (isset($_POST["c1142"])) ? 1 : 0;
        $param["jml_total"] += $param["c1142"];
        $param["c1143"] = (isset($_POST["c1143"])) ? 1 : 0;
        $param["jml_total"] += $param["c1143"];
        $param["c1143_c1"] = (isset($_POST["c1143_c1"])) ? 1 : 0;
        $param["c1143_c2"] = (isset($_POST["c1143_c2"])) ? 1 : 0;
        $param["c1143_c3"] = (isset($_POST["c1143_c3"])) ? 1 : 0;
        $param["c1143_c4"] = (isset($_POST["c1143_c4"])) ? 1 : 0;
        $param["c1143_c5"] = (isset($_POST["c1143_c5"])) ? 1 : 0;
        $param["c1151"] = (isset($_POST["c1151"])) ? 1 : 0;
        $param["jml_total"] += $param["c1151"];
        $param["c1152"] = (isset($_POST["c1152"])) ? 1 : 0;
        $param["jml_total"] += $param["c1152"];
        $param["c1152_c1"] = (isset($_POST["c1152_c1"])) ? 1 : 0;
        $param["c1152_c2"] = (isset($_POST["c1152_c2"])) ? 1 : 0;
        $param["c1152_c3"] = (isset($_POST["c1152_c3"])) ? 1 : 0;
        $param["c1152_c4"] = (isset($_POST["c1152_c4"])) ? 1 : 0;
        $param["c1152_c5"] = (isset($_POST["c1152_c5"])) ? 1 : 0;
        $param["c1153"] = (isset($_POST["c1153"])) ? 1 : 0;
        $param["jml_total"] += $param["c1153"];
        $param["c11611"] = (isset($_POST["c11611"])) ? 1 : 0;
        $param["jml_total"] += $param["c11611"];
        $param["c11612"] = (isset($_POST["c11612"])) ? 1 : 0;
        $param["jml_total"] += $param["c11612"];
        $param["c11621"] = (isset($_POST["c11621"])) ? 1 : 0;
        $param["jml_total"] += $param["c11621"];
        $param["c11622"] = (isset($_POST["c11622"])) ? 1 : 0;
        $param["jml_total"] += $param["c11622"];
        $param["c117"] = (isset($_POST["c117"])) ? 1 : 0;
        $param["c1181"] = (isset($_POST["c1181"])) ? 1 : 0;
        $param["c1182"] = (isset($_POST["c1182"])) ? 1 : 0;
        $param["c1183"] = (isset($_POST["c1183"])) ? 1 : 0;
        $param["c1184"] = (isset($_POST["c1184"])) ? 1 : 0;
        $param["c1185"] = (isset($_POST["c1185"])) ? 1 : 0;
        $param["c11911"] = (isset($_POST["c11911"])) ? 1 : 0;
        $param["c11912"] = (isset($_POST["c11912"])) ? 1 : 0;
        $param["c11913"] = (isset($_POST["c11913"])) ? 1 : 0;
        $param["c11914"] = (isset($_POST["c11914"])) ? 1 : 0;
        $param["c11921"] = (isset($_POST["c11921"])) ? 1 : 0;
        $param["c11922"] = (isset($_POST["c11922"])) ? 1 : 0;
        $param["c11923"] = (isset($_POST["c11923"])) ? 1 : 0;
        $param["c11924"] = (isset($_POST["c11924"])) ? 1 : 0;

        // var_dump($param); jika jml_tot sudah 49 maka complete
        // die();
        $save = array();
        //echo $param["group_id"] . " - " . $param["jml_total"]; die();
        $param["pmstat"] = "N";
        if ($param["group_id"] == "CSH") {
          if ($param["jml_total"] >= 25) {
            $param["pmstat"] = "C";
            //jika close update kembali gstat menjadi N
            $dies->updateDiesGStat($param["dies_id"], "N");
          }
        } else {
          if ($param["jml_total"] >= 35) {
            $param["pmstat"] = "C";
            //jika close update kembali gstat menjadi N
            $dies->updateDiesGStat($param["dies_id"], "N");
          }
        }

        $save = $class->updateChecksheet($param);

        if ($save["status"] == true) {
          header("Location: " . $action . "?success=Data%20Saved");
        } else {
          header("Location: " . $action . "?id=" . $id . "&step=2" . "&error=" . $save["message"]);
        }
      } else {
        $template["submenu"] = $id;
        $data["data"] = $class->getChecksheetById($id);

        require(TEMPLATE_PATH . "/t_checksheet_step2.php");
      }
    }
  } else {

    $data["list"] = $class->getList("N");

    require(TEMPLATE_PATH . "/t_checksheet_list.php");
  }
}
