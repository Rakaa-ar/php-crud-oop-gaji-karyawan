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
}
