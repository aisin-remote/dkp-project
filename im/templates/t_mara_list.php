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
            <ol class="breadcrumb mb-1 mt-4">
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
                          <a class="btn btn-primary" href="?action=<?php echo $action ?>&id=0"><span class="material-icons">add</span>New</a>
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
                    <table class="table table-sm" id="data-table-x">
                      <thead>
                        <tr>
                          <th>Material Type</th>
                          <th>Material Group</th>
                          <th>Material No.</th>
                          <th>External Material No.</th>
                          <th>Material Description</th>
                          <th>UoM</th>
                          <th>Cycle Time</th>
                          <th class="text-center">Edit</th>
                          <th class="text-center">Delete</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                        if(!empty($data["list"])) {
                          foreach($data["list"] as $list) {
                            echo "<tr>"
                            . "<td>".$list["mtart"]." - ".$list["mat_type"]."</td>"
                            . "<td>".$list["matkl"]." - ".$list["mat_group"]."</td>"
                            . "<td>".$list["matnr"]."</td>"
                            . "<td>".$list["ematn"]."</td>"
                            . "<td>".$list["name1"]."</td>"                                 
                            . "<td>".$list["meins"]."</td>"                                
                            . "<td class='text-center'>".$list["cctime"]."</td>"
                            . "<td class='text-center'><a href='?action=$action&id=".$list["matnr"]."' class='btn btn-outline-secondary btn-xs'><i class='material-icons'>edit</i></a></td>"
                            . "<td class='text-center'><a href='?action=$action&id=".$list["matnr"]."&delete=true' class='btn btn-outline-danger btn-xs'><i class='material-icons'>delete</i></a></td>"
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
    <div class="modal fade" id="modal_upload" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modal_upload_label" aria-hidden="true">
      <div class="modal-dialog">
        <form method="POST" action="?action=<?php echo $action; ?>&upload=excel" enctype="multipart/form-data">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="modal_upload_label"><span class="material-icons">upload_file</span> Upload Data User</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <a href="media/template/template_material.xlsx">Download Template</a>
              <div class="input-group mb-3">
                <div class="custom-file">
                  <input type="file" class="custom-file-input" id="excel" name="excel" accept=".xls, .xlsx">
                  <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-outline-primary">Upload</button>
            </div>
          </div>
        </form>
      </div>
    </div>
    <?php include 'common/t_js.php'; ?>
    <script src="vendors/ega/js/scripts.js?time=<?php echo date("Ymdhis"); ?>" type="text/javascript"></script>
    <script>
      $(document).ready(function () {
        $("#data-table-x").DataTable({
          stateSave: true,
          order: [
            [0, 'desc']
          ],
          dom: "<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-2'l><'col-sm-12 col-md-4'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
          buttons: [{
            extend: 'excel',
            title: "Material Master",
            className: 'btn btn-pale-green btn-sm',
            text: '<i class="material-icons">download</i>Download Excel',
            exportOptions: {
              columns: [0, 1, 2, 3, 4, 5]
            }
          },{
            className: 'btn btn-outline-success btn-sm',
            text: '<i class="material-icons">upload_file</i> Upload by Excel',
            action: function() {
              $('#modal_upload').modal("show");

            }
          } ]
        });
      });
      
      $('input[type="file"]').change(function(e) {
        var fileName = e.target.files[0].name;
        $('.custom-file-label').html(fileName);
      });
    </script>
  </body>
</html>
