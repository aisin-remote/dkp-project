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
                    <form method="post" action="?action=<?php echo $action; ?>&id=<?php echo $id; ?>&id3=<?php echo $id3; ?>">

                        <div class="row">
                            <div class="col-12">
                                <div class="card mt-2">
                                    <div class="card-body">
                                        <!-- Edit Here -->

                                        <div class="form-group row">
                                            <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Shift ID</label>
                                            <div class="col-lg-2 col-md-5 col-sm-12">
                                                <select name="shift_id" id="shift_id" class="form-control select2">
                                                    <?php
                                                    foreach ($shift_list as $shift) {
                                                        ?>
                                                        <option value="<?php echo $shift["shift_id"]; ?>" <?php if ($shift["shift_id"] == $data["data"]["shift_id"]) {
                                                               echo "selected";
                                                           } ?>><?php echo $shift["shift_id"]; ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Stop Reason Action
                                                ID</label>
                                            <div class="col-lg-3 col-md-5 col-sm-12">
                                                <select name="srna_id" id="srna_id" class="form-control select2">
                                                    <?php
                                                    foreach ($srna_list as $srna) {
                                                        ?>
                                                        <option value="<?php echo $srna["srna_id"]; ?>" <?php if ($srna["srna_id"] == $data["data"]["srna_id"]) {
                                                               echo "selected";
                                                           } ?>><?php echo $srna["srna_id"] . " - " . $srna["name1"]; ?>
                                                        </option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Time ID</label>
                                            <div class="col-lg-2 col-md-5 col-sm-12">
                                                <select name="time_id" id="time_id" class="form-control select2">
                                                    <?php
                                                    foreach ($time_list as $time) {
                                                        ?>
                                                        <option value="<?php echo $time["time_id"]; ?>" <?php if ($time["time_id"] == $_GET["id2"]) {
                                                               echo "selected";
                                                           } else {
                                                               " ";
                                                           } ?>><?php echo $time["time_id"] . " (" . $time["time_start"] . " - " . $time["time_end"] . ")"; ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Time Start</label>
                                            <div class="col-lg-2 col-md-5 col-sm-12">
                                                <input type="text" name="time_start" class="form-control jam_picker"
                                                    maxlength="100" value="<?php echo $data["data"]["time_start"]; ?>"
                                                    required>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Time End</label>
                                            <div class="col-lg-2 col-md-5 col-sm-12">
                                                <input type="text" name="time_end" class="form-control jam_picker"
                                                    maxlength="100" value="<?php echo $data["data"]["time_end"]; ?>"
                                                    required>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-lg-2 col-md-3 col-sm-12 d-sm-none d-md-block"></div>
                                            <div class="col-lg-5 col-md-5 col-sm-12">
                                                <button type="submit" name="save" value="save"
                                                    class="btn btn-pale-green"><span class="material-icons">save</span>
                                                    Save</button>
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
            $(".jam_picker").flatpickr({
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                time_24hr: true,
                minTime: "<?php echo $data_item_dtl["time_start"] ?>",
                maxTime: "<?php echo $data_item_dtl["time_end"] ?>",
                disableMobile: "true"
            });

            checklabel("enable_alarm");

            getShiftTime($("#shift_id").val());
        });

        $("#shift_id").change(function () {
            getShiftTime($("#shift_id").val());
        });

        function getShiftTime(shift_id) {
            $.getJSON("?action=api_get_shift_time", {
                shift: shift_id
            }, function (data) {
                var items = "";
                //$("#model_id").empty();

                $.each(data, function (key, val) {
                    console.log(val.time_id + " (" + val.time_start + " - " + val.time_end + ")");
                    items += "<option value='" + val.time_id + "'>" + val.time_id + " (" + val.time_start + " - " + val.time_end + ")" + "</option>";
                });

                $("#time_id").html(items);
            });
        }

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