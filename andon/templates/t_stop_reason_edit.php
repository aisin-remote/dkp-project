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
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Konten ID</label>
                      <div class="col-lg-3 col-md-5 col-sm-12">
                        <input type="text" name="srna_id" class="form-control" maxlength="100"
                          value="<?php echo $data["data"]["srna_id"]; ?>" <?php if (!empty($data["data"]["srna_id"])) {
                               echo "readonly";
                             } ?> required>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Type</label>
                      <div class="col-lg-2 col-md-5 col-sm-12">
                        <select name="type1" class="form-control select2">
                          <?php
                          foreach ($type_list as $type) {
                            ?>
                            <option value="<?php echo $type["type"]; ?>" <?php if ($type["type"] == $data["data"]["type1"]) {
                                 echo "selected";
                               } ?>><?php echo $type["name1"]; ?></option>
                            <?php
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Planned/Unplanned</label>
                      <div class="col-lg-2 col-md-5 col-sm-12">
                        <select name="type2" class="form-control select2">
                          <?php
                          foreach ($type2_list as $type) {
                            ?>
                            <option value="<?php echo $type["type"]; ?>" <?php if ($type["type"] == $data["data"]["type2"]) {
                                 echo "selected";
                               } ?>><?php echo $type["name1"]; ?></option>
                            <?php
                          }
                          ?>
                        </select>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Group</label>
                      <div class="col-lg-2 col-md-5 col-sm-12">
                        <select name="type3" class="form-control select2">
                          <option value="" selected>No Group</option>
                          <option value="MESIN" <?php if($data["data"]["type3"] == "MESIN"){echo "selected";} ?>>MESIN</option>
                          <option value="PART" <?php if($data["data"]["type3"] == "PART"){echo "selected";} ?>>PART</option>
                          <option value="OTHER" <?php if($data["data"]["type3"] == "OTHER"){echo "selected";} ?>>OTHER</option>
                        </select>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Sub Group</label>
                      <div class="col-lg-3 col-md-5 col-sm-12">
                        <input type="text" name="type4" class="form-control" maxlength="100"
                          value="<?=$data["data"]["type4"]?>">
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Description</label>
                      <div class="col-lg-6 col-md-5 col-sm-12">
                        <input type="text" name="name1" class="form-control" maxlength="100"
                          value="<?php echo $data["data"]["name1"]; ?>" required>
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
      checklabel("enable_alarm");
    });

    $('#enable_alarm').on("change", function () {
      checklabel("enable_alarm");
    });

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