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
                <div id="chart">

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
      var options = {
        series: [
          {            
            data: 
              <?php  echo json_encode($data_main, JSON_NUMERIC_CHECK); ?>
            
          }
        ],
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
            barHeight: '80%'
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
          position: 'top',
          horizontalAlign: 'left'
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
        
      });
      
      setInterval(updateAnnotation,5000);
      
      function updateAnnotation() {
        chart.clearAnnotations();
        chart.addXaxisAnnotation({
          x: new Date().getTime(),
          borderColor: '#775DD0',
          label: {
            style: {
              color: '#775DD0',
            },
            text: 'Current Time'
          },
        });
      }
      
      setInterval(updateDashboard,5000);
      function updateDashboard() {
        $.getJSON(
          "?action=api_dashboard_pooling", 
          {device_id:'<?=$_SERVER["REMOTE_ADDR"]?>'}, 
          function(data) {
            //var data_per_jam = data.data_per_jam;
            chart.updateSeries([data]);
          });
      }
       
    </script>
  </body>
</html>
