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
                    <!-- Edit Here -->
                    <div class="table-responsive">
                    <table class="table" id="data-table-x">
                      <thead class="bg-light text-uppercase">
                        <tr>
                          <th class='text-nowrap'>ID</th>
                          <th class='text-nowrap'>No</th>
                          <th class='text-nowrap'>Process Name</th>
                          <th class='text-nowrap text-center'>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                        if(!empty($data["list"])) {
                          foreach($data["list"] as $list) {
                            echo "<tr>"
                            . "<td class='text-nowrap'>".$list["grp_id"]."</td>"
                            . "<td class='text-nowrap'>".$list["grp_no"]."</td>"
                            . "<td class='text-nowrap'><a href=\"javascript:openModalEdit('".$list["grp_id"]."','".$list["grp_no"]."','".$list["name1"]."')\">".$list["name1"]."</a></td>"
                            . "<td class='text-nowrap text-center'><a href=\"javascript:deleteData('".$list["grp_id"]."','".$list["name1"]."')\" class='btn btn-danger btn-xs'><i class='material-icons'>delete</i></a></td>"
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
    <!-- Modal -->
    <div class="modal fade" id="modal_edit" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modal_upload_label" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <form id="modal_form_01" method="POST" action="?action=<?php echo $action; ?>" enctype="multipart/form-data">
            <div class="modal-header">
              <h5 class="modal-title" id="modal_upload_label"></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <input type="hidden" name="save" value="" id="save">
              <div class="form-group">
                <label for="grp_id">Process ID</label>
                <input type="number" class="form-control" id="grp_id" name="grp_id">
              </div>
              <div class="form-group">
                <label for="grp_no">No</label>
                <input type="text" class="form-control" id="grp_no" name="grp_no">
              </div>
              <div class="form-group">
                <label for="name1">Description</label>
                <input type="text" class="form-control" id="name1" name="name1">
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-outline-primary"><span class="material-icons">save</span> Save</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <?php include 'common/t_js.php'; ?>
    <script src="vendors/ega/js/scripts.js?time=<?php echo date("Ymdhis"); ?>" type="text/javascript"></script>
    <script>
      $("#modal_form_01").submit(function(){
        $(".btn").attr("disabled","disabled");
        $(".btn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...');
      });
      
      $(document).ready(function () {
        $("#data-table-x").DataTable({
          stateSave: true,
          order: [
            [0, 'asc']
          ],
          dom: "<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-2'l><'col-sm-12 col-md-4'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
          buttons: [{
              extend: 'excel',
              title: "master_process_name",
              className: 'btn btn-success btn-sm',
              text: '<i class="material-icons">download</i>Download Excel'
            },{
              className: 'btn btn-primary btn-sm',
              text: '<i class="material-icons">add</i> New',
              action: function() {
                $("#save").val("I");
                $("#grp_id").val("");
                $("#grp_id").removeAttr("readonly");
                $("#grp_no").val("");
                $("#name1").val("");
                $("#modal_upload_label").html('<span class="material-icons">add</span> New Data');
                $("#modal_edit").modal("show");
              }
            }
          ]
        });
      });
      
      function openModalEdit(grp_id, grp_no, name1) {      
        $("#save").val("U");
        $("#grp_id").val(grp_id);
        $("#grp_id").attr("readonly","readonly");
        $("#grp_no").val(grp_no);
        $("#name1").val(name1);
        $("#modal_upload_label").html('<span class="material-icons">edit</span> Edit Data');
        $("#modal_edit").modal("show");
      }
      
      function deleteData(id, name1) {
        var cfm = confirm("Apakah anda yakin menghapus data ["+name1+"]? ");
        if(cfm) {
          $(".btn").attr("disabled","disabled");
          $(".btn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...');
          window.location.href = "?action=<?=$action?>&id="+id+"&delete=true";
        }
      }
    </script>
  </body>
</html>
