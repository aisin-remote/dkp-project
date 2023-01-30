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
                    <ol class="breadcrumb mb-0 mt-2">
                        <li class="breadcrumb-item"><?php echo $template["group"]; ?></li>
                        <li class="breadcrumb-item active"><?php echo $template["menu"]; ?></li>
                        <li class="breadcrumb-item active"><?php echo $template["submenu"]; ?></li>
                    </ol>
                    <?php
                    if (isset($_GET["error"])) {
                        echo '<div class="mt-1 mb-0 alert alert-danger alert-dismissible fade show" role="alert">
                      Error : ' . $_GET["error"] . '
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>';
                    }
                    ?>

                    <?php
                    if (isset($_GET["success"])) {
                        echo '<div class="mt-1 mb-0 alert alert-success alert-dismissible fade show" role="alert">
                      Success : ' . $_GET["success"] . '
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>';
                    }
                    ?>
                    <div class="row mt-1">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header" style="background-color: #E4E4E4;">
                                    <h6 class="mb-0">Header Information</h6>
                                </div>
                                <div class="card-body" style="background-color: #F5F5F5;">
                                    <div class="row">
                                        <div class="col-md-4 col-sm-12">
                                            <div class="row">
                                                <div class="col-4">Line</div>
                                                <div class="col-8">: <?php echo $data_item_dtl["line_name"]; ?></div>
                                                <div class="col-4">Date</div>
                                                <div class="col-8">: <?php echo $data_item_dtl["prod_date"]; ?></div>
                                                <div class="col-4">Shift</div>
                                                <div class="col-8">: <?php echo $data_item_dtl["shift_name"]; ?></div>
                                                <div class="col-4">Hour</div>
                                                <div class="col-8">: <?php echo $data_item_dtl["time_start"] . " - " . $data_item_dtl["time_end"]; ?></div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <div class="row">
                                                <div class="col-4">Leader</div>
                                                <div class="col-8">: <?php echo $data_item_dtl["ld_name"]; ?></div>
                                                <div class="col-4">JP</div>
                                                <div class="col-8" :>: <?php echo $data_item_dtl["jp_name"]; ?></div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <div class="row">
                                                <div class="col-4 text-nowrap">Operator 1</div>
                                                <div class="col-8">: <?php echo $data_item_dtl["op1_name"]; ?></div>
                                                <div class="col-4 text-nowrap">Operator 2</div>
                                                <div class="col-8">: <?php echo $data_item_dtl["op2_name"]; ?></div>
                                                <div class="col-4 text-nowrap">Operator 3</div>
                                                <div class="col-8">: <?php echo $data_item_dtl["op3_name"]; ?></div>
                                                <div class="col-4 text-nowrap">Operator 4</div>
                                                <div class="col-8">: <?php echo $data_item_dtl["op4_name"]; ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <form method="post" action="?action=<?php echo $action; ?>&line=<?php echo $data_item_dtl["line_id"]; ?>&date=<?php echo $data_item_dtl["xdate"]; ?>&shift=<?php echo $data_item_dtl["shift"]; ?>&prd_seq=<?php echo $data_item_dtl["prd_seq"]; ?>">
                          <input type="hidden" name="action" value="<?=$action?>">  
                          <div class="col-12 mt-1">
                                <div class="card">
                                    <div class="card-header" style="background-color: #E4E4E4;">
                                        <h6 class="mb-0">Hour <?php echo $data_item_dtl["prd_seq"] . " - " . $data_item_dtl["time_start"] . " - " . $data_item_dtl["time_end"]; ?></h6>
                                    </div>
                                    <div class="card-body" style="background-color: #F5F5F5;">

                                        <!-- Required input parameter -->
                                        <input type="hidden" name="line_id" id="line_id" value="<?php echo $data_item_dtl["line_id"]; ?>">
                                        <input type="hidden" name="prd_dt" id="prd_dt" value="<?php echo $data_item_dtl["prd_dt"]; ?>">
                                        <input type="hidden" name="shift" id="shift" value="<?php echo $data_item_dtl["shift"]; ?>">
                                        <input type="hidden" name="prd_seq" id="prd_seq" value="<?php echo $data_item_dtl["prd_seq"]; ?>">

                                        <div class="row">
                                            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-form-label">Dies</div>
                                            <div class="col-xl-4 col-lg-4 col-md-5 col-sm-6">
                                                <select name="dies_id" class="form-control select2">
                                                    <?php
                                                    foreach ($dies_list as $row) {
                                                    ?>
                                                        <option value="<?php echo $row["dies_id"]; ?>" <?php if ($row["dies_id"] == $data_item_dtl["dies_id"]) {
                                                                                                            echo "selected";
                                                                                                        } ?>><?php echo $row["group_id"] . " - " . $row["model_id"] . " - " . $row["name1"]; ?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mt-1">
                                            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-form-label">Cycle Time</div>
                                            <div class="col-xl-2 col-lg-3 col-md-5 col-sm-6">
                                                <input class="form-control form-control-sm" type="number" name="cctime" value="<?php echo $data_item_dtl["cctime"]; ?>" readonly />
                                            </div>
                                        </div>
                                        <div class="row mt-1">
                                            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-form-label">Planning Qty</div>
                                            <div class="col-xl-2 col-lg-3 col-md-5 col-sm-6">
                                                <input class="form-control form-control-sm" type="number" name="pln_qty" value="<?php echo $data_item_dtl["pln_qty"]; ?>" readonly />
                                            </div>
                                        </div>
                                        <div class="row mt-1">
                                            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-form-label">Scan Qty OK</div>
                                            <div class="col-xl-2 col-lg-3 col-md-5 col-sm-6">
                                                <input class="form-control form-control-sm" type="number" name="scn_qty_ok" value="<?php echo $data_item_dtl["scn_qty_ok"]; ?>" readonly />
                                            </div>
                                        </div>
                                        <div class="row mt-1">
                                            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-form-label">Scan Qty NG</div>
                                            <div class="col-xl-2 col-lg-3 col-md-5 col-sm-6">
                                                <input class="form-control form-control-sm" type="number" name="scn_qty_ng" value="<?php echo $data_item_dtl["scn_qty_ng"]; ?>" readonly />
                                            </div>
                                        </div>
                                        <div class="row mt-1">
                                            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-form-label">Production Qty</div>
                                            <div class="col-xl-2 col-lg-3 col-md-5 col-sm-6">
                                                <input class="form-control form-control-sm" type="number" name="prd_qty" value="<?php echo $data_item_dtl["prd_qty"]; ?>" />
                                            </div>
                                        </div>
                                        <div class="row mt-1">
                                            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-form-label">Production Time</div>
                                            <div class="col-xl-2 col-lg-3 col-md-5 col-sm-6">
                                                <input class="form-control form-control-sm" type="number" name="prd_time" value="<?php echo $data_item_dtl["prd_time"]; ?>" readonly />
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-9 col-sm-12">
                                                <input type="hidden" name="save" value="true">
                                                <button type="submit" name="btn_save" id="btn_save" value="save" class="btn btn-pale-green btn-block mt-sm-2 mt-xs-2 mt-md-0"><i class="material-icons">save</i> Save</button>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-1">
                                <div class="card">
                                    <div class="card-header" style="background-color: #E4E4E4;">
                                        <h6 class="mb-0">Detailing</h6>
                                    </div>
                                    <div class="card-body" style="background-color: #F5F5F5;">
                                        <div class="row">
                                            <div class="col-12 mb-2">
                                                <textarea name="detail_text" rows="3" class="form-control"><?php echo $data_item_dtl["detail_text"]; ?></textarea>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12">
                                                <label class="col-form-label">DC Quality Check Part</label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12">
                                                <input type="number" name="dcqcp" class="form-control" value="<?php echo $data_item_dtl["dcqcp"]; ?>">
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12">
                                                <label class="col-form-label">QA Quality Check Part</label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12">
                                                <input type="number" name="qaqcp" class="form-control" value="<?php echo $data_item_dtl["qaqcp"]; ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="col-12 mt-1">
                            <button type="button" id="add_content_stop_btn" class="btn btn-magenta" onclick="openModal01()">Add Content Stop</button>
                        </div>
                        <div class="col-12 mt-1">
                            <div class="table-responsive">
                                <table class="table table-striped table-sm">
                                    <thead>
                                        <tr class="table-secondary">
                                            <th>Start Time</th>
                                            <th>End Time</th>
                                            <th class='text-center'>Stop Time(m)</th>
                                            <th class='text-center'>Qty Steuchi</th>
                                            <th>Konten Stop</th>
                                            <th>Konten Penanganan (Action)</th>
                                            <th>Eksekutor</th>
                                            <th class='text-center'>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="data_stop">
                                        <?php
                                        if (!empty($data_stop)) {
                                            foreach ($data_stop as $row) {
                                                $button_del = "";
                                                if ($row["stop_type"] == "U") {
                                                    $button_del = "<button type='button' class='btn btn-xs btn-outline-dark' onclick='delStop(\"" . $row["line_id"] . "\",\"" . $row["prd_dt"] . "\",\"" . $row["shift"] . "\",\"" . $row["prd_seq"] . "\",\"" . $row["stop_seq"] . "\")'><i class='material-icons'>delete</i></button>";
                                                }
                                                echo "<tr>"
                                                    . "<td>" . $row["start_time"] . "</td>"
                                                    . "<td>" . $row["end_time"] . "</td>"
                                                    . "<td class='text-center'>" . $row["stop_time"] . "</td>"
                                                    . "<td class='text-center'>" . $row["qty_stc"] . "</td>"
                                                    . "<td>" . $row["stop_name"] . "</td>"
                                                    . "<td>" . $row["action_name"] . "</td>"
                                                    . "<td>" . $row["exe_name"] . "</td>"
                                                    . "<td class='text-center'>$button_del</td>"
                                                    . "</tr>";
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-12 mt-1">
                            <button type="button" id="add_ng" class="btn btn-magenta" onclick="openModal02()">Add NG & Visualization</button>
                        </div>
                        <div class="col-12 mt-1">
                            <div class="table-responsive">
                                <table class="table table-striped table-sm">
                                    <thead>
                                        <tr class="table-secondary">
                                            <th>No</th>
                                            <th>NG Type</th>
                                            <th class='text-center'>NG Visualization</th>
                                            <th class='text-center'>Quantity</th>
                                            <th>Created By</th>
                                            <th class='text-center'>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="data_ng">
                                        <?php
                                        if (!empty($data_ng)) {
                                            foreach ($data_ng as $row) {
                                                $button_del = "";
                                                $button_del = "<button type='button' class='btn btn-xs btn-outline-dark' onclick='delNG(\"" . $row["line_id"] . "\",\"" . $row["prd_dt"] . "\",\"" . $row["shift"] . "\",\"" . $row["prd_seq"] . "\",\"" . $row["ng_seq"] . "\")'><i class='material-icons'>delete</i></button>";
                                                echo "<tr>"
                                                    . "<td>" . $row["ng_seq"] . "</td>"
                                                    . "<td>" . $row["ng_type_text"] . "</td>"
                                                    . "<td class='text-center'>" . $row["loc_x"] . "," . $row["loc_y"] . "</td>"
                                                    . "<td class='text-center'>" . $row["ng_qty"] . "</td>"
                                                    . "<td>" . $row["crt_by_name"] . "</td>"
                                                    . "<td class='text-center'>$button_del</td>"
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
            </main>

            <!-- Modal -->
            <div class="modal fade" id="mymodal01" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="mymodal01Label" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="mymodal01Label">Add Content</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group row">
                                <label for="start_time" class="col-sm-3 col-form-label">Time From</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control jam_picker" id="start_time">
                                </div>
                                <label for="end_time" class="col-sm-3 col-form-label">Time To</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control jam_picker" id="end_time">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="stop_time" class="col-sm-3 col-form-label">Stop Time(m)</label>
                                <div class="col-sm-3">
                                    <input type="number" class="form-control" id="stop_time" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="qty_stc" class="col-sm-3 col-form-label">Qty Steuchi</label>
                                <div class="col-sm-3">
                                    <input type="number" class="form-control" id="qty_stc">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="stop_id" class="col-sm-3 col-form-label">Konten Stop</label>
                                <div class="col-sm-9">
                                    <select id="stop_id" class="select2" data-live-search="true">
                                        <option value="">Select Stop</option>
                                        <?php
                                        if (!empty($list_stop)) {
                                            foreach ($list_stop as $row) {
                                        ?>
                                                <option value="<?php echo $row["srna_id"]; ?>"><?php echo $row["name1"]; ?></option>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="action_id" class="col-sm-3 col-form-label">Action</label>
                                <div class="col-sm-9">
                                    <select id="action_id" class="select2" data-live-search="true">
                                        <option value="">Select Action</option>
                                        <?php
                                        if (!empty($list_action)) {
                                            foreach ($list_action as $row) {
                                        ?>
                                                <option value="<?php echo $row["srna_id"]; ?>"><?php echo $row["name1"]; ?></option>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="action_id" class="col-sm-3 col-form-label">Eksekutor</label>
                                <div class="col-sm-9">
                                    <select id="exe_empid" class="select2" data-live-search="true">
                                        <option value="">Select Eksekutor</option>
                                        <?php
                                        if (!empty($list_person)) {
                                            foreach ($list_person as $row) {
                                        ?>
                                                <option value="<?php echo $row["empid"]; ?>"><?php echo $row["name1"]; ?></option>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" onclick="saveDataStop()">Submit</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="mymodal02" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="mymodal02Label" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="mymodal02Label">Not Good Visualization</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group row">
                                <label for="ng_type" class="col-sm-3 col-form-label">NG Type</label>
                                <div class="col-sm-9">
                                    <select id="ng_type" class="select2" data-live-search="true">
                                        <option value="">Select NG Type</option>
                                        <?php
                                        if (!empty($list_ng_type)) {
                                            foreach ($list_ng_type as $row) {
                                        ?>
                                                <option value="<?php echo $row["ng_type"]; ?>"><?php echo $row["ng_group"] . " - " . $row["name1"]; ?></option>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="loc_x" class="col-sm-3 col-form-label">X</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" id="loc_x">
                                </div>
                                <label for="loc_y" class="col-sm-3 col-form-label">Y</label>
                                <div class="col-sm-3">
                                    <input type="number" class="form-control" id="loc_y">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="ng_qty" class="col-sm-3 col-form-label">Quantity</label>
                                <div class="col-sm-4">
                                    <input type="number" class="form-control" id="ng_qty">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" onclick="saveDataNG()">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php include 'common/t_footer.php'; ?>
        </div>
    </div>
    <input type="hidden" id="crt_by" value="<?php echo $_SESSION[LOGIN_SESSION]; ?>">
    <?php include 'common/t_js.php'; ?>
    <script src="vendors/ega/js/scripts.js?time=<?php echo date("Ymdhis"); ?>" type="text/javascript"></script>
    <script>
        $(document).ready(function() {
            $(".jam_picker").flatpickr({
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                time_24hr: true,
                minTime: "<?php echo $data_item_dtl["time_start"] ?>",
                maxTime: "<?php echo $data_item_dtl["time_end"] ?>",
                disableMobile: "true"
            });
        });

        function openModal01() {
            $('#mymodal01').modal({
                keyboard: false
            });
        }

        function openModal02() {
            $('#mymodal02').modal({
                keyboard: false
            });
        }
        $("#start_time").change(function() {
            calculateDate();
        });

        $("#end_time").change(function() {
            calculateDate();
        });

        function calculateDate() {
            if ($("#start_time").val().length > 0 && $("#end_time").val().length > 0) {
                var start_time = new Date('1900-01-01 ' + $("#start_time").val());
                var end_time = new Date('1900-01-01 ' + $("#end_time").val());
                var diff = Math.abs(end_time - start_time);
                var minutes = Math.floor((diff / 1000) / 60);
                $("#stop_time").val(minutes);
            }
        }

        function saveDataStop() {
            $.ajax({
                type: 'POST',
                url: '?action=api_insert_daily_stop',
                data: {
                    line_id: $("#line_id").val(),
                    prd_dt: $("#prd_dt").val(),
                    shift: $("#shift").val(),
                    prd_seq: $("#prd_seq").val(),
                    start_time: $("#start_time").val(),
                    end_time: $("#end_time").val(),
                    stop_time: $("#stop_time").val(),
                    qty_stc: $("#qty_stc").val(),
                    stop_id: $("#stop_id").val(),
                    action_id: $("#action_id").val(),
                    exe_empid: $("#exe_empid").val()
                },
                success: function(response) {
                    // handle the response here
                    if (response.status == true) {
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                },
                error: function(error) {
                    // handle the error here
                    alert(error);
                },
                dataType: 'json'
            });
        }

        function saveDataNG() {
            $.ajax({
                type: 'POST',
                url: '?action=api_insert_daily_ng',
                data: {
                    line_id: $("#line_id").val(),
                    prd_dt: $("#prd_dt").val(),
                    shift: $("#shift").val(),
                    prd_seq: $("#prd_seq").val(),

                    ng_type: $("#ng_type").val(),
                    ng_qty: $("#ng_qty").val(),
                    loc_x: $("#loc_x").val(),
                    loc_y: $("#loc_y").val(),
                    crt_by: $("#crt_by").val(),
                },
                success: function(response) {
                    // handle the response here
                    if (response.status == true) {
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                },
                error: function(error) {
                    // handle the error here
                    alert(error);
                },
                dataType: 'json'
            });
        }

        function delStop(line_id, prd_dt, shift, prd_seq, stop_seq) {
            $.ajax({
                type: 'POST',
                url: '?action=api_delete_daily_stop',
                data: {
                    line_id: line_id,
                    prd_dt: prd_dt,
                    shift: shift,
                    prd_seq: prd_seq,
                    stop_seq: stop_seq
                },
                success: function(response) {
                    // handle the response here
                    if (response.status == true) {
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                },
                error: function(error) {
                    // handle the error here
                    alert(error);
                },
                dataType: 'json'
            });
        }

        function delNG(line_id, prd_dt, shift, prd_seq, ng_seq) {
            $.ajax({
                type: 'POST',
                url: '?action=api_delete_daily_ng',
                data: {
                    line_id: line_id,
                    prd_dt: prd_dt,
                    shift: shift,
                    prd_seq: prd_seq,
                    ng_seq: ng_seq
                },
                success: function(response) {
                    // handle the response here
                    if (response.status == true) {
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                },
                error: function(error) {
                    // handle the error here
                    alert(error);
                },
                dataType: 'json'
            });
        }
    </script>
</body>

</html>