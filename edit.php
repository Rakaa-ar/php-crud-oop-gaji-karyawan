<?php

include 'classes/database.php';
include 'classes/karyawan.php';

$db = new Database();
$koneksi = $db->connect();

$karyawan = new Karyawan($koneksi);

$id = $_GET['id'];

$data = $karyawan->getById($id);
$row = mysqli_fetch_assoc($data);

if (isset($_POST['update'])) {

  $nama = $_POST['nama'];
  $jabatan = $_POST['jabatan'];
  $gajiPokok = $_POST['gaji_pokok'];
  $tunjangan = $_POST['tunjangan'];
  $potongan = $_POST['potongan'];

  $karyawan->update(
    $id,
    $nama,
    $jabatan,
    $gajiPokok,
    $tunjangan,
    $potongan
  );

  header('Location: index.php?success=update');
  exit;
}
include 'layout/header.php';
?>

<div class="container mt-4">

  <div class="card shadow-sm border-0">

    <div class="card-header text-white">
      <h4 class="mb-0">
        <i class="bi bi-pencil-square me-2"></i>
        Edit Karyawan
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
            value="<?= htmlspecialchars($row['nama']); ?>"
            required>
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
            value="<?= htmlspecialchars($row['jabatan']); ?>"
            required>
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
            value="<?= $row['gaji_pokok']; ?>"
            required>
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
            value="<?= $row['tunjangan']; ?>"
            required>
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
            value="<?= $row['potongan']; ?>"
            required>
        </div>

        <div class="d-flex gap-2">

          <button
            type="submit"
            name="update"
            class="btn btn-warning">
            <i class="bi bi-save me-1"></i>
            Update
          </button>

          <a
            href="index.php"
            class="btn btn-danger">
            <i class="bi bi-arrow-left me-1"></i>
            Kembali
          </a>

        </div>

      </form>

    </div>

  </div>

</div>

<?php include 'layout/footer.php'; ?>

