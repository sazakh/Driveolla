<?php
include 'koneksi.php';

if (!isset($_GET['id_mobil'])) {
  header('Location: HalamanAdmin.php');
  exit;
}

$id = (int)$_GET['id_mobil'];
// Ambil data mobil dari DB
$sql = "SELECT * FROM mobil WHERE id_mobil = $id";
$res = mysqli_query($conn, $sql);
if (!$res || mysqli_num_rows($res)===0) {
  echo "Mobil tidak ditemukan.";
  exit;
}
$car = mysqli_fetch_assoc($res);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Edit Mobil</title>
  <link rel="stylesheet" href="HalamanAdmin.css">
</head>
<body>
  <div class="outer-box">
    <h1>Edit Mobil</h1>
    <form action="update_mobil.php" method="post" enctype="multipart/form-data">
      <!-- ID tersembunyi -->
      <input type="hidden" name="id_mobil" value="<?= $car['id_mobil'] ?>">
      <label>Merk:</label>
      <input type="text" name="merk" value="<?= htmlspecialchars($car['merk']) ?>" required>

      <label>Tipe:</label>
      <input type="text" name="tipe" value="<?= htmlspecialchars($car['tipe']) ?>" required>

      <label>Tahun:</label>
      <input type="number" name="tahun" value="<?= $car['tahun'] ?>" required>

      <label>Tarif per Hari:</label>
      <input type="number" name="tarif_per_hari" value="<?= htmlspecialchars($car['tarif_per_hari']) ?>" required>

      <label>Detail:</label>
      <textarea name="Detail_mobil" value="<?= htmlspecialchars($car['deta']) ?>" required>
      
      <label>Status:</label>
      <select name="status" required>
        <option value="tersedia" <?= $car['status']==='tersedia' ? 'selected' : '' ?>>Tersedia</option>
        <option value="disewa"    <?= $car['status']==='disewa'    ? 'selected' : '' ?>>Disewa</option>
      </select>

      <label>Gambar Sekarang:</label><br>
      <img src="<?= htmlspecialchars($car['gambar']) ?>" alt="" style="max-width:200px;margin-bottom:10px;"><br>

      <label>Ganti Gambar (opsional):</label>
      <input type="file" name="image" accept="image/*">

      <button type="submit">Simpan Perubahan</button>
      <a href="admin.php"><button type="button">Batal</button></a>
    </form>
  </div>
</body>
</html>
