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
                        <li class="breadcrumb-item"><?php echo $template["group"]; ?></li>
                        <li class="breadcrumb-item active"><?php echo $template["menu"]; ?></li>
                    </ol>
                    <?php
                    if (isset($_GET["error"])) {
                        echo '<div class="alert alert-danger alert-dismissible" role="alert">
                      Error : ' . $_GET["error"] . '
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>';
                    }
                    ?>
                    <form method="post" id="my-form" action="?action=<?php echo $action; ?>&id=<?php echo $id; ?>&step=2" enctype="multipart/form-data">
                      
                        <div class="row">
                            <div class="col-12">
                                <div class="card mt-2">
                                    <div class="card-body py-1 px-2">
                                        <table class="table table-sm table-borderless my-1">
                                            <thead style="background-color: #E4E4E4;">
                                                <tr>
                                                    <th class="align-middle px-3 table-header col-2" scope="col">Header Info.</th>
                                                    <th class="align-middle px-3 table-header col-3" scope="col"></th>
                                                    <th class="align-middle px-3 table-header" scope="col"></th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <tr>
                                                    <td class="align-middle px-3 table-item">Preventive No.</td>
                                                    <td class="align-middle px-3 table-item">
                                                        <input class="form-control form-control-sm" name="pmtid" id="pmtid" type="text" value="<?php echo $data["data"]["pmtid"]; ?>" readonly>
                                                    </td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                </tr>
                                                <tr>
                                                    <td class="align-middle px-3 table-item">Maintenance Date</td>
                                                    <td class="align-middle px-3 table-item">
                                                        <input class="form-control form-control-sm" name="pmtdt" type="text" value="<?php echo $data["data"]["pmtdt"]; ?>" readonly>
                                                    </td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                </tr>
                                                <tr>
                                                    <td class="align-middle px-3 table-item">Stroke</td>
                                                    <td class="align-middle px-3 table-item">
                                                        <input class="form-control form-control-sm" name="pmtstk" type="text" value="<?php echo $data["data"]["pmtstk"]; ?>" readonly>
                                                    </td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                </tr>
                                                <tr>
                                                    <td class="align-middle px-3 table-item">Preventive Type</td>
                                                    <td class="align-middle px-3 table-item">
                                                        <input class="form-control form-control-sm" name="pmtype" type="text" value="<?php echo $data["data"]["pmtype"]; ?>" readonly>
                                                    </td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                </tr>
                                                <tr>
                                                    <td class="align-middle px-3 table-item">Checked By</td>
                                                    <td class="align-middle px-3 table-item">
                                                        <input class="form-control form-control-sm" name="pmtby" type="text" value="<?php echo $data["data"]["pmtby"]; ?>" readonly>
                                                    </td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="card mt-2">
                                    <div class="card-body py-1 px-2">
                                        <table class="table table-borderless table-sm my-1">
                                            <thead style="background-color: #E4E4E4;">
                                                <tr>
                                                    <th class="align-middle px-2 table-header" scope="col">1.1.1</th>
                                                    <th class="align-middle px-3 table-header" scope="col">Chemical Cleaning</th>
                                                    <th class="align-middle px-3 table-header" scope="col">
                                                        <input type="file" accept="image/png,image/jpg" name="c11100" />
                                                        <input type="hidden" name="c11100_x" value="<?= $data["data"]["c11100"]; ?>" />
                                                    </th>
                                                    <th class="align-middle px-3 table-header" scope="col"><?= (!empty($data["data"]["c11100"])) ? "<a download='file_chemical_cleaning.jpg' href='data:image/jpg;base64," . $data["data"]["c11100"] . "'>Download File</a>" : "" ?></th>
                                                    <th class="align-middle px-3 table-header" scope="col"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="align-middle px-3 table-item">1.1.1.1</td>
                                                    <td class="align-middle px-3 table-item">Chemical Line Cooling Fix</td>
                                                    <td class="align-middle px-3 table-item">
                                                        <input class="" name="c11110" <?= (($data["data"]["c11110"] == "1")) ? "checked" : ''; ?> type="checkbox" data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success" data-offstyle="danger" data-size="mini" data-width="120">
                                                    </td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                </tr>
                                                <tr>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                </tr>
                                                <tr>
                                                    <td class="align-middle px-3 table-item">1.1.1.2</td>
                                                    <td class="align-middle px-3 table-item">Chemical Line Cooling Move</td>
                                                    <td class="align-middle px-3 table-item">
                                                        <input class="" name="c11120" <?= (($data["data"]["c11120"] == "1")) ? "checked" : ''; ?> type="checkbox" data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success" data-offstyle="danger" data-size="mini" data-width="120">
                                                    </td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                </tr>
                                            </tbody>

                                            <thead style="background-color: #E4E4E4;">
                                                <tr>
                                                    <th class="align-middle px-2 table-header" scope="col">1.1.2</th>
                                                    <th class="align-middle px-3 table-header" scope="col">Check Profile Activity</th>
                                                    <th class="align-middle px-3 table-header" scope="col"></th>
                                                    <th class="align-middle px-3 table-header" scope="col"></th>
                                                    <th class="align-middle px-3 table-header" scope="col"></th>
                                                </tr>
                                            </thead>

                                            <thead style="background-color: #E4E4E4;">
                                                <tr>
                                                    <th class="align-middle px-3 table-header" scope="col">1.1.2.1</th>
                                                    <th class="align-middle px-3 table-header" scope="col">Die Crack</th>
                                                    <th class="align-middle px-3 table-header" scope="col"></th>
                                                    <th class="align-middle px-3 table-header" scope="col"></th>
                                                    <th class="align-middle px-3 table-header" scope="col"></th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <tr>
                                                    <td class="align-middle px-4 table-item">1.1.2.1.1</td>
                                                    <td class="align-middle px-3 table-item">Fix</td>
                                                    <td class="align-middle px-3 table-item">
                                                        <input class="" name="c11211" <?= (($data["data"]["c11211"] == "1")) ? "checked" : ''; ?> type="checkbox" data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success" data-offstyle="danger" data-size="mini" data-width="120">
                                                    </td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                </tr>
                                                <tr>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                </tr>
                                                <tr>
                                                    <td class="align-middle px-4 table-item">1.1.2.1.2</td>
                                                    <td class="align-middle px-3 table-item">Move</td>
                                                    <td class="align-middle px-3 table-item">
                                                        <input class="" name="c11212" <?= (($data["data"]["c11212"] == "1")) ? "checked" : ''; ?> type="checkbox" data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success" data-offstyle="danger" data-size="mini" data-width="120">
                                                    </td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                </tr>
                                                <tr>
                                                    <td class="align-middle px-4 table-item">1.1.2.1.3</td>
                                                    <td class="align-middle px-3 table-item">Slider</td>
                                                    <td class="align-middle px-3 table-item">
                                                        <input class="" name="c11213" <?= (($data["data"]["c11213"] == "1")) ? "checked" : ''; ?> type="checkbox" data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success" data-offstyle="danger" data-size="mini" data-width="120">
                                                    </td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                </tr>
                                                <tr>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item">
                                                        <div>
                                                            <input type="checkbox" name="c11213_c1" <?= (($data["data"]["c11213_c1"] == "1")) ? "checked" : ''; ?>>
                                                            <label class="checkbox-table"><span></span>C1</label>
                                                            <input type="checkbox" name="c11213_c2" <?= (($data["data"]["c11213_c2"] == "1")) ? "checked" : ''; ?>>
                                                            <label class="checkbox-table"><span></span>C2</label>
                                                            <input type="checkbox" name="c11213_c3" <?= (($data["data"]["c11213_c3"] == "1")) ? "checked" : ''; ?>>
                                                            <label class="checkbox-table"><span></span>C3</label>
                                                            <input type="checkbox" name="c11213_c4" <?= (($data["data"]["c11213_c4"] == "1")) ? "checked" : ''; ?>>
                                                            <label class="checkbox-table"><span></span>C4</label>
                                                            <input type="checkbox" name="c11213_c5" <?= (($data["data"]["c11213_c5"] == "1")) ? "checked" : ''; ?>>
                                                            <label class="checkbox-table"><span></span>C5</label>
                                                        </div>
                                                    </td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                </tr>
                                            </tbody>

                                            <thead style="background-color: #E4E4E4;">
                                                <tr>
                                                    <th class="align-middle px-3 table-header" scope="col">1.1.2.2</th>
                                                    <th class="align-middle px-3 table-header" scope="col">Katakrute</th>
                                                    <th class="align-middle px-3 table-header" scope="col"></th>
                                                    <th class="align-middle px-3 table-header" scope="col"></th>
                                                    <th class="align-middle px-3 table-header" scope="col"></th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <tr>
                                                    <td class="align-middle px-4 table-item">1.1.2.2.1</td>
                                                    <td class="align-middle px-3 table-item">Fix</td>
                                                    <td class="align-middle px-3 table-item">
                                                        <input class="" name="c11221" <?= (($data["data"]["c11221"] == "1")) ? "checked" : ''; ?> type="checkbox" data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success" data-offstyle="danger" data-size="mini" data-width="120">
                                                    </td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                </tr>
                                                <tr>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                </tr>
                                                <tr>
                                                    <td class="align-middle px-4 table-item">1.1.2.2.2</td>
                                                    <td class="align-middle px-3 table-item">Move</td>
                                                    <td class="align-middle px-3 table-item">
                                                        <input class="" name="c11222" <?= (($data["data"]["c11222"] == "1")) ? "checked" : ''; ?> type="checkbox" data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success" data-offstyle="danger" data-size="mini" data-width="120">
                                                    </td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                </tr>
                                            </tbody>

                                            <thead style="background-color: #E4E4E4;">
                                                <tr>
                                                    <th class="align-middle px-3 table-header" scope="col">1.1.2.3</th>
                                                    <th class="align-middle px-3 table-header" scope="col">Yakitsuki</th>
                                                    <th class="align-middle px-3 table-header" scope="col"></th>
                                                    <th class="align-middle px-3 table-header" scope="col"></th>
                                                    <th class="align-middle px-3 table-header" scope="col"></th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <tr>
                                                    <td class="align-middle px-4 table-item">1.1.2.3.1</td>
                                                    <td class="align-middle px-3 table-item">Fix</td>
                                                    <td class="align-middle px-3 table-item">
                                                        <input class="" name="c11231" <?= (($data["data"]["c11231"] == "1")) ? "checked" : ''; ?> type="checkbox" data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success" data-offstyle="danger" data-size="mini" data-width="120">
                                                    </td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                </tr>
                                                <tr>
                                                    <td class="align-middle px-4 table-item">1.1.2.3.2</td>
                                                    <td class="align-middle px-3 table-item">Move</td>
                                                    <td class="align-middle px-3 table-item">
                                                        <input class="" name="c11232" <?= (($data["data"]["c11232"] == "1")) ? "checked" : ''; ?> type="checkbox" data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success" data-offstyle="danger" data-size="mini" data-width="120">
                                                    </td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                </tr>
                                                <tr>
                                                    <td class="align-middle px-4 table-item">1.1.2.3.3</td>
                                                    <td class="align-middle px-3 table-item">Slider</td>
                                                    <td class="align-middle px-3 table-item">
                                                        <input class="" name="c11233" <?= (($data["data"]["c11233"] == "1")) ? "checked" : ''; ?> type="checkbox" data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success" data-offstyle="danger" data-size="mini" data-width="120">
                                                    </td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                </tr>
                                            </tbody>

                                            <thead style="background-color: #E4E4E4;">
                                                <tr>
                                                    <th class="align-middle px-3 table-header" scope="col">1.1.2.4</th>
                                                    <th class="align-middle px-3 table-header" scope="col">Check Parting Line</th>
                                                    <th class="align-middle px-3 table-header" scope="col"></th>
                                                    <th class="align-middle px-3 table-header" scope="col"></th>
                                                    <th class="align-middle px-3 table-header" scope="col"></th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <tr>
                                                    <td class="align-middle px-4 table-item">1.1.2.4.1</td>
                                                    <td class="align-middle px-3 table-item">Fix</td>
                                                    <td class="align-middle px-3 table-item">
                                                        <input class="" name="c11241" <?= (($data["data"]["c11241"] == "1")) ? "checked" : ''; ?> type="checkbox" data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success" data-offstyle="danger" data-size="mini" data-width="120">
                                                    </td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                </tr>
                                                <tr>
                                                    <td class="align-middle px-4 table-item">1.1.2.4.2</td>
                                                    <td class="align-middle px-3 table-item">Move</td>
                                                    <td class="align-middle px-3 table-item">
                                                        <input class="" name="c11242" <?= (($data["data"]["c11242"] == "1")) ? "checked" : ''; ?> type="checkbox" data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success" data-offstyle="danger" data-size="mini" data-width="120">
                                                    </td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                </tr>
                                                <tr>
                                                    <td class="align-middle px-4 table-item">1.1.2.4.3</td>
                                                    <td class="align-middle px-3 table-item">Slider</td>
                                                    <td class="align-middle px-3 table-item">
                                                        <input class="" name="c11243" <?= (($data["data"]["c11243"] == "1")) ? "checked" : ''; ?> type="checkbox" data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success" data-offstyle="danger" data-size="mini" data-width="120">
                                                    </td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                </tr>
                                                <tr>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item">
                                                        <div>
                                                            <input type="checkbox" name="c11243_c1" <?= (($data["data"]["c11243_c1"] == "1")) ? "checked" : ''; ?>>
                                                            <label class="checkbox-table"><span></span>C1</label>
                                                            <input type="checkbox" name="c11243_c2" <?= (($data["data"]["c11243_c2"] == "1")) ? "checked" : ''; ?>>
                                                            <label class="checkbox-table"><span></span>C2</label>
                                                            <input type="checkbox" name="c11243_c3" <?= (($data["data"]["c11243_c3"] == "1")) ? "checked" : ''; ?>>
                                                            <label class="checkbox-table"><span></span>C3</label>
                                                            <input type="checkbox" name="c11243_c4" <?= (($data["data"]["c11243_c4"] == "1")) ? "checked" : ''; ?>>
                                                            <label class="checkbox-table"><span></span>C4</label>
                                                            <input type="checkbox" name="c11243_c5" <?= (($data["data"]["c11243_c5"] == "1")) ? "checked" : ''; ?>>
                                                            <label class="checkbox-table"><span></span>C5</label>
                                                        </div>
                                                    </td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                </tr>
                                            </tbody>

                                            <thead style="background-color: #E4E4E4;">
                                                <tr>
                                                    <th class="align-middle px-3 table-header" scope="col">1.1.2.5</th>
                                                    <th class="align-middle px-3 table-header" scope="col">Check V-Notch</th>
                                                    <th class="align-middle px-3 table-header" scope="col"></th>
                                                    <th class="align-middle px-3 table-header" scope="col"></th>
                                                    <th class="align-middle px-3 table-header" scope="col"></th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <tr>
                                                    <td class="align-middle px-4 table-item">1.1.2.5.1</td>
                                                    <td class="align-middle px-3 table-item">Fix</td>
                                                    <td class="align-middle px-3 table-item">
                                                        <input class="" name="c11251" <?= (($data["data"]["c11251"] == "1")) ? "checked" : ''; ?> type="checkbox" data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success" data-offstyle="danger" data-size="mini" data-width="120">
                                                    </td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                </tr>
                                                <tr>
                                                    <td class="align-middle px-4 table-item">1.1.2.5.2</td>
                                                    <td class="align-middle px-3 table-item">Move</td>
                                                    <td class="align-middle px-3 table-item">
                                                        <input class="" name="c11252" <?= (($data["data"]["c11252"] == "1")) ? "checked" : ''; ?> type="checkbox" data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success" data-offstyle="danger" data-size="mini" data-width="120">
                                                    </td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                </tr>
                                            </tbody>

                                            <thead style="background-color: #E4E4E4;">
                                                <tr>
                                                    <th class="align-middle px-2 table-header" scope="col">1.1.3</th>
                                                    <th class="align-middle px-3 table-header" scope="col">Check Vacuum</th>
                                                    <th class="align-middle px-3 table-header" scope="col"></th>
                                                    <th class="align-middle px-3 table-header" scope="col"></th>
                                                    <th class="align-middle px-3 table-header" scope="col"></th>
                                                </tr>
                                            </thead>

                                            <thead style="background-color: #E4E4E4;">
                                                <tr>
                                                    <th class="align-middle px-3 table-header" scope="col">1.1.3.1</th>
                                                    <th class="align-middle px-3 table-header" scope="col">Vacuum Component</th>
                                                    <th class="align-middle px-3 table-header" scope="col"></th>
                                                    <th class="align-middle px-3 table-header" scope="col"></th>
                                                    <th class="align-middle px-3 table-header" scope="col"></th>
                                                </tr>
                                            </thead>

                                            <?php

                                            $display = "";
                                            if ($data["data"]["group_id"] == "CSH") {
                                                $display = "hidden";
                                            }
                                            ?>
                                            <tbody>
                                                <tr>
                                                    <td class="align-middle px-4 table-item">1.1.3.1.1</td>
                                                    <td class="align-middle px-3 table-item">Cleaning Block Vacuum</td>
                                                    <td class="align-middle px-3 table-item <?= $display ?>">
                                                        <input class="" name="c11311" <?= (($data["data"]["c11311"] == "1")) ? "checked" : ''; ?> type="checkbox" data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success" data-offstyle="danger" data-size="mini" data-width="120">
                                                    </td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                </tr>
                                                <tr>
                                                    <td class="align-middle px-4 table-item">1.1.3.1.2</td>
                                                    <td class="align-middle px-3 table-item">Cleaning Piston Vacuum</td>
                                                    <td class="align-middle px-3 table-item <?= $display ?>">
                                                        <input class="" name="c11312" <?= (($data["data"]["c11312"] == "1")) ? "checked" : ''; ?> type="checkbox" data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success" data-offstyle="danger" data-size="mini" data-width="120">
                                                    </td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                </tr>
                                                <tr>
                                                    <td class="align-middle px-4 table-item">1.1.3.1.3</td>
                                                    <td class="align-middle px-3 table-item">Cleaning Pipa Vacuum</td>
                                                    <td class="align-middle px-3 table-item <?= $display ?>">
                                                        <input class="" name="c11313" <?= (($data["data"]["c11313"] == "1")) ? "checked" : ''; ?> type="checkbox" data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success" data-offstyle="danger" data-size="mini" data-width="120">
                                                    </td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                </tr>
                                                <tr>
                                                    <td class="align-middle px-4 table-item">1.1.3.1.4</td>
                                                    <td class="align-middle px-3 table-item">Fitting Check Piston Vacuum</td>
                                                    <td class="align-middle px-3 table-item <?= $display ?>">
                                                        <input class="" name="c11314" <?= (($data["data"]["c11314"] == "1")) ? "checked" : ''; ?> type="checkbox" data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success" data-offstyle="danger" data-size="mini" data-width="120">
                                                    </td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                </tr>
                                                <tr>
                                                    <td class="align-middle px-4 table-item">1.1.3.1.5</td>
                                                    <td class="align-middle px-3 table-item">Ganti O-Ring Piston Vacuum</td>
                                                    <td class="align-middle px-3 table-item <?= $display ?>">
                                                        <input class="" name="c11315" <?= (($data["data"]["c11315"] == "1")) ? "checked" : ''; ?> type="checkbox" data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success" data-offstyle="danger" data-size="mini" data-width="120">
                                                    </td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                </tr>
                                                <tr>
                                                    <td class="align-middle px-4 table-item">1.1.3.1.6</td>
                                                    <td class="align-middle px-3 table-item">Ganti Hose Vacuum</td>
                                                    <td class="align-middle px-3 table-item <?= $display ?>">
                                                        <input class="" name="c11316" <?= (($data["data"]["c11316"] == "1")) ? "checked" : ''; ?> type="checkbox" data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success" data-offstyle="danger" data-size="mini" data-width="120">
                                                    </td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                </tr>
                                            </tbody>

                                            <thead style="background-color: #E4E4E4;">
                                                <tr>
                                                    <th class="align-middle px-3 table-header" scope="col">1.1.3.2</th>
                                                    <th class="align-middle px-3 table-header" scope="col">Vacuum Final Check</th>
                                                    <th class="align-middle px-3 table-header" scope="col"></th>
                                                    <th class="align-middle px-3 table-header" scope="col"></th>
                                                    <th class="align-middle px-3 table-header" scope="col"></th>
                                                </tr>
                                            </thead>

                                            <thead style="background-color: #E4E4E4;">
                                                <tr>
                                                    <th class="align-middle px-4 table-header" scope="col">1.1.3.2.1</th>
                                                    <th class="align-middle px-3 table-header" scope="col">Vacuum Valve Indicator</th>
                                                    <th class="align-middle px-3 table-header" scope="col"></th>
                                                    <th class="align-middle px-3 table-header" scope="col"></th>
                                                    <th class="align-middle px-3 table-header" scope="col"></th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <tr>
                                                    <td class="align-middle px-5 table-item">1.1.3.2.1.1</td>
                                                    <td class="align-middle px-3 table-item">Valve Open</td>
                                                    <td class="align-middle px-3 table-item <?= $display ?>">
                                                        <input class="" name="c113211" <?= (($data["data"]["c113211"] == "1")) ? "checked" : ''; ?> type="checkbox" data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success" data-offstyle="danger" data-size="mini" data-width="120">
                                                    </td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                </tr>
                                                <tr>
                                                    <td class="align-middle px-5 table-item">1.1.3.2.1.2</td>
                                                    <td class="align-middle px-3 table-item">Valve Close</td>
                                                    <td class="align-middle px-3 table-item <?= $display ?>">
                                                        <input class="" name="c113212" <?= (($data["data"]["c113212"] == "1")) ? "checked" : ''; ?> type="checkbox" data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success" data-offstyle="danger" data-size="mini" data-width="120">
                                                    </td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                </tr>
                                                <tr>
                                                    <td class="align-middle px-4 table-item">1.1.3.2.2</td>
                                                    <td class="align-middle px-3 table-item">Valve Operation Time</td>
                                                    <td class="align-middle px-3 table-item <?= $display ?>">
                                                        <input class="" name="c11322" <?= (($data["data"]["c11322"] == "1")) ? "checked" : ''; ?> type="checkbox" data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success" data-offstyle="danger" data-size="mini" data-width="120">
                                                    </td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                </tr>
                                                <tr>
                                                    <td class="align-middle px-4 table-item">1.1.3.2.3</td>
                                                    <td class="align-middle px-3 table-item">Valve Stroke</td>
                                                    <td class="align-middle px-3 table-item <?= $display ?>">
                                                        <input class="" name="c11323" <?= (($data["data"]["c11323"] == "1")) ? "checked" : ''; ?> type="checkbox" data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success" data-offstyle="danger" data-size="mini" data-width="120">
                                                    </td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                </tr>
                                            </tbody>

                                            <thead style="background-color: #E4E4E4;">
                                                <tr>
                                                    <th class="align-middle px-2 table-header" scope="col">1.1.4</th>
                                                    <th class="align-middle px-3 table-header" scope="col">MTBF Core Pin</th>
                                                    <th class="align-middle px-3 table-header" scope="col"></th>
                                                    <th class="align-middle px-3 table-header" scope="col"></th>
                                                    <th class="align-middle px-3 table-header" scope="col"></th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <tr>
                                                    <td class="align-middle px-3 table-item">1.1.4.1</td>
                                                    <td class="align-middle px-3 table-item">Fix</td>
                                                    <td class="align-middle px-3 table-item">
                                                        <input class="" name="c1141" <?= (($data["data"]["c1141"] == "1")) ? "checked" : ''; ?> type="checkbox" data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success" data-offstyle="danger" data-size="mini" data-width="120">
                                                    </td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                </tr>
                                                <tr>
                                                    <td class="align-middle px-3 table-item">1.1.4.2</td>
                                                    <td class="align-middle px-3 table-item">Move</td>
                                                    <td class="align-middle px-3 table-item">
                                                        <input class="" name="c1142" <?= (($data["data"]["c1142"] == "1")) ? "checked" : ''; ?> type="checkbox" data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success" data-offstyle="danger" data-size="mini" data-width="120">
                                                    </td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                </tr>
                                                <tr>
                                                    <td class="align-middle px-3 table-item">1.1.4.3</td>
                                                    <td class="align-middle px-3 table-item">Slider</td>
                                                    <td class="align-middle px-3 table-item">
                                                        <input class="" name="c1143" <?= (($data["data"]["c1143"] == "1")) ? "checked" : ''; ?> type="checkbox" data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success" data-offstyle="danger" data-size="mini" data-width="120">
                                                    </td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                </tr>
                                                <tr>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item">
                                                        <div>
                                                            <input type="checkbox" name="c1143_c1" <?= (($data["data"]["c1143_c1"] == "1")) ? "checked" : ''; ?>>
                                                            <label class="checkbox-table"><span></span>C1</label>
                                                            <input type="checkbox" name="c1143_c2" <?= (($data["data"]["c1143_c2"] == "1")) ? "checked" : ''; ?>>
                                                            <label class="checkbox-table"><span></span>C2</label>
                                                            <input type="checkbox" name="c1143_c3" <?= (($data["data"]["c1143_c3"] == "1")) ? "checked" : ''; ?>>
                                                            <label class="checkbox-table"><span></span>C3</label>
                                                            <input type="checkbox" name="c1143_c4" <?= (($data["data"]["c1143_c4"] == "1")) ? "checked" : ''; ?>>
                                                            <label class="checkbox-table"><span></span>C4</label>
                                                            <input type="checkbox" name="c1143_c5" <?= (($data["data"]["c1143_c5"] == "1")) ? "checked" : ''; ?>>
                                                            <label class="checkbox-table"><span></span>C5</label>
                                                        </div>
                                                    </td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                </tr>
                                            </tbody>

                                            <thead style="background-color: #E4E4E4;">
                                                <tr>
                                                    <th class="align-middle px-2 table-header" scope="col">1.1.5</th>
                                                    <th class="align-middle px-3 table-header" scope="col">MTBF Core Pin</th>
                                                    <th class="align-middle px-3 table-header" scope="col"></th>
                                                    <th class="align-middle px-3 table-header" scope="col"></th>
                                                    <th class="align-middle px-3 table-header" scope="col"></th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <tr>
                                                    <td class="align-middle px-3 table-item">1.1.5.1</td>
                                                    <td class="align-middle px-3 table-item">Fix</td>
                                                    <td class="align-middle px-3 table-item">
                                                        <input class="" name="c1151" <?= (($data["data"]["c1151"] == "1")) ? "checked" : ''; ?> type="checkbox" data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success" data-offstyle="danger" data-size="mini" data-width="120">
                                                    </td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                </tr>
                                                <tr>
                                                    <td class="align-middle px-3 table-item">1.1.5.2</td>
                                                    <td class="align-middle px-3 table-item">Move</td>
                                                    <td class="align-middle px-3 table-item">
                                                        <input class="" name="c1152" <?= (($data["data"]["c1152"] == "1")) ? "checked" : ''; ?> type="checkbox" data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success" data-offstyle="danger" data-size="mini" data-width="120">
                                                    </td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                </tr>
                                                <tr>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item">
                                                        <div>
                                                            <input type="checkbox" name="c1152_c1" <?= (($data["data"]["c1152_c1"] == "1")) ? "checked" : ''; ?>>
                                                            <label class="checkbox-table"><span></span>C1</label>
                                                            <input type="checkbox" name="c1152_c2" <?= (($data["data"]["c1152_c2"] == "1")) ? "checked" : ''; ?>>
                                                            <label class="checkbox-table"><span></span>C2</label>
                                                            <input type="checkbox" name="c1152_c3" <?= (($data["data"]["c1152_c3"] == "1")) ? "checked" : ''; ?>>
                                                            <label class="checkbox-table"><span></span>C3</label>
                                                            <input type="checkbox" name="c1152_c4" <?= (($data["data"]["c1152_c4"] == "1")) ? "checked" : ''; ?>>
                                                            <label class="checkbox-table"><span></span>C4</label>
                                                            <input type="checkbox" name="c1152_c5" <?= (($data["data"]["c1152_c5"] == "1")) ? "checked" : ''; ?>>
                                                            <label class="checkbox-table"><span></span>C5</label>
                                                        </div>
                                                    </td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                </tr>
                                                <tr>
                                                    <td class="align-middle px-3 table-item">1.1.5.3</td>
                                                    <td class="align-middle px-3 table-item">Slider</td>
                                                    <td class="align-middle px-3 table-item">
                                                        <input class="" name="c1153" <?= (($data["data"]["c1153"] == "1")) ? "checked" : ''; ?> type="checkbox" data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success" data-offstyle="danger" data-size="mini" data-width="120">
                                                    </td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                </tr>
                                            </tbody>

                                            <thead style="background-color: #E4E4E4;">
                                                <tr>
                                                    <th class="align-middle px-2 table-header" scope="col">1.1.6</th>
                                                    <th class="align-middle px-3 table-header" scope="col">Check Flow</th>
                                                    <th class="align-middle px-3 table-header" scope="col"></th>
                                                    <th class="align-middle px-3 table-header" scope="col"></th>
                                                    <th class="align-middle px-3 table-header" scope="col"></th>
                                                </tr>
                                            </thead>

                                            <thead style="background-color: #E4E4E4;">
                                                <tr>
                                                    <th class="align-middle px-3 table-header" scope="col">1.1.6.1</th>
                                                    <th class="align-middle px-3 table-header" scope="col">Check Flow Power Cool</th>
                                                    <th class="align-middle px-3 table-header" scope="col">
                                                        <input type="file" accept="image/png,image/jpg" name="c1161" id="upload-image" />
                                                        <input type="hidden" name="c1161_x" value="<?= $data["data"]["c1161"]; ?>">
                                                    </th>
                                                    <th class="align-middle px-3 table-header" scope="col"><?= (!empty($data["data"]["c1161"])) ? "<a download='file_check_flow_power_cool.jpg' href='data:image/jpg;base64," . $data["data"]["c1161"] . "'>Download File</a>" : "" ?></th>
                                                    <th class="align-middle px-3 table-header" scope="col"></th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <tr>
                                                    <td class="align-middle px-4 table-item">1.1.6.1.1</td>
                                                    <td class="align-middle px-3 table-item">Fix</td>
                                                    <td class="align-middle px-3 table-item">
                                                        <input class="" name="c11611" <?= (($data["data"]["c11611"] == "1")) ? "checked" : ''; ?> type="checkbox" data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success" data-offstyle="danger" data-size="mini" data-width="120">
                                                    </td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                </tr>
                                                <tr>
                                                    <td class="align-middle px-4 table-item">1.1.6.1.2</td>
                                                    <td class="align-middle px-3 table-item">Move</td>
                                                    <td class="align-middle px-3 table-item">
                                                        <input class="" name="c11612" <?= (($data["data"]["c11612"] == "1")) ? "checked" : ''; ?> type="checkbox" data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success" data-offstyle="danger" data-size="mini" data-width="120">
                                                    </td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                </tr>
                                            </tbody>

                                            <thead style="background-color: #E4E4E4;">
                                                <tr>
                                                    <th class="align-middle px-3 table-header" scope="col">1.1.6.2</th>
                                                    <th class="align-middle px-3 table-header" scope="col">Check Flow Main Cool</th>
                                                    <th class="align-middle px-3 table-header" scope="col">
                                                        <input type="file" accept="image/png,image/jpg" name="c1162" id="upload-image" />
                                                        <input type="hidden" name="c1162_x" value="<?= $data["data"]["c1162"]; ?>">
                                                    </th>
                                                    <th class="align-middle px-3 table-header" scope="col"><?= (!empty($data["data"]["c1162"])) ? "<a download='file_check_flow_main_cool.jpg' href='data:image/jpg;base64," . $data["data"]["c1162"] . "'>Download File</a>" : "" ?></th>
                                                    <th class="align-middle px-3 table-header" scope="col"></th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <tr>
                                                    <td class="align-middle px-4 table-item">1.1.6.2.1</td>
                                                    <td class="align-middle px-3 table-item">Fix</td>
                                                    <td class="align-middle px-3 table-item">
                                                        <input class="" name="c11621" <?= (($data["data"]["c11621"] == "1")) ? "checked" : ''; ?> type="checkbox" data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success" data-offstyle="danger" data-size="mini" data-width="120">
                                                    </td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                </tr>
                                                <tr>
                                                    <td class="align-middle px-4 table-item">1.1.6.2.2</td>
                                                    <td class="align-middle px-3 table-item">Move</td>
                                                    <td class="align-middle px-3 table-item">
                                                        <input class="" name="c11622" <?= (($data["data"]["c11622"] == "1")) ? "checked" : ''; ?> type="checkbox" data-toggle="toggle" data-on="Completed" data-off="On Progress" data-onstyle="success" data-offstyle="danger" data-size="mini" data-width="120">
                                                    </td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                </tr>
                                            </tbody>

                                            <thead style="background-color: #E4E4E4;">
                                                <tr>
                                                    <th class="align-middle px-2 table-header" scope="col">1.1.7</th>
                                                    <th class="align-middle px-3 table-header" scope="col">Check Ejector</th>
                                                    <th class="align-middle px-3 table-header" scope="col">
                                                        <input class="" name="c117" <?= (($data["data"]["c117"] == "1")) ? "checked" : ''; ?> type="checkbox" data-toggle="toggle" data-on="Good" data-off="Not Good" data-onstyle="success" data-offstyle="danger" data-size="mini" data-width="120">
                                                    </th>
                                                    <th class="align-middle px-3 table-header" scope="col"></th>
                                                    <th class="align-middle px-3 table-header" scope="col"></th>
                                                </tr>
                                            </thead>

                                            <thead style="background-color: #E4E4E4;">
                                                <tr>
                                                    <th class="align-middle px-2 table-header" scope="col">1.1.8</th>
                                                    <th class="align-middle px-3 table-header" scope="col">Check Hydraulic Core</th>
                                                    <th class="align-middle px-3 table-header" scope="col"></th>
                                                    <th class="align-middle px-3 table-header" scope="col"></th>
                                                    <th class="align-middle px-3 table-header" scope="col"></th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <tr>
                                                    <td class="align-middle px-3 table-item">1.1.8.1</td>
                                                    <td class="align-middle px-3 table-item">Slider C1</td>
                                                    <td class="align-middle px-3 table-item">
                                                        <input class="" name="c1181" <?= (($data["data"]["c1181"] == "1")) ? "checked" : ''; ?> type="checkbox" data-toggle="toggle" data-on="Good" data-off="Not Good" data-onstyle="success" data-offstyle="danger" data-size="mini" data-width="120">
                                                    </td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                </tr>
                                                <tr>
                                                    <td class="align-middle px-3 table-item">1.1.8.2</td>
                                                    <td class="align-middle px-3 table-item">Slider C2</td>
                                                    <td class="align-middle px-3 table-item">
                                                        <input class="" name="c1182" <?= (($data["data"]["c1182"] == "1")) ? "checked" : ''; ?> type="checkbox" data-toggle="toggle" data-on="Good" data-off="Not Good" data-onstyle="success" data-offstyle="danger" data-size="mini" data-width="120">
                                                    </td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                </tr>
                                                <tr>
                                                    <td class="align-middle px-3 table-item">1.1.8.3</td>
                                                    <td class="align-middle px-3 table-item">Slider C3</td>
                                                    <td class="align-middle px-3 table-item">
                                                        <input class="" name="c1183" <?= (($data["data"]["c1183"] == "1")) ? "checked" : ''; ?> type="checkbox" data-toggle="toggle" data-on="Good" data-off="Not Good" data-onstyle="success" data-offstyle="danger" data-size="mini" data-width="120">
                                                    </td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                </tr>
                                                <tr>
                                                    <td class="align-middle px-3 table-item">1.1.8.4</td>
                                                    <td class="align-middle px-3 table-item">Slider C4</td>
                                                    <td class="align-middle px-3 table-item">
                                                        <input class="" name="c1184" <?= (($data["data"]["c1184"] == "1")) ? "checked" : ''; ?> type="checkbox" data-toggle="toggle" data-on="Good" data-off="Not Good" data-onstyle="success" data-offstyle="danger" data-size="mini" data-width="120">
                                                    </td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                </tr>
                                                <tr>
                                                    <td class="align-middle px-3 table-item">1.1.8.5</td>
                                                    <td class="align-middle px-3 table-item">Slider C5</td>
                                                    <td class="align-middle px-3 table-item">
                                                        <input class="" name="c1185" <?= (($data["data"]["c1185"] == "1")) ? "checked" : ''; ?> type="checkbox" data-toggle="toggle" data-on="Good" data-off="Not Good" data-onstyle="success" data-offstyle="danger" data-size="mini" data-width="120">
                                                    </td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                </tr>
                                            </tbody>

                                            <thead style="background-color: #E4E4E4;">
                                                <tr>
                                                    <th class="align-middle px-2 table-header" scope="col">1.1.9</th>
                                                    <th class="align-middle px-3 table-header" scope="col">Check Bocor</th>
                                                    <th class="align-middle px-3 table-header" scope="col">
                                                        <input type="file" accept="image/png,image/jpg" name="c119" />
                                                        <input type="hidden" name="c119_x" value="<?= $data["data"]["c119"]; ?>" />
                                                    </th>
                                                    <th class="align-middle px-3 table-header" scope="col"><?= (!empty($data["data"]["c119"])) ? "<a download='file_check_bocor.jpg' href='data:image/jpg;base64," . $data["data"]["c119"] . "'>Download File</a>" : "" ?></th>
                                                    <th class="align-middle px-3 table-header" scope="col"></th>
                                                </tr>
                                            </thead>

                                            <thead style="background-color: #E4E4E4;">
                                                <tr>
                                                    <th class="align-middle px-3 table-header" scope="col">1.1.9.1</th>
                                                    <th class="align-middle px-3 table-header" scope="col">Fix</th>
                                                    <th class="align-middle px-3 table-header" scope="col"></th>
                                                    <th class="align-middle px-3 table-header" scope="col"></th>
                                                    <th class="align-middle px-3 table-header" scope="col"></th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <tr>
                                                    <td class="align-middle px-4 table-item">1.1.9.1.1</td>
                                                    <td class="align-middle px-3 table-item">Power Cool Fix 1</td>
                                                    <td class="align-middle px-3 table-item">
                                                        <input class="" name="c11911" <?= (($data["data"]["c11911"] == "1")) ? "checked" : ''; ?> type="checkbox" data-toggle="toggle" data-on="Good" data-off="Not Good" data-onstyle="success" data-offstyle="danger" data-size="mini" data-width="120">
                                                    </td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                </tr>
                                                <tr>
                                                    <td class="align-middle px-4 table-item">1.1.9.1.2</td>
                                                    <td class="align-middle px-3 table-item">Power Cool Fix 2</td>
                                                    <td class="align-middle px-3 table-item">
                                                        <input class="" name="c11912" <?= (($data["data"]["c11912"] == "1")) ? "checked" : ''; ?> type="checkbox" data-toggle="toggle" data-on="Good" data-off="Not Good" data-onstyle="success" data-offstyle="danger" data-size="mini" data-width="120">
                                                    </td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                </tr>
                                                <tr>
                                                    <td class="align-middle px-4 table-item">1.1.9.1.3</td>
                                                    <td class="align-middle px-3 table-item">Main Cool Fix</td>
                                                    <td class="align-middle px-3 table-item">
                                                        <input class="" name="c11913" <?= (($data["data"]["c11913"] == "1")) ? "checked" : ''; ?> type="checkbox" data-toggle="toggle" data-on="Good" data-off="Not Good" data-onstyle="success" data-offstyle="danger" data-size="mini" data-width="120">
                                                    </td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                </tr>
                                                <tr>
                                                    <td class="align-middle px-4 table-item">1.1.9.1.4</td>
                                                    <td class="align-middle px-3 table-item">Sprue Bush</td>
                                                    <td class="align-middle px-3 table-item">
                                                        <input class="" name="c11914" <?= (($data["data"]["c11914"] == "1")) ? "checked" : ''; ?> type="checkbox" data-toggle="toggle" data-on="Good" data-off="Not Good" data-onstyle="success" data-offstyle="danger" data-size="mini" data-width="120">
                                                    </td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                </tr>
                                            </tbody>

                                            <thead style="background-color: #E4E4E4;">
                                                <tr>
                                                    <th class="align-middle px-3 table-header" scope="col">1.1.9.2</th>
                                                    <th class="align-middle px-3 table-header" scope="col">Move</th>
                                                    <th class="align-middle px-3 table-header" scope="col"></th>
                                                    <th class="align-middle px-3 table-header" scope="col"></th>
                                                    <th class="align-middle px-3 table-header" scope="col"></th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <tr>
                                                    <td class="align-middle px-4 table-item">1.1.9.2.1</td>
                                                    <td class="align-middle px-3 table-item">Power Cool Move 1</td>
                                                    <td class="align-middle px-3 table-item">
                                                        <input class="" name="c11921" <?= (($data["data"]["c11921"] == "1")) ? "checked" : ''; ?> type="checkbox" data-toggle="toggle" data-on="Good" data-off="Not Good" data-onstyle="success" data-offstyle="danger" data-size="mini" data-width="120">
                                                    </td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                </tr>
                                                <tr>
                                                    <td class="align-middle px-4 table-item">1.1.9.2.2</td>
                                                    <td class="align-middle px-3 table-item">Power Cool Move 2</td>
                                                    <td class="align-middle px-3 table-item">
                                                        <input class="" name="c11922" <?= (($data["data"]["c11922"] == "1")) ? "checked" : ''; ?> type="checkbox" data-toggle="toggle" data-on="Good" data-off="Not Good" data-onstyle="success" data-offstyle="danger" data-size="mini" data-width="120">
                                                    </td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                </tr>
                                                <tr>
                                                    <td class="align-middle px-4 table-item">1.1.9.2.3</td>
                                                    <td class="align-middle px-3 table-item">Main Cool Move</td>
                                                    <td class="align-middle px-3 table-item">
                                                        <input class="" name="c11923" <?= (($data["data"]["c11923"] == "1")) ? "checked" : ''; ?> type="checkbox" data-toggle="toggle" data-on="Good" data-off="Not Good" data-onstyle="success" data-offstyle="danger" data-size="mini" data-width="120">
                                                    </td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                </tr>
                                                <tr>
                                                    <td class="align-middle px-4 table-item">1.1.9.2.4</td>
                                                    <td class="align-middle px-3 table-item">Sprue Core</td>
                                                    <td class="align-middle px-3 table-item">
                                                        <input class="" name="c11924" <?= (($data["data"]["c11924"] == "1")) ? "checked" : ''; ?> type="checkbox" data-toggle="toggle" data-on="Good" data-off="Not Good" data-onstyle="success" data-offstyle="danger" data-size="mini" data-width="120">
                                                    </td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                    <td class="align-middle px-3 table-item"></td>
                                                </tr>
                                            </tbody>
                                        </table>
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
    <?php include 'common/t_js.php'; ?>
    <script src="vendors/ega/js/scripts.js?time=<?php echo date("Ymdhis"); ?>" type="text/javascript"></script>
    <script>
        $(document).ready(function() {
            $(':checkbox, :input').prop('disabled', true);
        });

        $(".datepicker").flatpickr({
            altInput: true,
            altFormat: "d-m-Y",
            dateFormat: "Ymd"
        });

        $("#btn-print").click(function() {
            var pmtid = $("#pmtid").val();
            alert("Print under construction, Preventive No : " + pmtid);
        });
    </script>
</body>

</html>