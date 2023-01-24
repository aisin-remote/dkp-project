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
            if(isset($message)) {
              echo '<div class="alert alert-'.$message["color"].' alert-dismissible fade show" role="alert">
                      '.$message["text"].'
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
                    <form method="post" action="<?php echo $action; ?>">
                      <div class="row">                      
                        <div class="col-lg-6 col-sm-12">
                          <!-- filter placement -->
                          <input type="text" name="license_key" class="form-control" placeholder="Serial Number">
                        </div>
                        <div class="col-lg-6 col-sm-12">
                          <div class="d-flex justify-content-start">
                            <!-- button placement -->
                            <button class="btn btn-primary" type="submit" name="activate_license"><span class="material-icons">check</span>Activate License</button>
                          </div>
                        </div>
                      </div>                      
                    </form>
                  </div>
                </div>
              </div>              
            </div>
            <div class="row">
              <div class="col-12">
                <div class="card mt-2">
                  <div class="card-body">
                    <!-- Edit Here -->
                    <table class="table table-sm" id="data-table">
                      <thead>
                        <tr>
                          <th>License ID</th>
                          <th>Serial No</th>
                          <th>Type</th>
                          <th class="text-center">Volume</th>
                          <th class="text-center">Exipired Date</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                        if(!empty($data["list"])) {
                          foreach($data["list"] as $list) {
                            echo "<tr>"
                            . "<td>".$list["lic_id"]."</td>"
                            . "<td>".$list["lic_srl"]."</td>"
                            . "<td>".$list["lic_type"]."</td>"
                            . "<td class='text-center'>".$list["lic_vol"]."</td>"
                            . "<td class='text-center'>".$list["expired_date"]."</td>"
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
      });
    </script>
  </body>
</html>
