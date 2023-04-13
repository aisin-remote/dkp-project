<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
    integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
  <title>Print Report</title>
</head>
<style>
  small {
    font-size: 10px;
  }

  .text-bold {
    font-weight: bold;
  }

  .table-condensed {
    border: 1px solid #dee2e6ff;
  }

  @media print {
    @page {
      size: landscape
    }
  }
</style>

<body>
  <div class="row">
    <div class="col-4"></div>
    <div class="col-4 text-center align-middle">
      <h3 class="mb-0">LAPORAN PRODUKSI HARIAN</h3>
      <p>
        <?php echo $data_header["prod_date"]; ?>
      </p>
    </div>
    <div class="col-4"></div>
  </div>
  <div class="row">
    <div class="col-3">
      <table class="table table-sm table-borderless table-condensed mx-2">
        <tbody>
          <tr>
            <td class="align-middle" width="30">
              <small style="font-weight: 700;" class="mb-0">LINE </small>
            </td>
            <td class="align-middle" width="20">
              <small class="mb-0">: </small>
            </td>
            <td class="align-middle" width="75">
              <small class="mb-0">
                <?php echo $data_header["line_name"]; ?>
              </small>
            </td>
            <td class="align-middle" width="30">
              <small style="font-weight: 700;" class="mb-0">CT </small>
            </td>
            <td class="align-middle" width="20">
              <small class="mb-0">: </small>
            </td>
            <td class="align-middle" width="75">
              <small class="mb-0">
                <?= $data["list"][0]["cctime"] ?> <span>detik</span>
              </small>
            </td>
          </tr>
          <tr>
            <td class="align-middle" width="30">
              <small style="font-weight: 700;" class="mb-0">SHIFT </small>
            </td>
            <td class="align-middle" width="20">
              <small class="mb-0">: </small>
            </td>
            <td colspan="4" class="align-middle" width="75">
              <small class="mb-0">
                <?php echo $data_header["shift_name"]; ?>
              </small>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="col-3">
      <table class="table table-sm table-borderless table-condensed mx-2">
        <tbody>
          <tr>
            <td colspan="3">
              <small style="font-weight: 700;" class="mb-0">NAMA MEMBER</small>
            </td>
          </tr>
          <tr>
            <td width="75">
              <small class="mb-0">JP </small>
            </td>
            <td width="20">
              <small class="mb-0">: </small>
            </td>
            <td>
              <small class="mb-0">
                <?php echo $data_header["jp_name"]; ?>
              </small>
            </td>
          </tr>
          <tr>
            <td>
              <small class="mb-0">Lastman </small>
            </td>
            <td>
              <small class="mb-0">: </small>
            </td>
            <td>
              <small class="mb-0">
                <?php echo $data_header["ld_name"]; ?>
              </small>
            </td>
          </tr>
          <tr>
            <td>
              <small class="mb-0">Pos 1 </small>
            </td>
            <td>
              <small class="mb-0">: </small>
            </td>
            <td>
              <small class="mb-0">
                <?php echo $data_header["op1_name"]; ?>
              </small>
            </td>
          </tr>
          <tr>
            <td>
              <small class="mb-0">Pos 2 </small>
            </td>
            <td>
              <small class="mb-0">: </small>
            </td>
            <td>
              <small class="mb-0">
                <?php echo $data_header["op2_name"]; ?>
              </small>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="col-3">
      <table class="table table-sm table-bordered mx-2">
        <tbody>
          <tr>
            <td class="text-center" width="125">
              <small style="font-weight: 700;" class="mb-0">Material </small>
            </td>
            <td class="text-center align-middle" width="125">
              <small class="mb-0">
                <?= $data["list"][0]["name1"] ?>
              </small>
            </td>
            <td class="text-center" width="75">
              <small class="mb-0">&nbsp;</small>
            </td>
            <td class="text-center" width="75">
              <small style="font-weight: 700;" class="mb-0">&nbsp;</small>
            </td>
          </tr>
          <tr>
            <td class="text-center" width="125">
              <small style="font-weight: 700;" class="mb-0">Qty Target Planning </small>
            </td>
            <td class="text-center" width="75">
              <small class="mb-0">&nbsp;</small>
            </td>
            <td class="text-center" width="75">
              <small class="mb-0">&nbsp;</small>
            </td>
            <td class="text-center" width="75">
              <small style="font-weight: 700;" class="mb-0">&nbsp;</small>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  <div class="row">
    <div class="col">
      <table class="table table-sm table-bordered mx-2 text-nowrap">
        <thead>
          <tr>
            <td class="text-center align-middle" rowspan="4">
              <small class="text-bold">Production time</small>
              <br>
              <small class="text-bold">Centang 1</small>
              <br>
              <small class="text-bold">NS</small>
              <br>
              <small class="text-bold">S1</small>
              <br>
              <small class="text-bold">S2</small>
              <br>
              <small class="text-bold">S3</small>
            </td>
            <td class="text-center align-middle" rowspan="4">
              <small class="text-bold">Nett Operasi</small>
            </td>
            <td class="text-center align-middle" rowspan="4">
              <small class="text-bold">Qty planning 100%</small>
            </td>
            <td rowspan="2" class="text-center">
              <small class="text-bold">Type</small>
            </td>
            <td rowspan="2" class="text-center">
              <small class="text-bold">Type</small>
            </td>
            <td rowspan="2" class="text-center">
              <small class="text-bold">Type</small>
            </td>
            <td rowspan="2" class="text-center">
              <small class="text-bold">Type</small>
            </td>
            <td rowspan="2" class="text-center">
              <small class="text-bold">Type</small>
            </td>
            <td rowspan="4" class="text-center align-middle">
              <small class="text-bold">Konten Stop</small>
            </td>
            <td rowspan="4" class="text-center align-middle">
              <small class="text-bold">Stop time</small>
            </td>
            <td rowspan="4" class="text-center align-middle">
              <small class="text-bold">Qty Steuchi</small>
            </td>
            <td rowspan="4" class="text-center align-middle">
              <small class="text-bold">Konten Penanganan</small>
            </td>
            <td rowspan="4" class="text-center align-middle">
              <small class="text-bold">Eksekutor</small>
            </td>
            <td colspan="4" class="text-center align-middle">
              <small class="text-bold">Job & Historical Abnormal</small>
            </td>
            <td colspan="2" class="text-center align-middle">
              <small class="text-bold">Informasi Hasil Trial</small>
            </td>
            <td colspan="2" class="text-center align-middle" width="100">
              <small class="text-bold">Sign Pengawas</small>
            </td>
          </tr>
          <tr>
            <td class="text-center align-middle">
              <small class="text-bold">Cleaning Keeping</small>
              <br>
              <small class="text-bold">Furnace (Min. 3</small>
              <br>
              <small class="text-bold">kali)</small>
            </td>
            <td class="text-center align-middle">
              <small class="text-bold">Abnormal Parameter/Produk</small>
            </td>
            <td class="text-center">
              <small class="text-bold">Konfirmasi</small>
              <br>
              <small class="text-bold">Dept</small>
              <br>
              <small class="text-bold">Terkait</small>
            </td>
            <td class="text-center align-middle">
              <small class="text-bold">Order Repair (Tuliskan</small>
              <br>
              <small class="text-bold">Item req repair)</small>
            </td>
            <td class="text-center align-middle">
              <small class="text-bold">Hasil Trial</small>
              <br>
              <small class="text-bold">Machining</small>
              <br>
              <small class="text-bold">(tulis item jika</small>
              <br>
              <small class="text-bold">NG)</small>
            </td>
            <td class="text-center align-middle">
              <small class="text-bold">KETERANGAN</small>
            </td>
            <td rowspan="3" class="text-center align-middle" width="50">
              <small class="text-bold">JP</small>
            </td>
            <td rowspan="3" class="text-center align-middle" width="50">
              <small class="text-bold">LDR</small>
            </td>
          </tr>
          <tr>
            <td rowspan="2" class="text-center">
              <small class="text-bold">Material</small>
            </td>
            <td rowspan="2" class="text-center">
              <small class="text-bold">Material</small>
            </td>
            <td rowspan="2" class="text-center">
              <small class="text-bold">Material</small>
            </td>
            <td rowspan="2" class="text-center">
              <small class="text-bold">Material</small>
            </td>
            <td rowspan="2" class="text-center">
              <small class="text-bold">Material</small>
            </td>
            <td rowspan="2" class="align-middle">
              <small class="text-bold">*jam</small>
            </td>
            <td class="align-middle">
              <small class="text-bold">Ada Masih NG : </small>
            </td>
            <td rowspan="2" class="align-middle">
              <small class="text-bold">Nama &</small>
              <br>
              <small class="text-bold">Sign</small>
            </td>
            <td rowspan="2" class="align-middle">
              <small class="text-bold">Num & Nama PIC</small>
            </td>
            <td rowspan="2" class="align-middle text-center">
              <small class="text-bold">OK/NG</small>
            </td>
            <td rowspan="2" class="align-middle text-center">
              <small class="text-bold">*Jam Info? *Item NG? *Info Siapa?</small>
              <br>
              <small class="text-bold">*Penanganan Bagaimana? *Hasil?</small>
              <br>
              <small class="text-bold">Cek Leak?</small>
            </td>
          </tr>
          <tr>
            <td class="align-middle">
              <small class="text-bold">Sudah Close :</small>
            </td>
          </tr>
        </thead>
        <tbody>
          <?php
          if (!empty($data["list"])) {
            $i = 1;
            foreach ($data["list"] as $list) {
              echo '<tr>
              <td  class="text-center align-middle">
                <small>' . $list["time_start"] . " - " . $list["time_end"] . '</small>
              </td>
              <td class="text-center align-middle">
                <small>' . $list["prd_time"] . '</small>
              </td>
              <td class="text-center align-middle">
                <small>' . $list["pln_qty"] . ' / ' . $list["tot_pln_qty"] . '</small>
              </td>
              <td class="text-center align-middle">
                <small>' . $list["prd_qty"] . ' / ' . $list["tot_prd_qty"] . '</small>
              </td>
              <td class="text-center align-middle">
                <small></small>
              </td>
              <td class="text-center align-middle">
                <small></small>
              </td>
              <td class="text-center align-middle">
                <small></small>
              </td>
              <td class="text-center align-middle">
                <small></small>
              </td>
              <td class="text-center align-middle">';
              $seq = $i;
              $data_stop = $class2->getStopList($line_id, $prd_dt, $shift, $seq);
              foreach ($data_stop as $data) {
                echo '<small>' . $data["start_time"] . ' - ' . $data["stop_name"] . '</small>
                <br>';
              }
              echo '</td>
              <td class="text-center align-middle">';
              $seq = $i;
              $data_stop = $class2->getStopList($line_id, $prd_dt, $shift, $seq);
              foreach ($data_stop as $data) {
                echo '<small>' . $data["stop_time"] . '</small>
                <br>';
              }
              echo '</td>
              <td class="text-center align-middle">';
              // $seq = $i;
              // $data_steuchi = $class->getSteuchiList($line_id, $prd_dt, $shift, $seq);
              // foreach ($data_steuchi as $data) {
              //   echo '<small>' . $data["steuchi"] . '</small>
              //   <br>';
              // }
              echo '</td>
              <td class="text-center align-middle">';
              $seq = $i;
              $data_stop = $class2->getStopList($line_id, $prd_dt, $shift, $seq);
              foreach ($data_stop as $data) {
                echo '<small>' . $data["action_name"] . '</small>
                <br>';
              }
              echo '</td>
              <td class="text-center align-middle">';
              $seq = $i;
              $data_stop = $class2->getStopList($line_id, $prd_dt, $shift, $seq);
              $stop_exe = $class2->getStopExeReport($line_id, $prd_dt, $shift, $seq);
              foreach ($data_stop as $data) {
                foreach ($stop_exe as $exe) {
                  if ($exe["stop_seq"] == $data["stop_seq"] && $exe["prd_seq"] == $data["prd_seq"]) {
                    echo '<small>' . $data["stop_name"] . ' - ' . $exe["name1"] . '</small>
                    <br>';
                  }
                }
              }
              echo '</td>
              <td class="text-center align-middle">
                <small></small>
                <small></small>
                <small></small>
              </td>
              <td class="text-center align-middle">
                <small></small>
              </td>
              <td class="text-center align-middle">
                <small></small>
              </td>
              <td class="text-center align-middle">
                <small></small>
              </td>
              <td class="text-center align-middle">
                <small></small>
              </td>
              <td class="text-center align-middle">
                <small></small>
              </td>
              <td class="text-center align-middle">
                <small></small>
              </td>
              <td class="text-center align-middle">
                <small></small>
              </td>
            </tr>
            <?php';
              $i++;
            }
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
  <div class="row">
    <div class="col ">
      <table class="table table-sm table-bordered mx-2 text-nowrap">
        <thead>
          <tr>
            <th class="text-center align-middle" rowspan="2">
              <small class="text-bold">MATERIAL</small>
            </th>
            <th class="text-center align-middle" rowspan="2">
              <small class="text-bold">WAKTU KERJA/SHIFT</small>
            </th>
            <th class="text-center align-middle" rowspan="2">
              <small class="text-bold">LOSSTIME TERPLANNING</small>
            </th>
            <th class="text-center align-middle" rowspan="2">
              <small class="text-bold">NETT OPERASI</small>
            </th>
            <th class="text-center align-middle" rowspan="2">
              <small class="text-bold">LOSSTIME</small>
            </th>
            <th class="text-center align-middle" rowspan="2">
              <small class="text-bold">QTY PRODUKSI MESIN</small>
            </th>
            <th class="text-center align-middle" rowspan="2">
              <small class="text-bold">QTY OK LASTMAN</small>
            </th>
            <th class="text-center align-middle" rowspan="2">
              <small class="text-bold">RIL</small>
            </th>
            <th class="text-center align-middle" colspan="11">
              <small class="text-bold">ROL</small>
            </th>
            <th class="text-center align-middle" rowspan="2">
              <small class="text-bold">WIP</small>
            </th>
            <th class="text-center align-middle" rowspan="2">
              <small class="text-bold">EFFICIENCY (A)</small>
            </th>
            <th class="text-center align-middle" rowspan="2">
              <small class="text-bold">LOSSTIME (B)</small>
            </th>
            <th class="text-center align-middle" rowspan="2">
              <small class="text-bold">RIL (C)</small>
            </th>
            <th class="text-center align-middle" rowspan="2">
              <small class="text-bold">ROL (D)</small>
            </th>
            <th class="text-center align-middle" rowspan="2">
              <small class="text-bold">WIP (E)</small>
            </th>
            <th class="text-center align-middle" rowspan="2">
              <small class="text-bold">TOTAL (A+B+C+D+E) = 100 -+ 1%</small>
            </th>
          </tr>
          <tr>
            <th class="text-center align-middle">
              <small class="text-bold">CMM</small>
            </th>
            <th class="text-center align-middle">
              <small class="text-bold">TRIAL MACHINING (CSH)</small>
            </th>
            <th class="text-center align-middle">
              <small class="text-bold">STEUCHI SETUP</small>
            </th>
            <th class="text-center align-middle">
              <small class="text-bold">STEUCHI TROUBLE</small>
            </th>
            <th class="text-center align-middle">
              <small class="text-bold">STEUCHI DANDORI</small>
            </th>
            <th class="text-center align-middle">
              <small class="text-bold">PRODUK JATUH</small>
            </th>
            <th class="text-center align-middle">
              <small class="text-bold">PRODUK NUMPUK</small>
            </th>
            <th class="text-center align-middle">
              <small class="text-bold">SAMPLE</small>
            </th>
            <th class="text-center align-middle">
              <small class="text-bold">KEKOTANSO</small>
            </th>
            <th class="text-center align-middle">
              <small class="text-bold">LOT OUT</small>
            </th>
            <th class="text-center align-middle">
              <small class="text-bold">DLL</small>
            </th>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach ($data2["list"] as $list) {
            ?>
            <tr class="text-center">
              <td>
                <small>
                  <?= $list["mtart"] ?>
                  <?= $list["name1"] ?>
                </small>
              </td>
              <td>
                <small>
                  <?= $list["waktu_shift"] ?>
                </small>
              </td>
              <td>
                <small>
                  <?= $list["loss_time_p"] ?>
                </small>
              </td>
              <td>
                <small>
                  <?= $list["prd_time"] ?>
                </small>
              </td>
              <td>
                <small>
                  <?= $list["loss_time"] ?>
                </small>
              </td>
              <td>
                <small>
                  <?= $list["tot_qty"] ?>
                </small>
              </td>
              <td>
                <small>
                  <?= $list["prd_qty"] ?>
                </small>
              </td>
              <td>
                <small>
                  <?= $list["ril"] ?>
                </small>
              </td>
              <td>
                <small>
                  <?= $list["rol1"] ?>
                </small>
              </td>
              <td>
                <small>
                  <?= $list["rol2"] ?>
                </small>
              </td>
              <td>
                <small>
                  <?= $list["rol3"] ?>
                </small>
              </td>
              <td>
                <small>
                  <?= $list["rol4"] ?>
                </small>
              </td>
              <td>
                <small>
                  <?= $list["rol5"] ?>
                </small>
              </td>
              <td>
                <small>
                  <?= $list["rol7"] ?>
                </small>
              </td>
              <td>
                <small>
                  <?= $list["rol8"] ?>
                </small>
              </td>
              <td>
                <small>
                  <?= $list["rol9"] ?>
                </small>
              </td>
              <td>
                <small>
                  <?= $list["rol10"] ?>
                </small>
              </td>
              <td>
                <small>
                  <?= $list["rol6"] ?>
                </small>
              </td>
              <td>
                <small></small>
              </td>
              <td>
                <small>
                  <?= $list["wip"] ?>
                </small>
              </td>
              <td>
                <small>
                  <?= $list["eff"] ?>
                </small>
              </td>
              <td>
                <small>
                  <?= $list["loss%"] ?>
                </small>
              </td>
              <td>
                <small>
                  <?= $list["ril%"] ?>
                </small>
              </td>
              <td>
                <small>
                  <?= $list["rol%"] ?>
                </small>
              </td>
              <td>
                <small>
                  <?= $list["wip%"] ?>
                </small>
              </td>
              <td>
                <small>
                  <?= $list["total%"] ?>
                </small>
              </td>
            </tr>
            <?php
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</body>

</html>