<!DOCTYPE html>
<html lang="en">
<?php include "common/t_css.php"; ?>

<body>
  <?php include "common/t_nav_top_dashboard.php"; ?>
  <main>
    <div class="container-fluid mt-2">
      <!-- <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
          <?php echo $template["group"]; ?>
        </li>
        <li class="breadcrumb-item active">
          <?php echo $template["menu"]; ?>
        </li>
      </ol> -->
      <div class="card mb-4">
        <div class="card-body">
          <div class="container-fluid text-center">
            <div class="row">
              <div class="col text-left pl-0">
                <h3 style="color: #002E94">Cycle Time</h3>
                <h2 style="color: #19A7CE" id="cctime">
                  <?= $cctime ?>0
                </h2>
              </div>
              <div class="col">
                <h1 style="color: #002E94">LINE EFFICIENCY</h1>
                <span class="font-weight-bold" style="font-size: 72px; color: #19A7CE">
                  <?= $line_name ?>
                </span>
              </div>
              <div class="col text-right pr-0">
                <h4 id="date" style="color: #002E94"></h4>
                <h4 id="time" style="color: #002E94"></h4>
              </div>
            </div>
            <div id='chart_line'></div>
          </div>
        </div>
      </div>
      <div class="card mb-3">
        <div class="card-body">
          <div class="container-fluid">
            <div class="table-responsive">
              <table class="table table-bordered mx-auto">
                <tbody>
                  <tr>
                    <td class="text-center h1" style="width: 60%;color: #002E94">TARGET QTY</td>
                    <td class="text-right align-middle h1 border-right-0" id="pln_qty" style="width: 20%;color: #002E94">
                      <?= $pln_qty ?>
                    </td>
                    <td class="text-left align-middle h1 border-left-0" id="pln_qty" style="width: 20%;color: #002E94">
                    </td>
                  </tr>
                  <tr>
                    <td class="text-center h1" style="width: 60%;color: #19A7CE">ACTUAL QTY</td>
                    <td class="text-right align-middle h1 border-right-0" id="prd_qty" style="width: 20%;color: #19A7CE">
                      <?= $prd_qty ?>
                    </td>
                    <td class="text-left align-middle h1 border-left-0" id="prd_qty" style="width: 20%;color: #19A7CE">
                    </td>
                  </tr>
                  <tr>
                    <td class="text-center h1" style="width: 60%;color: #002E94">BALANCE</td>
                    <td class="text-right align-middle h1 border-right-0" id="balance" style="width: 20%;color: #002E94">
                      <?= $balance ?>
                    </td>
                    <td class="text-left align-middle h1 border-left-0" id="balance" style="width: 20%;color: #002E94">
                    </td>
                  </tr>
                  <tr>
                    <td class="text-center h1" style="width: 60%;color: #19A7CE">ACHIEVE (%)</td>
                    <td class="text-right align-middle h1 border-right-0" id="achieve" style="width: 20%;color: #19A7CE">
                      <?= $achieve ?>
                    </td>
                    <td class="text-left align-middle h1 border-left-0" id="achieve" style="width: 20%;color: #19A7CE">
                      %
                    </td>
                  </tr>
                  <!-- <tr>
                    <td class="text-center h1" style="width: 60%">CYCLE TIME</td>
                    <td class="text-center align-middle h1" id="cctime" style="width: 60%">
                      <?= $cctime ?>
                    </td>
                  </tr> -->
                  <tr>
                    <td class="text-center h1" style="width: 60%;color: #002E94">LOSS TIME (PARTS)</td>
                    <td class="text-right align-middle h1 border-right-0 " id="stopdies" style="width: 20%;color: #002E94">
                      <?= $achieve ?>
                    </td>
                    <td class="text-left align-middle h1 border-left-0" id="stopdies" style="width: 20%;color: #002E94">
                      s
                    </td>
                  </tr>
                  <tr>
                    <td class="text-center h1" style="width: 60%;color: #19A7CE">LOSS TIME (M/C)</td>
                    <td class="text-right align-middle h1 border-right-0" id="stopmesin" style="width: 20%;color: #19A7CE">
                      <?= $achieve ?>
                    </td>
                    <td class="text-left align-middle h1 border-left-0" id="stopmesin" style="width: 20%;color: #19A7CE">
                      s
                    </td>
                  </tr>
                  <tr>
                    <td class="text-center h1" style="width: 60%;color: #002E94">RIL</td>
                    <td class="text-right h1 border-right-0"><span id="data_ril" style="width: 20%;color: #002E94">
                        <?= $ril ?>
                      </span></td>
                    <td class="text-left h1 border-left-0"><span id="data_ril" style="width: 20%;color: #002E94">
                        %
                      </span></td>
                  </tr>
                  <tr>
                    <td class="text-center h1" style="width: 60%;color: #19A7CE">ROL</td>
                    <td class="text-right h1 border-right-0"><span id="data_rol" style="width: 20%;color: #19A7CE">
                        <?= $rol ?>
                      </span></td>
                    <td class="text-left h1 border-left-0"><span id="data_rol" style="width: 20%;color: #19A7CE">
                        %
                      </span></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <!-- <div class="container-fluid">
            <div class="table-responsive">
              <table class="table table-bordered">
                <tbody>
                  <tr>
                    <th class="text-center h3">Line</th>
                    <th class="text-center line_name h3">
                      <?= $line_name ?>
                    </th>
                  </tr>

                </tbody>
              </table>
            </div>
          </div> -->
          <!-- <div class="container-fluid">
            <form method="get" action="<?= $action ?>">
              <div class="form-group text-center">
                <label for="line_id" class="h3">Select Line</label>
                <select class="form-control" id="line_id" name="line_id">
                  <?php
                  if (!empty($lines)) {
                    foreach ($lines as $row) {
                      if ($line_id == $row["line_id"]) {
                        echo "<option value='" . $row["line_id"] . "' selected class='h3'>" . $row["name1"] . "</option>";
                      } else {
                        echo "<option value='" . $row["line_id"] . "' class='h3'>" . $row["name1"] . "</option>";
                      }
                    }
                  }

                  ?>
                </select>
              </div>
            </form>
          </div> -->
        </div>
      </div>
    </div>
  </main>
  <?php include 'common/t_js.php'; ?>
  <script>
    // $(document).ready(function () {
    //   updateDashboard();
    //   fullscreen();
    // });

    setInterval(updateDashboard, 5000);
    setInterval(dateTime, 1000);
    var options = {
      // series: [<?= $eff ?>, {
      //   color: function({value}) {
      //     if (value <= 30) {
      //       return '#DF2E38'
      //     } else if (value <= 70 && value >= 30) {
      //       return '#FCE22A'
      //     } else {
      //       return '#03C988'
      //     }
      //   }
      // }],
      series: [<?= $eff ?>],
      chart: {
        type: 'radialBar',
        offsetY: -20,
        width: '100%',
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
              show: false,
            },
            value: {
              offsetY: -2,
              fontSize: '96px',
              fontWeight: 'bold'
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
        },
      },
      labels: ['Production Efficiency'],
    };
    var chart_line = new ApexCharts(document.querySelector('#chart_line'), options);
    chart_line.render();

    function updateDashboard() {
      $.getJSON(
        "?action=api_dashboard_prd_single",
        { line_id: $("#line_id").val() },
        function (data) {

          /*update series per line*/
          chart_line.updateSeries([data.eff]);
          $(".line_name").html(data.line_name);
          $("#pln_qty").html(data.pln_qty);
          $("#prd_qty").html(data.prd_qty);
          $("#balance").html(data.balance);
          $("#cctime").html(data.cctime);
          $("#stopdies").html(data.stop_dies);
          $("#stopmesin").html(data.stop_mesin);
          $("#achieve").html(data.achieve);
          $("#data_eff").html(data.eff);
          $("#data_ril").html(data.ril);
          $("#data_rol").html(data.rol);
        }
      );
    }

    function dateTime() {
      const now = new Date();
      const date = now.toLocaleDateString('en-US', {
        weekday: 'long',
        year: 'numeric',
        month: 'numeric',
        day: 'numeric'
      });
      const time = now.toLocaleTimeString('id-ID', {
        hour: 'numeric',
        minute: 'numeric',
        timeZoneName: 'short'
      });

      $("#date").html(date);
      $("#time").html(time);
    }

    var elem = document.getElementById("fs");

    function requestFullscreen() {
      if (document.fullscreenEnabled) {
        if (elem.requestFullscreen) {
          elem.requestFullscreen(); // Request full-screen mode
        } else if (elem.mozRequestFullScreen) { /* Firefox */
          elem.mozRequestFullScreen();
        } else if (elem.webkitRequestFullscreen) { /* Chrome, Safari and Opera */
          elem.webkitRequestFullscreen();
        } else if (elem.msRequestFullscreen) { /* IE/Edge */
          elem.msRequestFullscreen();
        }
      }
    }
  </script>
</body>

</html>