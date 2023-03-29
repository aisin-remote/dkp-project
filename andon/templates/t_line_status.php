<!DOCTYPE html>
<html lang="en">
<?php include "common/t_css.php"; ?>

<body class="bg-dark text-white">
    <style>
        .toggle-on,
        .toggle-off {
            white-space: nowrap;
        }

        .scrolling-wrapper {
            overflow-x: auto;
        }

        ::-webkit-scrollbar {
            height: 25px;
        }

        ::-webkit-scrollbar-thumb {
            background-color: #FF3CAC;
            background-image: linear-gradient(225deg, #FF3CAC 0%, #784BA0 50%, #2B86C5 100%);
            border-radius: 10px;
        }

    </style>
    <header class="fixed-top">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <a class="navbar-brand my-3" onclick="window.location.reload()"><img src="media/images/logo-white.png" height="30"
                            alt="" /></a>
                </div>
                <div class="col">
                    <h1 class="text-center my-3 mx-auto font-weight-bold mb-3">
                        <?= $result[0]["linename"] ?>
                    </h1>
                </div>
                <div class="col">
                    <div class="my-3 float-right" id="prd-btn"></div>
                </div>
            </div>
        </div>
        <div class="card border-0 px-3 bg-dark border-white">
            <div class="card-body p-2">
                <div class="row">
                    <div class="col px-1">
                        <h3 class="text-info">Date : </h3>
                        <h2 id="date"></h2>
                    </div>
                    <div class="col px-1">
                        <h3 class="text-info">Time : </h3>
                        <h2 id="time"></h2>
                    </div>
                    <div class="col px-1">
                        <h3 class="text-info">Shift : </h3>
                        <h2 id="shift"></h2>
                    </div>
                    <div class="col px-1">
                        <h3 class="text-info">Cycle Time : </h3>
                        <h1 id="cctime"></h1>
                    </div>
                    <div class="col px-1">
                        <h3 class="text-info">Qty OK : </h3>
                        <h1 id="qtyok" class="text-success"></h1>
                    </div>
                    <div class="col px-1">
                        <h3 class="text-info">Qty NG : </h3>
                        <h1 id="qtyng" class="text-danger"></h1>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <main>
        <div class="container-fluid mt-2">
            <!-- <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
          <?php echo $template["group"]; ?>
        </li>
        <li class="breadcrumb-item active">
          <?php echo $template["menu"]; ?>
        </li>
      </ol> -->
            <input type="hidden" id="mach" value="<?= $_GET["mach"] ?>" />
            <input type="hidden" id="line_id" value="<?= $result[0]["lineid"] ?>" />
            <input type="hidden" id="shift_hidden" value="" />
            <div id="alert"></div>

            <div class="row flex-row flex-nowrap scrolling-wrapper px-2" id="list" style="padding-top: 15%;">
                <?php
                if (count($result) < 5) {
                    $col = "col";
                } else {
                    $col = "col-2";
                }
                // print("<pre class='text-white'>" . print_r($_GET["mach"], true) . "</pre>");
                foreach ($result as $mach) {
                    ?>
                    <div class="<?= $col ?> p-1">
                        <div class="card border-0 bg-dark mb-2">
                            <div class="card-body p-0">
                                <div class="container-fluid p-1 text-center">
                                    <div class="card border-0 mb-2">
                                        <!-- <div class="card-body <?= $mach["bgcolor"] ?> text-center text-white"> -->
                                        <div class="card-body py-1 bg-info text-center text-white">
                                            <h4 id="mach_name">
                                                <?= $mach["machname"] ?>
                                            </h4>
                                        </div>
                                    </div>
                                    <?php
                                    foreach ($btns as $btn) {
                                        foreach ($status as $sts) {
                                            if ($sts["btn_on"] == 1 && $sts["andon_id"] == $btn["andon_id"] && $mach["machid"] == $btn["mach_id"]) {
                                                if ($sts["andon_id"] == 1) {
                                                    $color = "danger";
                                                } else if ($sts["andon_id"] == 2) {
                                                    $color = "warning";
                                                } else if ($sts["andon_id"] == 3) {
                                                    $color = "success";
                                                } else if ($sts["andon_id"] == 7) {
                                                    $color = "info";
                                                } else {
                                                    $color = "primary";
                                                }
                                                ?>
                                                <div class='mb-1'>
                                                    <input onchange='cek_cb(<?= $sts["andon_id"] ?>, "<?= $mach["machid"] ?>")'
                                                        name='<?= $sts["andon_id"] ?>'
                                                        id='<?= $sts["andon_id"] ?>_<?= $mach["machid"] ?>' type='checkbox'
                                                        data-toggle='toggle' data-on='<?= $sts["desc"] ?>'
                                                        data-off='<?= $sts["desc"] ?>'
                                                data-onstyle='<?= $color ?>' data-offstyle='secondary' data-width='100%' data-height='100%' data-size='lg'
                                                        <?= ($mach["machid"] == $btn["mach_id"] && $sts["andon_id"] == $btn["andon_id"] && $btn["btn_sts"] == 1) ? "checked='checked'" : ""; ?> />
                                                </div>
                                                <?php
                                            }
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
            <div class="card border-0 pb-4 bg-dark border-white fixed-bottom">
                <div class="card-body">
                    <div class="d-flex justify-content-around">
                        <button type="button" onclick="checkPrd('#productok')"
                            class="btn btn-lg rounded-pill text-center bg-primary text-white border border-secondary"
                            style="width: 20%">
                            <h3>Product OK</h3>
                        </button>
                        <button type="button" onclick="handleRev('ok')"
                            class="btn btn-lg rounded-pill text-center text-white border border-secondary"
                            style="width: 20%; background-color: #F99417">
                            <h3>OK Reversal</h3>
                        </button>
                        <button type="button" onclick="checkPrd('#productng')"
                            class="btn btn-lg rounded-pill text-center bg-danger text-white border border-secondary"
                            style="width: 20%">
                            <h3>Product NG</h3>
                        </button>
                        <button type="button" onclick="handleRev('ng')"
                            class="btn btn-lg rounded-pill text-center text-white border border-secondary"
                            style="width: 20%; background-color: #F99417">
                            <h3>NG Reversal</h3>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="productok" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content bg-dark">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Product OK</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body d-flex justify-content-around">
                        <button onclick="increment('#quantityok')" class="btn btn-primary btn-lg">
                            <h5><i class="material-icons">add</i></h5>
                        </button>
                        <input type="number" id="quantityok" value="1" min="1" class="form-control form-control-lg mx-2"
                            step="1" />
                        <button onclick="decrement('#quantityok')" class="btn btn-danger btn-lg">
                            <h5><i class="material-icons">remove</i></h5>
                        </button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" onclick="handleOK()" class="btn btn-block btn-success btn-lg">
                            <h5>Submit</h5>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="productng" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content bg-dark">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Product NG</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex justify-content-around mb-3">
                            <button onclick="increment('#quantityng')" class="btn btn-primary btn-lg">
                                <h5><i class="material-icons">add</i></h5>
                            </button>
                            <input type="number" id="quantityng" value="1" min="1"
                                class="form-control form-control-lg mx-2" step="1" />
                            <button onclick="decrement('#quantityng')" class="btn btn-danger btn-lg">
                                <h5><i class="material-icons">remove</i></h5>
                            </button>
                        </div>
                        <?php
                        foreach ($ng as $n) {
                            echo "<button type='button' onclick='handleNG(\"" . $n["ng_type_id"] . "\")' class='text-center btn btn-primary mx-1 text-white'>" . $n["name1"] . "</button>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Error Prd -->
        <div class="modal fade" id="nullprd" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content bg-dark">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle" style="color: #FC2947">Warning!</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h4 class="mx-3 mb-3" style="color: #FC2947">Please create production first!</h4>
                        <button type="button" class="btn float-right btn-success btn-lg" data-dismiss="modal">
                            OK
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Error Qty -->
        <div class="modal fade" id="minusqty" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content bg-dark">
                    <div class="modal-header">
                        <h5 class="modal-title " id="exampleModalLongTitle" style="color: #FC2947">Warning!</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h4 class=" mx-3 mb-3" style="color: #FC2947">Quantity must be greater than 0!</h4>
                        <button type="button" class="btn float-right btn-success btn-lg" data-dismiss="modal">
                            OK
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php include 'common/t_js.php'; ?>
    <script>
        $(document).ready(function () {
            updateDashboard();
            updateQty();
        });

        function increment(id) {
            $(id).val(parseInt($(id).val()) + 1);
        }

        function decrement(id) {
            if ($(id).val() > 1) {
                $(id).val(parseInt($(id).val()) - 1);
            } else {
                $(id).val(1);
            }
        }

        function checkPrd(id) {
            if ($("#cctime").text().trim() == 0) {
                $("#nullprd").modal("show")
            } else {
                $(id).modal("show")
            }
        }

        function handleOK() {
            if ($("#cctime").text().trim() == 0) {
                // $("#alert").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">Please create production first!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>')
            } else {
                if ($("#quantityok").val() < 0) {
                    $("#minusqty").modal("show")
                    // $("#alert").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">Quantity must be greater than 0!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>')
                } else {
                    $.post("?action=api_post_ib", {
                        ib_type: "P",
                        line_id: $("#line_id").val(),
                        qty: $("#quantityok").val(),
                        crt_dt: <?= date("Ymd") ?>,
                        shkzg: "C",
                        shift: $("#shift_hidden").val(),
                    }, function (data) {
                        let isJson = true
                        try {
                            let result = JSON.parse(data)
                        } catch (error) {
                            isJson = false
                        }

                        if (isJson === false) {
                            $("#alert").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">' + data + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>')
                        } else {
                            $("#alert").html("");
                        }

                        $("quantityok").val(1);
                        $("#productok").modal("hide");
                    });
                }
            }
        }

        function handleRev(type) {
            if ($("#cctime").text().trim() == 0) {
                $("#nullprd").modal("show")
                // $("#alert").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">Please create production first!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>')
            } else {
                if (type == 'ok') {
                    if ($("#qtyok").text().trim() == 0) {

                    } else {
                        $.post("?action=api_update_rev", {
                            rev: 'Y',
                            type: 'P',
                            line_id: $("#line_id").val(),
                        }, function (data) {
                            let isJson = true
                            try {
                                let result = JSON.parse(data)
                            } catch (error) {
                                isJson = false
                            }

                            if (isJson === false) {
                                $("#alert").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">' + data + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>')
                            } else {
                                $("#alert").html("");
                            }
                        })
                    }
                } else {
                    if ($("#qtyng").text().trim() == 0) {

                    } else {
                        $.post("?action=api_update_rev", {
                            rev: 'Y',
                            type: 'N',
                            line_id: $("#line_id").val(),
                        }, function (data) {
                            let isJson = true
                            try {
                                let result = JSON.parse(data)
                            } catch (error) {
                                isJson = false
                            }

                            if (isJson === false) {
                                $("#alert").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">' + data + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>')
                            } else {
                                $("#alert").html("");
                            }
                        })
                    }
                }
            }
        }

        function handleNG(type) {
            if ($("#cctime").text().trim() == 0) {
                // $("#alert").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">Please create production first!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>')
            } else {
                if ($("#quantityng").val() < 0) {
                    $("#minusqty").modal("show")
                    // $("#alert").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">Quantity must be greater than 0!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>')
                } else {
                    $.post("?action=api_post_ib", {
                        ib_type: "N",
                        line_id: $("#line_id").val(),
                        qty: $("#quantityng").val(),
                        crt_dt: <?= date("Ymd") ?>,
                        ng_type: type,
                        shkzg: "C",
                        shift: $("#shift_hidden").val(),
                    }, function (data) {
                        let isJson = true
                        try {
                            let result = JSON.parse(data)
                        } catch (error) {
                            isJson = false
                        }

                        if (isJson === false) {
                            $("#alert").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">' + data + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>')
                        } else {
                            $("#alert").html("");
                        }
                        $("#productng").modal("hide");
                    });
                }
            }
        }

        setInterval(updateDashboard, 5000);
        setInterval(updateQty, 1000);
        setInterval(dateTime, 1000);

        const params = new URLSearchParams(window.location.search)

        function updateDashboard() {
            $.getJSON(
                "?action=api_mach_status",
                {
                    line_id: '<?= $_GET["line_id"] ?>',
                    shift: $("#shift").text().trim(),
                    mach: params.getAll("mach[]")
                },
                function (data) {
                    var head = data.head
                    var mach = data.mach
                    var btn = data.btn
                    var sts = data.status
                    $("#cctime").html(head?.cctime ? head?.cctime : 0);
                    $("#shift").html(head?.shift ? head?.pval1 : "");
                    $("#shift_hidden").val(head?.shift ? head?.shift : 0);
                    if (head.cctime) {
                        $("#prd-btn").html("");
                    } else {
                        $("#prd-btn").html("<a href='?action=api_prd_entry&line=<?= $result[0]["lineid"] ?>&shift=" + $("#shift_hidden").val() + "&date=<?= date('Ymd') ?>' class='btn btn-lg bg-red-blink text-white font-weight-bold'>Create Production</a>")
                        $("#shift").html("")
                    }
                }
            );
        }

        function cek_cb(id, machid) {
            if ($("#" + id + "_" + machid).is(":checked")) {
                if (id == 4) {
                    $.post("?action=api_update_stats", {
                        mach_id: machid,
                        andon_id: id,
                        line_id: '<?= $_GET["line_id"] ?>',
                        btn_on: 1,
                    }, function (data) {
                        // window.location.reload();
                        console.log(data)
                    });
                }
                $.post("?action=api_update_stats", {
                    mach_id: machid,
                    andon_id: id,
                    line_id: '<?= $_GET["line_id"] ?>',
                    btn_on: 1,
                }, function (data) {
                    // window.location.reload();
                    console.log(data)
                });
                if (id < 4) {
                    $("#4_" + machid).prop("checked", false);
                    $("#4_" + machid).bootstrapToggle('off');
                }
            } else {
                $.post("?action=api_update_stats", {
                    mach_id: machid,
                    andon_id: id,
                    line_id: '<?= $_GET["line_id"] ?>',
                    btn_on: 0,
                }, function (data) {
                    // window.location.reload();
                    console.log(data)
                });
            }
        }

        function updateQty() {
            $.getJSON(
                "?action=api_get_ib",
                {
                    line_id: $("#line_id").val(),
                    shift: $("#shift_hidden").val(),
                    date: <?= date("Ymd") ?>
                },
                function (data) {
                    $("#qtyok").html(data.totalok && $("#cctime").text().trim() != 0 ? data.totalok.toLocaleString() : 0);
                    $("#qtyng").html(data.totalng && $("#cctime").text().trim() != 0 ? data.totalng.toLocaleString() : 0);
                    // console.log(data)
                }
            );
        }

        function dateTime() {
            const now = new Date();
            const date = now.toLocaleDateString('en-GB', {
                day: 'numeric',
                month: 'numeric',
                year: 'numeric',
            }).replace(/\//g, '-');
            const time = now.toLocaleTimeString('id-ID', {
                hour: 'numeric',
                minute: 'numeric',
                second: 'numeric'
            }).replace(/\./g, ':');;

            $("#date").html(date);
            $("#time").html(time);
        }
    </script>
</body>

</html>