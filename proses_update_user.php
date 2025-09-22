<?php
include "koneksi.php";

$id = $_POST['id_user'];
$nama = $_POST['nama'];
$username = $_POST['username'];
$email = $_POST['email'];
$no_hp = $_POST['no_hp'];
$password = $_POST['password']; // tambahkan hash jika diperlukan

// Jika ada foto baru
if (!empty($_FILES['foto']['name'])) {
    $foto = $_FILES['foto']['name'];
    $tmp = $_FILES['foto']['tmp_name'];
    $target = "Gambar" . $foto;
    move_uploaded_file($tmp, $target);

    $sql = "UPDATE user SET nama=?, username=?, email=?, no_hp=?, password=?, foto=? WHERE id_user=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssssssi", $nama, $username, $email, $no_hp, $password, $foto, $id);
} else {
    $sql = "UPDATE user SET nama=?, username=?, email=?, no_hp=?, password=? WHERE id_user=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssssi", $nama, $username, $email, $no_hp, $password, $id);
}

mysqli_stmt_execute($stmt);
header("Location: HalamanDataDiri.php");
exit;
