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
            <ol class="breadcrumb mb-1 mt-1">
              <li class="breadcrumb-item"><?php echo $template["group"]; ?></li>
              <li class="breadcrumb-item active"><?php echo $template["menu"]; ?></li>
            </ol>
            <?php 
            if(isset($_GET["error"])) {
              echo '<div class="alert alert-danger alert-dismissible fade show mb-1" role="alert">
                      '.$_GET["error"].'
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>';
            }
            
            if(isset($_GET["success"])) {
              echo '<div class="alert alert-success alert-dismissible fade show mb-1" role="alert">
                      '.$_GET["success"].'
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>';
            }
            ?>
            <form id="my_form" method="post" action="?action=<?php echo $action; ?>">
              <input type="hidden" name="save" value="post">
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
                          <button type="submit" id="btn_save" name="btn_save" value="btn_save" class="btn btn-primary"><span class="material-icons">send</span> Post</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>              
            </div>
            <div class="row">
              <div class="col-12">
                <div class="card mt-1">
                  <div class="card-body">
                    <!-- Edit Here -->                    
                    
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Posting Date</label>
                      <div class="col-lg-3 col-md-6 col-sm-12">
                        <input type="date" name="budat" class="form-control" value="<?=date("Y-m-d")?>" >
                      </div>
                    </div>
                    
                    <div class="row">
                      <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="container-fluid">
                          <p><strong>From</strong></p>
                          <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-md-12 col-sm-12">Plant</label>
                            <div class="col-lg-8 col-md-12 col-sm-12">
                              <select name="werks" class="form-control" id="werks" required="required">
                                <?php 
                                if(!empty($data["plants"])) {
                                  foreach($data["plants"] as $grp) {
                                    echo "<option value='".$grp["werks"]."'>".$grp["werks"]." - ".$grp["name1"]."</option>";                              
                                  }
                                }
                                ?>
                              </select>
                            </div>
                          </div>

                          <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-md-12 col-sm-12">Store Location</label>
                            <div class="col-lg-8 col-md-12 col-sm-12">
                              <select name="lgort" class="form-control" id="lgort" required="required">
                                <option value=''>Please Select S.Loc</option>
                                <?php 
                                if(!empty($data["slocs"])) {
                                  foreach($data["slocs"] as $grp) {
                                    echo "<option value='".$grp["lgort"]."'>".$grp["lgort"]." - ".$grp["name1"]."</option>";                              
                                  }
                                }
                                ?>
                              </select>
                            </div>
                          </div> 

                          <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-md-12 col-sm-12">Material</label>
                            <div class="col-lg-8 col-md-12 col-sm-12">
                              <select name="matnr" class="form-control" required="required">
                                <option value="">Please Select Material</option>
                                <?php 
                                if(!empty($data["materials"])) {
                                  foreach($data["materials"] as $grp) {
                                    if (!empty($grp["backno"])) {
                                      $backno = " (" . $grp["backno"] . ")";
                                    } else {
                                      $backno = "";
                                    }
                                    echo "<option value='".$grp["matnr"]."'>".$grp["matnr"]." - ".$grp["name1"].$backno."</option>";                              
                                  }
                                }
                                ?>
                              </select>
                            </div>
                          </div>                        

                          <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-md-3 col-sm-12">Batch Number</label>
                            <div class="col-lg-6 col-md-5 col-sm-12">
                              <input type="text" name="charg" class="form-control" value="" required="required">
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-12 border-left">
                        <div class="container-fluid">
                          <p><strong>Destination</strong></p>
                          <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-md-12 col-sm-12">Plant</label>
                            <div class="col-lg-8 col-md-12 col-sm-12">
                              <select name="werks2" class="form-control" id="werks2" required="required">
                                <?php 
                                if(!empty($data["plants"])) {
                                  foreach($data["plants"] as $grp) {
                                    echo "<option value='".$grp["werks"]."'>".$grp["werks"]." - ".$grp["name1"]."</option>";                              
                                  }
                                }
                                ?>
                              </select>
                            </div>
                          </div>

                          <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-md-12 col-sm-12">Store Location</label>
                            <div class="col-lg-8 col-md-12 col-sm-12">
                              <select name="lgort2" class="form-control" id="lgort2" required="required">
                                <option value=''>Please Select S.Loc</option>
                                <?php 
                                if(!empty($data["slocs"])) {
                                  foreach($data["slocs"] as $grp) {
                                    echo "<option value='".$grp["lgort"]."'>".$grp["lgort"]." - ".$grp["name1"]."</option>";                              
                                  }
                                }
                                ?>
                              </select>
                            </div>
                          </div> 
                          <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-md-12 col-sm-12">Material</label>
                            <div class="col-lg-8 col-md-12 col-sm-12">
                              <select name="matnr2" class="form-control" required="required">
                                <option value="">Please Select Material</option>
                                <?php 
                                if(!empty($data["materials"])) {
                                  foreach($data["materials"] as $grp) {
                                    echo "<option value='".$grp["matnr"]."'>".$grp["matnr"]." - ".$grp["name1"]."</option>";                              
                                  }
                                }
                                ?>
                              </select>
                            </div>
                          </div>                        

                          <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-md-3 col-sm-12">Batch Number</label>
                            <div class="col-lg-6 col-md-5 col-sm-12">
                              <input type="text" name="charg2" class="form-control" value="" required="required">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Quantity</label>
                      <div class="col-lg-2 col-md-6 col-sm-12">
                        <input type="number" name="menge" class="form-control" value="" placeholder="0" min="0">
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
        updateLgort("lgort","werks");
        updateLgort("lgort2","werks2");
      });
      
      $("#my_form").submit(function(){
        $("#btn_save").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Please Wait...');
        $("button").attr("disabled","disabled");
      });
      
      $("#werks").change(function(){
        updateLgort("lgort","werks");
      });
      
      $("#werks2").change(function(){
        updateLgort("lgort2","werks2");
      });
      
      function updateLgort(lgort_id,werks_id) {
        $("#"+lgort_id).html("<option value=''>Please Select S.Loc</option>");
        $.getJSON("?action=api_wms_get_sloc", {werks:$("#"+werks_id).val()}, function(result){
          $.each(result, function(i, field){
            $("#"+lgort_id).append("<option value='"+field.lgort+"'>"+field.lgort+" - "+field.name1+"</option>");
          });
        });
      }
      
    </script>
  </body>
</html>
