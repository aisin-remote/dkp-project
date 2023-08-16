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
                    <form id="my_form" method="post" enctype="multipart/form-data"
                        action="?action=<?php echo $action; ?>&id=<?php echo $id; ?>">
                        <input type="hidden" name="save" value="post">
                        <div class="row">
                            <div class="col-12">
                                <div class="card mt-1">
                                    <div class="card-body">
                                        <!-- Edit Here -->
                                        <div class="form-group row">
                                            <div class="col-6">
                                                <div class="row">
                                                    <label class="col-form-label col-lg-4 col-md-3 col-sm-12">Part
                                                        Number</label>
                                                    <div class="col-lg-7 col-md-6 col-sm-12">
                                                        <input type="text" name="partno"
                                                            class="form-control form-control-md"
                                                            value="" required />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6"></div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-6">
                                                <div class="row">
                                                    <label class="col-form-label col-lg-4 col-md-3 col-sm-12">Part
                                                        Name</label>
                                                    <div class="col-lg-7 col-md-6 col-sm-12">
                                                        <input type="text" name="partname"
                                                            class="form-control form-control-md"
                                                            value="" required />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6"></div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-6">
                                                <div class="row">
                                                    <label class="col-form-label col-lg-4 col-md-3 col-sm-12">File Model
                                                        (Excel)</label>
                                                    <div class="col-lg-7 col-md-6 col-sm-12">
                                                        <div class="input-group mb-3">
                                                            <div class="custom-file">
                                                                <input type="file" class="custom-file-input" id="excel"
                                                                    name="excel" accept=".xls, .xlsx" required>
                                                                <label class="custom-file-label excel-label"
                                                                    for="inputFile01">Choose
                                                                    file</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6"></div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-6">
                                                <div class="row">
                                                    <label class="col-form-label col-lg-4 col-md-3 col-sm-12"></label>
                                                    <div class="col-lg-7 col-md-6 col-sm-12">
                                                        <button type="submit" id="btn_save" name="btn_save"
                                                            value="btn_save" class="btn btn-primary btn-block"><span
                                                                class="material-icons">save</span>
                                                            Save</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6"></div>
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
    <input type="hidden" name="tmpid" value="<?= $_GET["id"] ?>">
    <?php include 'common/t_js.php'; ?>
    <script src="vendors/ega/js/scripts.js?time=<?php echo date("Ymdhis"); ?>" type="text/javascript"></script>
    <script>
        $(document).ready(function () {
            if ($('.view_img').length > 0) {
                $('.view_img').EZView();
            }
            bsCustomFileInput.init();
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

        $("#btn_add_grup").click(function () {
            // get the last DIV which ID starts with ^= "klon"
            var $div = $('tr[id^="table_"]:last');

            // Read the Number from that DIV's ID (i.e: 3 from "klon3")
            // And increment that number by 1
            var num_before = parseInt($div.prop("id").match(/\d+/g), 6);
            num_grup = num_before + 1;

            // Clone it and assign the new ID (i.e: from num 4 to ID "klon4")
            var $klon_id = 'table_' + num_grup;
            // // $("#" + $klon_id + " .select2").prop("id", $klon_id);
            var $klon = $div.clone(true).prop('id', $klon_id);
            // Finally insert $klon wherever you want
            $div.after($klon);

            $("#" + $klon_id + " #btn_del_grup_" + num_before).prop("id", "btn_del_grup_" + num_grup);
            $("#" + $klon_id + " #image_" + num_before).prop("id", "image_" + num_grup);
            $("#" + $klon_id + " #image_label_" + num_before).prop("id", "image_label_" + num_grup);
            $("#" + $klon_id + " .view_img").remove();
            // $("#image_" + num_grup).attr("onchange", "changeLabel('" + num_grup + "')");
            $("#btn_del_grup_" + num_grup).attr("onclick", "deleteGrup('" + num_grup + "')");
            // $(".view-img")
            bsCustomFileInput.init();
        });

        function deleteGrup(num) {
            console.log("deleted " + num)
            if (num == "1") {
                alert("First item cannot be deleted");
            } else {
                $("#table_" + num).remove();
            }
        }

        // function changeLabel(num) {
        //     var fileName = $("#image_" + num).val();
        //     console.log(num)
        //     // $("#image_label_" + num).html(fileName);
        // }

        $('#excel').change(function (e) {
            var fileName = e.target.files[0].name;
            $('.excel-label').html(fileName);
        });

    </script>
</body>

</html>