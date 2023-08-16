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
                    <form id="my_form" method="post" enctype="multipart/form-data"
                        action="?action=<?php echo $action; ?>&id=<?php echo $id; ?>">
                        <input type="hidden" name="save" value="post">
                        <div class="row">
                            <div class="col-12">
                                <div class="card mt-1">
                                    <div class="card-body">
                                        <!-- Edit Here -->
                                        <div class="row">
                                            <div class="col-6">

                                                <div class="form-group mb-2 row">
                                                    <label class="col-form-label col-lg-4 col-md-3 col-sm-12">Part
                                                        Number</label>
                                                    <div class="col-lg-7 col-md-6 col-sm-12">
                                                        <input type="text" name="partno"
                                                            class="form-control form-control-md"
                                                            value="<?= $header[0]["partno"] ?>" readonly required />
                                                    </div>
                                                </div>
                                                <div class="form-group mb-2 row">
                                                    <label class="col-form-label col-lg-4 col-md-3 col-sm-12">Part
                                                        Name</label>
                                                    <div class="col-lg-7 col-md-6 col-sm-12">
                                                        <input type="text" name="partname" id="partname"
                                                            class="form-control form-control-md"
                                                            value="<?= $header[0]["name1"] ?>" required />
                                                    </div>
                                                </div>
                                                <div class="form-group mb-2 row">
                                                    <label class="col-form-label col-lg-4 col-md-3 col-sm-12">File
                                                        Model
                                                        (Excel)</label>
                                                    <div class="col-lg-7 col-md-6 col-sm-12">
                                                        <div class="input-group mb-3">
                                                            <div class="custom-file">
                                                                <input type="file" class="custom-file-input" id="excel"
                                                                    name="excel" accept=".xls, .xlsx">
                                                                <label class="custom-file-label excel-label"
                                                                    for="inputFile01">Choose
                                                                    file</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="excel1"
                                                        value="<?= $header[0]["tmpfl"] ?>">
                                                </div>
                                                <div class="form-group mb-2 row">
                                                    <label class="col-form-label col-lg-4 col-md-3 col-sm-12"></label>
                                                    <div class="col-lg-7 col-md-6 col-sm-12">
                                                        <button type="submit" id="btn_save" name="btn_save"
                                                            value="btn_save" class="btn btn-primary btn-block" disabled>
                                                            <span class="material-icons">save</span>
                                                            Save</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group mb-2 row">
                                                    <label class="col-form-label col-lg-4 col-md-3 col-sm-12">Signature
                                                        Field</label>
                                                    <div class="col-lg-7 col-md-6 col-sm-12">
                                                        <input type="text" name="sign" id="sign"
                                                            class="form-control form-control-md"
                                                            value="<?= $header[0]["sign_pos"] ?>" required />
                                                    </div>
                                                </div>
                                                <div class="form-group mb-2 row">
                                                    <label class="col-form-label col-lg-4 col-md-3 col-sm-12">Rasio
                                                        Field</label>
                                                    <div class="col-lg-7 col-md-6 col-sm-12">
                                                        <input type="text" name="rasio" id="rasio"
                                                            class="form-control form-control-md"
                                                            value="<?= $header[0]["rasio_pos"] ?>" required />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <hr />
                                        <table class="table table-sm table-borderless">
                                            <thead class="">
                                                <tr>
                                                    <th colspan="4" class="text-left align-middle">
                                                        <h6>Mapping Template</h6>
                                                    </th>
                                                    <th colspan="4" class="text-right">
                                                        <button type="button" class="btn btn-info" id="btn_add_grup"
                                                            onclick="addGrup()"><span class="material-icons">add</span>
                                                            Add
                                                            Group</button>
                                                    </th>
                                                </tr>
                                            </thead>
                                        </table>
                                        <?php
                                        if (!empty($grup)) {
                                            $i = 1;
                                            foreach ($grup as $g) {
                                                ?>
                                                <div class="card mb-3">
                                                    <div class="card-header py-1">
                                                        <div class="row align-items-center">
                                                            <div class="col-4">
                                                                <div class="form-group row mb-0 mt-1">
                                                                    <label
                                                                        class="col-form-label col-lg-4 col-md-3 col-sm-12">Group
                                                                        Name</label>
                                                                    <div class="col-lg-7 col-md-6 col-sm-12">
                                                                        <input type="text" name="grup"
                                                                            class="form-control form-control-sm"
                                                                            value="<?= $g["desc1"] ?>" readonly />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-4">
                                                                <div class="form-group row mb-0 mt-1">
                                                                    <label
                                                                        class="col-form-label col-lg-4 col-md-3 col-sm-12">Group
                                                                        Image</label>
                                                                    <div class="col-lg-7 col-md-6 col-sm-12">
                                                                        <a class='view_img btn btn-outline-primary btn-sm'
                                                                            href='data:image/jpg;base64,<?= $g["img"] ?>'
                                                                            id="view_image_<?= $i ?>"><span
                                                                                class="material-icons">image</span> View
                                                                            Image</a>
                                                                        <input type="hidden" name="image1"
                                                                            value="<?= $g["img"] ?>">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-4 text-right">
                                                                <button type="button" class="btn btn-info btn-sm"
                                                                    id="btn_add_grup"
                                                                    onclick="addField(<?= $g['grupid'] ?>)"><span
                                                                        class="material-icons">add</span>
                                                                    Add
                                                                    Field</button>
                                                                <button type="button" class="btn btn-outline-info btn-sm"
                                                                    id="btn_add_grup"
                                                                    onclick="uploadField(<?= $g['grupid'] ?>)"><span
                                                                        class="material-icons">upload_file</span>
                                                                    Add
                                                                    Field by Excel</button>
                                                                <button type='button' class='btn btn-sm btn-danger btn_del_grup'
                                                                    onclick="deleteGrup('<?= $g['grupid'] ?>', '<?= $g['desc1'] ?>')"><span
                                                                        class='material-icons'>delete</span></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-body py-2">
                                                        <table class="table table-sm table-striped mb-0">
                                                            <thead>
                                                                <tr class="text-center">
                                                                    <th>
                                                                        Nomor
                                                                    </th>
                                                                    <th>
                                                                        Description
                                                                    </th>
                                                                    <th>
                                                                        Field
                                                                    </th>
                                                                    <th>
                                                                        Action
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                if (!empty($field)) {
                                                                    foreach ($field as $row) {
                                                                        if ($row["grupid"] == $g["grupid"]) {
                                                                            ?>
                                                                            <tr>
                                                                                <td>
                                                                                    <input type="text" name="cellid"
                                                                                        class="form-control form-control-sm"
                                                                                        value="<?= $row["cellid"] ?>" readonly />
                                                                                </td>
                                                                                <td>
                                                                                    <input type="text" name="desc"
                                                                                        class="form-control form-control-sm"
                                                                                        value="<?= $row["desc1"] ?>" readonly />
                                                                                </td>
                                                                                <td>
                                                                                    <input type="text" name="field"
                                                                                        class="form-control form-control-sm"
                                                                                        value="<?= $row["cell_map"] ?>" readonly />
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    <button type='button'
                                                                                        class='btn btn-danger btn-sm btn_del_item'
                                                                                        onclick="deleteField('<?= $g['grupid'] ?>','<?= $row['cellid'] ?>')"><span
                                                                                            class='material-icons'>delete</span></button>
                                                                                </td>
                                                                            </tr>
                                                                            <?php
                                                                        }
                                                                    }
                                                                } else {
                                                                    ?>
                                                                <td colspan="4" class="text-center">Data field doesnt exist!
                                                                </td>
                                                                <?php
                                                                }
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <?php
                                                $i++;
                                            }
                                        }
                                        ?>
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
    <input type="hidden" name="partid" value="<?= $_GET["id"] ?>">
    <input type="hidden" id="grupid" value="">
    <!-- Modal Upload -->
    <!-- <div class="modal fade" id="modal_upload" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="modal_upload_label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" action="?action=<?php echo $action; ?>&upload=excel" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal_upload_label"><span class="material-icons">upload_file</span>
                            Upload Data Field</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <a href="media/template/template-user.xlsx">Download Template</a>
                        <div class="input-group mb-3">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="excel" name="excel"
                                    accept=".xls, .xlsx">
                                <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </div>
            </form>
        </div>
    </div> -->
    <!-- Modal Upload Field -->
    <div class="modal fade" id="modal_upload" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="modal_filter_label" aria-hidden="true">
        <div class="modal-dialog">
            <!-- <form method="POST" action="?action=<?php echo $action; ?>&upload=excel" enctype="multipart/form-data"> -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal_upload_label"><span class="material-icons">upload_file</span>
                        Upload Data Field</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <a href="media/template/template-field.xlsx">Download Template</a>
                    <div class="input-group mb-3">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="excel_field" name="excel_field"
                                accept=".xls, .xlsx">
                            <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" onclick="insertFieldByExcel()">Upload</button>
                </div>
            </div>
            <!-- </form> -->
        </div>
    </div>
    <!-- Modal Add Grup -->
    <div class="modal fade" id="modal_grup" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="modal_filter_label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <!-- <form method="get" action="#"> -->
            <input type="hidden" name="action" value="<?= $action ?>">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal_filter_label"><span class="material-icons">add</span>
                        Add Group</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-form-label">Group
                            Name</label>
                        <div class="">
                            <input type="text" name="grup" id="grup" class="form-control form-control-md" value=""
                                required />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Group Image</label>
                        <div class="input-group mb-3">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="image" name="image"
                                    accept=".jpg, .jpeg, .png" required>
                                <label class="custom-file-label excel-label" for="inputFile01">Choose
                                    file</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Cancel</button>
                    <button type="submit" id="save" class="btn btn-primary" onclick="insertGrup()">Save</button>
                </div>
            </div>
            <!-- </form> -->
        </div>
    </div>
    <!-- Modal Add Field -->
    <div class="modal fade" id="modal_field" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="modal_filter_label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <!-- <form method="get" action="#"> -->
            <input type="hidden" name="action" value="<?= $action ?>">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal_filter_label"><span class="material-icons">add</span>
                        Add Field</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-form-label">Nomor</label>
                        <div class="">
                            <input type="text" name="cellid" id="cellid" class="form-control form-control-md" value=""
                                required />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Description</label>
                        <div class="">
                            <input type="text" name="desc" id="desc" class="form-control form-control-md" value=""
                                required />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Field</label>
                        <div class="">
                            <input type="text" name="field" id="field" class="form-control form-control-md" value=""
                                required />
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Cancel</button>
                    <button type="submit" id="save" class="btn btn-primary" onclick="insertField()">Save</button>
                </div>
            </div>
            <!-- </form> -->
        </div>
    </div>
    <?php include 'common/t_js.php'; ?>
    <script src="vendors/ega/js/scripts.js?time=<?php echo date("Ymdhis"); ?>" type="text/javascript"></script>
    <script>
        $(document).ready(function () {
            if ($('.view_img').length > 0) {
                $('.view_img').EZView();
            }
            bsCustomFileInput.init();
        });

        // $("#save").click(function () {
        //     $("button").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Please Wait...');
        //     $("button").attr("disabled", "disabled");
        // });

        function addGrup() {
            $("#modal_grup").modal("show");
        }

        function addField($grupid) {
            $("#grupid").val($grupid);
            $("#modal_field").modal("show");
            console.log($grupid);
        }

        function uploadField($grupid) {
            $("#grupid").val($grupid);
            $("#modal_upload").modal("show");
            console.log($grupid);
        }

        function insertGrup() {
            var grup = $("#grup").val();
            var image = document.getElementById("image").files[0];
            var partno = $("input[name=partid]").val();

            var formData = new FormData();
            formData.append("grup", grup);
            formData.append("image", image);
            formData.append("partno", partno);

            if (!grup) {
                alert("Group Name cannot be empty!");
                return false;
            } else if (!image) {
                alert("Group Image cannot be empty!");
                return false;
            } else {
                $.ajax({
                    type: "POST",
                    url: "?action=api_insert_grup",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        $("button").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Please Wait...');
                        $("button").attr("disabled", "disabled");
                        $("#modal_grup").modal("hide");
                        window.location.href = '?action=<?= $action ?>&id=' + partno;
                    }
                });
            }
        }

        function insertField() {
            var cellid = $("#cellid").val();
            var desc = $("#desc").val();
            var field = $("#field").val();
            var partno = $("input[name=partid]").val();
            var grupid = $("#grupid").val();

            var formData = new FormData();
            formData.append("cellid", cellid);
            formData.append("desc", desc);
            formData.append("field", field);
            formData.append("partno", partno);
            formData.append("grupid", grupid);

            if (!cellid) {
                alert("Nomor cannot be empty!");
                return false;
            } else if (!desc) {
                alert("Description cannot be empty!");
                return false;
            } else if (!field) {
                alert("Field cannot be empty!");
                return false;
            } else {
                $.ajax({
                    type: "POST",
                    url: "?action=api_insert_field",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        $("button").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Please Wait...');
                        $("button").attr("disabled", "disabled");
                        $("#modal_field").modal("hide");
                        var result = JSON.parse(response);
                        if (result.status == "false1") {
                            window.location.href = '?action=<?= $action ?>&id=' + partno + "&error=" + result.message;
                        } else if (result.status == "false2") {
                            window.location.href = '?action=<?= $action ?>&id=' + partno + "&error=" + result.message;
                        } else {
                            window.location.href = '?action=<?= $action ?>&id=' + partno;
                        }
                    }
                });
            }
        }

        function insertFieldByExcel() {
            var grupid = $("#grupid").val();
            var excel_field = document.getElementById("excel_field").files[0];
            var partno = $("input[name=partid]").val();

            var formData = new FormData();
            formData.append("grupid", grupid);
            formData.append("excel", excel_field);
            formData.append("partno", partno);

            if (!excel_field) {
                alert("File Excel cannot be empty!");
                return false;
            } else {
                $.ajax({
                    type: "POST",
                    url: "?action=api_insert_field_excel",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        $("button").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Please Wait...');
                        $("button").attr("disabled", "disabled");
                        $("#modal_upload").modal("hide");
                        var result = JSON.parse(response);
                        if (result.status == "false1") {
                            window.location.href = '?action=<?= $action ?>&id=' + partno + "&error=" + result.message;
                        } else if (result.status == "false2") {
                            window.location.href = '?action=<?= $action ?>&id=' + partno + "&error=" + result.message;
                        } else {
                            window.location.href = '?action=<?= $action ?>&id=' + partno;
                        }
                    }
                });
            }
        }

        function deleteGrup(grupid, desc) {
            var grup = grupid;
            var partno = $("input[name=partid]").val();

            var formData = new FormData();
            formData.append("grupid", grup);
            formData.append("partno", partno);

            if (confirm("Are you sure want to delete this " + desc + " group?")) {
                $.ajax({
                    type: "POST",
                    url: "?action=api_delete_grup",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        $("button").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Please Wait...');
                        $("button").attr("disabled", "disabled");
                        window.location.href = '?action=<?= $action ?>&id=' + partno;
                    }
                });
            }

        }

        function deleteField(grupid, cellid) {
            var grup = grupid;
            var cell = cellid;
            var partno = $("input[name=partid]").val();

            var formData = new FormData();
            formData.append("grupid", grup);
            formData.append("cellid", cell);
            formData.append("partno", partno);

            $.ajax({
                type: "POST",
                url: "?action=api_delete_field",
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    $("button").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Please Wait...');
                    $("button").attr("disabled", "disabled");
                    window.location.href = '?action=<?= $action ?>&id=' + partno;
                }
            });
        }

        $("#partname").change(function () {
            $("#btn_save").removeAttr("disabled");
        });

        $("#sign").change(function () {
            $("#btn_save").removeAttr("disabled");
        });

        $("#rasio").change(function () {
            $("#btn_save").removeAttr("disabled");
        });

        $('#excel').change(function (e) {
            var fileName = e.target.files[0].name;
            $('.excel-label').html(fileName);
            $("#btn_save").removeAttr("disabled");
        });

    </script>
</body>

</html>