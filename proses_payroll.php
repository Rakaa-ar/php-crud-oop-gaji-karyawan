<?php

session_start();

if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}

if ($_SESSION['role'] !== 'admin') {
  header('Location: index.php');
  exit;
}

include 'classes/database.php';
include 'classes/riwayat_gaji.php';
include 'classes/karyawan.php';

$db = new Database();
$koneksi = $db->connect();

$riwayatGaji = new riwayatGaji($koneksi);
$karyawan = new Karyawan($koneksi);

$error = '';
$sukses = '';

if (isset($_POST['generate'])) {

  $periode = $_POST['periode'];

  if ($periode == '') {

    $error = 'Periode wajib dipilih.';
  } else {

    // Ubah 2026-09 menjadi tanggal awal bulan
    $periodeTanggal = $periode . '-01';

    // Ambil semua karyawan
    $dataKaryawan = $karyawan->getAll();

    $berhasil = 0;
    $dilewati = 0;

    while ($row = mysqli_fetch_assoc($dataKaryawan)) {

      $karyawanId = $row['id'];

      // Cek apakah payroll periode ini sudah ada
      $queryCek = "SELECT id
                        FROM riwayat_gaji
                        WHERE karyawan_id = ?
                        AND periode = ?";

      $stmtCek = mysqli_prepare($koneksi, $queryCek);

      mysqli_stmt_bind_param(
        $stmtCek,
        "is",
        $karyawanId,
        $periodeTanggal
      );

      mysqli_stmt_execute($stmtCek);

      $hasilCek = mysqli_stmt_get_result($stmtCek);

      if (mysqli_num_rows($hasilCek) > 0) {

        // Sudah pernah diproses
        $dilewati++;
        continue;
      }

      // Ambil data gaji dari tabel karyawan
      $gajiPokok = $row['gaji_pokok'];
      $tunjangan = $row['tunjangan'];
      $potongan = $row['potongan'];

      // Simpan ke riwayat gaji
      $riwayatGaji->create(
        $karyawanId,
        $periodeTanggal,
        $gajiPokok,
        $tunjangan,
        $potongan
      );

      $berhasil++;
    }

    $sukses = "Payroll berhasil dibuat: $berhasil karyawan. $dilewati karyawan sudah diproses.";
  }
}

include 'layout/header.php';
?>

<div class="container mt-4">

  <div class="card shadow-sm border-0">

    <div class="card-header text-white">
      <h4 class="mb-0">
        <i class="bi bi-calculator me-2"></i>
        Proses Payroll
      </h4>
    </div>

    <div class="card-body p-4">

      <?php if ($error): ?>

        <div class="alert alert-danger">
          <?= htmlspecialchars($error); ?>
        </div>

      <?php endif; ?>

      <?php if ($sukses): ?>

        <div class="alert alert-success">
          <?= htmlspecialchars($sukses); ?>
        </div>

      <?php endif; ?>

      <form method="POST">

        <div class="mb-4">

          <label class="form-label">
            <i class="bi bi-calendar3 me-1"></i>
            Periode Payroll
          </label>

          <input
            type="month"
            name="periode"
            class="form-control"
            required>

        </div>

        <button
          type="submit"
          name="generate"
          class="btn btn-success">

          <i class="bi bi-play-circle me-1"></i>
          Generate Payroll

        </button>

        <a
          href="index.php"
          class="btn btn-danger">

          <i class="bi bi-arrow-left me-1"></i>
          Kembali

        </a>

      </form>

    </div>

  </div>

</div>

<?php include 'layout/footer.php'; ?>