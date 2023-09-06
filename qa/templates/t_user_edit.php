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
      background-color: #f8f8f8;
      background-size: cover;
      border: 1px solid #ccc;
      border-radius: 3px;
      margin-top: 7px;
      width: 200px;
      height: 100px;
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
          <ol class="breadcrumb mb-4 mt-4">
            <li class="breadcrumb-item">
              <?php echo $template["group"]; ?>
            </li>
            <li class="breadcrumb-item active">
              <?php echo $template["menu"]; ?>
            </li>
          </ol>
          <?php
          if (isset($_GET["error"])) {
            echo '<div class="alert alert-danger" role="alert">
                      Error : ' . $_GET["error"] . '
                    </div>';
          }
          ?>
          <form method="post" enctype="multipart/form-data" action="?action=<?php echo $action; ?>&id=<?php echo $id; ?>">
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
                          <button type="submit" name="save" value="save" class="btn btn-primary"><span
                              class="material-icons">save</span> Save</button>
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
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">User ID</label>
                      <div class="col-lg-3 col-md-5 col-sm-12">
                        <input type="text" name="usrid" class="form-control" maxlength="100"
                          value="<?php echo $data["data"]["usrid"]; ?>" <?php if (!empty($data["data"]["usrid"])) {
                               echo "readonly";
                             } ?>>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">User Name</label>
                      <div class="col-lg-4 col-md-6 col-sm-12">
                        <input type="text" name="name1" class="form-control" maxlength="255"
                          value="<?php echo $data["data"]["name1"]; ?>">
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Phone</label>
                      <div class="col-lg-4 col-md-6 col-sm-12">
                        <input type="text" name="phone" class="form-control" maxlength="255"
                          value="<?php echo $data["data"]["phone"]; ?>">
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">User Role</label>
                      <div class="col-lg-4 col-md-9 col-sm-12">
                        <select name="roles[]" class="form-control select2" multiple="">
                          <?php
                          foreach ($data["role"] as $val) {
                            ?>
                            <option value="<?php echo $val["roleid"]; ?>" <?php
                               if (!empty($data["user_role"])) {
                                 foreach ($data["user_role"] as $val2) {
                                   if ($val2 == $val["roleid"]) {
                                     echo "selected";
                                     break;
                                   }
                                 }
                               }
                               ?>><?php echo $val["roleid"] . " - " . $val["name1"]; ?></option>
                            <?php
                          }
                          ?>
                        </select>
                      </div>
                    </div>

                    <!--div class="form-group row">
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Link to Vendor</label>
                      <div class="col-lg-10 col-md-9 col-sm-12">
                        <select name="lifnr" class="form-control select2" data-live-search="true">
                          <option value="">Please Select Vendor</option>
                          <?php
                          /*foreach($data["vendor"] as $val) {
                          ?>
                          <option value="<?php echo $val["lifnr"]; ?>" 
                            <?php 
                            if($data["data"]["lifnr"] == $val["lifnr"]){
                              echo "selected";                                                           
                            } 
                            ?>><?php echo $val["lifnr"]." - ".$val["name1"]; ?></option>
                          <?php
                          }*/
                          ?>
                        </select>
                      </div>
                    </div-->

                    <div class="form-group row">
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">User Status</label>
                      <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="custom-control custom-switch">
                          <input type="checkbox" name="stats" class="custom-control-input" id="stats" <?php if ($data["data"]["stats"] == "A") {
                            echo "checked";
                          } ?>>
                          <label class="custom-control-label" for="stats" id="fl-stats"></label>
                        </div>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="date2" class="col-lg-2 col-form-label">Sign Image</label>
                      <div class="col-lg-4">
                        <a class="btn btn-primary text-white" onclick="openFile()"><i
                            class="material-icons">folder_open</i> Browse File</a>
                        <input type="hidden" name="foto_ktp_ori" value="<?php echo $data["data"]["ttd"]; ?>">
                        <input class="form-control" type="file" accept="image/*" id="foto_ktp" name="foto_ktp"
                          style="opacity:0;height:0;" <?php if (empty($data["data"]["ttd"])) {
                            echo "required='required'";
                          } ?>>
                      </div>

                    </div>
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12"></label>
                      <div class="col-lg-4 col-md-6 col-sm-12">
                        <img id="ktp_output" class="cropit-preview" src="<?php if (!empty($data["data"]["ttd"])) {
                          echo "data:image/jpeg;base64," . $data["data"]["ttd"];
                        } ?>">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-12">
                <div class="card mt-2">
                  <div class="card-body">
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Password</label>
                      <div class="col-lg-4 col-md-6 col-sm-12">
                        <input type="password" name="password1" class="form-control" value="">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Re-Type Password</label>
                      <div class="col-lg-4 col-md-6 col-sm-12">
                        <input type="password" name="password2" class="form-control" value="">
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
      checklabel("stats");
    });

    $('#stats').on("change", function () {
      checklabel("stats");
    });

    function openFile() {
      $("#foto_ktp").click();
      $("#foto_ktp").change(function () {
        loadFile(event);
      })
    };

    var loadFile = function (event) {
      var reader = new FileReader();
      reader.onload = function () {
        var output = document.getElementById('ktp_output');
        output.src = reader.result;
      };
      reader.readAsDataURL(event.target.files[0]);
    };

    function checklabel(id) {
      $("#fl-" + id).empty();
      if ($('#' + id).is(':checked')) {
        $("#fl-" + id).append("Active");
      } else {
        $("#fl-" + id).append("Inactive");
      }
    }
  </script>
</body>

</html>