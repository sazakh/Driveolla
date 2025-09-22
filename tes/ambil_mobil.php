<?php
// Koneksi ke database
$host = 'localhost'; 
$user = 'root'; 
$password = ''; 
$dbname = 'driveolla'; 

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query untuk mengambil data mobil
$sql = "SELECT * FROM mobil";
$result = $conn->query($sql);

// Membuat array untuk menampung data mobil
$cars = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cars[] = $row;
    }
}

// Mengirimkan data dalam format JSON
header('Content-Type: application/json');
echo json_encode($cars);

$conn->close();
?>
