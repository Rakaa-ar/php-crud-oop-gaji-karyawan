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

$query = "SELECT * FROM riwayat_gaji WHERE id = ?";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$dataRiwayat = mysqli_fetch_assoc($result);

if (!$dataRiwayat) {
  header('Location: index.php');
  exit;
}

$dataKaryawan = mysqli_fetch_assoc(
  $karyawan->getById($dataRiwayat['karyawan_id'])
);

$error = [];

if (isset($_POST['update'])) {

  $periode = $_POST['periode'];

  $gajiPokok = preg_replace('/\D/', '', $_POST['gaji_pokok']);
  $tunjangan = preg_replace('/\D/', '', $_POST['tunjangan']);
  $potongan = preg_replace('/\D/', '', $_POST['potongan']);

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

    $riwayatGaji->update(
      $id,
      $periode,
      $gajiPokok,
      $tunjangan,
      $potongan
    );

    header(
      "Location: riwayat.php?id=" .
        $dataRiwayat['karyawan_id']
    );
    exit;
  }
}

include 'layout/header.php';
?>

<div class="container mt-4">

  <div class="card shadow-sm border-0">

    <div class="card-header text-white">
      <h4 class="mb-0">
        <i class="bi bi-pencil-square me-2"></i>
        Edit Riwayat Gaji
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

        <div class="mb-3">
          <label class="form-label">Periode</label>

          <input
            type="date"
            name="periode"
            class="form-control"
            value="<?= htmlspecialchars($dataRiwayat['periode']); ?>"
            required>
        </div>

        <div class="mb-3">
          <label class="form-label">Gaji Pokok</label>

          <input
            type="text"
            name="gaji_pokok"
            id="gajiPokok"
            class="form-control"
            min="0"
            value="Rp <?= number_format($dataRiwayat['gaji_pokok'], 0, ',', '.'); ?>"
            required>
        </div>

        <div class="mb-3">
          <label class="form-label">Tunjangan</label>

          <input
            type="text"
            name="tunjangan"
            id="tunjangan"
            class="form-control"
            min="0"
            value="Rp <?= number_format($dataRiwayat['tunjangan'], 0, ',', '.'); ?>"
            required>
        </div>

        <div class="mb-4">
          <label class="form-label">Potongan</label>

          <input
            type="text"
            name="potongan"
            id="potongan"
            class="form-control"
            min="0"
            value="Rp <?= number_format($dataRiwayat['potongan'], 0, ',', '.'); ?>"
            required>
        </div>

        <?php if (!empty($error)): ?>

          <div class="alert alert-danger">
            <ul class="mb-0">

              <?php foreach ($error as $pesan): ?>
                <li><?= htmlspecialchars($pesan); ?></li>
              <?php endforeach; ?>

            </ul>
          </div>

        <?php endif; ?>

        <div class="d-flex gap-2">

          <button
            type="submit"
            name="update"
            class="btn btn-warning">

            <i class="bi bi-save me-1"></i>
            Update

          </button>

          <a
            href="riwayat.php?id=<?= $dataRiwayat['karyawan_id']; ?>"
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