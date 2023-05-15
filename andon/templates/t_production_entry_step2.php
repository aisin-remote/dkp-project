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
          <ol class="breadcrumb mb-0 mt-2">
            <li class="breadcrumb-item">
              <?php echo $template["group"]; ?>
            </li>
            <li class="breadcrumb-item active">
              <?php echo $template["menu"]; ?>
            </li>
            <li class="breadcrumb-item active">
              <?php echo $template["submenu"]; ?>
            </li>
            <li class="breadcrumb-item active">Edit</li>
            <li class="breadcrumb-item active"><a class="" href="?action=<?php echo $action; ?>">back <i
                  class="material-icons">arrow_back</i></a></li>

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
            <form method="post"
              action="?action=<?= $action . "&line=" . $data_header["line_id"] . "&date=" . $date . "&shift=" . $data_header["shift"] ?>">
              <div class="col-12">
                <div class="card">
                  <div class="card-header" style="background-color: #E4E4E4;">
                    <div class="d-flex align-items-center justify-content-between">
                      <h6 class="mb-0">Header Information</h6>
                      <?php
                      if ($op_role == "LEADER" || $op_role == "ADMIN") {
                        ?>
                        <div>
                          <a class="btn btn-md btn-danger" onclick="deleteProd()"><i class="material-icons">delete</i>
                            Delete
                            Prod Entry</a>
                          <button type="submit" name="save" id="btn_save" value="save"
                            class="btn btn-md btn-pale-green"><i class="material-icons">save</i> Save</button>
                        </div>
                        <?php
                      } else {
                        echo null;
                      }
                      ?>
                    </div>
                  </div>
                  <div class="card-body" style="background-color: #F5F5F5;">
                    <div class="row">
                      <div class="col-md-4 col-sm-12">
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
                      <div class="col-md-4 col-sm-12">
                        <div class="row align-items-center">
                          <div class="col-4">Leader</div>
                          <div class="col-1">:</div>
                          <div class="col-6 mb-1 px-0">
                            <?php
                            if ($op_role == "LEADER" || $op_role == "ADMIN") {
                              ?>
                              <select name="ldid" class="form-control select2">
                                <?php
                                foreach ($ld_list as $row) {
                                  ?>
                                  <option value="<?php echo $row["empid"]; ?>" <?= ($row["empid"] === $data_header["ldid"]) ? 'selected' : '' ?>><?php echo $row["name1"]; ?></option>
                                  <?php
                                }
                                ?>
                              </select>
                              <?php
                            } else {
                              echo $data_header["ld_name"];
                            }
                            ?>
                          </div>
                          <div class="col-4">JP</div>
                          <div class="col-1">:</div>
                          <div class="col-6 mb-1 px-0">
                            <?php
                            if ($op_role == "LEADER" || $op_role == "ADMIN") {
                              ?>
                              <select name="jpid" class="form-control select2">
                                <?php
                                foreach ($jp_list as $row) {
                                  ?>
                                  <option value="<?php echo $row["empid"]; ?>" <?= ($row["empid"] === $data_header["jpid"]) ? 'selected' : '' ?>><?php echo $row["name1"]; ?></option>
                                  <?php
                                }
                                ?>
                              </select>
                              <?php
                            } else {
                              echo $data_header["jp_name"];
                            }
                            ?>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-4 col-sm-12">
                        <div class="row align-items-center">
                          <div class="col-4">Operator 1</div>
                          <div class="col-1">:</div>
                          <div class="col-6 mb-1 px-0">
                            <?php
                            if ($op_role == "LEADER" || $op_role == "ADMIN") {
                              ?>
                              <select name="op1id" class="form-control select2">
                                <option value=""></option>
                                <?php
                                foreach ($op_list as $row) {
                                  ?>
                                  <option value="<?php echo $row["empid"]; ?>" <?= ($row["empid"] === $data_header["op1id"]) ? 'selected' : '' ?>><?php echo $row["name1"]; ?></option>
                                  <?php
                                }
                                ?>
                              </select>
                              <?php
                            } else {
                              echo $data_header["op1_name"];
                            }
                            ?>
                          </div>
                          <div class="col-4">Operator 2</div>
                          <div class="col-1">:</div>
                          <div class="col-6 mb-1 px-0">
                            <?php
                            if ($op_role == "LEADER" || $op_role == "ADMIN") {
                              ?>
                              <select name="op2id" class="form-control select2">
                                <option value=""></option>
                                <?php
                                foreach ($op_list as $row) {
                                  ?>
                                  <option value="<?php echo $row["empid"]; ?>" <?= ($row["empid"] === $data_header["op2id"]) ? 'selected' : '' ?>><?php echo $row["name1"]; ?></option>
                                  <?php
                                }
                                ?>
                              </select>
                              <?php
                            } else {
                              echo $data_header["op2_name"];
                            }
                            ?>
                          </div>
                          <div class="col-4">Operator 3</div>
                          <div class="col-1">:</div>
                          <div class="col-6 mb-1 px-0">
                            <?php
                            if ($op_role == "LEADER" || $op_role == "ADMIN") {
                              ?>
                              <select name="op3id" class="form-control select2">
                                <option value=""></option>
                                <?php
                                foreach ($op_list as $row) {
                                  ?>
                                  <option value="<?php echo $row["empid"]; ?>" <?= ($row["empid"] === $data_header["op3id"]) ? 'selected' : '' ?>><?php echo $row["name1"]; ?></option>
                                  <?php
                                }
                                ?>
                              </select>
                              <?php
                            } else {
                              echo $data_header["op3_name"];
                            }
                            ?>
                          </div>
                          <div class="col-4">Operator 4</div>
                          <div class="col-1">:</div>
                          <div class="col-6 px-0">
                            <?php
                            if ($op_role == "LEADER" || $op_role == "ADMIN") {
                              ?>
                              <select name="op4id" class="form-control select2">
                                <option value=""></option>
                                <?php
                                foreach ($op_list as $row) {
                                  ?>
                                  <option value="<?php echo $row["empid"]; ?>" <?= ($row["empid"] === $data_header["op4id"]) ? 'selected' : '' ?>><?php echo $row["name1"]; ?></option>
                                  <?php
                                }
                                ?>
                              </select>
                              <?php
                            } else {
                              echo $data_header["op4_name"];
                            }
                            ?>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="row">
            <div class="col-12">
              <div class="card mt-2">
                <div class="card-body">
                  <div class="table-responsive text-nowrap">
                    <!-- Edit Here -->
                    <table id="table" class="table table-sm table-striped">
                      <thead>
                        <tr>
                          <th class="">Material</th>
                          <th class="text-center">Hour</th>
                          <th class="text-right">CT</th>
                          <th class="text-right">Plan Qty</th>
                          <th class="text-right">OK Qty</th>
                          <th class="text-right">NG Qty</th>
                          <th class="text-right">WIP</th>
                          <!-- <th class="text-right">Scan Qty(OK)</th>
                          <th class="text-right">Scan Qty(NG)</th> -->
                          <th class="text-right">Stop Count</th>
                          <th class="text-right">Loss Time (m)</th>
                          <th class="text-right">Prd Time (m)</th>
                          <th class="text-right">Efficiency (%)</th>
                          <th class="text-center">Action</th>
                          <?php if ($op_role == "LEADER") {
                            echo '<th class="text-center">Approve</th>';
                          } ?>
                          <th class="text-left">Apr. By</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        if (!empty($data_item)) {
                          foreach ($data_item as $list) {
                            $efficiency = round(($list["prd_qty"] * $list["cctime"] / 60) / $list["prd_time"] * 100, 2);
                            if ($efficiency > 100 || $efficiency < 0 || $list["pln_qty"] < 0) {
                              $warna = "table-danger";
                            } else {
                              $warna = "";
                            }
                            $btn_approve = "";
                            if ($list["stats"] == "N") {
                              $btn_approve = "<button type='button' class='btn btn-sm btn-success' onclick='approveDailyI(\"" . $list["line_id"] . "\",\"" . $list["shift"] . "\",\"" . $list["prd_dt"] . "\",\"" . $list["prd_seq"] . "\")'><i class='material-icons'>done_outline</i></button>";
                            }
                            echo "<tr class='$warna'>"
                              . "<td class=''>" . $list["dies_name"] . "</td>"
                              . "<td class='text-center'>" . $list["time_start"] . " - " . $list["time_end"] . "</td>"
                              . "<td class='text-right'>" . $list["cctime"] . "</td>"
                              . "<td class='text-right'>" . $list["pln_qty"] . "</td>"
                              . "<td class='text-right'>" . $list["prd_qty"] . "</td>"
                              . "<td class='text-right'>" . $list["ng_qty"] . "</td>"
                              . "<td class='text-right'>" . $list["wip"] . "</td>"
                              // . "<td class='text-right'>" . $list["scn_qty_ok"] . "</td>"
                              // . "<td class='text-right'>" . $list["scn_qty_ng"] . "</td>"
                              . "<td class='text-right'>" . $list["stop_count"] . "</td>"
                              . "<td class='text-right'>" . $list["loss_time"] . "</td>"
                              . "<td class='text-right'>" . $list["prd_time"] . "</td>"
                              . "<td class='text-right'>" . $efficiency . "</td>"
                              . "<td class='text-center'>"
                              . "<a href='?action=$action&line=" . $list["line_id"] . "&date=" . $list["xdate"] . "&shift=" . $list["shift"] . "&prd_seq=" . $list["prd_seq"] . "' class='btn btn-link btn-sm text-center text-dark'><i class='material-icons'>edit_square</i></a>"
                              . "</td>";
                            if ($op_role == "LEADER") {
                              echo "<td class='text-center'>$btn_approve</td>";
                            }
                            echo "<td class='text-left'>" . $list["apr_by"] . "</td>"
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
          <div class="row">

          </div>
        </div>
      </main>
      <?php include 'common/t_footer.php'; ?>
      <input type="hidden" name="action" id="action" value="<?= $action ?>">
    </div>
  </div>
  <?php include 'common/t_js.php'; ?>
  <script src="vendors/ega/js/scripts.js?time=<?php echo date("Ymdhis"); ?>" type="text/javascript"></script>
  <script>
    $(document).ready(function () { });

    function deleteProd() {
      if (confirm("Are you sure to delete this production? this action cannot be undone")) {
        $(".btn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Please Wait...');
        $(".btn").attr("disabled", "disabled");
        window.location = "?action=" + $("#action").val() + "&line=<?= $_GET["line"] ?>&date=<?= $date ?>&shift=<?= $_GET["shift"] ?>&delete=true";
      }
    }

    function approveDailyI(line_id, shift, prd_dt, prd_seq) {
      $.ajax({
        type: 'POST',
        url: '?action=api_approve_daily_i',
        data: {
          line_id: line_id,
          shift: shift,
          prd_dt: prd_dt,
          prd_seq: prd_seq
        },
        success: function (response) {
          // handle the response here
          if (response.status == true) {
            location.reload();
          } else {
            alert(response.message);
          }
        },
        error: function (error) {
          // handle the error here
          alert(error);
        },
        dataType: 'json'
      });
    }
  </script>
</body>

</html>