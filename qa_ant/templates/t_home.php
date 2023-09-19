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
          <ol class="breadcrumb mb-2 bg-transparent">
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
                
                <div class='row'>
                  <!-- Filter -->
                  <div class='col-12'>
                    <table class='table table-bordered'>
                      <tr>
                        <td class='align-middle text-center'>Nama Proses</td>
                        <td class='align-middle text-center'><select class="select2" id="grp_id" name="grp_id">
                              <?php 
                              foreach($group_list as $list) {
                                echo '<option value="'.$list["grp_id"].'">'.$list["grp_no"].". ".$list["name1"].'</option>';
                              }
                              ?>
                            </select></td>
                        <td class='align-middle text-center'>Part Number</td>
                        <td class='align-middle text-center'>484120-11180<br>484120-11190</td>
                        <td class='align-middle text-center'>Alat Ukur</td>
                        <td class='align-middle text-center'><span id='mdev_name'><?=$item_list[0]["dev_name"]?></span></td>
                        <td class='align-middle text-center'>Bulan</td>
                        <td class='align-middle text-center'><input type="text" class="form-control month-picker" id="imonth" value="<?=date("Y-m")?>"></td>
                      </tr>
                      <tr>
                        <td class='align-middle text-center'>Tingkat Kontrol</td>
                        <td class='align-middle text-center'><span id='std_min_max'><?=$item_list[0]["std_min"]." - ".$item_list[0]["std_max"]." ".$item_list[0]["std_uom"]?></span></td>
                        <td class='align-middle text-center'>Item Kontrol</td>
                        <td class='align-middle text-center'><select class="select2" id="itm_id" name="itm_id">
                              <?php 
                              foreach($item_list as $list) {
                                echo '<option value="'.$list["itm_id"].'">'.$list["name1"].'</option>';
                              }
                              ?>
                            </select></td>
                        <td class='align-middle text-center'>Frekuensi Pengecekan</td>
                        <td class='align-middle text-center'>First & Last</td>
                        <td class='align-middle text-center'>Judgement</td>
                        <td class='align-middle text-center'></td>
                      </tr>
                    </table>                    
                  </div>
                  <!-- Chart -->
                  <div class='col-12'>
                    <div id='chart1'></div>
                  </div>
                  <div class='col-12'>
                    <div id='chart2'></div>
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
    $(document).ready(function(){
      $("#imonth").flatpickr({
        altInput:true,
        plugins: [
          new monthSelectPlugin({
            shorthand: true, //defaults to false
            dateFormat: "Y-m", //defaults to "F Y"
            altFormat: "M Y",
          })
        ]
      });
      updateOptions();
      updateChart();
    });
    
    var options1 = {
      series: [
      {
        name: "First",
        data: [null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null]
      },
      {
        name: "Last",
        data: [null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null]
      }
    ],
      chart: {
      height: 250,
      type: 'line',
      dropShadow: {
        enabled: true,
        color: '#000',
        top: 18,
        left: 7,
        blur: 10,
        opacity: 0.2
      },
      toolbar: {
        show: false
      }
    },
    colors: ['#00B0F0', '#FFC000'],
    dataLabels: {
      enabled: true,
    },
    stroke: {
      curve: 'straight'
    },
    grid: {
      borderColor: '#e7e7e7',
      row: {
        colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
        opacity: 0.5
      },
    },
    markers: {
      size: 1
    },
    xaxis: {
      categories: ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31'],
      /*title: {
        text: 'Tanggal'
      }*/
    },
    yaxis: {
      title: {
        text: 'Pagi'
      },
      min: 4,
      max: 5
    },
    legend: {
      position: 'top',
      horizontalAlign: 'right',
      floating: true,
      offsetY: -25,
      offsetX: -5
    }
    };

    var chart1 = new ApexCharts(document.querySelector("#chart1"), options1);
    chart1.render();
    
    var options2 = {
      series: [
      {
        name: "First",
        data: [null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null]
      },
      {
        name: "Last",
        data: [null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null]
      }
    ],
      chart: {
      height: 250,
      type: 'line',
      dropShadow: {
        enabled: true,
        color: '#000',
        top: 18,
        left: 7,
        blur: 10,
        opacity: 0.2
      },
      toolbar: {
        show: false
      }
    },
    colors: ['#00B0F0', '#FFC000'],
    dataLabels: {
      enabled: true,
    },
    stroke: {
      curve: 'straight'
    },
    grid: {
      borderColor: '#e7e7e7',
      row: {
        colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
        opacity: 0.5
      },
    },
    markers: {
      size: 1
    },
    xaxis: {
      categories: ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31'],
      /*title: {
        text: 'Tanggal'
      }*/
    },
    yaxis: {
      title: {
        text: 'Malam'
      },
      min: 4,
      max: 5
    },
    legend: {
      position: 'top',
      horizontalAlign: 'right',
      floating: true,
      offsetY: -25,
      offsetX: -5
    }
    };

    var chart2 = new ApexCharts(document.querySelector("#chart2"), options2);
    chart2.render();
    
    $("#grp_id").on("change",function(){
      var grp_id = this.value;
      $.getJSON("?action=api_check_item", {grp_id:grp_id}, function (data) { 
        var itm_id = "";
        $.each(data, function(row, value){
          itm_id += "<option value='"+value.itm_id+"'>"+value.name1+"</option>";
        });
        $("#itm_id").html(itm_id).trigger('change');
      });
    });
    
    $("#itm_id").on("change",function(){      
      updateOptions();
      updateChart();
    });
    
    $("#imonth").on("change",function(){      
      updateOptions();
      updateChart();
    });
    
    function updateOptions() {
      var itm_id = $("#itm_id").val();
      var std_min = 0;
      var std_max = 0;
      $.getJSON("?action=api_check_item_det", {itm_id:itm_id}, function (data) { 
        $("#mdev_name").html(data.dev_name);
        $("#std_min_max").html(data.std_min+" - "+data.std_max+" "+data.std_uom);
        std_min = parseFloat(data.std_min);
        std_max = parseFloat(data.std_max);
        
        chart1.updateOptions({
          yaxis: {
            title: {
              text: 'Pagi'
            },
            min: std_min,
            max: std_max
          }
        });
        
        chart2.updateOptions({
          yaxis: {
            title: {
              text: 'Malam'
            },
            min: std_min,
            max: std_max
          }
        });
      });
    }
    
    function updateChart() {
      var itm_id = $("#itm_id").val();
      var imonth = $("#imonth").val();
      $.getJSON("?action=api_get_trn_mon", {itm_id:itm_id,imonth:imonth}, function (data) { 
        chart1.updateOptions({
          series: [
          {
            name: "First",
            data: data.chart_1_f
          },
          {
            name: "Last",
            data: data.chart_1_l
          }
          ]
        });
        
        chart2.updateOptions({
          series: [
          {
            name: "First",
            data: data.chart_2_f
          },
          {
            name: "Last",
            data: data.chart_2_l
          }
          ]
        });
      });
    }
  </script>
</body>

</html>