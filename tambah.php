<?php

session_start();

if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}

include 'classes/database.php';
include 'classes/karyawan.php';

$db = new Database();
$koneksi = $db->connect();

$karyawan = new Karyawan($koneksi);

if (isset($_POST['simpan'])) {

  $nama = $_POST['nama'];
  $jabatan = $_POST['jabatan'];
  $gajiPokok = $_POST['gaji_pokok'];
  $tunjangan = $_POST['tunjangan'];
  $potongan = $_POST['potongan'];

  $error = [];
  if ($nama == '') {
    $error[] = 'Nama wajib diisi.';
  }

  if ($jabatan == '') {
    $error[] = 'Jabatan wajib diisi.';
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
    $karyawan->create(
      $nama,
      $jabatan,
      $gajiPokok,
      $tunjangan,
      $potongan
    );
    header("Location: index.php?success=tambah");
    exit;
  }
}

include 'layout/header.php';
?>
<div class="container mt-4">

  <div class="card shadow-sm border-0">

    <div class="card-header text-white">
      <h4 class="mb-0">
        <i class="bi bi-person-plus-fill me-2"></i>
        Tambah Karyawan
      </h4>
    </div>

    <div class="card-body p-4">

      <form method="POST">

        <div class="mb-3">
          <label class="form-label">
            <i class="bi bi-person me-1"></i>
            Nama
          </label>

          <input
            type="text"
            name="nama"
            class="form-control"
            placeholder="Masukkan nama karyawan">
        </div>

        <div class="mb-3">
          <label class="form-label">
            <i class="bi bi-briefcase me-1"></i>
            Jabatan
          </label>

          <input
            type="text"
            name="jabatan"
            class="form-control"
            placeholder="Masukkan jabatan">
        </div>

        <div class="mb-3">
          <label class="form-label">
            <i class="bi bi-cash-stack me-1"></i>
            Gaji Pokok
          </label>
          <input
            type="text"
            name="gaji_pokok"
            class="form-control"
            id="gajipokok"
            placeholder="Masukkan gaji pokok">
        </div>

        <div class="mb-3">
          <label class="form-label">
            <i class="bi bi-plus-circle me-1"></i>
            Tunjangan
          </label>
          <input
            type="text"
            name="tunjangan"
            class="form-control"
            id="tunjangan"
            placeholder="Masukkan tunjangan">
        </div>

        <div class="mb-4">
          <label class="form-label">
            <i class="bi bi-dash-circle me-1"></i>
            Potongan
          </label>
          <input
            type="text"
            name="potongan"
            class="form-control"
            id="potongan"
            placeholder="Masukkan potongan">
        </div>

        <div class="mb-3">
          <label class="form-label">
            <i class="bi bi-cash-stack me-1"></i>
            Gaji Bersih
          </label>
          <input
            type="text"
            id="gajiBersih"
            class="form-control"
            readonly>
        </div>

        <div class="d-flex gap-2">

          <button
            type="submit"
            name="simpan"
            class="btn btn-success">
            <i class="bi bi-save me-1"></i>
            Simpan
          </button>

          <a
            href="index.php"
            class="btn btn-danger">
            <i class="bi bi-arrow-left me-1"></i>
            Kembali
          </a>

        </div>
        <?php if (!empty($error)): ?>

          <div class="alert alert-danger">
            <ul class="mb-0">
              <?php foreach ($error as $pesan): ?>
                <li><?= $pesan; ?></li>
              <?php endforeach; ?>
            </ul>
          </div>

        <?php endif; ?>

      </form>
      <script>
        const gajiPokok = document.getElementById('gajipokok');
        const tunjangan = document.getElementById('tunjangan');
        const potongan = document.getElementById('potongan');
        const gajiBersih = document.getElementById('gajiBersih');

        function hitungGaji() {
          const pokok = Number(gajiPokok.value.replace(/\D/g, '')) || 0;
          const tunj = Number(tunjangan.value.replace(/\D/g, '')) || 0;
          const pot = Number(potongan.value.replace(/\D/g, '')) || 0;

          const hasil = pokok + tunj - pot;

          gajiBersih.value = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0
          }).format(hasil);
        }

        gajiPokok.addEventListener('input', hitungGaji);
        tunjangan.addEventListener('input', hitungGaji);
        potongan.addEventListener('input', hitungGaji);

        function formatRupiah(input) {
          let angka = input.value.replace(/\D/g, '');

          if (angka === '') {
            input.value = '';
            return;
          }

          input.value = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0
          }).format(Number(angka));
        }

        gajiPokok.addEventListener('input', function() {
          formatRupiah(gajiPokok);
          hitungGaji();
        });

        tunjangan.addEventListener('input', function() {
          formatRupiah(tunjangan);
          hitungGaji();
        });

        potongan.addEventListener('input', function() {
          formatRupiah(potongan);
          hitungGaji();
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

<?php include "layout/footer.php"; ?>