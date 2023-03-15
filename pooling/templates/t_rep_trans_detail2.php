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
        <div class="container-fluid mt-2">
          <ol class="breadcrumb mb-1">
            <li class="breadcrumb-item"><?php echo $template["group"]; ?></li>
            <li class="breadcrumb-item active"><?php echo $template["menu"]; ?></li>
          </ol>

          <?php
          if (isset($_GET["error"])) {
            echo '<div class="mb-1 alert alert-danger alert-dismissible fade show" role="alert">
                      Error : ' . $_GET["error"] . '
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>';
          }
          ?>

          <?php
          if (isset($_GET["success"])) {
            echo '<div class="mb-1 alert alert-success alert-dismissible fade show" role="alert">
                      Success : ' . $_GET["success"] . '
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>';
          }
          ?>
        </div>
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <div class="card mb-3">
                <div class="card-body">
                  <!-- <div class="filter">
                    <div class="container-fluid">
                      <form method="get" action="#">
                        <input type="hidden" name="action" value="</?= $action; ?>">
                        <table class="table">
                          <tbody>
                            <tr>
                              <td class="align-middle pl-0" width="150px">Delivery Date from</td>
                              <td width="250px" class="pl-0"><input type="text" name="lddat_from" class="form-control datepicker"></td>
                              <td class="align-middle pl-0" width="150px">Customer</td>
                              <td width="250px" class="pl-0"><select name="customer" id="customer" class="form-control select2">
                                  <option value="">None</option>
                                  </?php
                                  foreach ($customer as $cust) {
                                  ?>
                                    <option value="</?php echo $cust["name1"]; ?>"></?php echo $cust["name1"]; ?></option>
                                  </?php
                                  }
                                  ?>
                                </select></td>
                              <td class="px-0" width="20px"><button type="submit" class="btn btn-primary" name="filter" value="filter" id="filter">Filter</button></td>
                              <td class="px-0" width="20px"><button type="button" class="btn btn-danger" name="reset" id="reset" value="reset">Reset</button></td>
                            </tr>
                          </tbody>
                        </table>
                      </form>
                    </div>
                  </div> -->
                  <div class="container-fluid">
                    <div class="table-responsive">
                      <table class="table table-sm" id="data-table-x">
                        <thead>
                          <tr>
                            <th class=''>No</th>
                            <th class=''>Loading List Number</th>
                            <th class=''>PDS Number</th>
                            <th class=''>Customer</th>
                            <th class=''>P. Code</th>
                            <th class=''>Receive Area</th>
                            <th class=''>Cycle</th>
                            <th class=''>Delivery Date</th>
                            <th class=''>Delivery Time</th>
                            <th class=''>Parts No.</th>
                            <th class=''>Cust. Parts No.</th>
                            <th class=''>Cust. Parts ID</th>
                            <th class=''>Internal Parts ID</th>
                            <th class=''>Kanban Qty</th>
                            <th class=''>Check</th>
                            <th class=''>Qty/Pack</th>
                            <th class=''>Qty</th>
                            <th class=''>Kanban Barcode</th>
                            <th class=''>Kanban RFID</th>
                            <th class=''>Part Name</th>
                            <!-- <th class='text-center'>Device Antenna</th> -->
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          if (!empty($list)) {
                            foreach ($list as $row) {
                              echo
                              "<tr>"
                                . "<td class='align-middle'>" . $row["ldseq"] . "</td>"
                                . "<td class='align-middle'>" . $row["ldnum"] . "</td>"
                                . "<td class='align-middle'>" . $row["pdsno"] . "</td>"
                                . "<td class='align-middle'>" . $row["customer"] . "</td>"
                                . "<td class='align-middle'>" . $row["werks"] . "</td>"
                                . "<td class='align-middle'>" . $row["rcvar"] . "</td>"
                                . "<td class='align-middle'>" . $row["cycle1"] . "</td>"
                                . "<td class='align-middle'>" . $row["date_only"] . "</td>"
                                . "<td class='align-middle'>" . $row["time_only"] . "</td>"
                                . "<td class='align-middle'>" . $row["matnr"] . "</td>"
                                . "<td class='align-middle'>" . $row["custpart"] . "</td>"
                                . "<td class='align-middle'></td>"
                                . "<td class='align-middle'></td>"
                                . "<td class='align-middle'>" . $row["menge"] . "</td>"
                                . "<td class='align-middle'></td>"
                                . "<td class='align-middle'>" . $row["perpack"] . "</td>"
                                . "<td class='align-middle'>" . $row["totqty"] . "</td>"
                                . "<td class='align-middle'>" . $row["kanban_i"] . "</td>"
                                . "<td class='align-middle'>" . $row["kanban_e"] . "</td>"
                                . "<td class='align-middle'>" . $row["name1"] . "</td>"
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
          </div>
        </div>
      </main>
      <?php include 'common/t_footer.php'; ?>
    </div>
  </div>
  <input type="hidden" id="usrid" value="<?php echo $_SESSION[LOGIN_SESSION]; ?>">

  <div class="modal fade" id="modal_filter" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modal_filter_label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <form method="get" action="#">
          <input type="hidden" name="action" value="<?= $action; ?>">
          <div class="modal-header">
            <h5 class="modal-title" id="modal_filter_label"><span class="material-icons">filter_alt</span> Filter</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body pb-1">
            <div class="row">
              <div class="col-2"><label class="col-form-label">Date</label></div>
              <div class="col"><input type="text" name="date_from" class="form-control datepicker" value="<?php echo $lddat_from; ?>"></div>
              <label class="col-form-label px-3">to</label>
              <div class="col"><input type="text" name="date_to" class="form-control datepicker" value="<?php echo $lddat_to; ?>"></div>
            </div>
          </div>
          <div class="modal-body pt-1">
            <div class="row">
              <div class="col-2"><label class="col-form-label">Customer</label></div>
              <div class="col"><select name="customer" id="customer" class="form-control modalSelect01">
                  <option value="">None</option>
                  <?php
                  foreach ($customer as $cust) {
                  ?>
                    <option value="<?php echo $cust["name1"]; ?>" <?php
                                                                  if ($cust['name1'] == $_GET["customer"]) {
                                                                    echo "selected";
                                                                  }
                                                                  ?>><?php echo $cust["name1"]; ?></option>
                  <?php
                  }
                  ?>
                </select></div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary" name="filter" id="filter" value="filter">Filter</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <?php include 'common/t_js.php'; ?>
  <script src="vendors/ega/js/scripts.js?time=<?php echo date("Ymdhis"); ?>" type="text/javascript"></script>
  <script>
    $(document).ready(function() {
      // $('.collapse').collapse();
      $(".datepicker").flatpickr({
        altInput: true,
        altFormat: "d-m-Y",
        dateFormat: "Ymd"
      });
      $("#data-table-x").DataTable({
        stateSave: true,
        order: [
          [0, 'asc']
        ],
        dom: "<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-2'l><'col-sm-12 col-md-4'f>>" +
          "<'row'<'col-sm-12'tr>>" +
          "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        buttons: [{
            className: 'btn btn-primary btn-sm',
            text: '<i class="material-icons">filter_alt</i> Filter',
            action: function() {
              $('#modal_filter').modal("show");

            }
          },
          {
            extend: 'csv',
            title: "Detail Kanban Loading List",
            className: 'btn btn-success btn-sm',
            text: '<i class="material-icons">text_snippet</i> CSV',
          },
          {
            extend: 'excel',
            title: "Detail Kanban Loading List",
            className: 'btn btn-outline-success btn-sm',
            text: '<i class="material-icons">download</i> Excel',
          }
        ]
      });

      $('.modalSelect01').select2({
        dropdownParent: $('#modal_filter'),
        theme: 'bootstrap4',
        width: '100%'
      });

      $('td').each(function() {
        if ($(this).html() == 'COMPLETED') {
          $(this).css('color', 'green');
        } else if ($(this).html() == 'UNCOMPLETED') {
          $(this).css('color', 'red');
        }
      });

      var table = $('#data-table-x').DataTable();
      table.on('draw.dt', function() {
        $('td').each(function() {
          if ($(this).html() == 'COMPLETED') {
            $(this).css('color', 'green');
          } else if ($(this).html() == 'UNCOMPLETED') {
            $(this).css('color', 'red');
          }
        });
      });
    });
  </script>
</body>

</html>