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
        <div class="container-fluid mt-4">
          <ol class="breadcrumb mb-4 mt-4">
            <li class="breadcrumb-item"><?php echo $template["group"]; ?></li>
            <li class="breadcrumb-item active"><?php echo $template["menu"]; ?></li>
            <li class="breadcrumb-item active"><?php echo $template["submenu"]; ?></li>
          </ol>

          <form method="get" action="?action=<?php echo $action; ?>">
            <input type="hidden" name="action" value="<?= $action ?>">
            <div class="row">
              <div class="col-12">
                <div class="card mt-2">
                  <div class="card-body">
                    <!-- Edit Here -->
                    <!-- <?php print_r($shift_ori) ?> -->
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Date</label>
                      <div class="col-lg-2 col-md-5 col-sm-12">
                        <input type="text" name="date" class="form-control datepicker" value="<?php echo $date; ?>" required>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Shift</label>
                      <div class="col-lg-2 col-md-5 col-sm-12">
                        <select name="shift" class="form-control select">
                          <?php
                          foreach ($shift_list as $row) {
                          ?>
                            <option value="<?php echo $row["seq"]; ?>" <?php if ($row["seq"] == $shift) {
                                                                          echo "selected";
                                                                        } ?>><?php echo $row["pval1"]; ?></option>
                          <?php
                          }
                          ?>
                        </select>
                      </div>
                      <div class="col-lg-3 col-md-5 col-sm-12">
                        <button type="submit" name="refresh" value="refresh" class="btn btn-pale-green"><span class="material-icons">refresh</span> Refresh Data</button>
                      </div>
                    </div>

                    <h6>Line Selection</h6>
                    <div class="line-selection mx-4 mt-4">
                      <div class="row">
                        <?php
                        foreach ($line_list as $row) {
                        ?>
                          <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-2">
                            <button type="submit" name="line" value="<?php echo $row["line_id"]; ?>" class="btn <?php echo $row["color"]; ?> btn-block"><?php echo $row["name1"]; ?></button>
                          </div>
                        <?php
                        }
                        ?>
                      </div>
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
  <input type="hidden" id="usrid" value="<?php echo $_SESSION[LOGIN_SESSION]; ?>">
  <?php include 'common/t_js.php'; ?>
  <script src="vendors/ega/js/scripts.js?time=<?php echo date("Ymdhis"); ?>" type="text/javascript"></script>
  <script>
    $(document).ready(function() {
      $(".datepicker").flatpickr({
        altInput: true,
        altFormat: 'd-m-Y',
        dateFormat: 'Ymd',
        maxDate: "today"
      });
    });
  </script>
</body>

</html>