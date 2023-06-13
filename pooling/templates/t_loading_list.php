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
          <ol class="breadcrumb mb-4 mt-4">
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
                  <!-- Edit Here -->
                  <div class="container-fluid">
                    <div class="table-responsive text-nowrap">
                      <form action="?action=<?php echo $action; ?>" method="POST">
                        <table class="table table-sm" id="data-table-x">
                          <thead>
                            <tr>
                              <th class="align-middle pl-2"><input id='checkAll' type='checkbox'
                                  style='height: 18px; width: 18px;' /></th>
                              <th class=''>Loading List Number</th>
                              <th class=''>PDS Number</th>
                              <th class=''>Customer</th>
                              <th class=''>P. Code</th>
                              <th class=''>Receive Area</th>
                              <th class=''>Cycle</th>
                              <th class=''>Delivery Date</th>
                              <th class=''>Delivery Time</th>
                              <th class=''>Pulling Status</th>
                              <th class=''>Delivery Status</th>
                              <!-- <th class='text-center'>Device Antenna</th> -->
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                            if (!empty($list)) {
                              foreach ($list as $row) {
                                if ($row["stats"] == "UNCOMPLETED") {
                                  $isDisable = "disabled";
                                } else {
                                  $isDisable = "";
                                }
                                echo
                                  "<tr>"
                                  . "<td class='align-middle chkbox pl-2'><input name='chk_id[]' type='checkbox' value='" . $row["ldnum"] . "' style='height: 18px; width: 18px;' " . $isDisable . "/></td>"
                                  . "<td class='align-middle'>" . $row["ldnum"] . "</td>"
                                  . "<td class='align-middle'>" . $row["pdsno"] . "</td>"
                                  . "<td class='align-middle'>" . $row["customer"] . "</td>"
                                  . "<td class='align-middle'>" . $row["werks"] . "</td>"
                                  . "<td class='align-middle'>" . $row["rcvar"] . "</td>"
                                  . "<td class='align-middle'>" . $row["cycle1"] . "</td>"
                                  . "<td class='align-middle'>" . $row["date_only"] . "</td>"
                                  . "<td class='align-middle'>" . $row["time_only"] . "</td>"
                                  . "<td class='align-middle'>" . $row["stats"] . "</td>"
                                  . "<td class='align-middle'>" . $row["dstat"] . "</td>"
                                  . "</tr>";
                              }
                            }
                            ?>
                          </tbody>
                        </table>
                        <button name="chg_status" type="submit" class="btn btn-template btn-sm mb-3">Delivered</button>
                      </form>
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
  <div class="modal fade" id="modal_filter" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="modal_filter_label" aria-hidden="true">
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
              <div class="col"><input type="text" name="date_from" class="form-control datepicker"
                  value="<?php echo $lddat_from; ?>"></div>
              <label class="col-form-label px-3">to</label>
              <div class="col"><input type="text" name="date_to" class="form-control datepicker"
                  value="<?php echo $lddat_to; ?>"></div>
            </div>
          </div>
          <div class="modal-body pt-1">
            <div class="row">
              <div class="col-2"><label class="col-form-label">Customer</label></div>
              <div class="col"><select name="customer" id="customer" class="form-control modalSelect01">
                  <option value="">Pilih Customer</option>
                  <?php
                  foreach ($customer as $cust) {
                    ?>
                    <option value="<?php echo $cust["lifnr"]; ?>" <?php
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
    $(document).ready(function () {
      $("#checkAll").change(function () {
        $("input[type='checkbox']:not(option[disabled])").prop("checked", this.checked);
      });
    });

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
        action: function () {
          $('#modal_filter').modal("show");

        }
      }
      ]
    });

    $('.modalSelect01').select2({
      dropdownParent: $('#modal_filter'),
      theme: 'bootstrap4',
      width: '100%'
    });

    $('td').each(function () {
      if ($(this).html() == 'COMPLETED') {
        $(this).css('color', 'green');
      } else if ($(this).html() == 'UNCOMPLETED') {
        $(this).css('color', 'red');
      } else if ($(this).html() == 'NOT DELIVERED') {
        $(this).css('color', 'red');
      } else if ($(this).html() == 'DELIVERED') {
        $(this).css('color', 'green');
      }
    });

    var table = $('#data-table-x').DataTable();
    table.on('draw.dt', function () {
      $('td').each(function () {
        if ($(this).html() == 'COMPLETED') {
          $(this).css('color', 'green');
        } else if ($(this).html() == 'UNCOMPLETED') {
          $(this).css('color', 'red');
        } else if ($(this).html() == 'NOT DELIVERED') {
          $(this).css('color', 'red');
        } else if ($(this).html() == 'DELIVERED') {
          $(this).css('color', 'green');
        }
      });
    });


  </script>
</body>

</html>