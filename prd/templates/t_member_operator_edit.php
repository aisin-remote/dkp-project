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
              <li class="breadcrumb-item"><?php echo $template["menu"]; ?></li>
              <li class="breadcrumb-item active"><?php echo $template["submenu"]; ?></li>
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
                <div class="card mt-2">
                  <div class="card-body">
                    <!-- Edit Here -->
                    
                    <input type="hidden" name="empid" class="form-control" maxlength="100" value="<?php echo $data["data"]["empid"]; ?>">

                    <div class="form-group row">
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Employee ID</label>
                      <div class="col-lg-3 col-md-5 col-sm-12">
                        <input type="text" name="empid" class="form-control" maxlength="100" value="<?php echo $data["data"]["empid"]; ?>" <?php if(!empty($data["data"]["empid"])) {echo "readonly";} ?> required>
                      </div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Role</label>
                        <div class="col-lg-3 col-md-5 col-sm-12">
                            <select name="role1" class="form-control select2">
                                <?php 
                                foreach($role_list as $role) {
                                ?>
                                <option value="<?php echo $role["pval1"]; ?>" <?php if($role["pval1"] == $data["data"]["role1"]){echo "selected";} ?>><?php echo $role["pval2"]; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Employee Name</label>
                      <div class="col-lg-3 col-md-5 col-sm-12">
                        <input type="text" name="name1" class="form-control" maxlength="100" value="<?php echo $data["data"]["name1"]; ?>" required>
                      </div>
                    </div>
                    
                    <div class="form-group row">
                      <div class="col-lg-2 col-md-3 col-sm-12 d-sm-none d-md-block"></div>
                      <div class="col-lg-5 col-md-5 col-sm-12">
                        <button type="submit" name="save" value="save" class="btn btn-pale-green"><span class="material-icons">save</span> Save</button>
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
