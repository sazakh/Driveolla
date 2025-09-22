<?php
session_start();
require 'koneksi.php';

// Proses login jika ada data POST masuk
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'Login') {
    header('Content-Type: application/json');
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM user WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 's', $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($user = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            $redirect = ($user['role'] === 'admin') ? 'HalamanAdmin.php' : 'index.php';
            echo json_encode(['success' => true, 'redirect' => $redirect]);
            exit;
        }
    }

    echo json_encode(['success' => false, 'message' => 'Username atau password salah']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login</title>
  <link rel="stylesheet" href="Login.css" />
</head>
<body>
  <div class="login-wrapper">
    <div class="login-container">
      <h2>Login</h2>
      <form id="loginForm">
        <label for="username">Username:</label>
        <input type="text" id="username" required />

        <label for="password">Password:</label>
        <input type="password" id="password" required />

        <button type="submit">Login</button>
        <p>Belum punya akun? <a href="signup.php">Daftar di sini</a></p>
      </form>
      <p id="loginError" class="error-message"></p>
    </div>
  </div>
    
    <script src="Login.js"></script>
</body>
</html>
