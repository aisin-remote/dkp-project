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
          if (isset($_GET["success"])) {
            echo '<div class="alert alert-success" alert-dismissible fade show" role="alert">
                      ' . $_GET["success"] . '
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>';
          }
          ?>
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-6 col-sm-12">
                      <!-- filter placement -->

                    </div>
                    <div class="col-lg-6 col-sm-12">
                      <div class="d-flex justify-content-end">
                        <!-- button placement -->
                        <a class="btn btn-primary" href="?action=<?php echo $action ?>&id=0"><span
                            class="material-icons">add</span>New</a>
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
                  <!-- Edit Here -->
                  <table class="table table-sm" id="data-table-x">
                    <thead>
                      <tr>
                        <th>Part Number</th>
                        <th>Part Name</th>
                        <th class="text-center">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      if (!empty($list)) {
                        foreach ($list as $row) {
                          echo "<tr>"
                            . "<td>" . $row["partno"] . "</td>"
                            . "<td>" . $row["name1"] . "</td>"
                            . "<td class='text-center'>"
                            . "<a href='?action=$action&id=" . $row["partno"] . "' class='btn btn-primary btn-sm mr-2'><span
                              class='material-icons'>edit</span> Edit</a>"
                            . "<a href='?action=$action&id=" . $row["partno"] . "&download=excel' class='btn btn-outline-primary btn-sm'><span
                              class='material-icons'>download</span> Excel</a>"
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
    $(document).ready(function () {
      $("#data-table-x").DataTable({
        stateSave: true,
        order: [
          [0, 'desc']
        ],
      });
    });

    $('input[type="file"]').change(function (e) {
      var fileName = e.target.files[0].name;
      $('.custom-file-label').html(fileName);
    });
  </script>
</body>

</html>