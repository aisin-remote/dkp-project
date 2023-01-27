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
            <li class="breadcrumb-item"><?php echo $template["group"]; ?></li>
            <li class="breadcrumb-item active"><?php echo $template["menu"]; ?></li>
          </ol>
          <div class="card mb-3">
            <div class="card-body">
              <h3 class="text-uli-blue">Dies Status</h3>
              <div class="row">
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
                                . "<a href='CHECKSHEET_PREVENTIVE?id=0&step=1&group_id=" . $dies["group_id"] . "&model_id=" . $dies["model_id"] . "&dies_id=" . $dies["dies_id"] . "' class='card-body border border-secondary rounded p-1  " . $dies["bg_color"] . "'>"
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
                            <td class="col-2 bg-ivory">White</td>
                            <td class="bg-ivory" style="width: 50px;">:</td>
                            <td class="bg-ivory">Dies stroke < 1,600</td>
                          </tr>
                          <tr>
                            <td class="col-2 bg-yellow">Yellow</td>
                            <td class="bg-yellow" style="width: 50px;">:</td>
                            <td class="bg-yellow">Dies stroke >= 1,600</td>
                          </tr>
                          <tr>
                            <td class="col-2 bg-danger">Red</td>
                            <td class="bg-danger" style="width: 50px;">:</td>
                            <td class="bg-danger">Dies stroke > 2,000</td>
                          </tr>
                          <tr>
                            <td class="col-2 bg-blink">Blue (Blinking)</td>
                            <td class="bg-blink" style="width: 50px;">:</td>
                            <td class="bg-blink">Dies under preventive maintenance</td>
                          </tr>
                          <tr>
                            <td class="col-2 bg-amber">Amber</td>
                            <td class="bg-amber" style="width: 50px;">:</td>
                            <td class="bg-amber">Dies under repair to vendor</td>
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
      </main>
      <?php include 'common/t_footer.php'; ?>
    </div>
  </div>
  <input type="hidden" id="usrid" value="<?php echo $_SESSION[LOGIN_SESSION]; ?>">
  <?php include 'common/t_js.php'; ?>
  <script src="vendors/ega/js/scripts.js?time=<?php echo date("Ymdhis"); ?>" type="text/javascript"></script>
  <script>
    setInterval(updateDashboard, 1000);

    $(document).ready(function() {

    });

    function updateDashboard() {
      /*$.ajax({
        type: "POST",
        url: "get_pos",
        crossDomain: true,
        cache: false,
        data : {},
        success: function (data) {
          var obj = $.parseJSON(data);
          
          if (obj.length > 0) {
            //error
            var dashboard1 = "";
            $.each(obj,function(index, value){                
              dashboard1 += "<tr><td class='text-center'>"+value.id_rfid+"</td><td class='text-center'>"+value.id_pos+"</td></tr>";
            });
            $("#dashboard1").html(dashboard1);
          } else {
            $("#dashboard1").html("");
          }
        }
      });*/
    }
  </script>
</body>

</html>