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
          <ol class="breadcrumb mb-1 mt-1">
            <li class="breadcrumb-item">
              <?php echo $template["group"]; ?>
            </li>
            <li class="breadcrumb-item active">
              <?php echo $template["menu"]; ?>
            </li>
          </ol>
          <?php
          if (isset($_GET["error"])) {
            echo '<div class="alert alert-danger alert-dismissible fade show mb-1" role="alert">
                      ' . $_GET["error"] . '
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>';
          }

          if (isset($_GET["success"])) {
            echo '<div class="alert alert-success alert-dismissible fade show mb-1" role="alert">
                      ' . $_GET["success"] . '
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>';
          }
          ?>
          <form id="my_form" method="post" action="?action=<?php echo $action; ?>">
            <input type="hidden" name="save" value="post">
            <div class="row">
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
                          <button type="button" id="btn_scn2" name="btn_scn1" class="btn btn-info mr-2"
                            data-toggle="modal" data-target="#modal_02"><span
                              class="material-icons">qr_code_scanner</span> Scan Kanban Receiving</button>
                          <button type="button" id="btn_scn1" name="btn_scn1" class="btn btn-warning mr-2"
                            data-toggle="modal" data-target="#modal_01"><span
                              class="material-icons">qr_code_scanner</span> Scan Label Kuning</button>
                          <button type="submit" id="btn_save" name="btn_save" value="btn_save"
                            class="btn btn-primary"><span class="material-icons">send</span> Post</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="card mt-1">
                  <div class="card-body">
                    <!-- Edit Here -->

                    <div class="form-group row">
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Posting Date</label>
                      <div class="col-lg-3 col-md-6 col-sm-12">
                        <input type="date" name="budat" class="form-control" value="<?= date("Y-m-d") ?>">
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Plant</label>
                      <div class="col-lg-4 col-md-5 col-sm-12">
                        <select name="werks" class="form-control" id="werks" required="required">
                          <!--option value="">Please Select Plant</option-->
                          <?php
                          if (!empty($data["plants"])) {
                            foreach ($data["plants"] as $grp) {
                              echo "<option value='" . $grp["werks"] . "'>" . $grp["werks"] . " - " . $grp["name1"] . "</option>";
                            }
                          }
                          ?>
                        </select>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">PO. Number</label>
                      <div class="col-lg-4 col-md-6 col-sm-12">
                        <input type="text" name="ebeln" class="form-control" maxlength="20" value="">
                      </div>
                    </div>
                    <table class="table">
                      <thead>
                        <tr>
                          <th colspan="5" class="text-right"><button type="button" class="btn btn-info"
                              id="btn_add_item">Add Item</button></th>
                        </tr>
                        <tr>
                          <th>Material</th>
                          <th>Batch</th>
                          <th>Storage Location</th>
                          <th>Quantity</th>
                          <!-- <th>NG</th> -->
                          <th class="text-center">Del</th>
                        </tr>
                      </thead>
                      <tbody id="mseg">
                        <tr id="mseg_data_1">
                          <td>
                            <select name="matnr[]" class="form-control" required="required">
                              <option value="">Please Select Material</option>
                              <?php
                              if (!empty($data["materials"])) {
                                foreach ($data["materials"] as $grp) {
                                  echo "<option value='" . $grp["matnr"] . "'>" . $grp["matnr"] . " - " . $grp["name1"] . "</option>";
                                }
                              }
                              ?>
                            </select>
                          </td>
                          <td>
                            <input type="text" name="charg[]" class="form-control menge" maxlength="20" value=""
                              readonly>
                          </td>
                          <td style="width: 400px!important;">
                            <select name="lgort[]" class="form-control lgort" required="required">
                              <option value=''>Please Select S.Loc</option>
                              <?php
                              if (!empty($data["slocs"])) {
                                foreach ($data["slocs"] as $grp) {
                                  echo "<option value='" . $grp["lgort"] . "'>" . $grp["lgort"] . " - " . $grp["name1"] . "</option>";
                                }
                              }
                              ?>
                            </select>
                          </td>
                          <td>
                            <input type="number" name="menge[]" class="form-control menge" step="any" min="0" value=""
                              placeholder="0" required="required">
                            <input type="hidden" name="date[]" class="form-control menge" value="">
                          </td>
                          <!-- <td>
                            <input type="number" name="ng[]" class="form-control menge" step="any" min="0" value=""
                              placeholder="0">
                          </td> -->
                          <td class="text-center">
                            <button id="btn_del_item_1" type="button" class="btn btn-outline-danger"
                              onclick="deleteItem('1')"><i class="material-icons">delete</i></button>
                          </td>
                        </tr>
                      </tbody>
                    </table>
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
  <!-- Modal -->
  <div class="modal fade" id="modal_01" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="modal_01_label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modal_01_label">Scan Label Kuning</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label class="col-form-label">QR Code Label Kuning</label>
            <input type="text" name="qrcode" id="qrcode" class="form-control" value="">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal_02" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="modal_02_label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modal_02_label">Scan Kanban Receiving</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label class="col-form-label">QR Code Kanban Receiving</label>
            <input type="text" name="qrcode2" id="qrcode2" class="form-control" value="">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <?php include 'common/t_js.php'; ?>
  <script src="vendors/ega/js/scripts.js?time=<?php echo date("Ymdhis"); ?>" type="text/javascript"></script>
  <script>
    $(document).ready(function () {
      updateLgort();
    });

    $('#modal_01').on('shown.bs.modal', function (event) {
      $("#qrcode").focus();
    });

    $('#modal_02').on('shown.bs.modal', function (event) {
      $("#qrcode2").focus();
    });


    $("#qrcode").keypress(function (e) {
      if (e.which == 13) {
        validateQrCodeKuning(this.value);
      } else {

      }
    });

    $("#qrcode2").keypress(function (e) {
      if (e.which == 13) {
        validateQrCodeKanbanRecv(this.value);
      } else {

      }
    });

    function validateQrCodeKuning(qrcode) {
      var arr_code = qrcode.split(/\s+/);
      var matnr = arr_code[1];
      var menge = arr_code[2];
      var charg = arr_code[5];
      var lgort = "MSTR";
      $("#qrcode").val("");
      //get default lgort
      $.ajax({
        type: "POST",
        url: "index.php?action=api_wms_get_default_sloc",
        dataType: "json",
        data: { matnr: matnr },
        success: function (data) {
          if (data.lgort) {
            lgort = data.lgort;
          }
          var matnrs = $("select[name='matnr[]']");
          var menges = $("input[name='menge[]']");
          var chargs = $("input[name='charg[]']");
          var lgorts = $("select[name='lgort[]']");
          if (matnrs[matnrs.length - 1].value == "") {
            matnrs[matnrs.length - 1].value = matnr;
            menges[matnrs.length - 1].value = menge;
            chargs[matnrs.length - 1].value = matnr; /*charg;*/
            lgorts[matnrs.length - 1].value = lgort;
          } else {
            if (matnrs[matnrs.length - 1].value != matnr) {
              addItem();
              matnrs = $("select[name='matnr[]']");
              menges = $("input[name='menge[]']");
              chargs = $("input[name='charg[]']");
              lgorts = $("select[name='lgort[]']");
              matnrs[matnrs.length - 1].value = matnr;
              menges[matnrs.length - 1].value = menge;
              chargs[matnrs.length - 1].value = matnr; /*charg;*/
              lgorts[matnrs.length - 1].value = lgort;
            } else {
              // addItem();
              matnrs = $("select[name='matnr[]']");
              menges = $("input[name='menge[]']");
              chargs = $("input[name='charg[]']");
              lgorts = $("select[name='lgort[]']");
              matnrs[matnrs.length - 1].value = matnr;
              menges[matnrs.length - 1].value = parseInt(menges[matnrs.length - 1].value) + menge;
              chargs[matnrs.length - 1].value = matnr; /*charg;*/
              lgorts[matnrs.length - 1].value = lgort;
            }
          }

          $('#modal_01').modal("hide");
        },
        error: function () {
          alert("Connection Failed");
        }
      });
    }

    function validateQrCodeKanbanRecv(qrcode) {
      var arr_code = qrcode.split(/\s+/);
      var matnr = arr_code[3].substring(9);
      var menge = parseInt(arr_code[8].substring(0, 7));
      var charg = arr_code[9];
      var lgort = "MSTR";
      $("#qrcode2").val("");

      $.ajax({
        type: "POST",
        url: "index.php?action=api_wms_get_default_sloc",
        dataType: "json",
        data: { matnr: matnr },
        success: function (data) {
          if (data.lgort) {
            lgort = data.lgort;
          }
          var matnrs = $("select[name='matnr[]']");
          var menges = $("input[name='menge[]']");
          var chargs = $("input[name='charg[]']");
          var lgorts = $("select[name='lgort[]']");
          var date = $("input[name='date[]']");
          if (matnrs[matnrs.length - 1].value == "") {
            matnrs[matnrs.length - 1].value = matnr;
            menges[matnrs.length - 1].value = menge;
            chargs[matnrs.length - 1].value = matnr; /*charg;*/
            lgorts[matnrs.length - 1].value = lgort;
            date[matnrs.length - 1].value = charg;
          } else {

            if (matnrs[matnrs.length - 1].value != matnr) {
              if (date[matnrs.length - 1].value == charg) {
                alert("Kanban sudah discan!");
              } else {
                addItem();
                matnrs = $("select[name='matnr[]']");
                menges = $("input[name='menge[]']");
                chargs = $("input[name='charg[]']");
                lgorts = $("select[name='lgort[]']");
                matnrs[matnrs.length - 1].value = matnr;
                menges[matnrs.length - 1].value = menge;
                chargs[matnrs.length - 1].value = matnr; /*charg;*/
                lgorts[matnrs.length - 1].value = lgort;
                date[matnrs.length - 1].value = charg;
              }
            } else {
              if (date[matnrs.length - 1].value == charg) {
                alert("Kanban sudah discan!");
              } else {
                // addItem();
                matnrs = $("select[name='matnr[]']");
                menges = $("input[name='menge[]']");
                chargs = $("input[name='charg[]']");
                lgorts = $("select[name='lgort[]']");
                matnrs[matnrs.length - 1].value = matnr;
                menges[matnrs.length - 1].value = parseInt(menges[matnrs.length - 1].value) + menge;
                chargs[matnrs.length - 1].value = matnr; /*charg;*/
                lgorts[matnrs.length - 1].value = lgort;
                date[matnrs.length - 1].value = charg;
              }
            }
          }

          $('#modal_02').modal("hide");
        },
        error: function () {
          alert("Connection Failed");
        }
      });
    }

    $("#my_form").submit(function () {
      $("#btn_save").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Please Wait...');
      $("button").attr("disabled", "disabled");
    });

    $("#werks").change(function () {
      updateLgort();
    });

    function updateLgort() {
      $(".lgort").html("<option value=''>Please Select S.Loc</option>");
      $.getJSON("?action=api_wms_get_sloc", { werks: $("#werks").val() }, function (result) {
        $.each(result, function (i, field) {
          $(".lgort").append("<option value='" + field.lgort + "'>" + field.lgort + " - " + field.name1 + "</option>");
        });
      });
    }

    $("#btn_add_item").click(function () {
      addItem();
    });

    function addItem() {
      // get the last DIV which ID starts with ^= "klon"
      var $div = $('tr[id^="mseg_data_"]:last');

      // Read the Number from that DIV's ID (i.e: 3 from "klon3")
      // And increment that number by 1
      var num_before = parseInt($div.prop("id").match(/\d+/g), 10);
      var num = num_before + 1;

      // Clone it and assign the new ID (i.e: from num 4 to ID "klon4")
      var $klon_id = 'mseg_data_' + num;
      var $klon = $div.clone().prop('id', $klon_id);

      // Finally insert $klon wherever you want
      $div.after($klon);

      $("#" + $klon_id + " #btn_del_item_" + num_before).prop("id", "btn_del_item_" + num);
      $("#btn_del_item_" + num).attr("onclick", "deleteItem('" + num + "')");
      $("#" + $klon_id + " .menge").val("");
    }

    function deleteItem(num) {
      if (num == "1") {
        alert("First item cannot be deleted");
      } else {
        $("#mseg_data_" + num).remove();
      }

    }
  </script>
</body>

</html>