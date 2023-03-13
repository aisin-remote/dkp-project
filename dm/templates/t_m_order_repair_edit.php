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
  <style>
    .cropit-preview {
      max-width: 250px;
    }
  </style>
</head>

<body>
  <?php include "common/t_nav_top.php"; ?>
  <div id="layoutSidenav">
    <?php include "common/t_nav_left.php"; ?>
    <div id="layoutSidenav_content">
      <main>
        <div class="container-fluid">
          <ol class="breadcrumb mb-2 mt-4">
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
            echo '<div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
                      Error : ' . $_GET["error"] . '
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>';
          }
          ?>

          <?php
          if (isset($_GET["success"])) {
            echo '<div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
                      Success : ' . $_GET["success"] . '
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>';
          }
          ?>
          <form method="post" id="my-form" action="?action=<?php echo $action; ?>&id=<?php echo $id; ?>"
            enctype="multipart/form-data">

            <div class="row">
              <div class="col-12">
                <div class="card" style="background-color: #F0F0F0;">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-lg-6 col-sm-12">
                        <!-- filter placement -->

                      </div>
                      <div class="col-lg-6 col-sm-12">
                        <div class="d-flex justify-content-end">
                          <!-- button placement -->
                          <button type="submit" type="button" name="save" class="btn btn-dark-blue btn-sm px-5 mx-2"
                            id="btn-save">Save</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="card mt-2">
                  <div class="card-body">
                    <!-- Edit Here -->
                    <div class="row">
                      <div class="col-md-6 col-sm-12">
                        <div class="form-group row">
                          <label class="col-form-label col-lg-3 col-md-3 col-sm-12">Dies Group</label>
                          <div class="col-lg-3 col-md-5 col-sm-12">
                            <select name="group_id" id="group_id" class="form-control select2" <?php echo $data["data"]["group_id"]; ?> <?php if (!empty($data["data"]["group_id"])) {
                                  echo "disabled";
                                } ?>>
                              <?php
                              foreach ($group_list as $group) {
                                ?>
                                <option value="<?php echo $group["pval1"]; ?>" <?php if ($data["data"]["group_id"] == $group["pval1"]) {
                                     echo "selected";
                                   } ?>><?php echo $group["pval1"]; ?></option>
                                <?php
                              }
                              ?>
                            </select>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-form-label col-lg-3 col-md-3 col-sm-12">Model</label>
                          <div class="col-lg-3 col-md-5 col-sm-12">
                            <select name="model_id" id="model_id" class="form-control select2" <?php echo $data["data"]["model_id"]; ?> <?php if (!empty($data["data"]["model_id"])) {
                                  echo "disabled";
                                } ?>>
                              <?php
                              foreach ($model_list as $model) {
                                ?>
                                <option value="<?php echo $model["model_id"]; ?>" <?php if ($data["data"]["model_id"] == $model["model_id"]) {
                                     echo "selected";
                                   } ?>><?php echo $model["model_id"]; ?></option>
                                <?php
                              }
                              ?>
                            </select>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-form-label col-lg-3 col-md-3 col-sm-12">Dies No. #</label>
                          <div class="col-lg-3 col-md-5 col-sm-12">
                            <select name="dies_id" id="dies_id" class="form-control select2" required <?php echo $data["data"]["dies_id"]; ?> <?php if (!empty($data["data"]["dies_id"])) {
                                  echo "disabled";
                                } ?>>
                              <?php
                              foreach ($dies_list as $diesid) {
                                ?>
                                <option value="<?php echo $diesid["dies_id"]; ?>" <?php if ($data["data"]["dies_id"] == $diesid["dies_id"]) {
                                     echo "selected";
                                   } ?>><?php echo $diesid["dies_no"]; ?></option>
                                <?php
                              }
                              ?>
                            </select>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-form-label col-lg-3 col-md-3 col-sm-12">Order Date</label>
                          <div class="col-lg-4 col-md-5 col-sm-12">
                            <input type="text" name="ori_dt" class="form-control datepicker" maxlength="100"
                              value="<?php echo $data["data"]["ori_dt"]; ?>" required>
                          </div>
                        </div>

                        <div class="form-group row">
                          <label class="col-form-label col-lg-3 col-md-3 col-sm-12">Remarks</label>
                          <div class="col-lg-5 col-md-5 col-sm-12">
                            <input type="text" name="remarks" class="form-control" maxlength="100"
                              value="<?php echo $data["data"]["remarks"]; ?>">
                          </div>
                        </div>

                        <div class="form-group row">
                          <label class="col-form-label col-lg-3 col-md-3 col-sm-12">Order Type</label>
                          <div class="col-lg-9 col-md-9 col-sm-12">
                            <select name="ori_typ" id="ori_typ" class="form-control select2">
                              <?php
                              foreach ($type_list as $tl) {
                                ?>
                                <option value="<?php echo $tl["ori_typ"]; ?>" <?php if ($data["data"]["ori_typ"] == $tl["ori_typ"]) {
                                     echo "selected";
                                   } ?>><?php echo $tl["name1"]; ?></option>
                                <?php
                              }
                              ?>
                            </select>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-form-label col-lg-3 col-md-3 col-sm-12">Zona Maintenance</label>
                          <div class="col-lg-5 col-md-5 col-sm-12">
                            <select name="zona1" id="zona1" class="form-control select2">
                              <option value="" selected>Pilih Zona Maintenance</option>
                              <?php
                              foreach ($list_zona as $zona) {
                                if ($zona["zona_type"] == "M") {
                                  $selected = "";
                                  if ($zona["zona_id"] == $data["data"]["zona1"]) {
                                    $selected = "selected";
                                  }
                                  ?>
                                  <option value="<?php echo $zona["zona_id"]; ?>" <?= $selected ?>><?php echo $zona["desc"]; ?></option>
                                  <?php
                                }
                              }
                              ?>
                            </select>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-form-label col-lg-3 col-md-3 col-sm-12">Zona Parkir</label>
                          <div class="col-lg-5 col-md-5 col-sm-12">
                            <select name="zona2" id="zona2" class="form-control select2" <?php if($data["data"]["stats"] == '0' || empty($data["data"]["stats"])) {echo "disabled";} ?>>
                              <option value="" selected>Pilih Zona Parkir</option>
                              <?php
                              foreach ($list_zona as $zona) {
                                if ($zona["zona_type"] == "P") {
                                  $selected = "";
                                  if ($zona["zona_id"] == $data["data"]["zona2"]) {
                                    $selected = "selected";
                                  }
                                  ?>
                                  <option value="<?php echo $zona["zona_id"]; ?>" <?= $selected ?>><?php echo $zona["desc"]; ?></option>
                                  <?php
                                }
                              }
                              ?>
                            </select>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-form-label col-lg-3 col-md-3 col-sm-12">Status</label>
                          <div class="col-lg-5 col-md-5 col-sm-12">
                            <input class="" name="status" id="status" onchange="cekStats()" <?= (($data["data"]["stats"] == "1")) ? "checked" : ''; ?> type="checkbox" data-toggle="toggle" data-on="Completed"
                              data-off="On Progress" data-onstyle="success" data-offstyle="danger" data-size="mini"
                              data-width="120">
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6 col-sm-12">
                        <div>
                          <h6>Order Repair</h6>
                          <img id="output" class="cropit-preview" src="<?php if (!empty($data["data"]["ori_doc"])) {
                            echo "data:image/jpeg;base64," . $data["data"]["ori_doc"];
                          } ?>">
                          <div class="custom-file mt-2">
                            <input type="file" name="ori_doc" accept="image/*" onchange="loadFile(event)">
                            <input type="hidden" name="ori_docx" value="<?php echo $data["data"]["ori_doc"]; ?>">
                          </div>
                        </div>
                        <br />
                        <div>
                          <h6>A3 Document</h6>
                          <img id="output2" class="cropit-preview" src="<?php if (!empty($data["data"]["ori_a3"])) {
                            echo "data:image/jpeg;base64," . $data["data"]["ori_a3"];
                          } ?>">
                          <div class="custom-file mt-2">
                            <input type="file" name="ori_a3" accept="image/*" onchange="loadFile2(event)" <?php if (empty($data["data"]["ori_a3"])) {
                              echo "required";
                            } ?>>
                            <input type="hidden" name="ori_a3x" value="<?php echo $data["data"]["ori_a3"]; ?>">
                          </div>
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
    $(document).ready(function () {
      $(".datepicker").flatpickr({
        altInput: true,
        altFormat: "d-m-Y",
        dateFormat: "Y-m-d"
      });
    });

    var loadFile = function (event) {
      var reader = new FileReader();
      reader.onload = function () {
        var output = document.getElementById('output');
        output.src = reader.result;
      };
      reader.readAsDataURL(event.target.files[0]);
    };

    var loadFile2 = function (event) {
      var reader = new FileReader();
      reader.onload = function () {
        var output2 = document.getElementById('output2');
        output2.src = reader.result;
      };
      reader.readAsDataURL(event.target.files[0]);
    };

    function cekStats() {
      if ($("#status").is(":checked")) {
        console.log("checked")
        $("#zona2").attr("required", "required")
        $("#zona2").removeAttr("disabled")
      } else {
        console.log("unchecked")
        $("#zona2").attr("disabled", "disabled")
        $("#zona2").removeAttr("required")
      }
    }

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
              getDiesList(first_model);
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
      getDiesList($("#model_id").val(), $("#group_id").val());
    });

    function getDiesList(model_id, group_id) {
      $("#dies_id").empty();
      $.getJSON("?action=api_get_dies_list", {
        model: model_id,
        group_id: group_id
      }, function (data) {
        var items = "";
        //$("#model_id").empty();

        $.each(data, function (key, val) {
          console.log(val.model_id);
          items += "<option value='" + val.dies_id + "'>" + val.dies_no + "</option>";
        });

        $("#dies_id").html(items);
      });
    }

    $("#my-form").submit(function (event) {
      $("#group_id, #model_id, #dies_id").removeAttr("disabled");
    });
  </script>
</body>

</html>