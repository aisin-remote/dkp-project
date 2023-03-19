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
                    
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Plant</label>
                      <div class="col-lg-4 col-md-6 col-sm-12">
                        <select name="werks" class="form-control select2" id="werks" required="required">
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
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Store Location</label>
                      <div class="col-lg-4 col-md-6 col-sm-12">
                        <select name="lgort" class="form-control select2" id="lgort" required="required">
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
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Material</label>
                      <div class="col-lg-5 col-md-6 col-sm-12">
                        <select name="matnr" id="matnr" class="form-control select2" required="required">
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
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Batch Number</label>
                      <div class="col-lg-3 col-md-6 col-sm-12">
                        <input type="text" name="charg" id="charg" class="form-control" value="" required="required">
                      </div>
                    </div>
                    
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Quantity</label>
                      <div class="col-lg-2 col-md-6 col-sm-12">
                        <input type="number" name="menge" id="menge" class="form-control" value="" placeholder="0" readonly="readonly">
                      </div>
                    </div>
                    
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Adjustment</label>
                      <div class="col-lg-2 col-md-6 col-sm-12">
                        <input type="number" name="menge2" id="menge2" class="form-control" value="" placeholder="0" min="0" required="required">
                      </div>
                    </div>
                    
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Difference</label>
                      <div class="col-lg-2 col-md-6 col-sm-12">
                        <input type="number" name="menge3" id="menge3" class="form-control" value="" placeholder="0" readonly="readonly">
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
      });
      
      $("#my_form").submit(function(){
        $("#btn_save").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Please Wait...');
        $("button").attr("disabled","disabled");
      });
      
      $("#werks").change(function(){
        updateLgort("lgort","werks");
        getBatchStock();
      });
      
      $("#lgort").change(function(){
        getBatchStock();
      });
      
      $("#matnr").change(function(){
        getBatchStock();
      });
      
      $("#charg").change(function(){
        getBatchStock();
      });
      
      $("#menge2").change(function(){
        var menge = parseFloat($("#menge").val());
        var menge2 = parseFloat($("#menge2").val());
        
        var menge3 = menge2 - menge;
        $("#menge3").val(menge3);
      });
      
      function updateLgort(lgort_id,werks_id) {
        $("#"+lgort_id).html("<option value=''>Please Select S.Loc</option>");
        $.getJSON("?action=api_wms_get_sloc", {werks:$("#"+werks_id).val()}, function(result){
          $.each(result, function(i, field){
            $("#"+lgort_id).append("<option value='"+field.lgort+"'>"+field.lgort+" - "+field.name1+"</option>");
          });
        });
      }
      
      function getBatchStock() {
        var werks = $("#werks").val();
        var lgort = $("#lgort").val();
        var matnr = $("#matnr").val();
        var charg = $("#charg").val();
        var menge = 0;
        if(werks.length > 0 && lgort.length > 0 && matnr.length > 0 && charg.length > 0) {
          $.getJSON("?action=api_get_batch_stock", {werks:werks,lgort:lgort,matnr:matnr,charg:charg}, function(result){
            if(result.clabs) {
              menge = result.clabs;
            }
            $("#menge").val(menge);
          });
        }
      }
      
    </script>
  </body>
</html>
