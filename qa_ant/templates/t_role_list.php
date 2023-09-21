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
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-body">
                    <!-- Edit Here -->
                    <table class="table" id="data-table-x">
                      <thead class="bg-light text-uppercase">
                        <tr>
                          <th>ID</th>
                          <th>Description</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                        if(!empty($data["list"])) {
                          foreach($data["list"] as $list) {
                            echo "<tr>"
                            . "<td><a href='?action=$action&id=".$list["roleid"]."'><u>".$list["roleid"]."</u></a></td>"
                            . "<td>".$list["name1"]."</td>"
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
    <?php include 'common/t_js.php'; ?>
    <script src="vendors/ega/js/scripts.js?time=<?php echo date("Ymdhis"); ?>" type="text/javascript"></script>
    <script>
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
              title: "master_role",
              className: 'btn btn-success btn-sm',
              text: '<i class="material-icons">download</i>Download Excel',
              exportOptions: {
                columns: [0, 1]
              }
            },{
              className: 'btn btn-primary btn-sm',
              text: '<i class="material-icons">add</i> New',
              action: function() {
                window.location.href = '?action=<?php echo $action ?>&id=0';
              }
            }
          ]
        });
      });
    </script>
  </body>
</html>
