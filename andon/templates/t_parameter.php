<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
  <head>
    <?php include "common/t_css.php"; ?>
    <link href="vendors/ega/css/styles.css" rel="stylesheet" type="text/css"/>
  </head>
  <body>		
    <?php include "common/t_nav_top.php"; ?>
    <div id="layoutSidenav">
      <?php include "common/t_nav_left.php"; ?>
      <div id="layoutSidenav_content">
        <main>
          <div class="container-fluid">
            <ol class="breadcrumb mb-4 mt-4">
              <li class="breadcrumb-item"><?php echo $template["group"]; ?></li>
              <li class="breadcrumb-item active"><?php echo $template["menu"]; ?></li>
            </ol>
            <?php 
            if(isset($_GET["error"])) {
              echo '<div class="alert alert-danger" role="alert">
                      Error : '.$_GET["error"].'
                    </div>';
            }
            ?>
            <?php 
            if(isset($_GET["success"])) {
              echo '<div class="alert alert-success" role="alert">
                      Success : '.$_GET["success"].'
                    </div>';
            }
            ?>
            <form method="post" action="?action=<?php echo $action; ?>&id=<?php echo $id; ?>">
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
                          <button type="submit" name="save" value="save" class="btn btn-primary"><span class="material-icons">save</span> Save</button>
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
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">KALIBRASI TEMP.</label>
                      <div class="col-lg-3 col-md-5 col-sm-12">
                        <input type="text" name="shift_temp" class="form-control" maxlength="5"
                          value="<?php echo $temp; ?>">
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">KALIBRASI HUMID.</label>
                      <div class="col-lg-3 col-md-5 col-sm-12">
                        <input type="text" name="shift_humid" class="form-control" maxlength="5"
                          value="<?php echo $humid; ?>">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">MIN TEMP.</label>
                      <div class="col-lg-3 col-md-5 col-sm-12">
                        <input type="text" name="min_temp" class="form-control" maxlength="5"
                          value="<?php echo $data["range"]["pval1"]; ?>">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">MAX TEMP.</label>
                      <div class="col-lg-3 col-md-5 col-sm-12">
                        <input type="text" name="max_temp" class="form-control" maxlength="5"
                          value="<?php echo $data["range"]["pval2"]; ?>">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">MIN HUMID.</label>
                      <div class="col-lg-3 col-md-5 col-sm-12">
                        <input type="text" name="min_humid" class="form-control" maxlength="5"
                          value="<?php echo $data["range"]["pval3"]; ?>">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">MAX HUMID.</label>
                      <div class="col-lg-3 col-md-5 col-sm-12">
                        <input type="text" name="max_humid" class="form-control" maxlength="5"
                          value="<?php echo $data["range"]["pval4"]; ?>">
                      </div>
                    </div>
                  </div>
                </div>
              </div>              
            </div>
            <div class="row">
              
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
      $(document).ready(function () {
      });
    </script>
  </body>
</html>
