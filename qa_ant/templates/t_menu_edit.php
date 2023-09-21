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
            <ol class="breadcrumb mb-2 mt-2 bg-transparent">
              <li class="breadcrumb-item"><?php echo $template["group"]; ?></li>
              <li class="breadcrumb-item">
                <a href="?action=<?=$action?>"><?php echo $template["menu"]; ?></a>
              </li>
              <li class="breadcrumb-item active">
                Edit
              </li>
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
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Menu Group</label>
                      <div class="col-lg-3 col-md-5 col-sm-12">
                        <select name="groupid" class="form-control select2">
                          <?php 
                          if(!empty($data["menugroup"])) {
                            foreach($data["menugroup"] as $grp) {
                              if($grp["groupid"] == $data["data"]["groupid"]) {
                                echo "<option value='".$grp["groupid"]."' selected>".$grp["groupdsc"]."</option>";
                              } else {
                                echo "<option value='".$grp["groupid"]."'>".$grp["groupdsc"]."</option>";
                              }
                            }
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                    
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Menu ID</label>
                      <div class="col-lg-3 col-md-5 col-sm-12">
                        <input type="text" name="menuid" class="form-control" maxlength="100" value="<?php echo $data["data"]["menuid"]; ?>" <?php if(!empty($data["data"]["menuid"])) {echo "readonly";} ?>>
                      </div>
                    </div>
                    
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Menu Description</label>
                      <div class="col-lg-3 col-md-5 col-sm-12">
                        <input type="text" name="name1" class="form-control" maxlength="255" value="<?php echo $data["data"]["name1"]; ?>">
                      </div>
                    </div>
                    
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Sort Order</label>
                      <div class="col-lg-3 col-md-5 col-sm-12">
                        <input type="number" name="sort1" class="form-control" value="<?php echo $data["data"]["sort1"]; ?>">
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
