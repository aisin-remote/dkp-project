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
                        <li class="breadcrumb-item active">
                            <a
                                href="?action=<?= $action ?>&id=<?= $_GET["id"] ?>&id=<?php echo $id; ?>&date=<?php echo $date; ?>&shift=<?= $shift ?>"><span
                                    class='material-icons'>arrow_back</span> Back</a>
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
                    <form method="post" id="my-form"
                        action="?action=<?php echo $action; ?>&id=<?php echo $id; ?>&date=<?php echo $date; ?>&shift=<?= $shift ?>&grup=<?= $grup ?>">
                        <input type="hidden" name="empid" value="<?= $data_header["empid"] ?>">
                        <input type="hidden" name="partid" value="<?= $data_header["partno"] ?>">
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
                                                            <div class="col-8" :>:
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
                                                <div
                                                    class="d-flex container-fluid align-items-center justify-content-between">
                                                    <h6 class="mb-0">Field List -
                                                        <?= $data_grup[0]["desc1"] ?>
                                                    </h6>
                                                    <div class="row">
                                                        <a class='view_img btn btn-primary mr-3'
                                                            href='data:image/jpg;base64,<?= $data_grup[0]["img"] ?>'>View
                                                            Image</a>
                                                        <input type="hidden" name="save" value="true">
                                                        <button name="btn_save" id="btn_save" value="save"
                                                            class="btn btn-pale-green" <?= ($data_header["approval"] == "Y") ? "disabled" : "" ?>>Save
                                                            Value</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="mx-4 mt-2 mb-0">
                                                    <div class="row">
                                                        <div class="col-6">
                                                        <?php
                                                        foreach ($data_map as $row) {
                                                            ?>
                                                            <div class="form-group row">
                                                                <label
                                                                    class="col-form-label col-lg-1 col-md-3 col-sm-12 pr-0"><?= $row["cellid"] ?> .</label>
                                                                <label
                                                                    class="col-form-label col-lg-4 col-md-3 col-sm-12 pl-0"><?= $row["desc1"] ?></label>
                                                                <div class="col-lg-7 col-md-5 col-sm-12">
                                                                    <input type="number" step="any" class="form-control form-control-sm" name="value[]"
                                                                        value="<?= $row["value"] ?>" autofocus <?= ($data_header["approval"] == "Y") ? "readonly" : "" ?> />
                                                                </div>
                                                            </div>
                                                            <?php
                                                        }
                                                        ?>
                                                        </div>
                                                        <div class="col-6 text-center">
                                                            <img src="data:image/jpg;base64,<?= $data_grup[0]["img"] ?>" alt="" class="img-fluid">
                                                        </div>
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
            if ($('.view_img').length > 0) {
                $('.view_img').EZView();
            }
            getDefaultCycleTime();


        });

        function play($path) {
            var audio = new Audio($path);
            audio.play();
        }

        document.addEventListener('keydown', function (event) {
            if (event.keyCode === 13 && event.target.nodeName === 'INPUT') {
                var form = event.target.form;
                var index = Array.prototype.indexOf.call(form, event.target);
                form.elements[index + 1].focus();
                event.preventDefault();
                if (index == form.length - 1) {
                    play('media/sound/last.mp3');
                } else {
                    play('media/sound/input.mp3');
                }
                console.log(form.length)
            }
        });

        $("#my-form").submit(function (event) {
            play('media/sound/last.mp3');
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