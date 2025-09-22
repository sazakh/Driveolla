<?php
// Koneksi ke database
$mysqli = new mysqli("localhost", "root", "", "driveolla");

if ($mysqli->connect_error) {
  die("Connection failed: " . $mysqli->connect_error);
}

$id_mobil = $_POST['id_mobil'];
$query = "DELETE FROM mobil WHERE id_mobil = $id_mobil";

if ($mysqli->query($query) === TRUE) {
  echo "Mobil berhasil dihapus";
} else {
  echo "Gagal menghapus mobil: " . $mysqli->error;
}

$mysqli->close();
?>
