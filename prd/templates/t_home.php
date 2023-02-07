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
                  <div class="col-12 d-none" id="btn-exit-fullscreen">
                    <div class="d-flex justify-content-end">
                      <button type="button" class="btn btn-link" onclick="closeFullscreen()"><i class="material-icons">fullscreen_exit</i></button>
                    </div>
                  </div>
                  <?php
                  if (!empty($data_line_name)) {
                    $i = 0;
                    foreach ($data_line_name as $row) {
                      echo "<div class='col-lg-3 col-md-6 col-sm-12 text-center border rounded pt-2' id='title_line_$i'>"
                        . "<h5 id='line_name_$i' class='mb-0'>" . $row["line"] . "</h5><small class='" . $row["dies_color"] . "' id='dies_name_$i'>" . $row["dies"] . "</small>"
                        . "<div id='line_$i'></div>"
                        . "</div>";
                      $i++;
                    }
                  }
                  ?>
                </div>
              </div>
              <div class="container-fluid border-bottom mb-2">
                <div id="chart2"></div>
              </div>
              <!--div class="container-fluid mb-2">
                <div class="table-responsive">
                  <table class="table table-striped table-bordered">
                    <thead>
                      <tr>
                        <th>Actual (%)</th>
                        <?php
                        if (!empty($data_line_name)) {
                          foreach ($data_line_name as $row) {
                            echo "<th>" . $row["line"] . "</th>";
                          }
                        }
                        ?>
                      </tr>
                    </thead>
                    <tbody>
                      <tr id="row_eff">
                        <td></td>
                        <?php
                        if (!empty($data_eff)) {
                          foreach ($data_eff as $row) {
                            echo "<th>$row %</th>";
                          }
                        }
                        ?>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div-->
              <div class="container-fluid">
                <div class="table-responsive">
                  <table class="table table-striped table-bordered table-sm">
                    <thead>
                      <tr>
                        <th></th>
                        <?php
                        if (!empty($data_line_name)) {
                          foreach ($data_line_name as $row) {
                            echo "<th>" . $row["line"] . "</th>";
                          }
                        }
                        ?>
                      </tr>
                    </thead>
                    <tbody>
                      <tr id="row_ril">
                        <td>RIL</td>
                        <?php
                        if (!empty($data_ril)) {
                          foreach ($data_ril as $row) {
                            echo "<td>$row %</td>";
                          }
                        }
                        ?>
                      </tr>
                      <tr id="row_rol">
                        <td>ROL</td>
                        <?php
                        if (!empty($data_rol)) {
                          foreach ($data_rol as $row) {
                            echo "<td>$row %</td>";
                          }
                        }
                        ?>
                      </tr>
                    </tbody>
                  </table>
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
    var options_radial = {
      series: [0],
      chart: {
        type: 'radialBar',
        offsetY: -20,
        sparkline: {
          enabled: true
        }
      },
      plotOptions: {
        radialBar: {
          startAngle: -90,
          endAngle: 90,
          track: {
            background: '#e7e7e7',
            strokeWidth: '97%',
            margin: 5, // margin is in pixels
            dropShadow: {
              enabled: true,
              top: 2,
              left: 0,
              color: '#999',
              opacity: 1,
              blur: 2
            }
          },
          dataLabels: {
            name: {
              show: false
            },
            value: {
              offsetY: -2,
              fontSize: '22px'
            }
          }
        }
      },
      grid: {
        padding: {
          top: -10
        }
      },
      fill: {
        type: 'gradient',
        gradient: {
          shade: 'light',
          shadeIntensity: 0.4,
          inverseColors: false,
          opacityFrom: 1,
          opacityTo: 1,
          stops: [0, 50, 53, 91]
        }
      },
      labels: ['Production Efficiency'],
    };
    <?php
    if (!empty($data_line_name)) {
      $i = 0;
      foreach ($data_line_name as $row) {
        echo "";

        echo "var chart_line_" . $i . " = new ApexCharts(document.querySelector('#line_" . $i . "'), options_radial);
              chart_line_" . $i . ".render();";
        $i++;
      }
    }
    ?>
    var options = {
      series: [{
        name: 'Efficiency',
        type: 'line',
        data: [<?php echo implode(", ", $data_eff_sum); ?>]
      }, {
        name: 'RIL',
        type: 'bar',
        data: [<?php echo implode(", ", $data_ril_sum); ?>]
      }, {
        name: 'ROL',
        type: 'bar',
        data: [<?php echo implode(", ", $data_rol_sum); ?>]
      }],
      chart: {
        type: 'line',
        height: 200,
        stacked: true,
        toolbar: {
          show: true
        },
        zoom: {
          enabled: true
        }
      },
      dataLabels: {
        enabled: true,
        formatter: function(value, {
          seriesIndex,
          dataPointIndex,
          w
        }) {
          return value + " %";
        }
      },
      responsive: [{
        breakpoint: 480,
        options: {
          legend: {
            position: 'bottom',
            offsetX: -10,
            offsetY: 0
          }
        }
      }],
      plotOptions: {
        bar: {
          horizontal: false,
          borderRadius: 10,
          columnWidth: '30%',
          dataLabels: {
            total: {
              enabled: true,
              style: {
                fontSize: '13px',
                fontWeight: 900
              }
            }
          }
        },
      },
      xaxis: {
        type: 'text',
        categories: ["<?php echo implode("\",\"", array_map(null, ...$data_line_name)[0]); ?>"],
      },
      legend: {
        position: 'right',
        offsetY: 40
      },
      fill: {
        opacity: 1
      }
    };

    var chart = new ApexCharts(document.querySelector("#chart2"), options);
    chart.render();

    $(document).ready(function() {
      updateDashboard();
    });

    function updateDashboard() {
      $.getJSON(
        "?action=api_dashboard_prd", {},
        function(data) {
          //var data_per_jam = data.data_per_jam;
          var data_ril = data.data_ril;
          var data_rol = data.data_rol;
          //var data_line_name = data.data_line_name;
          var data_eff = data.data_eff;

          var data_ril_sum = data.data_ril_sum;
          var data_rol_sum = data.data_rol_sum;
          var data_line_name = data.data_line_name;
          var data_eff_sum = data.data_eff_sum;

          if (data_line_name.length > 0) {
            var i = 0;
            $.each(data_line_name, function(row, value) {
              if ($("#line_name_" + i).length > 0) {
                $("#line_name_" + i).html(value.line);
              }
              if ($("#dies_name_" + i).length > 0) {
                $("#dies_name_" + i).html(value.dies);
                $("#dies_name_" + i).removeClass("text-danger");
                $("#dies_name_" + i).addClass(value.dies_color);
              }
              i++;
            });
          }
          if (data_eff_sum.length > 0 && data_ril_sum.length > 0 && data_rol_sum.length > 0) {
            chart.updateSeries([{
              name: 'Efficiency',
              data: data_eff
            }, {
              name: 'RIL',
              data: data_ril
            }, {
              name: 'ROL',
              data: data_ril
            }]);
          }

          /*if(data_eff_sum.length > 0) {
            var append_data = "<td></td>";
            $.each(data_eff_sum,function(row, value){
              append_data += "<td>"+value+" %</td>";
            });
            $("#row_eff").html(append_data);
          }*/

          if (data_ril_sum.length > 0) {
            var append_data = "<td>RIL</td>";
            $.each(data_ril_sum, function(row, value) {
              append_data += "<td>" + value + " %</td>";
            });
            $("#row_ril").html(append_data);
          }
          if (data_rol.length > 0) {
            var append_data = "<td>ROL</td>";
            $.each(data_rol, function(row, value) {
              append_data += "<td>" + value + " %</td>";
            });
            $("#row_rol").html(append_data);
          }
          /*update series per line*/
          if (data_eff.length > 0) {
            if (typeof chart_line_0 !== 'undefined' && data_eff[0] !== "undefined") {
              chart_line_0.updateSeries([data_eff[0]]);
            }
            if (typeof chart_line_1 !== 'undefined' && data_eff[1] !== "undefined") {
              chart_line_1.updateSeries([data_eff[1]]);
            }
            if (typeof chart_line_2 !== 'undefined' && data_eff[2] !== "undefined") {
              chart_line_2.updateSeries([data_eff[2]]);
            }
            if (typeof chart_line_3 !== 'undefined' && data_eff[3] !== "undefined") {
              chart_line_3.updateSeries([data_eff[3]]);
            }
            if (typeof chart_line_4 !== 'undefined' && data_eff[4] !== "undefined") {
              chart_line_4.updateSeries([data_eff[4]]);
            }
            if (typeof chart_line_5 !== 'undefined' && data_eff[5] !== "undefined") {
              chart_line_5.updateSeries([data_eff[5]]);
            }
            if (typeof chart_line_6 !== 'undefined' && data_eff[6] !== "undefined") {
              chart_line_6.updateSeries([data_eff[6]]);
            }
            if (typeof chart_line_7 !== 'undefined' && data_eff[7] !== "undefined") {
              chart_line_7.updateSeries([data_eff[7]]);
            }
          }
        }
      );
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
  </script>
</body>

</html>