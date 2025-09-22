<?php
$host = 'localhost'; 
$user = 'root'; 
$password = ''; 
$dbname = 'driveolla'; 

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

header('Content-Type: application/json');

// Jika ada parameter id, ambil 1 mobil
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM mobil WHERE id_mobil = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $mobil = $result->fetch_assoc();
    echo json_encode($mobil ?: []);
} else {
    // Ambil semua mobil
    $sql = "SELECT * FROM mobil";
    $result = $conn->query($sql);
    $cars = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $cars[] = $row;
        }
    }

    echo json_encode($cars);
}

$conn->close();
?>
