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
              LIMIT $limit OFFSET $offset";

    return mysqli_query($this->koneksi, $query);
  }

  public function search($keyword)
  {
    $query = "SELECT * FROM karyawan 
              WHERE nama LIKE '%$keyword%'
              OR jabatan LIKE '%$keyword%'
              ORDER BY id DESC";

    return mysqli_query($this->koneksi, $query);
  }

  public function getById($id)
  {
    $query = "SELECT * FROM karyawan WHERE id = '$id'";

    return mysqli_query($this->koneksi, $query);
  }

  public function create($nama, $jabatan, $gajiPokok, $tunjangan, $potongan)
  {
    $query = "INSERT INTO karyawan
                  (nama, jabatan, gaji_pokok, tunjangan, potongan)
                  VALUES
                  ('$nama', '$jabatan', '$gajiPokok', '$tunjangan', '$potongan')";

    return mysqli_query($this->koneksi, $query);
  }

  public function update($id, $nama, $jabatan, $gajiPokok, $tunjangan, $potongan)
  {
    $query = "UPDATE karyawan SET
                nama = '$nama',
                jabatan = '$jabatan',
                gaji_pokok = '$gajiPokok',
                tunjangan = '$tunjangan',
                potongan = '$potongan'
              WHERE id = '$id'";

    return mysqli_query($this->koneksi, $query);
  }
  public function delete($id)
  {
    $query = "DELETE FROM karyawan WHERE id = '$id'";

    return mysqli_query($this->koneksi, $query);
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
