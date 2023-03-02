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
        <div class="container-fluid mt-2" id="fs">
          <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Profile</a>
            </li>
          </ul>
          <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
              <div class="card mb-3 border-top-0">
                <div class="card-body">
                  <div class="row">
                    <div class="col-3">
                      <a id="logo" class="navbar-brand mb-3" href=".."><img src="media/images/logo.svg" height="30" alt="logo" /></a>
                    </div>
                    <div class="col-6">
                      <div id="title" class="text-ega-blue text-center">
                        <h4 class='mb-3' style="font-weight: 700; ">DASHBOARD DIES MAINTENANCE</h4>
                      </div>
                    </div>
                    <div class="col-3">
                      <div class="d-flex justify-content-end">
                        <button id="close-fs" type="button" class="btn btn-link" onclick="closeFullscreen()"><i class="material-icons">fullscreen_exit</i></button>
                      </div>
                    </div>
                  </div>
                  <div class="row" id="dashboard">
                    <?php
                    if (!empty($data_group)) {
                      foreach ($data_group as $grp) {
                        echo "<div class='col-lg-4 col-md-6 col-sm-12 px-1'><div class='card'>"
                          . "<div class='card-header text-center'><h4 class='card-title mb-0 text-uli-blue font-weight-bold'>" . $grp["pval2"] . "</h4></div>"
                          . "<div class='card-body p-1 rounded'><div class='container-fluid'>";
                        if (!empty($data_model)) {
                          foreach ($data_model as $mdl) {
                            if ($mdl["group_id"] == $grp["pval1"]) {
                              echo "<div class='row mb-1'>"
                                . "<div class='col-lg-3 p-1'>"
                                . "<div class='card'>"
                                . "<div class='px-2 py-2 card-body rounded border border-secondary rounded' style=' background-color: " . $mdl["colour"] . "; '>"
                                . "<h4 class='card-title text-center mb-0 font-weight-bold " . $mdl["font_colour"] . "'>" . $mdl["model_id"] . "</h4>"
                                . "</div>"
                                . "</div>"
                                . "</div>";
                              echo "<div class='col-lg-9 p-1'><div class='container-fluid'>"
                                . "<div class='row'>";
                              foreach ($data_dies as $dies) {
                                if ($dies["group_id"] == $mdl["group_id"] && $dies["model_id"] == $mdl["model_id"]) {

                                  echo "<div class='col-lg-3 p-0'>"
                                    . "<div class='card'>"
                                    . "<a id='dies_data' href='?action=CHECKSHEET_PREVENTIVE&id=0&step=1&group_id=" . $dies["group_id"] . "&model_id=" . $dies["model_id"] . "&dies_id=" . $dies["dies_id"] . "' class='card-body border border-secondary rounded p-1  " . $dies["bg_color"] . "'>"
                                    . "<h4 class='card-title text-center mb-0 text-dark font-weight-bold'>" . $dies["dies_no"] . "</h4>"
                                    . "<p class='m-0 text-center text-dark small text-nowrap font-weight-bold' >Stroke</p>"
                                    . "<p class='m-0 text-dark small text-nowrap font-weight-bold '>" . str_pad("Prev", 6, " ", STR_PAD_RIGHT) . "<span>:" . $formatted_number = number_format($dies["stkrun"], 0, ',', '.') . "</span></p>"
                                    . "<p class='m-0 text-dark small text-nowrap font-weight-bold '>" . str_pad("Act", 6, " ", STR_PAD_RIGHT) . "<span>:" . $formatted_number = number_format($dies["stk6k"], 0, ',', '.') . "</span></p>"
                                    . "</a>"
                                    . "</div>"
                                    . "</div>";
                                }
                              }
                              echo "</div></div>"
                                . "</div>"
                                . "</div>";
                            }
                          }
                        }
                        echo "</div></div></div></div>";
                      }
                    }
                    ?>
                  </div>
                  <div class="row mt-4">
                    <div class="col-lg-12 col-md-12 col-sm-12 px-1">
                      <div class="card">
                        <div class='card-header align-middle'>
                          <h5 class='card-title mb-0 text-uli-blue font-weight-bold'>Legend</h5>
                        </div>
                        <div class="card-body p-1 ">
                          <table class="table table-borderless table-sm mb-0">
                            <tbody>
                              <tr>
                                <td style="width: 100px;" class="bg-ivory">White</td>
                                <td>Dies stroke < 1,600</td>

                                <td style="width: 100px;" class="bg-yellow">Yellow</td>
                                <td>Dies stroke >= 1,600</td>

                                <td style="width: 100px;" class="bg-danger">Red</td>
                                <td>Dies stroke > 2,000</td>
                              </tr>
                              <tr>
                                <td style="width: 100px;" class="bg-blink">Blue (Blinking)</td>
                                <td>Dies under preventive maintenance</td>

                                <td style="width: 100px;" class="bg-amber">Orange</td>
                                <td>Dies under repair to maker</td>

                                <td style="width: 100px;" class="bg-red-blink">Red (Blinking)</td>
                                <td>Dies not yet finish preventive</td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
              <div class="card mb-3 border-top-0">
                <div class="card-body">
                  <div class="d-flex justify-content-center mt-1">
                    <div class="row">
                      <?php
                      foreach ($list_zona as $list) {
                        if ($list["zona_type"] == "P") {
                          echo "<div class='col-3'>"
                            . "<div class='card ".$list["bg"]."' style='width: 300px'>"
                            . "<div class='card-body'>"
                            . "<div class='card-title'>" . $list["desc"] . "</div>"
                            . "<div id='".$list["zona_id"]."'></div>"
                            . "</div>"
                            . "</div>"
                            . "</div>";
                        }
                      }
                      ?>
                    </div>
                    <!-- <div class="card" style="width: 300px">
                      <div class="card-body">
                        <div class="card-title">Parking Area DC5</div>
                      </div>
                    </div> -->
                  </div>
                  <div class="d-flex justify-content-center mt-1">
                    <div class="row">
                      <?php
                      foreach ($list_zona as $list) {
                        if ($list["zona_type"] == "M") {
                          echo "<div class='col-3'>"
                            . "<div class='card ".$list["bg"]."' style='width: 300px'>"
                            . "<div class='card-body'>"
                            . "<div class='card-title'>" . $list["desc"] . "</div>"
                            . "<div id='".$list["zona_id"]."'></div>"
                            . "</div>"
                            . "</div>"
                            . "</div>";
                        }
                      }
                      ?>
                    </div>
                    <!-- <div class="card" style="width: 300px">
                      <div class="card-body">
                        <div class="card-title">Parking Area DC5</div>
                      </div>
                    </div> -->
                  </div>
                  <!-- <div class="d-flex container justify-content-center">
                    <div>
                      <div class="card" style="width: 300px">
                        <div class="card-body">
                          <div class="card-title">Parking Area TCC A</div>
                        </div>
                      </div>
                      <br />
                      <div class="card">
                        <div class="card-body bg-success">
                          <div class="card-title">Zone TCC A</div>
                        </div>
                      </div>
                      <div class="card">
                        <div class="card-body bg-success">
                          <div class="card-title">Zone TCC B</div>
                        </div>
                      </div>
                    </div>
                    <div>
                      <div class="card" style="width: 300px">
                        <div class="card-body">
                          <div class="card-title">Parking Area Emergency A</div>
                        </div>
                      </div>
                      <br />
                      <div class="card">
                        <div class="card-body bg-warning">
                          <div class="card-title">Zone Emergency A</div>
                        </div>
                      </div>
                      <div class="card">
                        <div class="card-body bg-success">
                          <div class="card-title">Zone OPN A</div>
                        </div>
                      </div>
                    </div>
                    <div>
                      <div class="card" style="width: 300px">
                        <div class="card-body">
                          <div class="card-title">Parking Area Emergency B</div>
                        </div>
                      </div>
                      <br />
                      <div class="card">
                        <div class="card-body bg-warning">
                          <div class="card-title">Zone Emergency B</div>
                        </div>
                      </div>
                      <div class="card">
                        <div class="card-body bg-success">
                          <div class="card-title">Zone OPN B</div>
                        </div>
                      </div>
                    </div>
                    <div>
                      <div class="card" style="width: 300px">
                        <div class="card-body">
                          <div class="card-title">Parking Area CSH B</div>
                        </div>
                      </div>
                      <br />
                      <div class="card">
                        <div class="card-body bg-success">
                          <div class="card-title">Zone CSH A</div>
                        </div>
                      </div>
                      <div class="card">
                        <div class="card-body bg-success">
                          <div class="card-title">Zone CSH B</div>
                        </div>
                      </div>
                    </div>
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
  <?php include 'common/t_js.php'; ?>
  <script src="vendors/ega/js/scripts.js?time=<?php echo date("Ymdhis"); ?>" type="text/javascript"></script>
  <script>
    setInterval(updateDashboard, 5000);

    $(document).ready(function() {
      // closeFullscreen();
    });

    function updateDashboard() {
      $.getJSON(
        "?action=api_dashboard_dm", {},
        function(data) {
          var data_dies = data.data_dies;
          var data_group = data.data_group;
          var data_model = data.data_model

          var append_data = "";
          if (data_group.length !== 0) {
            var i = 0;
            $.each(data_group, function(row, grp) {
              append_data += "<div class='col-lg-4 col-md-6 col-sm-12 px-1'><div class='card'>";
              append_data += "<div class='card-header text-center'><h4 class='card-title mb-0 text-uli-blue font-weight-bold'>" + data_group[i].pval1 + "</h4></div>";
              append_data += "<div class='card-body p-1 rounded'><div class='container-fluid'>";
              if (data_model.length !== 0) {
                var j = 0;
                $.each(data_model, function(row, mdl) {
                  if (data_model[j].group_id == data_group[i].pval1) {
                    append_data += "<div class='row mb-1'>";
                    append_data += "<div class='col-lg-3 p-1'>";
                    append_data += "<div class='card'>";
                    append_data += "<div class='px-2 py-2 card-body rounded border border-secondary rounded' style=' background-color: " + data_model[j].colour + "; '>";
                    append_data += "<h4 class='card-title text-center mb-0 font-weight-bold " + data_model[j].font_colour + "'>" + data_model[j].model_id + "</h4>";
                    append_data += "</div>";
                    append_data += "</div>";
                    append_data += "</div>";
                    append_data += "<div class='col-lg-9 p-1'><div class='container-fluid'>";
                    append_data += "<div class='row'>";
                    var x = 0;
                    $.each(data_dies, function(row, dies) {
                      if (data_dies[x].group_id == data_model[j].group_id && data_dies[x].model_id == data_model[j].model_id) {
                        // console.log(data_dies[x].bg_color);
                        append_data += "<div class='col-lg-3 p-0'>";
                        append_data += "<div class='card'>";
                        append_data += "<a id='dies_data' href='?action=CHECKSHEET_PREVENTIVE&id=0&step=1&group_id=" + data_dies[x].group_id + "&model_id=" + data_dies[x].model_id + "&dies_id=" + data_dies[x].dies_id + "' class='card-body border border-secondary rounded p-1  " + data_dies[x].bg_color + "'>";
                        append_data += "<h4 class='card-title text-center mb-0 text-dark font-weight-bold'>" + data_dies[x].dies_no + "</h4>"
                        append_data += "<p class='m-0 text-center text-dark small text-nowrap font-weight-bold' >Stroke</p>"
                        append_data += "<p class='m-0 text-dark small text-nowrap font-weight-bold '>" + "Prev " + "<span>: " + data_dies[x].stkrun + "</span></p>";
                        append_data += "<p class='m-0 text-dark small text-nowrap font-weight-bold '>" + "Act " + "<span>: " + data_dies[x].stk6k + "</span></p>";
                        append_data += "</a>";
                        append_data += "</div>";
                        append_data += "</div>";
                      }
                      x++;
                    });
                    append_data += "</div></div></div></div>";
                  }
                  j++;
                });
              }
              append_data += "</div></div></div></div>";
              i++;
            });
            $("#dashboard").html(append_data);
          }
          // console.log(append_data);
        }
      );
    }

    var elem = document.getElementById("fs");

    function fullscreen() {
      document.body.style.zoom = '77%';
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

    var div = document.querySelector('#title');
    var logo = document.querySelector('#logo');
    var closefs = document.querySelector('#close-fs');
    div.style.display = 'none';
    logo.style.display = 'none';
    closefs.style.display = 'none';

    // $("#fs-btn").click(fullscreen);
    $("#fs-btn").click(function() {
      fullscreen();
      var div = document.querySelector('#title');
      var logo = document.querySelector('#logo');
      var closefs = document.querySelector('#close-fs');
      div.style.display = '';
      logo.style.display = '';
      closefs.style.display = '';
    });

    function closeFullscreen() {
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

    $(document).bind('webkitfullscreenchange mozfullscreenchange fullscreenchange', function(e) {
      var state = document.fullScreen || document.mozFullScreen || document.webkitIsFullScreen;
      var event = state ? 'FullscreenOn' : 'FullscreenOff';

      // Now do something interesting
      if (event == "FullscreenOff") {
        document.body.style.zoom = '100%';
        var div = document.querySelector('#title');
        var logo = document.querySelector('#logo');
        var closefs = document.querySelector('#close-fs');
        div.style.display = 'none';
        logo.style.display = 'none';
        closefs.style.display = 'none';
        closeFullscreen();
      }

    });
  </script>
</body>

</html>