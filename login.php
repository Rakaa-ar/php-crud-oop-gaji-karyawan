<?php

session_start();

include 'config/koneksi.php';
include 'classes/user.php';

$database = new Database();
$koneksi = $database->connect();

$user = new User($koneksi);

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $email = $_POST['email'];
  $password = $_POST['password'];

  $dataUser = $user->login($email, $password);

  if ($dataUser) {

    $_SESSION['user_id'] = $dataUser['id'];
    $_SESSION['nama'] = $dataUser['nama'];
    $_SESSION['email'] = $dataUser['email'];
    $_SESSION['role'] = $dataUser['role'];

    header('Location: dashboard.php');
    exit;
  } else {

    $error = "Email atau password salah!";
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Login</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

  <link rel="stylesheet" href="assets/login.css">
</head>

<body>

  <div class="container min-vh-100 d-flex justify-content-center align-items-center">
    <div class="login-card shadow">
      <div class="welcome-panel">
        <h1>Selamat Datang<br>Kembali!</h1>
        <p>
          Silakan masuk untuk<br>
          mengakses sistem gaji karyawan.
        </p>
        <a href="register.php" class="btn btn-outline-light">
          REGISTER
        </a>
      </div>

      <div class="login-panel">
        <h1>Login</h1>
        <p class="text-muted">
          atau gunakan akun anda untuk masuk
        </p>
        
        <form method="POST"> 
          <?php if ($error): ?>
            <div class="alert alert-danger">
              <?= $error ?>
            </div>
          <?php endif; ?>


          <div class="mb-3">
            <input
              type="email"
              name="email"
              class="form-control"
              placeholder="Email">
          </div>
          <div class="mb-2">
            <input
              type="password"
              name="password"
              class="form-control"
              placeholder="Password">
          </div>
          <div class="text-end mb-4">
            <a href="forget_password.php" class="forgot-password">
              Forgot Password?
            </a>
          </div>
          <button type="submit" class="btn login-btn">
            SIGN IN
          </button>
        </form>
      </div>
    </div>
  </div>

</body>

</html>