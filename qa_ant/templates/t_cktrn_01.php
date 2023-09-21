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
              <li class="breadcrumb-item">
                <a href="?action=<?=$action?>"><?php echo $template["menu"]; ?></a>
              </li>
              <li class="breadcrumb-item active">
                <?php echo $template["sub_menu"]; ?>
              </li>
            </ol>
            <?php 
            if(isset($_GET["error"])) {
              echo '<div class="alert alert-danger" role="alert">
                      Error : '.$_GET["error"].'
                    </div>';
            }
            ?>
            <form method="get" action="?action=<?=$action; ?>" id="myform">
              <input type="hidden" name="action" value="<?=$action; ?>">
            <div class="row">
              <div class="col-12">
                <div class="card mt-2">
                  <div class="card-body">
                    <!-- Edit Here -->
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Date</label>
                      <div class="col-lg-3 col-md-5 col-sm-12">
                        <input type="text" name="date1" id="date1" class="form-control date-picker" value="<?=(!empty($filter_date1)) ? $filter_date1 : date("Y-m-d")?>">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Shift</label>
                      <div class="col-lg-3 col-md-5 col-sm-12">
                        <select name="shift" class="form-control select2" id="shift">
                          <?php 
                          if(!empty($shift_list)) {
                            foreach($shift_list as $row) {
                              if($row["shift"] == $filter_shift) {
                                echo "<option value='".$row["shift"]."' selected>".$row["name1"]."</option>";
                              } else {
                                echo "<option value='".$row["shift"]."'>".$row["name1"]."</option>";
                              }
                            }
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                    <div class="form-group row">                      
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Type</label>
                      <?php 
                        if(!empty($type_list)) {
                          foreach($type_list as $row) {
                            echo "<div class='col-lg-2 col-md-3 col-sm-12'><button class='btn ".$row["bg_color"]." btn-block' type='submit' name='type1' value='".$row["type1"]."'>".$row["name1"]."</button></div>";
                          }
                        }
                        ?>
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
      $("#myform").submit(function(){
        $(".btn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Please Wait...');
      });
      
      $(document).ready(function () {
        $(".date-picker").flatpickr({
          altInput:true,
          dateFormat: "Y-m-d",
          altFormat: "d M Y",
        });
      });
      
      $("#shift").on("change", function(){
        $("#myform").submit();
      });
      
      $("#date1").on("change", function(){
        $("#myform").submit();
      });
    </script>
  </body>
</html>
