<?php
// Koneksi ke database
$mysqli = new mysqli("localhost", "root", "", "driveolla");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Ambil data dari POST
$merk = $mysqli->real_escape_string($_POST['merk']);
$tipe = $mysqli->real_escape_string($_POST['tipe']);
$tahun = (int)$_POST['tahun']; // Tahun diubah ke integer
$price = (float)$_POST['tarif_per_hari']; // Harga diubah ke float
$status = $mysqli->real_escape_string($_POST['status']);
$kapasitas = $mysqli->real_escape_string($_POST['kapasitas']);
$bensin = $mysqli->real_escape_string($_POST['bensin']);
$transmisi = $mysqli->real_escape_string($_POST['transmisi']);
$drive = $mysqli->real_escape_string($_POST['drive']);
$restriksi_driver = $mysqli->real_escape_string($_POST['restriksi_driver']);

// Validasi input gambar
$image = $_FILES['image']['name'];
$tmp_name = $_FILES['image']['tmp_name'];
$error = $_FILES['image']['error'];

// Cek apakah file gambar
$target_dir = "Gambar/";
$target_file = $target_dir . basename($image);
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Batas ukuran file (contoh 2MB)
$max_file_size = 2 * 1024 * 1024; // 2MB

// Daftar ekstensi yang diperbolehkan
$allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

// Cek jika file adalah gambar
$check = getimagesize($tmp_name);
if ($check === false) {
    echo "File yang diupload bukan gambar.";
    exit();
}

// Cek ekstensi file
if (!in_array($imageFileType, $allowed_types)) {
    echo "Hanya file gambar dengan ekstensi JPG, JPEG, PNG, atau GIF yang diperbolehkan.";
    exit();
}

// Cek ukuran file
if ($_FILES['image']['size'] > $max_file_size) {
    echo "File terlalu besar, maksimal ukuran gambar adalah 2MB.";
    exit();
}

// Cek error upload
if ($error !== UPLOAD_ERR_OK) {
    echo "Gagal upload gambar. Error code: $error";
    exit();
}

// Buat nama file unik
$unique_name = uniqid() . "_" . basename($image);
$final_target = $target_dir . $unique_name;

// Upload file
if (move_uploaded_file($tmp_name, $final_target)) {
    // Gunakan prepared statement untuk insert
    $stmt = $mysqli->prepare("INSERT INTO mobil (merk, tipe, tahun, tarif_per_hari, status, kapasitas, bensin, transmisi, drive, restriksi_driver, gambar) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("sssdsssssss", $merk, $tipe, $tahun, $price, $status, $kapasitas, $bensin, $transmisi, $drive, $restriksi_driver, $final_target);

        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "Gagal menambahkan mobil: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Prepare statement gagal: " . $mysqli->error;
    }
} else {
    echo "Gagal upload gambar ke server.";
}

$mysqli->close();
?>
