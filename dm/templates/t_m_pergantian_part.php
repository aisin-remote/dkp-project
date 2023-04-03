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
            <li class="breadcrumb-item"><?php echo $template["menu"]; ?></li>
            <li class="breadcrumb-item active"><?php echo $template["submenu"]; ?></li>
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
          <form method="post" action="?action=<?php echo $action; ?>&id=<?php $getId; ?>">

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
                          <a class="btn btn-dark-blue btn-sm mx-2" href="?action=<?php echo $action; ?>&id=0"><i class="material-icons">add</i> New</a>
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
                    <div class="table-responsive">
                      <table class="table table-striped table-sm" id="data-table">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>Dies Group</th>
                            <th>Model</th>
                            <th>Dies No</th>
                            <th>Part Change Date</th>
                            <th>Status</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          if (!empty($data["list"])) {
                            foreach ($data["list"] as $row) {
                          ?>
                              <tr>
                                <td><?php echo $row["pchid"]; ?></td>
                                <td><?php echo $row["group_id"]; ?></td>
                                <td><?php echo $row["model_id"]; ?></td>
                                <td><?php echo $row["dies_no"]; ?></td>
                                <td><?php echo $row["pcdate"]; ?></td>
                                <td><?php echo $row["stats"]; ?></td>
                                <td><a class="btn btn-xs btn-outline-dark" href="?action=<?php echo $action ?>&id=<?php echo $row["pchid"]; ?>"><i class="material-icons">edit_square</i></a></td>
                              </tr>
                          <?php
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

          </form>
        </div>
      </main>
      <?php include 'common/t_footer.php'; ?>
    </div>
  </div>
  <?php include 'common/t_js.php'; ?>
  <script src="vendors/ega/js/scripts.js?time=<?php echo date("Ymdhis"); ?>" type="text/javascript"></script>
  <script>
    $(document).ready(function() {

    });
  </script>
</body>

</html>