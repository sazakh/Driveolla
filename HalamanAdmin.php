<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
  header('Location: Login.php');
  exit;
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>DRIVEOLLA</title>
  <link rel="stylesheet" href="HalamanAdmin.css" />
</head>
<body>
  <div class="outer-box">
    <div class="logout-wrapper">
      <a href="pemesanan.php" class="btn-pemesanan">Lihat Pemesanan</a>
      <a href="tracking_mobil.php" class="btn-tracking">Tracking Mobil</a>
      <button id="logoutBtn">Logout</button>
    </div>   
    <div class="container">
      <h1>Dashboard Admin</h1>
      <div class="car-grid" id="carGrid">
        <!-- Data mobil dan tambah mobil akan muncul di sini -->
      </div>
    </div>
  </div>

  <script src="HalamanAdmin.js"></script>
</body>
</html>
