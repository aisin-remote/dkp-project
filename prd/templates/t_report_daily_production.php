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
            <li class="breadcrumb-item"><?php echo $template["group"]; ?></li>
            <li class="breadcrumb-item active"><?php echo $template["menu"]; ?></li>
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
                          <th class="align-middle">Date</th>
                          <th class="align-middle">Year</th>
                          <th class="align-middle">Month</th>
                          <th class="align-middle">Shift</th>
                          <th class="align-middle">Line DC</th>
                          <th class="align-middle">Leader</th>
                          <th class="align-middle">JP</th>
                          <th class="text-center align-middle">Planning Qty</th>
                          <th class="text-center align-middle">Prod. Qty</th>
                          <th class="text-center align-middle">NG Qty</th>
                          <th class="text-center align-middle">Lost Time(m)</th>
                          <th class="text-center align-middle">Efficiency (%)</th>
                          <th class="text-center align-middle">Details</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if (!empty($data["list"])) {
                          foreach ($data["list"] as $list) {
                            echo "<tr>" . "<td class='align-middle'>" . $list["prd_dt"] . "</td>"
                              . "<td class='align-middle'>" . $list["prd_year"] . "</td>"
                              . "<td class='align-middle'>" . $list["prd_month"] . "</td>"
                              . "<td class='align-middle'>" . $list["shift"] . "</td>"
                              . "<td class='align-middle'>" . $list["line_id"] . "</td>"
                              . "<td class='align-middle'>" . $list["ld_name"] . "</td>"
                              . "<td class='align-middle'>" . $list["jp_name"] . "</td>"
                              . "<td class='text-center pr-3 align-middle'>" . $list["pln_qty"] . "</td>"
                              . "<td class='text-center pr-3 align-middle'>" . $list["prd_qty"] . "</td>"
                              . "<td class='text-center pr-3 align-middle'>" . $list["ng_qty"] . "</td>"
                              . "<td class='text-center pr-3 align-middle'>" . $list["stop_time"] . "</td>"
                              . "<td class='text-center pr-3 align-middle'>" . $list["eff"] . "</td>"
                              . "<td class='text-center pr-3'>"
                              . "<a href='?action=$action&id=" . $list["line_id"] . "&id2=" . $list["prd_dt"] . "&id3=" . $list["shift"] . "&step=2" . "' class='btn btn-outline-dark btn-xs text-center mb-1'><i class='material-icons'>visibility</i> </a>"
                              . "</td>"
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
  <div class="modal fade" id="modal_filter" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modal_filter_label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <form method="get" action="#">
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
              <div class="col"><input type="text" name="date_from" class="form-control datepicker" value="<?php echo $date_from; ?>"></div>
              <label class="col-form-label px-3">to</label>
              <div class="col"><input type="text" name="date_to" class="form-control datepicker" value="<?php echo $date_to; ?>"></div>
            </div>
            <div class="row my-2">
              <div class="col-4"><label class="col-form-label">Year</label></div>
              <div class="col"><input type="text" name="prd_year" class="form-control" value="<?php echo $prd_year; ?>"></div>
            </div>
            <div class="row my-2">
              <div class="col-4"><label class="col-form-label">Month</label></div>
              <div class="col"><input type="text" name="prd_month" class="form-control" value="<?php echo $prd_month; ?>"></div>
            </div>
            <div class="row my-2">
              <div class="col-4"><label class="col-form-label">Shift</label></div>
              <div class="col"><input type="text" name="shift" class="form-control" value="<?php echo $shift; ?>"></div>
            </div>
            <div class="row my-2">
              <div class="col-4"><label class="col-form-label">Line DC</label></div>
              <div class="col"><input type="text" name="line_id" class="form-control" value="<?php echo $line_id; ?>"></div>
            </div>
            <div class="row my-2">
              <div class="col-4"><label class="col-form-label">Leader</label></div>
              <div class="col"><input type="text" name="ldid" class="form-control" value="<?php echo $ldid; ?>"></div>
            </div>
            <div class="row my-2">
              <div class="col-4"><label class="col-form-label">JP</label></div>
              <div class="col"><input type="text" name="jpid" class="form-control" value="<?php echo $jpid; ?>"></div>
            </div>
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
    $(document).ready(function() {
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
            title: "daily_production_report",
            className: 'btn btn-pale-green btn-sm',
            text: '<i class="material-icons">download</i>Download Excel',
          },
          {
            className: 'btn btn-pale-green-outlined btn-sm',
            text: '<i class="material-icons">filter_alt</i> Filter',
            action: function() {
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

      $('td').each(function() {
        if ($(this).html() == 'Completed') {
          $(this).css('color', 'green');
        } else if ($(this).html() == 'On Progress') {
          $(this).css('color', 'red');
        }
      });
    });
  </script>
</body>

</html>