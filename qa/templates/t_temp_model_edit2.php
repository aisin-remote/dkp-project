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
                    <ol class="breadcrumb mb-1 mt-1">
                        <li class="breadcrumb-item">
                            <?php echo $template["group"]; ?>
                        </li>
                        <li class="breadcrumb-item active">
                            <?php echo $template["menu"]; ?>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="?action=<?= $action ?>&id=<?= $_GET["id"] ?>"><span
                                    class='material-icons'>arrow_back</span> Back</a>
                        </li>
                    </ol>
                    <?php
                    if (isset($_GET["error"])) {
                        echo '<div class="alert alert-danger alert-dismissible fade show mb-1" role="alert">
                      ' . $_GET["error"] . '
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>';
                    }

                    if (isset($_GET["success"])) {
                        echo '<div class="alert alert-success alert-dismissible fade show mb-1" role="alert">
                      ' . $_GET["success"] . '
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>';
                    }
                    ?>
                    <form id="my_form" method="post"
                        action="?action=<?php echo $action; ?>&id=<?php echo $id; ?>&grup=<?= $_GET["grup"] ?>&map=0">
                        <input type="hidden" name="btn_save" value="post">
                        <div class="row">
                            <div class="col-12">
                                <div class="card mt-1">
                                    <div class="card-body">
                                        <!-- Edit Here -->
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="row mb-2">
                                                    <label
                                                        class="col-form-label font-weight-normal col-lg-3 col-md-3 col-sm-12 pr-0">Nama
                                                        Grup</label>
                                                    <div class="col-lg-4 col-md-6 col-sm-12 pl-1">
                                                        <input type="text" name="grup[]" class="form-control"
                                                            value="<?= $grup[0]["desc1"] ?>" placeholder="" readonly />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <button type="submit" id="btn_save" name="btn_save" value="btn_save"
                                                    class="btn btn-primary float-right"><span
                                                        class="material-icons">save</span>
                                                    Save</button>
                                            </div>
                                        </div>
                                        <div class="row mb-4">
                                            <div class="col-6">
                                                <div class="row mb-2">
                                                    <label
                                                        class="col-form-label font-weight-normal col-lg-3 col-md-3 col-sm-12 pr-0">File
                                                        Gambar</label>
                                                    <div class="input-group col-lg-4 col-md-6 col-sm-12 pl-1">
                                                        <a class='view_img btn btn-outline-primary btn-sm'
                                                            href='data:image/jpg;base64,<?= $grup[0]["img"] ?>'><span
                                                        class="material-icons">image</span> View
                                                            Image</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6"></div>
                                        </div>
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th colspan="4" class="text-left align-middle">
                                                        Mapping Field
                                                    </th>
                                                    <th colspan="4" class="text-right">
                                                        <button type="button" class="btn btn-info" id="btn_add_item"><span
                                                        class="material-icons">add</span> Add
                                                            Field</button>
                                                    </th>
                                                </tr>
                                            </thead>
                                        </table>
                                        <table class="table table-striped table-sm">
                                            <thead class="border-top-0">
                                                <tr>
                                                    <th class="text-center w-25">No</th>
                                                    <th class="text-center">Description</th>
                                                    <th class="text-center">Field</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (!empty($item)) {
                                                    $i = 1;
                                                    foreach ($item as $row) {
                                                        ?>
                                                        <input type="hidden" name="map" value="1" />
                                                        <tr id="item_<?= $i ?>">
                                                            <td>
                                                                <input type="text" name="cellid[]"
                                                                    class="form-control form-control-sm"
                                                                    value="<?= $row["cellid"] ?>" />
                                                            </td>
                                                            <td>
                                                                <input type="text" name="desc[]"
                                                                    class="form-control form-control-sm"
                                                                    value="<?= $row["desc1"] ?>" />
                                                            </td>
                                                            <td>
                                                                <input type="text" name="field[]"
                                                                    class="form-control form-control-sm"
                                                                    value="<?= $row["cell_map"] ?>" />
                                                            </td>
                                                            <td class="text-center">
                                                                <button id='btn_del_item_<?= $i ?>' type='button'
                                                                    class='btn btn-danger btn-sm btn_del_item'><span
                                                                        class='material-icons'>delete</span></button>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                        $i++;
                                                    }
                                                } else {
                                                    ?>
                                                    <input type="hidden" name="map" value="0" />
                                                    <tr id="item_1">
                                                        <td>
                                                            <input type="text" name="cellid[]"
                                                                class="form-control form-control-sm" />
                                                        </td>
                                                        <td>
                                                            <input type="text" name="desc[]"
                                                                class="form-control form-control-sm" />
                                                        </td>
                                                        <td>
                                                            <input type="text" name="field[]"
                                                                class="form-control form-control-sm" />
                                                        </td>
                                                        <td class="text-center">
                                                            <button id='btn_del_item_1' type='button'
                                                                class='btn btn-danger btn-sm btn_del_item'><span
                                                                    class='material-icons'>delete</span></button>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table>
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
    <input type="hidden" name="tmpid" value="<?= $_GET["id"] ?>">
    <?php include 'common/t_js.php'; ?>
    <script src="vendors/ega/js/scripts.js?time=<?php echo date("Ymdhis"); ?>" type="text/javascript"></script>
    <script>
        $(document).ready(function () {
            if ($('.view_img').length > 0) {
                $('.view_img').EZView();
            }
        });

        $("#my_form").submit(function () {
            $("#btn_save").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Please Wait...');
            $("button").attr("disabled", "disabled");
        });

        $("#werks").change(function () {
            updateLgort();
        });

        function updateLgort() {
            $(".lgort").html("<option value=''>Please Select S.Loc</option>");
            $.getJSON("?action=api_wms_get_sloc", { werks: $("#werks").val() }, function (result) {
                $.each(result, function (i, field) {
                    $(".lgort").append("<option value='" + field.lgort + "'>" + field.lgort + " - " + field.name1 + "</option>");
                });
            });
        }

        let num_grup = 1;
        let num_item

        $("#btn_add_item").click(function () {
            // get the last DIV which ID starts with ^= "klon"
            var $div = $('tr[id^="item_"]:last');

            // Read the Number from that DIV's ID (i.e: 3 from "klon3")
            // And increment that number by 1
            var num_before = parseInt($div.prop("id").match(/\d+/g), 5);
            num_item = num_before + 1;

            // Clone it and assign the new ID (i.e: from num 4 to ID "klon4")
            var $klon_id = 'item_' + num_item;
            // // $("#" + $klon_id + " .select2").prop("id", $klon_id);
            var $klon = $div.clone(true).prop('id', $klon_id);
            // Finally insert $klon wherever you want
            $div.after($klon);

            $("#" + $klon_id + " #btn_del_item_" + num_before).prop("id", "btn_del_item_" + num_item);
            $("#btn_del_item_" + num_item).attr("onclick", "deleteItem('" + num_item + "')");
        });

        function deleteItem(num) {
            console.log("deleted " + num)
            if (num == "1") {
                alert("First item cannot be deleted");
            } else {
                $("#item_" + num).remove();
            }
        }

        $('#excel').change(function (e) {
            var fileName = e.target.files[0].name;
            $('.excel-label').html(fileName);
        });
    </script>
</body>

</html>