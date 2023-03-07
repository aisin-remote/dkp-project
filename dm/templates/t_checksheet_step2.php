<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>

<head>
  <?php include "common/t_css.php"; ?>
  <link href="vendors/ega/css/styles.css" rel="stylesheet" type="text/css" />
</head>

<body>
  <?php include "common/t_nav_top.php"; ?>
  <div id="layoutSidenav">
    <?php include "common/t_nav_left.php"; ?>
    <div id="layoutSidenav_content">
      <main>
        <div class="container-fluid">
          <ol class="breadcrumb mb-2 mt-4">
            <li class="breadcrumb-item">
              <?php echo $template["group"]; ?>
            </li>
            <li class="breadcrumb-item active">
              <?php echo $template["menu"]; ?>
            </li>
          </ol>
          <?php
          if (isset($_GET["error"])) {
            echo '<div class="alert alert-danger alert-dismissible" role="alert">
                      Error : ' . $_GET["error"] . '
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>';
          }
          ?>
          <form method="post" id="my-form" action="?action=<?php echo $action; ?>&id=<?php echo $id; ?>&step=2"
            enctype="multipart/form-data">

            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-body">
                    <input type="hidden" name="group_id" class="form-control" maxlength="100"
                      value="<?php echo $data["data"]["group_id"]; ?>">
                    <div class="d-flex justify-content-end">
                      <input type="hidden" name="save" value="save">
                      <button type="submit" type="button" name="btn-save" id="btn-save"
                        class="btn btn-dark-blue btn-sm px-5 mx-2">Save</button>
                      <div class="dropdown mr-2">
                        <button class="btn btn-sm btn-dark-blue dropdown-toggle" type="button" data-toggle="dropdown"
                          aria-expanded="false">
                          Dies History
                        </button>
                        <div class="dropdown-menu">
                          <a class="dropdown-item"
                            href="?action=r_checksheet_preventive&dies_id=<?= $data["data"]["dies_id"]; ?>">Checksheet
                            Preventive</a>
                          <a class="dropdown-item"
                            href="?action=r_pergantian_part&dies_id=<?= $data["data"]["dies_id"]; ?>">Pergantian
                            Part</a>
                          <a class="dropdown-item"
                            href="?action=r_order_repair_and_improvement&dies_id=<?= $data["data"]["dies_id"]; ?>">Order
                            Repair
                            and Improvement</a>
                          <a class="dropdown-item"
                            href="?action=r_stroke_total_dies&dies_id=<?= $data["data"]["dies_id"]; ?>">Stroke Total
                            Dies</a>
                        </div>
                      </div>
                      <button type="button" class="btn btn-dark-blue btn-sm px-4" id="btn-print">Print
                        Checksheet</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-12">
                <div class="card mt-2">
                  <div class="card-body py-1 px-2">
                    <table class="table table-sm table-borderless my-1">
                      <thead style="background-color: #E4E4E4;">
                        <tr>
                          <th class="align-middle px-3 table-header col-2" scope="col">Header Info.</th>
                          <th class="align-middle px-3 table-header col-3" scope="col"></th>
                          <th class="align-middle px-3 table-header" scope="col">
                            <?= $data["data"]["group_id"] ?>
                            <?= $data["data"]["model_id"] ?>
                            <?= $data["data"]["dies_no"] ?>
                          </th>
                        </tr>
                      </thead>
                      <input type="hidden" id="group" value="<?= $data["data"]["group_id"] ?>" />
                      <tbody>
                        <tr>
                          <td class="align-middle px-3 table-item">Preventive No.</td>
                          <td class="align-middle px-3 table-item">
                            <input class="form-control form-control-sm" name="pmtid" id="pmtid" type="text"
                              value="<?php echo $data["data"]["pmtid"]; ?>" readonly>
                          </td>
                          <td class="align-middle px-3 table-item"></td>
                        </tr>
                        <tr>
                          <td class="align-middle px-3 table-item">Maintenance Date</td>
                          <td class="align-middle px-3 table-item">
                            <input class="form-control form-control-sm" name="pmtdt" type="text"
                              value="<?php echo $data["data"]["pmt_date"]; ?>" readonly>
                          </td>
                          <td class="align-middle px-3 table-item"></td>
                        </tr>
                        <tr>
                          <td class="align-middle px-3 table-item">Stroke</td>
                          <td class="align-middle px-3 table-item">
                            <input class="form-control form-control-sm" name="pmtstk" type="text"
                              value="<?php echo $data["data"]["pmtstk"]; ?>" readonly>
                          </td>
                          <td class="align-middle px-3 table-item"></td>
                        </tr>
                        <tr>
                          <td class="align-middle px-3 table-item">Preventive Type</td>
                          <td class="align-middle px-3 table-item">
                            <input type="hidden" name="pmtype" value="<?php echo $data["data"]["pmtype"]; ?>">
                            <input type="hidden" name="dies_id" value="<?php echo $data["data"]["dies_id"]; ?>">
                            <input class="form-control form-control-sm" id="pmtype" name="pm_type" type="text"
                              value="<?php echo $data["data"]["pm_type"]; ?>" readonly>
                          </td>
                          <td class="align-middle px-3 table-item"></td>
                        </tr>
                        <tr>
                          <td class="align-middle px-3 table-item">Checked By</td>
                          <td class="align-middle px-3 table-item">
                            <input class="form-control form-control-sm" name="pmtby_name" type="text"
                              value="<?php echo $data["data"]["pmtby_name"]; ?>" readonly>
                          </td>
                          <td class="align-middle px-3 table-item"></td>
                        </tr>
                        <tr>
                          <td class="align-middle px-3 table-item">Dies Position</td>
                          <td class="align-middle px-3 table-item">
                            <select name="zona_id" id="zona_id" class="form-control select2" disabled>
                              <?php
                              foreach ($list_zona as $zona) {
                                if ($zona["zona_id"] == $data["data"]["zona_id"]) {
                                  ?>
                                  <!-- <option value="<?php echo $zona["zona_id"]; ?>" <?php if ($zona["zona_id"] == $data["data"]["zona_id"]) {
                                       echo "selected";
                                     } ?>><?php echo $zona["desc"]; ?></option> -->
                                  <option value="<?php echo $zona["zona_id"]; ?>" selected><?php echo $zona["desc"]; ?>
                                  </option>
                                  <?php
                                } else {
                                  if ($zona["zona_type"] == "P") {
                                    ?>
                                    <option value="<?php echo $zona["zona_id"]; ?>"><?php echo $zona["desc"]; ?></option>
                                    <?php
                                  }
                                }
                              }
                              ?>
                            </select>
                          </td>
                          <td class="align-middle px-3 table-item"></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-12">
                <div class="card mt-2">
                  <div class="card-body py-1 px-2">
                    <table class="table table-borderless table-sm my-1">
                      <thead class="table-secondary">
                        <tr>
                          <th class="align-middle px-2 table-header" scope="col" colspan="2">
                            <h6 class="mb-0">Checksheet</h6>
                          </th>
                          <th class="align-middle px-3 table-header" scope="col">
                            <input type="file" id="imgc11100" accept="image/png, image/jpeg" name="c11100" />
                            <input type="hidden" name="c11100_x" value="<?= $data["data"]["c11100"]; ?>" />
                          </th>
                          <th class="align-middle px-3 table-header" scope="col">
                            <?= (!empty($data["data"]["c11100"])) ? "<a class='view_img btn btn-outline-primary btn-sm' href='data:image/jpg;base64," . $data["data"]["c11100"] . "'>View Image</a>" : "" ?>
                          </th>
                          <th class="align-middle px-3 table-header" scope="col"></th>
                        </tr>
                      </thead>
                      <thead style="background-color: #E4E4E4;">
                        <tr>
                          <th class="align-middle px-2 table-header" scope="col">1.1.1</th>
                          <th class="align-middle px-3 table-header" scope="col">Chemical Cleaning</th>
                          <th class="align-middle px-3 table-header" scope="col"></th>
                          <th class="align-middle px-3 table-header" scope="col"></th>
                          <th class="align-middle px-3 table-header" scope="col"></th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td class="align-middle px-3 table-item">1.1.1.1</td>
                          <td class="align-middle px-3 table-item">Chemical Line Cooling Fix</td>
                          <td class="align-middle px-3 table-item">
                            <input class="" name="c11110" id="c11110" onchange="cek_cb()" <?php if ($data["data"]["pm_type"] != "PM Stroke 6000") {
                              echo "disabled";
                            } else {
                              echo "";
                            } ?> <?= (($data["data"]["c11110"] == "1")) ? "checked" : ''; ?> type="checkbox"
                              data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success"
                              data-offstyle="danger" data-size="mini" data-width="120">
                          </td>
                          <td class="align-middle px-3 table-item"></td>
                          <td class="align-middle px-3 table-item"></td>
                        </tr>
                        <tr>
                          <td class="align-middle px-3 table-item">1.1.1.2</td>
                          <td class="align-middle px-3 table-item">Chemical Line Cooling Move</td>
                          <td class="align-middle px-3 table-item">
                            <input class="" name="c11120" id="c11120" onchange="cek_cb()" <?php if ($data["data"]["pm_type"] != "PM Stroke 6000") {
                              echo "disabled";
                            } else {
                              echo "";
                            } ?> <?= (($data["data"]["c11120"] == "1")) ? "checked" : ''; ?> type="checkbox"
                              data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success"
                              data-offstyle="danger" data-size="mini" data-width="120">
                          </td>
                          <td class="align-middle px-3 table-item"></td>
                          <td class="align-middle px-3 table-item"></td>
                        </tr>
                      </tbody>

                      <thead style="background-color: #E4E4E4;">
                        <tr>
                          <th class="align-middle px-2 table-header" scope="col">1.1.2</th>
                          <th class="align-middle px-3 table-header" scope="col">Check Profile Activity</th>
                          <th class="align-middle px-3 table-header" scope="col"></th>
                          <th class="align-middle px-3 table-header" scope="col"></th>
                          <th class="align-middle px-3 table-header" scope="col"></th>
                        </tr>
                      </thead>

                      <thead style="background-color: #E4E4E4;">
                        <tr>
                          <th class="align-middle px-3 table-header" scope="col">1.1.2.1</th>
                          <th class="align-middle px-3 table-header" scope="col">Die Crack</th>
                          <th class="align-middle px-3 table-header" scope="col"></th>
                          <th class="align-middle px-3 table-header" scope="col"></th>
                          <th class="align-middle px-3 table-header" scope="col"></th>
                        </tr>
                      </thead>

                      <tbody>
                        <tr>
                          <td class="align-middle px-4 table-item">1.1.2.1.1</td>
                          <td class="align-middle px-3 table-item">Fix</td>
                          <td class="align-middle px-3 table-item">
                            <input class="" name="c11211" id="c11211" onchange="cek_cb()"
                              <?= (($data["data"]["c11211"] == "1")) ? "checked" : ''; ?> type="checkbox"
                              data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success"
                              data-offstyle="danger" data-size="mini" data-width="120">
                          </td>
                          <td class="align-middle px-3 table-item"></td>
                          <td class="align-middle px-3 table-item"></td>
                        </tr>
                        <tr>
                          <td class="align-middle px-4 table-item">1.1.2.1.2</td>
                          <td class="align-middle px-3 table-item">Move</td>
                          <td class="align-middle px-3 table-item">
                            <input class="" name="c11212" id="c11212" onchange="cek_cb()"
                              <?= (($data["data"]["c11212"] == "1")) ? "checked" : ''; ?> type="checkbox"
                              data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success"
                              data-offstyle="danger" data-size="mini" data-width="120">
                          </td>
                          <td class="align-middle px-3 table-item"></td>
                          <td class="align-middle px-3 table-item"></td>
                        </tr>
                        <tr>
                          <td class="align-middle px-4 table-item">1.1.2.1.3</td>
                          <td class="align-middle px-3 table-item">Slider</td>
                          <td class="align-middle px-3 table-item">
                            <input class="" name="c11213" id="c11213" onchange="cek_cb()"
                              <?= (($data["data"]["c11213"] == "1")) ? "checked" : ''; ?> type="checkbox"
                              data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success"
                              data-offstyle="danger" data-size="mini" data-width="120">
                          </td>
                          <td class="align-middle px-3 table-item"></td>
                          <td class="align-middle px-3 table-item"></td>
                        </tr>
                        <tr>
                          <td class="align-middle px-3 table-item"></td>
                          <td class="align-middle px-3 table-item"></td>
                          <td class="align-middle px-3 table-item">
                            <div>
                              <input type="checkbox" name="c11213_c1" <?= (($data["data"]["c11213_c1"] == "1")) ? "checked" : ''; ?>>
                              <label class="checkbox-table"><span></span>C1</label>
                              <input type="checkbox" name="c11213_c2" <?= (($data["data"]["c11213_c2"] == "1")) ? "checked" : ''; ?>>
                              <label class="checkbox-table"><span></span>C2</label>
                              <input type="checkbox" name="c11213_c3" <?php if ($data["data"]["group_id"] == "CSH") {
                                echo "disabled";
                              } ?> <?= (($data["data"]["c11213_c3"] == "1")) ? "checked" : ''; ?>>
                              <label class="checkbox-table"><span></span>C3</label>
                              <input type="checkbox" name="c11213_c4" <?php if ($data["data"]["group_id"] == "CSH") {
                                echo "disabled";
                              } ?> <?= (($data["data"]["c11213_c4"] == "1")) ? "checked" : ''; ?>>
                              <label class="checkbox-table"><span></span>C4</label>
                              <input type="checkbox" name="c11213_c5" <?php if ($data["data"]["group_id"] == "CSH" || $data["data"]["group_id"] == "OPN") {
                                echo "disabled";
                              } ?> <?= (($data["data"]["c11213_c5"] == "1")) ? "checked" : ''; ?>>
                              <label class="checkbox-table"><span></span>C5</label>
                            </div>
                          </td>
                          <td class="align-middle px-3 table-item"></td>
                          <td class="align-middle px-3 table-item"></td>
                        </tr>
                      </tbody>

                      <thead style="background-color: #E4E4E4;">
                        <tr>
                          <th class="align-middle px-3 table-header" scope="col">1.1.2.2</th>
                          <th class="align-middle px-3 table-header" scope="col">Katakrute</th>
                          <th class="align-middle px-3 table-header" scope="col"></th>
                          <th class="align-middle px-3 table-header" scope="col"></th>
                          <th class="align-middle px-3 table-header" scope="col"></th>
                        </tr>
                      </thead>

                      <tbody>
                        <tr>
                          <td class="align-middle px-4 table-item">1.1.2.2.1</td>
                          <td class="align-middle px-3 table-item">Fix</td>
                          <td class="align-middle px-3 table-item">
                            <input class="" name="c11221" id="c11221" onchange="cek_cb()"
                              <?= (($data["data"]["c11221"] == "1")) ? "checked" : ''; ?> type="checkbox"
                              data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success"
                              data-offstyle="danger" data-size="mini" data-width="120">
                          </td>
                          <td class="align-middle px-3 table-item"></td>
                          <td class="align-middle px-3 table-item"></td>
                        </tr>
                        <tr>
                          <td class="align-middle px-4 table-item">1.1.2.2.2</td>
                          <td class="align-middle px-3 table-item">Move</td>
                          <td class="align-middle px-3 table-item">
                            <input class="" name="c11222" id="c11222" onchange="cek_cb()"
                              <?= (($data["data"]["c11222"] == "1")) ? "checked" : ''; ?> type="checkbox"
                              data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success"
                              data-offstyle="danger" data-size="mini" data-width="120">
                          </td>
                          <td class="align-middle px-3 table-item"></td>
                          <td class="align-middle px-3 table-item"></td>
                        </tr>
                      </tbody>

                      <thead style="background-color: #E4E4E4;">
                        <tr>
                          <th class="align-middle px-3 table-header" scope="col">1.1.2.3</th>
                          <th class="align-middle px-3 table-header" scope="col">Yakitsuki</th>
                          <th class="align-middle px-3 table-header" scope="col"></th>
                          <th class="align-middle px-3 table-header" scope="col"></th>
                          <th class="align-middle px-3 table-header" scope="col"></th>
                        </tr>
                      </thead>

                      <tbody>
                        <tr>
                          <td class="align-middle px-4 table-item">1.1.2.3.1</td>
                          <td class="align-middle px-3 table-item">Fix</td>
                          <td class="align-middle px-3 table-item">
                            <input class="" name="c11231" id="c11231" onchange="cek_cb()"
                              <?= (($data["data"]["c11231"] == "1")) ? "checked" : ''; ?> type="checkbox"
                              data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success"
                              data-offstyle="danger" data-size="mini" data-width="120">
                          </td>
                          <td class="align-middle px-3 table-item"></td>
                          <td class="align-middle px-3 table-item"></td>
                        </tr>
                        <tr>
                          <td class="align-middle px-4 table-item">1.1.2.3.2</td>
                          <td class="align-middle px-3 table-item">Move</td>
                          <td class="align-middle px-3 table-item">
                            <input class="" name="c11232" id="c11232" onchange="cek_cb()"
                              <?= (($data["data"]["c11232"] == "1")) ? "checked" : ''; ?> type="checkbox"
                              data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success"
                              data-offstyle="danger" data-size="mini" data-width="120">
                          </td>
                          <td class="align-middle px-3 table-item"></td>
                          <td class="align-middle px-3 table-item"></td>
                        </tr>
                        <tr>
                          <td class="align-middle px-4 table-item">1.1.2.3.3</td>
                          <td class="align-middle px-3 table-item">Slider</td>
                          <td class="align-middle px-3 table-item">
                            <input class="" name="c11233" id="c11233" onchange="cek_cb()"
                              <?= (($data["data"]["c11233"] == "1")) ? "checked" : ''; ?> type="checkbox"
                              data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success"
                              data-offstyle="danger" data-size="mini" data-width="120">
                          </td>
                          <td class="align-middle px-3 table-item"></td>
                          <td class="align-middle px-3 table-item"></td>
                        </tr>
                      </tbody>

                      <thead style="background-color: #E4E4E4;">
                        <tr>
                          <th class="align-middle px-3 table-header" scope="col">1.1.2.4</th>
                          <th class="align-middle px-3 table-header" scope="col">Check Parting Line</th>
                          <th class="align-middle px-3 table-header" scope="col"></th>
                          <th class="align-middle px-3 table-header" scope="col"></th>
                          <th class="align-middle px-3 table-header" scope="col"></th>
                        </tr>
                      </thead>

                      <tbody>
                        <tr>
                          <td class="align-middle px-4 table-item">1.1.2.4.1</td>
                          <td class="align-middle px-3 table-item">Fix</td>
                          <td class="align-middle px-3 table-item">
                            <input class="" name="c11241" id="c11241" onchange="cek_cb()"
                              <?= (($data["data"]["c11241"] == "1")) ? "checked" : ''; ?> type="checkbox"
                              data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success"
                              data-offstyle="danger" data-size="mini" data-width="120">
                          </td>
                          <td class="align-middle px-3 table-item"></td>
                          <td class="align-middle px-3 table-item"></td>
                        </tr>
                        <tr>
                          <td class="align-middle px-4 table-item">1.1.2.4.2</td>
                          <td class="align-middle px-3 table-item">Move</td>
                          <td class="align-middle px-3 table-item">
                            <input class="" name="c11242" id="c11242" onchange="cek_cb()"
                              <?= (($data["data"]["c11242"] == "1")) ? "checked" : ''; ?> type="checkbox"
                              data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success"
                              data-offstyle="danger" data-size="mini" data-width="120">
                          </td>
                          <td class="align-middle px-3 table-item"></td>
                          <td class="align-middle px-3 table-item"></td>
                        </tr>
                        <tr>
                          <td class="align-middle px-4 table-item">1.1.2.4.3</td>
                          <td class="align-middle px-3 table-item">Slider</td>
                          <td class="align-middle px-3 table-item">
                            <input class="" name="c11243" id="c11243" onchange="cek_cb()"
                              <?= (($data["data"]["c11243"] == "1")) ? "checked" : ''; ?> type="checkbox"
                              data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success"
                              data-offstyle="danger" data-size="mini" data-width="120">
                          </td>
                          <td class="align-middle px-3 table-item"></td>
                          <td class="align-middle px-3 table-item"></td>
                        </tr>
                        <tr>
                          <td class="align-middle px-3 table-item"></td>
                          <td class="align-middle px-3 table-item"></td>
                          <td class="align-middle px-3 table-item">
                            <div>
                              <input type="checkbox" name="c11243_c1" <?= (($data["data"]["c11243_c1"] == "1")) ? "checked" : ''; ?>>
                              <label class="checkbox-table"><span></span>C1</label>
                              <input type="checkbox" name="c11243_c2" <?= (($data["data"]["c11243_c2"] == "1")) ? "checked" : ''; ?>>
                              <label class="checkbox-table"><span></span>C2</label>
                              <input type="checkbox" name="c11243_c3" <?php if ($data["data"]["group_id"] == "CSH") {
                                echo "disabled";
                              } ?> <?= (($data["data"]["c11243_c3"] == "1")) ? "checked" : ''; ?>>
                              <label class="checkbox-table"><span></span>C3</label>
                              <input type="checkbox" name="c11243_c4" <?php if ($data["data"]["group_id"] == "CSH") {
                                echo "disabled";
                              } ?> <?= (($data["data"]["c11243_c4"] == "1")) ? "checked" : ''; ?>>
                              <label class="checkbox-table"><span></span>C4</label>
                              <input type="checkbox" name="c11243_c5" <?php if ($data["data"]["group_id"] == "CSH" || $data["data"]["group_id"] == "OPN") {
                                echo "disabled";
                              } ?> <?= (($data["data"]["c11243_c5"] == "1")) ? "checked" : ''; ?>>
                              <label class="checkbox-table"><span></span>C5</label>
                            </div>
                          </td>
                          <td class="align-middle px-3 table-item"></td>
                          <td class="align-middle px-3 table-item"></td>
                        </tr>
                      </tbody>

                      <thead style="background-color: #E4E4E4;">
                        <tr>
                          <th class="align-middle px-3 table-header" scope="col">1.1.2.5</th>
                          <th class="align-middle px-3 table-header" scope="col">Check V-Notch</th>
                          <th class="align-middle px-3 table-header" scope="col"></th>
                          <th class="align-middle px-3 table-header" scope="col"></th>
                          <th class="align-middle px-3 table-header" scope="col"></th>
                        </tr>
                      </thead>

                      <tbody>
                        <tr>
                          <td class="align-middle px-4 table-item">1.1.2.5.1</td>
                          <td class="align-middle px-3 table-item">Fix</td>
                          <td class="align-middle px-3 table-item">
                            <input class="" name="c11251" id="c11251" onchange="cek_cb()"
                              <?= (($data["data"]["c11251"] == "1")) ? "checked" : ''; ?> type="checkbox"
                              data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success"
                              data-offstyle="danger" data-size="mini" data-width="120">
                          </td>
                          <td class="align-middle px-3 table-item"></td>
                          <td class="align-middle px-3 table-item"></td>
                        </tr>
                        <tr>
                          <td class="align-middle px-4 table-item">1.1.2.5.2</td>
                          <td class="align-middle px-3 table-item">Move</td>
                          <td class="align-middle px-3 table-item">
                            <input class="" name="c11252" id="c11252" onchange="cek_cb()"
                              <?= (($data["data"]["c11252"] == "1")) ? "checked" : ''; ?> type="checkbox"
                              data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success"
                              data-offstyle="danger" data-size="mini" data-width="120">
                          </td>
                          <td class="align-middle px-3 table-item"></td>
                          <td class="align-middle px-3 table-item"></td>
                        </tr>
                      </tbody>

                      <thead style="background-color: #E4E4E4;">
                        <tr>
                          <th class="align-middle px-2 table-header" scope="col">1.1.3</th>
                          <th class="align-middle px-3 table-header" scope="col">Check Vacuum</th>
                          <th class="align-middle px-3 table-header" scope="col"></th>
                          <th class="align-middle px-3 table-header" scope="col"></th>
                          <th class="align-middle px-3 table-header" scope="col"></th>
                        </tr>
                      </thead>

                      <thead style="background-color: #E4E4E4;">
                        <tr>
                          <th class="align-middle px-3 table-header" scope="col">1.1.3.1</th>
                          <th class="align-middle px-3 table-header" scope="col">Vacuum Component</th>
                          <th class="align-middle px-3 table-header" scope="col"></th>
                          <th class="align-middle px-3 table-header" scope="col"></th>
                          <th class="align-middle px-3 table-header" scope="col"></th>
                        </tr>
                      </thead>

                      <tbody>
                        <tr>
                          <td class="align-middle px-4 table-item">1.1.3.1.1</td>
                          <td class="align-middle px-3 table-item">Cleaning Block Vacuum</td>
                          <td class="align-middle px-3 table-item">
                            <input class="" name="c11311" id="c11311" onchange="cek_cb()" <?php if ($data["data"]["group_id"] == "CSH") {
                              echo "disabled";
                            } ?> <?= (($data["data"]["c11311"] == "1")) ? "checked" : ''; ?> type="checkbox"
                              data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success"
                              data-offstyle="danger" data-size="mini" data-width="120">
                          </td>
                          <td class="align-middle px-3 table-item"></td>
                          <td class="align-middle px-3 table-item"></td>
                        </tr>
                        <tr>
                          <td class="align-middle px-4 table-item">1.1.3.1.2</td>
                          <td class="align-middle px-3 table-item">Cleaning Piston Vacuum</td>
                          <td class="align-middle px-3 table-item">
                            <input class="" name="c11312" id="c11312" onchange="cek_cb()" <?php if ($data["data"]["group_id"] == "CSH") {
                              echo "disabled";
                            } ?> <?= (($data["data"]["c11312"] == "1")) ? "checked" : ''; ?> type="checkbox"
                              data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success"
                              data-offstyle="danger" data-size="mini" data-width="120">
                          </td>
                          <td class="align-middle px-3 table-item"></td>
                          <td class="align-middle px-3 table-item"></td>
                        </tr>
                        <tr>
                          <td class="align-middle px-4 table-item">1.1.3.1.3</td>
                          <td class="align-middle px-3 table-item">Cleaning Pipa Vacuum</td>
                          <td class="align-middle px-3 table-item">
                            <input class="" name="c11313" id="c11313" onchange="cek_cb()" <?php if ($data["data"]["group_id"] == "CSH") {
                              echo "disabled";
                            } ?> <?= (($data["data"]["c11313"] == "1")) ? "checked" : ''; ?> type="checkbox"
                              data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success"
                              data-offstyle="danger" data-size="mini" data-width="120">
                          </td>
                          <td class="align-middle px-3 table-item"></td>
                          <td class="align-middle px-3 table-item"></td>
                        </tr>
                        <tr>
                          <td class="align-middle px-4 table-item">1.1.3.1.4</td>
                          <td class="align-middle px-3 table-item">Fitting Check Piston Vacuum</td>
                          <td class="align-middle px-3 table-item">
                            <input class="" name="c11314" id="c11314" onchange="cek_cb()" <?php if ($data["data"]["group_id"] == "CSH") {
                              echo "disabled";
                            } ?> <?= (($data["data"]["c11314"] == "1")) ? "checked" : ''; ?> type="checkbox"
                              data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success"
                              data-offstyle="danger" data-size="mini" data-width="120">
                          </td>
                          <td class="align-middle px-3 table-item"></td>
                          <td class="align-middle px-3 table-item"></td>
                        </tr>
                        <tr>
                          <td class="align-middle px-4 table-item">1.1.3.1.5</td>
                          <td class="align-middle px-3 table-item">Ganti O-Ring Piston Vacuum</td>
                          <td class="align-middle px-3 table-item">
                            <input class="" name="c11315" id="c11315" onchange="cek_cb()" <?php if ($data["data"]["group_id"] == "CSH") {
                              echo "disabled";
                            } ?> <?= (($data["data"]["c11315"] == "1")) ? "checked" : ''; ?> type="checkbox"
                              data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success"
                              data-offstyle="danger" data-size="mini" data-width="120">
                          </td>
                          <td class="align-middle px-3 table-item"></td>
                          <td class="align-middle px-3 table-item"></td>
                        </tr>
                        <tr>
                          <td class="align-middle px-4 table-item">1.1.3.1.6</td>
                          <td class="align-middle px-3 table-item">Ganti Hose Vacuum</td>
                          <td class="align-middle px-3 table-item">
                            <input class="" name="c11316" id="c11316" onchange="cek_cb()" <?php if ($data["data"]["group_id"] == "CSH") {
                              echo "disabled";
                            } ?> <?= (($data["data"]["c11316"] == "1")) ? "checked" : ''; ?> type="checkbox"
                              data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success"
                              data-offstyle="danger" data-size="mini" data-width="120">
                          </td>
                          <td class="align-middle px-3 table-item"></td>
                          <td class="align-middle px-3 table-item"></td>
                        </tr>
                      </tbody>

                      <thead style="background-color: #E4E4E4;">
                        <tr>
                          <th class="align-middle px-3 table-header" scope="col">1.1.3.2</th>
                          <th class="align-middle px-3 table-header" scope="col">Vacuum Final Check</th>
                          <th class="align-middle px-3 table-header" scope="col"></th>
                          <th class="align-middle px-3 table-header" scope="col"></th>
                          <th class="align-middle px-3 table-header" scope="col"></th>
                        </tr>
                      </thead>

                      <thead style="background-color: #E4E4E4;">
                        <tr>
                          <th class="align-middle px-4 table-header" scope="col">1.1.3.2.1</th>
                          <th class="align-middle px-3 table-header" scope="col">Vacuum Valve Indicator</th>
                          <th class="align-middle px-3 table-header" scope="col"></th>
                          <th class="align-middle px-3 table-header" scope="col"></th>
                          <th class="align-middle px-3 table-header" scope="col"></th>
                        </tr>
                      </thead>

                      <tbody>
                        <tr>
                          <td class="align-middle px-5 table-item">1.1.3.2.1.1</td>
                          <td class="align-middle px-3 table-item">Valve Open</td>
                          <td class="align-middle px-3 table-item">
                            <input class="" name="c113211" id="c113211" onchange="cek_cb()" <?php if ($data["data"]["group_id"] == "CSH") {
                              echo "disabled";
                            } ?> <?= (($data["data"]["c113211"] == "1")) ? "checked" : ''; ?> type="checkbox"
                              data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success"
                              data-offstyle="danger" data-size="mini" data-width="120">
                          </td>
                          <td class="align-middle px-3 table-item"></td>
                          <td class="align-middle px-3 table-item"></td>
                        </tr>
                        <tr>
                          <td class="align-middle px-5 table-item">1.1.3.2.1.2</td>
                          <td class="align-middle px-3 table-item">Valve Close</td>
                          <td class="align-middle px-3 table-item">
                            <input class="" name="c113212" id="c113212" onchange="cek_cb()" <?php if ($data["data"]["group_id"] == "CSH") {
                              echo "disabled";
                            } ?> <?= (($data["data"]["c113212"] == "1")) ? "checked" : ''; ?> type="checkbox"
                              data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success"
                              data-offstyle="danger" data-size="mini" data-width="120">
                          </td>
                          <td class="align-middle px-3 table-item"></td>
                          <td class="align-middle px-3 table-item"></td>
                        </tr>
                        <tr>
                          <td class="align-middle px-5 table-item">1.1.3.2.2</td>
                          <td class="align-middle px-3 table-item">Valve Operation Time</td>
                          <td class="align-middle px-3 table-item">
                            <input class="" name="c11322" id="c11322" onchange="cek_cb()" <?php if ($data["data"]["group_id"] == "CSH") {
                              echo "disabled";
                            } ?> <?= (($data["data"]["c11322"] == "1")) ? "checked" : ''; ?> type="checkbox"
                              data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success"
                              data-offstyle="danger" data-size="mini" data-width="120">
                          </td>
                          <td class="align-middle px-3 table-item"></td>
                          <td class="align-middle px-3 table-item"></td>
                        </tr>
                        <tr>
                          <td class="align-middle px-5 table-item">1.1.3.2.3</td>
                          <td class="align-middle px-3 table-item">Valve Stroke</td>
                          <td class="align-middle px-3 table-item">
                            <input class="" name="c11323" id="c11323" onchange="cek_cb()" <?php if ($data["data"]["group_id"] == "CSH") {
                              echo "disabled";
                            } ?> <?= (($data["data"]["c11323"] == "1")) ? "checked" : ''; ?> type="checkbox"
                              data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success"
                              data-offstyle="danger" data-size="mini" data-width="120">
                          </td>
                          <td class="align-middle px-3 table-item"></td>
                          <td class="align-middle px-3 table-item"></td>
                        </tr>
                      </tbody>

                      <thead style="background-color: #E4E4E4;">
                        <tr>
                          <th class="align-middle px-2 table-header" scope="col">1.1.4</th>
                          <th class="align-middle px-3 table-header" scope="col">MTBF Core Pin</th>
                          <th class="align-middle px-3 table-header" scope="col"></th>
                          <th class="align-middle px-3 table-header" scope="col"></th>
                          <th class="align-middle px-3 table-header" scope="col"></th>
                        </tr>
                      </thead>

                      <tbody>
                        <tr>
                          <td class="align-middle px-3 table-item">1.1.4.1</td>
                          <td class="align-middle px-3 table-item">Fix</td>
                          <td class="align-middle px-3 table-item">
                            <input class="" name="c1141" id="c1141" onchange="cek_cb()"
                              <?= (($data["data"]["c1141"] == "1")) ? "checked" : ''; ?> type="checkbox"
                              data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success"
                              data-offstyle="danger" data-size="mini" data-width="120">
                          </td>
                          <td class="align-middle px-3 table-item"></td>
                          <td class="align-middle px-3 table-item"></td>
                        </tr>
                        <tr>
                          <td class="align-middle px-3 table-item">1.1.4.2</td>
                          <td class="align-middle px-3 table-item">Move</td>
                          <td class="align-middle px-3 table-item">
                            <input class="" name="c1142" id="c1142" onchange="cek_cb()"
                              <?= (($data["data"]["c1142"] == "1")) ? "checked" : ''; ?> type="checkbox"
                              data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success"
                              data-offstyle="danger" data-size="mini" data-width="120">
                          </td>
                          <td class="align-middle px-3 table-item"></td>
                          <td class="align-middle px-3 table-item"></td>
                        </tr>
                        <tr>
                          <td class="align-middle px-3 table-item">1.1.4.3</td>
                          <td class="align-middle px-3 table-item">Slider</td>
                          <td class="align-middle px-3 table-item">
                            <input class="" name="c1143" id="c1143" onchange="cek_cb()"
                              <?= (($data["data"]["c1143"] == "1")) ? "checked" : ''; ?> type="checkbox"
                              data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success"
                              data-offstyle="danger" data-size="mini" data-width="120">
                          </td>
                          <td class="align-middle px-3 table-item"></td>
                          <td class="align-middle px-3 table-item"></td>
                        </tr>
                        <tr>
                          <td class="align-middle px-3 table-item"></td>
                          <td class="align-middle px-3 table-item"></td>
                          <td class="align-middle px-3 table-item">
                            <div>
                              <input type="checkbox" name="c1143_c1" <?= (($data["data"]["c1143_c1"] == "1")) ? "checked" : ''; ?>>
                              <label class="checkbox-table"><span></span>C1</label>
                              <input type="checkbox" name="c1143_c2" <?= (($data["data"]["c1143_c2"] == "1")) ? "checked" : ''; ?>>
                              <label class="checkbox-table"><span></span>C2</label>
                              <input type="checkbox" name="c1143_c3" <?php if ($data["data"]["group_id"] == "CSH") {
                                echo "disabled";
                              } ?> <?= (($data["data"]["c1143_c3"] == "1")) ? "checked" : ''; ?>>
                              <label class="checkbox-table"><span></span>C3</label>
                              <input type="checkbox" name="c1143_c4" <?php if ($data["data"]["group_id"] == "CSH") {
                                echo "disabled";
                              } ?> <?= (($data["data"]["c1143_c4"] == "1")) ? "checked" : ''; ?>>
                              <label class="checkbox-table"><span></span>C4</label>
                              <input type="checkbox" name="c1143_c5" <?php if ($data["data"]["group_id"] == "CSH" || $data["data"]["group_id"] == "OPN") {
                                echo "disabled";
                              } ?> <?= (($data["data"]["c1143_c5"] == "1")) ? "checked" : ''; ?>>
                              <label class="checkbox-table"><span></span>C5</label>
                            </div>
                          </td>
                          <td class="align-middle px-3 table-item"></td>
                          <td class="align-middle px-3 table-item"></td>
                        </tr>
                      </tbody>

                      <thead style="background-color: #E4E4E4;">
                        <tr>
                          <th class="align-middle px-2 table-header" scope="col">1.1.5</th>
                          <th class="align-middle px-3 table-header" scope="col">Check PIN Back</th>
                          <th class="align-middle px-3 table-header" scope="col"></th>
                          <th class="align-middle px-3 table-header" scope="col"></th>
                          <th class="align-middle px-3 table-header" scope="col"></th>
                        </tr>
                      </thead>

                      <tbody>
                        <tr>
                          <td class="align-middle px-3 table-item">1.1.5.1</td>
                          <td class="align-middle px-3 table-item">Fix</td>
                          <td class="align-middle px-3 table-item">
                            <input class="" name="c1151" id="c1151" onchange="cek_cb()"
                              <?= (($data["data"]["c1151"] == "1")) ? "checked" : ''; ?> type="checkbox"
                              data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success"
                              data-offstyle="danger" data-size="mini" data-width="120">
                          </td>
                          <td class="align-middle px-3 table-item"></td>
                          <td class="align-middle px-3 table-item"></td>
                        </tr>
                        <tr>
                          <td class="align-middle px-3 table-item">1.1.5.2</td>
                          <td class="align-middle px-3 table-item">Move</td>
                          <td class="align-middle px-3 table-item">
                            <input class="" name="c1152" id="c1152" onchange="cek_cb()"
                              <?= (($data["data"]["c1152"] == "1")) ? "checked" : ''; ?> type="checkbox"
                              data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success"
                              data-offstyle="danger" data-size="mini" data-width="120">
                          </td>
                          <td class="align-middle px-3 table-item"></td>
                          <td class="align-middle px-3 table-item"></td>
                        </tr>
                        <tr>
                          <td class="align-middle px-3 table-item"></td>
                          <td class="align-middle px-3 table-item"></td>
                          <td class="align-middle px-3 table-item">
                            <div>
                              <input type="checkbox" name="c1152_c1" <?= (($data["data"]["c1152_c1"] == "1")) ? "checked" : ''; ?>>
                              <label class="checkbox-table"><span></span>C1</label>
                              <input type="checkbox" name="c1152_c2" <?= (($data["data"]["c1152_c2"] == "1")) ? "checked" : ''; ?>>
                              <label class="checkbox-table"><span></span>C2</label>
                              <input type="checkbox" name="c1152_c3" <?php if ($data["data"]["group_id"] == "CSH") {
                                echo "disabled";
                              } ?> <?= (($data["data"]["c1152_c3"] == "1")) ? "checked" : ''; ?>>
                              <label class="checkbox-table"><span></span>C3</label>
                              <input type="checkbox" name="c1152_c4" <?php if ($data["data"]["group_id"] == "CSH") {
                                echo "disabled";
                              } ?> <?= (($data["data"]["c1152_c4"] == "1")) ? "checked" : ''; ?>>
                              <label class="checkbox-table"><span></span>C4</label>
                              <input type="checkbox" name="c1152_c5" <?php if ($data["data"]["group_id"] == "CSH" || $data["data"]["group_id"] == "OPN") {
                                echo "disabled";
                              } ?> <?= (($data["data"]["c1152_c5"] == "1")) ? "checked" : ''; ?>>
                              <label class="checkbox-table"><span></span>C5</label>
                            </div>
                          </td>
                          <td class="align-middle px-3 table-item"></td>
                          <td class="align-middle px-3 table-item"></td>
                        </tr>
                        <tr>
                          <td class="align-middle px-3 table-item">1.1.5.3</td>
                          <td class="align-middle px-3 table-item">Slider</td>
                          <td class="align-middle px-3 table-item">
                            <input class="" name="c1153" id="c1153" onchange="cek_cb()"
                              <?= (($data["data"]["c1153"] == "1")) ? "checked" : ''; ?> type="checkbox"
                              data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success"
                              data-offstyle="danger" data-size="mini" data-width="120">
                          </td>
                          <td class="align-middle px-3 table-item"></td>
                          <td class="align-middle px-3 table-item"></td>
                        </tr>
                      </tbody>

                      <thead style="background-color: #E4E4E4;">
                        <tr>
                          <th class="align-middle px-2 table-header" scope="col">1.1.6</th>
                          <th class="align-middle px-3 table-header" scope="col">Check Flow</th>
                          <th class="align-middle px-3 table-header" scope="col"></th>
                          <th class="align-middle px-3 table-header" scope="col"></th>
                          <th class="align-middle px-3 table-header" scope="col"></th>
                        </tr>
                      </thead>

                      <thead style="background-color: #E4E4E4;">
                        <tr>
                          <th class="align-middle px-3 table-header" scope="col">1.1.6.1</th>
                          <th class="align-middle px-3 table-header" scope="col">Check Flow Power Cool</th>
                          <th class="align-middle px-3 table-header" scope="col">
                            <input type="file" id="imgc1161" accept="image/png, image/jpeg" name="c1161"
                              id="upload-image" />
                            <input type="hidden" name="c1161_x" value="<?= $data["data"]["c1161"]; ?>">
                          </th>
                          <th class="align-middle px-3 table-header" scope="col">
                            <?= (!empty($data["data"]["c1161"])) ? "<a class='view_img btn btn-outline-primary btn-sm' href='data:image/jpg;base64," . $data["data"]["c1161"] . "'>View Image</a>" : "" ?>
                          </th>
                          <th class="align-middle px-3 table-header" scope="col"></th>
                        </tr>
                      </thead>

                      <tbody>
                        <tr>
                          <td class="align-middle px-4 table-item">1.1.6.1.1</td>
                          <td class="align-middle px-3 table-item">Fix</td>
                          <td class="align-middle px-3 table-item">
                            <input class="" id="c11611" onchange="cek_cb()" name="c11611"
                              <?= (($data["data"]["c11611"] == "1")) ? "checked" : ''; ?> type="checkbox"
                              data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success"
                              data-offstyle="danger" data-size="mini" data-width="120">
                          </td>
                          <td class="align-middle px-3 table-item"></td>
                          <td class="align-middle px-3 table-item"></td>
                        </tr>
                        <tr>
                          <td class="align-middle px-4 table-item">1.1.6.1.2</td>
                          <td class="align-middle px-3 table-item">Move</td>
                          <td class="align-middle px-3 table-item">
                            <input class="" id="c11612" onchange="cek_cb()" name="c11612"
                              <?= (($data["data"]["c11612"] == "1")) ? "checked" : ''; ?> type="checkbox"
                              data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success"
                              data-offstyle="danger" data-size="mini" data-width="120">
                          </td>
                          <td class="align-middle px-3 table-item"></td>
                          <td class="align-middle px-3 table-item"></td>
                        </tr>
                      </tbody>

                      <thead style="background-color: #E4E4E4;">
                        <tr>
                          <th class="align-middle px-3 table-header" scope="col">1.1.6.2</th>
                          <th class="align-middle px-3 table-header" scope="col">Check Flow Main Cool</th>
                          <th class="align-middle px-3 table-header" scope="col">
                            <input type="file" id="imgc1162" <?php if ($data["data"]["pm_type"] != "PM Stroke 6000") {
                              echo "disabled";
                            } else {
                              echo "";
                            } ?> accept="image/png, image/jpeg" name="c1162"
                              id="upload-image" />
                            <input type="hidden" name="c1162_x" value="<?= $data["data"]["c1162"]; ?>">
                          </th>
                          <th class="align-middle px-3 table-header" scope="col">
                            <?= (!empty($data["data"]["c1162"])) ? "<a class='view_img btn btn-outline-primary btn-sm' href='data:image/jpg;base64," . $data["data"]["c1162"] . "'>View Image</a>" : "" ?>
                          </th>
                          <th class="align-middle px-3 table-header" scope="col"></th>
                        </tr>
                      </thead>

                      <tbody>
                        <tr>
                          <td class="align-middle px-4 table-item">1.1.6.2.1</td>
                          <td class="align-middle px-3 table-item">Fix</td>
                          <td class="align-middle px-3 table-item">
                            <input class="" name="c11621" id="c11621" onchange="cek_cb()" <?php if ($data["data"]["pm_type"] != "PM Stroke 6000") {
                              echo "disabled";
                            } else {
                              echo "";
                            } ?> <?= (($data["data"]["c11621"] == "1")) ? "checked" : ''; ?> type="checkbox"
                              data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success"
                              data-offstyle="danger" data-size="mini" data-width="120">
                          </td>
                          <td class="align-middle px-3 table-item"></td>
                          <td class="align-middle px-3 table-item"></td>
                        </tr>
                        <tr>
                          <td class="align-middle px-4 table-item">1.1.6.2.2</td>
                          <td class="align-middle px-3 table-item">Move</td>
                          <td class="align-middle px-3 table-item">
                            <input class="" name="c11622" id="c11622" onchange="cek_cb()" <?php if ($data["data"]["pm_type"] != "PM Stroke 6000") {
                              echo "disabled";
                            } else {
                              echo "";
                            } ?> <?= (($data["data"]["c11622"] == "1")) ? "checked" : ''; ?> type="checkbox"
                              data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success"
                              data-offstyle="danger" data-size="mini" data-width="120">
                          </td>
                          <td class="align-middle px-3 table-item"></td>
                          <td class="align-middle px-3 table-item"></td>
                        </tr>
                      </tbody>

                      <thead style="background-color: #E4E4E4;">
                        <tr>
                          <th class="align-middle px-2 table-header" scope="col">1.1.7</th>
                          <th class="align-middle px-3 table-header" scope="col">Check Ejector</th>
                          <th class="align-middle px-3 table-header" scope="col">
                            <input class="" name="c117" id="c117" onchange="cek_cb()" <?= (($data["data"]["c117"] == "1")) ? "checked" : ''; ?> type="checkbox" data-toggle="toggle" data-on="Completed"
                              data-off="On Progress" data-onstyle="success" data-offstyle="danger" data-size="mini"
                              data-width="120">
                          </th>
                          <th class="align-middle px-3 table-header" scope="col"></th>
                          <th class="align-middle px-3 table-header" scope="col"></th>
                        </tr>
                      </thead>

                      <thead style="background-color: #E4E4E4;">
                        <tr>
                          <th class="align-middle px-2 table-header" scope="col">1.1.8</th>
                          <th class="align-middle px-3 table-header" scope="col">Check Hydraulic Core</th>
                          <th class="align-middle px-3 table-header" scope="col"></th>
                          <th class="align-middle px-3 table-header" scope="col"></th>
                          <th class="align-middle px-3 table-header" scope="col"></th>
                        </tr>
                      </thead>

                      <tbody>
                        <tr>
                          <td class="align-middle px-3 table-item">1.1.8.1</td>
                          <td class="align-middle px-3 table-item">Slider C1</td>
                          <td class="align-middle px-3 table-item">
                            <input class="" name="c1181" id="c1181" onchange="cek_cb()"
                              <?= (($data["data"]["c1181"] == "1")) ? "checked" : ''; ?> type="checkbox"
                              data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success"
                              data-offstyle="danger" data-size="mini" data-width="120">
                          </td>
                          <td class="align-middle px-3 table-item"></td>
                          <td class="align-middle px-3 table-item"></td>
                        </tr>
                        <tr>
                          <td class="align-middle px-3 table-item">1.1.8.2</td>
                          <td class="align-middle px-3 table-item">Slider C2</td>
                          <td class="align-middle px-3 table-item">
                            <input class="" name="c1182" id="c1182" onchange="cek_cb()"
                              <?= (($data["data"]["c1182"] == "1")) ? "checked" : ''; ?> type="checkbox"
                              data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success"
                              data-offstyle="danger" data-size="mini" data-width="120">
                          </td>
                          <td class="align-middle px-3 table-item"></td>
                          <td class="align-middle px-3 table-item"></td>
                        </tr>
                        <tr>
                          <td class="align-middle px-3 table-item">1.1.8.3</td>
                          <td class="align-middle px-3 table-item">Slider C3</td>
                          <td class="align-middle px-3 table-item">
                            <input class="" name="c1183" id="c1183" onchange="cek_cb()" <?php if ($data["data"]["group_id"] == "CSH") {
                              echo "disabled";
                            } ?> <?= (($data["data"]["c1183"] == "1")) ? "checked" : ''; ?> type="checkbox"
                              data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success"
                              data-offstyle="danger" data-size="mini" data-width="120">
                          </td>
                          <td class="align-middle px-3 table-item"></td>
                          <td class="align-middle px-3 table-item"></td>
                        </tr>
                        <tr>
                          <td class="align-middle px-3 table-item">1.1.8.4</td>
                          <td class="align-middle px-3 table-item">Slider C4</td>
                          <td class="align-middle px-3 table-item">
                            <input class="" name="c1184" id="c1184" onchange="cek_cb()" <?php if ($data["data"]["group_id"] == "CSH") {
                              echo "disabled";
                            } ?> <?= (($data["data"]["c1184"] == "1")) ? "checked" : ''; ?> type="checkbox"
                              data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success"
                              data-offstyle="danger" data-size="mini" data-width="120">
                          </td>
                          <td class="align-middle px-3 table-item"></td>
                          <td class="align-middle px-3 table-item"></td>
                        </tr>
                        <tr>
                          <td class="align-middle px-3 table-item">1.1.8.5</td>
                          <td class="align-middle px-3 table-item">Slider C5</td>
                          <td class="align-middle px-3 table-item">
                            <input class="" name="c1185" id="c1185" onchange="cek_cb()" <?php if ($data["data"]["group_id"] == "CSH" || $data["data"]["group_id"] == "OPN") {
                              echo "disabled";
                            } ?> <?= (($data["data"]["c1185"] == "1")) ? "checked" : ''; ?> type="checkbox"
                              data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success"
                              data-offstyle="danger" data-size="mini" data-width="120">
                          </td>
                          <td class="align-middle px-3 table-item"></td>
                          <td class="align-middle px-3 table-item"></td>
                        </tr>
                      </tbody>

                      <thead style="background-color: #E4E4E4;">
                        <tr>
                          <th class="align-middle px-2 table-header" scope="col">1.1.9</th>
                          <th class="align-middle px-3 table-header" scope="col">Check Bocor</th>
                          <th class="align-middle px-3 table-header" scope="col">
                            <!--input type="file" accept="image/png, image/jpeg" name="c119" />
                            <input type="hidden" name="c119_x" value="<?= $data["data"]["c119"]; ?>" /-->
                          </th>
                          <th class="align-middle px-3 table-header" scope="col">
                            <?= (!empty($data["data"]["c119"])) ? "<a download='file_check_bocor.jpg' href='data:image/jpg;base64," . $data["data"]["c119"] . "'>Download File</a>" : "" ?>
                          </th>
                          <th class="align-middle px-3 table-header" scope="col"></th>
                        </tr>
                      </thead>

                      <thead style="background-color: #E4E4E4;">
                        <tr>
                          <th class="align-middle px-3 table-header" scope="col">1.1.9.1</th>
                          <th class="align-middle px-3 table-header" scope="col">Fix</th>
                          <th class="align-middle px-3 table-header" scope="col"></th>
                          <th class="align-middle px-3 table-header" scope="col"></th>
                          <th class="align-middle px-3 table-header" scope="col"></th>
                        </tr>
                      </thead>

                      <tbody>
                        <tr>
                          <td class="align-middle px-4 table-item">1.1.9.1.1</td>
                          <td class="align-middle px-3 table-item">Power Cool Fix 1</td>
                          <td class="align-middle px-3 table-item">
                            <input class="" name="c11911" id="c11911" onchange="cek_cb()"
                              <?= (($data["data"]["c11911"] == "1")) ? "checked" : ''; ?> type="checkbox"
                              data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success"
                              data-offstyle="danger" data-size="mini" data-width="120">
                          </td>
                          <td class="align-middle px-3 table-item"></td>
                          <td class="align-middle px-3 table-item"></td>
                        </tr>
                        <tr>
                          <td class="align-middle px-4 table-item">1.1.9.1.2</td>
                          <td class="align-middle px-3 table-item">Power Cool Fix 2</td>
                          <td class="align-middle px-3 table-item">
                            <input class="" name="c11912" id="c11912" onchange="cek_cb()"
                              <?= (($data["data"]["c11912"] == "1")) ? "checked" : ''; ?> type="checkbox"
                              data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success"
                              data-offstyle="danger" data-size="mini" data-width="120">
                          </td>
                          <td class="align-middle px-3 table-item"></td>
                          <td class="align-middle px-3 table-item"></td>
                        </tr>
                        <tr>
                          <td class="align-middle px-4 table-item">1.1.9.1.3</td>
                          <td class="align-middle px-3 table-item">Main Cool Fix</td>
                          <td class="align-middle px-3 table-item">
                            <input class="" name="c11913" id="c11913" onchange="cek_cb()"
                              <?= (($data["data"]["c11913"] == "1")) ? "checked" : ''; ?> type="checkbox"
                              data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success"
                              data-offstyle="danger" data-size="mini" data-width="120">
                          </td>
                          <td class="align-middle px-3 table-item"></td>
                          <td class="align-middle px-3 table-item"></td>
                        </tr>
                        <tr>
                          <td class="align-middle px-4 table-item">1.1.9.1.4</td>
                          <td class="align-middle px-3 table-item">Sprue Bush</td>
                          <td class="align-middle px-3 table-item">
                            <input class="" name="c11914" id="c11914" onchange="cek_cb()"
                              <?= (($data["data"]["c11914"] == "1")) ? "checked" : ''; ?> type="checkbox"
                              data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success"
                              data-offstyle="danger" data-size="mini" data-width="120">
                          </td>
                          <td class="align-middle px-3 table-item"></td>
                          <td class="align-middle px-3 table-item"></td>
                        </tr>
                      </tbody>

                      <thead style="background-color: #E4E4E4;">
                        <tr>
                          <th class="align-middle px-3 table-header" scope="col">1.1.9.2</th>
                          <th class="align-middle px-3 table-header" scope="col">Move</th>
                          <th class="align-middle px-3 table-header" scope="col"></th>
                          <th class="align-middle px-3 table-header" scope="col"></th>
                          <th class="align-middle px-3 table-header" scope="col"></th>
                        </tr>
                      </thead>

                      <tbody>
                        <tr>
                          <td class="align-middle px-4 table-item">1.1.9.2.1</td>
                          <td class="align-middle px-3 table-item">Power Cool Move 1</td>
                          <td class="align-middle px-3 table-item">
                            <input class="" name="c11921" id="c11921" onchange="cek_cb()"
                              <?= (($data["data"]["c11921"] == "1")) ? "checked" : ''; ?> type="checkbox"
                              data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success"
                              data-offstyle="danger" data-size="mini" data-width="120">
                          </td>
                          <td class="align-middle px-3 table-item"></td>
                          <td class="align-middle px-3 table-item"></td>
                        </tr>
                        <tr>
                          <td class="align-middle px-4 table-item">1.1.9.2.2</td>
                          <td class="align-middle px-3 table-item">Power Cool Move 2</td>
                          <td class="align-middle px-3 table-item">
                            <input class="" name="c11922" id="c11922" onchange="cek_cb()"
                              <?= (($data["data"]["c11922"] == "1")) ? "checked" : ''; ?> type="checkbox"
                              data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success"
                              data-offstyle="danger" data-size="mini" data-width="120">
                          </td>
                          <td class="align-middle px-3 table-item"></td>
                          <td class="align-middle px-3 table-item"></td>
                        </tr>
                        <tr>
                          <td class="align-middle px-4 table-item">1.1.9.2.3</td>
                          <td class="align-middle px-3 table-item">Main Cool Move</td>
                          <td class="align-middle px-3 table-item">
                            <input class="" name="c11923" id="c11923" onchange="cek_cb()"
                              <?= (($data["data"]["c11923"] == "1")) ? "checked" : ''; ?> type="checkbox"
                              data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success"
                              data-offstyle="danger" data-size="mini" data-width="120">
                          </td>
                          <td class="align-middle px-3 table-item"></td>
                          <td class="align-middle px-3 table-item"></td>
                        </tr>
                        <tr>
                          <td class="align-middle px-4 table-item">1.1.9.2.4</td>
                          <td class="align-middle px-3 table-item">Sprue Core</td>
                          <td class="align-middle px-3 table-item">
                            <input class="" name="c11924" id="c11924" onchange="cek_cb()"
                              <?= (($data["data"]["c11924"] == "1")) ? "checked" : ''; ?> type="checkbox"
                              data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success"
                              data-offstyle="danger" data-size="mini" data-width="120">
                          </td>
                          <td class="align-middle px-3 table-item"></td>
                          <td class="align-middle px-3 table-item"></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </main>
      <?php include 'common/t_footer.php'; ?>
    </div>
  </div>
  <?php include 'common/t_js.php'; ?>
  <script src="vendors/ega/js/scripts.js?time=<?php echo date("Ymdhis"); ?>" type="text/javascript"></script>
  <script>
    $(document).ready(function () {
      $('.view_img').EZView();
    });

    $("#my-form").submit(function (event) {
      $("#btn-save").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Please Wait...');
      $("#btn-save").attr("disabled", "disabled");

      $("#btn-print").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Please Wait...');
      $("#btn-print").attr("disabled", "disabled");
    });

    function cek_cb() {
      if ($("#group").val() == "CSH") {
        if ($("#pmtype").val() == "PM Stroke 2000") {
          if (
            // $("#c11110").is(":checked") && $("#c11120").is(":checked") &&
            $("#c11211").is(":checked") && $("#c11212").is(":checked") && $("#c11213").is(":checked") &&
            $("#c11221").is(":checked") && $("#c11222").is(":checked") &&
            $("#c11231").is(":checked") && $("#c11232").is(":checked") && $("#c11233").is(":checked") &&
            $("#c11241").is(":checked") && $("#c11242").is(":checked") && $("#c11243").is(":checked") &&
            $("#c11251").is(":checked") && $("#c11252").is(":checked") &&
            // $("#c11311").is(":checked") && $("#c11312").is(":checked") && $("#c11313").is(":checked") && $("#c11314").is(":checked") && $("#c11315").is(":checked") && $("#c11316").is(":checked") &&
            // $("#c113211").is(":checked") && $("#c113212").is(":checked") && $("#c11322").is(":checked") && $("#c11323").is(":checked") &&
            $("#c1141").is(":checked") && $("#c1142").is(":checked") && $("#c1143").is(":checked") &&
            $("#c1151").is(":checked") && $("#c1152").is(":checked") && $("#c1153").is(":checked") &&
            $("#c11611").is(":checked") && $("#c11612").is(":checked") &&
            // $("#c11621").is(":checked") && $("#c11622").is(":checked") &&
            $("#c117").is(":checked") &&
            $("#c1181").is(":checked") && $("#c1182").is(":checked") &&
            $("#c11911").is(":checked") && $("#c11912").is(":checked") && $("#c11913").is(":checked") && $("#c11914").is(":checked") &&
            $("#c11921").is(":checked") && $("#c11922").is(":checked") && $("#c11923").is(":checked") && $("#c11924").is(":checked")) {
            $("input[type=file]").attr("required", "required");
            $("select").removeAttr("disabled")
            console.log("csh")
          } else {
            $("input[type=file]").removeAttr("required");
            $("select").attr("disabled", "disabled")
            console.log("csh else")
          }
        } else {
          if (
            $("#c11110").is(":checked") && $("#c11120").is(":checked") &&
            $("#c11211").is(":checked") && $("#c11212").is(":checked") && $("#c11213").is(":checked") &&
            $("#c11221").is(":checked") && $("#c11222").is(":checked") &&
            $("#c11231").is(":checked") && $("#c11232").is(":checked") && $("#c11233").is(":checked") &&
            $("#c11241").is(":checked") && $("#c11242").is(":checked") && $("#c11243").is(":checked") &&
            $("#c11251").is(":checked") && $("#c11252").is(":checked") &&
            // $("#c11311").is(":checked") && $("#c11312").is(":checked") && $("#c11313").is(":checked") && $("#c11314").is(":checked") && $("#c11315").is(":checked") && $("#c11316").is(":checked") &&
            // $("#c113211").is(":checked") && $("#c113212").is(":checked") && $("#c11322").is(":checked") && $("#c11323").is(":checked") &&
            $("#c1141").is(":checked") && $("#c1142").is(":checked") && $("#c1143").is(":checked") &&
            $("#c1151").is(":checked") && $("#c1152").is(":checked") && $("#c1153").is(":checked") &&
            $("#c11611").is(":checked") && $("#c11612").is(":checked") &&
            $("#c11621").is(":checked") && $("#c11622").is(":checked") &&
            $("#c117").is(":checked") &&
            $("#c1181").is(":checked") && $("#c1182").is(":checked") &&
            $("#c11911").is(":checked") && $("#c11912").is(":checked") && $("#c11913").is(":checked") && $("#c11914").is(":checked") &&
            $("#c11921").is(":checked") && $("#c11922").is(":checked") && $("#c11923").is(":checked") && $("#c11924").is(":checked")) {
            $("input[type=file]").attr("required", "required");
            $("select").removeAttr("disabled")
            console.log("csh 6k")
          } else {
            $("input[type=file]").removeAttr("required");
            $("select").attr("disabled", "disabled")
            console.log("csh 6k else")
          }
        }
      } else if ($("#group").val() == "TCC") {
        if ($("#pmtype").val() != "PM Stroke 2000") {
          if (
            $("#c11110").is(":checked") && $("#c11120").is(":checked") &&
            $("#c11211").is(":checked") && $("#c11212").is(":checked") && $("#c11213").is(":checked") &&
            $("#c11221").is(":checked") && $("#c11222").is(":checked") &&
            $("#c11231").is(":checked") && $("#c11232").is(":checked") && $("#c11233").is(":checked") &&
            $("#c11241").is(":checked") && $("#c11242").is(":checked") && $("#c11243").is(":checked") &&
            $("#c11251").is(":checked") && $("#c11252").is(":checked") &&
            $("#c11311").is(":checked") && $("#c11312").is(":checked") && $("#c11313").is(":checked") && $("#c11314").is(":checked") && $("#c11315").is(":checked") && $("#c11316").is(":checked") &&
            $("#c113211").is(":checked") && $("#c113212").is(":checked") && $("#c11322").is(":checked") && $("#c11323").is(":checked") &&
            $("#c1141").is(":checked") && $("#c1142").is(":checked") && $("#c1143").is(":checked") &&
            $("#c1151").is(":checked") && $("#c1152").is(":checked") && $("#c1153").is(":checked") &&
            $("#c11611").is(":checked") && $("#c11612").is(":checked") &&
            $("#c11621").is(":checked") && $("#c11622").is(":checked") &&
            $("#c117").is(":checked") &&
            $("#c1181").is(":checked") && $("#c1182").is(":checked") && $("#c1183").is(":checked") && $("#c1184").is(":checked") && $("#c1185").is(":checked") &&
            $("#c11911").is(":checked") && $("#c11912").is(":checked") && $("#c11913").is(":checked") && $("#c11914").is(":checked") &&
            $("#c11921").is(":checked") && $("#c11922").is(":checked") && $("#c11923").is(":checked") && $("#c11924").is(":checked")) {
            $("input[type=file]").attr("required", "required");
            $("select").removeAttr("disabled")
            console.log("tcc 6k")
          } else {
            $("input[type=file]").removeAttr("required");
            $("select").attr("disabled", "disabled")
            console.log("tcc 6k else")
          }
        } else {
          if (
            // $("#c11110").is(":checked") && $("#c11120").is(":checked") &&
            $("#c11211").is(":checked") && $("#c11212").is(":checked") && $("#c11213").is(":checked") &&
            $("#c11221").is(":checked") && $("#c11222").is(":checked") &&
            $("#c11231").is(":checked") && $("#c11232").is(":checked") && $("#c11233").is(":checked") &&
            $("#c11241").is(":checked") && $("#c11242").is(":checked") && $("#c11243").is(":checked") &&
            $("#c11251").is(":checked") && $("#c11252").is(":checked") &&
            $("#c11311").is(":checked") && $("#c11312").is(":checked") && $("#c11313").is(":checked") && $("#c11314").is(":checked") && $("#c11315").is(":checked") && $("#c11316").is(":checked") &&
            $("#c113211").is(":checked") && $("#c113212").is(":checked") && $("#c11322").is(":checked") && $("#c11323").is(":checked") &&
            $("#c1141").is(":checked") && $("#c1142").is(":checked") && $("#c1143").is(":checked") &&
            $("#c1151").is(":checked") && $("#c1152").is(":checked") && $("#c1153").is(":checked") &&
            $("#c11611").is(":checked") && $("#c11612").is(":checked") &&
            // $("#c11621").is(":checked") && $("#c11622").is(":checked") &&
            $("#c117").is(":checked") &&
            $("#c1181").is(":checked") && $("#c1182").is(":checked") && $("#c1183").is(":checked") && $("#c1184").is(":checked") && $("#c1185").is(":checked") &&
            $("#c11911").is(":checked") && $("#c11912").is(":checked") && $("#c11913").is(":checked") && $("#c11914").is(":checked") &&
            $("#c11921").is(":checked") && $("#c11922").is(":checked") && $("#c11923").is(":checked") && $("#c11924").is(":checked")) {
            $("input[type=file]").attr("required", "required");
            $("select").removeAttr("disabled")
            console.log("tcc")
          } else {
            $("input[type=file]").removeAttr("required");
            $("select").attr("disabled", "disabled")
            console.log("tcc else")
          }
        }
      } else if ($("#group").val() == "OPN") {
        if ($("#pmtype").val() != "PM Stroke 2000") {
          if (
            $("#c11110").is(":checked") && $("#c11120").is(":checked") &&
            $("#c11211").is(":checked") && $("#c11212").is(":checked") && $("#c11213").is(":checked") &&
            $("#c11221").is(":checked") && $("#c11222").is(":checked") &&
            $("#c11231").is(":checked") && $("#c11232").is(":checked") && $("#c11233").is(":checked") &&
            $("#c11241").is(":checked") && $("#c11242").is(":checked") && $("#c11243").is(":checked") &&
            $("#c11251").is(":checked") && $("#c11252").is(":checked") &&
            $("#c11311").is(":checked") && $("#c11312").is(":checked") && $("#c11313").is(":checked") && $("#c11314").is(":checked") && $("#c11315").is(":checked") && $("#c11316").is(":checked") &&
            $("#c113211").is(":checked") && $("#c113212").is(":checked") && $("#c11322").is(":checked") && $("#c11323").is(":checked") &&
            $("#c1141").is(":checked") && $("#c1142").is(":checked") && $("#c1143").is(":checked") &&
            $("#c1151").is(":checked") && $("#c1152").is(":checked") && $("#c1153").is(":checked") &&
            $("#c11611").is(":checked") && $("#c11612").is(":checked") &&
            $("#c11621").is(":checked") && $("#c11622").is(":checked") &&
            $("#c117").is(":checked") &&
            $("#c1181").is(":checked") && $("#c1182").is(":checked") && $("#c1183").is(":checked") && $("#c1184").is(":checked") &&
            $("#c11911").is(":checked") && $("#c11912").is(":checked") && $("#c11913").is(":checked") && $("#c11914").is(":checked") &&
            $("#c11921").is(":checked") && $("#c11922").is(":checked") && $("#c11923").is(":checked") && $("#c11924").is(":checked")) {
            $("input[type=file]").attr("required", "required");
            $("select").removeAttr("disabled", "disabled")
            console.log("opn 6k")
          } else {
            $("input[type=file]").removeAttr("required");
            $("select").attr("disabled", "disabled")
            console.log("opn 6k else")
          }
        } else {
          if (
            // $("#c11110").is(":checked") && $("#c11120").is(":checked") &&
            $("#c11211").is(":checked") && $("#c11212").is(":checked") && $("#c11213").is(":checked") &&
            $("#c11221").is(":checked") && $("#c11222").is(":checked") &&
            $("#c11231").is(":checked") && $("#c11232").is(":checked") && $("#c11233").is(":checked") &&
            $("#c11241").is(":checked") && $("#c11242").is(":checked") && $("#c11243").is(":checked") &&
            $("#c11251").is(":checked") && $("#c11252").is(":checked") &&
            $("#c11311").is(":checked") && $("#c11312").is(":checked") && $("#c11313").is(":checked") && $("#c11314").is(":checked") && $("#c11315").is(":checked") && $("#c11316").is(":checked") &&
            $("#c113211").is(":checked") && $("#c113212").is(":checked") && $("#c11322").is(":checked") && $("#c11323").is(":checked") &&
            $("#c1141").is(":checked") && $("#c1142").is(":checked") && $("#c1143").is(":checked") &&
            $("#c1151").is(":checked") && $("#c1152").is(":checked") && $("#c1153").is(":checked") &&
            $("#c11611").is(":checked") && $("#c11612").is(":checked") &&
            // $("#c11621").is(":checked") && $("#c11622").is(":checked") &&
            $("#c117").is(":checked") &&
            $("#c1181").is(":checked") && $("#c1182").is(":checked") && $("#c1183").is(":checked") && $("#c1184").is(":checked") &&
            $("#c11911").is(":checked") && $("#c11912").is(":checked") && $("#c11913").is(":checked") && $("#c11914").is(":checked") &&
            $("#c11921").is(":checked") && $("#c11922").is(":checked") && $("#c11923").is(":checked") && $("#c11924").is(":checked")) {
            $("input[type=file]").attr("required", "required");
            $("select").removeAttr("disabled", "disabled")
            console.log("opn")
          } else {
            $("input[type=file]").removeAttr("required");
            $("select").attr("disabled", "disabled")
            console.log("opn else")
          }
        }
      }
    }

    $(".datepicker").flatpickr({
      altInput: true,
      altFormat: "d-m-Y",
      dateFormat: "Ymd"
    });

    $("#btn-print").click(function () {
      var pmtid = $("#pmtid").val();
      alert("Print under construction, Preventive No : " + pmtid);
    });
  </script>
</body>

</html>