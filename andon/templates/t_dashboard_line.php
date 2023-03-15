<!DOCTYPE html>
<html lang="en">
  <?php include "common/t_css.php"; ?>
  <body>
    <?php include "common/t_nav_top.php"; ?>    
    <main>
      <div class="container-fluid mt-2">
        <ol class="breadcrumb mb-2">
          <li class="breadcrumb-item"><?php echo $template["group"]; ?></li>
          <li class="breadcrumb-item active"><?php echo $template["menu"]; ?></li>
        </ol>
        <div class="card mb-3">
          <div class="card-body">
            <div class="container-fluid text-center">
              <h2>Efficiency Line <span class="line_name"><?=$line_name?></span></h2>
              <div id='chart_line'></div>              
            </div>
          </div>
        </div>
        <div class="card mb-3">
          <div class="card-body">
            <div class="container-fluid">
              <div class="table-responsive">
                <table class="table table-bordered">                  
                  <tbody>
                    <tr>
                      <th rowspan="2" class="text-center align-middle h3">Actual (%)</th>
                      <th class="text-center line_name h3"><?=$line_name?></th>
                    </tr>
                    <tr>                      
                      <td class="text-center h3" id="data_eff"><?=$eff?> %</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="container-fluid">
              <div class="table-responsive">
                <table class="table table-bordered">                  
                  <tbody>
                    <tr>
                      <th class="text-center h3">Line</th>
                      <th class="text-center line_name h3"><?=$line_name?></th>
                    </tr>
                    <tr>
                      <td class="text-center h3">RIL</td>                      
                      <td class="text-center h3"><span id="data_ril"><?=$ril?> %</span></td>
                    </tr>
                    <tr>
                      <td class="text-center h3">ROL</td>                      
                      <td class="text-center h3"><span id="data_rol"><?=$rol?> %</span></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="container-fluid">
              <form method="get" action="<?=$action?>">
                <div class="form-group text-center">
                  <label for="line_id" class="h3">Select Line</label>
                  <select class="form-control" id="line_id" name="line_id">
                    <?php 
                    if(!empty($lines)) {
                      foreach($lines as $row) {
                        if($line_id == $row["line_id"]) {
                          echo "<option value='".$row["line_id"]."' selected class='h3'>".$row["name1"]."</option>";
                        } else {
                          echo "<option value='".$row["line_id"]."' class='h3'>".$row["name1"]."</option>";
                        }
                      } 
                    }
                    
                    ?>
                  </select>
                </div>
              </form>
            </div>
          </div>
        </div>  
      </div>
    </main>
    <?php include 'common/t_js.php'; ?>
    <script>
      setInterval(updateDashboard, 5000);
      var options = {
          series: [<?=$eff?>],
          chart: {
          type: 'radialBar',
          offsetY: -20,
          width:'100%',
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
          },
        },
        labels: ['Production Efficiency'],
      };
      var chart_line = new ApexCharts(document.querySelector('#chart_line'), options);
      chart_line.render();
    
      function updateDashboard() {
        $.getJSON(
          "?action=api_dashboard_prd_single", 
          {line_id:$("#line_id").val()}, 
          function(data) {  

            /*update series per line*/          
            chart_line.updateSeries([data.eff]);
            $(".line_name").html(data.line_name);
            $("#data_eff").html(data.eff+" %");
            $("#data_ril").html(data.ril+" %");
            $("#data_rol").html(data.rol+" %");
          }
        );
      }
    </script>
  </body>
</html>
