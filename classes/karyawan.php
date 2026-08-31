<?php

class Karyawan
{
  private $koneksi;

  public function __construct($koneksi)
  {
    $this->koneksi = $koneksi;
  }

  public function getAll()
  {
    $query = "SELECT * FROM karyawan ORDER BY id DESC";

    return mysqli_query($this->koneksi, $query);
  }

  public function getPagination($limit, $offset)
  {
    $query = "SELECT * FROM karyawan
              ORDER BY id DESC
              LIMIT ? OFFSET ?";

    $stmt = mysqli_prepare($this->koneksi, $query);

    mysqli_stmt_bind_param($stmt, "ii", $limit, $offset);

    mysqli_stmt_execute($stmt);

    return mysqli_stmt_get_result($stmt);
  }


  public function search($keyword)
  {
    $query = "SELECT * FROM karyawan
              WHERE nama LIKE ?
              OR jabatan LIKE ?";

    $stmt = mysqli_prepare($this->koneksi, $query);

    $keyword = "%$keyword%";

    mysqli_stmt_bind_param($stmt, "ss", $keyword, $keyword);

    mysqli_stmt_execute($stmt);

    return mysqli_stmt_get_result($stmt);
  }

  public function getById($id)
  {
    $query = "SELECT * FROM karyawan 
              WHERE id = ?";

    $stmt = mysqli_prepare($this->koneksi, $query);

    mysqli_stmt_bind_param($stmt, "i", $id);

    mysqli_stmt_execute($stmt);

    return mysqli_stmt_get_result($stmt);
  }

  public function create($nama, $jabatan, $gajiPokok, $tunjangan, $potongan)
  {
    $query = "INSERT INTO karyawan
                  (nama, 
                  jabatan, 
                  gaji_pokok, 
                  tunjangan, 
                  potongan)
                  VALUES
                  (?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($this->koneksi, $query);

    mysqli_stmt_bind_param($stmt, "ssiii", $nama, $jabatan, $gajiPokok, $tunjangan, $potongan);

    return mysqli_stmt_execute($stmt);
  }

  public function update($id, $nama, $jabatan, $gajiPokok, $tunjangan, $potongan)
  {
    $query = "UPDATE karyawan SET
                nama = ?,
                jabatan = ?,
                gaji_pokok = ?,
                tunjangan = ?,
                potongan = ?
              WHERE id = ?";

    $stmt = mysqli_prepare($this->koneksi, $query);

    mysqli_stmt_bind_param($stmt, "ssiiii", $nama, $jabatan, $gajiPokok, $tunjangan, $potongan, $id);

    return mysqli_stmt_execute($stmt);
  }

  public function delete($id)
  {
    $query = "DELETE FROM karyawan 
              WHERE id = ?";

    $stmt = mysqli_prepare($this->koneksi, $query);

    mysqli_stmt_bind_param($stmt, "i", $id);

    return  mysqli_stmt_execute($stmt);
  }


  public function countKaryawan()
  {
    $query = "SELECT COUNT(*) AS total FROM karyawan";

    return mysqli_query($this->koneksi, $query);
  }
  public function TotalGajiPokok()
  {
    $query = "SELECT SUM(gaji_pokok) AS total FROM karyawan";

    return mysqli_query($this->koneksi, $query);
  }
  public function TotalGajiBersih()
  {
    $query = "SELECT SUM(gaji_pokok + tunjangan - potongan) AS total FROM karyawan";

    return mysqli_query($this->koneksi, $query);
  }
  public function getDataGrafik()
  {
    $query = "SELECT nama, gaji_pokok FROM karyawan";

    return mysqli_query($this->koneksi, $query);
  }
}
