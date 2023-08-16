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
                          <th class="text-nowrap">Document No</th>
                          <th class="text-nowrap">Inspection Date</th>
                          <th class="text-nowrap">Inspection Time</th>
                          <th class="text-nowrap">Shift</th>
                          <th class="text-nowrap">Part No</th>
                          <th class="text-nowrap">Part Name</th>
                          <th class="text-nowrap">Inspector</th>
                          <th class="text-nowrap">Status</th>
                          <th class="text-center text-nowrap">Action</th>
                        </tr>
                      </thead>
                      <tbody class="">
                        <?php if (!empty($data_header)) {
                          foreach ($data_header as $list) {
                            echo "<tr>" . "<td class='text-nowrap align-middle'>" . $list["doc_no"] . "</td>"
                              . "<td class='text-nowrap align-middle'>" . $list["date"] . "</td>"
                              . "<td class='text-nowrap align-middle'>" . $list["time"] . "</td>"
                              . "<td class='text-nowrap align-middle'>" . $list["pval1"] . "</td>"
                              . "<td class='text-nowrap align-middle'>" . $list["partno"] . "</td>"
                              . "<td class='text-nowrap align-middle'>" . $list["partname"] . "</td>"
                              . "<td class='text-nowrap align-middle'>" . $list["inspector"] . "</td>"
                              . "<td class='text-nowrap align-middle'>" . $list["stats"] . "</td>"
                              . "<td class='text-center pr-3'>"
                              . "<a href='?action=$action&id=" . $list["doc_no"] . "&date=" . date_format(new DateTime($list["date"]), 'Ymd') . "&shift=" . $list["shift"] . "&export=true" . "' class='btn btn-primary btn-sm text-center'><i class='material-icons'>download</i> Excel</a>"
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
            <div class="row my-2">
              <div class="col-4"><label class="col-form-label">Shift</label></div>
              <div class="col"><select name="shift" id="shift"
                  class="form-control select2" style="width: 300px">
                  <option value="" selected>Pilih Shift</option>
                  <?php
                  foreach ($data_shift as $shift) {
                    ?>
                    <option value="<?php echo $shift["seq"]; ?>" <?= ($shift["seq"] == $_GET["shift"]) ? 'selected' : '' ?>><?php echo $shift["pval1"]; ?></option>
                    <?php
                  }
                  ?>
                </select></div>
            </div>
            <div class="row my-2">
              <div class="col-4"><label class="col-form-label">Part Name</label></div>
              <div class="col">
                <select name="partname" id="partname"
                  class="form-control select2" style="width: 300px">
                  <option value="" selected>Pilih Part Name</option>
                  <?php
                  foreach ($data_part as $row) {
                    ?>
                    <option value="<?php echo $row["partno"]; ?>" <?= ($row["partno"] == $_GET["partname"]) ? 'selected' : '' ?>><?= $row["partno"] ?> - <?php echo $row["name1"]; ?></option>
                    <?php
                  }
                  ?>
                </select></div>
            </div>
            <div class="row my-2">
              <div class="col-4"><label class="col-form-label">Part Name</label></div>
              <div class="col">
                <select name="status" id="status"
                  class="form-control select2" style="width: 300px">
                  <option value="" selected>All</option>
                  <option value="C">Completed</option>
                  <option value="N">On Progress</option>
                  
                </select></div>
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
          className: 'btn btn-pale-green btn-sm',
          text: '<i class="material-icons">download</i>Download Excel',
          action: function () {
            window.location.href = window.location.href + "&download=true";
            // window.location.href = "?action=<?php echo $action; ?>&download=true";
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
  </script>
</body>

</html>