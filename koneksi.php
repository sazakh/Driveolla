<?php
$host = 'localhost';  // Sesuaikan dengan host database kamu
$username = 'root';  // Username database kamu
$password = '';  // Password database kamu
$dbname = 'driveolla';  // Nama database yang kamu gunakan

// Koneksi ke database
$conn = new mysqli($host, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
