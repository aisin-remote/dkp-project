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
                          <button type="button" class="btn btn-dark-blue btn-sm mx-2" onclick="openModal01()">New Reply</button>
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
                            <th class="align-middle">No</th>
                            <th class="align-middle">Message</th>
                            <th class="align-middle">Date/Time</th>
                            <th class="align-middle">Created By</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php 
                          if(!empty($data["list"])) {
                            foreach($data["list"] as $list) {
                              echo "<tr>"
                              . "<td class='align-middle'>".$list["msg_itm"]."</td>"
                              . "<td class='align-middle'>".$list["msg_txt"]."</td>"
                              . "<td class='align-middle'>".$list["fdate"]."</td>"
                              . "<td class='align-middle'>".$list["crt_by"]."</td>"                                                               
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
    <form method="post" action="<?=$action?>?id=<?=$id?>&" class="modal fade" id="mymodal01" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="mymodal01Label" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="mymodal01Label">Messages</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="msg_txt" >Add Reply</label>
              <textarea class="form-control" id="msg_txt" name="msg_txt" required maxlength="1000" rows="3"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" name="save" value="detail" class="btn btn-primary" >Submit</button>
          </div>
        </div>
      </div>
    </form>
    <?php include 'common/t_js.php'; ?>
    <script src="vendors/ega/js/scripts.js?time=<?php echo date("Ymdhis"); ?>" type="text/javascript"></script>
    <script>
      $(document).ready(function () {
        
      });
      
      function openModal01() {
        $('#mymodal01').modal({
          keyboard: false
        });
      }
      
      function saveDetail() {
        
      }
    </script>
  </body>
</html>
