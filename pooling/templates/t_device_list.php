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
                          <a class="btn btn-template" href="?action=<?php echo $action ?>&id=0"><span class="material-icons">add</span>New</a>
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
                    <table class="table table-sm" id="data-table">
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th>Description</th>
                          <th class="text-center">Edit</th>
                          <th class="text-center">Delete</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                        if(!empty($data["list"])) {
                          foreach($data["list"] as $list) {
                            echo "<tr>"
                            . "<td>".$list["device_id"]."</td>"
                            . "<td>".$list["name1"]."</td>"
                            . "<td class='text-center'><a href='?action=$action&id=".$list["device_id"]."' class='btn btn-outline-primary btn-sm'>edit</a></td>"
                            . "<td class='text-center'><a href='?action=$action&id=".$list["device_id"]."&del=true' class='btn btn-danger btn-sm'>delete</a></td>"
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
      });
    </script>
  </body>
</html>
