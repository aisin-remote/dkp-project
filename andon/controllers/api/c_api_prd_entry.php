<?php
if ($action == "api_prd_entry") {
    $class = new Production();
    $member = new Member();
    $dies = new Dies();
    $stop = new Stop();
    $user = new User();
    $material = new Material();
    // $zona = new Zona();
    if (isset($_GET["line"])) {
        $line = $_GET["line"];
        $date = $_GET["date"];
        $shift = $_GET["shift"];

        //step 1 bila belum ada data
        if (isset($_POST["save"])) {
            //mulai generate data
            $param = $_POST;
            // print_r($param);
            // die();
            // $dies->updateZonaByLine($param["line_id"]);
            $save_header = $class->insertHeader($param);
            if ($save_header["status"] == true) {
                //insert item
                $cctime = $param["cctime"];
                $dies_id = $param["dies_id"];
                //update zona dies sesuai line id
                // $dies->updateZonaId($param["dies_id"], $param["line_id"]);
                $get_item = $class->getItemTemplateByShift($param["shift"]);
                $jml_item = count($get_item);
                $planned_stops = $stop->getPlannedStopByShift($param["shift"]);
                $cctime_per_jam = $cctime; // / $jml_item;

                $total_target = $param["total_target"];
                //$target_per_jam = $total_target / $jml_item;
                $param_item = array();
                $i = 0;
                foreach ($get_item as $itm) {
                    $param_item[$i]["line_id"] = $param["line_id"];
                    $param_item[$i]["prd_dt"] = $param["prd_dt"];
                    $param_item[$i]["shift"] = $param["shift"];
                    $param_item[$i]["prd_seq"] = $itm["time_id"];
                    $param_item[$i]["dies_id"] = $dies_id;
                    $param_item[$i]["time_start"] = $itm["time_start"];
                    $param_item[$i]["time_end"] = $itm["time_end"];
                    $param_item[$i]["date_add"] = $itm["date_add"];
                    $param_item[$i]["cctime"] = $cctime_per_jam;

                    //calculate pengurang production time
                    $prd_time = floatval($itm["prd_time"]);
                    $stop_time = 0;
                    foreach ($planned_stops as $ps) {
                        if ($itm["time_id"] == $ps["time_id"]) {
                            $stop_time += floatval($ps["stop_time"]);
                        }
                    }
                    $prd_time -= $stop_time;
                    $param_item[$i]["prd_time"] = $prd_time;
                    //di formulasikan lagi dari production time
                    $target_per_jam = ($prd_time * 60) / floatval($cctime);
                    $param_item[$i]["pln_qty"] = round($target_per_jam, 0, PHP_ROUND_HALF_UP);
                    $i++;
                }
                $save_item = $class->insertItem($param_item);
                if ($save_item["status"] == true) {
                    //auto insert planned stop
                    foreach ($planned_stops as $ps) {
                        $param_stop = [];
                        $param_stop["line_id"] = $param["line_id"];
                        $param_stop["prd_dt"] = $param["prd_dt"];
                        $param_stop["shift"] = $param["shift"];
                        $param_stop["prd_seq"] = $ps["time_id"];
                        $param_stop["start_time"] = $ps["start_time"];
                        $param_stop["end_time"] = $ps["end_time"];
                        $param_stop["stop_time"] = $ps["stop_time"];
                        $param_stop["qty_stc"] = "0";
                        $param_stop["stop_id"] = $ps["srna_id"];
                        $param_stop["action_id"] = null;
                        $param_stop["exe_empid"] = null;
                        $class->insertStop($param_stop);
                    }
                    header("Location: ".$param["reff"]."");
                } else {
                    $class->rollBackHeader($line, $date, $param["shift"]);
                    $error = "Item - " . $save_item["message"];
                    header("Location: ".$param["reff"]."&error=" . $error);
                }
            } else {
                $error = "Header - " . $save_header["message"];
                header("Location: ".$param["reff"]."&error=" . $error);
            }
        } else {
            $ld_list = $member->getList("LD");
            $jp_list = $member->getList("JP");
            $op_list = $member->getList("OP");
            $line_data = $class->getLineById($line);
            $template["submenu"] = $line_data["name1"];
            $shift_list = $class->getListShift();
            $shift_count = $class->getShiftCount($shift);
            $dies_list = $dies->getListDies($line, "A");
            $matlist = $material->getListMaterial();
            $shift_ori = $class->getShiftOri();
            require(TEMPLATE_PATH . "/t_production_entry_new.php");
        }
    }
}

if ($action == "shift_count") {
    $shift = $_REQUEST["shift"];
    $return = 0;
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT count(*) as cnt FROM m_prd_shift "
      . "WHERE shift_id = '$shift' AND app_id = '" . APP . "' ";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute()) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $return = $row["cnt"];
      }
    }
    $stmt = null;
    $conn = null;
    echo $return;
}

// if ($action == "api_create_prd") {
//     $class = new Production();
//     $member = new Member();
//     $dies = new Dies();
//     $stop = new Stop();
//     $user = new User();
//     $material = new Material();

//     $return = array();
//     $line = $_GET["line"];
//     $shift = $_GET["shift"];
//     $date = date("Ymd");
//     if (isset($_REQUEST["save"])) {
//         //mulai generate data
//         $param = $_REQUEST;
//         // print_r($param);
//         // die();
//         // $dies->updateZonaByLine($param["line_id"]);
//         $save_header = $class->insertHeader($param);
//         if ($save_header["status"] == true) {
//             //insert item
//             $cctime = $param["cctime"];
//             $dies_id = $param["dies_id"];
//             //update zona dies sesuai line id
//             // $dies->updateZonaId($param["dies_id"], $param["line_id"]);
//             $get_item = $class->getItemTemplateByShift($shift);
//             $jml_item = count($get_item);
//             $planned_stops = $stop->getPlannedStopByShift($shift);
//             $cctime_per_jam = $cctime; // / $jml_item;

//             $total_target = $param["total_target"];
//             //$target_per_jam = $total_target / $jml_item;
//             $param_item = array();
//             $i = 0;
//             foreach ($get_item as $itm) {
//                 $param_item[$i]["line_id"] = $param["line_id"];
//                 $param_item[$i]["prd_dt"] = $param["prd_dt"];
//                 $param_item[$i]["shift"] = $param["shift"];
//                 $param_item[$i]["prd_seq"] = $itm["time_id"];
//                 $param_item[$i]["dies_id"] = $dies_id;
//                 $param_item[$i]["time_start"] = $itm["time_start"];
//                 $param_item[$i]["time_end"] = $itm["time_end"];
//                 $param_item[$i]["cctime"] = $cctime_per_jam;

//                 //calculate pengurang production time
//                 $prd_time = floatval($itm["prd_time"]);
//                 $stop_time = 0;
//                 foreach ($planned_stops as $ps) {
//                     if ($itm["time_id"] == $ps["time_id"]) {
//                         $stop_time += floatval($ps["stop_time"]);
//                     }
//                 }
//                 $prd_time -= $stop_time;
//                 $param_item[$i]["prd_time"] = $prd_time;
//                 //di formulasikan lagi dari production time
//                 $target_per_jam = ($prd_time * 60) / floatval($cctime);
//                 $param_item[$i]["pln_qty"] = round($target_per_jam, 0, PHP_ROUND_HALF_UP);
//                 $i++;
//             }
//             // print("<pre>" . print_r($param_item, true) . "</pre>");
//             // die();
//             $save_item = $class->insertItem($param_item);
//             if ($save_item["status"] == true) {
//                 //auto insert planned stop
//                 foreach ($planned_stops as $ps) {
//                     $param_stop = [];
//                     $param_stop["line_id"] = $param["line_id"];
//                     $param_stop["prd_dt"] = $param["prd_dt"];
//                     $param_stop["shift"] = $param["shift"];
//                     $param_stop["prd_seq"] = $ps["time_id"];
//                     $param_stop["start_time"] = $ps["start_time"];
//                     $param_stop["end_time"] = $ps["end_time"];
//                     $param_stop["stop_time"] = $ps["stop_time"];
//                     $param_stop["qty_stc"] = "0";
//                     $param_stop["stop_id"] = $ps["srna_id"];
//                     $param_stop["action_id"] = null;
//                     $param_stop["exe_empid"] = null;
//                     $class->insertStop($param_stop);
//                 }
//                 $return["status"] = true;
//             } else {
//                 $class->rollBackHeader($line, $date, $shift);
//                 $return["status"] = false;
//                 $return["message"] = "insert item fail";
//             }
//         } else {
//             $return["status"] = false;
//             $return["message"] = "insert header fail";
//         }
//     }
//     echo json_encode($return);
// }
?>