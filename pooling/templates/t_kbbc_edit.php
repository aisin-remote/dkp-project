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
                          <button type="submit" name="save" value="save" class="btn btn-template"><span class="material-icons">save</span> Save</button>
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
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Customer</label>
                      <div class="col-lg-6 col-md-6 col-sm-12">
                        <select name="lifnr" class="form-control select2" data-live-search="true">
                        <?php 
                        if(!empty($data["customer"])) {
                          foreach($data["customer"] as $row) {
                            if($row["lifnr"] == $data["data"]["lifnr"]) {
                              echo "<option value='".$row["lifnr"]."' selected>".$row["name1"]."</option>";
                            } else {
                              echo "<option value='".$row["lifnr"]."' >".$row["name1"]."</option>";
                            }
                          }
                        }
                        ?>
                        </select>
                      </div>
                    </div>
                    
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Material Start Char. Pos.</label>
                      <div class="col-lg-3 col-md-5 col-sm-12">
                        <input type="number" name="mat_start" class="form-control" value="<?php echo $data["data"]["mat_start"]; ?>" required>
                      </div>
                    </div>
                    
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Material End Char. Pos.</label>
                      <div class="col-lg-3 col-md-5 col-sm-12">
                        <input type="number" name="mat_end" class="form-control" value="<?php echo $data["data"]["mat_end"]; ?>" required>
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
        checklabel("enable_alarm");
      });
      
      $('#enable_alarm').on("change",function(){
        checklabel("enable_alarm");        
      });
      
      function checklabel(id) {
        $("#fl-"+id).empty();
        if($('#'+id).is(':checked')) {
          $("#fl-"+id).append("Active");
        } else {
          $("#fl-"+id).append("Inactive");
        }
      }
    </script>
  </body>
</html>
