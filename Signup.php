<?php
session_start();
require 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Menetapkan role sebagai 'customer' secara default
    $role = 'customer';

    // Hash password untuk keamanan
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Cek apakah username sudah ada
    $sql_check = "SELECT * FROM user WHERE username = '$username'";
    $result_check = mysqli_query($conn, $sql_check);

    if (mysqli_num_rows($result_check) > 0) {
        $error_message = "Username sudah terdaftar!";
    } else {
        // Masukkan data pengguna baru dengan role default 'customer'
        $sql_insert = "INSERT INTO user (username, password, role) VALUES ('$username', '$hashed_password', '$role')";
        if (mysqli_query($conn, $sql_insert)) {
            header("Location: Login.php");
            exit;
        } else {
            $error_message = "Terjadi kesalahan saat registrasi!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="Login.css" />
</head>
<body>
 <div class="login-wrapper">
    <div class="login-container">
        <h2>Sign Up</h2>
            <?php if (isset($error_message)): ?>
             <p style="color: red;"><?php echo $error_message; ?></p>
                <?php endif; ?>
            <form id="loginForm" method="POST">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>

                <button type="submit">Sign Up</button>
                <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
            </form>
            <p id="loginError" class="error-message"></p>
        </div>
    </div>
</body>
</html>
