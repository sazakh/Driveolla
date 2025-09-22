<?php
// update_mobil.php
include 'koneksi.php';

// Ambil data dari form
$id    = (int)$_POST['id_mobil'];
$merk  = mysqli_real_escape_string($conn, $_POST['merk']);
$tipe  = mysqli_real_escape_string($conn, $_POST['tipe']);
$tahun = (int)$_POST['tahun'];
$tarif = mysqli_real_escape_string($conn, $_POST['tarif_per_hari']);
$status= mysqli_real_escape_string($conn, $_POST['status']);
$kapasitas= mysqli_real_escape_string($conn, $_POST['kapasitas']);
$bensin= mysqli_real_escape_string($conn, $_POST['bensin']);
$transmisi= mysqli_real_escape_string($conn, $_POST['transmisi']);
$drive= mysqli_real_escape_string($conn, $_POST['drive']);
$restriksi_driver= mysqli_real_escape_string($conn, $_POST['restriksi_driver']);

// Mulai susun query untuk update lainnya
$updates = "merk=?, tipe=?, tahun=?, tarif_per_hari=?, status=?, kapasitas=?, bensin=?, transmisi=?, drive=?, restriksi_driver=?";

// Cek apakah ada file gambar baru
if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
    // Validasi gambar
    $fileName = basename($_FILES['image']['name']);
    $target   = "Gambar/" . $fileName;
    $imageFileType = strtolower(pathinfo($target, PATHINFO_EXTENSION));

    // Daftar ekstensi file yang diperbolehkan
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
    $max_file_size = 2 * 1024 * 1024; // Maksimal 2MB

    // Cek apakah file gambar
    if (!in_array($imageFileType, $allowed_types)) {
        die("Hanya file gambar dengan ekstensi JPG, JPEG, PNG, atau GIF yang diperbolehkan.");
    }

    // Cek ukuran file
    if ($_FILES['image']['size'] > $max_file_size) {
        die("File terlalu besar. Maksimal ukuran gambar adalah 2MB.");
    }

    // Cek jika file berhasil di-upload
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $updates .= ", gambar=?";
    } else {
        die("Gagal upload gambar.");
    }
}

// Gunakan prepared statement untuk update
$sql = "UPDATE mobil SET $updates WHERE id_mobil=?";
$stmt = mysqli_prepare($conn, $sql);
if ($stmt) {
    // Bind parameter
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $stmt->bind_param("sssdss", $merk, $tipe, $tahun, $tarif, $status, $kapasitas, $bensin, $transmisi, $drive, $restriksi_driver, $target);
    } else {
        $stmt->bind_param("sssdss", $merk, $tipe, $tahun, $tarif, $status, $kapasitas, $bensin, $transmisi, $drive, $restriksi_driver, $empty); // jika tidak ada gambar baru, kosongkan parameter gambar
    }

    // Eksekusi query
    if (mysqli_stmt_execute($stmt)) {
        header('Location: admin.php?msg=update_success');
    } else {
        die("Gagal update: " . mysqli_error($conn));
    }
    mysqli_stmt_close($stmt);
} else {
    die("Error preparing statement: " . mysqli_error($conn));
}

mysqli_close($conn);
?>
