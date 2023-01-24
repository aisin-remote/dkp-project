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
                                                    <th class="">Message No.</th>
                                                    <th class="">Start Date</th>
                                                    <th class="">Time</th>
                                                    <th class="">Model</th>
                                                    <th class="">Dies</th>
                                                    <th class="">Dies No #</th>
                                                    <th class="">Message Subject</th>
                                                    <th class="">Created By</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (!empty($data["list"])) {
                                                    foreach ($data["list"] as $list) {
                                                        echo "<tr>"
                                                            . "<td class=''>" . $list["group_id"] . "</td>"
                                                            . "<td class=''>" . $list["model_id"] . "</td>"
                                                            . "<td class=''>" . $list["dies_no"] . "</td>"
                                                            . "<td class=''>" . $list["stktot"] . "</td>"
                                                            . "<td class=''>" . $list[""] . "</td>"
                                                            . "<td class=''>" . $list[""] . "</td>"
                                                            . "<td class=''>" . $list["stkrun"] . "</td>"
                                                            . "<td class=''>" . $list["stkrun"] . "</td>"
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
                        title: "Report Message",
                        className: 'btn btn-dark-blue btn-sm',
                        text: '<i class="material-icons">download</i>Download Excel',
                    },
                    {
                        className: 'btn btn-dark-blue-outlined btn-sm',
                        text: '<i class="material-icons">filter_alt</i> Filter',
                        action: function() {
                            $('#modal_upload').modal("show");

                        }
                    }
                ]
            });

            $('td').each(function() {
                if ($(this).text() == 'I') {
                    $(this).text('Improvements');
                } else if ($(this).text() == "R") {
                    $(this).text('Order Repair');
                }
            });

            $('td').each(function() {
                if ($(this).html() == 'Completed') {
                    $(this).css('color', 'green');
                } else if ($(this).html() == 'On Progress') {
                    $(this).css('color', 'red');
                }
            });
        });
    </script>
</body>

</html>