<?php
if ($action == "daily_production_entry") {
  $template["group"] = "Production Entry";
  $template["menu"] = "Daily Production Entry";
  $data["list"];
  $class = new Production();
  $member = new Member();
  $dies = new Dies();
  $stop = new Stop();
  $user = new User();
  $material = new Material();
  if (isset($_GET["line"])) {
    $line = $_GET["line"];
    $date = $_GET["date"];
    $shift = $_GET["shift"];

    if (isset($_GET["prd_seq"])) {
      $seq = $_GET["prd_seq"];
      //lanjut ke step 3

      if (isset($_POST["save"])) {
        $param = $_POST;
        if (empty($param["prd_qty"])) {
          $param["prd_qty"] = 0;
        }

        if (empty($param["wip"])) {
          $param["wip"] = 0;
        }

        if (empty($param["dcqcp"])) {
          $param["dcqcp"] = 0;
        }

        if (empty($param["qaqcp"])) {
          $param["qaqcp"] = 0;
        }
        //update data stroke
        $prev_data = $class->getItemById($line, $date, $shift, $seq);
        $prev_dies_id = $prev_data["dies_id"];
        $prev_prd_qty = $prev_data["prd_qty"];
        if (empty($prev_prd_qty)) {
          $prev_prd_qty = 0;
        }

        $save = $class->updateItem($param);
        if ($save["status"] == true) {
          // $dies->updateStroke($prev_dies_id, $param["dies_id"], $prev_prd_qty, $param["prd_qty"]);
          $success = "Data Saved";
          header("Location: ?action=" . $action . "&line=" . $line . "&date=" . $date . "&shift=" . $shift . "&prd_seq=" . $seq . "&success=" . $success);
        } else {
          $error = $save["message"];
          header("Location: ?action=" . $action . "&line=" . $line . "&date=" . $date . "&shift=" . $shift . "&prd_seq=" . $seq . "&error=" . $error);
        }
      }
      $list_stop = $stop->getList('S', null);
      $list_action = $stop->getList('A');
      //$list_person = $class->getPersonById($line, $date, $shift);
      $list_person = $member->getList(null, "A");
      $data_item_dtl = $class->getItemById($line, $date, $shift, $seq);
      $data_stop = $class->getStopList($line, $date, $shift, $seq);
      $line_name = $data_item_dtl["line_name"];
      $date_time_start = $data_item_dtl["prd_dt"] . "%20" . $data_item_dtl["time_start"];
      $date_time_end = $data_item_dtl["prd_dt"] . "%20" . $data_item_dtl["time_end"];
      /*$data_scn = $class->getDataScan($line_name, $date_time_start, $date_time_end);*/

      $data_item_dtl["scn_qty_ok"] = 0;
      $data_item_dtl["scn_qty_ng"] = 0;
      /*if (!empty($data_scn["dcQty"])) {
        $data_item_dtl["scn_qty_ok"] = $data_scn["dcQty"];
      }
      if (!empty($data_scn["ngQty"])) {
        $data_item_dtl["scn_qty_ng"] = $data_scn["ngQty"];
      }*/

      $list_ng_type = $class->getNGType();
      $data_ng = $class->getNGList($line, $date, $shift, $seq);
      $template["submenu"] = $data_item_dtl["line_name"];

      $dies_list = $dies->getListDies($line, "A");
      $matlist = $material->getListMaterial();
      $exe_stop = $class->getStopExe($line, $date, $shift, $seq);
      require(TEMPLATE_PATH . "/t_production_entry_step3.php");
    } else {
      //check if data already created
      $is_exist = $class->isDailyHeaderExist($line, $date, $shift);
      if ($is_exist === true) {
        //lanjut ke step 2
        $data_header = $class->getHeaderById($line, $date, $shift);
        $data_item = $class->getListItemById($line, $date, $shift);
        $template["submenu"] = $data_header["line_name"];
        //cek apakah user ada role leader
        $op_role = "OPERATOR";
        $ld_list = $member->getList("LD");
        $jp_list = $member->getList("JP");
        $op_list = $member->getList("OP");
        $cek_user = $user->getUserRole($_SESSION[LOGIN_SESSION]);
        if (!empty($cek_user)) {
          foreach ($cek_user as $usr) {
            if ($usr == "LEADER") {
              $op_role = "LEADER";
              break;
            }
          }
        }
        if (isset($_POST["save"])) {
          $param = $_POST;
          $save = $class->updateHeader($line, $date, $shift, $param);
          if ($save["status"] == true) {
            $success = "Data Saved";
            header("Location: ?action=" . $action . "&line=" . $line . "&date=" . $date . "&shift=" . $shift . "&success=" . $success);
          } else {
            $error = $save["message"];
            header("Location: ?action=" . $action . "&line=" . $line . "&date=" . $date . "&shift=" . $shift . "&error=" . $error);
          }
        }

        if (isset($_GET["delete"])) {
          $save = $class->deletePrd($line, $date, $shift);
          if ($save["status"] == true) {
            $success = "Data Deleted";
            header("Location: ?action=" . $action . "&success=" . $success);
          } else {
            $error = "Data Failed to Delete";
            header("Location: ?action=" . $action . "&error=" . $error);
          }
        }
        //end of cek apakah user ada role leader
        require(TEMPLATE_PATH . "/t_production_entry_step2.php");
      } else {
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
            $get_item = $class->getItemTemplateByShift($shift);
            $jml_item = count($get_item);
            $planned_stops = $stop->getPlannedStopByShift($shift);
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
              header("Location: ?action=" . $action . "&line=" . $line . "&date=" . $date . "&shift=" . $shift);
            } else {
              $class->rollBackHeader($line, $date, $shift);
              $error = "Item - " . $save_item["message"];
              header("Location: ?action=" . $action . "&line=" . $line . "&date=" . $date . "&shift=" . $shift . "&error=" . $error);
            }
          } else {
            $error = "Header - " . $save_header["message"];
            header("Location: ?action=" . $action . "&line=" . $line . "&date=" . $date . "&shift=" . $shift . "&error=" . $error);
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
          require(TEMPLATE_PATH . "/t_production_entry_step1.php");
        }
      }
    }
  } else {
    $template["submenu"] = "Line Selection";
    //$data["list"] = $class->getList();
    $date = date("Ymd");
    if (isset($_GET["date"])) {
      $date = $_GET["date"];
    }
    $shift_ori = $class->getShiftOri();
    $shift = $shift_ori[0]["seq"];
    if (isset($_GET["shift"])) {
      $shift = $_GET["shift"];
    }
    $shift_list = $class->getListShift();
    $line_list = $class->getListLine();
    //check data
    $i = 0;
    foreach ($line_list as $row) {
      $cek_data_production = $class->isDailyHeaderExist($row["line_id"], $date, $shift);
      if ($cek_data_production === true) {
        $line_list[$i]["color"] = "btn-pale-green";
      } else {
        $line_list[$i]["color"] = "btn-dark-blue";
      }
      $i++;
    }
    require(TEMPLATE_PATH . "/t_production_entry.php");
  }
}