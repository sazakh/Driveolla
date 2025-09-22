<?php
session_start();

// Jika belum login, redirect ke Login.php
if (!isset($_SESSION['id_user'])) {
    header("Location: Login.php");
    exit;
}

require 'koneksi.php';

// Ambil data mobil
$result = mysqli_query($conn, "SELECT * FROM mobil");
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>DRIVEOLLA</title>
  <a href="index.php" class="back-button">X</a>
  <link rel="stylesheet" href="HalamanProduk.css" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
  <div class="main-wrapper">
    <div class="scroll-area">
      <header class="header">
        <h1>Rental Mobil</h1>
        <input type="text" id="searchInput" placeholder="Cari mobil untuk disewa..." />
      </header>

    <main id="productList" class="products">
      <!-- Kartu produk akan digenerate di sini -->
    </main>
  </div>

  <!-- Letakkan di bawah, pakai defer -->
  <script src="HalamanProduk.js" defer></script>
</body>
</html>
