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
          <ol class="breadcrumb mb-4 mt-4">
            <li class="breadcrumb-item"><?php echo $template["group"]; ?></li>
            <li class="breadcrumb-item active"><?php echo $template["menu"]; ?></li>
          </ol>
          <?php
          if (isset($_GET["error"])) {
            echo '<div class="alert alert-danger" role="alert">
                      Error : ' . $_GET["error"] . '
                    </div>';
          }
          ?>
          <form method="post" id="my-form" action="?action=<?php echo $action; ?>&id=<?php echo $id; ?>" enctype="multipart/form-data">

            <div class="card mt-2">
              <div class="row">
                <div class="col-6">
                  <div class="card-body">
                    <!-- Edit Here -->

                    <input type="hidden" name="dies_id" class="form-control" value="<?php echo $data["data"]["dies_id"]; ?>">

                    <div class="form-group row">
                      <label class="col-form-label col-lg-4 col-md-3 col-sm-12">Group</label>
                      <div class="col-lg-4 col-md-5 col-sm-12">
                        <select name="group_id" id="group_id" class="form-control" <?php if (!empty($data["data"]["group_id"])) {
                                                                                      echo "disabled";
                                                                                    } ?>>
                          <?php
                          foreach ($group_list as $group) {
                          ?>
                            <option value="<?php echo $group["pval1"]; ?>" <?php
                                                                            if ($group['pval1'] == $data["data"]["group_id"]) {
                                                                              echo "selected";
                                                                            }
                                                                            ?>><?php echo $group["pval1"]; ?></option>
                          <?php
                          }
                          ?>
                        </select>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-form-label col-lg-4 col-md-3 col-sm-12">Model</label>
                      <div class="col-lg-6 col-md-5 col-sm-12">
                        <select name="model_id" id="model_id" class="form-control" <?php echo $data["data"]["model_id"]; ?> <?php if (!empty($data["data"]["model_id"])) {
                                                                                                                              echo "disabled";
                                                                                                                            } ?>>
                          <?php
                          foreach ($model_list as $model) {
                          ?>
                            <option value="<?php echo $model["model_id"]; ?>" <?php
                                                                              if ($model['model_id'] == $data["data"]["model_id"]) {
                                                                                echo "selected";
                                                                              }
                                                                              ?>><?php echo $model["model_id"]; ?></option>
                          <?php
                          }
                          ?>
                        </select>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-form-label col-lg-4 col-md-3 col-sm-12">Dies No.</label>
                      <div class="col-lg-4 col-md-5 col-sm-12">
                        <input type="text" name="dies_no" class="form-control" maxlength="100" value="<?php echo $data["data"]["dies_no"]; ?>" required>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-form-label col-lg-4 col-md-3 col-sm-12">Description</label>
                      <div class="col-lg-8 col-md-5 col-sm-12">
                        <input type="text" name="name1" class="form-control" maxlength="100" value="<?php echo $data["data"]["name1"]; ?>">
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-form-label col-lg-4 col-md-3 col-sm-12">Cycle Time(Second)</label>
                      <div class="col-lg-4 col-md-5 col-sm-12">
                        <input type="number" name="ctsec" class="form-control" maxlength="100" value="<?php echo $data["data"]["ctsec"]; ?>" required>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-form-label col-lg-4 col-md-3 col-sm-12">Early Warning Stroke</label>
                      <div class="col-lg-4 col-md-5 col-sm-12">
                        <input type="number" name="ewstk" class="form-control" maxlength="100" value="<?php echo $data["data"]["ewstk"]; ?>" required>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-form-label col-lg-4 col-md-3 col-sm-12">Total Stroke</label>
                      <div class="col-lg-4 col-md-5 col-sm-12">
                        <input type="number" name="stktot" class="form-control" maxlength="100" value="<?php echo $data["data"]["stktot"]; ?>" <?php if ($data["data"]["stktot"] !== null) {
                                                                                                                                                  echo 'readonly';
                                                                                                                                                }  ?>>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-form-label col-lg-4 col-md-3 col-sm-12">Running Stroke</label>
                      <div class="col-lg-4 col-md-5 col-sm-12">
                        <input type="number" name="stkrun" class="form-control" maxlength="100" value="<?php echo $data["data"]["stkrun"]; ?>" readonly>
                      </div>
                    </div>

                    <div class="form-group row">
                      <div class="col-lg-4 col-md-3 col-sm-12 d-sm-none d-md-block"></div>
                      <div class="col-lg-5 col-md-5 col-sm-12">
                        <button type="submit" name="save" id="submit-btn" value="save" class="btn btn-dark-blue"><span class="material-icons">save</span> Save</button>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-6 mt-3">
                  <h6>Dies Picture</h6>
                  <img id="output" class="cropit-preview" src="<?php if (!empty($data["data"]["img01"])) {
                                                                  echo "data:image/jpeg;base64," . $data["data"]["img01"];
                                                                } ?>">
                  <div class="custom-file mt-2">
                    <input type="file" name="img01" accept="image/*" onchange="loadFile(event)">
                    <input type="hidden" name="img01x" value="<?php echo $data["data"]["img01"]; ?>">
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
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
  <script>
    $(document).ready(function() {});

    var loadFile = function(event) {
      var reader = new FileReader();
      reader.onload = function() {
        var output = document.getElementById('output');
        output.src = reader.result;
      };
      reader.readAsDataURL(event.target.files[0]);
    };

    $("#my-form").submit(function(event) {
      $("#group_id, #model_id").removeAttr("disabled");
    });

    $("#group_id").change(function() {
      getDiesModel($("#group_id").val());
    });

    function getDiesModel(group_id) {
      $.getJSON("?action=api_get_dies_model", {
        group: group_id
      }, function(data) {
        var items = "";
        //$("#model_id").empty();

        $.each(data, function(key, val) {
          console.log(val.model_id);
          items += "<option value='" + val.model_id + "'>" + val.model_id + "</option>";
        });

        $("#model_id").html(items);
      });
    }
  </script>
</body>

</html>