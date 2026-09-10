<?php

session_start();

if (!isset($_SESSION['user_id'])) {
  header('Location: index.php');
}

if ($_SESSION['role'] !== 'admin') {
  header('location: index.php');
}

include 'classes/database.php';
include 'classes/audit_log.php';
include 'classes/riwayat_gaji.php';

$db = new Database();
$koneksi = $db->connect();

$riwayatGaji = new RiwayatGaji($koneksi);
$log = new AuditLog($koneksi);

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
  header('Location: semua_riwayat.php');
  exit;
}

$berhasil = $riwayatGaji->approve($id);

if ($berhasil) {

  $log->createLog(
    $_SESSION['user_id'],
    $id,
    'Approve Payroll',
    'Payroll disetujui'
  );

  header('Location: semua_riwayat.php');
  exit;
}

header('Location: semua_riwayat.php');
exit;
