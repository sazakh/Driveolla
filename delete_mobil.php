<?php
// Koneksi ke database
$mysqli = new mysqli("localhost", "root", "", "driveolla");

// Mengecek koneksi database
if ($mysqli->connect_error) {
  die("Connection failed: " . $mysqli->connect_error);
}

// Menyiapkan query untuk mengambil data mobil
$query  = "SELECT * FROM mobil";
$result = $mysqli->query($query);

// Mengecek apakah query berhasil
if (!$result) {
  die("Error in query execution: " . $mysqli->error);
}

$cars = [];
// Mengambil hasil query dan menambahkannya ke array
while ($row = $result->fetch_assoc()) {
  $cars[] = $row;
}

// Mengecek apakah data ada
if (empty($cars)) {
  die("No data found.");
}

// Mengirimkan header untuk konten JSON
header('Content-Type: application/json');
// Mengubah data ke format JSON dan mengirimkannya
echo json_encode($cars);

// Menutup koneksi database
$mysqli->close();
?>
