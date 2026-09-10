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

$result = $riwayatGaji->getDetailPayrollByBulan($bulan);

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setTitle('Detail Payroll');

/* Judul */
$sheet->mergeCells('A1:K1');
$sheet->setCellValue('A1', 'DETAIL PAYROLL');

$sheet->getStyle('A1:K1')->getFont()
    ->setBold(true)
    ->setSize(16);

$sheet->getStyle('A1:K1')->getAlignment()
    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

/* Periode */
$sheet->setCellValue('A2', 'Periode');
$sheet->setCellValue('B2', $bulan);

/* Header */
$headers = [
    'No',
    'Payroll ID',
    'Karyawan ID',
    'Nama',
    'Jabatan',
    'Periode',
    'Gaji Pokok',
    'Tunjangan',
    'Potongan',
    'Gaji Bersih',
    'Status'
];

$column = 'A';

foreach ($headers as $header) {
    $sheet->setCellValue($column . '4', $header);
    $column++;
}

$sheet->getStyle('A4:K4')->getFont()->setBold(true);
$sheet->getStyle('A4:K4')->getAlignment()
    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

/* Data */
$rowExcel = 5;
$no = 1;

while ($row = mysqli_fetch_assoc($result)) {

    $sheet->setCellValue('A' . $rowExcel, $no++);
    $sheet->setCellValue('B' . $rowExcel, $row['payroll_id']);
    $sheet->setCellValue('C' . $rowExcel, $row['karyawan_id']);
    $sheet->setCellValue('D' . $rowExcel, $row['nama']);
    $sheet->setCellValue('E' . $rowExcel, $row['jabatan']);
    $sheet->setCellValue('F' . $rowExcel, $row['periode']);
    $sheet->setCellValue('G' . $rowExcel, $row['gaji_pokok']);
    $sheet->setCellValue('H' . $rowExcel, $row['tunjangan']);
    $sheet->setCellValue('I' . $rowExcel, $row['potongan']);
    $sheet->setCellValue('J' . $rowExcel, $row['gaji_bersih']);
    $sheet->setCellValue('K' . $rowExcel, $row['status']);

    $rowExcel++;
}

/* Format angka */
if ($rowExcel > 5) {
    $sheet->getStyle('G5:J' . ($rowExcel - 1))
        ->getNumberFormat()
        ->setFormatCode('#,##0');
}

/* Lebar kolom */
$widths = [
    'A' => 8,
    'B' => 14,
    'C' => 14,
    'D' => 25,
    'E' => 20,
    'F' => 15,
    'G' => 18,
    'H' => 18,
    'I' => 18,
    'J' => 18,
    'K' => 14
];

foreach ($widths as $col => $width) {
    $sheet->getColumnDimension($col)->setWidth($width);
}

$filename = 'detail_payroll_' . $bulan . '.xlsx';

header(
    'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
);

header(
    'Content-Disposition: attachment; filename="' . $filename . '"'
);

header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

exit;