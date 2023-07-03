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
            if(isset($_GET["success"])) {
              echo '<div class="alert alert-success" alert-dismissible fade show" role="alert">
                      '.$_GET["success"].'
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>';
            }
            ?>
            <?php 
            if(isset($_GET["error"])) {
              echo '<div class="alert alert-danger" alert-dismissible fade show" role="alert">
                      '.$_GET["error"].'
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
                    <div class="row">
                      <div class="col-lg-6 col-sm-12">
                        <!-- filter placement -->
                        
                      </div>
                      <div class="col-lg-6 col-sm-12">
                        <div class="d-flex justify-content-end">
                          <!-- button placement -->
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
                    <div class='table-responsive'>
                      <table class="table table-sm" id="data-table-x">
                        <thead>
                          <tr>
                            <th class='text-nowrap'>Material No</th>
                            <th class='text-nowrap'>Material Desc</th>
                            <th class='text-nowrap'>Plant</th>
                            <th class='text-nowrap'>Plant Desc.</th>
                            <th class='text-nowrap'>S.Loc</th>
                            <th class='text-nowrap'>S.Loc Desc</th>
                            <th class='text-nowrap'>Batch Number</th>
                            <th class='text-nowrap text-center'>Stock</th>
                            <!--th class='text-nowrap text-center'>Quality Stock</th-->
                            <th class='text-nowrap text-center'>UoM</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php 
                          if(!empty($data["list"])) {
                            foreach($data["list"] as $list) {
                              if (!empty($list["backno"])) {
                                $backno = " (" . $list["backno"] . ")";
                              } else {
                                $backno = "";
                              }
                              echo "<tr>"
                              . "<td class='text-center text-nowrap'>".$list["matnr"]."</td>"
                              . "<td class='text-nowrap'>".$list["maktx"] . $backno."</td>"
                              . "<td class='text-center text-nowrap'>".$list["werks"]."</td>"
                              . "<td class='text-nowrap'>".$list["plant_name"]."</td>"
                              . "<td class='text-center text-nowrap'>".$list["lgort"]."</td>"
                              . "<td class='text-nowrap'>".$list["sloc_name"]."</td>"
                              . "<td class='text-center text-nowrap'>".$list["charg"]."</td>"
                              . "<td class='text-right'>".$list["clabs"]."</td>"
                              /*. "<td class='text-right'>".$list["cinsm"]."</td>" */                               
                              . "<td class='text-center'>".$list["meins"]."</td>"
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
            </div>
            <div class="row">
              
            </div>
          </div>
        </main>
        <?php include 'common/t_footer.php'; ?>
      </div>
    </div>
    <div class="modal fade" id="modal_upload" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modal_upload_label" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">        
        <div class="modal-content">
          <form method="GET" action="#" enctype="multipart/form-data">
            <input type="hidden" name="action" value="<?=$action?>">
            <div class="modal-header">
              <h5 class="modal-title" id="modal_upload_label"><span class="material-icons">filter_alt</span> Filter</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="container-fluid">
                <div class="form-group row">
                  <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Plant</label>
                  <div class="col-lg-10 col-md-9 col-sm-12">
                    <select name="werks" class="form-control modal_select2" id="werks">
                      <option value="">Please Select Plant</option>
                      <?php 
                      if(!empty($data["plants"])) {
                        foreach($data["plants"] as $grp) {
                          $selected = "";
                          if($grp["werks"] == $werks) {
                            $selected = "selected";
                          }
                          echo "<option value='".$grp["werks"]."' $selected>".$grp["werks"]." - ".$grp["name1"]."</option>";                              
                        }
                      }
                      ?>
                    </select>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-lg-2 col-md-3 col-sm-12">S.Loc</label>
                  <div class="col-lg-10 col-md-9 col-sm-12">
                    <select name="lgort" class="form-control modal_select2" id="lgort">
                      <option value="">Please Select S.Loc</option>
                      <?php 
                      if(!empty($data["lgorts"])) {
                        foreach($data["lgorts"] as $grp) {
                          $selected = "";
                          if($grp["lgort"] == $lgort) {
                            $selected = "selected";
                          }
                          echo "<option value='".$grp["lgort"]."' $selected>".$grp["lgort"]." - ".$grp["name1"]."</option>";                              
                        }
                      }
                      ?>
                    </select>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Material</label>
                  <div class="col-lg-10 col-md-9 col-sm-12">
                    <select name="matnr" class="form-control modal_select2" id="matnr">
                      <option value="">Please Select Material</option>
                      <?php 
                      if(!empty($data["materials"])) {
                        foreach($data["materials"] as $grp) {
                          if (!empty($grp["backno"])) {
                            $backno = " (" . $grp["backno"] . ")";
                          } else {
                            $backno = "";
                          }
                          $selected = "";
                          if($grp["matnr"] == $matnr) {
                            $selected = "selected";
                          }
                          echo "<option value='".$grp["matnr"]."' $selected>".$grp["matnr"]." - ".$grp["name1"].$backno."</option>";                              
                        }
                      }
                      ?>
                    </select>
                  </div>
                </div>
              </div>
              
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-outline-primary">Apply</button>
            </div>
              
          </form>
        </div>
      </div>
    </div>
    <?php include 'common/t_js.php'; ?>
    <script src="vendors/ega/js/scripts.js?time=<?php echo date("Ymdhis"); ?>" type="text/javascript"></script>
    <script>
      $(document).ready(function () {
        $("#data-table-x").DataTable({
          stateSave: true,
          order: [
            [0, 'asc'], [ 1, "asc" ]
          ],
          dom: "<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-2'l><'col-sm-12 col-md-4'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
          buttons: [{
            extend: 'excel',
            title: "Report Stock",
            className: 'btn btn-pale-green btn-sm',
            text: '<i class="material-icons">download</i>Download Excel',
            /*exportOptions: {
              columns: [0, 1, 2, 3, 4]
            }*/
          },{
            className: 'btn btn-outline-success btn-sm',
            text: '<i class="material-icons">filter_alt</i> Filter',
            action: function() {
              $('#modal_upload').modal("show");

            }
          } ]
        });
        
        $('.modal_select2').select2({
          dropdownParent: $('#modal_upload'),
          theme: 'bootstrap4',
          width: '100%'
        });
      });
      
      $("#werks").change(function(){
        updateLgort();
      });
      
      function updateLgort() {
        $("#lgort").html("<option value=''>Please Select S.Loc</option>");
        $.getJSON("?action=api_wms_get_sloc", {werks:$("#werks").val()}, function(result){
          $.each(result, function(i, field){
            $("#lgort").append("<option value='"+field.lgort+"'>"+field.lgort+" - "+field.name1+"</option>");
          });
        });
      }
    </script>
  </body>
</html>
