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
                        <li class="breadcrumb-item active">
                            <?php echo $template["group"]; ?>
                        </li>
                        <li class="breadcrumb-item">
                            <?php echo $template["menu"]; ?>
                        </li>
                    </ol>
                    <?php
                    if (isset($_GET["error"])) {
                        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                      Error : ' . $_GET["error"] . '
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>';
                    }
                    ?>

                    <?php
                    if (isset($_GET["success"])) {
                        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                      Success : ' . $_GET["success"] . '
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>';
                    }
                    ?>
                    <div class="row">
                        <div class="col-12">
                            <div class="card mt-2">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <!-- Edit Here -->
                                        <table class="table table-striped table-sm" id="data-table-x">
                                            <thead>
                                                <tr>
                                                    <th class="">Order No.</th>
                                                    <th class="">Start Date</th>
                                                    <th class="">Time</th>
                                                    <th class="">Group</th>
                                                    <th class="">Model</th>
                                                    <th class="">Dies No.</th>
                                                    <th class="">Order Type</th>
                                                    <th class="">Order Repair</th>
                                                    <th class="">A3 Report</th>
                                                    <th class="">Created By</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (!empty($data["list"])) {
                                                    foreach ($data["list"] as $list) {
                                                        echo "<tr>"
                                                            . "<td class=''>" . $list["ori_id"] . "</td>"
                                                            . "<td class=''>" . $list["ori_dt"] . "</td>"
                                                            . "<td class=''>" . $list["ori_time"] . "</td>"
                                                            . "<td class=''>" . $list["group_id"] . "</td>"
                                                            . "<td class=''>" . $list["model_id"] . "</td>"
                                                            . "<td class=''>" . $list["dies_no"] . "</td>"
                                                            . "<td class=''>" . $list["ori_typ"] . "</td>"
                                                            . "<td class=''><a id='ori_doc' class='view-image btn btn-outline-primary btn-sm' target='_blank' href='data:image/jpg;base64," . $list["ori_doc"] . "'>View Image</a></td>"
                                                            . "<td class=''><a id='ori_a3' class='view-image btn btn-outline-primary btn-sm' target='_blank' href='data:image/jpg;base64," . $list["ori_a3"] . "'>View Image</a></td>"
                                                            . "<td class=''>" . $list["crt_by"] . "</td>"
                                                            . "</tr>";
                                                    }
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">

                    </div>
                </div>
            </main>
            <?php include 'common/t_footer.php'; ?>
        </div>
    </div>

    <div class="modal fade" id="modal_filter" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="modal_filter_label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="get" action="#">
                <input type="hidden" name="action" value="<?= $action ?>">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal_filter_label"><span class="material-icons">filter_alt</span>
                            Filter</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row my-2">
                            <div class="col-4"><label class="col-form-label">Start Date</label></div>
                            <div class="col"><input type="text" name="date_from" class="form-control datepicker"
                                    value="<?php echo $date_from; ?>"></div>
                            <label class="col-form-label px-3">to</label>
                            <div class="col"><input type="text" name="date_to" class="form-control datepicker"
                                    value="<?php echo $date_to; ?>"></div>
                        </div>
                        <div class="row my-2">
                            <div class="col-4"><label class="col-form-label">Group</label></div>
                            <div class="col"><select name="group_id" id="group_id" class="form-control select2"
                                    style="width: 300px">
                                    <?php
                                    foreach ($group_list as $group) {
                                        ?>
                                        <option value="<?php echo $group["pval1"]; ?>" <?php if ($group["pval1"] == $group_id) {
                                               echo "selected";
                                           } ?>><?php echo $group["pval1"]; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select></div>
                        </div>
                        <div class="row my-2">
                            <div class="col-4"><label class="col-form-label">Model</label></div>
                            <div class="col"><select name="model_id" id="model_id" class="form-control select2"
                                    style="width: 300px">
                                    <?php
                                    foreach ($model_list as $model) {
                                        ?>
                                        <option value="<?php echo $model["model_id"]; ?>" <?php if ($model["model_id"] == $model_id) {
                                               echo "selected";
                                           } ?>><?php echo $model["model_id"]; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select></div>
                        </div>
                        <div class="row my-2">
                            <div class="col-4"><label class="col-form-label">Dies No #</label></div>
                            <div class="col"><select name="dies_id" id="dies_id" class="form-control select2"
                                    style="width: 300px">
                                    <?php
                                    foreach ($diesid_list as $dies) {
                                        ?>
                                        <option value="<?php echo $dies["dies_id"]; ?>" <?php if ($dies["dies_id"] == $dies_id) {
                                               echo "selected";
                                           } ?>><?php echo $dies["dies_no"] . " - " . $dies["name1"]; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select></div>
                        </div>
                        <div class="row my-2">
                            <div class="col-4"><label class="col-form-label">Order Type</label></div>
                            <div class="col">
                                <select name="ori_type" class="form-control select2" style="width: 300px">
                                    <option value="">None</option>
                                    <option value="R">Repair</option>
                                    <option value="I">Improvements</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-dark-blue-outlined" name="filter" value="filter">Apply
                            Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php include 'common/t_js.php'; ?>
    <script src="vendors/ega/js/scripts.js?time=<?php echo date("Ymdhis"); ?>" type="text/javascript"></script>
    <script>
        $(document).ready(function () {
            $(".view-image").EZView();
            $("#data-table-x").DataTable({
                stateSave: true,
                order: [
                    [0, 'desc']
                ],
                dom: "<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-2'l><'col-sm-12 col-md-4'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                    extend: 'excel',
                    title: "Report Order Repair & Improvement",
                    className: 'btn btn-dark-blue btn-sm',
                    text: '<i class="material-icons">download</i>Download Excel',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6]
                    }
                },
                {
                    className: 'btn btn-dark-blue-outlined btn-sm',
                    text: '<i class="material-icons">filter_alt</i> Filter',
                    action: function () {
                        $('#modal_filter').modal("show");

                    }
                }
                ]
            });

            $('td').each(function () {
                if ($(this).text() == 'I') {
                    $(this).text('Improvements');
                } else if ($(this).text() == "R") {
                    $(this).text('Order Repair');
                }
            });

            $('td').each(function () {
                if ($(this).html() == 'Completed') {
                    $(this).css('color', 'green');
                } else if ($(this).html() == 'On Progress') {
                    $(this).css('color', 'red');
                }
            });

            $(".datepicker").flatpickr({
                altInput: true,
                altFormat: "d-m-Y",
                dateFormat: "Ymd"
            });
        });

        $("#group_id").change(function () {
            getDiesModel($("#group_id").val());
        });

        function getDiesModel(group_id) {
            $("#model_id").empty();
            var first_model = "";
            $.getJSON("?action=api_get_dies_model", {
                group: group_id
            }, function (data) {
                var items = "";
                //$("#model_id").empty();
                var $i = 0
                $.each(data, function (key, val) {
                    if ($i == 0) {
                        first_model = val.model_id;
                        if (first_model.length > 0) {
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

        $("#model_id").change(function () {
            getDiesList($("#group_id").val(), $("#model_id").val());
        });

        function getDiesList(group_id, model_id) {
            $("#dies_id").empty();
            $.getJSON("?action=api_get_dies_list", {
                model: model_id,
                group_id: group_id
            }, function (data) {
                var items = "";
                //$("#model_id").empty();

                $.each(data, function (key, val) {
                    console.log(val.model_id);
                    items += "<option value='" + val.dies_id + "'>" + val.dies_no + " - " + val.name1 + "</option>";
                });

                $("#dies_id").html(items);
            });
        } $("#group_id").change(function () {
            getDiesModel($("#group_id").val());
        });

        function getDiesModel(group_id) {
            $("#model_id").empty();
            var first_model = "";
            $.getJSON("?action=api_get_dies_model", {
                group: group_id
            }, function (data) {
                var items = "";
                //$("#model_id").empty();
                var $i = 0
                $.each(data, function (key, val) {
                    if ($i == 0) {
                        first_model = val.model_id;
                        if (first_model.length > 0) {
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

        $("#model_id").change(function () {
            getDiesList($("#group_id").val(), $("#model_id").val());
        });

        function getDiesList(group_id, model_id) {
            $("#dies_id").empty();
            $.getJSON("?action=api_get_dies_list", {
                model: model_id,
                group_id: group_id
            }, function (data) {
                var items = "";
                //$("#model_id").empty();

                $.each(data, function (key, val) {
                    console.log(val.model_id);
                    items += "<option value='" + val.dies_id + "'>" + val.dies_no + " - " + val.name1 + "</option>";
                });

                $("#dies_id").html(items);
            });
        }
    </script>
</body>

</html>