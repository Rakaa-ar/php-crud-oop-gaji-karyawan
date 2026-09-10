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

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
  header('Location: semua_riwayat.php');
  exit;
}

$data = $riwayatGaji->getDetailWithKaryawan($id);
$row = mysqli_fetch_assoc($data);

if (!$row) {
  header('Location: semua_riwayat.php');
  exit;
}

include 'layout/header.php';
?>

<div class="container mt-4">

  <div class="card shadow-sm border-0">

    <div class="card-header text-center">
      <h3 class="mb-0">
        <i class="bi bi-receipt me-2"></i>
        Slip Gaji
      </h3>
    </div>

    <div class="card-body p-4">

      <div class="row mb-4">

        <div class="col-md-6">
          <strong>Nama</strong>
          <p class="mb-2">
            <?= htmlspecialchars($row['nama']); ?>
          </p>

          <strong>Jabatan</strong>
          <p class="mb-2">
            <?= htmlspecialchars($row['jabatan']); ?>
          </p>
        </div>

        <div class="col-md-6">
          <strong>Periode</strong>
          <p class="mb-2">
            <?= date('F Y', strtotime($row['periode'])); ?>
          </p>

          <strong>Status</strong>
          <p class="mb-2">
            <?php if ($row['status'] === 'paid'): ?>

              <span class="badge bg-success">
                Paid
              </span>

            <?php else: ?>

              <span class="badge bg-warning text-dark">
                Pending
              </span>

            <?php endif; ?>
          </p>
        </div>

      </div>

      <div class="table-responsive">

        <table class="table table-bordered">

          <tr>
            <th>Gaji Pokok</th>
            <td>
              Rp <?= number_format(
                    $row['gaji_pokok'],
                    0,
                    ',',
                    '.'
                  ); ?>
            </td>
          </tr>

          <tr>
            <th>Tunjangan</th>
            <td>
              Rp <?= number_format(
                    $row['tunjangan'],
                    0,
                    ',',
                    '.'
                  ); ?>
            </td>
          </tr>

          <tr>
            <th>Potongan</th>
            <td>
              Rp <?= number_format(
                    $row['potongan'],
                    0,
                    ',',
                    '.'
                  ); ?>
            </td>
          </tr>

          <tr class="table-light">
            <th>Gaji Bersih</th>
            <th>
              Rp <?= number_format(
                    $row['gaji_pokok']
                      + $row['tunjangan']
                      - $row['potongan'],
                    0,
                    ',',
                    '.'
                  ); ?>
            </th>
          </tr>

        </table>

      </div>

    </div>

  </div>

</div>

<?php include 'layout/footer.php'; ?>