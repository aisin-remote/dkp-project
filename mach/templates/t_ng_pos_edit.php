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
            echo '<div class="alert alert-danger" role="alert">
                      Error : ' . $_GET["error"] . '
                    </div>';
          }
          ?>
          <form method="post" action="?action=<?php echo $action; ?>&id=<?php echo $id; ?>">

            <div class="row">
              <div class="col-12">
                <div class="card mt-2">
                  <div class="card-body">
                    <!-- Edit Here -->

                    <div class="form-group row">
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Position ID</label>
                      <div class="col-lg-3 col-md-5 col-sm-12">
                        <input type="text" class="form-control" name="ng_id"
                          value="<?php echo $data["data"][0]["ng_pos_id"]; ?>" <?php if (!empty($data["data"][0]["ng_pos_id"])) {
                               echo "readonly";
                             } else {
                               "";
                             } ?>>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Group</label>
                      <div class="col-lg-3 col-md-5 col-sm-12">
                        <select name="group" id="group" class="form-control">
                          <?php
                          foreach ($group_list as $group) {
                            ?>
                            <option value="<?php echo $group["pval1"]; ?>" <?php
                               if ($group['group_id'] === $data["data"][0]["group_id"]) {
                                 echo "selected";
                               } else {
                                 "";
                               }
                               ?>><?php echo $group["pval1"]; ?></option>
                            <?php
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Model</label>
                      <div class="col-lg-3 col-md-5 col-sm-12">
                        <select name="model" id="model" class="form-control">
                          <?php
                          foreach ($model_list as $model) {
                            ?>
                            <option value="<?php echo $model["model_id"]; ?>" <?php
                               if ($model['model_id'] === $data["data"][0]["model_id"]) {
                                 echo "selected";
                               } else {
                                 "";
                               }
                               ?>><?php echo $model["name1"]; ?></option>
                            <?php
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">No.</label>
                      <div class="col-lg-3 col-md-5 col-sm-12">
                        <input type="text" name="no" class="form-control" maxlength="100"
                          value="<?php echo $data["data"][0]["ng_pos_no"]; ?>" required>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Description</label>
                      <div class="col-lg-3 col-md-5 col-sm-12">
                        <input type="text" name="desc" class="form-control" maxlength="100"
                          value="<?php echo $data["data"][0]["desc1"]; ?>" required>
                      </div>
                    </div>
                    <div class="form-group row">
                      <div class="col-lg-2 col-md-3 col-sm-12 d-sm-none d-md-block"></div>
                      <div class="col-lg-5 col-md-5 col-sm-12">
                        <button type="submit" name="save" value="save" class="btn btn-pale-green"><span
                            class="material-icons">save</span> Save</button>
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

    });
  </script>
</body>

</html>