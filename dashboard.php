<?php
include 'classes/database.php';
include 'classes/karyawan.php';

$db = new Database();

$koneksi = $db->connect();

$karyawan = new Karyawan($koneksi);

$resultGrafik = $karyawan->getDataGrafik();
$dataGrafik = [];

while ($row = mysqli_fetch_assoc($resultGrafik)) {
  $dataGrafik[] = $row;
}

$resultKaryawan = $karyawan->countKaryawan();
$dataKaryawan = mysqli_fetch_assoc($resultKaryawan);
$totalKaryawan = $dataKaryawan['total'];

$resultGaji = $karyawan->TotalGajiPokok();
$dataGaji = mysqli_fetch_assoc($resultGaji);
$totalGaji = $dataGaji['total'];

$resultGajiBersih = $karyawan->TotalGajiBersih();
$dataGaji = mysqli_fetch_assoc($resultGajiBersih);
$totalGajiBersih = $dataGaji['total'];


include 'layout/header.php';
?>

<div class="container mt-4">
  <div class="row">
    <div class="col-md-4">
      <div class="card shadow-sm border-0">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <i class="bi bi-people-fill fs-1 me-3"></i>
            <div>
              <p class="mb-1 text-muted">
                Total Karyawan
              </p>
              <h3 class="mb-0">
                <?= $totalKaryawan; ?>
              </h3>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card shadow-sm border-0">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <i class="bi bi-cash-stack fs-1 me-3"></i>
            <div>
              <p class="mb-1 text-muted">
                Total Gaji Pokok
              </p>
              <h3 class="mb-0">
                Rp <?= number_format($totalGaji, 0, ',', '.'); ?>
              </h3>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card shadow-sm border-0">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <i class="bi bi-cash-stack fs-1 me-3"></i>
            <div>
              <p class="mb-1 text-muted">
                Total Gaji Bersih
              </p>
              <h3 class="mb-0">
                Rp <?= number_format($totalGajiBersih, 0, ',', '.'); ?>
              </h3>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="container mt-4">
      <div class="card shadow-sm border-0">
        <div class="card-body">
          <h5 class="card-title bi bi-bar-chart-fill mb-4">
            Grafik Gaji Karyawan
          </h5>
          <canvas id="grafikGaji"></canvas>
        </div>
      </div>
    </div>

    <script>
      const namaKaryawan =
        <?= json_encode(array_column($dataGrafik, 'nama')); ?>;

      const gajiKaryawan =
        <?= json_encode(array_column($dataGrafik, 'gaji_pokok')); ?>;
    </script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="assets/js/dashboard.js"></script>

    <?php include 'layout/footer.php'; ?>