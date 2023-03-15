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
            <form id="my_form" method="get" action="index.php">
              <input type="hidden" name="check" value="check">
              <input type="hidden" name="action" value="<?=$action?>">
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
                          <button type="submit" id="btn_save" name="btn_save" value="btn_save" class="btn btn-primary"><span class="material-icons">search</span> Check</button>
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
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Material Document No.</label>
                      <div class="col-lg-3 col-md-6 col-sm-12">
                        <input type="text" name="mblnr" maxlength="20" class="form-control" value="" >
                      </div>
                    </div>
                    
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Year</label>
                      <div class="col-lg-2 col-md-6 col-sm-12">
                        <input type="text" name="mjahr" maxlength="4" class="form-control" value="<?=date("Y")?>" >
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
