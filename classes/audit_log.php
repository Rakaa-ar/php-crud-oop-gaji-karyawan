<?php
class  AuditLog
{
  private $koneksi;
  public function __construct($koneksi)
  {
    $this->koneksi = $koneksi;
  }

  public function createLog($user_id, $payroll_id, $aksi, $detail)
  {
    $query = "INSERT INTO audit_log
              (user_id, payroll_id, aksi, detail)
              VALUES (?, ?, ?, ?)";

    $stmt = mysqli_prepare($this->koneksi, $query);

    mysqli_stmt_bind_param(
      $stmt,
      "iiss",
      $user_id,
      $payroll_id,
      $aksi,
      $detail
    );

    return mysqli_stmt_execute($stmt);
  }

  public function getAll()
  {
    $query = "SELECT
                audit_log.id,
                audit_log.payroll_id,
                audit_log.aksi,
                audit_log.detail,
                audit_log.created_at,
                users.nama AS user_nama,
                riwayat_gaji.karyawan_id,
                riwayat_gaji.periode,
                riwayat_gaji.status,
                karyawan.nama AS karyawan_nama
              FROM audit_log

              INNER JOIN users
                  ON audit_log.user_id = users.id

              LEFT JOIN riwayat_gaji
                  ON audit_log.payroll_id = riwayat_gaji.id

              LEFT JOIN karyawan
                  ON riwayat_gaji.karyawan_id = karyawan.id

              ORDER BY audit_log.created_at DESC";

    return mysqli_query($this->koneksi, $query);
  }
}
