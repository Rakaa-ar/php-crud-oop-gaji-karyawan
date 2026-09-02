<?php

session_start();

if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}

include "classes/database.php";
include "classes/karyawan.php";

$db = new Database();
$koneksi = $db->connect();

$karyawan = new Karyawan($koneksi);

$keyword = $_GET['keyword'] ?? '';

$limit = 5;

$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

$offset = ($page - 1) * $limit;

$resultCount = $karyawan->countKaryawan();

$dataCount = mysqli_fetch_assoc($resultCount);

$totalKaryawan = $dataCount['total'];

$totalPages = ceil($totalKaryawan / $limit);

if ($keyword != '') {
  $dataKaryawan = $karyawan->search($keyword);
} else {
  $dataKaryawan = $karyawan->getPagination($limit, $offset);
}

include 'layout/header.php';

// logic index.php
// ...
?>

<?php if (isset($_GET['success'])): ?>

  <?php if ($_GET['success'] === 'tambah'): ?>

    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="bi bi-check-circle me-1"></i>
      Karyawan berhasil ditambahkan.

      <button type="button"
        class="btn-close"
        data-bs-dismiss="alert"></button>
    </div>

  <?php elseif ($_GET['success'] === 'update'): ?>

    <div class="alert alert-warning alert-dismissible fade show" role="alert">
      <i class="bi bi-check-circle me-1"></i>
      Data karyawan berhasil diperbarui.

      <button type="button"
        class="btn-close"
        data-bs-dismiss="alert"></button>
    </div>

  <?php elseif ($_GET['success'] === 'hapus'): ?>

    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <i class="bi bi-check-circle me-1"></i>
      Data karyawan berhasil dihapus.

      <button type="button"
        class="btn-close"
        data-bs-dismiss="alert"></button>
    </div>

  <?php endif; ?>

<?php endif; ?>
<form method="GET" class="mb-3">
  <div class="input-group">
    <input
      type="text"
      name="keyword"
      class="form-control"
      placeholder="Cari Nama atau Jabatan...">
    <button type="submit" class="btn btn-light">
      <i class="bi bi-search"></i>
      Cari
    </button>
  </div>
</form>
<div class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Data Karyawan</h1>

    <a href="tambah.php" class=" btn btn-success shadow-ms">
      TAMBAH KARYAWAN
    </a>
  </div>
</div>

<div class="card shadow-sm">

  <div class="card-body">

    <div class="table-responsive">

      <table class="table table-bordered table-striped table-hover align-middle">

        <thead class="table-header-custom">

          <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Jabatan</th>
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

          while ($row = mysqli_fetch_assoc($dataKaryawan)) :
          ?>

            <tr>

              <td><?= $no++; ?></td>

              <td><?= htmlspecialchars($row['nama']); ?></td>

              <td><?= htmlspecialchars($row['jabatan']); ?></td>

              <td>Rp <?= number_format($row['gaji_pokok'], 0, ',', '.'); ?></td>

              <td>Rp <?= number_format($row['tunjangan'], 0, ',', '.'); ?></td>

              <td>Rp <?= number_format($row['potongan'], 0, ',', '.'); ?></td>

              <td>
                Rp
                <?= number_format(
                  $row['gaji_pokok'] + $row['tunjangan'] - $row['potongan'],
                  0,
                  ',',
                  '.'
                ); ?>
              </td>

              <td>

                <a
                  href="edit.php?id=<?= $row['id']; ?>"
                  class="btn btn-secondary btn-sm">
                  <i class="bi bi-pencil-square"></i>
                  Edit
                </a>

                <a
                  href="hapus.php?id=<?= $row['id']; ?>"
                  class="btn btn-danger btn-sm"
                  onclick="return confirm('Yakin ingin menghapus data ini?');">
                  <i class="bi bi-trash3-fill"></i>
                  Hapus
                </a>

              </td>

            </tr>

          <?php endwhile; ?>

        </tbody>

      </table>
      <nav class="mt-3">
        <ul class="pagination justify-content-center">

          <?php for ($i = 1; $i <= $totalPages; $i++): ?>

            <li class="page-item <?= ($page == $i) ? 'active' : ''; ?>">
              <a class="page-link" href="?page=<?= $i; ?>">
                <?= $i; ?>
              </a>
            </li>

          <?php endfor; ?>

        </ul>
      </nav>

    </div>

  </div>

</div>

<?php include "layout/footer.php"; ?>