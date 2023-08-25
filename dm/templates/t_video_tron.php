<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>

  <head>
    <?php include "common/t_css.php"; ?>
    <link href="vendors/font/PressStart.css" rel="stylesheet" type="text/css"/>
    <link href="vendors/ega/css/styles.css" rel="stylesheet" type="text/css" />
    <style>
      body {
        font-family: 'Roboto', 'Calibri', sans-serif !important;
        font-size: 98px !important;
        font-weight: 900;
        line-height: 1.3 !important;
      }
    </style>
  </head>

  <body>
    <div id="layoutSidenav">
      <div id="layoutSidenav_content">
        <main class="mt-1">
          <div class="px-1 bg-white" id="fs">
            <div class="row mx-1" id="dashboard">
              <div class="col-6 p-1">
                <div class="container-fluid border text-dark" style='background-color: #A7E9AF;'>
                  <div class="row">
                    <div class="col-8">
                      <p class='text-left mb-0'>TCC D98E</p>
                      <p class='text-left mb-0'>#1</p>
                    </div>
                    <div class="col-4">
                      <p class='text-right mb-0'>2000</p>
                      <p class='text-right mb-0'>6000</p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-6 p-1">
                <div class="container-fluid border text-dark" style='background-color: #B0DEFF;'>
                  <div class="row">
                    <div class="col-8">
                      <p class='text-left mb-0'>TCC D72F</p>
                      <p class='text-left mb-0'>#2</p>
                    </div>
                    <div class="col-4">
                      <p class='text-right mb-0'>2000</p>
                      <p class='text-right mb-0'>6000</p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-6 p-1">
                <div class="container-fluid border text-dark" style='background-color: #BBBBBB;'>
                  <div class="row">
                    <div class="col-8">
                      <p class='text-left mb-0'>TCC D12E</p>
                      <p class='text-left mb-0'>#3</p>
                    </div>
                    <div class="col-4">
                      <p class='text-right mb-0'>2000</p>
                      <p class='text-right mb-0'>6000</p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-6 p-1">
                <div class="container-fluid border text-dark" style='background-color: #92A9BD;'>
                  <div class="row">
                    <div class="col-8">
                      <p class='text-left mb-0'>TCC 4A91</p>
                      <p class='text-left mb-0'>#4</p>
                    </div>
                    <div class="col-4">
                      <p class='text-right mb-0'>2000</p>
                      <p class='text-right mb-0'>6000</p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-6 p-1">
                <div class="container-fluid border text-dark" style='background-color: #F7C8E0;'>
                  <div class="row">
                    <div class="col-8">
                      <p class='text-left mb-0'>OPN D72F</p>
                      <p class='text-left mb-0'>#5</p>
                    </div>
                    <div class="col-4">
                      <p class='text-right mb-0'>2000</p>
                      <p class='text-right mb-0'>6000</p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-6 p-1">
                <div class="container-fluid border text-dark" style='background-color: #FF8787;'>
                  <div class="row">
                    <div class="col-8">
                      <p class='text-left mb-0'>OPN 889F</p>
                      <p class='text-left mb-0'>#6</p>
                    </div>
                    <div class="col-4">
                      <p class='text-right mb-0'>2000</p>
                      <p class='text-right mb-0'>6000</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </main>
      </div>
    </div>
    <input type="hidden" id="usrid" value="<?php echo $_SESSION[LOGIN_SESSION]; ?>">
    <?php include 'common/t_js.php'; ?>
    <script src="vendors/ega/js/scripts.js?time=<?php echo date("Ymdhis"); ?>" type="text/javascript"></script>
    <script>
      $(document).ready(function () {
        // closeFullscreen();
      });
    </script>
  </body>

</html>