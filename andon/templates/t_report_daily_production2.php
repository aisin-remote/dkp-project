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

                    <div class="row mt-1">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header align-middle" style="background-color: #E4E4E4;">
                                    <div class="row mx-2">
                                        <div class="col-6">
                                            <h6 class="pt-2 mr-2">Header Information</h6>
                                        </div>
                                        <div class="col-6 py-1 d-flex justify-content-end">
                                            <a target="_blank"
                                                href="?action=<?= $action ?>&id=<?= $line_id ?>&id2=<?= str_replace("-", "", $prd_dt); ?>&id3=<?= $shift ?>&step=2&print=print"
                                                class="btn btn-pale-green btn-sm" id="print"><i
                                                    class="material-icons">download</i> Excel</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body" style="background-color: #F5F5F5;">
                                    <div class="row">
                                        <div class="col-md-3 col-sm-12">
                                            <div class="row">
                                                <div class="col-4">Line</div>
                                                <div class="col-8">:
                                                    <?php echo $data_header["line_name"]; ?>
                                                </div>
                                                <div class="col-4">Date</div>
                                                <div class="col-8">:
                                                    <?php echo $data_header["prod_date"]; ?>
                                                </div>
                                                <div class="col-4">Shift</div>
                                                <div class="col-8">:
                                                    <?php echo $data_header["shift_name"]; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-12">
                                            <div class="row">
                                                <div class="col-4">Leader</div>
                                                <div class="col-8">:
                                                    <?php echo $data_header["ld_name"]; ?>
                                                </div>
                                                <div class="col-4">JP</div>
                                                <div class="col-8" :>:
                                                    <?php echo $data_header["jp_name"]; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-12">
                                            <div class="row">
                                                <div class="col-4"><?= ($_GET["line"] == 'E14') ? 'POS 1' : 'Operator 1'; ?></div>
                                                <div class="col-8">:
                                                    <?php echo $data_header["op1_name"]; ?>
                                                </div>
                                                <div class="col-4"><?= ($_GET["line"] == 'E14') ? 'POS 2' : 'Operator 2'; ?></div>
                                                <div class="col-8">:
                                                    <?php echo $data_header["op2_name"]; ?>
                                                </div>
                                                <div class="col-4"><?= ($_GET["line"] == 'E14') ? 'POS 3' : 'Operator 3'; ?></div>
                                                <div class="col-8">:
                                                    <?php echo $data_header["op3_name"]; ?>
                                                </div>
                                                <div class="col-4"><?= ($_GET["line"] == 'E14') ? 'POS 4' : 'Operator 4'; ?></div>
                                                <div class="col-8">:
                                                    <?php echo $data_header["op4_name"]; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-12">
                                            <div class="row">
                                                <div class="col-4"><?= ($_GET["line"] == 'E14') ? 'POS 5' : 'Operator 5'; ?></div>
                                                <div class="col-8">:
                                                    <?php echo $data_header["op5_name"]; ?>
                                                </div>
                                                <div class="col-4"><?= ($_GET["line"] == 'E14') ? 'POS 6' : 'Operator 6'; ?></div>
                                                <div class="col-8">:
                                                    <?php echo $data_header["op6_name"]; ?>
                                                </div>
                                                <div class="col-4"><?= ($_GET["line"] == 'E14') ? 'POS 7' : 'Operator 7'; ?></div>
                                                <div class="col-8">:
                                                    <?php echo $data_header["op7_name"]; ?>
                                                </div>
                                                <div class="col-4"><?= ($_GET["line"] == 'E14') ? 'POS 8' : 'Operator 8'; ?></div>
                                                <div class="col-8">:
                                                    <?php echo $data_header["op8_name"]; ?>
                                                </div>
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
                                    <!-- Edit Here -->
                                    <table class="table table-striped table-sm" id="data-table">
                                        <thead>
                                            <tr>
                                                <th class="text-nowrap">Material</th>
                                                <th class="text-nowrap">Hour</th>
                                                <th class="text-nowrap">CT</th>
                                                <th class="text-nowrap">Plan Qty</th>
                                                <th class="text-nowrap">Prod Qty</th>
                                                <th class="text-nowrap">NG Qty</th>
                                                <th class="text-nowrap">WIP</th>
                                                <th class="text-nowrap">Loss Time (m)</th>
                                                <th class="text-nowrap">Prd Time (m)</th>
                                                <th class="text-nowrap">Efficiency (%)</th>
                                                <th class="text-nowrap">Apr. By</th>
                                                <th class="text-center text-nowrap">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (!empty($data["list"])) {
                                                foreach ($data["list"] as $list) {
                                                    if (!empty($list["backno"])) {
                                                        $backno = " (" . $list["backno"] . ")";
                                                      } else {
                                                        $backno = "";
                                                      }
                                                    echo "<tr>"
                                                        . "<td class='text-nowrap'>" . $list["name1"] . $backno . "</td>"
                                                        . "<td class='text-nowrap'>" . $list["time_start"] . " - " . $list["time_end"] . "</td>"
                                                        . "<td class='text-nowrap'>" . $list["cctime"] . "</td>"
                                                        . "<td class='text-nowrap'>" . $list["pln_qty"] . "</td>"
                                                        . "<td class='text-nowrap'>" . $list["prd_qty"] . "</td>"
                                                        . "<td class='text-nowrap'>" . $list["tot_ng"] . "</td>"
                                                        . "<td class='text-nowrap'>" . $list["wip"] . "</td>"
                                                        . "<td class='text-nowrap'>" . $list["loss_time"] . "</td>"
                                                        . "<td class='text-nowrap'>" . $list["prd_time"] . "</td>"
                                                        . "<td class='text-nowrap'>" . $list["eff"] . "</td>"
                                                        . "<td class='text-nowrap'>" . $list["apr_name"] . "</td>"
                                                        . "<td class='text-center pr-3'>"
                                                        . "<a href='?action=$action&id=" . $list["line_id"] . "&id2=" . $list["prd_dt"] . "&id3=" . $list["shift"] . "&id4=" . $list["prd_seq"] . "&step=detail" . "' class='btn btn-outline-dark btn-xs text-center mb-1'><i class='material-icons'>visibility</i> </a>"
                                                        . "</td>"
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
                    <div class="row details">
                        <div class="col-12">
                            <div class="card mt-2">
                                <div class="card-header" style="background-color: #E4E4E4;">
                                    <h6 class="mb-0">Summary</h6>
                                </div>
                                <div class="card-body px-0 py-0 pb-2" style="background-color: #F0F0F0;">
                                    <div class=" table-responsive">
                                        <!-- Edit Here -->
                                        <table class="table table-striped table-borderless table-responsive table-sm">
                                            <thead>
                                                <tr style="background-color: #F0F0F0;">
                                                    <th class="pl-4 text-dark text-right align-middle" colspan="14">ROL
                                                    </th>
                                                </tr>
                                                <tr style="background-color: #F0F0F0;">
                                                    <th class="pl-4 text-dark align-middle">Type</th>
                                                    <th class="pl-4 text-dark align-middle">Material</th>
                                                    <th class="pl-4 text-dark align-middle">Waktu Kerja/Shift</th>
                                                    <th class="pl-4 text-dark align-middle">Loss Time Terplanning</th>
                                                    <th class="pl-4 text-dark align-middle">Nett Operasi</th>
                                                    <th class="pl-4 text-dark align-middle">Losstime</th>
                                                    <th class="pl-4 text-dark align-middle">Qty Produksi</th>
                                                    <th class="pl-4 text-dark align-middle">Qty Lastman</th>
                                                    <th class="pl-4 text-dark align-middle text-center">RIL</th>
                                                    <th class="pl-4 text-dark align-middle text-center">CMM</th>
                                                    <th class="pl-4 text-dark align-middle text-center">TRIAL
                                                        MANUFACTURING</th>
                                                    <th class="pl-4 text-dark align-middle text-center">STEUCHI SETUP
                                                    </th>
                                                    <th class="pl-4 text-dark align-middle text-center">STEUCHI PROBLEM
                                                    </th>
                                                    <th class="pl-4 text-dark align-middle text-center">STEUCHI DANDORI
                                                    </th>
                                                    <th class="pl-4 text-dark align-middle text-center">LOT OUT</th>
                                                    <th class="pl-4 text-dark align-middle text-center">PRODUK JATUH
                                                    </th>
                                                    <th class="pl-4 text-dark align-middle text-center">PRODUK NUMPUK
                                                    </th>
                                                    <th class="pl-4 text-dark align-middle text-center">SAMPLE QC</th>
                                                    <th class="pl-4 text-dark align-middle text-center">KEKOTANSO
                                                    </th>
                                                    <th class="pl-4 text-dark align-middle">WIP</th>
                                                    <th class="pl-4 text-dark align-middle">Efficiency</th>
                                                    <th class="pl-4 text-dark align-middle">Losstime</th>
                                                    <th class="pl-4 text-dark align-middle">RIL</th>
                                                    <th class="pl-4 text-dark align-middle">ROL</th>
                                                    <th class="pl-4 text-dark align-middle">WIP (%)</th>
                                                    <th class="pl-4 text-dark align-middle">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (!empty($data2["list"])) {
                                                    foreach ($data2["list"] as $list) {
                                                        echo "<tr>"
                                                            . "<td class='pl-4 align-middle text-center'>" . $list["mtart"] . " " . $list["model_id"] . "</td>"
                                                            . "<td class='pl-4 align-middle text-center'>" . $list["name1"] . "</td>"
                                                            . "<td class='pl-4 align-middle text-center'>" . $list["waktu_shift"] . "</td>"
                                                            . "<td class='pl-4 align-middle text-center'>" . $list["loss_time_p"] . "</td>"
                                                            . "<td class='pl-4 align-middle text-center'>" . $list["prd_time"] . "</td>"
                                                            . "<td class='pl-4 align-middle text-center'>" . $list["loss_time"] . "</td>"
                                                            . "<td class='pl-4 align-middle text-center'>" . $list["tot_qty"] . "</td>"
                                                            . "<td class='pl-4 align-middle text-center'>" . $list["prd_qty"] . "</td>"
                                                            . "<td class='pl-4 align-middle text-center'>" . $list["ng_ril"] . "</td>"
                                                            . "<td class='pl-4 align-middle text-center'>" . $list["rol1"] . "</td>"
                                                            . "<td class='pl-4 align-middle text-center'>" . $list["rol2"] . "</td>"
                                                            . "<td class='pl-4 align-middle text-center'>" . $list["rol6"] . "</td>"
                                                            . "<td class='pl-4 align-middle text-center'>" . $list["rol4"] . "</td>"
                                                            . "<td class='pl-4 align-middle text-center'>" . $list["rol5"] . "</td>"
                                                            . "<td class='pl-4 align-middle text-center'>" . $list["rol3"] . "</td>"
                                                            . "<td class='pl-4 align-middle text-center'>" . $list["rol7"] . "</td>"
                                                            . "<td class='pl-4 align-middle text-center'>" . $list["rol8"] . "</td>"
                                                            . "<td class='pl-4 align-middle text-center'>" . $list["rol9"] . "</td>"
                                                            . "<td class='pl-4 align-middle text-center'>" . $list["rol10"] . "</td>"
                                                            . "<td class='pl-4 align-middle text-center'>" . $list["wip"] . "</td>"
                                                            . "<td class='pl-4 align-middle text-center'>" . $list["eff"] . "%</td>"
                                                            . "<td class='pl-4 align-middle text-center'>" . $list["loss%"] . "%</td>"
                                                            . "<td class='pl-4 align-middle text-center'>" . $list["ril%"] . "%</td>"
                                                            . "<td class='pl-4 align-middle text-center'>" . $list["rol%"] . "%</td>"
                                                            . "<td class='pl-4 align-middle text-center'>" . $list["wip%"] . "%</td>"
                                                            . "<td class='pl-4 align-middle text-center'>" . $list["total%"] . "%</td>"
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
                </div>
            </main>
            <?php include 'common/t_footer.php'; ?>
        </div>
    </div>
    <div class="modal fade" id="modal_filter" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="modal_filter_label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="get" action="#">
                <input type="hidden" name="action" value="<?= $action ?>">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal_filter_label"><span class="material-icons">filter_alt</span>
                            Filter</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row my-2">
                            <div class="col-4"><label class="col-form-label">Start Date</label></div>
                            <div class="col"><input type="text" name="date_from" class="form-control datepicker"
                                    value="<?php echo $date_from; ?>"></div>
                            <label class="col-form-label px-3">to</label>
                            <div class="col"><input type="text" name="date_to" class="form-control datepicker"
                                    value="<?php echo $date_to; ?>"></div>
                        </div>
                        <div class="row my-2">
                            <div class="col-4"><label class="col-form-label">Model</label></div>
                            <div class="col"><input type="text" name="group_id" class="form-control"
                                    value="<?php echo $group_id; ?>"></div>
                        </div>
                        <div class="row my-2">
                            <div class="col-4"><label class="col-form-label">Dies</label></div>
                            <div class="col"><input type="text" name="model_id" class="form-control"
                                    value="<?php echo $model_id; ?>"></div>
                        </div>
                        <div class="row my-2">
                            <div class="col-4"><label class="col-form-label">Dies No #</label></div>
                            <div class="col"><input type="text" name="dies_no" class="form-control"
                                    value="<?php echo $dies_no; ?>"></div>
                        </div>
                        <div class="row my-2">
                            <div class="col-4"><label class="col-form-label">Order Type</label></div>
                            <div class="col">
                                <select name="ori_type" class="form-control select2" style="width: 300px">
                                    <option value="">None</option>
                                    <option value="R">Repair</option>
                                    <option value="I">Improvements</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-dark-blue-outlined" name="filter" value="filter">Apply
                            Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php include 'common/t_js.php'; ?>
    <script src="vendors/ega/js/scripts.js?time=<?php echo date("Ymdhis"); ?>" type="text/javascript"></script>
    <script>
        $(document).ready(function () {
            $("#data-table-x").DataTable({
                stateSave: true,
                ordering: false,
            });

            $(".datepicker").flatpickr({
                altInput: true,
                altFormat: "d-m-Y",
                dateFormat: "Ymd"
            });

            <?php
            $i = 0;
            foreach ($data["list"] as $list) {
                ?>
                $("#show-button<?php echo $i; ?>").click(function () {
                    $(".details").toggle();
                });
                <?php
                $i++;
            }
            ?>
        });
    </script>
</body>

</html>