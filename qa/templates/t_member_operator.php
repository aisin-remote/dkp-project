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
          <ol class="breadcrumb mb-4 mt-4">
            <li class="breadcrumb-item"><?php echo $template["group"]; ?></li>
            <li class="breadcrumb-item active"><?php echo $template["menu"]; ?></li>
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
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-6 col-sm-12">
                      <!-- filter placement -->

                    </div>
                    <div class="col-lg-6 col-sm-12">
                      <div class="d-flex justify-content-end">
                        <!-- button placement -->
                        <a class="btn btn-primary" href="?action=<?php echo $action ?>&id=0"><span class="material-icons">add</span>New</a>
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
                  <div class="table-responsive">
                    <!-- Edit Here -->
                    <form action="?action=<?php echo $action; ?>" method="POST">
                      <table class="table table-sm table-striped" id="data-table-x">
                        <thead>
                          <tr>
                            <th class="align-middle pl-2"><input id='checkAll' type='checkbox' style='height: 18px; width: 18px;' /></th>
                            <th class="">Employee ID</th>
                            <th class="">Role</th>
                            <th class="">Employee Name</th>
                            <th class="">Status</th>
                            <th class="text-center pr-2">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          if (!empty($data["list"])) {
                            foreach ($data["list"] as $list) {
                              echo "<tr>"
                                . "<td class='align-middle chkbox pl-2'><input name='chk_id[]' type='checkbox' value='" . $list["empid"] . "' style='height: 18px; width: 18px;' /></td>"
                                . "<td class=''>" . $list["empid"] . "</td>"
                                . "<td class=''>" . $list["role_name"] . "</td>"
                                . "<td class=''>" . $list["name1"] . "</td>"
                                . "<td class=''>" . $list["stats"] . "</td>"
                                . "<td class='text-center'>"
                                . "<a href='?action=$action&id=" . $list["empid"] . "' class='btn btn-outline-dark btn-xs'><i class='material-icons'>edit</i> Edit</a>"
                                . "</td>"
                                . "</tr>";
                            }
                          }
                          ?>
                        </tbody>
                      </table>
                      <button name="chg_status" type="submit" class="btn btn-dark-blue btn-sm mb-3">Change Status of Selected Row(s)</button>
                    </form>
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
    </div>
  </div>

  <input type="hidden" name="id" id="empid">

  <div class="modal fade" id="modal_delete" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modal_upload_label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <form method="GET" action="" enctype="multipart/form-data">
          <div class="modal-header">
            <h5 class="modal-title" id="modal_upload_label"><span class="material-icons">delete</span> Delete Data Member/Operator</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="input-group mb-3">
              <label class="custom-label" for="delete-confirmation">Apakah Anda yakin ingin menghapus data ini ?</label>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Cancel</button>
            <a id="checkDelete" type="submit" class="btn btn-outline-danger">Delete</a>
          </div>
      </div>
      </form>
    </div>
  </div>

  <?php include 'common/t_js.php'; ?>
  <script src="vendors/ega/js/scripts.js?time=<?php echo date("Ymdhis"); ?>" type="text/javascript"></script>
  <script>
    $(document).ready(function() {
      $("#data-table-x").DataTable({
        stateSave: true,
        order: [
          [0, 'desc']
        ],
        dom: "<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-2'l><'col-sm-12 col-md-4'f>>" +
          "<'row'<'col-sm-12'tr>>" +
          "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        buttons: [{
          extend: 'excel',
          title: "Member/Operator List",
          className: 'btn btn-primary btn-sm',
          text: '<i class="material-icons">download</i>Download Excel',
          exportOptions: {
            columns: [0, 1, 2]
          }
        }, ]
      });

      $("#checkAll").change(function() {
        $("input[type='checkbox']").prop("checked", this.checked);
      });

      $('#checkDelete').on('click', function() {
        $.ajax({
          type: "GET",
          url: "?action=api_delete_material",
          data: {
            matnr: $('#empid').val()
          },
          success: function(response) {
            var result = $.parseJSON(response);
            var empid2 = $('#empid').val()
            // console.log(empid2)
            if (result.count > 0) {
              var alertDiv = $('<div>')
                .addClass('alert alert-danger mb-2')
                .attr('role', 'alert')
                .text("Tidak bisa dihapus!. Data ini sudah digunakan dalam transaksi produksi.");
              $('#alert-container').append(alertDiv);
              $('.alert').fadeOut(4000);
            } else {
              window.location.href = '<?php echo $action; ?>?id=' + empid2 + '&delete=delete';
            }
          }
        });
      });
    });

    function openModal(empid) {
      $("#empid").val(empid);
      $("#modal_delete").modal("show");
    }

    $('td').each(function() {
      if ($(this).html() == 'Active') {
        $(this).css('color', 'green');
      } else if ($(this).html() == 'Inactive') {
        $(this).css('color', 'red');
      }
    });
  </script>
</body>

</html>