<?php

session_start();

if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
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

$dataRiwayat = $riwayatGaji->getByKaryawan($id);

include 'layout/header.php';
?>

<div class="container mt-4">

  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h1 class="mb-1">Riwayat Gaji</h1>
      <p class="text-muted mb-0">
        <?= htmlspecialchars($dataKaryawan['nama']); ?>
        - <?= htmlspecialchars($dataKaryawan['jabatan']); ?>
      </p>
    </div>

    <a href="index.php" class="btn btn-danger">
      <i class="bi bi-arrow-left me-1"></i>
      Kembali
    </a>
  </div>
  <div class="card shadow-sm">
    <div class="card-body">

      <?php if ($_SESSION['role'] === 'admin'): ?>
        <a href="tambah_riwayat.php?id=<?= $id; ?>" class="btn btn-success">
          <i class="bi bi-plus-circle me-1"></i>
          Tambah Riwayat
        </a>
      <?php endif ?>

      <div class="table-responsive">

        <table class="table table-bordered table-striped table-hover align-middle">

          <thead class="table-header-custom">
            <tr>
              <th>No</th>
              <th>Periode</th>
              <th>Gaji Pokok</th>
              <th>Tunjangan</th>
              <th>Potongan</th>
              <th>Gaji Bersih</th>
              <th>Aksi</th>
            </tr>
          </thead>

          <tbody>

            <?php
            $no = 1;

            while ($row = mysqli_fetch_assoc($dataRiwayat)) :
            ?>

              <tr>

                <td><?= $no++; ?></td>

                <td>
                  <?= date('d-m-Y', strtotime($row['periode'])); ?>
                </td>

                <td>
                  Rp <?= number_format($row['gaji_pokok'], 0, ',', '.'); ?>
                </td>

                <td>
                  Rp <?= number_format($row['tunjangan'], 0, ',', '.'); ?>
                </td>

                <td>
                  Rp <?= number_format($row['potongan'], 0, ',', '.'); ?>
                </td>

                <td>
                  Rp <?= number_format(
                        $row['gaji_pokok']
                          + $row['tunjangan']
                          - $row['potongan'],
                        0,
                        ',',
                        '.'
                      ); ?>
                </td>
                <td>
                  <?php if ($_SESSION['role'] === 'admin'): ?>
                    <a
                      href="edit_riwayat.php?id=<?= $row['id']; ?>"
                      class="btn btn-warning btn-sm">

                      <i class="bi bi-pencil-square"></i>
                      Edit

                    </a>
                  <?php endif; ?>

                  <?php if ($_SESSION['role'] === 'admin'): ?>
                    <a
                      href="hapus_riwayat.php?id=<?= $row['id']; ?>"
                      class="btn btn-danger btn-sm"
                      onclick="return confirm('Yakin ingin menghapus riwayat ini?');">

                      <i class="bi bi-trash3-fill"></i>
                      Hapus
                    </a>
                  <?php endif; ?>

                </td>

              </tr>

            <?php endwhile; ?>

          </tbody>

        </table>

      </div>

    </div>
  </div>

</div>

<?php include 'layout/footer.php'; ?>