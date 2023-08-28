<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>

  <head>
    <?php include "common/t_css.php"; ?>
    <link href="vendors/font/PressStart.css" rel="stylesheet" type="text/css"/>
    <link href="vendors/ega/css/styles.css" rel="stylesheet" type="text/css" />
    <style>
      body {
        font-family: 'Roboto', Arial, sans-serif !important;
        font-size: 98px !important;
        font-weight: 900;
        line-height: 1.3 !important;
      }
      
      .disp-1 {
        font-family: 'Roboto', Arial, sans-serif !important;
        font-size: 172px !important;
        font-weight: 900;
        line-height: 1.3 !important;
      }
    </style>
  </head>

  <body class="h-100">
    <div id="layoutSidenav">
      <div id="layoutSidenav_content">
        <main class="mt-1 h-100">
          <div class="px-1 bg-white h-100" id="fs">
            <div id="carouselExampleIndicators" class="carousel slide h-100" data-ride="carousel">
              <div class="carousel-inner h-100">
                <div class="carousel-item active h-100">
                  <div class="d-flex justify-content-center align-items-center my-auto h-100">
                    <h1 class='disp-1'>Dies Map</h1>
                  </div>
                </div>
                <?php
                //generate lokasi parkir
                foreach ($list_zona as $list) {
                  if ($list["zona_type"] == "P") {
                ?>
                <div class="h-100 carousel-item <?=$list["bg"]?>">
                  <p><?=$list["desc"]?></p>
                  <hr>
                  <div class="container-fluid">
                    <div id="zona_<?=$list["zona_id"]?>" class="data-zona row">

                    </div>
                  </div>
                </div>
                <?php
                  }
                }
                ?>
                <?php
                //generate lokasi produksi
                foreach ($list_zona as $list) {
                  if ($list["zona_type"] == "L") {
                ?>
                <div class="h-100 carousel-item <?=$list["bg"]?>">
                  <p><?=$list["desc"]?></p>
                  <hr>
                  <div class="container-fluid">
                    <div id="zona_<?=$list["zona_id"]?>" class="data-zona row">

                    </div>
                  </div>
                </div>
                <?php
                  }
                }
                ?>
                <?php
                //generate lokasi Maintenance
                foreach ($list_zona as $list) {
                  if ($list["zona_type"] == "M") {
                ?>
                <div class="h-100 carousel-item <?=$list["bg"]?>">
                  <p><?=$list["desc"]?></p>
                  <hr>
                  <div class="container-fluid">
                    <div id="zona_<?=$list["zona_id"]?>" class="data-zona row">

                    </div>
                  </div>
                </div>
                <?php
                  }
                }
                ?>
                <div class="h-100 carousel-item h-100">
                  <div class="d-flex justify-content-center align-items-center my-auto h-100">
                    <h1 class='disp-1'>Dies Status</h1>
                  </div>
                </div>
                <?php
                $jumlah_data = count($data_dies);
                $jumlah_carousel = floor($jumlah_data / 6);
                $sisa_carousel = $jumlah_data % 6;
                if($sisa_carousel > 0) {
                  $jumlah_carousel += 1;
                }
                $x = 0;
                
                for($i = 0; $i < $jumlah_carousel; $i++) {
                  $y = 0;
                ?>
                <div class="carousel-item">
                  <div class="row mx-1">
                  <?php
                  while($x < $jumlah_data) {
                    
                    if($y == 6) {
                      break;
                    }
                    echo "<div class='col-6 p-1'>
                            <div id='bg_colour_".$data_dies[$x]["dies_id"]."' class='container-fluid border text-dark ".$data_dies[$x]["bg_color"]."' style='background-color: ".$data_dies[$x]["colour"].";'>
                              <div class='row'>
                                <div class='col-8'>
                                  <p class='text-left mb-0'>".$data_dies[$x]["group_id"]." ".$data_dies[$x]["model_id"]."</p>
                                  <p class='text-left mb-0'>".$data_dies[$x]["dies_no"]."</p>
                                </div>
                                <div class='col-4'>
                                  <p class='text-right mb-0'><span id='stkrun_".$data_dies[$x]["dies_id"]."'>".$data_dies[$x]["stkrun"]."</span></p>
                                  <p class='text-right mb-0'><span id='stk6k_".$data_dies[$x]["dies_id"]."'>".$data_dies[$x]["stk6k"]."</span></p>
                                </div>
                              </div>
                            </div>
                          </div>";
                    $x++;
                    $y++;
                  }
                  ?>
                  </div>
                </div>
                <?php
                }
                ?>
               
              </div>
            </div>
          </div>
        </main>
      </div>
    </div>
    <input type="hidden" id="usrid" value="<?php echo $_SESSION[LOGIN_SESSION]; ?>">
    <?php include 'common/t_js.php'; ?>
    <script src="vendors/ega/js/scripts.js?time=<?php echo date("Ymdhis"); ?>" type="text/javascript"></script>
    <script>
      setInterval(updateDashboardStatusDies, 5000);
      $(document).ready(function () {
        // closeFullscreen();
      });
      
      function updateDashboardStatusDies() {
        $.getJSON("?action=api_dashboard_dm", {}, function (data) {
          var data_dies = data.data_dies;
          $(".data-zona").html("");
          $.each(data_dies, function(row, value){
            $("#bg_colour_"+value.dies_id).removeClass();
            $("#bg_colour_"+value.dies_id).addClass("container-fluid border text-dark");
            if(value.bg_color == "bg-light") {
            } else {
              $("#bg_colour_"+value.dies_id).addClass(value.bg_color);
            }
            $("#stkrun_"+value.dies_id).html(value.stkrun);
            $("#stk6k_"+value.dies_id).html(value.stk6k);
            
            $("#zona_"+value.zona_id).append("<div class='col-6 text-dark p-1' style='background-color:"+value.colour+";'>"+value.group_id+" " + value.model_id + " " + value.dies_no + "</div>");
          });
        });
      }
    </script>
  </body>

</html>