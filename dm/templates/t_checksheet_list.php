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
            <li class="breadcrumb-item active"><?php echo $template["group"]; ?></li>
            <li class="breadcrumb-item"><?php echo $template["menu"]; ?></li>
            <li class="breadcrumb-item"><?php echo $template["submenu"]; ?></li>
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
              <div class="card" style="background-color: #F0F0F0;">
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-6 col-sm-12">
                      <!-- filter placement -->

                    </div>
                    <div class="col-lg-6 col-sm-12">
                      <div class="d-flex justify-content-end">
                        <!-- button placement -->
                        <a class="btn btn-dark-blue btn-sm mx-2" href="<?php echo $action ?>?id=0&step=1">New Preventive Maintenance</a>
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
                    <table class="table table-striped table-sm" id="data-table">
                      <thead>
                        <tr>
                          <th class="">PM. Doc. No.</th>
                          <th class="">Group</th>
                          <th class="">Model</th>
                          <th class="">Dies No.</th>
                          <th class="">Dies Description</th>
                          <th class="text-center">Maintenance Date</th>
                          <th class="text-center">Status</th>
                          <th class="text-center">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        if (!empty($data["list"])) {
                          foreach ($data["list"] as $list) {
                            echo "<tr>"
                              . "<td class=''>" . $list["pmtid"] . "</td>"
                              . "<td class=''>" . $list["group_id"] . "</td>"
                              . "<td class=''>" . $list["model_id"] . "</td>"
                              . "<td class=''>" . $list["dies_no"] . "</td>"
                              . "<td class=''>" . $list["name1"] . "</td>"
                              . "<td class='text-center'>" . $list["pmt_date"] . "</td>"
                              . "<td class='text-center ".$list["text_color"]."'>" . $list["pmstat_tx"] . "</td>"
                              . "<td class='text-center'>"
                              . "<a href='$action?id=".$list["pmtid"]."&step=2' class='btn btn-outline-dark btn-xs text-center mb-1'><i class='material-icons'>edit</i> edit</a>"
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
  <?php include 'common/t_js.php'; ?>
  <script src="vendors/ega/js/scripts.js?time=<?php echo date("Ymdhis"); ?>" type="text/javascript"></script>
  <script>
    $(document).ready(function() {});
  </script>
</body>

</html>