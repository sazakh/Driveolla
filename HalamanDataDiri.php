<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include "koneksi.php";

if (!isset($_SESSION['id_user'])) {
  header("Location: login.php");
  exit;
}

$id_user = $_SESSION['id_user'];
$sql = "SELECT * FROM user WHERE id_user = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id_user);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

if (!$data) {
  echo "User tidak ditemukan.";
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>DRIVEOLLA - Data Diri</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
  <style>
    * {
      box-sizing: border-box;
    }
    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f2f6fc;
      color: #333;
      line-height: 1.5;
    }
    .profile-container {
      display: flex;
      min-height: 100vh;
    }
    .sidebar {
      background-color:rgb(70, 75, 80);
      width: 300px;
      padding: 30px 20px;
      color: white;
      display: flex;
      flex-direction: column;
      align-items: center;
      position: relative;
    }
    .back-button {
      position: absolute;
      top: 15px;
      left: 15px;
      background: transparent;
      border: none;
      font-size: 32px;
      color: white;
      cursor: pointer;
      font-weight: 700;
      line-height: 1;
    }
    .profile-pic {
      margin-top: 50px;
      text-align: center;
    }
    .profile-pic img {
      width: 130px;
      height: 130px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid white;
      box-shadow: 0 0 8px rgba(255, 255, 255, 0.6);
    }
    .edit-photo-btn {
      display: inline-block;
      margin-top: 15px;
      background-color:rgb(17, 17, 17);
      color: #fff;
      padding: 8px 16px;
      border-radius: 20px;
      cursor: pointer;
      font-size: 14px;
      transition: background-color 0.3s ease;
      user-select: none;
      margin-bottom: -10px;
    }
    .edit-photo-btn:hover {
      background-color:rgb(0, 0, 0);
    }
    .profile-content {
      flex-grow: 1;
      padding: 10px 40px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      background: #fff;
    }
    .profile-content h1 {
      margin-bottom: 32px;
      font-size: 28px;
      font-weight: 700;
      color:rgb(57, 62, 67);
      text-align: center;
    }
    .display-name{


    }
    form {
      background: #fff;
      padding: 30px 25px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      max-width: 2000px; /* diperlebar */
      margin: 0 auto;
      border: 1px solid #ddd;
      width: 1100px;
    }
    label {
      display: block;
      margin-bottom: 8px;
      font-weight: 600;
      font-size: 15px;
      color: #444;
    }
    input[type="text"],
    input[type="email"],
    input[type="tel"],
    input[type="password"] {
      width: 100%;
      padding: 12px 14px;
      margin-bottom: 20px;
      border: 1.8px solid #ccc;
      border-radius: 8px;
      font-size: 16px;
      transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }
    input[type="text"]:focus,
    input[type="email"]:focus,
    input[type="tel"]:focus,
    input[type="password"]:focus {
      border-color:rgb(57, 62, 67);
      box-shadow: 0 0 6px rgba(45,137,229,0.4);
      outline: none;
      background-color: #f0f7ff;
    }
    button[type="submit"] {
      width: 100%;
      margin-top: 12px;
      padding: 14px 0;
      font-size: 17px;
      border-radius: 8px;
      font-weight: 700;
      background-color:rgb(78, 83, 88);
      color: white;
      border: none;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }
    button[type="submit"]:hover {
      background-color:rgb(95, 99, 103);
    }
    small {
      font-size: 13px;
      color: #777;
      margin-top: -16px;
      margin-bottom: 12px;
      display: block;
    }
    .password-wrapper {
      position: relative;
      margin-bottom: 20px;
    }
    .password-wrapper i {
      position: absolute;
      right: 12px;
      top: 30%;
      transform: translateY(-50%);
      cursor: pointer;
      color: #888;
      font-size: 18px;
      transition: color 0.3s ease;
    }
    .password-wrapper i:hover {
      color:rgb(57, 62, 67);
    }
    
  </style>
</head>
<body>
  <div class="profile-container">
    <div class="sidebar">
      <button class="back-button" onclick="window.history.back()">×</button>

      <!-- FOTO PROFIL -->
      <div class="profile-pic" title="Klik untuk ubah foto">
        <img id="profile-picture" src="Gambar/<?= htmlspecialchars($data['foto'] ?: 'default.jpg') ?>" alt="Foto Profil">
      </div>

      <!-- TOMBOL EDIT FOTO DI BAWAH -->
      <label for="image-upload" class="edit-photo-btn">Edit Foto</label>
      <input type="file" id="image-upload" name="foto" form="profile-form" accept="image/*" style="display:none;">

      <!-- NAMA -->
      <h2 id="display-name"><?= htmlspecialchars($data['nama']) ?></h2>
    </div>

    <div class="profile-content">
      <h1>Data Diri</h1>
      <form id="profile-form" action="proses_update_user.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id_user" value="<?= $data['id_user'] ?>">

        <label for="name">Nama</label>
        <input type="text" id="name" name="nama" value="<?= htmlspecialchars($data['nama']) ?>" required>

        <label for="username">Username</label>
        <input type="text" id="username" name="username" value="<?= htmlspecialchars($data['username']) ?>" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($data['email']) ?>" required>

        <label for="phone">Nomor HP</label>
        <input type="tel" id="phone" name="no_hp" value="<?= htmlspecialchars($data['no_hp']) ?>">

        <label for="password">Password</label>
        <div class="password-wrapper">
          <input type="password" id="password" name="password" placeholder="Kosongkan jika tidak ingin ganti password" value="<?= htmlspecialchars($data['password']) ?>" required>
          <i id="toggle-password" class="fas fa-eye"></i>
        </div>

        <button type="submit">Simpan Perubahan</button>
      </form>
    </div>
  </div>

  <script>
    const togglePassword = document.getElementById('toggle-password');
    const passwordInput = document.getElementById('password');
    togglePassword.addEventListener('click', () => {
      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        togglePassword.classList.replace('fa-eye', 'fa-eye-slash');
      } else {
        passwordInput.type = 'password';
        togglePassword.classList.replace('fa-eye-slash', 'fa-eye');
      }
    });

    document.querySelector('.edit-photo-btn').addEventListener('click', function(){
      document.getElementById('image-upload').click();
    });
  </script>
</body>
</html>
