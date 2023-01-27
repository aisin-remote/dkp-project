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
                        <li class="breadcrumb-item active"><?php echo $template["group"]; ?></li>
                        <li class="breadcrumb-item"><?php echo $template["menu"]; ?></li>
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
                                                    <th class="">Model</th>
                                                    <th class="">Dies</th>
                                                    <th class="">Dies No.</th>
                                                    <th class="">Preventive Type</th>
                                                    <th class="">Status</th>
                                                    <th class="">Stroke</th>
                                                    <th class="text-center">View</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (!empty($data["list"])) {
                                                    foreach ($data["list"] as $list) {
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
                                                            . "<td class='text-center pr-3'>"
                                                            . "<a href='$action?id=" . $list["pmtid"] . "&step=2' class='btn btn-outline-dark btn-xs text-center mb-1'><i class='material-icons'>visibility</i></a>"
                                                            . "</td>"
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

    <div class="modal fade" id="modal_filter" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modal_filter_label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="get" action="#">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal_filter_label"><span class="material-icons">filter_alt</span> Filter</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row my-2">
                            <div class="col-4"><label class="col-form-label">Preventive No.</label></div>
                            <div class="col"><input type="text" name="pmtid" class="form-control" value="<?php echo $pmtid; ?>"></div>
                        </div>
                        <div class="row my-2">
                            <div class="col-4"><label class="col-form-label">Preventive Date</label></div>
                            <div class="col"><input type="text" name="date_from" class="form-control datepicker" value="<?php echo $date_from; ?>"></div>
                            <label class="col-form-label px-3">to</label>
                            <div class="col"><input type="text" name="date_to" class="form-control datepicker" value="<?php echo $date_to; ?>"></div>
                        </div>
                        <div class="row my-2">
                            <div class="col-4"><label class="col-form-label">Model</label></div>
                            <div class="col"><input type="text" name="group_id" class="form-control" value="<?php echo $group_id; ?>"></div>
                        </div>
                        <div class="row my-2">
                            <div class="col-4"><label class="col-form-label">Dies</label></div>
                            <div class="col"><input type="text" name="model_id" class="form-control" value="<?php echo $model_id; ?>"></div>
                        </div>
                        <div class="row my-2">
                            <div class="col-4"><label class="col-form-label">Dies No #</label></div>
                            <div class="col"><input type="text" name="dies_no" class="form-control" value="<?php echo $dies_no; ?>"></div>
                        </div>
                        <div class="row my-2">
                            <div class="col-4"><label class="col-form-label">Preventive Type</label></div>
                            <div class="col"><input type="text" name="pmtype" class="form-control" value="<?php echo $pmtype; ?>"></div>
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
                        <button type="submit" class="btn btn-dark-blue-outlined" name="filter" value="filter">Apply Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php include 'common/t_js.php'; ?>
    <script src="vendors/ega/js/scripts.js?time=<?php echo date("Ymdhis"); ?>" type="text/javascript"></script>
    <script>
        $(document).ready(function() {
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
                        title: "Report Checksheet Preventive",
                        className: 'btn btn-dark-blue btn-sm',
                        text: '<i class="material-icons">download</i>Download Excel',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                        }
                    },
                    {
                        className: 'btn btn-dark-blue-outlined btn-sm',
                        text: '<i class="material-icons">filter_alt</i> Filter',
                        action: function() {
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

            $('td').each(function() {
                if ($(this).html() == 'Completed') {
                    $(this).css('color', 'green');
                } else if ($(this).html() == 'On Progress') {
                    $(this).css('color', 'red');
                }
            });

            var table = $('#data-table-x').DataTable();
            table.on('draw.dt', function() {
                $('td').each(function() {
                    if ($(this).html() == 'Completed') {
                        $(this).css('color', 'green');
                    } else if ($(this).html() == 'On Progress') {
                        $(this).css('color', 'red');
                    }
                });
            });
        });
    </script>
</body>

</html>