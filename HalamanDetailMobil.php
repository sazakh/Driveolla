<?php
require 'koneksi.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  echo "ID mobil tidak ditemukan.";
  exit;
}

$id = (int)$_GET['id'];
$sql = "SELECT * FROM mobil WHERE id_mobil = $id";
$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) === 0) {
  echo "Mobil dengan ID tersebut tidak ditemukan.";
  exit;
}

$mobil = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>DRIVEOLLA</title>
  <link rel="stylesheet" href="HalamanDetailMobil.css" />
</head>
<body>
  <div class="container">
    <!-- KIRI: Gambar dan Detail Mobil -->
    <div class="left-side">
      <a href="HalamanProduk.php" class="back-button">✕</a>
      <div class="background-box">
        <div class="image-box">
          <img id="carImage" src="<?php echo htmlspecialchars($mobil['gambar']); ?>" alt="Gambar Mobil" />
        </div>
        <div class="car-details">
          <h2 id="carName"><?php echo htmlspecialchars($mobil['merk'] . ' ' . $mobil['tipe']); ?></h2>
          <h2 id="car-price">Rp <?php echo number_format($mobil['tarif_per_hari'], 0, ',', '.'); ?> / hari</h2>
          <div class="booking-summary">
            <div class="specs">
              <div><strong>👥 Kapasitas:</strong> <?php echo htmlspecialchars($mobil['kapasitas']); ?> orang</div>
              <div><strong>⛽ Bensin:</strong> <?php echo htmlspecialchars($mobil['bensin']); ?></div>
              <div><strong>🚙 Tipe:</strong> <?php echo htmlspecialchars($mobil['tipe']); ?></div>
              <div><strong>⚙️ Transmisi:</strong> <?php echo htmlspecialchars($mobil['transmisi']); ?></div>
              <div><strong>🚗 Drive:</strong> <?php echo htmlspecialchars($mobil['drive']); ?></div>
              <div><strong>🪪 Driver restrictions:</strong> <?php echo htmlspecialchars($mobil['restriksi_driver'] ?? '-'); ?></div>
            </div>
          </div>
        </div>
      </div>      
    </div>

    <!-- KANAN: Form Booking -->
    <div class="right-side">
      <form id="bookingForm" >
        <!-- Hidden inputs -->
        <input type="hidden" name="id_mobil" value="<?php echo $mobil['id_mobil']; ?>" />
        <input type="hidden" name="total_harga" id="totalHargaInput" value="0" />

        <h3>Booking dates</h3>
        <label>Pickup date</label>
        <input type="date" id="pickupDateInput" name="tanggal_mulai" required />

        <label>Return date</label>
        <input type="date" id="returnDateInput" name="tanggal_selesai" required />

        <h3>Driver details</h3>
        <input type="text" name="nama_driver" id="name" placeholder="Driver name" required />
        <input type="email" name="email_driver" id="email" placeholder="Email address" required />
        <input type="tel" name="telepon_driver" id="phone" placeholder="Phone number" required />

        <div class="total">
          <h2><span id="totalPrice">Rp 0</span> untuk <span id="finalDays">0</span> hari</h2>
        </div>

        <button type="submit" class="pay-btn">PAY NOW</button>
      </form>
    </div>
  </div>

  <script>
    const tarifPerHari = <?php echo (int)$mobil['tarif_per_hari']; ?>;
  </script>
  <script src="HalamanDetailMobil.js"></script>
  <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-bn0mmV0e4ZOiax98"></script>

</body>
</html>
