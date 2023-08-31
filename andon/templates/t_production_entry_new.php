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
  <?php include "common/t_nav_top_dashboard.php"; ?>
  <div id="layoutSidenav">
    <div id="layoutSidenav_content">
      <main>
        <div class="container-fluid">
          <!-- <ol class="breadcrumb mb-0 mt-2">
            <li class="breadcrumb-item"><?php echo $template["group"]; ?></li>
            <li class="breadcrumb-item active"><?php echo $template["menu"]; ?></li>
            <li class="breadcrumb-item active"><?php echo $template["submenu"]; ?></li>
            <li class="breadcrumb-item active">New</li>
          </ol> -->
          <?php
          if (isset($_GET["error"])) {
            echo '<div class="alert alert-danger" role="alert">
                      Error : ' . $_GET["error"] . '
                    </div>';
          }
          ?>
          <button class="btn mt-4 font-weight-bold" onclick="window.history.back()"
            style="background-color: transparent;"><span class="material-icons">
              arrow_back_ios
            </span>Back</button>
          <form method="post" id="my-form"
            action="?action=<?php echo $action; ?>&line=<?php echo $line; ?>&date=<?php echo $date; ?>&shift=<?php echo $shift; ?>">

            <div class="row">
              <div class="col-12">
                <div class="card border-0">
                  <div class="card-body">
                    <!-- Edit Here -->

                    <input type="hidden" name="line_id" id="line_id" class="form-control"
                      value="<?php echo $_GET["line"]; ?>">
                    <!-- <input type="hidden" name="shift" id="shift" class="form-control" value="<?php echo $_GET["shift"]; ?>"> -->
                    <input type="hidden" name="reff" id="reff" class="form-control" value="">

                    <div class="row">
                      <div class="col-6">
                        <div class="form-group row">
                          <label class="col-form-label col-lg-4 col-md-1 col-sm-12">Shift</label>
                          <div class="col-lg-8 col-md-2 col-sm-12">
                            <select name="shift" onchange="shiftCount()" id="shift" class="form-control select2"
                              readonly>
                              <?php
                              foreach ($shift_list as $row) {
                                ?>
                                <option value="<?php echo $row["seq"]; ?>" <?= ($row["seq"] == $shift_ori[0]["seq"]) ? "selected" : "" ?>><?php echo $row["pval1"]; ?></option>
                                <?php
                              }
                              ?>
                            </select>
                          </div>
                        </div>

                        <div class="form-group row">
                          <label class="col-form-label col-lg-4 col-md-1 col-sm-12">Date</label>
                          <div class="col-lg-8 col-md-2 col-sm-12">
                            <input type="text" id="prd_dt" name="prd_dt" class="form-control datepicker" maxlength="100"
                              value="<?php echo $date; ?>" readonly>
                          </div>
                        </div>

                        <div class="form-group row">
                          <label class="col-form-label col-lg-4 col-md-1 col-sm-12">Leader</label>
                          <div class="col-lg-8 col-md-2 col-sm-12">
                            <select name="ldid" id="ldid" class="form-control select2">
                              <?php
                              foreach ($ld_list as $row) {
                                ?>
                                <option value="<?php echo $row["empid"]; ?>"><?php echo $row["name1"]; ?></option>
                                <?php
                              }
                              ?>
                            </select>
                          </div>
                        </div>

                        <div class="form-group row">
                          <label class="col-form-label col-lg-4 col-md-1 col-sm-12">JP</label>
                          <div class="col-lg-8 col-md-2 col-sm-12">
                            <select name="jpid" id="jpid" class="form-control select2">
                              <?php
                              foreach ($jp_list as $row) {
                                ?>
                                <option value="<?php echo $row["empid"]; ?>"><?php echo $row["name1"]; ?></option>
                                <?php
                              }
                              ?>
                            </select>
                          </div>
                        </div>

                        <div class="form-group row">
                          <label class="col-form-label col-lg-4 col-md-1 col-sm-12">Group</label>
                          <div class="col-lg-8 col-md-2 col-sm-12">
                            <select name="group" id="group" class="form-control select2">
                              <option value="A">None</option>
                              <?php
                              foreach ($group as $row) {
                                ?>
                                <option value="<?= $row["group_id"] ?>"><?= $row["group_id"] ?></option>
                                <?php
                              }
                              ?>
                            </select>
                          </div>
                        </div>

                        <div class="form-group row">
                          <label class="col-form-label col-lg-4 col-md-1 col-sm-12">
                            <?= ($_GET["line"] == 'E14') ? 'POS 1' : 'Operator 1'; ?>
                          </label>
                          <div class="col-lg-8 col-md-2 col-sm-12">
                            <select name="op1id" id="op1id" class="form-control select2 g_opr">
                              <option value="">None</option>
                              <?php
                              foreach ($op_list as $row) {
                                ?>
                                <option value="<?php echo $row["empid"]; ?>"><?php echo $row["name1"]; ?></option>
                                <?php
                              }
                              ?>
                            </select>
                          </div>
                        </div>

                        <div class="form-group row">
                          <label class="col-form-label col-lg-4 col-md-1 col-sm-12">
                            <?= ($_GET["line"] == 'E14') ? 'POS 2' : 'Operator 2'; ?>
                          </label>
                          <div class="col-lg-8 col-md-2 col-sm-12">
                            <select name="op2id" id="op2id" class="form-control select2 g_opr">
                              <option value="">None</option>
                              <?php
                              foreach ($op_list as $row) {
                                ?>
                                <option value="<?php echo $row["empid"]; ?>"><?php echo $row["name1"]; ?></option>
                                <?php
                              }
                              ?>
                            </select>
                          </div>
                        </div>

                        <div class="form-group row">
                          <label class="col-form-label col-lg-4 col-md-1 col-sm-12">
                            <?= ($_GET["line"] == 'E14') ? 'POS 3' : 'Operator 3'; ?>
                          </label>
                          <div class="col-lg-8 col-md-2 col-sm-12">
                            <select name="op3id" id="op3id" class="form-control select2 g_opr">
                              <option value="">None</option>
                              <?php
                              foreach ($op_list as $row) {
                                ?>
                                <option value="<?php echo $row["empid"]; ?>"><?php echo $row["name1"]; ?></option>
                                <?php
                              }
                              ?>
                            </select>
                          </div>
                        </div>

                        <div class="form-group row">
                          <label class="col-form-label col-lg-4 col-md-1 col-sm-12">
                            <?= ($_GET["line"] == 'E14') ? 'POS 4' : 'Operator 4'; ?>
                          </label>
                          <div class="col-lg-8 col-md-2 col-sm-12">
                            <select name="op4id" id="op4id" class="form-control select2 g_opr">
                              <option value="">None</option>
                              <?php
                              foreach ($op_list as $row) {
                                ?>
                                <option value="<?php echo $row["empid"]; ?>"><?php echo $row["name1"]; ?></option>
                                <?php
                              }
                              ?>
                            </select>
                          </div>
                        </div>
                      </div>

                      <div class="col-6">
                        <div class="form-group row">
                          <label class="col-form-label col-lg-4 col-md-1 col-sm-12">
                            <?= ($_GET["line"] == 'E14') ? 'POS 5' : 'Operator 5'; ?>
                          </label>
                          <div class="col-lg-8 col-md-2 col-sm-12">
                            <select name="op5id" id="op5id" class="form-control select2 g_opr">
                              <option value="">None</option>
                              <?php
                              foreach ($op_list as $row) {
                                ?>
                                <option value="<?php echo $row["empid"]; ?>"><?php echo $row["name1"]; ?></option>
                                <?php
                              }
                              ?>
                            </select>
                          </div>
                        </div>

                        <div class="form-group row">
                          <label class="col-form-label col-lg-4 col-md-1 col-sm-12">
                            <?= ($_GET["line"] == 'E14') ? 'POS 6' : 'Operator 6'; ?>
                          </label>
                          <div class="col-lg-8 col-md-2 col-sm-12">
                            <select name="op6id" id="op6id" class="form-control select2 g_opr">
                              <option value="">None</option>
                              <?php
                              foreach ($op_list as $row) {
                                ?>
                                <option value="<?php echo $row["empid"]; ?>"><?php echo $row["name1"]; ?></option>
                                <?php
                              }
                              ?>
                            </select>
                          </div>
                        </div>

                        <div class="form-group row">
                          <label class="col-form-label col-lg-4 col-md-1 col-sm-12">
                            <?= ($_GET["line"] == 'E14') ? 'POS 7' : 'Operator 7'; ?>
                          </label>
                          <div class="col-lg-8 col-md-2 col-sm-12">
                            <select name="op7id" id="op7id" class="form-control select2 g_opr">
                              <option value="">None</option>
                              <?php
                              foreach ($op_list as $row) {
                                ?>
                                <option value="<?php echo $row["empid"]; ?>"><?php echo $row["name1"]; ?></option>
                                <?php
                              }
                              ?>
                            </select>
                          </div>
                        </div>

                        <div class="form-group row">
                          <label class="col-form-label col-lg-4 col-md-1 col-sm-12">
                            <?= ($_GET["line"] == 'E14') ? 'POS 8' : 'Operator 8'; ?>
                          </label>
                          <div class="col-lg-8 col-md-2 col-sm-12">
                            <select name="op8id" id="op8id" class="form-control select2 g_opr">
                              <option value="">None</option>
                              <?php
                              foreach ($op_list as $row) {
                                ?>
                                <option value="<?php echo $row["empid"]; ?>"><?php echo $row["name1"]; ?></option>
                                <?php
                              }
                              ?>
                            </select>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-form-label col-lg-4 col-md-1 col-sm-12">Material</label>
                          <div class="col-lg-8 col-md-2 col-sm-12">
                            <select name="dies_id" id="dies_id" class="form-control select2">
                              <?php
                              foreach ($matlist as $row) {
                                if (!empty($row["backno"])) {
                                  $backno = " (" . $row["backno"] . ")";
                                } else {
                                  $backno = "";
                                }
                                ?>
                                <option value="<?php echo $row["matnr"]; ?>"><?php echo $row["matnr"] . " - " . $row["mtart"] . " - " . $row["name1"] . $backno; ?></option>
                                <?php
                              }
                              ?>
                            </select>
                          </div>
                        </div>

                        <div class="form-group row">
                          <label class="col-form-label col-lg-4 col-md-1 col-sm-12">Cycle Time</label>
                          <div class="col-lg-8 col-md-2 col-sm-12">
                            <input type="number" name="cctime" id="cctime" step="any" min="0" class="form-control"
                              required>
                          </div>
                        </div>

                        <div class="form-group row">
                          <label class="col-form-label col-lg-4 col-md-1 col-sm-12">Total Target (Est)</label>
                          <div class="col-lg-8 col-md-2 col-sm-12">
                            <input type="number" name="total_target" id="total_target" step="1" min="0"
                              class="form-control">
                          </div>
                        </div>

                        <div class="form-group row">
                          <div class="col-lg-4 col-md-2 col-sm-12 d-sm-none d-md-block"></div>
                          <div class="col-lg-8 col-md-2 col-sm-12">
                            <input type="hidden" name="save" id="save" value="true">
                            <button type="submit" name="btn_save" id="btn_save" value="save"
                              class="btn btn-pale-green">Save
                              & Generate Production Entry</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row mb-2">

            </div>
          </form>
        </div>
      </main>
      <!-- <?php include 'common/t_footer.php'; ?> -->
    </div>
  </div>
  <input type="hidden" id="shift_count" value="">
  <input type="hidden" id="line" value="<?= $_GET["line"] ?>">

  <div class="modal fade" id="modal_delete" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="modal_upload_label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <form method="GET" action="" enctype="multipart/form-data">
          <div class="modal-header">
            <h5 class="modal-title" id="modal_upload_label"><span class="material-icons">warning</span> Dies Sedang
              Dalam Maintenance</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="input-group mb-3">
              <label class="custom-label" for="delete-confirmation">Dies ini sedang dalam maintenance!</label>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-dark" data-dismiss="modal">OK</button>
          </div>
      </div>
      </form>
    </div>
  </div>

  <?php include 'common/t_js.php'; ?>
  <script src="vendors/ega/js/scripts.js?time=<?php echo date("Ymdhis"); ?>" type="text/javascript"></script>
  <script>
    $(document).ready(function () {
      $(".datepicker").flatpickr({
        altInput: true,
        altFormat: 'd-m-Y',
        dateFormat: 'Ymd',
        disableMobile: "true",
        maxDate: "today"
      });

      getDefaultCycleTime();
      shiftCount();
    });

    $("#reff").val(document.referrer)

    $("#my-form").submit(function (event) {
      $("#btn_save").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Please Wait...');
      $("#btn_save").attr("disabled", "disabled");
    });

    $("#cctime").change(calculateTarget);

    function calculateTarget() {
      var cctime = parseInt($("#cctime").val());
      var shift_count = parseInt($("#shift_count").val());
      var total_target = Math.ceil((3600 / cctime) * shift_count);
      $("#total_target").val(total_target);
    }

    function shiftCount() {
      $.post("?action=shift_count", {
        shift: $("#shift").val()
      }, function (data) {
        $("#shift_count").val(data);
        calculateTarget();
      })
    }

    $("#dies_id").change(getDefaultCycleTime);
    $("#dies_id").change(getPreventive);

    $("#group").change(function () {
      var groupid = $("#group").val();
      $(".g_opr").val("").trigger("change");
      $.ajax({
        type: 'GET',
        url: '?action=api_get_opr',
        data: {
          group: groupid,
          line: $("#line").val()
        },
        success: function (response) {
          // handle the response here
          let data = $.parseJSON(response);
          // console.log(data[0]);
          for (let i = 0; i < data.length; i++) {
            console.log("#op" + (i + 1) + "id - " + data[i].empid);
            $("#op" + (i + 1) + "id option[value='" + data[i].empid + "']").prop("selected", true).change();
            // $("#op" + (i + 1)).val(data[i].empid);
          }
        },
        error: function (error) {
          // handle the error here
          alert(error);
        }
      });
    })

    function getDefaultCycleTime() {
      $.ajax({
        type: 'POST',
        url: '?action=api_get_default_cctime',
        data: {
          dies_id: $("#dies_id").val()
        },
        success: function (response) {
          // handle the response here
          $("#cctime").val(response.cctime);
          calculateTarget();
        },
        error: function (error) {
          // handle the error here
          alert(error);
        },
        dataType: 'json'
      });
    }

    function getPreventive() {
      var dies_id = $("#dies_id").val();
      $.ajax({
        url: '?action=api_get_dies_preventive',
        data: 'dies_id=' + dies_id,
        success: (function (data) {
          var json = data,
            obj = JSON.parse(json);
          if (obj.gstat === "P" && inputValue == formattedDate) {
            $("#modal_delete").modal("show");
          } else {
            console.log("Tidak sedang dalam preventive")
          }
        })
      })
    }

    var inputValue = $("#prd_dt").val();
    var currentDate = new Date();
    var formattedDate = currentDate.getFullYear().toString() +
      (currentDate.getMonth() + 1).toString().padStart(2, '0') +
      currentDate.getDate().toString().padStart(2, '0');
  </script>
</body>

</html>