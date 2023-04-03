<!DOCTYPE html>
<html lang="en">
<?php include "common/t_css.php"; ?>

<body>
  <style>
    .toast-100 {
      max-width: 100% !important;
      width: 100% !important;
    }
  </style>
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
      <div style="position: absolute; top: 20px; left: 0;" class="container-fluid">
        <div id="toast_container" class="position-relative w-100 bg-dark" style="z-index:9999;" >
          <?php
          if(!empty($data_andon_status)) {
            foreach($data_andon_status as $row) {
          ?>
          <div class="alert text-center <?=$row["bgcolor"].' '.$row["text_color"]?>" role="alert" id="<?=$row["line_id"]."_".$row["mach_id"]."_".$row["andon_id"]?>" style="display: none;">
            <h1 class="display-1"><?=$row["mach_id"]?> <?=$row["desc"]?></h1>
          </div>
          <?php
            }
          } 
          ?>
        </div>
      </div>
        
      <div class="card mb-5">
        <div class="card-body">
          <div class="container-fluid text-center">
            <div class="row">
              <div class="col text-left pl-0">
                <h2 style="color: #002E94">Cycle Time</h2>
                <h1 style="color: #19A7CE" id="cctime">
                  <?= $cctime ?>
                </h1>
              </div>
              <div class="col">
                <h1 style="color: #002E94">LINE EFFICIENCY</h1>
                <span class="font-weight-bold" style="font-size: 96px; color: #19A7CE">
                  <?= $line_name ?>
                </span>
              </div>
              <div class="col text-right pr-0">
                <h3 id="date" style="color: #002E94"></h3>
                <h3 id="time" style="color: #002E94"></h3>
              </div>
            </div>
            <div id='chart_line' style="overflow:hidden"></div>
          </div>
        </div>
      </div>
      <!-- <div class="card mb-3">
        <div class="card-body"> -->
      <div class="container-fluid p-0">
        <div class="table-responsive">
          <table class="table table-bordered">
            <tbody>
              <tr>
                <td class="text-center h1" style="width: 60%;color: #002E94;font-size:60px">TARGET QTY</td>
                <td class="text-right align-middle h1 border-right-0" id="pln_qty"
                  style="width: 25%;color: #002E94;font-size:60px">
                  <?= $pln_qty ?>
                </td>
                <td class="text-left align-middle h1 border-left-0" 
                  style="width: 15%;color: #002E94;font-size:60px">
                </td>
              </tr>
              <tr>
                <td class="text-center h1" style="width: 60%;color: #19A7CE;font-size:60px">ACTUAL QTY</td>
                <td class="text-right align-middle h1 border-right-0" id="prd_qty"
                  style="width: 25%;color: #19A7CE;font-size:60px">
                  <?= $prd_qty ?>
                </td>
                <td class="text-left align-middle h1 border-left-0" 
                  style="width: 15%;color: #19A7CE;font-size:60px">
                </td>
              </tr>
              <tr>
                <td class="text-center h1" style="width: 60%;color: #002E94;font-size:60px">BALANCE</td>
                <td class="text-right align-middle h1 border-right-0" id="balance"
                  style="width: 25%;color: #002E94;font-size:60px">
                  <?= $balance ?>
                </td>
                <td class="text-left align-middle h1 border-left-0" 
                  style="width: 15%;color: #002E94;font-size:60px">
                </td>
              </tr>
              <tr>
                <td class="text-center h1" style="width: 60%;color: #19A7CE;font-size:60px">ACHIEVE (%)</td>
                <td class="text-right align-middle h1 border-right-0" id="achieve"
                  style="width: 25%;color: #19A7CE;font-size:60px">
                  <?= $achieve ?>
                </td>
                <td class="text-left align-middle h1 border-left-0" 
                  style="width: 15%;color: #19A7CE;font-size:60px">
                  %
                </td>
              </tr>
              <tr>
                <td class="text-center h1" style="width: 60%;color: #002E94;font-size:60px">LOSS TIME (PART)</td>
                <td class="text-right align-middle h1 border-right-0 " id="stopdies"
                  style="width: 25%;color: #002E94;font-size:60px">
                  <?= $stop_dies ?>
                </td>
                <td class="text-left align-middle h1 border-left-0" 
                  style="width: 15%;color: #002E94;font-size:60px">
                  s
                </td>
              </tr>
              <tr>
                <td class="text-center h1" style="width: 60%;color: #19A7CE;font-size:60px">LOSS TIME (M/C)</td>
                <td class="text-right align-middle h1 border-right-0" id="stopmesin"
                  style="width: 25%;color: #19A7CE;font-size:60px">
                  <?= $stop_mesin ?>
                </td>
                <td class="text-left align-middle h1 border-left-0" 
                  style="width: 15%;color: #19A7CE;font-size:60px">
                  s
                </td>
              </tr>              
              <tr>
                <td class="text-center h1" style="width: 60%;color: #002E94;font-size:60px">DANDORI</td>
                <td class="text-right h1 border-right-0"><span id="data_dandori"
                    style="width: 25%;color: #002E94;font-size:60px">
                    <?= $dandori ?>
                  </span></td>
                <td class="text-left h1 border-left-0"><span 
                    style="width: 15%;color: #002E94;font-size:60px">
                    s
                  </span></td>
              </tr>
              <tr>
                <td class="text-center h1" style="width: 60%;color: #19A7CE;font-size:60px">RIL</td>
                <td class="text-right h1 border-right-0"><span id="data_ril"
                    style="width: 25%;color: #19A7CE;font-size:60px">
                    <?= $ril ?>
                  </span></td>
                <td class="text-left h1 border-left-0"><span 
                    style="width: 15%;color: #19A7CE;font-size:60px">
                    %
                  </span></td>
              </tr>
            </tbody>
          </table>
          <!-- </div>
          </div> -->
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
    <input type="hidden" id="line_id" value="<?= $_GET["line_id"] ?>">
  </main>  
      
  <?php include 'common/t_js.php'; ?>
  <script>
    $(document).ready(function(){
      setInterval(updateDashboard, 5000);
      setInterval(dateTime, 1000);
    });
    // Detect fullscreen support
    /*var fullscreenEnabled = document.fullscreenEnabled || document.webkitFullscreenEnabled || document.mozFullScreenEnabled || document.msFullscreenEnabled;

    // Request fullscreen
    function requestFullscreen(element) {
      if (element.requestFullscreen) {
        element.requestFullscreen();
      } else if (element.webkitRequestFullscreen) {
        element.webkitRequestFullscreen();
      } else if (element.mozRequestFullScreen) {
        element.mozRequestFullScreen();
      } else if (element.msRequestFullscreen) {
        element.msRequestFullscreen();
      }
    }

    // Exit fullscreen
    function exitFullscreen() {
      if (document.exitFullscreen) {
        document.exitFullscreen();
      } else if (document.webkitExitFullscreen) {
        document.webkitExitFullscreen();
      } else if (document.mozCancelFullScreen) {
        document.mozCancelFullScreen();
      } else if (document.msExitFullscreen) {
        document.msExitFullscreen();
      }
    }

    // Toggle fullscreen mode
    function toggleFullscreen() {
      if (document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement || document.msFullscreenElement) {
        exitFullscreen();
      } else {
        requestFullscreen(document.documentElement);
      }
    }

    // Check if fullscreen mode is already active
    if (!document.fullscreenElement && !document.webkitFullscreenElement && !document.mozFullScreenElement && !document.msFullscreenElement) {
      toggleFullscreen();
    }
    */
      
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
        width: '150%',
        sparkline: {
          enabled: true
        }
      },
      plotOptions: {
        radialBar: {
          startAngle: -90,
          endAngle: 90,
          offsetX: -210,
          track: {
            background: '#e7e7e7',
            strokeWidth: '100%',
            margin: 0, // margin is in pixels
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
              fontSize: '9em',
              fontWeight: 'bold'
            }
          }
        }
      },
      grid: {
        padding: {
          top: -10
        },
        margin: 0
      },
      fill: {
        colors: [function ({ value, seriesIndex, w }) {
          if (value < 30) {
            return '#E21818'
          } else if (value >= 30 && value < 70) {
            return '#F7C04A'
          } else {
            return '#03C988'
          }
        }]
      },
      labels: ['Production Efficiency'],
    };
    var chart_line = new ApexCharts(document.querySelector('#chart_line'), options);
    chart_line.render();

    function updateDashboard() {
      $.getJSON(
        "?action=api_dashboard_adn_single",
        { line_id: $("#line_id").val() },
        function (data) {
          console.log(typeof data.prd_qty)

          /*update series per line*/
          chart_line.updateSeries([data.eff]);
          $(".line_name").html(data.line_name);
          $("#pln_qty").html(data.pln_qty);
          $("#prd_qty").html(data.prd_qty);
          $("#balance").html(data.balance);
          $("#cctime").html(data.cctime);
          $("#stopdies").html(data.stop_dies ? data.stop_dies : 0);
          $("#stopmesin").html(data.stop_mesin ? data.stop_mesin : 0);
          $("#dandori").html(data.dandori ? data.dandori : 0);
          $("#achieve").html(data.achieve);
          $("#data_eff").html(data.eff);
          $("#data_ril").html(data.ril);
          $("#data_rol").html(data.rol);
        }
      );
    }

    function dateTime() {
      const now = new Date();
      const date = now.toLocaleDateString('en-GB', {
        weekday: 'long',
        day: 'numeric',
        month: 'numeric',
        year: 'numeric',
      }).replace(/\//g, '-');
      const time = now.toLocaleTimeString('id-ID', {
        hour: 'numeric',
        minute: 'numeric',
        timeZoneName: 'short'
      });

      $("#date").html(date);
      $("#time").html(time);
      
      updateAndonStatus();
    }
    
    function updateAndonStatus() {
      var className = document.getElementsByClassName('alert');
      var classnameCount = className.length;
      var toast_id = new Array();
      for(var j = 0; j < classnameCount; j++){
        toast_id.push(className[j].id);
      }
      
      var line_id = $("#line_id").val();
      if(line_id.length > 0) {
        $.getJSON("?action=api_get_andon_status",{line_id:line_id},function(result){
          if(result.status == true) {
            //show alert
            //$('.toast').toast('hide');
            var data = result.data;
            $.each(toast_id, function(row, value){
              var toast_show = false;
              $.each(data, function(row, value2){
                toast_id_single = value2.line_id+"_"+value2.mach_id+"_"+value2.andon_id;
                if(toast_id_single == value) {
                  toast_show = true;
                  return false;
                }
              });
              if(toast_show === true) {
                $("#"+value).show();
              } else {
                $("#"+value).hide();
              }
            });
            
          } else {
            $('.alert').hide();
          }
        });
      }
    }
  </script>
</body>

</html>