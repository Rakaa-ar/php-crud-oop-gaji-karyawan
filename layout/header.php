<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Gaji Karyawan</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet">
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
        rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css?v=2">
</head>

<body>

    <nav class="navbar navbar-custom">
        <div class="container">
            <a href="index.php" class="navbar-brand">
                Gaji Karyawan
            </a>
            <div class="ms-auto d-flex align-items-center gap-2">

                <a href="index.php" class="nav-link px-3 py-2 rounded">
                    <i class="bi bi-house-fill me-1"></i>
                    Home
                </a>
                <div class="ms-auto dropdown">
                    <button
                        class="btn btn-skyblue"
                        type="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">

                        <i class="bi bi-three-dots-vertical fs-4"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a href="proses_payroll.php" class="dropdown-item">
                                <i class="bi bi-calculator me-2"></i>
                                Proses PAYROLL
                            </a>
                        </li>

                        <li>
                            <a href="rekap_payroll.php" class="dropdown-item">
                                <i class="bi bi-bar-chart me-2"></i>
                                Rekap PAYROLL
                            </a>
                        </li>

                        <li>
                            <a href="audit_log.php" class="dropdown-item">
                                <i class="bi bi-journal-text me-2"></i>
                                Audit Log
                            </a>
                        </li>

                        <li>
                            <a href="semua_riwayat.php" class="dropdown-item">
                                <i class="bi bi-clock-history me-2"></i>
                                Riwayat Gaji
                            </a>
                        </li>

                        <li>
                            <a href="dashboard.php" class="dropdown-item">
                                <i class="bi bi-bar-chart-line-fill me-2"></i>
                                Dashboard
                            </a>
                        </li>

                        <?php if ($_SESSION['role'] === 'admin'): ?>
                            <li>
                                <a href="tambah.php" class="dropdown-item">
                                    <i class="bi bi-person-plus-fill me-2"></i>
                                    Tambah
                                </a>
                            </li>
                        <?php endif; ?>

                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a href="logout.php" class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i>
                                Logout
                            </a>
                        </li>

                    </ul>

                </div>

            </div>
    </nav>
</body>
<div class="container mt-4">