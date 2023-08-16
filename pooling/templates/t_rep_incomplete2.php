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
                <?php echo $template["menu"]; ?>
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
                              <th class='text-center'>Item No.</th>
                              <th class=''>Loading List Number</th>
                              <th class=''>Parts No.</th>
                              <th class=''>Cust. Parts No.</th>
                              <th class=''>Kanban Qty</th>
                              <th class=''>Actual Qty</th>
                              <th class=''>Delivered Qty</th>
                              <th class=''>Qty/Pack</th>
                              <th class=''>Qty</th>
                              <th class=''>Status</th>
                              <th class=''>Delivery Status</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                            if (!empty($list)) {
                              foreach ($list as $row) {
                                echo
                                "<tr>"
                                . "<td class='align-middle text-center'><a href='?action=$action&id=" . $row["ldnum"] . "&seq=" . $row["ldseq"] . "' class=''>" . $row["ldseq"] . "</a></td>"
                                . "<td class='align-middle'><a href='?action=$action&id=" . $row["ldnum"] . "&seq=" . $row["ldseq"] . "' class=''>" . $row["ldnum"] . "</a></td>"
                                . "<td class='align-middle'><a href='?action=$action&id=" . $row["ldnum"] . "&seq=" . $row["ldseq"] . "' class=''>" . $row["matnr"] . "</a></td>"
                                . "<td class='align-middle'><a href='?action=$action&id=" . $row["ldnum"] . "&seq=" . $row["ldseq"] . "' class=''>" . $row["custpart"] . "</a></td>"
                                . "<td class='align-middle'>" . $row["menge"] . "</td>"
                                . "<td class='align-middle'>" . $row["wmeng"] . "</td>"
                                . "<td class='align-middle'>" . $row["dlv_qty"] . "</td>"
                                . "<td class='align-middle'>" . $row["perpack"] . "</td>"
                                . "<td class='align-middle'>" . $row["totqty"] . "</td>"
                                . "<td class='align-middle'>" . $row["stats"] . "</td>"
                                . "<td class='align-middle'>" . $row["dstat"] . "</td>"
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

        <div class="modal-content">
          <form id="my_form" method="post" action="?action=<?php echo $action; ?>">
            <div class="modal-header">
              <h5 class="modal-title" id="modal_filter_label"><span class="material-icons">checklist_rtl</span>
                Complete Manual Loading List</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <input type="hidden" name="set_complete" value="true">
              <input type="hidden" name="ldnum" value="<?=$_GET["id"]?>">
              <label for="remarks" class="col-form-label">Remarks</label>
              <textarea id="remarks" name="remarks" class="form-control" maxlength="500" required></textarea>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-outline-primary" name="btn_set_complete" value="true">Submit</button>
            </div>
          </form>
        </div>

      </div>
    </div>

    <?php include 'common/t_js.php'; ?>
    <script src="vendors/ega/js/scripts.js?time=<?php echo date("Ymdhis"); ?>" type="text/javascript"></script>
    <script>
      $("#my_form").submit(function(){
        $(".btn").html('<div class="spinner-border spinner-border-sm" role="status"></div> Please Wait...');
        $(".btn").attr("disabled","disabled");
      });
      
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
          dom: "<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-2'l><'col-sm-12 col-md-4'f>>" +
                  "<'row'<'col-sm-12'tr>>" +
                  "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
          buttons: [{
              title: "Detail Loading List",
              className: 'btn btn-success btn-sm',
              text: '<i class="material-icons">text_snippet</i> Set Complete',
              action: function () {
                $("#modal_filter").modal("show");
              }
            }
          ]
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