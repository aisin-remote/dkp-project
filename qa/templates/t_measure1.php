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
                        <li class="breadcrumb-item">
                            <?php echo $template["group"]; ?>
                        </li>
                        <li class="breadcrumb-item">
                            <?php echo $template["menu"]; ?>
                        </li>
                        <li class="breadcrumb-item active">
                            <?php echo $data_header["partname"]; ?>
                        </li>
                    </ol>
                    <?php
                    if (isset($_GET["error"])) {
                        echo '<div class="alert alert-danger alert-dismissible fade show mt-1" role="alert">
                      Error : ' . $_GET["error"] . '
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>';
                    }
                    ?>

                    <?php
                    if (isset($_GET["success"])) {
                        echo '<div class="alert alert-success alert-dismissible fade show mt-1" role="alert">
                      Success : ' . $_GET["success"] . '
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>';
                    }
                    ?>
                    <form method="get" id="my-form" action="?action=<?php echo $action; ?>">
                        <input type="hidden" name="action" value="<?= $action ?>">
                        <input type="hidden" name="id" value="<?= $id ?>">
                        <input type="hidden" name="date" value="<?= $date ?>">
                        <input type="hidden" name="shift" value="<?= $shift ?>">
                        <div class="row mt-1">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <!-- Edit Here -->
                                        <div class="card">
                                            <div class="card-header text-center" style="background-color: #E4E4E4;">
                                                <h6 class="mb-0">Inspection Header Data</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-4 col-sm-12">
                                                        <div class="row">
                                                            <div class="col-4">Part Number</div>
                                                            <div class="col-8">:
                                                                <?php echo $data_header["partno"]; ?>
                                                            </div>
                                                            <div class="col-4">Part Name</div>
                                                            <div class="col-8">:
                                                                <?php echo $data_header["partname"]; ?>
                                                            </div>

                                                            <div class="col-4">Shift</div>
                                                            <div class="col-8">:
                                                                <?php echo $data_header["pval1"]; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-sm-12">
                                                        <div class="row">
                                                            <div class="col-4">Inspection Date</div>
                                                            <div class="col-8">:
                                                                <?php echo $data_header["date"]; ?>
                                                            </div>
                                                            <div class="col-4">Inspector</div>
                                                            <div class="col-8">:
                                                                <?php echo $data_header["inspector"]; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-sm-12">
                                                        <div class="row">
                                                            <div class="col-4">Document No.</div>
                                                            <div class="col-8">:
                                                                <?php echo $data_header["doc_no"]; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card mt-3">
                                            <div class="card-header">
                                                <div class="d-flex align-items-center justify-content-between mb-0">
                                                    <h6 class="mb-0">Group List</h6>
                                                    <!-- <?php
                                                    if ($data_header["status"] == "N" || $data_header["approval"] == "Y") {
                                                        $disable = "disabled";
                                                    } else {
                                                        $disable = "";
                                                    }
                                                    if ($op_role == "ADMIN" || $op_role == "LEADER") {
                                                        echo "
                                                            <a href='?action=" . $action . "&id=" . $data_header['doc_no'] . "&date=" . $data_header['date'] . "&shift=" . $data_header['shift'] . "&approve=true' 
                                                            class='btn btn-sm btn-success " . $disable . "'><i class='material-icons'>done_outline</i> Approve</a>";
                                                    }
                                                    ?> -->
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="line-selection mx-4">
                                                    <div class="row">
                                                        <?php
                                                        foreach ($data_grup as $row) {
                                                            ?>
                                                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-2">
                                                                <button type="submit" name="grup"
                                                                    value="<?php echo $row["grupid"]; ?>"
                                                                    class="btn <?= $row["bgcolor"] ?> text-white btn-block"><?php echo $row["desc1"]; ?></button>
                                                            </div>
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">

                        </div>
                    </form>
                </div>
            </main>
            <?php include 'common/t_footer.php'; ?>
        </div>
    </div>
    <input type="hidden" id="shift_count" value="<?php echo $shift_count; ?>">

    <div class="modal fade" id="modal_delete" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="modal_upload_label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="GET" action="" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal_upload_label"><span class="material-icons">warning</span> Dies
                            Sedang
                            Dalam Maintenance</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="input-group mb-3">
                            <label class="custom-label" for="delete-confirmation">Dies ini sedang dalam
                                maintenance!</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-dark" data-dismiss="modal">OK</button>
                    </div>
            </div>
            </form>
        </div>
    </div>

    <?php include 'common/t_js.php'; ?>
    <script src="vendors/ega/js/scripts.js?time=<?php echo date("Ymdhis"); ?>" type="text/javascript"></script>
    <script>
        $(document).ready(function () {
            $(".datepicker").flatpickr({
                altInput: true,
                altFormat: 'd-m-Y',
                dateFormat: 'Ymd',
                disableMobile: "true",
                maxDate: "today"
            });

            getDefaultCycleTime();
        });

        $("#my-form").submit(function (event) {
            $("#btn_save").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Please Wait...');
            $("#btn_save").attr("disabled", "disabled");
        });

        var inputValue = $("#prd_dt").val();
        var currentDate = new Date();
        var formattedDate = currentDate.getFullYear().toString() +
            (currentDate.getMonth() + 1).toString().padStart(2, '0') +
            currentDate.getDate().toString().padStart(2, '0');
    </script>
</body>

</html>