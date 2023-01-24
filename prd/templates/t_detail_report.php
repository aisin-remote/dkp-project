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
              <li class="breadcrumb-item active"><?php echo $template["group"]; ?></li>
              <li class="breadcrumb-item"><?php echo $template["menu"]; ?></li>
              <li class="breadcrumb-item"><?php echo $template["submenu"]; ?></li>
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
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col py-2 header-info" style="background-color: #E4E4E4;">
                                <h6 id="header-info">Header Information</h6>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-2 py-2 info-content" style="background-color: #F5F5F5;">
                                <p>Line Production</p>
                            </div>
                            <div class="col-2 py-2 info-content" style="background-color: #F5F5F5;">
                                <p><span>: </span>DCAA01</p>
                            </div>
                            <div class="col-2 py-2 info-content" style="background-color: #F5F5F5;">
                                <p>JP Name</p>
                            </div>
                            <div class="col-2 py-2 info-content" style="background-color: #F5F5F5;">
                                <p><span>: </span>Will Smith</p>
                            </div>
                            <div class="col-2 py-2 info-content" style="background-color: #F5F5F5;">
                            </div>
                            <div class="col-2 py-2 info-content" style="background-color: #F5F5F5;">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-2 py-2 info-content" style="background-color: #F5F5F5;">
                                <p>Production Date</p>
                            </div>
                            <div class="col-2 py-2 info-content" style="background-color: #F5F5F5;">
                                <p><span>: </span>2022-12-01</p>
                            </div>
                            <div class="col-2 py-2 info-content" style="background-color: #F5F5F5;">
                                <p>Lastman Name</p>
                            </div>
                            <div class="col-2 py-2 info-content" style="background-color: #F5F5F5;">
                                <p><span>: </span>Will Smith</p>
                            </div>
                            <div class="col-2 py-2 info-content" style="background-color: #F5F5F5;">
                            </div>
                            <div class="col-2 py-2 info-content" style="background-color: #F5F5F5;">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-2 py-2 info-content" style="background-color: #F5F5F5;">
                                <p>Shift</p>
                            </div>
                            <div class="col-2 py-2 info-content" style="background-color: #F5F5F5;">
                                <p><span>: </span>1-Pagi</p>
                            </div>
                            <div class="col-2 py-2 info-content" style="background-color: #F5F5F5;">
                                <p>Pos 1 Name</p>
                            </div>
                            <div class="col-2 py-2 info-content" style="background-color: #F5F5F5;">
                                <p><span>: </span>Will Smith</p>
                            </div>
                            <div class="col-2 py-2 info-content" style="background-color: #F5F5F5;">
                            </div>
                            <div class="col-2 py-2 info-content" style="background-color: #F5F5F5;">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-2 py-2 info-content" style="background-color: #F5F5F5;">
                            </div>
                            <div class="col-2 py-2 info-content" style="background-color: #F5F5F5;">
                            </div>
                            <div class="col-2 py-2 info-content" style="background-color: #F5F5F5;">
                                <p>Pos 2 Name</p>
                            </div>
                            <div class="col-2 py-2 info-content" style="background-color: #F5F5F5;">
                                <p><span>: </span>Will Smith</p>
                            </div>
                            <div class="col-2 py-2 info-content" style="background-color: #F5F5F5;">
                            </div>
                            <div class="col-2 py-2 info-content" style="background-color: #F5F5F5;">
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
                      <table class="table table-sm table-striped" id="data-table">
                        <thead>
                          <tr>
                            <th class="align-middle">Dies</th>
                            <th class="align-middle">Hour</th>
                            <th class="align-middle">Cycle Time</th>
                            <th class="align-middle">Planning Qty</th>
                            <th class="align-middle">Total Plan Qty</th>
                            <th class="align-middle">Prod Qty</th>
                            <th class="align-middle">Total Prod Qty</th>
                            <th class="align-middle">Total NG</th>
                            <th class="align-middle">Lost Time(m)</th>
                            <th class="align-middle">Efficiency</th>
                            <th class="text-center align-middle">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php 
                          if(!empty($data["list"])) {
                            foreach($data["list"] as $list) {
                              echo "<tr>"
                              . "<td class='align-middle'>".$list["Dies"]."</td>"
                              . "<td class='align-middle'>".$list["Hour"]."</td>"
                              . "<td class='align-middle'>".$list["Cycle Time"]."</td>"
                              . "<td class='align-middle'>".$list["Planning Qty"]."</td>"
                              . "<td class='align-middle'>".$list["Total Plan Qty"]."</td>"
                              . "<td class='align-middle'>".$list["Prod Qty"]."</td>"
                              . "<td class='align-middle'>".$list["Total Prod Qty"]."</td>"
                              . "<td class='align-middle'>".$list["NG Qty"]."</td>"
                              . "<td class='align-middle'>".$list["Lost Time(m)"]."</td>"
                              . "<td class='align-middle'>".$list["Efficiency"]."</td>"
                              . "<td class='text-center'>"
                                . "<a href='$action?id=".$list["empid"]."' class='btn btn-outline-dark btn-xs text-center mb-1'><i class='material-icons'>edit</i> edit</a>"        
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
