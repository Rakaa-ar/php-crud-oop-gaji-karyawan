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

  $dataUser = $user->forgetpassword($email);

  if ($dataUser) {
    header("Location: reset_password.php");
    exit;
  } else {
    $error = "Email tidak ditemukan!";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Forgot Password</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

  <link rel="stylesheet" href="assets/login.css">
</head>

<body>

  <div class="container min-vh-100 d-flex justify-content-center align-items-center">

    <div class="login-card shadow">

      <div class="welcome-panel">

        <h1>Lupa<br>Password?</h1>

        <p>
          Masukkan email yang<br>
          terdaftar untuk melanjutkan.
        </p>

        <a href="login.php" class="btn btn-outline-light">
          LOGIN
        </a>

      </div>

      <div class="login-panel">

        <h1>Forgot Password</h1>

        <p class="text-muted">
          Masukkan email akun anda
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

          <div class="mb-4">

            <input
              type="email"
              name="email"
              class="form-control"
              placeholder="Email"
              required>

          </div>

          <button type="submit" class="btn login-btn">
            KIRIM
          </button>

        </form>

      </div>

    </div>

  </div>

</body>

</html>