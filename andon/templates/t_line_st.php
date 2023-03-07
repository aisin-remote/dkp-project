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
          <div class="card text-white my-3 bg-dark" id="fs">
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
                    <div class="container-fluid pb-3">
                      <div class="row column-gap">
                        <?php
                        foreach ($data["line"] as $line) {
                          echo "<div class='col-3'>";
                          echo "<div class='card'>";
                          echo '<button type="button" onclick="lineVal(\''.$line['line_id'].'\')" data-toggle="modal" data-target="#modalStatus" class="card-body ' . $line["pval4"] . ' text-center text-white border border-secondary"><h2>' . $line['name1'] . '</h2></button>';
                          echo "</div>";
                          echo "</div>";
                        }
                        ?>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="container mt-3">
                <table class="table table-borderless table-sm mb-0">
                  <tbody>
                    <tr>
                      <?php
                        foreach ($data["param"] as $param) {
                          echo "<td style='width: 100px;' class='" . $param["pval4"] . "'>";
                          echo "<td class='text-white'>" . $param["pval2"] . "</td>";
                        }
                      ?>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="modal fade" id="modalStatus" tabindex="-1" role="dialog" aria-labelledby="modalStatus" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content bg-dark">
              <div class="modal-header">
                <h5 class="modal-title text-white" id="exampleModalLongTitle">Pilih Status</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <?php
                foreach ($data["param"] as $param) {
                  echo "<a onclick='paramVal(".$param["pval1"].")' class='btn btn-block text-white btn-md ".$param["pval4"]."'>" . $param["pval2"] . "</a>";
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
  <input type="hidden" id="line" value="">
  <?php include 'common/t_js.php'; ?>
  <script src="vendors/ega/js/scripts.js?time=<?php echo date("Ymdhis"); ?>" type="text/javascript"></script>
  <script src="vendors/apexchart/apexcharts.min.js" type="text/javascript"></script>
  <script>
    //setInterval(updateDashboard, 5000);
    var param
    var line

    $.ready(function() {
      updateDashboard();
    });

    function lineVal(line_id) {
      $("#line").val(line_id)
    }

    function paramVal(param_id) {
      param = param_id;
      line = $("#line").val();
      window.location.href = '?action=<?php echo $action; ?>&line=' + line + '&status='+ param;
      // console.log(param)
    }

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