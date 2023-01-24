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
          <div class="container-fluid mt-2">
            <ol class="breadcrumb mb-2">
              <li class="breadcrumb-item"><?php echo $template["group"]; ?></li>
              <li class="breadcrumb-item active"><?php echo $template["menu"]; ?></li>
            </ol>
            <div class="card mb-3">
              <div class="card-body">
                <h3 class="text-uli-blue">Dies Status</h3>
                <div class="row">
                <?php 
                if(!empty($data_group)) {
                  foreach($data_group as $grp) {
                    echo "<div class='col-lg-4 col-md-6 col-sm-12 px-1'><div class='card'>"
                    . "<div class='card-header'><h5 class='card-title mb-0 text-uli-blue'>".$grp["pval2"]."</h5></div>"
                    . "<div class='card-body'>";
                    if(!empty($data_dies)) {
                      echo "<div class='container-fluid'><div class='row'>";
                      foreach($data_dies as $ds) {
                        if($ds["group_id"] == $grp["pval1"]) {
                          echo "<div class='border-right border-white col-lg-4 col-md-6 col-sm-6 text-center ".$ds["bg_color"]."'>"
                                  . "<a href='CHECKSHEET_PREVENTIVE?id=0&step=1&group_id=".$ds["group_id"]."&model_id=".$ds["model_id"]."&dies_id=".$ds["dies_id"]."'><h6 class='mt-2 text-white text-nowrap'>".$ds["model_id"]." ".$ds["dies_no"]."</h6></a>"
                                  . "<small class='mb-1 text-white text-nowrap'>Stroke : ".$ds["stkrun"]."</small>"
                                  . "</div>";
                        }
                      }
                      echo "</div></div>";
                    }
                    echo "</div></div></div>";
                  }
                }
                ?>
                </div>
              </div>
            </div>         
          </div>
        </main>
        <?php include 'common/t_footer.php'; ?>
      </div>
    </div>
    <input type="hidden" id="usrid" value="<?php echo $_SESSION[LOGIN_SESSION]; ?>">
    <?php include 'common/t_js.php'; ?>
    <script src="vendors/ega/js/scripts.js?time=<?php echo date("Ymdhis"); ?>" type="text/javascript"></script>
    <script>
      setInterval(updateDashboard, 1000);
      
      $(document).ready(function () {
        
      });
      
      function updateDashboard() {
        /*$.ajax({
          type: "POST",
          url: "get_pos",
          crossDomain: true,
          cache: false,
          data : {},
          success: function (data) {
            var obj = $.parseJSON(data);
            
            if (obj.length > 0) {
              //error
              var dashboard1 = "";
              $.each(obj,function(index, value){                
                dashboard1 += "<tr><td class='text-center'>"+value.id_rfid+"</td><td class='text-center'>"+value.id_pos+"</td></tr>";
              });
              $("#dashboard1").html(dashboard1);
            } else {
              $("#dashboard1").html("");
            }
          }
        });*/
      }
    </script>
  </body>
</html>
