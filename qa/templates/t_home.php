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
            <li class="breadcrumb-item">
              <?php echo $template["group"]; ?>
            </li>
            <li class="breadcrumb-item active">
              <?php echo $template["menu"]; ?>
            </li>
          </ol>
          <div class="card mb-3" id="fs">
            <div class="card-body">
              <div class="container-fluid mb-2">
                <div class="row">
                  <div class="col-12 d-none container-fluid" id="btn-exit-fullscreen">
                    <div class="row">
                      <div class="col-3">
                        <a id="logo" class="navbar-brand mb-3" href=".."><img src="media/images/logo.svg" height="30"
                            alt="logo" /></a>
                      </div>
                      <div class="col-6">
                        <div id="title" class="text-ega-blue text-center">
                          <h4 class='mb-3' style="font-weight: 700; ">DASHBOARD CALIPER DIGITALIZATION</h4>
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
                    <form method="get" action="#">
                      <div class="row">
                        <div class="col-1">
                          <label class="col-form-label">Date</label>
                        </div>
                        <div class="col-3">
                          <input type="month" class="form-control form-control-sm" name="tanggal" id="tanggal"
                            value="<?= $tanggal ?>">
                        </div>
                        <div class="col-2">
                          <button class="btn btn-block btn-primary btn-sm" type="button"
                            onclick="updateDashboard()">Refresh
                            Data</button>
                        </div>
                      </div>
                      <!-- <table class="table table-sm border-bottom py-1 ">
                        <tr>
                          <td><label class="col-form-label">Tanggal</label></td>
                          <td><input type="month" class="form-control form-control-sm" name="tanggal" id="tanggal"
                              value="<?= $tanggal ?>"></td>
                          <td>
                            <label class="col-form-label">Shift</label></td>
                          <td><select class="custom-select custom-select-sm" name="shift" id="shift">
                              <?php foreach ($data_shift as $row) {
                                $selected = "";
                                if ($row["code"] == $shift) {
                                  $selected = "selected";
                                }
                                echo "<option value='" . $row["code"] . "' $selected>" . $row["name"] . "</option>";
                              } ?>
                            </select></td>
                          <td><button class="btn btn-block btn-primary btn-sm" type="button"
                              onclick="updateDashboard()">Refresh
                              Data</button></td>
                        </tr>
                      </table> -->
                    </form>
                  </div>
                  <hr/>
                  <div class="col-12">
                    <div class="row">
                      <div class="col-9">
                        <div id="chart"></div>
                      </div>
                      <div class="col-3">
                        <div id="month" class="row"></div>
                      </div>
                    </div>
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
    setInterval(updateDashboard, 5000);
    const month = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Des'];
    month.map((item, index) => {
      $("#month").append("<div class='col-6 mb-2'><a href='/' class='btn btn-primary w-100 py-3'>" + item + "</a></div>");
    });
    var options = {
      series: [],
      chart: {
        type: 'bar',
        height: 350
      },
      plotOptions: {
        bar: {
          horizontal: false,
          columnWidth: '55%',
          endingShape: 'rounded'
        },
      },
      dataLabels: {
        enabled: false
      },
      stroke: {
        show: true,
        width: 2,
        colors: ['transparent']
      },
      xaxis: {
        // categories: ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
        title: {
          text: 'Part Name'
        }
      },
      yaxis: {
        title: {
          text: 'Passing Rasio'
        }
      },
      fill: {
        opacity: 1
      },
      tooltip: {
        y: {
          formatter: function (val) {
            return val + " %"
          }
        }
      }
    };

    var chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();

    $(document).ready(function () {
      updateDashboard();
    });

    function testDate() {
      console.log($("#tanggal").val())
    }

    function updateDashboard() {
      $.getJSON("?action=api_dashboard", {
        tanggal: $("#tanggal").val()
      },
        function (data) {
          console.log(data);
          let data_series = [];
          let categories = [];
          data.map((item) => {
            data_series.push(Math.round(item.rasio));
            categories.push(item.name1);
          });
          chart.updateSeries([{
            name: "Rasio",
            data: data_series
          }]);
          chart.updateOptions({
            xaxis: {
              categories: categories
            }
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