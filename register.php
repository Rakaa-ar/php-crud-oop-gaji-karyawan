<?php

session_start();

include 'classes/database.php';
include 'classes/user.php';

$database = new Database();
$koneksi = $database->connect();

$user = new User($koneksi);

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $nama = $_POST['nama'];
  $email = $_POST['email'];
  $password = $_POST['password'];

  if ($user->register($nama, $email, $password)) {
    header('Location: login.php');
    exit;
  } else {
    $error = "Registrasi gagal!";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/login.css">
</head>

<body>

  <div class="container min-vh-100 d-flex justify-content-center align-items-center">

    <div class="login-card shadow">

      <div class="welcome-panel">

        <h1>Selamat Datang!</h1>

        <p>
          Buat akun untuk<br>
          mengakses sistem gaji karyawan.
        </p>

        <a href="login.php" class="btn btn-outline-light">
          LOGIN
        </a>

      </div>

      <div class="login-panel">

        <h1>Register</h1>

        <p class="text-muted">
          Buat akun baru anda
        </p>

        <form method="POST">

          <?php if ($error): ?>
            <div class="alert alert-danger">
              <?= $error ?>
            </div>
          <?php endif; ?>

          <div class="mb-3">
            <input
              type="text"
              name="nama"
              class="form-control"
              placeholder="Nama"
              required>
          </div>

          <div class="mb-3">
            <input
              type="email"
              name="email"
              class="form-control"
              placeholder="Email"
              required>
          </div>

          <div class="mb-4">
            <input
              type="password"
              name="password"
              class="form-control"
              placeholder="Password"
              required>
          </div>

          <button type="submit" class="btn login-btn">
            REGISTER
          </button>

        </form>

      </div>

    </div>

  </div>

</body>

</html>