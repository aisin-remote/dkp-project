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
            if(!empty($error)) {
              echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                      Error : '.$error.'
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>';
            }
            ?>
            
            <?php 
            if(!empty($success)) {
              echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                      Success : '.$success.'
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>';
            }
            ?>
            <form method="post" action="?action=<?php echo $action; ?>" enctype="multipart/form-data">
            
            <div class="row">
              <div class="col-12">
                <div class="card mt-2">
                  <div class="card-body">
                    <!-- Edit Here -->
                                        
                    <div class="form-group row">
                      <label class="col-form-label col-lg-3 col-md-3 col-sm-12">App Version</label>
                      <div class="col-lg-2 col-md-5 col-sm-12">
                        <input type="text" name="app_version" class="form-control" required="required" value="<?php echo $app_version; ?>">
                      </div>
                    </div>
                    
                    <div class="form-group row">
                      <label class="col-form-label col-lg-3 col-md-3 col-sm-12">APK File</label>
                      <div class="col-lg-2 col-md-5 col-sm-12">
                        <input type="file" name="app_file" class="form-control-file" required="required" >
                      </div>
                    </div>
                    
                    <div class="form-group row">
                      <div class="col-lg-3 col-md-3 col-sm-12 d-sm-none d-md-block"></div>
                      <div class="col-lg-5 col-md-5 col-sm-12">
                        <button type="submit" name="save" value="save" class="btn btn-dark-blue"><span class="material-icons">send</span> Upload</button>
                        <a href="android/app.apk" class="btn btn-info ml-2">Download App</a>
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
