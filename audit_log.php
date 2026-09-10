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
include 'classes/audit_log.php';

$db = new Database();
$koneksi = $db->connect();

$log = new AuditLog($koneksi);

$dataLog = $log->getAll();

include 'layout/header.php';
?>

<div class="container mt-4">

  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Audit Log</h1>
  </div>

  <div class="card shadow-sm border-0">

    <div class="card-body">

      <div class="table-responsive">

        <table class="table table-bordered table-striped table-hover align-middle">

          <thead>
            <tr>
              <th>No</th>
              <th>User</th>
              <th>Payroll ID</th>
              <th>Karyawan ID</th>
              <th>Nama Karyawan</th>
              <th>Periode</th>
              <th>Aksi</th>
              <th>Status</th>
              <th>Detail</th>
              <th>Tanggal</th>
            </tr>
          </thead>

          <tbody>


            <?php $no = 1; ?>

            <?php while ($row = mysqli_fetch_assoc($dataLog)): ?>

              <tr>

                <td>
                  <?= $no++; ?>
                </td>

                <td>
                  <?= htmlspecialchars($row['user_nama']); ?>
                </td>

                <td>
                  <?= $row['payroll_id'] ?? '-'; ?>
                </td>

                <td>
                  <?= $row['karyawan_id'] ?? '-'; ?>
                </td>

                <td>
                  <?= htmlspecialchars($row['karyawan_nama'] ?? '-'); ?>
                </td>

                <td>
                  <?= !empty($row['periode'])
                    ? date('F Y', strtotime($row['periode']))
                    : '-'; ?>
                </td>

                <td>
                  <?= htmlspecialchars($row['aksi']); ?>
                </td>

                <td>
                  <?php if ($row['status'] === 'paid'): ?>

                    <span class="badge bg-success">
                      Paid
                    </span>

                  <?php elseif ($row['status'] === 'approved'): ?>

                    <span class="badge bg-primary">
                      Approved
                    </span>

                  <?php elseif ($row['status'] === 'pending'): ?>

                    <span class="badge bg-warning text-dark">
                      Pending
                    </span>

                  <?php else: ?>

                    <span class="badge bg-secondary">
                      -
                    </span>

                  <?php endif; ?>
                </td>

                <td>
                  <?= htmlspecialchars($row['detail']); ?>
                </td>

                <td>
                  <?= date(
                    'd-m-Y H:i',
                    strtotime($row['created_at'])
                  ); ?>
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