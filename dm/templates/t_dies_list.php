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
            echo '<div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
                      Error : ' . $_GET["error"] . '
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>';
          }
          ?>

          <?php
          if (isset($_GET["success"])) {
            echo '<div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
                      Success : ' . $_GET["success"] . '
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>';
          }
          ?>
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-body" style="background-color: #F0F0F0;">
                  <div class="row">
                    <div class="col-lg-6 col-sm-12">
                      <!-- filter placement -->

                    </div>
                    <div class="col-lg-6 col-sm-12">
                      <div class="d-flex justify-content-end">
                        <!-- button placement -->
                        <a class="btn btn-dark-blue" href="<?php echo $action ?>?id=0"><span class="material-icons">add</span>New</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <div class="card mt-2">
                <div class="card-body">
                  <div class="table-responsive">
                    <!-- Edit Here -->
                    <form action="<?php echo $action; ?>" method="POST">
                      <table class="table table-striped table-sm" id="data-table-x">
                        <thead>
                          <tr>
                            <th class="align-middle pl-2"><input id='checkAll' type='checkbox' style='height: 18px; width: 18px;' /></th>
                            <th class="">Group</th>
                            <th class="">Model</th>
                            <th class="">Dies No</th>
                            <th class="">Description</th>
                            <th class="text-center">Total Stroke</th>
                            <th class="text-center">Running Stroke</th>
                            <th class="">Status</th>
                            <th class="">Location</th>
                            <th class="text-center">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          if (!empty($data["list"])) {
                            foreach ($data["list"] as $list) {
                              echo "<tr>"
                                . "<td class='align-middle chkbox pl-2'><input name='chk_id[]' type='checkbox' value='" . $list["dies_id"] . "' style='height: 18px; width: 18px;' /></td>"
                                . "<td class=''>" . $list["group_id"] . "</td>"
                                . "<td class=''>" . $list["model_id"] . "</td>"
                                . "<td class=''>" . $list["dies_no"] . "</td>"
                                . "<td class=''>" . $list["name1"] . "</td>"
                                . "<td class='text-center pr-3'>" . $formatted_number = number_format($list["stktot"], 0, '.', ',') . "</td>"
                                . "<td class='text-center pr-3'>" . $formatted_number = number_format($list["stkrun"], 0, '.', ',') . "</td>"
                                . "<td class=''>" . $list["stats"] . "</td>"
                                . "<td class=''>" . $list["iostat"] . "</td>"
                                . "<td class='text-center pr-3'>"
                                . "<a href='$action?id=" . $list["dies_id"] . "' class='btn btn-outline-dark btn-xs text-center mb-1'><i class='material-icons'>edit</i> edit</a>"
                                . "</td>"
                                . "</tr>";
                            }
                          }
                          ?>
                        </tbody>
                      </table>
                      <button name="chg_status" type="submit" class="btn btn-dark-blue btn-sm mb-3">Change Status of Selected Row(s)</button> <button name="io_main" type="submit" class="btn btn-dark-blue-outlined btn-sm mb-3">Repair to Vendor / Return to Factory</button>
                    </form>
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
          title: "Dies Asset",
          className: 'btn btn-dark-blue btn-sm',
          text: '<i class="material-icons">download</i>Download Excel',
          exportOptions: {
            columns: [1, 2, 3, 4, 5, 6, 7, 8]
          }
        }, ]
      });
    });

    $('td').each(function() {
      if ($(this).html() == 'Active') {
        $(this).css('color', 'green');
      } else if ($(this).html() == 'Inactive') {
        $(this).css('color', 'red');
      }
    });

    $('td').each(function() {
      if ($(this).html() == 'Factory') {
        $(this).css('color', 'green');
      } else if ($(this).html() == 'Vendor') {
        $(this).css('color', 'red');
      }
    });

    $("#checkAll").change(function() {
      $("input[type='checkbox']").prop("checked", this.checked);
    });
  </script>
</body>

</html>