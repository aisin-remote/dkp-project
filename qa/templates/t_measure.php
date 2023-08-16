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
                <div class="container-fluid mt-4">
                    <ol class="breadcrumb mb-2 mt-4">
                        <li class="breadcrumb-item">
                            <?php echo $template["group"]; ?>
                        </li>
                        <li class="breadcrumb-item active">
                            <?php echo $template["menu"]; ?>
                        </li>
                        <li class="breadcrumb-item active">
                            <?php echo $template["submenu"]; ?>
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
                    <form id="my_form" method="post" action="?action=<?php echo $action; ?>&id=0">
                        <input type="hidden" name="action" value="<?= $action ?>">
                        <div class="row">
                            <div class="col-12">
                                <div class="card mt-0">
                                    <div class="card-body">
                                        <!-- Edit Here -->
                                        <div class="form-group row">
                                            <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Inspection
                                                Date</label>
                                            <div class="col-lg-2 col-md-5 col-sm-12">
                                                <input type="text" name="date" class="form-control datepicker"
                                                    value="<?php echo $prd_date; ?>" required>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Shift</label>
                                            <div class="col-lg-2 col-md-5 col-sm-12">
                                                <select name="shift" class="form-control select2">
                                                    <?php
                                                    foreach ($shift_list as $row) {
                                                        ?>
                                                        <option value="<?php echo $row["seq"]; ?>" <?php if ($row["seq"] == $shift) {
                                                               echo "selected";
                                                           } ?>><?php echo $row["pval1"]; ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Inspector</label>
                                            <div class="col-lg-3 col-md-5 col-sm-12">
                                                <select name="inspector" class="form-control select2">
                                                    <?php
                                                    foreach ($operator_list as $row) {
                                                        ?>
                                                        <option value="<?php echo $row["empid"]; ?>"><?php echo $row["name1"]; ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Part Name</label>
                                            <div class="col-lg-4 col-md-5 col-sm-12">
                                                <select name="partid" class="form-control select2">
                                                    <?php
                                                    foreach ($list_tmpl as $row) {
                                                        ?>
                                                        <option value="<?php echo $row["partno"]; ?>"><?= $row["partno"] ?>
                                                            - <?php echo $row["name1"]; ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-lg-2 col-md-3 col-sm-12 d-sm-none d-md-block"></div>
                                            <div class="col-lg-5 col-md-5 col-sm-12">
                                                <input type="hidden" name="save" value="true">
                                                <button type="submit" name="btn_save" id="btn_save" value="save"
                                                    class="btn btn-pale-green">Save
                                                    & Generate Measurement</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </main>
            <?php include 'common/t_footer.php'; ?>
        </div>
    </div>
    <input type="hidden" id="usrid" value="<?php echo $_SESSION[LOGIN_SESSION]; ?>">
    <?php include 'common/t_js.php'; ?>
    <script src="vendors/ega/js/scripts.js?time=<?php echo date("Ymdhis"); ?>" type="text/javascript"></script>
    <script>
        $(document).ready(function () {
            $(".datepicker").flatpickr({
                altInput: true,
                altFormat: 'd-m-Y',
                dateFormat: 'Ymd',
                maxDate: "today"
            });
        });

        $("#my_form").submit(function () {
            $("#btn_save").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Please Wait...');
            $("button").attr("disabled", "disabled");
        });
    </script>
</body>

</html>