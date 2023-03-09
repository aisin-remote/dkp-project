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
                                                    <th class="">Preventive No.</th>
                                                    <th class="">Preventive Date</th>
                                                    <th class="">Completed Date</th>
                                                    <th class="">Group</th>
                                                    <th class="">Dies</th>
                                                    <th class="">Dies No.</th>
                                                    <th class="">Preventive Type</th>
                                                    <th class="">Status</th>
                                                    <th class="">Stroke</th>
                                                    <th class="">Zona Maintenance</th>
                                                    <th class="">Zona Parkir</th>
                                                    <th class="">Chemical Line Cooling Fix</th>
                                                    <th class="">Chemical Line Cooling Move</th>
                                                    <th class="">Die Crack Fix</th>
                                                    <th class="">Die Crack Move</th>
                                                    <th class="">Die Crack Slider</th>
                                                    <th class="">Katakrute Fix</th>
                                                    <th class="">Katakrute Move</th>
                                                    <th class="">Yakitsuki Fix</th>
                                                    <th class="">Yakitsuki Move</th>
                                                    <th class="">Yakitsuki Slider</th>
                                                    <th class="">Check Parting Line Fix</th>
                                                    <th class="">Check Parting Line Move</th>
                                                    <th class="">Check Parting Line Slider</th>
                                                    <th class="">Check V-Notch Fix</th>
                                                    <th class="">Check V-Notch Move</th>
                                                    <th class="">Cleaning Block Vacuum</th>
                                                    <th class="">Cleaning Piston Vacuum</th>
                                                    <th class="">Cleaning Pipa Vacuum</th>
                                                    <th class="">Fitting Check Piston Vacuum</th>
                                                    <th class="">Ganti O-Ring Piston Vacuum</th>
                                                    <th class="">Ganti Hose Vacuum</th>
                                                    <th class="">Valve Open</th>
                                                    <th class="">Valve Close</th>
                                                    <th class="">Valve Operation Time</th>
                                                    <th class="">Valve Stroke</th>
                                                    <th class="">MTBF Core Pin Fix</th>
                                                    <th class="">MTBF Core Pin Move</th>
                                                    <th class="">MTBF Core Pin Slider</th>
                                                    <th class="">Check PIN Back Fix</th>
                                                    <th class="">Check PIN Back Move</th>
                                                    <th class="">Check PIN Back Slider</th>
                                                    <th class="">Check Flow Power Cool Fix</th>
                                                    <th class="">Check Flow Power Cool Move</th>
                                                    <th class="">Check Flow Main Cool Fix</th>
                                                    <th class="">Check Flow Main Cool Move</th>
                                                    <th class="">Check Ejector</th>
                                                    <th class="">Check Hydraulic Core SliderC1</th>
                                                    <th class="">Check Hydraulic Core SliderC2</th>
                                                    <th class="">Check Hydraulic Core SliderC3</th>
                                                    <th class="">Check Hydraulic Core SliderC4</th>
                                                    <th class="">Check Hydraulic Core SliderC5</th>
                                                    <th class="">Fix Power Cool Fix1</th>
                                                    <th class="">Fix Power Cool Fix2</th>
                                                    <th class="">Fix Main Cool Fix</th>
                                                    <th class="">Fix Sprue Bush</th>
                                                    <th class="">Move Power Cool Move1</th>
                                                    <th class="">Move Power Cool Move2</th>
                                                    <th class="">Move Main Cool Move</th>
                                                    <th class="">Move Sprue Core</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (!empty($data["list"])) {
                                                    foreach ($data["list"] as $list) {
                                                        $c11110 = $list["c11110"] == '1' ? 'Completed' : 'On Progress';
                                                        $c11120 = $list["c11120"] == '1' ? 'Completed' : 'On Progress';
                                                        $c11211 = $list["c11211"] == '1' ? 'Completed' : 'On Progress';
                                                        $c11212 = $list["c11212"] == '1' ? 'Completed' : 'On Progress';
                                                        $c11213 = $list["c11213"] == '1' ? 'Completed' : 'On Progress';
                                                        $c11221 = $list["c11221"] == '1' ? 'Completed' : 'On Progress';
                                                        $c11222 = $list["c11222"] == '1' ? 'Completed' : 'On Progress';
                                                        $c11231 = $list["c11231"] == '1' ? 'Completed' : 'On Progress';
                                                        $c11232 = $list["c11232"] == '1' ? 'Completed' : 'On Progress';
                                                        $c11233 = $list["c11233"] == '1' ? 'Completed' : 'On Progress';
                                                        $c11241 = $list["c11241"] == '1' ? 'Completed' : 'On Progress';
                                                        $c11242 = $list["c11242"] == '1' ? 'Completed' : 'On Progress';
                                                        $c11243 = $list["c11243"] == '1' ? 'Completed' : 'On Progress';
                                                        $c11251 = $list["c11251"] == '1' ? 'Completed' : 'On Progress';
                                                        $c11252 = $list["c11252"] == '1' ? 'Completed' : 'On Progress';
                                                        $c11311 = $list["c11311"] == '1' ? 'Completed' : 'On Progress';
                                                        $c11312 = $list["c11312"] == '1' ? 'Completed' : 'On Progress';
                                                        $c11313 = $list["c11313"] == '1' ? 'Completed' : 'On Progress';
                                                        $c11314 = $list["c11314"] == '1' ? 'Completed' : 'On Progress';
                                                        $c11315 = $list["c11315"] == '1' ? 'Completed' : 'On Progress';
                                                        $c11316 = $list["c11316"] == '1' ? 'Completed' : 'On Progress';
                                                        $c113211 = $list["c113211"] == '1' ? 'Completed' : 'On Progress';
                                                        $c113212 = $list["c113212"] == '1' ? 'Completed' : 'On Progress';
                                                        $c11322 = $list["c11322"] == '1' ? 'Completed' : 'On Progress';
                                                        $c11323 = $list["c11323"] == '1' ? 'Completed' : 'On Progress';
                                                        $c1141 = $list["c1141"] == '1' ? 'Completed' : 'On Progress';
                                                        $c1142 = $list["c1142"] == '1' ? 'Completed' : 'On Progress';
                                                        $c1143 = $list["c1143"] == '1' ? 'Completed' : 'On Progress';
                                                        $c1151 = $list["c1151"] == '1' ? 'Completed' : 'On Progress';
                                                        $c1152 = $list["c1152"] == '1' ? 'Completed' : 'On Progress';
                                                        $c1153 = $list["c1153"] == '1' ? 'Completed' : 'On Progress';
                                                        $c11611 = $list["c11611"] == '1' ? 'Completed' : 'On Progress';
                                                        $c11612 = $list["c11612"] == '1' ? 'Completed' : 'On Progress';
                                                        $c11621 = $list["c11621"] == '1' ? 'Completed' : 'On Progress';
                                                        $c11622 = $list["c11622"] == '1' ? 'Completed' : 'On Progress';
                                                        $c117 = $list["c117"] == '1' ? 'Completed' : 'On Progress';
                                                        $c1181 = $list["c1181"] == '1' ? 'Completed' : 'On Progress';
                                                        $c1182 = $list["c1182"] == '1' ? 'Completed' : 'On Progress';
                                                        $c1183 = $list["c1183"] == '1' ? 'Completed' : 'On Progress';
                                                        $c1184 = $list["c1184"] == '1' ? 'Completed' : 'On Progress';
                                                        $c1185 = $list["c1185"] == '1' ? 'Completed' : 'On Progress';
                                                        $c11911 = $list["c11911"] == '1' ? 'Completed' : 'On Progress';
                                                        $c11912 = $list["c11912"] == '1' ? 'Completed' : 'On Progress';
                                                        $c11913 = $list["c11913"] == '1' ? 'Completed' : 'On Progress';
                                                        $c11914 = $list["c11914"] == '1' ? 'Completed' : 'On Progress';
                                                        $c11921 = $list["c11921"] == '1' ? 'Completed' : 'On Progress';
                                                        $c11922 = $list["c11922"] == '1' ? 'Completed' : 'On Progress';
                                                        $c11923 = $list["c11923"] == '1' ? 'Completed' : 'On Progress';
                                                        $c11924 = $list["c11924"] == '1' ? 'Completed' : 'On Progress';

                                                        if (!empty($list["cdate"])) {
                                                            $new_date = date("Y-m-d", strtotime($list["cdate"]));
                                                        } else {
                                                            $new_date = null;
                                                        }
                                                        echo "<tr>"
                                                            . "<td class=''>" . $list["pmtid"] . "</td>"
                                                            . "<td class=''>" . $list["pmtdt"] . "</td>"
                                                            . "<td class=''>" . $new_date . "</td>"
                                                            . "<td class=''>" . $list["group_id"] . "</td>"
                                                            . "<td class=''>" . $list["model_id"] . "</td>"
                                                            . "<td class=''>" . $list["dies_no"] . "</td>"
                                                            . "<td class=''>" . $list["pmtype"] . "</td>"
                                                            . "<td class=''>" . $list["pmstat"] . "</td>"
                                                            . "<td class=''>" . $formatted_number = number_format($list["pmtstk"], 0, '.', ',') . "</td>"
                                                            . "<td class=''>" . $list["zona1"] . "</td>"
                                                            . "<td class=''>" . $list["zona2"] . "</td>"
                                                            . "<td class=''>" . $c11110 . "</td>"
                                                            . "<td class=''>" . $c11120 . "</td>"
                                                            . "<td class=''>" . $c11211 . "</td>"
                                                            . "<td class=''>" . $c11212 . "</td>"
                                                            . "<td class=''>" . $c11213 . "</td>"
                                                            . "<td class=''>" . $c11221 . "</td>"
                                                            . "<td class=''>" . $c11222 . "</td>"
                                                            . "<td class=''>" . $c11231 . "</td>"
                                                            . "<td class=''>" . $c11232 . "</td>"
                                                            . "<td class=''>" . $c11233 . "</td>"
                                                            . "<td class=''>" . $c11241 . "</td>"
                                                            . "<td class=''>" . $c11242 . "</td>"
                                                            . "<td class=''>" . $c11243 . "</td>"
                                                            . "<td class=''>" . $c11251 . "</td>"
                                                            . "<td class=''>" . $c11252 . "</td>"
                                                            . "<td class=''>" . $c11311 . "</td>"
                                                            . "<td class=''>" . $c11312 . "</td>"
                                                            . "<td class=''>" . $c11313 . "</td>"
                                                            . "<td class=''>" . $c11314 . "</td>"
                                                            . "<td class=''>" . $c11315 . "</td>"
                                                            . "<td class=''>" . $c11316 . "</td>"
                                                            . "<td class=''>" . $c113211 . "</td>"
                                                            . "<td class=''>" . $c113212 . "</td>"
                                                            . "<td class=''>" . $c11322 . "</td>"
                                                            . "<td class=''>" . $c11323 . "</td>"
                                                            . "<td class=''>" . $c1141 . "</td>"
                                                            . "<td class=''>" . $c1142 . "</td>"
                                                            . "<td class=''>" . $c1143 . "</td>"
                                                            . "<td class=''>" . $c1151 . "</td>"
                                                            . "<td class=''>" . $c1152 . "</td>"
                                                            . "<td class=''>" . $c1153 . "</td>"
                                                            . "<td class=''>" . $c11611 . "</td>"
                                                            . "<td class=''>" . $c11612 . "</td>"
                                                            . "<td class=''>" . $c11621 . "</td>"
                                                            . "<td class=''>" . $c11622 . "</td>"
                                                            . "<td class=''>" . $c117 . "</td>"
                                                            . "<td class=''>" . $c1181 . "</td>"
                                                            . "<td class=''>" . $c1182 . "</td>"
                                                            . "<td class=''>" . $c1183 . "</td>"
                                                            . "<td class=''>" . $c1184 . "</td>"
                                                            . "<td class=''>" . $c1185 . "</td>"
                                                            . "<td class=''>" . $c11911 . "</td>"
                                                            . "<td class=''>" . $c11912 . "</td>"
                                                            . "<td class=''>" . $c11913 . "</td>"
                                                            . "<td class=''>" . $c11914 . "</td>"
                                                            . "<td class=''>" . $c11921 . "</td>"
                                                            . "<td class=''>" . $c11922 . "</td>"
                                                            . "<td class=''>" . $c11923 . "</td>"
                                                            . "<td class=''>" . $c11924 . "</td>"
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
                            <div class="col-4"><label class="col-form-label">Preventive No.</label></div>
                            <div class="col"><input type="text" name="pmtid" class="form-control"
                                    value="<?php echo $pmtid; ?>"></div>
                        </div>
                        <div class="row my-2">
                            <div class="col-4"><label class="col-form-label">Preventive Date</label></div>
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
                            <div class="col-4"><label class="col-form-label">Preventive Type</label></div>
                            <div class="col"><select name="pmtype" id="pmtype" class="form-control select2"
                                    style="width: 300px">
                                    <option value="">None</option>
                                    <option value="2K">Preventive Stroke 2000</option>
                                    <option value="6K">Preventive Stroke 6000</option>
                                </select></div>
                        </div>
                        <div class="row my-2">
                            <div class="col-4"><label class="col-form-label">Status</label></div>
                            <div class="col">
                                <select name="pmstat" class="form-control select2" style="width: 300px">
                                    <option value="">None</option>
                                    <option value="C">Completed</option>
                                    <option value="N">On Progress</option>
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
                    title: "Report Checksheet Preventive Detail",
                    className: 'btn btn-dark-blue btn-sm',
                    text: '<i class="material-icons">download</i>Download Excel',
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

            $(".datepicker").flatpickr({
                altInput: true,
                altFormat: "d-m-Y",
                dateFormat: "Ymd"
            });

            $('td').each(function () {
                if ($(this).html() == 'Completed') {
                    $(this).css('color', 'green');
                } else if ($(this).html() == 'On Progress') {
                    $(this).css('color', 'red');
                }
            });

            var table = $('#data-table-x').DataTable();
            table.on('draw.dt', function () {
                $('td').each(function () {
                    if ($(this).html() == 'Completed') {
                        $(this).css('color', 'green');
                    } else if ($(this).html() == 'On Progress') {
                        $(this).css('color', 'red');
                    }
                });
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