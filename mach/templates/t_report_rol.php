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
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                      Error : ' . $_GET["error"] . '
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>';
          }
          ?>

          <?php
          if (isset($_GET["success"])) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                      Success : ' . $_GET["success"] . '
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>';
          }
          ?>
          <div class="row">
            <div class="col-12">
              <div class="card mt-2">
                <div class="card-body">
                  <div class="table-responsive">
                    <!-- Edit Here -->
                    <table class="table table-sm table-striped" id="data-table-x">
                      <thead>
                        <tr>
                          <th class="text-nowrap align-middle" rowspan="2">Date</th>
                          <th class="text-nowrap align-middle" rowspan="2">Shift</th>
                          <th class="text-nowrap align-middle" rowspan="2">Line</th>
                          <th class="text-nowrap align-middle" rowspan="2">Model</th>
                          <th class="text-nowrap align-middle" rowspan="2">Dies</th>
                          <th class="text-nowrap align-middle" rowspan="2">Hour</th>
                          <th class="text-nowrap align-middle" rowspan="2">LOT</th>
                          <th class="text-nowrap align-middle" rowspan="2">Qty NG ROL (PCS)</th>
                          <th class="text-nowrap align-middle" rowspan="2">Leak General</th>
                          <th class="text-nowrap align-middle" rowspan="2">Leak OP</th>
                          <th class="text-nowrap align-middle" rowspan="2">Leak WP</th>
                          <th class="text-nowrap align-middle" rowspan="2">Kurokawa</th>
                          <th class="text-nowrap align-middle" rowspan="2">Kontaminasi</th>
                          <th class="text-nowrap align-middle" rowspan="2">Peeling</th>
                          <th class="text-nowrap align-middle" rowspan="2">Crack</th>
                          <th class="text-nowrap align-middle" rowspan="2">Burrysasi</th>
                          <th class="text-nowrap align-middle" rowspan="2">Trial</th>
                          <th class="text-nowrap text-center" colspan="6">Porosity</th>
                          <th class="text-nowrap align-middle" rowspan="2">Others</th>
                          <th class="text-nowrap align-middle" rowspan="2">Keterangan</th>
                        </tr>
                        <tr>
                          <th>NE SENSOR</th>
                          <th>H/C</th>
                          <th>OIL SEAL</th>
                          <th>OIL PUMP</th>
                          <th>WATER PUMP</th>
                          <th>OTHERS</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if (!empty($data["list"])) {
                          foreach ($data["list"] as $list) {
                            echo "<tr>" . "<td class='text-nowrap'>" . $list["prd_dt"] . "</td>"
                              . "<td class='text-nowrap'>" . $list["pval1"] . "</td>"
                              . "<td class='text-nowrap'>" . $list["line_name"] . "</td>"
                              . "<td class='text-nowrap'>" . $list["group_id"] . " - " . $list["model_id"] . " - " . explode(" ", $list["dies_name"])[2] . "</td>"
                              . "<td class='text-nowrap'>" . $list["dies_no"] . "</td>"
                              . "<td class='text-nowrap'>" . $list["time_start"] . " - " . $list["time_end"] . "</td>"
                              . "<td class='text-nowrap text-center'> - </td>"
                              . "<td class='text-nowrap text-center'>" . $list["ng_rol"] . "</td>"
                              . "<td class='text-nowrap text-center'>" . $list["ng_rol7"] . "</td>"
                              . "<td class='text-nowrap text-center'>" . $list["ng_rol5"] . "</td>"
                              . "<td class='text-nowrap text-center'>" . $list["ng_rol6"] . "</td>"
                              . "<td class='text-nowrap text-center'>" . $list["ng_rol3"] . "</td>"
                              . "<td class='text-nowrap text-center'>" . $list["ng_rol4"] . "</td>"
                              . "<td class='text-nowrap text-center'>" . $list["ng_rol2"] . "</td>"
                              . "<td class='text-nowrap text-center'>" . $list["ng_rol8"] . "</td>"
                              . "<td class='text-nowrap text-center'>" . $list["ng_rol9"] . "</td>"
                              . "<td class='text-nowrap text-center'>" . $list["ng_rol10"] . "</td>"
                              . "<td class='text-nowrap text-center'>" . $list["ng_rol1"] . "</td>"
                              . "<td class='text-nowrap text-center'>" . $list["ng_rol12"] . "</td>"
                              . "<td class='text-nowrap text-center'>" . $list["ng_rol13"] . "</td>"
                              . "<td class='text-nowrap text-center'>" . $list["ng_rol14"] . "</td>"
                              . "<td class='text-nowrap text-center'>" . $list["ng_rol15"] . "</td>"
                              . "<td class='text-nowrap text-center'>" . $list["ng_rol16"] . "</td>"
                              . "<td class='text-nowrap text-center'>" . $list["ng_rol11"] . "</td>"
                              . "<td class='text-nowrap'>" . $list["desc1"] . "</td>"
                              . "</tr>";
                          }
                        }
                        ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">

          </div>
        </div>
      </main>
      <?php include 'common/t_footer.php'; ?>
    </div>
  </div>
  <div class="modal fade" id="modal_filter" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="modal_filter_label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <form method="get" action="#">
        <input type="hidden" name="action" value="<?= $action ?>">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modal_filter_label"><span class="material-icons">filter_alt</span> Filter</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row my-2">
              <div class="col-4"><label class="col-form-label">Start Date</label></div>
              <div class="col"><input type="text" name="date_from" class="form-control datepicker"
                  value="<?php echo $date_from; ?>"></div>
              <label class="col-form-label px-3">to</label>
              <div class="col"><input type="text" name="date_to" class="form-control datepicker"
                  value="<?php echo $date_to; ?>"></div>
            </div>
            <!-- <div clas
           -->
            <div class="row my-2">
              <div class="col-4"><label class="col-form-label">Shift</label></div>
              <!-- <div class="col"><input type="text" name="shift" class="form-control" value="<?php echo $shift; ?>"></div> -->
              <div class="col">
                <select name="shift" id="shift" class="form-control select2" style="width: 300px">
                  <option value="" selected>Select Shift</option>
                  <?php
                  foreach ($shiftlist as $s) {
                    ?>
                    <option value="<?php echo $s["seq"]; ?>" <?php if ($s["pval1"] == $_GET["shift"]) {
                         echo "selected";
                       } ?>><?php echo $s["pval1"]; ?></option>
                    <?php
                  }
                  ?>
                </select>
              </div>
            </div>
            <div class="row my-2">
              <div class="col-4"><label class="col-form-label">Line DC</label></div>
              <div class="col"><select name="line_id" id="line_id" class="form-control select2" style="width: 300px">
                  <option value="" selected>Pilih Line</option>
                  <?php
                  foreach ($line as $group) {
                    ?>
                    <option value="<?php echo $group["line_id"]; ?>"><?php echo $group["name1"]; ?></option>
                    <?php
                  }
                  ?>
                </select></div>
            </div>
            <div class="row my-2">
              <div class="col-4"><label class="col-form-label">Group</label></div>
              <div class="col"><select name="group_id" id="group_id" class="form-control select2" style="width: 300px">
                  <option value="" selected>Pilih Group</option>
                  <?php
                  foreach ($group_list as $group) {
                    ?>
                    <option value="<?php echo $group["pval1"]; ?>"><?php echo $group["pval1"]; ?></option>
                    <?php
                  }
                  ?>
                </select></div>
            </div>
            <div class="row my-2">
              <div class="col-4"><label class="col-form-label">Model</label></div>
              <div class="col"><select name="model_id" id="model_id" class="form-control select2" style="width: 300px">
                  <option value="" selected>Pilih Model</option>
                  <?php
                  foreach ($model_list as $model) {
                    ?>
                    <option value="<?php echo $model["model_id"]; ?>"><?php echo $model["model_id"]; ?></option>
                    <?php
                  }
                  ?>
                </select></div>
            </div>
            <div class="row my-2">
              <div class="col-4"><label class="col-form-label">Dies No #</label></div>
              <div class="col"><select name="dies_id" id="dies_id" class="form-control select2" style="width: 300px">
                  <option value="" selected>Pilih Dies</option>
                  <?php
                  foreach ($diesid_list as $dies) {
                    ?>
                    <option value="<?php echo $dies["dies_id"]; ?>"><?php echo $dies["dies_no"] . " - " . $dies["name1"]; ?></option>
                    <?php
                  }
                  ?>
                </select></div>
            </div>
            <!-- <div class="row my-2">
              <div class="col-4"><label class="col-form-label">Leader</label></div>
              <div class="col"><input type="text" name="ldid" class="form-control" value="<?php echo $ldid; ?>"></div>
            </div>
            <div class="row my-2">
              <div class="col-4"><label class="col-form-label">JP</label></div>
              <div class="col"><input type="text" name="jpid" class="form-control" value="<?php echo $jpid; ?>"></div>
            </div> -->
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-pale-green" name="filter" value="filter">Apply Filter</button>
          </div>
        </div>
      </form>
    </div>
  </div>
  <?php include 'common/t_js.php'; ?>
  <script src="vendors/ega/js/scripts.js?time=<?php echo date("Ymdhis"); ?>" type="text/javascript"></script>
  <script>
    $(document).ready(function () {
      $("#data-table-x").DataTable({
        stateSave: true,
        order: [
          [0, 'desc']
        ],
        dom: "<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-2'l><'col-sm-12 col-md-4'f>>" +
          "<'row'<'col-sm-12'tr>>" +
          "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        buttons: [{
          extend: 'excel',
          title: "daily_production_report_content_stop",
          className: 'btn btn-pale-green btn-sm',
          text: '<i class="material-icons">download</i>Download Excel'
        },
        {
          className: 'btn btn-pale-green-outlined btn-sm',
          text: '<i class="material-icons">filter_alt</i> Filter',
          action: function () {
            $('#modal_filter').modal("show");

          }
        }
        ]
      });

      $(".datepicker").flatpickr({
        altInput: true,
        altFormat: "d-m-Y",
        dateFormat: "Ymd"
      });

      $('td').each(function () {
        if ($(this).html() == 'Completed') {
          $(this).css('color', 'green');
        } else if ($(this).html() == 'On Progress') {
          $(this).css('color', 'red');
        }
      });
    });
    $("#group_id").change(function () {
      getDiesModel($("#group_id").val());
    });

    function getDiesModel(group_id) {
      $("#model_id").empty();
      var first_model = "";
      $.getJSON("?action=api_get_dies_model", {
        group: group_id
      }, function (data) {
        var items = "";
        //$("#model_id").empty();
        var $i = 0
        $.each(data, function (key, val) {
          // if ($i == 0) {
          //     first_model = val.model_id;
          //     if (first_model.length > 0) {
          //         getDiesList(first_model);
          //     }
          // }
          console.log(val.model_id);
          items += "<option value='" + val.model_id + "'>" + val.model_id + "</option>";
          $i++;
        });

        $("#model_id").html(items);
      });
    }

    $("#model_id").change(function () {
      getDiesList($("#group_id").val(), $("#model_id").val());
    });

    function getDiesList(group_id, model_id) {
      $("#dies_id").empty();
      $.getJSON("?action=api_get_dies_list", {
        model: model_id,
        group_id: group_id
      }, function (data) {
        var items = "";
        //$("#model_id").empty();

        $.each(data, function (key, val) {
          console.log(val.model_id);
          if (val.model_id == model_id) {
            items += "<option value='" + val.dies_id + "'>" + val.dies_no + " - " + val.name1 + "</option>";
          }
        });

        $("#dies_id").html(items);
      });
    } $("#group_id").change(function () {
      getDiesModel($("#group_id").val());
    });

    function getDiesModel(group_id) {
      $("#model_id").empty();
      var first_model = "";
      $.getJSON("?action=api_get_dies_model", {
        group: group_id
      }, function (data) {
        var items = "";
        //$("#model_id").empty();
        var $i = 0
        $.each(data, function (key, val) {
          if ($i == 0) {
            first_model = val.model_id;
            if (first_model.length > 0) {
              items += "<option value=''>Pilih Model</option>"
            }
          }
          console.log(val.model_id);
          items += "<option value='" + val.model_id + "'>" + val.model_id + "</option>";
          $i++;
        });

        $("#model_id").html(items);
      });
    }

    $("#model_id").change(function () {
      getDiesList($("#group_id").val(), $("#model_id").val());
    });

    function getDiesList(group_id, model_id) {
      $("#dies_id").empty();
      $.getJSON("?action=api_get_dies_list", {
        model: model_id,
        group_id: group_id
      }, function (data) {
        var items = "";
        //$("#model_id").empty();

        $.each(data, function (key, val) {
          console.log(val.model_id);
          if (val.model_id == model_id) {
            items += "<option value='" + val.dies_id + "'>" + val.dies_no + " - " + val.name1 + "</option>";
          }
        });

        $("#dies_id").html(items);
      });
    }
  </script>
</body>

</html>