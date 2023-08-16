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
              <li class="breadcrumb-item">
                <a href="?action=<?=$action?>"><?php echo $template["group"]; ?></a>
              </li>
              <li class="breadcrumb-item active">
                <a href="?action=<?=$action?>&id=<?=$_GET["id"]?>"><?php echo $template["menu"]; ?></a>
              </li>
              <li class="breadcrumb-item active">
                Scanned Item
              </li>
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
                    <div class="container-fluid">
                      <div class="table-responsive">
                        <table class="table table-sm" id="data-table-x">
                          <thead>
                            <tr>
                              <th class=''>No.</th>
                              <th class=''>Item No.</th>
                              <th class='text-nowrap'>Loading List Number</th>
                              <th class='text-nowrap'>Serial Kanban Internal</th>
                              <th class='text-nowrap'>Kanban External</th>
                              <th class='text-nowrap'>Part Name</th>
                              <th class='text-nowrap'>Scan Date</th>
                              <th class='text-nowrap'>Scan Time</th>
                              <th class='text-nowrap'>Delivered Date</th>
                              <th class='text-nowrap'>Delivered Time</th>
                              <!-- <th class=''>Delivery Date</th>
                              <th class=''>Status</th>
                              <th class='text-center'>Action</th> -->
                              <!-- <th class='text-center'>Device Antenna</th> -->
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                            if (!empty($list)) {
                              foreach ($list as $row) {
                                echo
                                "<tr>"
                                . "<td class='align-middle'>" . $row["trseq"] . "</td>"
                                . "<td class='align-middle'>" . $row["ldseq"] . "</td>"
                                . "<td class='align-middle'>" . $row["ldnum"] . "</td>"
                                . "<td class='align-middle'>" . $row["kanban_i_srl"] . "</td>"
                                . "<td class='align-middle'>" . $row["kanban_e"] . "</td>"
                                . "<td class='align-middle text-nowrap'>" . $row["name1"] . "</td>"
                                . "<td class='align-middle'>" . $row["scan_date"] . "</td>"
                                . "<td class='align-middle'>" . $row["scan_time"] . "</td>"
                                . "<td class='align-middle'>" . $row["date_only"] . "</td>"
                                . "<td class='align-middle'>" . $row["time_only"] . "</td>"
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

    <div class="modal fade" id="modal_filter" data-backdrop="static" data-keyboard="false" tabindex="-1"
         aria-labelledby="modal_filter_label" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <form method="post" action="?action=<?php echo $action; ?>?delete=true">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="modal_filter_label"><span class="material-icons">delete</span>
                Delete Log</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="row my-2">
                <div class="col-1"><label class="col-form-label">Date</label></div>
                <div class="col"><input type="text" name="date_from" class="form-control datepicker"></div>
                <label class="col-form-label px-3">to</label>
                <div class="col"><input type="text" name="date_to" class="form-control datepicker"></div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-outline-danger" name="delete_log"
                      value="filter">Delete</button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <?php include 'common/t_js.php'; ?>
    <script src="vendors/ega/js/scripts.js?time=<?php echo date("Ymdhis"); ?>" type="text/javascript"></script>
    <script>
      $(document).ready(function () {
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
        });

        $('td').each(function () {
          if ($(this).html() == 'COMPLETED') {
            $(this).css('color', 'green');
          } else if ($(this).html() == 'UNCOMPLETED') {
            $(this).css('color', 'red');
          } else if ($(this).html() == 'DELIVERED') {
            $(this).css('color', 'green');
          } else if ($(this).html() == 'NOT DELIVERED') {
            $(this).css('color', 'red');
          }
        });

        var table = $('#data-table-x').DataTable();
        table.on('draw.dt', function () {
          $('td').each(function () {
            if ($(this).html() == 'COMPLETED') {
              $(this).css('color', 'green');
            } else if ($(this).html() == 'UNCOMPLETED') {
              $(this).css('color', 'red');
            } else if ($(this).html() == 'DELIVERED') {
              $(this).css('color', 'green');
            } else if ($(this).html() == 'NOT DELIVERED') {
              $(this).css('color', 'red');
            }
          });
        });
      });
    </script>
  </body>

</html>