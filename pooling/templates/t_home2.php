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
</head>

<body>
  <?php include "common/t_nav_top.php"; ?>
  <div id="layoutSidenav">
    <?php include "common/t_nav_left.php"; ?>
    <div id="layoutSidenav_content">
      <main>
        <div class="container-fluid mt-2">
          <ol class="breadcrumb mb-2">
            <li class="breadcrumb-item">
              <?php echo $template["group"]; ?>
            </li>
            <li class="breadcrumb-item active">
              <?php echo $template["menu"]; ?>
            </li>
          </ol>
          <div class="card mb-3" id="fs">
            <div class="card-body">
              <div class="container-fluid">
                <div class="d-flex justify-content-center pt-0 mb-2" id="tab-button">
                  <a href="?action=home" class="btn btn-sm btn-primary mr-2">Pershift</a>
                  <a href="?action=home_realtime" class="btn btn-sm btn-primary">Realtime</a>
                </div>
                  <div class="row">
                  <div class="col-12 d-none container-fluid" id="btn-exit-fullscreen">
                    <div class="row">
                      <div class="col-3">
                        <a id="logo" class="navbar-brand mb-3" href=".."><img src="media/images/logo.svg" height="30"
                            alt="logo" /></a>
                      </div>
                      <div class="col-6">
                        <div id="title" class="text-ega-blue text-center">
                          <h4 class='mb-3' style="font-weight: 700; ">DELIVERY CONTROL BOARD</h4>
                        </div>
                      </div>
                      <div class="col-3">
                        <!-- DIV kosong -->
                        <div class="d-flex justify-content-end">
                          <button type="button" class="btn btn-link" onclick="closeFullscreen()"><i
                              class="material-icons">fullscreen_exit</i></button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-12">
                    <div id="chart"></div>
                  </div>
                  <div class="col-12">
                    <table class="table table-sm">
                      <tr>
                        <th colspan="4">Legend</th>
                      </tr>
                      <tr>
                        <td style="background-color: #b5b5b5;">
                          <div style="width: 20px;"></div>
                        </td>
                        <td>Order Open (On Progress Pulling)</td>
                        <td style="background-color: #00E396;">
                          <div style="width: 20px;"></div>
                        </td>
                        <td>Pulling Finish, Not Delivered</td>
                      </tr>
                      <tr>
                        <td style="background-color: #FEB019;">
                          <div style="width: 20px;"></div>
                        </td>
                        <td>Pulling Progress</td>
                        <td style="background-color: #FF4560;">
                          <div style="width: 20px;"></div>
                        </td>
                        <td>Delivery time has passed</td>
                      </tr>
                      <tr>
                        <td style="background-color: #03adfc;">
                          <div style="width: 20px;"></div>
                        </td>
                        <td>Delivered</td>
                        <td style="background-color: #FFF;">
                          <div style="width: 20px;"></div>
                        </td>
                        <td></td>
                      </tr>
                    </table>
                  </div>
                    
                  <div class="col-12">
                    <div class="table-responsive">
                      <table id="tbl_abnormal" class="table table-sm table-bordered table-striped">
                        <thead>
                          <tr>
                            <th colspan="4">Abnormality</th>
                          </tr>
                          <tr>
                            <th>Customer</th>
                            <th>Loading List</th>
                            <th>Shipping DateTime</th>
                            <th>Status</th>
                          </tr>
                        </thead>
                        <tbody id="dt_abnormal">
                          
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <!-- <div class="col-12">
                    <table class="table">
                      <tr>
                        <th colspan="4">Legend</th>
                      </tr>
                      <tr>
                        <td style="background-color: #b5b5b5;">
                          <div style="width: 40px;"></div>
                        </td>
                        <td>Order Open (On Progres Pulling)</td>
                        <td style="background-color: #00E396;">
                          <div style="width: 40px;"></div>
                        </td>
                        <td>Pulling Finish/Finish Pulling, tapi belum terkirim atau diambil oleh customer</td>
                      </tr>
                      <tr>
                        <td style="background-color: #FEB019;">
                          <div style="width: 40px;"></div>
                        </td>
                        <td>Pulling Belum finish, delivery time berada di antara jam sekarang dan lead time.</td>
                        <td style="background-color: #FF4560;">
                          <div style="width: 40px;"></div>
                        </td>
                        <td>Pulling Belum finish, Waktu delivery sudah lewat</td>
                      </tr>
                      <tr>
                        <td style="background-color: #03adfc;">
                          <div style="width: 40px;"></div>
                        </td>
                        <td>Terdelivery</td>
                        <td style="background-color: #FFF;">
                          <div style="width: 40px;"></div>
                        </td>
                        <td></td>
                      </tr>
                    </table>
                  </div> -->
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
  <input type="hidden" id="lead_time" value="<?= $lead_time ?>">
  <?php include 'common/t_js.php'; ?>
  <script src="vendors/ega/js/scripts.js?time=<?php echo date("Ymdhis"); ?>" type="text/javascript"></script>
  <script>
    var options = {
      series: [],
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
      // grid: {
      //   xaxis: {
      //     lines: {
      //       show: true
      //     }
      //   },
      //   yaxis: {
      //     lines: {
      //       show: false
      //     }
      //   }
      // },
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
    
    var abn_tbl = $("#tbl_abnormal").DataTable();
    
    $(document).ready(function () {
      /*updateDashboard();*/
      /*updateAnnotation();*/ 
    });

    setInterval(function () {
      updateDashboard();
      updateAnnotation();
    }, 10000);

    function updateAnnotation() {
      var lead_time = parseFloat($("#lead_time").val());
      var current_time = new Date().getTime();
      var lead_date_time = new Date().getTime() + (lead_time * 60 * 1000);
      console.log(current_time + " - " + lead_date_time);
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
        "?action=api_dashboard_realtime",
        { device_id: '<?= $_SERVER["REMOTE_ADDR"] ?>' },
        function (data) {
          //var data_per_jam = data.data_per_jam;
          $("#lead_time").val(data.lead_time);
          /*chart.updateSeries([{
            name : "pulling",
            data: data.data_chart
          }]);*/

          chart.updateOptions({
            series: data.data_chart
          });
          
          var data_abnormal = data.data_abnormal;
          //var append_data = "";
          abn_tbl.clear();
          $.each(data_abnormal, function(index, value){
            if(value.sts == "0") {
            } else {
              //append_data += "<tr><td>"+value.ldnum+"</td><td>"+value.status+"</td></tr>";
              abn_tbl.row.add([value.customer,value.ldnum,value.dtime,value.status]);
            }
          });
          //$("#dt_abnormal").html(append_data);          
          abn_tbl.draw();
        });
    }

    var elem = document.getElementById("fs");

    function fullscreen() {
      //document.body.style.zoom = '75%';
      $("#btn-exit-fullscreen").removeClass("d-none");
      $("#tab-button").hide();
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
      $("#tab-button").show();
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

    $(document).bind('webkitfullscreenchange mozfullscreenchange fullscreenchange', function (e) {
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