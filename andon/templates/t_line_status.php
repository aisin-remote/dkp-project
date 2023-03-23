<!DOCTYPE html>
<html lang="en">
<?php include "common/t_css.php"; ?>

<body class="bg-dark text-white">
  <div class="container-fluid">
    <div class="row">
      <div class="col">
        <a class="navbar-brand my-3" href="../"><img src="media/images/logo-white.png" height="30" alt="" /></a>
      </div>
      <div class="col">
        <h1 class="text-center my-3 mx-auto font-weight-bold mb-3">
          <?= $result[0]["linename"] ?>
        </h1>
      </div>
      <div class="col"></div>
    </div>
  </div>
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
      <input type="hidden" id="mach" value="<?= $_GET["mach"] ?>" />
      <input type="hidden" id="line_id" value="<?= $result[0]["lineid"] ?>" />
      <input type="hidden" id="shift_hidden" value="" />
      <div class="card px-3 bg-dark border-white">
        <div class="card-body p-2">
          <div class="my-2" id="prd-btn"></div>
          <div class="row">
            <div class="col px-1">
              <h3>Date : </h3>
              <h1 id="date">
                </h2>
            </div>
            <div class="col px-1">
              <h3>Time : </h3>
              <h1 id="time"></h1>
            </div>
            <div class="col px-1">
              <h3>Shift : </h3>
              <h1 id="shift"></h1>
            </div>
            <div class="col px-1">
              <h3>Cycle Time : </h3>
              <h1 id="cctime"></h1>
            </div>
            <div class="col px-1">
              <h3>Qty OK : </h3>
              <h1 id="qtyok" class="text-success"></h1>
            </div>
            <div class="col px-1">
              <h3>Qty NG : </h3>
              <h1 id="qtyng" class="text-danger"></h1>
            </div>
          </div>
        </div>
      </div>
      <div class="row px-2 mt-2" id="list">
        <?php
        // print("<pre class='text-white'>" . print_r($_GET["mach"], true) . "</pre>");
        foreach ($result as $mach) {
          ?>
          <div class="col p-1">
            <div class="card border-0 bg-dark mb-2">
              <div class="card-body p-0">
                <div class="container-fluid p-1 text-center">
                  <div class="card mb-3">
                    <!-- <div class="card-body <?= $mach["bgcolor"] ?> text-center text-white"> -->
                    <div class="card-body bg-info text-center text-white">
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
                        } else {
                          $color = "primary";
                        }
                        ?>
                        <div class='mb-2'>
                          <input onchange='cek_cb(<?= $sts["andon_id"] ?>, "<?= $mach["machid"] ?>")'
                            name='<?= $sts["andon_id"] ?>' id='<?= $sts["andon_id"] ?>_<?= $mach["machid"] ?>' type='checkbox'
                            data-toggle='toggle' data-on='<?= $sts["desc"] ?>' data-off='<?= $sts["desc"] ?>'
                            data-onstyle='<?= $color ?>' data-offstyle='secondary' data-width='100%' data-height='100%'
                            data-size='lg' <?= ($mach["machid"] == $btn["mach_id"] && $sts["andon_id"] == $btn["andon_id"] && $btn["btn_sts"] == 1) ? "checked='checked'" : ""; ?> />
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
      <div class="card bg-dark border-white">
        <div class="card-body">
          <div class="d-flex justify-content-around">
            <button type="button" data-toggle="modal" data-target="#productok"
              class="btn btn-lg text-center bg-primary text-white border border-secondary" style="width: 20%">
              <h3>Product OK</h3>
            </button>
            <button type="button" onclick="handleRev('ok')"
              class="btn btn-lg text-center text-white border border-secondary"
              style="width: 20%; background-color: #F99417">
              <h3>OK Reversal</h3>
            </button>
            <button type="button" data-toggle="modal" data-target="#productng"
              class="btn btn-lg text-center bg-danger text-white border border-secondary" style="width: 20%">
              <h3>Product NG</h3>
            </button>
            <button type="button" onclick="handleRev('ng')"
              class="btn btn-lg text-center text-white border border-secondary"
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
          <div class="modal-body">
            <input type="number" id="quantityok" value="1" class="form-control form-control-lg" step="1" />
          </div>
          <div class="modal-footer d-flex justify-content-between">
            <div class="float-left">
              <button onclick="increment('#quantityok')" class="btn px-5 btn-primary btn-lg">
                <h2><i class="material-icons">add</i></h2>
              </button>
              <button onclick="decrement('#quantityok')" class="btn px-5 btn-danger btn-lg">
                <h2><i class="material-icons">remove</i></h2>
              </button>
            </div>
            <button type="button" onclick="handleOK()" class="btn btn-success btn-lg"><h2>Submit</h2></button>
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
              <input type="number" id="quantityng" value="1" class="form-control form-control-lg mx-2" step="1" />
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
  </main>
  <?php include 'common/t_js.php'; ?>
  <script>
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

    function handleOK() {
      if ($("#cctime").text().trim() == 0) {
        alert("Please create production first")
      } else {
        $.post("?action=api_post_ib", {
          ib_type: "P",
          line_id: $("#line_id").val(),
          qty: $("#quantityok").val(),
          crt_dt: <?= date("Ymd") ?>,
          shkzg: "C",
          shift: $("#shift_hidden").val(),
        }, function (data) {
          console.log(data);
          $("quantity").val(1);
          $("#productok").modal("hide");
        });
      }
    }

    function handleRev(type) {
      if ($("#cctime").text().trim() == 0) {
        alert("Please create production first")
      } else {
        if (type == 'ok') {
          $.post("?action=api_update_rev", {
            rev: 'Y',
            type: 'P',
            line_id: $("#line_id").val(),
          }, function (data) {
            console.log(data)
          })
        } else {
          $.post("?action=api_update_rev", {
            rev: 'Y',
            type: 'N',
            line_id: $("#line_id").val(),
          }, function (data) {
            console.log(data)
          })
        }
      }
    }

    function handleNG(type) {
      if ($("#cctime").text().trim() == 0) {
        alert("Please create production first")
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
          console.log(data);
          $("#productng").modal("hide");
        });
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
          line_id: $("#line_id").val(),
          shift: $("#shift").text().trim(),
          mach: params.getAll("mach[]")
        },
        function (data) {
          var head = data.head
          var mach = data.mach
          var btn = data.btn
          var sts = data.status
          var append_data = ""
          $("#cctime").html(head?.cctime ? head?.cctime : 0);
          $("#shift").html(head?.shift ? head?.pval1 : 0);
          $("#shift_hidden").val(head?.shift ? head?.shift : 0);
          if (head.cctime) {
            $("#prd-btn").html("");
          } else {
            $("#prd-btn").html("<a href='?action=api_prd_entry&line=<?= $result[0]["lineid"] ?>&shift=" + $("#shift_hidden").val() + "&date=<?= date('Ymd') ?>' class='btn btn-lg bg-red-blink text-white font-weight-bold'>Create Production</a>")
          }
          // console.log(head)

          // $.each(mach, function (row, m) {
          //   append_data += '<div class="col p-1">'
          //   append_data += '<div class="card border-0 bg-dark mb-2">'
          //   append_data += '<div class="card-body p-0">'
          //   append_data += '<div class="container-fluid p-1 text-center">'
          //   append_data += '<div class="card mb-3">'
          //   append_data += '<div class="card-body bg-info text-center text-white">'
          //   append_data += '<h4 id="mach_name">' + m.machname + '</h4>'
          //   append_data += '</div></div>'
          //   $.each(btn, function (row, b) {
          //     $.each(sts, function (row, s) {
          //       if (s.btn_on == 1 && s.andon_id == b.andon_id && m.machid == b.mach_id) {
          //         ($mach["machid"] == $btn["mach_id"] && $sts["andon_id"] == $btn["andon_id"] && $btn["btn_sts"] == 1) ? "checked='checked'" : "";
          //         if (m.machid == b.mach_id && s.andon_id == b.andon_id && b.btn_sts == 1) {
          //           let checked = "checked"
          //         } else {
          //           let checked = ""
          //         }
          //         append_data += '<div class="mb-2">'
          //         append_data += '<input onchange="cek_cb(' + s.andon_id + ', \"' + m.machid + '\")" id="' + s.andon_id + '_' + m.machid + '" type="checkbox" data-toggle="toggle" data-on="' + s.desc + '" data-off="' + s.desc + '" data-onstyle="primary" data-offstyle="secondary" data-width="100%" data-height="100%" data-size="lg" ' + checked + ' />'
          //         append_data += '</div>'
          //       }
          //     })
          //   })
          //   append_data += '</div></div></div></div>'
          // })
          // $("#list").html(append_data)
        }
      );
    }

    function cek_cb(id, machid) {
      if ($("#" + id + "_" + machid).is(":checked")) {
        $.post("?action=api_update_stats", {
          mach_id: machid,
          andon_id: id,
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
          $("#qtyok").html(data.totalok && $("#cctime").text().trim() != 0 ? data.totalok : 0);
          $("#qtyng").html(data.totalng && $("#cctime").text().trim() != 0 ? data.totalng : 0);
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