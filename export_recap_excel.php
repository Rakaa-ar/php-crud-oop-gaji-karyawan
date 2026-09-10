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

require 'vendor/autoload.php';

include 'classes/database.php';
include 'classes/riwayat_gaji.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

$db = new Database();
$koneksi = $db->connect();

$riwayatGaji = new RiwayatGaji($koneksi);

$bulan = $_GET['bulan'] ?? date('Y-m');

$result = $riwayatGaji->getRekapByBulan($bulan);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    $data = [
        'jumlah_karyawan' => 0,
        'total_gaji_pokok' => 0,
        'total_tunjangan' => 0,
        'total_potongan' => 0,
        'total_gaji_bersih' => 0
    ];
}

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setTitle('Rekap Payroll');

/* Judul */
$sheet->mergeCells('A1:B1');
$sheet->setCellValue('A1', 'REKAP PAYROLL');

$sheet->getStyle('A1:B1')->getFont()
    ->setBold(true)
    ->setSize(16);

$sheet->getStyle('A1:B1')->getAlignment()
    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

/* Periode */
$sheet->setCellValue('A3', 'Periode');
$sheet->setCellValue('B3', $bulan);

/* Data */
$sheet->setCellValue('A5', 'Jumlah Karyawan');
$sheet->setCellValue('B5', $data['jumlah_karyawan']);

$sheet->setCellValue('A6', 'Total Gaji Pokok');
$sheet->setCellValue('B6', $data['total_gaji_pokok']);

$sheet->setCellValue('A7', 'Total Tunjangan');
$sheet->setCellValue('B7', $data['total_tunjangan']);

$sheet->setCellValue('A8', 'Total Potongan');
$sheet->setCellValue('B8', $data['total_potongan']);

$sheet->setCellValue('A9', 'Total Gaji Bersih');
$sheet->setCellValue('B9', $data['total_gaji_bersih']);

/* Styling */
$sheet->getStyle('A5:A9')
    ->getFont()
    ->setBold(true);

$sheet->getStyle('B6:B9')
    ->getNumberFormat()
    ->setFormatCode('#,##0');

$sheet->getColumnDimension('A')->setWidth(25);
$sheet->getColumnDimension('B')->setWidth(25);

/* Download */
$filename = 'rekap_payroll_' . $bulan . '.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;