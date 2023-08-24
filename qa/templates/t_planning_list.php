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
                        <li class="breadcrumb-item active">
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
                            <div class="card">
                                <div class="card-body" style="background-color: #F0F0F0;">
                                    <div class="row">
                                        <div class="col-lg-6 col-sm-12">
                                            <!-- filter placement -->

                                        </div>
                                        <div class="col-lg-6 col-sm-12">
                                            <div class="d-flex justify-content-end">
                                                <!-- button placement -->
                                                <a class="btn btn-pale-green"
                                                    href="?action=<?php echo $action ?>&id=0"><span
                                                        class="material-icons">add</span>New</a>
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
                                    <div class="table-responsive">
                                        <!-- Edit Here -->
                                        <form action="?action=<?php echo $action; ?>" method="POST">
                                            <table class="table table-sm table-striped" id="data-table-x">
                                                <thead>
                                                    <tr>
                                                        <th class="">Part No.</th>
                                                        <th class="">Part Name</th>
                                                        <th class="">Month</th>
                                                        <th class="text-center pr-2">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if (!empty($data["list"])) {
                                                        foreach ($data["list"] as $list) {
                                                            $dt = DateTime::createFromFormat('!m', $list['month']);
                                                            echo "<tr>"
                                                                . "<td class=''>" . $list["partno"] . "</td>"
                                                                . "<td class=''>" . $list["name1"] . "</td>"
                                                                . "<td class=''>" . $dt->format('F') . "</td>"
                                                                . "<td class='text-center'>"
                                                                . "<a href='?action=$action&id=" . $list["partno"] . "&month=" . $list["month"] . "' class='btn btn-outline-dark btn-xs'><i class='material-icons'>edit</i> Edit</a>"
                                                                . "</td>"
                                                                . "</tr>";
                                                        }
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </form>
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
        $(document).ready(function () {
            $("#data-table-x").DataTable({
                stateSave: true,
                order: [
                    [2, 'asc']
                ],
                dom: "<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-2'l><'col-sm-12 col-md-4'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                    extend: 'excel',
                    title: "Member/Operator List",
                    className: 'btn btn-pale-green btn-sm',
                    text: '<i class="material-icons">download</i>Download Excel',
                },]
            });
        });

        function openModal(empid) {
            $("#empid").val(empid);
            $("#modal_delete").modal("show");
        }

        $('td').each(function () {
            if ($(this).html() == 'Active') {
                $(this).css('color', 'green');
            } else if ($(this).html() == 'Inactive') {
                $(this).css('color', 'red');
            }
        });
    </script>
</body>

</html>