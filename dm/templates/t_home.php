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
                <div class="container-fluid bg-white" id="fs">
                    <div class="row mt-1">
                        <div class="col-3">
                            <a id="logo" class="navbar-brand mb-3" href=".."><img src="media/images/logo.svg"
                                    height="30" alt="logo" /></a>
                        </div>
                        <div class="col-6">
                            <div id="title" class="text-ega-blue text-center">
                                <h4 class='mb-3' style="font-weight: 700; ">DASHBOARD DIES MAINTENANCE
                                </h4>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="d-flex justify-content-end">
                                <button id="close-fs" type="button" class="btn btn-link" onclick="closeFullscreen()"><i
                                        class="material-icons">fullscreen_exit</i></button>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center mt-3 mb-2">
                        <a href="?action=home" class="btn btn-sm btn-primary mr-2">Dies Status</a>
                        <a href="?action=home_diesmap" class="btn btn-sm btn-primary">Dies Map</a>
                    </div>
                    <div class="row mx-1" id="dashboard">
                        <div class="col-3 p-1" id="tcc">
                            <?php
                            if (!empty($data_group)) {
                                foreach ($data_group as $grp) {
                                    if ($grp["pval1"] == "TCC") {
                                        foreach ($data_model as $mdl) {
                                            if ($mdl["group_id"] == $grp["pval1"]) {
                                                echo "<div class='card rounded-0 mb-2' style='border-color: " . $mdl["colour"] . ";'>"
                                                    . "<div class='card-header p-1 rounded-0 " . $mdl["font_colour"] . " font-weight-bold'
                                                                    style='background-color: " . $mdl["colour"] . ";'>" . $grp["pval1"] . " " . $mdl["model_id"] . "
                                                                    </div>"
                                                    . "<div class='card-body p-1'><div class='container-fluid'>
                                                                    <div class='row'>";
                                                foreach ($data_dies as $dies) {
                                                    if ($dies["group_id"] == $mdl["group_id"] && $dies["model_id"] == $mdl["model_id"]) {
                                                        echo "<div class='col-4 p-1'>"
                                                            . "<div class='card rounded-0'>"
                                                            . "<a id='dies_data' href='?action=CHECKSHEET_PREVENTIVE&id=0&step=1&group_id=" . $dies["group_id"] . "&model_id=" . $dies["model_id"] . "&dies_id=" . $dies["dies_id"] . "' class='card-body rounded-0 p-1  " . $dies["bg_color"] . "'>"
                                                            . "<div class='row align-items-center'>"
                                                            . "<div class='col-6 pr-1 pl-3'>"
                                                            . "<h5 class='text-left text-dark m-0 font-weight-bold'>" . $dies["dies_no"] . "</h5>"
                                                            . "</div>"
                                                            . "<div class='col-6 pl-1 pr-3'>"
                                                            . "<p class='m-0 text-dark text-right text-nowrap font-weight-bold'><span style='color: #10A19D'>" . $formatted_number = number_format($dies["stkrun"], 0, ',', '.') . "</span></p>"
                                                            . "<p class='m-0 text-dark text-right text-nowrap font-weight-bold'><span style='color: #1746A2'>" . $formatted_number = number_format($dies["stk6k"], 0, ',', '.') . "</span></p>"
                                                            . "</div>"
                                                            . "</div>"
                                                            . "</a>"
                                                            . "</div>"
                                                            . "</div>";
                                                    }
                                                }
                                                echo "</div></div></div></div>";
                                            }
                                        }
                                    }
                                }
                            }
                            ?>
                        </div>
                        <div class="col-3 p-1" id="opn">
                            <?php
                            if (!empty($data_group)) {
                                foreach ($data_group as $grp) {
                                    if ($grp["pval1"] == "OPN") {
                                        foreach ($data_model as $mdl) {
                                            if ($mdl["group_id"] == $grp["pval1"]) {
                                                echo "<div class='card rounded-0 mb-2' style='border-color: " . $mdl["colour"] . ";'>"
                                                    . "<div class='card-header p-1 rounded-0 " . $mdl["font_colour"] . " font-weight-bold'
                                                                    style='background-color: " . $mdl["colour"] . ";'>" . $grp["pval1"] . " " . $mdl["model_id"] . "
                                                                    </div>"
                                                    . "<div class='card-body p-1'><div class='container-fluid'>
                                                                    <div class='row'>";
                                                foreach ($data_dies as $dies) {
                                                    if ($dies["group_id"] == $mdl["group_id"] && $dies["model_id"] == $mdl["model_id"]) {
                                                        echo "<div class='col-4 p-1'>"
                                                            . "<div class='card rounded-0'>"
                                                            . "<a id='dies_data' href='?action=CHECKSHEET_PREVENTIVE&id=0&step=1&group_id=" . $dies["group_id"] . "&model_id=" . $dies["model_id"] . "&dies_id=" . $dies["dies_id"] . "' class='card-body rounded-0 p-1  " . $dies["bg_color"] . "'>"
                                                            . "<div class='row align-items-center'>"
                                                            . "<div class='col-6 pr-1 pl-3'>"
                                                            . "<h5 class='text-left text-dark m-0 font-weight-bold'>" . $dies["dies_no"] . "</h5>"
                                                            . "</div>"
                                                            . "<div class='col-6 pl-1 pr-3'>"
                                                            . "<p class='m-0 text-dark text-right text-nowrap font-weight-bold'><span style='color: #10A19D'>" . $formatted_number = number_format($dies["stkrun"], 0, ',', '.') . "</span></p>"
                                                            . "<p class='m-0 text-dark text-right text-nowrap font-weight-bold'><span style='color: #1746A2'>" . $formatted_number = number_format($dies["stk6k"], 0, ',', '.') . "</span></p>"
                                                            . "</div>"
                                                            . "</div>"
                                                            . "</a>"
                                                            . "</div>"
                                                            . "</div>";
                                                    }
                                                }
                                                echo "</div></div></div></div>";
                                            }
                                        }
                                    }
                                }
                            }
                            ?>
                        </div>
                        <div class="col-3 p-1" id="csh">
                            <?php
                            if (!empty($data_group)) {
                                foreach ($data_group as $grp) {
                                    if ($grp["pval1"] == "CSH") {
                                        foreach ($data_model as $mdl) {
                                            if ($mdl["group_id"] == $grp["pval1"]) {
                                                echo "<div class='card rounded-0 mb-2' style='border-color: " . $mdl["colour"] . ";'>"
                                                    . "<div class='card-header p-1 rounded-0 " . $mdl["font_colour"] . " font-weight-bold'
                                                                    style='background-color: " . $mdl["colour"] . ";'>" . $grp["pval1"] . " " . $mdl["model_id"] . "
                                                                    </div>"
                                                    . "<div class='card-body p-1'><div class='container-fluid'>
                                                                    <div class='row'>";
                                                foreach ($data_dies as $dies) {
                                                    if ($dies["group_id"] == $mdl["group_id"] && $dies["model_id"] == $mdl["model_id"]) {
                                                        echo "<div class='col-4 p-1'>"
                                                            . "<div class='card rounded-0'>"
                                                            . "<a id='dies_data' href='?action=CHECKSHEET_PREVENTIVE&id=0&step=1&group_id=" . $dies["group_id"] . "&model_id=" . $dies["model_id"] . "&dies_id=" . $dies["dies_id"] . "' class='card-body rounded-0 p-1  " . $dies["bg_color"] . "'>"
                                                            . "<div class='row align-items-center'>"
                                                            . "<div class='col-6 pr-1 pl-3'>"
                                                            . "<h5 class='text-left text-dark m-0 font-weight-bold'>" . $dies["dies_no"] . "</h5>"
                                                            . "</div>"
                                                            . "<div class='col-6 pl-1 pr-3'>"
                                                            . "<p class='m-0 text-dark text-right text-nowrap font-weight-bold'><span style='color: #10A19D'>" . $formatted_number = number_format($dies["stkrun"], 0, ',', '.') . "</span></p>"
                                                            . "<p class='m-0 text-dark text-right text-nowrap font-weight-bold'><span style='color: #1746A2'>" . $formatted_number = number_format($dies["stk6k"], 0, ',', '.') . "</span></p>"
                                                            . "</div>"
                                                            . "</div>"
                                                            . "</a>"
                                                            . "</div>"
                                                            . "</div>";
                                                    }
                                                }
                                                echo "</div></div></div></div>";
                                            }
                                        }
                                    }
                                }
                            }
                            ?>
                        </div>
                        <div class="col-3 p-1">
                            <div class="card">
                                <div class='card-header'>
                                    <h6 class='card-title mb-0 text-uli-blue font-weight-bold'>Legend
                                    </h6>
                                </div>
                                <div class="card-body p-1 ">
                                    <table class="table table-bordered table-sm mb-0">
                                        <tbody>
                                            <tr>
                                                <td style="width: 100px;" class="bg-light text-center">White</td>
                                                <td>Dies stroke < 1,600</td>
                                            </tr>
                                            <tr>
                                                <td style="width: 100px;" class="bg-yellow text-center">Yellow</td>
                                                <td>Dies stroke >= 1,600</td>
                                            </tr>
                                            <tr>
                                                <td style="width: 100px;" class="bg-danger text-center">Red</td>
                                                <td>Dies stroke > 2,000</td>
                                            </tr>
                                            <tr>
                                                <td style="width: 100px;" class="bg-blink text-center">Blue
                                                    (Blinking)</td>
                                                <td>Dies under preventive maintenance/repair</td>
                                            </tr>
                                            <tr>
                                                <td style="width: 100px;" class="bg-amber text-center">Orange</td>
                                                <td>Dies under repair to maker</td>
                                            </tr>
                                            <tr>
                                                <td style="width: 100px;" class="text-center font-weight-bold">#999</td>
                                                <td>Dies Number</td>
                                            </tr>
                                            <tr>
                                                <td style="width: 100px;color: #10A19D" class="text-center font-weight-bold">9999</td>
                                                <td>Prev. Stroke</td>
                                            </tr>
                                            <tr>
                                                <td style="width: 100px;color: #1746A2" class="text-center font-weight-bold">9999</td>
                                                <td>Act. Stroke</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <?php include 'common/t_footer.php'; ?>
        </div>
    </div>
    <input type="hidden" id="usrid" value="<?php echo $_SESSION[LOGIN_SESSION]; ?>">
    <?php include 'common/t_js.php'; ?>
    <script src="vendors/ega/js/scripts.js?time=<?php echo date("Ymdhis"); ?>" type="text/javascript"></script>
    <script>
        setInterval(updateDashboard, 5000);

        $(document).ready(function () {
            // closeFullscreen();
        });

        function updateDashboard() {
            $.getJSON(
                "?action=api_dashboard_dm", {},
                function (data) {
                    var data_dies = data.data_dies;
                    var data_group = data.data_group;
                    var data_model = data.data_model

                    var append_dataTCC = "";
                    var append_dataOPN = "";
                    var append_dataCSH = "";
                    if (data_group.length !== 0) {
                        var i = 0;
                        $.each(data_group, function (row, grp) {
                            if (data_group[i]?.pval1 == "TCC") {
                                var j = 0;
                                $.each(data_model, function (row, mdl) {
                                    if (data_model[j].group_id == data_group[i].pval1) {
                                        append_dataTCC += "<div class='card rounded-0 mb-2' style='border-color: " +data_model[j].colour+ ";'>";
                                        append_dataTCC += "<div class='card-header p-1 rounded-0 " + data_model[j].font_colour + " font-weight-bold' style='background-color: " + data_model[j].colour + ";'>" + data_group[i].pval1 + " " + data_model[j].model_id + "</div>";
                                        append_dataTCC += "<div class='card-body p-2'><div class='container-fluid'>";
                                        append_dataTCC += "<div class='row'>";
                                        var x = 0;
                                        $.each(data_dies, function (row, dies) {
                                            if (data_dies[x].group_id == data_model[j].group_id && data_dies[x].model_id == data_model[j].model_id) {
                                                // console.log(data_dies[x].bg_color);
                                                append_dataTCC += "<div class='col-4 p-1'>";
                                                append_dataTCC += "<div class='card rounded-0'>";
                                                append_dataTCC += "<a id='dies_data' href='?action=CHECKSHEET_PREVENTIVE&id=0&step=1&group_id=" + data_dies[x].group_id + "&model_id=" + data_dies[x].model_id + "&dies_id=" + data_dies[x].dies_id + "' class='card-body rounded-0 p-1  " + data_dies[x].bg_color + "'>";
                                                append_dataTCC += "<div class='row align-items-center'>";
                                                append_dataTCC += "<div class='col-6 pr-1 pl-2'>";
                                                append_dataTCC += "<h5 class='text-center text-dark m-0 font-weight-bold'>" + data_dies[x].dies_no + "</h5>";
                                                append_dataTCC += "</div>";
                                                append_dataTCC += "<div class='col-6 pr-3 pl-1'>";
                                                append_dataTCC += "<p class='m-0 text-dark text-right text-nowrap font-weight-bold'><span style='color: #10A19D'>" + data_dies[x].stkrun + "</span></p>";
                                                append_dataTCC += "<p class='m-0 text-dark text-right text-nowrap font-weight-bold'><span style='color: #1746A2'>" + data_dies[x].stk6k + "</span></p>";
                                                append_dataTCC += "</div>";
                                                append_dataTCC += "</div>";
                                                append_dataTCC += "</a>";
                                                append_dataTCC += "</div>";
                                                append_dataTCC += "</div>";
                                            }
                                            x++;
                                        });
                                        append_dataTCC += "</div></div></div></div>";
                                    }
                                    j++;
                                });
                                i++;
                            }
                            if (data_group[i]?.pval1 == "OPN") {
                                var j = 0;
                                $.each(data_model, function (row, mdl) {
                                    if (data_model[j].group_id == data_group[i].pval1) {
                                        append_dataOPN += "<div class='card rounded-0 mb-2' style='border-color: " +data_model[j].colour+ ";'>";
                                        append_dataOPN += "<div class='card-header p-1 rounded-0 " + data_model[j].font_colour + " font-weight-bold' style='background-color: " + data_model[j].colour + ";'>" + data_group[i].pval1 + " " + data_model[j].model_id + "</div>";
                                        append_dataOPN += "<div class='card-body p-2'><div class='container-fluid'>";
                                        append_dataOPN += "<div class='row'>";
                                        var x = 0;
                                        $.each(data_dies, function (row, dies) {
                                            if (data_dies[x].group_id == data_model[j].group_id && data_dies[x].model_id == data_model[j].model_id) {
                                                // console.log(data_dies[x].bg_color);
                                                append_dataOPN += "<div class='col-4 p-1'>";
                                                append_dataOPN += "<div class='card rounded-0'>";
                                                append_dataOPN += "<a id='dies_data' href='?action=CHECKSHEET_PREVENTIVE&id=0&step=1&group_id=" + data_dies[x].group_id + "&model_id=" + data_dies[x].model_id + "&dies_id=" + data_dies[x].dies_id + "' class='card-body rounded-0 p-1  " + data_dies[x].bg_color + "'>";
                                                append_dataOPN += "<div class='row align-items-center'>";
                                                append_dataOPN += "<div class='col-6 pr-1 pl-2'>";
                                                append_dataOPN += "<h5 class='text-center text-dark m-0 font-weight-bold'>" + data_dies[x].dies_no + "</h5>";
                                                append_dataOPN += "</div>";
                                                append_dataOPN += "<div class='col-6 pr-3 pl-1'>";
                                                append_dataOPN += "<p class='m-0 text-dark text-right text-nowrap font-weight-bold'><span style='color: #10A19D'>" + data_dies[x].stkrun + "</span></p>";
                                                append_dataOPN += "<p class='m-0 text-dark text-right text-nowrap font-weight-bold'><span style='color: #1746A2'>" + data_dies[x].stk6k + "</span></p>";
                                                append_dataOPN += "</div>";
                                                append_dataOPN += "</div>";
                                                append_dataOPN += "</a>";
                                                append_dataOPN += "</div>";
                                                append_dataOPN += "</div>";
                                            }
                                            x++;
                                        });
                                        append_dataOPN += "</div></div></div></div>";
                                    }
                                    j++;
                                });
                                i++;
                            }
                            if (data_group[i]?.pval1 == "CSH") {
                                var j = 0;
                                $.each(data_model, function (row, mdl) {
                                    if (data_model[j].group_id == data_group[i].pval1) {
                                        append_dataCSH += "<div class='card rounded-0 mb-2' style='border-color: " +data_model[j].colour+ ";'>";
                                        append_dataCSH += "<div class='card-header p-1 rounded-0 " + data_model[j].font_colour + " font-weight-bold' style='background-color: " + data_model[j].colour + ";'>" + data_group[i].pval1 + " " + data_model[j].model_id + "</div>";
                                        append_dataCSH += "<div class='card-body p-2'><div class='container-fluid'>";
                                        append_dataCSH += "<div class='row'>";
                                        var x = 0;
                                        $.each(data_dies, function (row, dies) {
                                            if (data_dies[x].group_id == data_model[j].group_id && data_dies[x].model_id == data_model[j].model_id) {
                                                // console.log(data_dies[x].bg_color);
                                                append_dataCSH += "<div class='col-4 p-1'>";
                                                append_dataCSH += "<div class='card rounded-0'>";
                                                append_dataCSH += "<a id='dies_data' href='?action=CHECKSHEET_PREVENTIVE&id=0&step=1&group_id=" + data_dies[x].group_id + "&model_id=" + data_dies[x].model_id + "&dies_id=" + data_dies[x].dies_id + "' class='card-body rounded-0 p-1  " + data_dies[x].bg_color + "'>";
                                                append_dataCSH += "<div class='row align-items-center'>";
                                                append_dataCSH += "<div class='col-6 pr-1 pl-2'>";
                                                append_dataCSH += "<h5 class='text-center text-dark m-0 font-weight-bold'>" + data_dies[x].dies_no + "</h5>";
                                                append_dataCSH += "</div>";
                                                append_dataCSH += "<div class='col-6 pr-3 pl-1'>";
                                                append_dataCSH += "<p class='m-0 text-dark text-right text-nowrap font-weight-bold'><span style='color: #10A19D'>" + data_dies[x].stkrun + "</span></p>";
                                                append_dataCSH += "<p class='m-0 text-dark text-right text-nowrap font-weight-bold'><span style='color: #1746A2'>" + data_dies[x].stk6k + "</span></p>";
                                                append_dataCSH += "</div>";
                                                append_dataCSH += "</div>";
                                                append_dataCSH += "</a>";
                                                append_dataCSH += "</div>";
                                                append_dataCSH += "</div>";
                                            }
                                            x++;
                                        });
                                        append_dataCSH += "</div></div></div></div>";
                                    }
                                    j++;
                                });
                                i++;
                            }
                        });
                        $("#tcc").html(append_dataTCC);
                        $("#opn").html(append_dataOPN);
                        $("#csh").html(append_dataCSH);
                    }
                    // console.log(append_data);
                }
            );
        }

        var elem = document.getElementById("fs");

        function fullscreen() {
            document.body.style.zoom = '77%';
            if (elem.requestFullscreen) {
                elem.requestFullscreen();
            } else if (elem.mozRequestFullScreen) {
                /* Firefox */
                elem.mozRequestFullScreen();
            } else if (elem.webkitRequestFullscreen) {
                /* Chrome, Safari and Opera */
                elem.webkitRequestFullscreen();
            } else if (elem.msRequestFullscreen) {
                /* IE/Edge */
                elem.msRequestFullscreen();
            }
        }

        var div = document.querySelector('#title');
        var logo = document.querySelector('#logo');
        var closefs = document.querySelector('#close-fs');
        div.style.display = 'none';
        logo.style.display = 'none';
        closefs.style.display = 'none';

        // $("#fs-btn").click(fullscreen);
        $("#fs-btn").click(function () {
            fullscreen();
            var div = document.querySelector('#title');
            var logo = document.querySelector('#logo');
            var closefs = document.querySelector('#close-fs');
            div.style.display = '';
            logo.style.display = '';
            closefs.style.display = '';
        });

        function closeFullscreen() {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.webkitExitFullscreen) {
                /* Safari */
                document.webkitExitFullscreen();
            } else if (document.msExitFullscreen) {
                /* IE11 */
                document.msExitFullscreen();
            }
        }

        $(document).bind('webkitfullscreenchange mozfullscreenchange fullscreenchange', function (e) {
            var state = document.fullScreen || document.mozFullScreen || document.webkitIsFullScreen;
            var event = state ? 'FullscreenOn' : 'FullscreenOff';

            // Now do something interesting
            if (event == "FullscreenOff") {
                document.body.style.zoom = '100%';
                var div = document.querySelector('#title');
                var logo = document.querySelector('#logo');
                var closefs = document.querySelector('#close-fs');
                div.style.display = 'none';
                logo.style.display = 'none';
                closefs.style.display = 'none';
                closeFullscreen();
            }

        });
    </script>
</body>

</html>