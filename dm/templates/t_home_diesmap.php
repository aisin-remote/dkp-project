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
                    <h5 class="mt-3 ml-3">Parking Area</h5>
                    <div class="d-flex justify-content-center mt-1">
                        <div class="row" id="mappingP">
                            <?php
                            foreach ($list_zona as $list) {
                                if ($list["zona_type"] == "P") {
                                    echo "<div class='col-3 mb-2'>"
                                        . "<div class='card " . $list["bg"] . "' style='width: 300px; height: 250px'>"
                                        . "<div class='card-body p-2'>"
                                        . "<div class='card-title p-1 font-weight-bold'>" . $list["desc"] . "</div>"
                                        . "</div>"
                                        . "</div>"
                                        . "</div>";
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <hr />
                    <h5 class="ml-3">Production Area</h5>
                    <div class="d-flex justify-content-center mt-1">
                        <div class="row" id="mappingL">
                            <?php
                            foreach ($list_zona as $list) {
                                if ($list["zona_type"] == "L") {
                                    echo "<div class='col'>"
                                        . "<div class='card " . $list["bg"] . "' style='width: 110px; height: 110px'>"
                                        . "<div class='card-body p-2'>"
                                        . "<div class='card-title p-1 font-weight-bold'>" . $list["desc"] . "</div>"
                                        . "</div>"
                                        . "</div>"
                                        . "</div>";
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <hr>
                    <h5 class="mt-2 ml-3">Maintenance Area</h5>
                    <center>
                        <!-- <div class="d-flex justify-content-center mt-1"> -->
                        <div class="row" id="mappingM">
                            <?php
                            foreach ($list_zona as $list) {
                                if ($list["zona_type"] == "M") {
                                    echo "<div class='col-3 mb-2'>"
                                        . "<div class='card " . $list["bg"] . "' style='width: 300px; height: 170px'>"
                                        . "<div class='card-body p-2'>"
                                        . "<div class='card-title p-1 text-white font-weight-bold'>" . $list["desc"] . "</div>"
                                        . "</div>"
                                        . "</div>"
                                        . "</div>";
                                }
                            }
                            ?>
                        </div>
                        <!-- </div> -->
                    </center>
                </div>
            </main>
            <?php include 'common/t_footer.php'; ?>
        </div>
    </div>
    <input type="hidden" id="usrid" value="<?php echo $_SESSION[LOGIN_SESSION]; ?>">
    <?php include 'common/t_js.php'; ?>
    <script src="vendors/ega/js/scripts.js?time=<?php echo date("Ymdhis"); ?>" type="text/javascript"></script>
    <script>
        setInterval(mapping, 3000);

        $(document).ready(function () {
            // closeFullscreen();
        });

        function mapping() {
            $.getJSON("?action=api_mapping_dm", {},
                function (data) {
                    var dies = data.data_dies
                    var zona = data.data_zona
                    var model = data.data_model

                    var append_dataP = ""
                    var append_dataM = ""
                    var append_dataL = ""

                    if (zona.length != 0) {
                        var i = 0
                        $.each(zona, function (row, z) {
                            if (zona[i].zona_type == "P") {
                                append_dataP += "<div class='col-3 mb-2'>";
                                append_dataP += "<div class='card " + zona[i].bg + "' style='width: 300px;'>";
                                append_dataP += "<div class='card-body p-2'>";
                                append_dataP += "<div class='card-title p-1 font-weight-bold'>" + zona[i].desc + "</div>";
                                append_dataP += "<div class='row no-gutters'>";
                                if (model.length != 0) {
                                    var j = 0
                                    $.each(model, function (row, m) {
                                        var k = 0
                                        $.each(dies, function (row, d) {
                                            if (dies[k].zona_id == zona[i].zona_id) {
                                                if (dies[k].model_id == model[j].model_id && dies[k].group_id == model[j].group_id) {
                                                    append_dataP += "<div class='col-4 mb-1'>";
                                                    append_dataP += "<div class='card' style='width: 90px; background-color: " + model[j].colour + "'>";
                                                    append_dataP += "<a id='dies_data' class='card-body text-center rounded p-1'>";
                                                    append_dataP += "<small class=' mb-0 font-weight-bold " + model[j].font_colour + "'>" + d.group_id + " " + d.model_id + " " + d.dies_no + "</small>"
                                                    append_dataP += "</a>";
                                                    append_dataP += "</div></div>";
                                                }
                                            }
                                            k++
                                        })
                                        j++
                                    })
                                }
                                append_dataP += "</div></div></div></div>";
                            } else if (zona[i].zona_type == "M") {
                                append_dataM += "<div class='col-3 mb-2'>";
                                append_dataM += "<div class='card " + zona[i].bg + "' style='width: 300px; height: 170px'>";
                                append_dataM += "<div class='card-body p-2'>";
                                append_dataM += "<div class='card-title p-1 text-white font-weight-bold'>" + zona[i].desc + "</div>";
                                append_dataM += "<div class='row no-gutters'>";
                                if (model.length != 0) {
                                    var j = 0
                                    $.each(model, function (row, m) {
                                        var k = 0
                                        $.each(dies, function (row, d) {
                                            if (dies[k].zona_id == zona[i].zona_id) {
                                                if (dies[k].model_id == model[j].model_id && dies[k].group_id == model[j].group_id) {
                                                    append_dataM += "<div class='col mb-1'>";
                                                    append_dataM += "<div class='card mx-auto' style='width: 150px; height: 95px; background-color: " + model[j].colour + "'>";
                                                    append_dataM += "<a id='dies_data' class='card-body text-center rounded p-1'>";
                                                    append_dataM += "<h2 class=' mb-0 font-weight-bold " + model[j].font_colour + "'>" + d.group_id + " " + d.model_id + " " + d.dies_no + "</h2>"
                                                    append_dataM += "</a>";
                                                    append_dataM += "</div></div>";
                                                }
                                            }
                                            k++
                                        })
                                        j++
                                    })
                                }
                                append_dataM += "</div></div></div></div>";
                            } else if (zona[i].zona_type == "L") {
                                console.log(zona[i].desc)
                                append_dataL += "<div class='col'>";
                                append_dataL += "<div class='card " + zona[i].bg + "' style='width: 110px; height: 110px'>";
                                append_dataL += "<div class='card-body p-2'>";
                                append_dataL += "<div class='card-title p-1 font-weight-bold'>" + zona[i].desc + "</div>";
                                append_dataL += "<div class='row no-gutters'>";
                                if (model.length != 0) {
                                    var j = 0
                                    $.each(model, function (row, m) {
                                        var k = 0
                                        $.each(dies, function (row, d) {
                                            if (dies[k].zona_id == zona[i].zona_id) {
                                                if (dies[k].model_id == model[j].model_id && dies[k].group_id == model[j].group_id) {
                                                    append_dataL += "<div class='col-4 mb-1'>";
                                                    append_dataL += "<div class='card' style='width: 90px; background-color: " + model[j].colour + "'>";
                                                    append_dataL += "<a id='dies_data' class='card-body text-center rounded p-1'>";
                                                    append_dataL += "<small class=' mb-0 font-weight-bold " + model[j].font_colour + "'>" + d.group_id + " " + d.model_id + " " + d.dies_no + "</small>"
                                                    append_dataL += "</a>";
                                                    append_dataL += "</div></div>";
                                                }
                                            }
                                            k++
                                        })
                                        j++
                                    })
                                }
                                append_dataL += "</div></div></div></div>";
                            }
                            i++
                        })
                        $("#mappingP").html(append_dataP);
                        $("#mappingM").html(append_dataM);
                        $("#mappingL").html(append_dataL);
                    }
                }
            )
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