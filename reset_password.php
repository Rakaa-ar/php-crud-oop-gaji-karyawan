<?php

include 'classes/Database.php';
include 'classes/User.php';

$database = new Database();
$koneksi = $database->connect();

$user = new User($koneksi);

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $email = $_POST['email'];
  $password = $_POST['password'];

  if ($user->resetPassword($email, $password)) {
    $success = "Password berhasil diubah!";

    header('Refresh: 2; URL=login.php');
  } else {
    $error = "Gagal mengubah password!";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Reset Password</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

  <link rel="stylesheet" href="assets/login.css">
</head>

<body>

  <div class="container min-vh-100 d-flex justify-content-center align-items-center">

    <div class="login-card shadow">

      <div class="welcome-panel">

        <h1>Reset<br>Password</h1>

        <p>
          Buat password baru<br>
          untuk akun anda.
        </p>

        <a href="login.php" class="btn btn-outline-light">
          LOGIN
        </a>

      </div>

      <div class="login-panel">

        <h1>Reset Password</h1>

        <p class="text-muted">
          Masukkan email dan password baru
        </p>

        <form method="POST">

          <?php if ($error): ?>

            <div class="alert alert-danger">
              <?= $error ?>
            </div>

          <?php endif; ?>

          <?php if ($success): ?>

            <div class="alert alert-success">
              <?= $success ?>
            </div>

          <?php endif; ?>

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
              placeholder="Password Baru"
              required>

          </div>

          <button type="submit" class="btn login-btn">
            RESET PASSWORD
          </button>

        </form>

      </div>

    </div>

  </div>

</body>

</html>