<?php

class Database
{
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "gaji_karyawan";

    public function connect()
    {
        $koneksi = mysqli_connect(
            $this->host,
            $this->username,
            $this->password,
            $this->database
        );

        return $koneksi;
    }
}