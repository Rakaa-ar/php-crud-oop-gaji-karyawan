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
include 'classes/audit_log.php';

$db = new Database();
$koneksi = $db->connect();

$riwayatGaji = new riwayatGaji($koneksi);
$log = new AuditLog($koneksi);

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    header('Location: semua_riwayat.php');
    exit;
}

// Ubah status menjadi paid
$berhasil = $riwayatGaji->updateStatus($id, 'paid');

if ($berhasil) {

    $log->createLog(
        $_SESSION['user_id'],
        $id,
        'Bayar Payroll',
        'Status payroll diubah menjadi Paid'
    );

    header('Location: semua_riwayat.php?success=paid');
    exit;
}

header('Location: semua_riwayat.php?error=paid');
exit;
