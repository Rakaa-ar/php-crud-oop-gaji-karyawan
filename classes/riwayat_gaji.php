<?php
class riwayatGaji
{
  private $koneksi;

  public function __construct($koneksi)
  {
    $this->koneksi = $koneksi;
  }

  public function getByKaryawan($karyawan_id)
  {
    $query = "SELECT * FROM  riwayat_gaji
                WHERE karyawan_id = ?
                ORDER BY periode DESC";

    $stmt = mysqli_prepare($this->koneksi, $query);
    mysqli_stmt_bind_param($stmt, "i", $karyawan_id);
    mysqli_stmt_execute($stmt);

    return mysqli_stmt_get_result($stmt);
  }

  public function create($karyawan_id, $periode, $gaji_pokok, $tunjangan, $potongan)
  {
    $query = "INSERT INTO riwayat_gaji
                (karyawan_id, periode, gaji_pokok, tunjangan, potongan)
                VALUES (?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($this->koneksi, $query);
    mysqli_stmt_bind_param($stmt, "isddd", $karyawan_id, $periode, $gaji_pokok, $tunjangan, $potongan);

    return mysqli_stmt_execute($stmt);
  }

  public function delete($id)
  {
    $query = "DELETE FROM riwayat_gaji WHERE id = ?";

    $stmt = mysqli_prepare($this->koneksi, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);

    return mysqli_stmt_execute($stmt);
  }

  public function update($id, $periode, $gaji_pokok, $tunjangan, $potongan)
  {
    $query = "UPDATE riwayat_gaji
              SET periode = ?, gaji_pokok = ?, tunjangan = ?, potongan =?
              WHERE id = ?";
    $stmt = mysqli_prepare($this->koneksi, $query);

    mysqli_stmt_bind_param(
      $stmt,
      "sdddi",
      $periode,
      $gaji_pokok,
      $tunjangan,
      $potongan,
      $id
    );

    return mysqli_stmt_execute($stmt);
  }

  public function getAllWithKaryawan()
  {
    $query = "SELECT riwayat_gaji.*,karyawan.nama,karyawan.jabatan
              FROM riwayat_gaji INNER JOIN karyawan ON riwayat_gaji.karyawan_id = karyawan.id
              ORDER BY riwayat_gaji.periode DESC";

    $stmt = mysqli_prepare($this->koneksi, $query);
    mysqli_stmt_execute($stmt);

    return mysqli_stmt_get_result($stmt);
  }

  public function getByBulan($bulan)
  {
    $query = "SELECT
                riwayat_gaji.*,
                karyawan.nama,
                karyawan.jabatan
              FROM riwayat_gaji
              INNER JOIN karyawan
                ON riwayat_gaji.karyawan_id = karyawan.id
              WHERE DATE_FORMAT(riwayat_gaji.periode, '%Y-%m') = ?
              ORDER BY riwayat_gaji.periode DESC";

    $stmt = mysqli_prepare($this->koneksi, $query);
    mysqli_stmt_bind_param($stmt, "s", $bulan);
    mysqli_stmt_execute($stmt);

    return mysqli_stmt_get_result($stmt);
  }

  public function getByRange($dari, $sampai)
  {
    $query = "SELECT
                riwayat_gaji.*,
                karyawan.nama,
                karyawan.jabatan
              FROM riwayat_gaji
              INNER JOIN karyawan
                ON riwayat_gaji.karyawan_id = karyawan.id
              WHERE riwayat_gaji.periode BETWEEN ? AND ?
              ORDER BY riwayat_gaji.periode DESC";

    $stmt = mysqli_prepare($this->koneksi, $query);
    mysqli_stmt_bind_param($stmt, "ss", $dari, $sampai);
    mysqli_stmt_execute($stmt);

    return mysqli_stmt_get_result($stmt);
  }

  public function updateStatus($id, $status)
  {
    $query = "UPDATE riwayat_gaji
              SET status = ?
              WHERE id = ?";

    $stmt = mysqli_prepare($this->koneksi, $query);

    mysqli_stmt_bind_param(
      $stmt,
      "si",
      $status,
      $id
    );

    return mysqli_stmt_execute($stmt);
  }
}
