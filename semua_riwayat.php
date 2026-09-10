<?php

session_start();

if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}

include 'classes/database.php';
include 'classes/riwayat_gaji.php';

$db = new Database();
$koneksi = $db->connect();

$riwayatGaji = new riwayatGaji($koneksi);

$bulan = $_GET['bulan'] ?? '';
$dari = $_GET['dari'] ?? '';
$sampai = $_GET['sampai'] ?? '';

if ($bulan !== '') {

  $dataRiwayat = $riwayatGaji->getByBulan($bulan);
} elseif ($dari !== '' && $sampai !== '') {

  $dataRiwayat = $riwayatGaji->getByRange($dari, $sampai);
} else {

  $dataRiwayat = $riwayatGaji->getAllWithKaryawan();
}

include 'layout/header.php';
?>

<div class="container mt-4">

  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Semua Riwayat Gaji</h1>

    <a href="index.php" class="btn btn-danger">
      <i class="bi bi-arrow-left me-1"></i>
      Kembali
    </a>
  </div>

  <form method="GET" class="mb-4">

    <div class="row g-3">

      <div class="col-md-4">
        <label class="form-label">Bulan</label>
        <input
          type="month"
          name="bulan"
          class="form-control">
      </div>

      <div class="col-md-4">
        <label class="form-label">Dari</label>
        <input
          type="date"
          name="dari"
          class="form-control">
      </div>

      <div class="col-md-4">
        <label class="form-label">Sampai</label>
        <input
          type="date"
          name="sampai"
          class="form-control">
      </div>

    </div>

    <div class="mt-3 d-flex gap-2">

      <button type="submit" class="btn btn-primary">
        <i class="bi bi-funnel me-1"></i>
        Filter
      </button>

      <a href="semua_riwayat.php" class="btn btn-secondary">
        Reset
      </a>

    </div>

  </form>

  <div class="card shadow-sm">

    <div class="card-body">

      <div class="table-responsive">

        <table class="table table-bordered table-striped table-hover align-middle">

          <thead class="table-header-custom">
            <tr>
              <th>No</th>
              <th>Nama</th>
              <th>Jabatan</th>
              <th>Periode</th>
              <th>Gaji Pokok</th>
              <th>Tunjangan</th>
              <th>Potongan</th>
              <th>Gaji Bersih</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>

          <tbody>


            <?php $no = 1; ?>

            <?php while ($row = mysqli_fetch_assoc($dataRiwayat)): ?>

              <tr>

                <td><?= $no++; ?></td>


                <td>
                  <?= htmlspecialchars($row['nama']); ?>
                </td>

                <td>
                  <?= htmlspecialchars($row['jabatan']); ?>
                </td>

                <td>
                  <?= date('d-m-Y', strtotime($row['periode'])); ?>
                </td>


                <?php if ($_SESSION['role'] === 'admin'): ?>
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
                    <?php if ($row['status'] === 'paid'): ?>

                      <span class="badge bg-success">
                        Paid
                      </span>

                    <?php else: ?>

                      <span class="badge bg-warning text-dark">
                        Pending
                      </span>

                    <?php endif; ?>
                  </td>

                  <td>

                    <a
                      href="slip_gaji.php?id=<?= $row['id']; ?>"
                      class="btn btn-primary btn-sm">

                      <i class="bi bi-receipt me-1"></i>
                      Lihat

                    </a>

                    <?php if ($_SESSION['role'] === 'admin' && $row['status'] === 'pending'): ?>

                      <a
                        href="approve_payroll.php?id=<?= $row['id']; ?>"
                        class="btn btn-warning btn-sm"
                        onclick="return confirm('Approve payroll ini?');">

                        <i class="bi bi-check-circle me-1"></i>
                        Approve

                      </a>

                    <?php elseif ($_SESSION['role'] === 'admin' && $row['status'] === 'approved'): ?>

                      <a
                        href="bayar_riwayat.php?id=<?= $row['id']; ?>"
                        class="btn btn-success btn-sm"
                        onclick="return confirm('Tandai payroll ini sebagai sudah dibayar?');">

                        <i class="bi bi-cash-coin me-1"></i>
                        Bayar

                      </a>

                    <?php elseif ($row['status'] === 'paid'): ?>

                      <span class="text-success ms-1" title="Sudah dibayar">
                        <i class="bi bi-check2-circle fs-5"></i>
                      </span>

                    <?php endif; ?>

                  </td>
                <?php else: ?>

                  <td>XXXXXXXX</td>
                  <td>XXXXXXXX</td>
                  <td>XXXXXXXX</td>
                  <td>XXXXXXXX</td>

                <?php endif; ?>

              </tr>

            <?php endwhile; ?>

          </tbody>

        </table>

      </div>

    </div>

  </div>

</div>

<?php include "layout/footer.php"; ?>