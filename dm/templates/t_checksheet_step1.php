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
        <div class="container-fluid">
          <ol class="breadcrumb mb-4 mt-4">
            <li class="breadcrumb-item">
              <?php echo $template["group"]; ?>
            </li>
            <li class="breadcrumb-item">
              <?php echo $template["menu"]; ?>
            </li>
            <li class="breadcrumb-item active">
              <?php echo $template["submenu"]; ?>
            </li>
          </ol>
          <?php
          if (isset($_GET["error"])) {
            echo '<div class="alert alert-danger alert-dismissible" role="alert">
                      Error : ' . $_GET["error"] . '
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>';
          }
          ?>
          <form method="post" id="my-form" action="?action=<?php echo $action; ?>&id=<?php echo $id; ?>&step=1">

            <div class="row">
              <div class="col-12">
                <div class="card mt-2">
                  <div class="card-body">
                    <!-- Edit Here -->

                    <input type="hidden" name="model_id" class="form-control" maxlength="100"
                      value="<?php echo $data["data"]["model_id"]; ?>">

                    <div class="form-group row">
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Preventive</label>
                      <div class="col-lg-3 col-md-5 col-sm-12">
                        <select name="pmtype" id="pmtype" class="form-control select2">
                          <option value="2K">Preventive Stroke 2000</option>
                          <option value="6K">Preventive Stroke 6000</option>
                        </select>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Dies Group</label>
                      <div class="col-lg-1 col-md-5 col-sm-12">
                        <select name="group_id" id="group_id" class="form-control select2">
                          <?php
                          foreach ($group_list as $group) {
                            ?>
                            <option value="<?php echo $group["pval1"]; ?>" <?php if ($group["pval1"] == $group_id) {
                                 echo "selected";
                               } ?>><?php echo $group["pval1"]; ?></option>
                            <?php
                          }
                          ?>
                        </select>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Model</label>
                      <div class="col-lg-2 col-md-5 col-sm-12">
                        <select name="model_id" id="model_id" class="form-control select2">
                          <?php
                          foreach ($model_list as $model) {
                            ?>
                            <option value="<?php echo $model["model_id"]; ?>" <?php if ($model["model_id"] == $model_id) {
                                 echo "selected";
                               } ?>><?php echo $model["model_id"]; ?></option>
                            <?php
                          }
                          ?>
                        </select>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Dies No.</label>
                      <div class="col-lg-2 col-md-5 col-sm-12">
                        <select name="dies_id" id="dies_id" class="form-control select2">
                          <?php
                          foreach ($diesid_list as $dies) {
                            ?>
                            <option value="<?php echo $dies["dies_id"]; ?>" <?php if ($dies["dies_id"] == $dies_id) {
                                 echo "selected";
                               } ?>><?php echo $dies["dies_no"] . " - " . $dies["name1"]; ?></option>
                            <?php
                          }
                          ?>
                        </select>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Maintenance Date</label>
                      <div class="col-lg-2 col-md-5 col-sm-12">
                        <input type="text" name="pmtdt" class="form-control datepicker"
                          value="<?php echo date("Y-m-d"); ?>" required>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Zone</label>
                      <div class="col-lg-2 col-md-5 col-sm-12">
                        <select name="zona_id" id="zona_id" class="form-control select2">
                          <?php
                          foreach ($list_zona as $zona) {
                            if ($zona["zona_type"] == "M") {

                              ?>
                              <option value="<?php echo $zona["zona_id"]; ?>"><?php echo $zona["desc"]; ?></option>
                              <?php
                            }
                          }
                          ?>
                        </select>
                      </div>
                    </div>

                    <div class="form-group row">
                      <div class="col-lg-2 col-md-3 col-sm-12 d-sm-none d-md-block"></div>
                      <div class="col-lg-5 col-md-5 col-sm-12 mx-3">
                        <div class="row">
                          <input type="hidden" name="save" value="true">
                          <button type="submit" name="btn_save" id="btn_save" value="save"
                            class="btn btn-dark-blue"><span class="material-icons">save</span> Save</button>
                          <div class="dropdown mx-2">
                            <button class="btn btn-dark-blue dropdown-toggle" type="button" data-toggle="dropdown"
                              aria-expanded="false">
                              Dies History
                            </button>
                            <div class="dropdown-menu">
                              <a class="dropdown-item" href="?action=r_checksheet_preventive&dies_id=">Checksheet
                                Preventive</a>
                              <a class="dropdown-item" href="?action=r_pergantian_part&dies_id=">Pergantian
                                Part</a>
                              <a class="dropdown-item" href="?action=r_order_repair_and_improvement&dies_id=">Order
                                Repair
                                and Improvement</a>
                              <a class="dropdown-item" href="?action=r_stroke_total_dies&dies_id=">Stroke Total
                                Dies</a>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                  </div>
                </div>
              </div>
            </div>
            <div class="row">

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
    $(document).ready(function () {
      editLink($('#dies_id').val());
    });
    
    $("#my-form").submit(function (event) {
      // event.preventDefault();
      $("#group_id, #model_id").removeAttr("disabled");
      $("#btn_save").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Please Wait...');
      $("#btn_save").attr("disabled", "disabled");
    });

    $(".datepicker").flatpickr({
      altInput: true,
      altFormat: "d-m-Y",
      dateFormat: "Y-m-d",
      allowInput: true
    });

    $("#group_id").change(function () {
      getDiesModel($("#group_id").val());
    });

    function getDiesModel(group_id) {
      $("#model_id").empty();
      var first_model = "";
      $.getJSON("?action=api_get_dies_model", {
        group: group_id
      }, function (data) {
        var items = "";
        //$("#model_id").empty();
        var $i = 0
        $.each(data, function (key, val) {
          if ($i == 0) {
            first_model = val.model_id;
            if (first_model.length > 0) {
              getDiesList($("#group_id").val(), first_model);
            }
          }
          console.log(val.model_id);
          items += "<option value='" + val.model_id + "'>" + val.model_id + "</option>";
          $i++;
        });

        $("#model_id").html(items);
      });
    }

    $("#model_id").change(function () {
      getDiesList($("#group_id").val(), $("#model_id").val());
    });

    function getDiesList(group_id, model_id) {
      $("#dies_id").empty();
      $.getJSON("?action=api_get_dies_list", {
        model: model_id,
        group_id: group_id
      }, function (data) {
        var items = "";
        //$("#model_id").empty();

        $.each(data, function (key, val) {
          console.log(val.model_id);
          items += "<option value='" + val.dies_id + "'>" + val.dies_no + " - " + val.name1 + "</option>";
        });

        $("#dies_id").html(items);
      });
    }

    
    $('#dies_id').change(function () {
      var selectedValue = $(this).value;
      editLink(selectedValue);
    });
    function editLink(dies_id) {
      $('.dropdown-menu a').each(function () {
        var href = $(this).attr('href');
        href = href.replace(/dies_id=[^&]*/, 'dies_id=' + dies_id);
        $(this).attr('href', href);
      });
    }
  </script>
</body>

</html>