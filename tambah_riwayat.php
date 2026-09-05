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

$riwayatGaji = new RiwayatGaji($koneksi);
$karyawan = new Karyawan($koneksi);

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
  header('Location: index.php');
  exit;
}

$dataKaryawan = mysqli_fetch_assoc($karyawan->getById($id));

if (!$dataKaryawan) {
  header('Location: index.php');
  exit;
}

$error = [];

if (isset($_POST['simpan'])) {

  $periode = $_POST['periode'];
  $gajiPokok = $_POST['gaji_pokok'];
  $tunjangan = $_POST['tunjangan'];
  $potongan = $_POST['potongan'];

  // Bersihkan format Rupiah
  $gajiPokok = preg_replace('/\D/', '', $gajiPokok);
  $tunjangan = preg_replace('/\D/', '', $tunjangan);
  $potongan = preg_replace('/\D/', '', $potongan);

  // Kalau kosong, anggap 0
  $gajiPokok = $gajiPokok === '' ? 0 : (float) $gajiPokok;
  $tunjangan = $tunjangan === '' ? 0 : (float) $tunjangan;
  $potongan = $potongan === '' ? 0 : (float) $potongan;

  if ($periode == '') {
    $error[] = 'Periode wajib diisi.';
  }

  if ($gajiPokok < 0) {
    $error[] = 'Gaji pokok tidak boleh negatif.';
  }

  if ($tunjangan < 0) {
    $error[] = 'Tunjangan tidak boleh negatif.';
  }

  if ($potongan < 0) {
    $error[] = 'Potongan tidak boleh negatif.';
  }

  if (empty($error)) {

    $riwayatGaji->create(
      $id,
      $periode,
      $gajiPokok,
      $tunjangan,
      $potongan
    );

    header("Location: riwayat.php?id=$id");
    exit;
  }
}

include 'layout/header.php';
?>

<div class="container mt-4">

  <div class="card shadow-sm border-0">

    <div class="card-header text-white">
      <h4 class="mb-0">
        <i class="bi bi-clock-history me-2"></i>
        Tambah Riwayat Gaji
      </h4>
    </div>

    <div class="card-body p-4">

      <div class="mb-4">
        <h5>
          <?= htmlspecialchars($dataKaryawan['nama']); ?>
        </h5>

        <p class="text-muted mb-0">
          <?= htmlspecialchars($dataKaryawan['jabatan']); ?>
        </p>
      </div>

      <form method="POST">

        <!-- PERIODE -->
        <div class="mb-3">
          <label class="form-label">
            <i class="bi bi-calendar3 me-1"></i>
            Periode
          </label>

          <input
            type="date"
            name="periode"
            class="form-control"
            required>
        </div>

        <!-- GAJI POKOK -->
        <div class="mb-3">
          <label class="form-label">
            <i class="bi bi-cash-stack me-1"></i>
            Gaji Pokok
          </label>

          <input
            type="text"
            name="gaji_pokok"
            id="gajiPokok"
            class="form-control"
            placeholder="Rp 0"
            required>
        </div>

        <!-- TUNJANGAN -->
        <div class="mb-3">
          <label class="form-label">
            <i class="bi bi-plus-circle me-1"></i>
            Tunjangan
          </label>

          <input
            type="text"
            name="tunjangan"
            id="tunjangan"
            class="form-control"
            placeholder="Rp 0"
            required>
        </div>

        <!-- POTONGAN -->
        <div class="mb-3">
          <label class="form-label">
            <i class="bi bi-dash-circle me-1"></i>
            Potongan
          </label>

          <input
            type="text"
            name="potongan"
            id="potongan"
            class="form-control"
            placeholder="Rp 0"
            required>
        </div>

        <!-- GAJI BERSIH -->
        <div class="mb-4">
          <label class="form-label">
            <i class="bi bi-wallet2 me-1"></i>
            Gaji Bersih
          </label>

          <input
            type="text"
            id="gajiBersih"
            class="form-control"
            placeholder="Rp 0"
            readonly>
        </div>

        <?php if (!empty($error)): ?>

          <div class="alert alert-danger">
            <ul class="mb-0">

              <?php foreach ($error as $pesan): ?>
                <li>
                  <?= htmlspecialchars($pesan); ?>
                </li>
              <?php endforeach; ?>

            </ul>
          </div>

        <?php endif; ?>

        <div class="d-flex gap-2">

          <button
            type="submit"
            name="simpan"
            class="btn btn-success">

            <i class="bi bi-save me-1"></i>
            Simpan

          </button>

          <a
            href="riwayat.php?id=<?= $id; ?>"
            class="btn btn-danger">

            <i class="bi bi-arrow-left me-1"></i>
            Kembali

          </a>

        </div>

      </form>

      <script>
        const gajiPokok = document.getElementById('gajiPokok');
        const tunjangan = document.getElementById('tunjangan');
        const potongan = document.getElementById('potongan');
        const gajiBersih = document.getElementById('gajiBersih');

        function ambilAngka(value) {
          return Number(value.replace(/\D/g, '')) || 0;
        }

        function formatRupiah(input) {

          let angka = input.value.replace(/\D/g, '');

          if (angka === '') {
            input.value = '';
            hitungGaji();
            return;
          }

          input.value = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0
          }).format(Number(angka));

          hitungGaji();
        }

        function hitungGaji() {

          const pokok = ambilAngka(gajiPokok.value);
          const tunj = ambilAngka(tunjangan.value);
          const pot = ambilAngka(potongan.value);

          const hasil = pokok + tunj - pot;

          gajiBersih.value = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0
          }).format(hasil);
        }

        gajiPokok.addEventListener('input', function() {
          formatRupiah(gajiPokok);
        });

        tunjangan.addEventListener('input', function() {
          formatRupiah(tunjangan);
        });

        potongan.addEventListener('input', function() {
          formatRupiah(potongan);
        });

        document.querySelector('form').addEventListener('submit', function() {

          gajiPokok.value = gajiPokok.value.replace(/\D/g, '');
          tunjangan.value = tunjangan.value.replace(/\D/g, '');
          potongan.value = potongan.value.replace(/\D/g, '');

        });
      </script>

    </div>

  </div>

</div>

<?php include 'layout/footer.php'; ?>