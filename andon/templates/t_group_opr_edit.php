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
                    </ol>
                    <?php
                    if (isset($_GET["error"])) {
                        echo '<div class="alert alert-danger" role="alert">
                      Error : ' . $_GET["error"] . '
                    </div>';
                    }
                    ?>
                    <form method="post"
                        action="?action=<?php echo $action; ?>&line=<?= empty($data[0]["line_id"]) ? '0' : $data[0]["line_id"]; ?>&group=<?php echo $data[0]["group_id"]; ?>">
                        <div class="row">
                            <div class="col-12">
                                <div class="card mt-2">
                                    <div class="card-body">
                                        <!-- Edit Here -->

                                        <input type="hidden" name="empid" class="form-control" maxlength="100"
                                            value="<?php echo $data[0]["empid"]; ?>">

                                        <div class="form-group row">
                                            <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Group</label>
                                            <div class="col-lg-3 col-md-5 col-sm-12">
                                                <select name="group" class="form-control select2">
                                                    <option value="A" <?php if ($data[0]["group_id"] == "A") {
                                                        echo "selected";
                                                    } ?>>Group A</option>
                                                    <option value="B" <?php if ($data[0]["group_id"] == "B") {
                                                        echo "selected";
                                                    } ?>>Group B</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Line</label>
                                            <div class="col-lg-3 col-md-5 col-sm-12">
                                                <select name="line" class="form-control select2">
                                                    <?php
                                                    foreach ($line_list as $line) {
                                                        ?>
                                                        <option value="<?php echo $line["line_id"]; ?>" <?php if ($line["line_id"] == $data[0]["line_id"]) {
                                                               echo "selected";
                                                           } ?>><?php echo $line["name1"]; ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <?php
                                            if ($_GET["line"] == "0") {
                                        ?>
                                        <div class="form-group row">
                                            <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Operator</label>
                                            <div class="col-lg-3 col-md-5 col-sm-12">
                                                <select name="empid[]" class="form-control select2" multiple=""
                                                    data-live-search="true">
                                                    <?php
                                                    foreach ($opr_list as $opr) {
                                                            ?>
                                                            <option value="<?php echo $opr["empid"]; ?>">
                                                                <?php echo $opr["name1"]; ?></option>
                                                            <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <?php
                                            } else {
                                        ?>
                                        <div class="form-group row">
                                            <label class="col-form-label col-lg-2 col-md-3 col-sm-12">Operator</label>
                                            <div class="col-lg-3 col-md-5 col-sm-12">
                                                <select name="empid[]" class="form-control select2" multiple=""
                                                    data-live-search="true">
                                                    <?php
                                                    foreach ($opr_list as $opr) {
                                                        foreach ($data as $row) {
                                                            ?>
                                                            <option value="<?php echo $opr["empid"]; ?>" <?php if ($opr["empid"] == $row["empid"]) {
                                                                   echo "selected";
                                                               } ?>>
                                                                <?php echo $opr["name1"]; ?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <?php
                                            }
                                        ?>
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