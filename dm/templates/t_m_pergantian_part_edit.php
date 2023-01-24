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
          <form method="post" action="<?php echo $action; ?>?id=<?php echo $id; ?>">

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
                          <button type="submit" type="button" name="save" class="btn btn-dark-blue btn-sm px-5 mx-2" id="btn-save">Save</button>
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
                            <option value="<?php echo $group["pval1"]; ?>" <?php if($data["data"]["group_id"] == $group["pval1"]) {echo "selected";} ?>><?php echo $group["pval1"]; ?></option>
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
                            <option value="<?php echo $model["model_id"]; ?>" <?php if($data["data"]["model_id"] == $model["model_id"]) {echo "selected";} ?>><?php echo $model["model_id"]; ?></option>
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
                            <option value="<?php echo $diesid["dies_id"]; ?>" <?php if($data["data"]["dies_id"] == $diesid["dies_id"]) {echo "selected";} ?>><?php echo $diesid["dies_no"]; ?></option>
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
                        <input type="text" name="desc1" class="form-control" maxlength="100" value="<?php echo $data["data"]["desc1"]; ?>" >
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
                    <table class="table">
                      <tr class="table-secondary">
                        <td>1.3</td>
                        <td colspan="2">Penggantian Part</td>
                      </tr>
                      <tr class="table-secondary">
                        <td>1.3.1</td>
                        <td colspan="2">Fix</td>
                      </tr>
                    <?php 
                    if(!empty($part_list)) {
                      foreach($part_list as $row) {
                        if($row["part_grp"] == "F") {
                      ?>
                      <tr>
                        <td><?php echo $row["part_id"]; ?></td>
                        <td><?php echo $row["name1"]; ?></td>
                        <td><input type="<?php echo $row["input_type"]; ?>" name="item[<?php echo $row["part_id"]; ?>]"  <?php if($row["input_type"]=="checkbox") { if(!empty($data["item"])) {foreach($data["item"] as $itm){if($itm["part_id"]==$row["part_id"]){echo "checked";break;}}} } else {if(!empty($data["item"])){foreach($data["item"] as $itm){if($itm["part_id"]==$row["part_id"]){echo "value='".$itm["part_text"]."'";break;}}}} ?>></td>
                      </tr>
                      <?php
                        }
                      }
                    }
                    ?>
                      <tr class="table-secondary">
                        <td>1.3.2</td>
                        <td colspan="2">Move</td>
                      </tr>
                    <?php 
                    if(!empty($part_list)) {
                      foreach($part_list as $row) {
                        if($row["part_grp"] == "M") {
                      ?>
                      <tr>
                        <td><?php echo $row["part_id"]; ?></td>
                        <td><?php echo $row["name1"]; ?></td>
                        <td><input type="<?php echo $row["input_type"]; ?>" name="item[<?php echo $row["part_id"]; ?>]" <?php if($row["input_type"]=="checkbox") { if(!empty($data["item"])) {foreach($data["item"] as $itm){if($itm["part_id"]==$row["part_id"]){echo "checked";break;}}} } else {if(!empty($data["item"])){foreach($data["item"] as $itm){if($itm["part_id"]==$row["part_id"]){echo "value='".$itm["part_text"]."'";break;}}}} ?>></td>
                      </tr>
                      <?php
                        }
                      }
                    }
                    ?>  
                    </table>
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
    
    $(document).ready(function() {
      $(".datepicker").flatpickr({
        altInput: true,
        altFormat: "d-m-Y",
        dateFormat: "Y-m-d"
      });
    });
    
    $("#group_id").change( function(){
      getDiesModel($("#group_id").val());      
    }); 

    function getDiesModel(group_id) {
      $("#model_id").empty();
      var first_model = "";
      $.getJSON( "api_get_dies_model",{ group:group_id }, function( data ) {
        var items = "";
        //$("#model_id").empty();
        var $i = 0
        $.each( data, function( key, val ) {
          if($i == 0) {
            first_model = val.model_id;
            if(first_model.length > 0) {
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
    
    $("#model_id").change( function(){
      getDiesList($("#model_id").val());
    }); 
    
    function getDiesList(model_id) {
      $("#dies_id").empty();
      $.getJSON( "api_get_dies_list",{ model:model_id }, function( data ) {
        var items = "";
        //$("#model_id").empty();

        $.each( data, function( key, val ) {
          console.log(val.model_id);
          items += "<option value='" + val.dies_id + "'>" + val.dies_no + "</option>";
        });

        $("#dies_id").html(items);
      });
    }
  </script>
</body>

</html>