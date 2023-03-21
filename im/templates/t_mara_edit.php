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
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Type</label>
                      <div class="col-lg-3 col-md-5 col-sm-12">
                        <select name="mtart" class="form-control">
                          <?php 
                          if(!empty($data["mtarts"])) {
                            foreach($data["mtarts"] as $grp) {
                              if($grp["mtart"] == $data["data"]["mtart"]) {
                                echo "<option value='".$grp["mtart"]."' selected>".$grp["mtart"]." - ".$grp["name1"]."</option>";
                              } else {
                                echo "<option value='".$grp["mtart"]."'>".$grp["mtart"]." - ".$grp["name1"]."</option>";
                              }
                            }
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                    
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Group</label>
                      <div class="col-lg-3 col-md-5 col-sm-12">
                        <select name="matkl" class="form-control">
                          <option value='' selected>SELECT MATERIAL GROUP</option>
                          <?php 
                          if(!empty($data["matkls"])) {
                            foreach($data["matkls"] as $grp) {
                              if($grp["matkl"] == $data["data"]["matkl"]) {
                                echo "<option value='".$grp["matkl"]."' selected>".$grp["matkl"]." - ".$grp["name1"]."</option>";
                              } else {
                                echo "<option value='".$grp["matkl"]."'>".$grp["matkl"]." - ".$grp["name1"]."</option>";
                              }
                            }
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                    
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Material No.</label>
                      <div class="col-lg-3 col-md-5 col-sm-12">
                        <input type="text" name="matnr" class="form-control" maxlength="40" value="<?php echo $data["data"]["matnr"]; ?>" <?php if(!empty($data["data"]["matnr"])) {echo "readonly";} ?>>
                      </div>
                    </div>
                    
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">External Material No.</label>
                      <div class="col-lg-3 col-md-5 col-sm-12">
                        <input type="text" name="ematn" class="form-control" maxlength="40" value="<?php echo $data["data"]["ematn"]; ?>">
                      </div>
                    </div>
                    
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Material Description</label>
                      <div class="col-lg-5 col-md-6 col-sm-12">
                        <input type="text" name="name1" class="form-control" maxlength="255" value="<?php echo $data["data"]["name1"]; ?>">
                      </div>
                    </div>                    
                    
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Unit of Measure</label>
                      <div class="col-lg-2 col-md-4 col-sm-12">
                        <input type="text" name="meins" class="form-control" maxlength="5" value="<?php echo $data["data"]["meins"]; ?>" >
                      </div>
                    </div>
                    
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Cycle Time (Second)</label>
                      <div class="col-lg-2 col-md-5 col-sm-12">
                        <input type="number" name="cctime" step="any" class="form-control" value="<?php echo $data["data"]["cctime"]; ?>">
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
