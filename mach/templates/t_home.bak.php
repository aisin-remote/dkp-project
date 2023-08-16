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
        <div class="container-fluid mt-2">
          <ol class="breadcrumb mb-2">
            <li class="breadcrumb-item"><?php echo $template["group"]; ?></li>
            <li class="breadcrumb-item active"><?php echo $template["menu"]; ?></li>
          </ol>
          <div class="card mb-3">
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-striped">
                  <thead>
                    <tr class="table-dark-blue">
                      <th class='border-right'>Line</th>
                      <th class='border-right'>Shift</th>
                      <th class='border-right text-right'>Planning Qty</th>
                      <th class="border-right text-right">Production Qty</th>
                      <th class="border-right text-right">OK Qty</th>
                      <th class="border-right text-right">NG Qty</th>
                      <th class="text-right">Efficiency</th>
                    </tr>
                  </thead>
                  <tbody id="prd_summary">
                    <?php
                    $tot_planning = 0;
                    $tot_production = 0;
                    $tot_ok = 0;
                    $tot_ng = 0;
                    $tot_efficiency = 0;
                    if (!empty($data["list"])) {
                      foreach ($data["list"] as $list) {
                        echo "<tr>"
                          . "<td class='border-right'>" . $list["line_name"] . "</td>"
                          . "<td class='border-right'>" . $list["shift_name"] . "</td>"
                          . "<td class='border-right text-right'>" . $list["pln_qty"] . "</td>"
                          . "<td class='border-right text-right'>" . $list["tot_qty"] . "</td>"
                          . "<td class='border-right text-right'>" . $list["prd_qty"] . "</td>"
                          . "<td class='border-right text-right'>" . $list["ng_qty"] . "</td>"
                          . "<td class='text-right'>" . $list["efficiency"] . "</td>"
                          . "</tr>";
                        $tot_planning += $list["pln_qty"];
                        $tot_production += $list["tot_qty"];
                        $tot_ok += $list["prd_qty"];
                        $tot_ng += $list["ng_qty"];
                      }
                      if ($tot_planning > 0) {
                        $tot_efficiency = round(($tot_ok / $tot_planning) * 100, 2);
                      }
                    }
                    ?>
                  </tbody>
                </table>
              </div>
              <div class="table-responsive">
                <table class='table table-dark-blue table-borderless table-sm'>
                  <tr>
                    <td colspan='2'>Summary Today Running</td>
                  </tr>
                  <tr>
                    <td>Total Planning</td>
                    <td>: <?= $tot_planning ?></td>
                  </tr>
                  <tr>
                    <td>Total Production</td>
                    <td>: <?= $tot_production ?></td>
                  </tr>
                  <tr>
                    <td>Total OK Qty</td>
                    <td>: <?= $tot_ok ?></td>
                  </tr>
                  <tr>
                    <td>Total NG</td>
                    <td>: <?= $tot_ng ?></td>
                  </tr>
                  <tr>
                    <td>Efficiency %</td>
                    <td>: <?= $tot_efficiency ?></td>
                  </tr>
                </table>
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
    //setInterval(updateDashboard, 1000);

    $(document).ready(function() {

    });

    function updateDashboard() {

    }
  </script>
</body>

</html>