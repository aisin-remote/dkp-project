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
                    <form method="post" action="?action=<?php echo $action; ?>&id=<?php echo $id; ?>">

                        <div class="row">
                            <div class="col-12">
                                <div class="card mt-2">
                                    <div class="card-body">
                                        <!-- Edit Here -->

                                        <div class="form-group row">
                                            <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Part No.</label>
                                            <div class="col-lg-3 col-md-5 col-sm-12">
                                                <input type="text" name="partno" class="form-control" maxlength="100"
                                                    value="<?php echo $data["data"]["partno"]; ?>" required>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Month</label>
                                            <div class="col-lg-3 col-md-5 col-sm-12">
                                                <select name="month" class="form-control select2" required>
                                                    <option value="">Select Month</option>
                                                    <?php
                                                    foreach (['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $monthNumber => $month) {
                                                        ?>
                                                        <option value='<?= $monthNumber + 1 ?>' <?= ($data["data"]["month"] == ($monthNumber + 1)) ? "selected" : "" ?>><?= $month ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
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

        });
    </script>
</body>

</html>