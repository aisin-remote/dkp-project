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
            <div class="card mb-3" id="fs">
              <div class="card-body">
                <div class="container-fluid">
                  <div class="row">
                    <div class="col-12 d-none container-fluid" id="btn-exit-fullscreen">
                      <div class="row">
                        <div class="col-3">
                          <a id="logo" class="navbar-brand mb-3" href=".."><img src="media/images/logo.svg" height="30" alt="logo" /></a>
                        </div>
                        <div class="col-6">
                          <div id="title" class="text-ega-blue text-center">
                            <h4 class='mb-3' style="font-weight: 700; ">DELIVERY CONTROL BOARD</h4>
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
                      <form method="get" action="#">
                        <table class="table table-sm border-bottom py-1 ">
                          <tr>
                            <td><label class="col-form-label">Tanggal</label></td>
                            <td><input type="date" class="form-control form-control-sm" name="tanggal" id="tanggal" value="<?=$tanggal?>"></td>
                            <td><label class="col-form-label">Shift</label></td>
                            <td><select class="custom-select custom-select-sm" name="shift" id="shift"><?php foreach($data_shift as $row) {$selected = ""; if($row["code"] == $shift){$selected = "selected";} echo "<option value='".$row["code"]."' $selected>".$row["name"]."</option>";} ?></select></td>
                            <td><button class="btn btn-block btn-primary btn-sm" type="button" onclick="updateDashboard()">Refresh Data</button></td>
                          </tr>
                        </table>
                      </form>
                    </div>
                    <div class="col-12">
                      <div id="chart"></div>
                    </div>
                    <div class="col-12">
                      <table class="table">
                        <tr>
                          <th colspan="4">Legend</th>
                        </tr>
                        <tr>
                          <td style="background-color: #b5b5b5;"><div style="width: 40px;"></div></td>
                          <td>Order Open (On Progres Pulling)</td>
                          <td style="background-color: #00E396;"><div style="width: 40px;"></div></td>
                          <td>Pulling Finish/Finish Pulling, tapi belum terkirim atau diambil oleh customer</td>
                        </tr>
                        <tr>
                          <td style="background-color: #FEB019;"><div style="width: 40px;"></div></td>
                          <td>Pulling Belum finish, delivery time berada di antara jam sekarang dan lead time.</td>
                          <td style="background-color: #FF4560;"><div style="width: 40px;"></div></td>
                          <td>Pulling Belum finish, Waktu delivery sudah lewat</td>
                        </tr>
                        <tr>
                          <td style="background-color: #03adfc;"><div style="width: 40px;"></div></td>
                          <td>Terdelivery</td>
                          <td style="background-color: #FFF;"><div style="width: 40px;"></div></td>
                          <td></td>
                        </tr>
                      </table>
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
    <input type="hidden" id="lead_time" value="<?=$lead_time?>">
    <?php include 'common/t_js.php'; ?>
    <script src="vendors/ega/js/scripts.js?time=<?php echo date("Ymdhis"); ?>" type="text/javascript"></script>
    <script>
      var options = {
        series:[],
        tooltip: {
          x: {
            format: "HH:mm"
          }
        },
        chart: {
          height: 450,
          type: 'rangeBar'
        },
        plotOptions: {
          bar: {
            horizontal: true,
            barHeight: '80%',
            rangeBarGroupRows: true,
          }
        },
        xaxis: {
          type: 'datetime',
          labels: {
            datetimeUTC: false,
            rotate: -45
          }
        },
        fill: {
          type: 'solid',
          opacity: 1
        },
        stroke: {
          show: true,
          colors: ["#cfcfcf"],
        },
        legend: {
          show: false
        },
        /*annotations: {
          xaxis: [
            {
              x: new Date().getTime(),
              borderColor: '#775DD0',
              label: {
                style: {
                  color: '#775DD0',
                },
                text: 'Current Time'
              }
            }
          ]
        }*/
      };

      var chart = new ApexCharts(document.querySelector("#chart"), options);
      chart.render();

      $(document).ready(function () {
        /*updateDashboard();*/
        /*updateAnnotation();*/
      });
      
      setInterval(function(){
        updateDashboard();
        //updateAnnotation();        
      },10000);
      
      function updateAnnotation() {
        var lead_time = parseFloat($("#lead_time").val());
        var current_time = new Date().getTime();
        var lead_date_time = new Date().getTime() + ( lead_time * 60 * 1000 );
        console.log(current_time+" - "+lead_date_time);
        chart.clearAnnotations();
        chart.addXaxisAnnotation(
          {
            x: current_time,
            borderColor: '#f05d5d',
            label: {
              style: {
                color: '#f05d5d',
              },
              text: 'Current Time'
            }
          }
        );
        chart.addXaxisAnnotation(
          {
            x: lead_date_time,
            borderColor: '#000000',
            label: {
              style: {
                color: '#000000',
              },
              text: 'Lead Time'
            }
          }
        );
      }
      
      function updateDashboard() {
        $.getJSON(
          "?action=api_dashboard_pooling", 
          {device_id:'<?=$_SERVER["REMOTE_ADDR"]?>',tanggal:$("#tanggal").val(),shift:$("#shift").val()}, 
          function(data) {
            //var data_per_jam = data.data_per_jam;
            $("#lead_time").val(data.lead_time);
            /*chart.updateSeries([{
              name : "pulling",
              data: data.data_chart
            }]);*/
          
            chart.updateOptions({
              series: data.data_chart
            });
          });
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
