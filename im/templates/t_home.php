<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>

<head>
  <?php include "common/t_css.php"; ?>
  <link href="vendors/ega/css/styles.css" rel="stylesheet" type="text/css" />
  <link href="vendors/apexchart/apexcharts.css" rel="stylesheet" type="text/css" />
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
          <div class="card mb-3" id="fs">
            <div class="card-body">
              <div class="container-fluid border-bottom mb-2">
                <div class="row">
                  <div class="col-12 d-none container-fluid" id="btn-exit-fullscreen">
                    <div class="row">
                      <div class="col-3">
                        <a id="logo" class="navbar-brand mb-3" href=".."><img src="media/images/logo.svg" height="30" alt="logo" /></a>
                      </div>
                      <div class="col-6">
                        <div id="title" class="text-ega-blue text-center">
                          <h4 class='mb-3' style="font-weight: 700; ">ANDON SYSTEM</h4>
                        </div>
                      </div>
                      <div class="col-3">
                        <!-- DIV kosong -->
                        <div class="d-flex justify-content-end">
                          <button type="button" class="btn btn-link" onclick="closeFullscreen()"><i class="material-icons">fullscreen_exit</i></button>
                        </div>
                      </div>
                    </div>                        
                  </div>
                  <div class="col-12">
                    <!-- Edit Here -->
                  </div>
                </div>
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
  <script src="vendors/apexchart/apexcharts.min.js" type="text/javascript"></script>
  <script>
    //setInterval(updateDashboard, 5000);
    $.ready(function(){
      updateDashboard();
    });

    function updateDashboard() {
      console.log("Update dashboard");
    }

    var elem = document.getElementById("fs");

    function fullscreen() {
      //document.body.style.zoom = '75%';
      $("#btn-exit-fullscreen").removeClass("d-none");
      if (elem.requestFullscreen) {
        elem.requestFullscreen();
      } else if (elem.mozRequestFullScreen) {
        /* Firefox */
        elem.mozRequestFullScreen();
      } else if (elem.webkitRequestFullscreen) {
        /* Chrome, Safari and Opera */
        elem.webkitRequestFullscreen();
      } else if (elem.msRequestFullscreen) {
        /* IE/Edge */
        elem.msRequestFullscreen();
      }
    }

    /* Close fullscreen */
    function closeFullscreen() {
      $("#btn-exit-fullscreen").addClass("d-none");
      if (document.exitFullscreen) {
        document.exitFullscreen();
      } else if (document.webkitExitFullscreen) {
        /* Safari */
        document.webkitExitFullscreen();
      } else if (document.msExitFullscreen) {
        /* IE11 */
        document.msExitFullscreen();
      }
    }

    $("#fs-btn").click(fullscreen);
    
    $(document).bind('webkitfullscreenchange mozfullscreenchange fullscreenchange', function(e) {
      var state = document.fullScreen || document.mozFullScreen || document.webkitIsFullScreen;
      var event = state ? 'FullscreenOn' : 'FullscreenOff';

      // Now do something interesting
      if (event == "FullscreenOff") {
        closeFullscreen();
      }
    });
  </script>
</body>

</html>