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

$riwayatGaji = new riwayatGaji($koneksi);

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    header('Location: semua_riwayat.php');
    exit;
}

$riwayatGaji->updateStatus($id, 'paid');

header('Location: semua_riwayat.php');
exit;