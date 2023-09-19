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
          <div class="container-fluid">
            <ol class="breadcrumb mb-2 mt-2 bg-transparent">
              <li class="breadcrumb-item"><?php echo $template["group"]; ?></li>
              <li class="breadcrumb-item">
                <a href="?action=<?= $action ?>"><?php echo $template["menu"]; ?></a>
              </li>
              <li class="breadcrumb-item active">
                <?php echo $template["sub_menu"]; ?>
              </li>
            </ol>
            <?php 
             if(isset($_GET["success"])) {
               echo '<div class="alert alert-success" alert-dismissible fade show" role="alert">
                       '.$_GET["success"].'
                       <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                         <span aria-hidden="true">&times;</span>
                       </button>
                     </div>';
             }
             ?>
             <?php 
             if(isset($_GET["error"])) {
               echo '<div class="alert alert-danger" alert-dismissible fade show" role="alert">
                       '.$_GET["error"].'
                       <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                         <span aria-hidden="true">&times;</span>
                       </button>
                     </div>';
             }
            ?>
            <form method="post" action="?action=<?= $action; ?>" id="myform">
              <input type="hidden" name="save_type" value="<?= $save_type; ?>">
              <input type="hidden" name="date1" value="<?= (!empty($data_hdr)) ? $data_hdr["date1"] : $filter_date1; ?>">
              <input type="hidden" name="shift" value="<?= (!empty($data_hdr)) ? $data_hdr["shift"] : $filter_shift; ?>">
              <input type="hidden" name="type1" value="<?= (!empty($data_hdr)) ? $data_hdr["type1"] : $filter_type1; ?>">
              
              <div class="row">
                <div class="col-12">
                  <div class="accordion" id="accordion1">
                    <?php
                    $i = 0;
                    foreach ($dev_list as $dev) {
                      $show = "";
                      if ($i == 0) {
                        $show = "show";
                      }
                      echo '<div class="card">
                          <div class="card-header bg-white" id="heading_' . $dev["mdev_id"] . '">
                            <h2 class="mb-0">
                              <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapse_' . $dev["mdev_id"] . '" aria-expanded="true" aria-controls="collapse_' . $dev["mdev_id"] . '">
                                ' . $dev["sort1"] . ". " . $dev["name1"] . '
                              </button>
                            </h2>
                          </div>

                          <div id="collapse_' . $dev["mdev_id"] . '" class="collapse ' . $show . '" aria-labelledby="heading_' . $dev["mdev_id"] . '" data-parent="#accordion1">
                            <div class="card-body">';
                      echo "<table class='table table-sm table-striped'>";
                      /* echo "<tr>"
                        . "<td class='align-middle text-nowrap'>No. Item</td>"
                        . "<td class='align-middle text-nowrap'>Process Name</td>"
                        . "<td class='align-middle text-nowrap'>Check item</td>"
                        . "<td class='align-middle text-nowrap text-center'>Standard</td>"
                        . "<td class='align-middle text-nowrap text-center'>Actual</td>"
                        . "<td class='align-middle text-nowrap text-center'>Judgement</td>"
                        . "</tr>"; */
                      foreach ($itm_list as $itm) {
                        if ($itm["mdev_id"] == $dev["mdev_id"]) {
                          $value_act = 0;
                          $value_jud = null;
                          if(!empty($data_itm)) {
                            foreach ($data_itm as $ditm) {
                              if ($ditm["itm_id"] == $itm["itm_id"]) {
                                $value_act = $ditm["actual"];
                                $value_jud = $ditm["result1"];
                                break;
                              }
                            }
                          }
                            
                          echo "<tr>"
                          . "<td class='align-middle'>" . $itm["itm_id"] . "</td>"
                          . "<td class='align-middle'>" . $itm["grp_no"] . ". " . $itm["grp_name"] . "</td>"
                          . "<td class='align-middle'>" . $itm["name1"] . "</td>"
                          . "<td class='align-middle text-center'><span id='std_min_" . $itm["itm_id"] . "'>" . $itm["std_min"] . "</span></td>"
                          . "<td class='align-middle text-center'><span id='std_max_" . $itm["itm_id"] . "'>" . $itm["std_max"] . "</span></td>"
                          . "<td class='align-middle text-center'>" . $itm["std_uom"] . "</td>"
                          . "<td class='align-middle text-center'><input onchange='judgement(\"" . $itm["itm_id"] . "\")' class='form-control form-control-sm text-center' type='number' id='actual_" . $itm["itm_id"] . "' name='actual[" . $itm["itm_id"] . "]' value='" . $value_act . "'></td>"
                          . "<td class='align-middle text-center'><input class='form-control form-control-sm text-center' type='text' id='result1_" . $itm["itm_id"] . "' name='result1[" . $itm["itm_id"] . "]' value='" . $value_jud . "' readonly></td>"
                          . "</tr>";
                        }
                      }
                      echo "</table>";
                      echo '</div>
                        </div>
                      </div>';
                      $i++;
                    }
                    ?>
                  </div>
                </div>
              </div>
              <div class="row mt-1">
                <div class="col-12">
                  <div class="card">
                    <div class="card-body">
                      <div class="row">
                        <div class="col-lg-6 col-sm-12">
                          <!-- filter placement -->

                        </div>
                        <div class="col-lg-6 col-sm-12">
                          <div class="d-flex justify-content-end">
                            <!-- button placement -->
                            <input type="hidden" name="save" value="save">
                            <button type="submit" name="bt-save" value="save" class="btn btn-primary"><span class="material-icons">save</span> Save</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>              
              </div>
            </form>
          </div>
        </main>
        <?php include 'common/t_footer.php'; ?>
      </div>
    </div>
    <?php include 'common/t_js.php'; ?>
    <script src="vendors/ega/js/scripts.js?time=<?php echo date("Ymdhis"); ?>" type="text/javascript"></script>
    <script>
      $("#myform").submit(function () {
        $(".btn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Please Wait...').attr("disabled","disabled");        
      });

      $(document).ready(function () {
        $(".date-picker").flatpickr({
          altInput: true,
          dateFormat: "Y-m-d",
          altFormat: "d M Y",
        });

        $(window).keydown(function (e) {
          if (e.which === 13) {
            e.preventDefault();
            return false;
          }
        });

        $('body').on('keydown', 'input, select, button', function (e) {
          if (e.which === 13) {
            var self = $(this), form = self.parents('form:eq(0)'), focusable, next;
            focusable = form.find('input,a,select,button,textarea').filter(':not([readonly])');
            next = focusable.eq(focusable.index(this) + 1);
            if (next.length) {
              next.focus();
            }
            return false;
          }
        });
      });

      $("#shift").on("change", function () {
        $("#myform").submit();
      });

      $("#date1").on("change", function () {
        $("#myform").submit();
      });

      function judgement(itm_id) {
        var std_max = parseFloat($("#std_max_" + itm_id).text());
        var std_min = parseFloat($("#std_min_" + itm_id).text());
        var actual = parseFloat($("#actual_" + itm_id).val());

        var result1 = "OK";
        if (actual < std_min || actual > std_max) {
          result1 = "NG";
        }
        $("#result1_" + itm_id).val(result1);
      }
    </script>
  </body>
</html>
