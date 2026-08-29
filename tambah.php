<?php
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
            type="number"
            name="gaji_pokok"
            class="form-control"
            placeholder="Masukkan gaji pokok">
        </div>

        <div class="mb-3">
          <label class="form-label">
            <i class="bi bi-plus-circle me-1"></i>
            Tunjangan
          </label>

          <input
            type="number"
            name="tunjangan"
            class="form-control"
            placeholder="Masukkan tunjangan">
        </div>

        <div class="mb-4">
          <label class="form-label">
            <i class="bi bi-dash-circle me-1"></i>
            Potongan
          </label>

          <input
            type="number"
            name="potongan"
            class="form-control"
            placeholder="Masukkan potongan">
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

    </div>
  </div>

</div>

<?php include "layout/footer.php"; ?>