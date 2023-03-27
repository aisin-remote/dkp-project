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
                    <form id="my_form" method="post" action="?action=<?php echo $action; ?>&id=<?php echo $id; ?>">
                        <input type="hidden" name="save" value="post">
                        <input type="hidden" name="meins" value="<?= $bomheader[0]["meins"] ?>">
                        <div class="row">
                            <div class="col-12">
                                <div class="card mt-1">
                                    <div class="card-body">
                                        <!-- Edit Here -->
                                        <div class="form-group row">
                                            <div class="col-6">
                                                <div class="row">
                                                    <label class="col-form-label col-lg-4 col-md-3 col-sm-12">Material
                                                        No.</label>
                                                    <div class="col-lg-7 col-md-6 col-sm-12">
                                                        <select name="matnr" class="form-control select2"
                                                            data-live-search="true" required <?php if (!empty($bomheader)) {
                                                                echo "disabled";
                                                            } ?>>
                                   <!-- <option value=''>Please Select Sub Material</option> -->
                                                            <?php
                                                            foreach ($matlist as $row) {
                                                                ?>
                                                                <option value="<?php echo $row["matnr"]; ?>" <?php if ($row["matnr"] == $_GET["id"]) {
                                                                       echo "selected";
                                                                   } else {
                                                                       echo "";
                                                                   } ?>><?php echo $row["matnr"] . " - " . $row["name1"]; ?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <button type="submit" id="btn_save" name="btn_save" value="btn_save"
                                                    class="btn btn-primary float-right"><span
                                                        class="material-icons">send</span>
                                                    Post</button>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Quantity</label>
                                            <div class="col-lg-2 col-md-6 col-sm-12">
                                                <input type="text" name="menge_h" placeholder="0" class="form-control"
                                                    maxlength="20" value="<?= $bomheader[0]["menge"]; ?>"
                                                    required="required">
                                            </div>
                                        </div>
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th colspan="4" class="text-right">
                                                        <button type="button" class="btn btn-info" id="btn_add_item">Add
                                                            Sub Material</button>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th>Sub Material</th>
                                                    <th>Quantity</th>
                                                    <th class="text-center">Del</th>
                                                </tr>
                                            </thead>
                                            <tbody id='mseg'>
                                                <?php
                                                if (!empty($bomdetail)) {
                                                    // print_r($bomdetail);
                                                    $i = 1;
                                                    foreach ($bomdetail as $data) {
                                                        echo "<tr id='mseg_data_" . $i . "'>
                                                          <td>
                                                          <select name='matn1[]' class='form-control' required='required'>
                                                          <option value=''>Please Select Sub Material</option>";
                                                        foreach ($matlistall as $mat) {
                                                            ?>
                                                            <option value="<?php echo $mat["matnr"]; ?>" <?php if ($mat["matnr"] == $data["matn1"]) {
                                                                   echo "selected";
                                                               } ?>><?php echo $mat["matnr"] . " - " . $mat["name1"]; ?>
                                                            </option>
                                                            <?php
                                                        }
                                                        echo "</select>
                                                           </td>
                                                           <td>
                                                           <input type='number' name='menge[]' class='form-control' min='0' value='" . $data["menge"] . "' placeholder='0' required='required'>
                                                           </td>
                                                           <td class='text-center'>
                                                           <button id='btn_del_item_" . $i . "' type='button' onclick='deleteItem(\"" . $i . "\")' class='btn btn-danger btn-sm btn_del_item'><span class='material-icons'>delete</span></button>
                                                           </td>
                                                           </tr>
                                                           ";
                                                        $i++;
                                                    }
                                                } else {
                                                    echo "<tr id='mseg_data_1'>
                                                          <td>
                                                          <select name='matn1[]' class='form-control' required='required'>
                                                          <option value=''>Please Select Sub Material</option>";
                                                    foreach ($matlistall as $mat) {
                                                        echo "<option value='" . $mat["matnr"] . "' >" . $mat["matnr"] . " - " . $mat["name1"] . "</option>";
                                                    }
                                                    echo "</select>
                                                           </td>
                                                           <td>
                                                           <input type='number' name='menge[]' class='form-control menge' min='0' value='" . $row["menge"] . "' placeholder='0' required='required'>
                                                           </td>
                                                           <td class='text-center'>
                                                           <button id='btn_del_item_1' type='button' class='btn btn-danger btn_del_item'><span class='material-icons'>delete</span></button>
                                                           </td>
                                                           </tr>
                                                           ";
                                                }
                                                ?>
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
    <?php include 'common/t_js.php'; ?>
    <script src="vendors/ega/js/scripts.js?time=<?php echo date("Ymdhis"); ?>" type="text/javascript"></script>
    <script>
        $(document).ready(function () {
            updateLgort();
            $("#mseg_data_1").children("select").select2();
        });

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
            // get the last DIV which ID starts with ^= "klon"
            var $div = $('tr[id^="mseg_data_"]:last');

            // Read the Number from that DIV's ID (i.e: 3 from "klon3")
            // And increment that number by 1
            var num_before = parseInt($div.prop("id").match(/\d+/g), 10);
            var num = num_before + 1;

            // Clone it and assign the new ID (i.e: from num 4 to ID "klon4")
            var $klon_id = 'mseg_data_' + num;
            // // $("#" + $klon_id + " .select2").prop("id", $klon_id);
            var $klon = $div.clone(true).prop('id', $klon_id);
            // Finally insert $klon wherever you want
            $div.after($klon);

            $("#" + $klon_id + " #btn_del_item_" + num_before).prop("id", "btn_del_item_" + num);
            $("#btn_del_item_" + num).attr("onclick", "deleteItem('" + num + "')");
            $("#" + $klon_id + " .menge").val("");
        });

        function deleteItem(num) {
            console.log("deleted " + num)
            if (num == "1") {
                alert("First item cannot be deleted");
            } else {
                $("#mseg_data_" + num).remove();
            }

        }
    </script>
</body>

</html>