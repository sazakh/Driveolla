<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_user']) || !isset($_SESSION['role'])) {
    header("Location: Login.php");
    exit;
}

$id_user = $_SESSION['id_user'];
$role = $_SESSION['role'];

if ($role == 'admin') {
    $sql = "
        SELECT m.id_mobil, m.merk, m.latitude, m.longitude, u.nama AS nama_user
        FROM pemesanan p
        JOIN mobil m ON p.id_mobil = m.id_mobil
        JOIN user u ON p.id_user = u.id_user
        WHERE p.status IN ('pending', 'disetujui', 'selesai')
    ";
    $stmt = mysqli_prepare($conn, $sql);
} else {
    $sql = "
        SELECT m.id_mobil, m.merk, m.latitude, m.longitude
        FROM pemesanan p
        JOIN mobil m ON p.id_mobil = m.id_mobil
        WHERE p.id_user = ? AND p.status IN ('pending', 'disetujui', 'selesai')
    ";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id_user);
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$locations = [];
while ($row = mysqli_fetch_assoc($result)) {
    $locations[] = $row;
}

$first_mobil = $locations[0] ?? null;
?>
<!DOCTYPE html>
<html>
<head>
  <title>DRIVEOLLA</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
  <link rel="stylesheet" href="tracking_mobil.css" />
</head>
<body>
  <div class="header">
    <h1>Tracking Lokasi Mobil</h1>
  </div>

  <div class="tracking-container">
    <?php if ($first_mobil): ?>
      <div class="user-info">
        <p><strong><?= htmlspecialchars($first_mobil['merk']) ?></strong></p>
        <?php if ($role == 'admin' && isset($first_mobil['nama_user'])): ?>
          <p>Disewa oleh: <?= htmlspecialchars($first_mobil['nama_user']) ?></p>
        <?php endif; ?>
      </div>
    <?php endif; ?>

    <div id="map"></div>
  </div>

  <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
  <script>
    const map = L.map('map').setView([-7.3274, 108.2207], 6);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 18,
    }).addTo(map);

    const mobilData = <?php echo json_encode($locations); ?>;

    mobilData.forEach(mobil => {
      if (mobil.latitude && mobil.longitude) {
        let popupContent = `Mobil: ${mobil.merk}<br>Lokasi: (${mobil.latitude}, ${mobil.longitude})`;

        <?php if ($role == 'admin'): ?>
          if (mobil.nama_user) {
            popupContent += `<br>Disewa oleh: ${mobil.nama_user}`;
          }
        <?php endif; ?>

        L.marker([mobil.latitude, mobil.longitude])
          .addTo(map)
          .bindPopup(popupContent);
      }
    });
  </script>
</body>
</html>
