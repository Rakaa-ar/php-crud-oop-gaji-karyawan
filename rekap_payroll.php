<?php

session_start();

if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}

include "classes/database.php";
include "classes/riwayat_gaji.php";

$db = new Database();
$koneksi = $db->connect();

$riwayatGaji = new riwayatGaji($koneksi);

$bulan = $_GET['bulan'] ?? date('Y-m');

$data = $riwayatGaji->getRekapByBulan($bulan);
$rekap = mysqli_fetch_assoc($data);

include 'layout/header.php';
?>

<form method="GET" class="mb-4">

  <div class="row g-3 align-items-end">

    <div class="col-md-4">
      <label class="form-label">Periode</label>

      <input
        type="month"
        name="bulan"
        class="form-control"
        value="<?= htmlspecialchars($bulan); ?>"
        required>
    </div>

    <div class="col-md-2">
      <button type="submit" class="btn btn-primary">
        <i class="bi bi-funnel me-1"></i>
        Tampilkan
      </button>
    </div>

  </div>

</form>

<div class="row g-4">

  <div class="col-md-6">
    <div class="card shadow-sm border-0">
      <div class="card-body">
        <h5>
          <i class="bi bi-people me-2"></i>
          Jumlah Karyawan
        </h5>

        <h2>
          <?= $rekap['jumlah_karyawan'] ?? 0; ?>
        </h2>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="card shadow-sm border-0">
      <div class="card-body">
        <h5>
          <i class="bi bi-cash-stack me-2"></i>
          Total Gaji Pokok
        </h5>

        <h2>
          Rp <?= number_format(
                $rekap['total_gaji_pokok'] ?? 0,
                0,
                ',',
                '.'
              ); ?>
        </h2>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="card shadow-sm border-0">
      <div class="card-body">
        <h5>
          <i class="bi bi-plus-circle me-2"></i>
          Total Tunjangan
        </h5>

        <h2>
          Rp <?= number_format(
                $rekap['total_tunjangan'] ?? 0,
                0,
                ',',
                '.'
              ); ?>
        </h2>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="card shadow-sm border-0">
      <div class="card-body">
        <h5>
          <i class="bi bi-dash-circle me-2"></i>
          Total Potongan
        </h5>

        <h2>
          Rp <?= number_format(
                $rekap['total_potongan'] ?? 0,
                0,
                ',',
                '.'
              ); ?>
        </h2>
      </div>
    </div>
  </div>

  <div class="col-12">
    <div class="card shadow-sm border-0">
      <div class="card-body">
        <h5>
          <i class="bi bi-wallet2 me-2"></i>
          Total Gaji Bersih
        </h5>

        <h2>
          Rp <?= number_format(
                $rekap['total_gaji_bersih'] ?? 0,
                0,
                ',',
                '.'
              ); ?>
        </h2>
        <div class="d-flex justify-content-end mt-4">
          <a href="index.php" class="btn btn-danger">
            <i class="bi bi-arrow-left me-1"></i>
            Kembali
          </a>
        </div>
      </div>
    </div>
  </div>

</div>

</div>

<?php include 'layout/footer.php'; ?>