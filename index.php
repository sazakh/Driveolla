<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>DRIVEOLLA</title>
  <link rel="stylesheet" href="HalamanBeranda.css" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
  <div class="main-wrapper">
    <div class="scroll-area">
      <!-- Halaman 1 -->
      <section class="page">
        <header class="navbar">
          <div class="logo">DRIVEOLLA</div>

          <div class="outer-box">
            <div class="logout-wrapper">
              <a href="HalamanDataDiri.php" class="user-icon" title="Data Diri">
                <i class="fas fa-user"></i>
              </a> 
              <button id="logoutBtn">Logout</button> 
            </div>      
        </header>

        <section class="hero">
          <div class="hero-left">
            <h1>Rental Mobil Premium</h1>
            <p>Rental mobil dengan layanan cepat dan mudah. Pilih kendaraan sesuai kebutuhan dan nikmati perjalanan tanpa khawatir</p>
            <a href="HalamanProduk.php" class="pesan-btn">Pesan Sekarang</a>
          </div>

          <div class="hero-right">
            <div class="map-bg"></div>
            <div class="car-wrapper">
              <img src="Gambar/1.png" alt="Car" />
            </div>
          </div>
        </section>

        <div class="brand-logos">
          <img src="Gambar/Hyundai.png" alt="Brand" />
          <img src="Gambar/Mercedes.png" alt="Brand" />
          <img src="Gambar/Mitsubishi.png" alt="Brand" />
          <img src="Gambar/Suzuki.png" alt="Brand" />
          <img src="Gambar/Toyota.png" alt="Brand" />
          <img src="Gambar/Honda.png" alt="Brand" />
          <img src="Gambar/Daihatsu.png" alt="Brand" />
          <img src="Gambar/Wuling.png" alt="Brand" />
          <img src="Gambar/Mazda.png" alt="Next" />
        </div>
      </section>

      <!-- Halaman 2 -->
      <section class="page">
        <div class="service-section">
          <h2>Layanan Kami</h2>
          <div class="services">
            <div class="service-card">
              <a href="https://maps.app.goo.gl/UPXNijkHtxTUhfuG6"><img src="Gambar/Maps.jpg" alt="Lokasi"/></a>
              
              <h3>Lokasi Pengambilan</h3>
              <p>Tersedia di Tamansari Tasikmalaya.</p>
            </div>
            <div class="service-card">
              <a href="HalamanProduk.php"><img src="Gambar/JenisMobil.jpg" alt="Tipe Layanan" /></a>
              <h3>Pilih Jenis Mobil</h3>
              <p>Fleksibel sesuai kebutuhan perjalanan Anda.</p>
            </div>
            <div class="service-card">
              <img src="Gambar/Laptop.png" alt="Cepat" />
              <h3>Proses Cepat</h3>
              <p>Booking online mudah, tanpa ribet.</p>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
  <script src="HalamanBeranda.js"></script>
</body>
</html>
