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
                          <th class="text-nowrap align-middle" rowspan="2">JP</th>
                          <th class="text-nowrap align-middle" rowspan="2">Product</th>
                          <th class="text-nowrap align-middle" rowspan="2">Model</th>
                          <th class="text-nowrap align-middle" rowspan="2">Dies</th>
                          <th class="text-nowrap align-middle" rowspan="2">Hour</th>
                          <th class="text-nowrap align-middle" rowspan="2">CT</th>
                          <th class="text-center text-nowrap align-middle" rowspan="2">Plan Qty</th>
                          <th class="text-center text-nowrap align-middle" rowspan="2">Prod. OK Qty</th>
                          <th class="text-center text-nowrap" colspan="2">NG</th>
                          <th class="text-center text-nowrap" colspan="2">Losstime (m)</th>
                          <th class="text-center text-nowrap align-middle" rowspan="2">Prod. Time</th>
                          <th class="text-center text-nowrap align-middle" rowspan="2">Efficiency (%)</th>
                          <th class="text-center text-nowrap align-middle" rowspan="2">RIL (%)</th>
                          <th class="text-center text-nowrap align-middle" rowspan="2">ROL (%)</th>
                          <th class="text-center text-nowrap align-middle" rowspan="2">Losstime (%)</th>
                          <th class="text-center text-nowrap align-middle" rowspan="2">Dandori (%)</th>
                          <th class="text-center text-nowrap align-middle" rowspan="2">Baratsuki (%)</th>
                          <th class="text-center text-nowrap align-middle" rowspan="2">Setup (%)</th>
                          <th class="text-center text-nowrap align-middle" rowspan="2">5S (%)</th>
                          <th class="text-center text-nowrap align-middle" rowspan="2">Apr Leader By</th>
                          <th class="text-center text-nowrap align-middle" rowspan="2">Apr SPV By</th>
                        </tr>
                        <tr>
                          <th class="text-center text-nowrap">RIL</th>
                          <th class="text-center text-nowrap">ROL</th>
                          <th class="text-center text-nowrap">Planning</th>
                          <th class="text-center text-nowrap">Unplanning</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if (!empty($report_detail)) {
                          foreach ($report_detail as $list) {
                            echo "<tr>" . "<td class='text-nowrap'>" . $list["prd_dt"] . "</td>"
                              . "<td class='text-nowrap'>" . $list["pval1"] . "</td>"
                              . "<td class='text-nowrap'>" . $list["line_name"] . "</td>"
                              . "<td class='text-nowrap'>" . $list["operator"] . "</td>"
                              . "<td class='text-nowrap'>" . $list["group_id"] . "</td>"
                              . "<td class='text-nowrap'>" . $list["group_id"] . " - " . $list["model_id"] . " - " . explode(" ", $list["dies_name"])[2] . "</td>"
                              . "<td class='text-center text-nowrap'>" . $list["dies_no"] . "</td>"
                              . "<td class='text-center pr-3 text-nowrap'>" . $list["time_start"] . " - " . $list["time_end"] . "</td>"
                              . "<td class='text-center pr-3 text-nowrap'>" . $list["cctime"] . "</td>"
                              . "<td class='text-center pr-3 text-nowrap'>" . $list["pln_qty"] . "</td>"
                              . "<td class='text-center pr-3 text-nowrap'>" . $list["prd_qty"] . "</td>"
                              . "<td class='text-center pr-3 text-nowrap'>" . $list["ng_ril"] . "</td>"
                              . "<td class='text-center pr-3 text-nowrap'>" . $list["ng_rol"] . "</td>"
                              . "<td class='text-center pr-3 text-nowrap'>" . $list["planning"] . "</td>"
                              . "<td class='text-center pr-3 text-nowrap'>" . $list["unplanning"] . "</td>"
                              . "<td class='text-center pr-3 text-nowrap'>" . $list["prd_time"] . "</td>"
                              . "<td class='text-center pr-3 text-nowrap'>" . $list["eff"] . "</td>"
                              . "<td class='text-center pr-3 text-nowrap'>" . $list["ril%"] . "</td>"
                              . "<td class='text-center pr-3 text-nowrap'>" . $list["rol%"] . "</td>"
                              . "<td class='text-center pr-3 text-nowrap'>" . $list["losstime%"] . "</td>"
                              . "<td class='text-center pr-3 text-nowrap'>" . $list["dandori%"] . "</td>"
                              . "<td class='text-center pr-3 text-nowrap'>" . $list["baratsuki%"] . "</td>"
                              . "<td class='text-center pr-3 text-nowrap'>" . $list["setup%"] . "</td>"
                              . "<td class='text-center pr-3 text-nowrap'>" . $list["limas%"] . "</td>"
                              . "<td class='text-center pr-3 text-nowrap'>" . $list["apr_name"] . "</td>"
                              . "<td class='text-center pr-3 text-nowrap'>" . $list["spv_name"] . "</td>"
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
        "ordering": false,
        dom: "<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-2'l><'col-sm-12 col-md-4'f>>" +
          "<'row'<'col-sm-12'tr>>" +
          "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        buttons: [{
          extend: 'excelHtml5',
          title: "daily_production_report_detail",
          className: 'btn btn-pale-green btn-sm',
          text: '<i class="material-icons">download</i>Download Excel',
          customize: function (xlsx) {

            //copy _createNode function from source
            function _createNode(doc, nodeName, opts) {
              var tempNode = doc.createElement(nodeName);

              if (opts) {
                if (opts.attr) {
                  $(tempNode).attr(opts.attr);
                }

                if (opts.children) {
                  $.each(opts.children, function (key, value) {
                    tempNode.appendChild(value);
                  });
                }

                if (opts.text !== null && opts.text !== undefined) {
                  tempNode.appendChild(doc.createTextNode(opts.text));
                }
              }

              return tempNode;
            }

            var sheet = xlsx.xl.worksheets['sheet1.xml'];
            var mergeCells = $('mergeCells', sheet);
            mergeCells[0].children[0].remove(); // remove merge cell 1st row

            var rows = $('row', sheet);
            rows[0].children[0].remove(); // clear header cell

            // create new cell
            rows[0].appendChild(_createNode(sheet, 'c', {
              attr: {
                t: 'inlineStr',
                r: 'L1', //address of new cell
                s: 51 // center style - https://www.datatables.net/reference/button/excelHtml5
              },
              children: {
                row: _createNode(sheet, 'is', {
                  children: {
                    row: _createNode(sheet, 't', {
                      text: 'NG'
                    })
                  }
                })
              }
            }));


            // set new cell merged
            mergeCells[0].appendChild(_createNode(sheet, 'mergeCell', {
              attr: {
                ref: 'L1:M1' // merge address
              }
            }));

            mergeCells.attr('count', mergeCells.attr('count') + 1);

            // create new cell
            rows[0].appendChild(_createNode(sheet, 'c', {
              attr: {
                t: 'inlineStr',
                r: 'N1', //address of new cell
                s: 51 // center style - https://www.datatables.net/reference/button/excelHtml5
              },
              children: {
                row: _createNode(sheet, 'is', {
                  children: {
                    row: _createNode(sheet, 't', {
                      text: 'Losstime (m)'
                    })
                  }
                })
              }
            }));


            // set new cell merged
            mergeCells[0].appendChild(_createNode(sheet, 'mergeCell', {
              attr: {
                ref: 'N1:O1' // merge address
              }
            }));

            mergeCells.attr('count', mergeCells.attr('count') + 1);
          }
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