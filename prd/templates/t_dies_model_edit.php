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
          <form method="post" id="my-form" action="<?php echo $action; ?>?id=<?php echo $id; ?>">

            <div class="row">
              <div class="col-12">
                <div class="card mt-2">
                  <div class="card-body">
                    <!-- Edit Here -->

                    <div class="form-group row">
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Group</label>
                      <div class="col-lg-2 col-md-5 col-sm-12">
                        <select name="group_id" id="group_id" class="form-control select2" <?php if (!empty($data["data"]["group_id"])) {
                                                                                              echo "disabled";
                                                                                            } ?>>
                          <?php
                          foreach ($group_list as $group) {
                          ?>
                            <option value="<?php echo $group["pval1"]; ?>" <?php
                                                                            if ($group['pval1'] == $group_id) {
                                                                              echo "selected";
                                                                            }
                                                                            ?>><?php echo $group["pval2"]; ?></option>
                          <?php
                          }
                          ?>
                        </select>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Model</label>
                      <div class="col-lg-3 col-md-5 col-sm-12">
                        <input type="text" name="model_id" class="form-control" maxlength="100" value="<?php echo $data["data"]["model_id"]; ?>" <?php if (!empty($data["data"]["model_id"])) {
                                                                                                                                                    echo "readonly";
                                                                                                                                                  } ?> required>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Description</label>
                      <div class="col-lg-4 col-md-5 col-sm-12">
                        <input type="text" name="name1" class="form-control" maxlength="100" value="<?php echo $data["data"]["name1"]; ?>" required>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Line</label>
                      <div class="col-lg-4 col-md-9 col-sm-12">
                        <select name="lines[]" class="form-control select2" multiple="">
                          <?php
                          foreach ($data["line"] as $val) {
                          ?>
                            <option value="<?php echo $val["line_id"]; ?>" <?php
                                                                            if (!empty($data["dies_line"])) {
                                                                              foreach ($data["dies_line"] as $val2) {
                                                                                if ($val2 == $val["line_id"]) {
                                                                                  echo "selected";
                                                                                  break;
                                                                                }
                                                                              }
                                                                            }
                                                                            ?>><?php echo $val["line_id"] . " - " . $val["name1"]; ?></option>
                          <?php
                          }
                          ?>
                        </select>
                      </div>
                    </div>

                    <div class="form-group row">
                      <div class="col-lg-2 col-md-3 col-sm-12 d-sm-none d-md-block"></div>
                      <div class="col-lg-5 col-md-5 col-sm-12">
                        <button type="submit" name="save" value="save" class="btn btn-dark-blue"><span class="material-icons">save</span> Save</button>
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
    $(document).ready(function() {

    });

    $("#my-form").submit(function(event) {
      $("#group_id, #model_id").removeAttr("disabled");
    });
  </script>
</body>

</html>