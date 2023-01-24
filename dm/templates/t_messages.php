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
            <ol class="breadcrumb mb-2 mt-4">
              <li class="breadcrumb-item"><?php echo $template["group"]; ?></li>
              <li class="breadcrumb-item active"><?php echo $template["menu"]; ?></li>
            </ol>
            <?php 
            if(isset($_GET["error"])) {
              echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                      Error : '.$_GET["error"].'
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>';
            }
            ?>
            
            <?php 
            if(isset($_GET["success"])) {
              echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                      Success : '.$_GET["success"].'
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>';
            }
            ?>
            <div class="row">
              <div class="col-12">
                <div class="card" style="background-color: #F0F0F0;">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-lg-6 col-sm-12">
                        <!-- filter placement -->
                        
                      </div>
                      <div class="col-lg-6 col-sm-12">
                        <div class="d-flex justify-content-end">
                          <!-- button placement -->
                          <a class="btn btn-dark-blue btn-sm" href="#"><span class="material-icons">filter_alt</span></a>
                          <a class="btn btn-dark-blue btn-sm mx-2" href="#">Download Excel</a>
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
                    <div class="table-responsive">
                    <!-- Edit Here -->
                      <table class="table table-striped table-sm" id="data-table">
                        <thead>
                          <tr>
                            <th class="align-middle">Message No</th>
                            <th class="align-middle">Date</th>
                            <th class="align-middle">Time</th>
                            <th class="align-middle">Message Subject</th>
                            <th class="align-middle">Created By</th>
                            <th class="text-center align-middle">View Details</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php 
                          if(!empty($data["list"])) {
                            foreach($data["list"] as $list) {
                              echo "<tr>"
                              . "<td class='align-middle'>".$list["message_no"]."</td>"
                              . "<td class='align-middle'>".$list["date"]."</td>"
                              . "<td class='align-middle'>".$list["time"]."</td>"
                              . "<td class='align-middle'>".$list["message_subject"]."</td>"
                              . "<td class='align-middle'>".$list["created_by"]."</td>"
                              . "<td class='text-center'>"
                                . "<a href='$action?id=".$list["dies_id"]."' class='btn btn-outline-dark btn-xs text-center mb-1'><i class='material-icons'>edit</i> edit</a>"        
                              . "</td>"                                    
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
    <?php include 'common/t_js.php'; ?>
    <script src="vendors/ega/js/scripts.js?time=<?php echo date("Ymdhis"); ?>" type="text/javascript"></script>
    <script>
      $(document).ready(function () {
      });
    </script>
  </body>
</html>
