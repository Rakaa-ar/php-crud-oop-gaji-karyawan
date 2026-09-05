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

$db = new Database();
$koneksi = $db->connect();

$riwayatGaji = new RiwayatGaji($koneksi);

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    header('Location: index.php');
    exit;
}

/*
 * Ambil karyawan_id terlebih dahulu
 * supaya setelah hapus kita bisa kembali
 * ke halaman riwayat karyawan yang benar.
 */
$query = "SELECT karyawan_id FROM riwayat_gaji WHERE id = ?";

$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    header('Location: index.php');
    exit;
}

$karyawan_id = $data['karyawan_id'];

/*
 * Hapus riwayat
 */
$riwayatGaji->delete($id);

header("Location: riwayat.php?id=$karyawan_id");
exit;