<?php

session_start();

if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}

include 'classes/database.php';
include 'classes/karyawan.php';
include 'classes/riwayat_gaji.php';

$db = new Database();

$koneksi = $db->connect();

$karyawan = new Karyawan($koneksi);
$riwayatGaji = new riwayatGaji($koneksi);

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

$bulan = $_GET['bulan'] ?? date('Y-m');

$resultPayroll = $riwayatGaji->getDashboardPayroll($bulan);
$dataPayroll = mysqli_fetch_assoc($resultPayroll);

$totalPending = $dataPayroll['pending'] ?? 0;
$totalApproved = $dataPayroll['approved'] ?? 0;
$totalPaid = $dataPayroll['paid'] ?? 0;


include 'layout/header.php';
?>

<div class="mb-3">
  <label class="form-label">
    Periode Payroll
  </label>

  <form method="GET" class="d-flex gap-2">

    <div class="w-25">
      <input
        type="month"
        name="bulan"
        class="form-control"
        value="<?= htmlspecialchars($bulan); ?>"
        required>
    </div>

    <button
      type="submit"
      class="btn btn-primary">

      <i class="bi bi-funnel me-1"></i>
      Tampilkan

    </button>

  </form>
</div>
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
  <div class="col-md-4">
    <div class="card shadow-sm border-0">
      <div class="card-body">
        <div class="d-flex align-items-center">

          <i class="bi bi-hourglass-split fs-1 me-3"></i>

          <div>
            <p class="mb-1 text-muted">
              Payroll Pending
            </p>
            <h3 class="mb-0">
              <?= $totalPending; ?>
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

          <i class="bi bi-check-circle-fill fs-1 me-3"></i>

          <div>
            <p class="mb-1 text-muted">
              Payroll Paid
            </p>

            <h3 class="mb-0">
              <?= $totalPaid; ?>
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
                <i class="bi bi-check-circle-fill fs-1 me-3"></i>

                <div>
                    <p class="mb-1 text-muted">
                        Payroll Approved
                    </p>

                    <h3 class="mb-0">
                        <?= $totalApproved; ?>
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