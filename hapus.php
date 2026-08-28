<?php

include 'classes/database.php';
include 'classes/karyawan.php';

$db = new Database();
$koneksi = $db->connect();

$karyawan = new Karyawan($koneksi);

$id = $_GET['id'];

$karyawan->delete($id);

header('Location: index.php?success=hapus');
exit;