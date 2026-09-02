<?php

class User
{
  private $koneksi;

  public function __construct($koneksi)
  {
    $this->koneksi = $koneksi;
  }

  public function login($email, $password)
  {
    $query  = "SELECT * FROM users WHERE email = ?";

    $stmt = mysqli_prepare($this->koneksi, $query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
      return $user;
    }

    return false;
  }

  public function register($nama, $email, $password)
  {
    $query = "INSERT INTO users (nama, email, password)
    VALUES (?, ?, ?)";

    $stmt = mysqli_prepare($this->koneksi, $query);
    $password = password_hash($password, PASSWORD_DEFAULT);
    mysqli_stmt_bind_param($stmt, "sss", $nama, $email, $password);

    return mysqli_stmt_execute($stmt);
  }

  public function forgetpassword($email)
  {
    $query = "SELECT * FROM users WHERE email = ?";

    $stmt = mysqli_prepare($this->koneksi, $query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
      return $user;
    }

    return false;
  }

  public function resetPassword($email, $password)
  {
    $query = "UPDATE users SET password = ? WHERE email = ?";

    $stmt = mysqli_prepare($this->koneksi, $query);

    $password = password_hash($password, PASSWORD_DEFAULT);

    mysqli_stmt_bind_param($stmt, "ss", $password, $email);

    return mysqli_stmt_execute($stmt);
  }
}
