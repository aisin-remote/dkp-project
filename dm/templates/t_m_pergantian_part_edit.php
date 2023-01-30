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
          <ol class="breadcrumb mb-2 mt-4">
            <li class="breadcrumb-item"><?php echo $template["group"]; ?></li>
            <li class="breadcrumb-item"><?php echo $template["menu"]; ?></li>
            <li class="breadcrumb-item active"><?php echo $template["submenu"]; ?></li>
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
          <form id="my_form" method="post" action="?action=<?php echo $action; ?>&id=<?php echo $id; ?>">
            <input type="hidden" name="save" value="save">
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
                          <button type="submit" type="button" name="btn_save" class="btn btn-dark-blue btn-sm px-5 mx-2" id="btn_save">Save</button>
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

                    <div class="form-group row">
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Dies Group</label>
                      <div class="col-lg-2 col-md-5 col-sm-12">
                        <select name="group_id" id="group_id" class="form-control select2">
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
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Model</label>
                      <div class="col-lg-3 col-md-5 col-sm-12">
                        <select name="model_id" id="model_id" class="form-control select2">
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
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Dies No. #</label>
                      <div class="col-lg-3 col-md-5 col-sm-12">
                        <select name="dies_id" id="dies_id" class="form-control select2" required>
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
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Part Change Date</label>
                      <div class="col-lg-3 col-md-5 col-sm-12">
                        <input type="text" name="pcdat" class="form-control datepicker" maxlength="100" value="<?php echo $data["data"]["pcdat"]; ?>" required>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Remarks</label>
                      <div class="col-lg-5 col-md-5 col-sm-12">
                        <input type="text" name="desc1" class="form-control" maxlength="100" value="<?php echo $data["data"]["desc1"]; ?>">
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
                    <div class="table-responsive">
                      <table class="table">
                        <tr class="table-secondary">
                          <td>1.3</td>
                          <td colspan="3">Penggantian Part</td>
                        </tr>
                        <tr class="table-secondary">
                          <td>1.3.1</td>
                          <td colspan="2">Fix</td>
                          <td>Remarks</td>
                        </tr>
                        <?php
                        if (!empty($part_list)) {
                          foreach ($part_list as $row) {
                            if ($row["part_grp"] == "F") {
                        ?>
                              <tr>
                                <td class="align-middle"><?php echo $row["part_id"]; ?></td>
                                <td class="align-middle"><?php echo $row["name1"]; ?></td>
                                <td class="align-middle"><input type="<?php echo $row["input_type"]; ?>" class="<?php if ($row["input_type"] == "checkbox") {
                                                                                                                  echo "";
                                                                                                                } else {
                                                                                                                  echo "form-control";
                                                                                                                } ?>" name="item[<?php echo $row["part_id"]; ?>]" <?php if ($row["input_type"] == "checkbox") {
                                                                                                                                                                    if (!empty($data["item"])) {
                                                                                                                                                                      foreach ($data["item"] as $itm) {
                                                                                                                                                                        if ($itm["part_id"] == $row["part_id"]) {
                                                                                                                                                                          echo "checked";
                                                                                                                                                                          break;
                                                                                                                                                                        }
                                                                                                                                                                      }
                                                                                                                                                                    }
                                                                                                                                                                  } else {
                                                                                                                                                                    if (!empty($data["item"])) {
                                                                                                                                                                      foreach ($data["item"] as $itm) {
                                                                                                                                                                        if ($itm["part_id"] == $row["part_id"]) {
                                                                                                                                                                          echo "value='" . $itm["part_text"] . "'";
                                                                                                                                                                          break;
                                                                                                                                                                        }
                                                                                                                                                                      }
                                                                                                                                                                    }
                                                                                                                                                                  } ?>></td>
                                <td class="align-middle"><?php if ($row["part_id"] == "1.3.1.1") { ?> <button type="button" class="btn btn-sm btn-info" onclick="addCorePinDetail('1.3.1.1')">Add Detail</button> <?php } else { ?><input type="text" name="remarks[<?= $row["part_id"] ?>]" class="form-control" value="<?php if (!empty($data["item"])) {
                                                                                                                                                                                                                                                                                                                            foreach ($data["item"] as $itm) {
                                                                                                                                                                                                                                                                                                                              if ($itm["part_id"] == $row["part_id"]) {
                                                                                                                                                                                                                                                                                                                                echo $itm["remarks"];
                                                                                                                                                                                                                                                                                                                                break;
                                                                                                                                                                                                                                                                                                                              }
                                                                                                                                                                                                                                                                                                                            }
                                                                                                                                                                                                                                                                                                                          } ?>"><?php } ?></td>
                              </tr>
                        <?php
                              if ($row["part_id"] == "1.3.1.1") {
                                echo "<tr><td></td><td colspan='3' id='core_pin_dtl_f'>";
                                echo "<div class='input-group input-group-sm mb-1' >
                                      <input readonly type='text' class='form-control' value='Core Pin No.'>
                                      <input readonly type='text' class='form-control' value='Posisi'>
                                      <input readonly type='text' class='form-control' value='Alasan Ganti'>
                                      <div class='input-group-append'><button disabled type='button' class='btn btn-outline-secondary'><i class='material-icons'>delete</i></button></div>
                                    </div>";
                                if (!empty($data_core_pin)) {
                                  foreach ($data_core_pin as $xrow) {
                                    if ($xrow["part_id"] == $row["part_id"]) {
                                      echo "<div class='input-group input-group-sm mb-1' id='core_pin_dtl_f_" . $xrow["seqno"] . "'>
                                          <input readonly type='text' class='form-control bg-light' name='text_f[" . $xrow["seqno"] . "][1]' value='" . $xrow["text1"] . "'>
                                          <input readonly type='text' class='form-control bg-light' name='text_f[" . $xrow["seqno"] . "][2]' value='" . $xrow["text2"] . "'>
                                          <input readonly type='text' class='form-control bg-light' name='text_f[" . $xrow["seqno"] . "][3]' value='" . $xrow["text3"] . "'>
                                          <div class='input-group-append'>
                                           <button type='button' class='btn btn-outline-danger' onclick=\"delDetailCorePin('f','" . $xrow["seqno"] . "')\"><i class='material-icons'>delete</i></button>
                                          </div>
                                         </div>";
                                    }
                                  }
                                }
                                echo "</td></tr>";
                              }
                            }
                          }
                        }
                        ?>
                        <tr class="table-secondary">
                          <td>1.3.2</td>
                          <td colspan="2">Move</td>
                          <td>Remarks</td>
                        </tr>
                        <?php
                        if (!empty($part_list)) {
                          foreach ($part_list as $row) {
                            if ($row["part_grp"] == "M") {
                        ?>
                              <tr>
                                <td class="align-middle"><?php echo $row["part_id"]; ?></td>
                                <td class="align-middle"><?php echo $row["name1"]; ?></td>
                                <td class="align-middle"><input type="<?php echo $row["input_type"]; ?>" class="<?php if ($row["input_type"] == "checkbox") {
                                                                                                                  echo "";
                                                                                                                } else {
                                                                                                                  echo "form-control";
                                                                                                                } ?>" name="item[<?php echo $row["part_id"]; ?>]" <?php if ($row["input_type"] == "checkbox") {
                                                                                                                                                                    if (!empty($data["item"])) {
                                                                                                                                                                      foreach ($data["item"] as $itm) {
                                                                                                                                                                        if ($itm["part_id"] == $row["part_id"]) {
                                                                                                                                                                          echo "checked";
                                                                                                                                                                          break;
                                                                                                                                                                        }
                                                                                                                                                                      }
                                                                                                                                                                    }
                                                                                                                                                                  } else {
                                                                                                                                                                    if (!empty($data["item"])) {
                                                                                                                                                                      foreach ($data["item"] as $itm) {
                                                                                                                                                                        if ($itm["part_id"] == $row["part_id"]) {
                                                                                                                                                                          echo "value='" . $itm["part_text"] . "'";
                                                                                                                                                                          break;
                                                                                                                                                                        }
                                                                                                                                                                      }
                                                                                                                                                                    }
                                                                                                                                                                  } ?>></td>
                                <td class="align-middle"><?php if ($row["part_id"] == "1.3.2.1") { ?> <button type="button" class="btn btn-sm btn-info" onclick="addCorePinDetail('1.3.2.1')">Add Detail</button> <?php } else { ?><input type="text" name="remarks[<?= $row["part_id"] ?>]" class="form-control" value="<?php if (!empty($data["item"])) {
                                                                                                                                                                                                                                                                                                                            foreach ($data["item"] as $itm) {
                                                                                                                                                                                                                                                                                                                              if ($itm["part_id"] == $row["part_id"]) {
                                                                                                                                                                                                                                                                                                                                echo $itm["remarks"];
                                                                                                                                                                                                                                                                                                                                break;
                                                                                                                                                                                                                                                                                                                              }
                                                                                                                                                                                                                                                                                                                            }
                                                                                                                                                                                                                                                                                                                          } ?>"><?php } ?></td>
                              </tr>
                        <?php
                              if ($row["part_id"] == "1.3.2.1") {
                                echo "<tr><td></td><td colspan='3' id='core_pin_dtl_m'>";
                                echo "<div class='input-group input-group-sm mb-1' >
                                      <input readonly type='text' class='form-control' value='Core Pin No.'>
                                      <input readonly type='text' class='form-control' value='Posisi'>
                                      <input readonly type='text' class='form-control' value='Alasan Ganti'>
                                      <div class='input-group-append'><button disabled type='button' class='btn btn-outline-secondary'><i class='material-icons'>delete</i></button></div>
                                    </div>";
                                if (!empty($data_core_pin)) {
                                  foreach ($data_core_pin as $xrow) {
                                    if ($xrow["part_id"] == $row["part_id"]) {
                                      echo "<div class='input-group input-group-sm mb-1' id='core_pin_dtl_m_" . $xrow["seqno"] . "'>
                                          <input readonly type='text' class='form-control bg-light' name='text_m[" . $xrow["seqno"] . "][1]' value='" . $xrow["text1"] . "'>
                                          <input readonly type='text' class='form-control bg-light' name='text_m[" . $xrow["seqno"] . "][2]' value='" . $xrow["text2"] . "'>
                                          <input readonly type='text' class='form-control bg-light' name='text_m[" . $xrow["seqno"] . "][3]' value='" . $xrow["text3"] . "'>
                                          <div class='input-group-append'>
                                           <button type='button' class='btn btn-outline-danger' onclick=\"delDetailCorePin('m','" . $xrow["seqno"] . "')\"><i class='material-icons'>delete</i></button>
                                          </div>
                                         </div>";
                                    }
                                  }
                                }
                                echo "</td></tr>";
                              }
                            }
                          }
                        }
                        ?>
                      </table>
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
  <!-- Modal Section -->
  <div class="modal fade" id="myModal1" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="myModal1_label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="myModal1_label"><span class="material-icons">hub</span> Detail Core Pin</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="x_part_id">
          <div class="row my-2">
            <div class="col-4"><label class="col-form-label">Core Pin No.</label></div>
            <div class="col"><input type="text" id="text1" class="form-control" value=""></div>
          </div>
          <div class="row my-2">
            <div class="col-4"><label class="col-form-label">Posisi</label></div>
            <div class="col"><input type="text" id="text2" class="form-control" value=""></div>
          </div>
          <div class="row my-2">
            <div class="col-4"><label class="col-form-label">Alasan Ganti</label></div>
            <div class="col"><input type="text" id="text3" class="form-control" value=""></div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-dark-blue-outlined" name="filter" value="filter" onclick="appendCorePinDetail()">Submit</button>
        </div>
      </div>
    </div>
  </div>
  <input type="hidden" id="counter_f" value="<?= $count_f ?>">
  <input type="hidden" id="counter_m" value="<?= $count_m ?>">
  <input type="hidden" id="trans_type" value="<?= $template["submenu"] ?>">
  <?php include 'common/t_js.php'; ?>
  <script src="vendors/ega/js/scripts.js?time=<?php echo date("Ymdhis"); ?>" type="text/javascript"></script>
  <script>
    $(document).ready(function() {
      $(".datepicker").flatpickr({
        altInput: true,
        altFormat: "d-m-Y",
        dateFormat: "Y-m-d"
      });

      if ($("#trans_type").val() == "Edit") {
        $("#group_id").attr("disabled", "disabled");
        $("#model_id").attr("disabled", "disabled");
        $("#dies_id").attr("disabled", "disabled");
      }
    });

    $("#my_form").submit(function(event) {
      // event.preventDefault();
      $("#group_id").removeAttr("disabled");
      $("#model_id").removeAttr("disabled");
      $("#dies_id").removeAttr("disabled");
      $("#btn_save").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Please Wait...');
      $("#btn_save").attr("disabled", "disabled");
    });

    $("#group_id").change(function() {
      getDiesModel($("#group_id").val());
    });

    function getDiesModel(group_id) {
      $("#model_id").empty();
      var first_model = "";
      $.getJSON("?action=api_get_dies_model", {
        group: group_id
      }, function(data) {
        var items = "";
        //$("#model_id").empty();
        var $i = 0
        $.each(data, function(key, val) {
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

    $("#model_id").change(function() {
      getDiesList($("#group_id").val(), $("#model_id").val());
    });

    function getDiesList(group_id, model_id) {
      $("#dies_id").empty();
      $.getJSON("?action=api_get_dies_list", {
        group_id: group_id,
        model: model_id
      }, function(data) {
        var items = "";
        //$("#model_id").empty();

        $.each(data, function(key, val) {
          console.log(val.model_id);
          items += "<option value='" + val.dies_id + "'>" + val.dies_no + "</option>";
        });

        $("#dies_id").html(items);
      });
    }

    function addCorePinDetail(part_id) {
      $("#x_part_id").val(part_id);
      $('#myModal1').modal("show");
    }

    function appendCorePinDetail() {
      var counter = 0;
      var part_id = $("#x_part_id").val();
      var type = "f";
      if (part_id == "1.3.1.1") {
        type = "f";
        counter = parseInt($("#counter_f").val()) + 1;
      } else {
        type = 'm';
        counter = parseInt($("#counter_m").val()) + 1;
      }

      var text1 = $("#text1").val();
      var text2 = $("#text2").val();
      var text3 = $("#text3").val();
      var append_data = "<div class='input-group input-group-sm mb-1' id='core_pin_dtl_" + type + "_" + counter + "'>\n\
                          <input readonly type='text' class='form-control bg-light' name='text_" + type + "[" + counter + "][1]' value='" + text1 + "'>\n\
                          <input readonly type='text' class='form-control bg-light' name='text_" + type + "[" + counter + "][2]' value='" + text2 + "'>\n\
                          <input readonly type='text' class='form-control bg-light' name='text_" + type + "[" + counter + "][3]' value='" + text3 + "'>\n\
                          <div class='input-group-append'>\n\
                           <button type='button' class='btn btn-outline-danger' onclick=\"delDetailCorePin('" + type + "','" + counter + "')\"><i class='material-icons'>delete</i></button>\n\
                          </div>\n\
                         </div>";
      $("#core_pin_dtl_" + type).append(append_data);
      $("#counter_" + type).val(counter);
      $("#text1").val("");
      $("#text2").val("");
      $("#text3").val("");
      $('#myModal1').modal("hide");
    }

    function delDetailCorePin(type, counter) {
      $("#core_pin_dtl_" + type + "_" + counter).remove();
    }
  </script>
</body>

</html>