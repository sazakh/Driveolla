-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 15, 2025 at 11:43 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `driveolla`
--

-- --------------------------------------------------------

--
-- Table structure for table `mobil`
--

CREATE TABLE `mobil` (
  `id_mobil` int(11) NOT NULL,
  `merk` varchar(100) DEFAULT NULL,
  `tipe` varchar(100) DEFAULT NULL,
  `tahun` year(4) DEFAULT NULL,
  `tarif_per_hari` decimal(10,2) DEFAULT NULL,
  `kapasitas` varchar(50) DEFAULT NULL,
  `bensin` varchar(50) DEFAULT NULL,
  `transmisi` varchar(50) DEFAULT NULL,
  `drive` varchar(50) DEFAULT NULL,
  `restriksi_driver` varchar(255) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `status` enum('tersedia','disewa') DEFAULT 'tersedia',
  `latitude` decimal(10,6) DEFAULT NULL,
  `longitude` decimal(10,6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mobil`
--

INSERT INTO `mobil` (`id_mobil`, `merk`, `tipe`, `tahun`, `tarif_per_hari`, `kapasitas`, `bensin`, `transmisi`, `drive`, `restriksi_driver`, `gambar`, `status`, `latitude`, `longitude`) VALUES
(4, 'Toyota Raize', 'SUV', '2021', 400000.00, '5 Orang', '36 Liter', 'Otomatis', 'FWD', 'Usia minimal 21 tahun, memiliki SIM minimal 1 tahun', 'Gambar/681b2386ef04e_ToyotaRaize.png', 'tersedia', -7.327400, 108.220800),
(5, 'Daihatsu Sigra', 'MPV', '2023', 350000.00, '7 Orang', '36 Liter', 'Manual', 'FWD', 'Usia minimal 21 tahun, memiliki SIM minimal 1 tahun', 'Gambar/681b23fc1d470_DaihatsuSigra.png', 'tersedia', -6.917500, 107.619100),
(6, 'Hyundai Palisade Elite', 'SUV', '2018', 800.00, '7 Orang', '71 Liter', 'Otomatis', 'AWD', 'Usia minimal 21 tahun, memiliki SIM minimal 1 tahun', 'Gambar/682c34f9928ec_HyundaiPalisadeElite.png', 'tersedia', -6.208800, 106.845600),
(7, 'Mercedes Benz GLS', 'SUV', '2023', 850.00, '7 Orang', '90 Liter', 'Otomatis', 'AWD', 'Usia minimal 21 tahun, memiliki SIM minimal 1 tahun', 'Gambar/682c357b9864e_MercedesBenzGLSClass.png', 'tersedia', -7.250400, 112.768800),
(8, 'Suzuki Ertiga', 'MPV', '2012', 600.00, '7 Orang', '45 Liter', 'Otomatis', 'FWD', 'Usia minimal 21 tahun, memiliki SIM minimal 1 tahun', 'Gambar/682c36172eb06_SuzukiErtiga.png', 'tersedia', -7.210900, 107.908700),
(9, 'Wuling Air EV', 'City Car', '2022', 500.00, '4 Orang', 'Listrik (Baterai 26.7 kwh, Jarak Tempuh ±200 km)', 'Otomatis', 'FWD', 'Usia minimal 21 tahun, memiliki SIM minimal 1 tahun', 'Gambar/682c368befad1_WulingAirEv.png', 'tersedia', -7.795600, 110.369500);

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id_pembayaran` int(11) NOT NULL,
  `id_pemesanan` int(11) DEFAULT NULL,
  `metode_pembayaran` varchar(50) DEFAULT NULL,
  `jumlah_bayar` decimal(10,2) DEFAULT NULL,
  `status` enum('belum_bayar','sudah_bayar','gagal') DEFAULT 'belum_bayar',
  `tanggal_bayar` datetime DEFAULT NULL,
  `bukti_transfer` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pemesanan`
--

CREATE TABLE `pemesanan` (
  `id_pemesanan` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_mobil` int(11) DEFAULT NULL,
  `nama_driver` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `no_hp` int(255) DEFAULT NULL,
  `tanggal_sewa` date DEFAULT NULL,
  `tanggal_kembali` date DEFAULT NULL,
  `status` enum('pending','disetujui','ditolak','selesai') DEFAULT 'pending',
  `total_harga` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pemesanan`
--

INSERT INTO `pemesanan` (`id_pemesanan`, `id_user`, `id_mobil`, `nama_driver`, `email`, `no_hp`, `tanggal_sewa`, `tanggal_kembali`, `status`, `total_harga`) VALUES
(11, 3, 4, 'luna', '237006000@stundent.unsil.ac.id', 12345678, '2025-05-17', '2025-05-18', 'selesai', 400000.00),
(12, 16, 5, 'Luna Maya', 'Lunamaya@gmail.com', 12345678, '2025-05-21', '2025-05-22', 'selesai', 350000.00),
(13, 14, 6, 'salma', '237006097@stundent.unsil.ac.id', 12345678, '2025-05-12', '2025-05-13', 'pending', 800.00);

-- --------------------------------------------------------

--
-- Table structure for table `pengembalian`
--

CREATE TABLE `pengembalian` (
  `id_pengembalian` int(11) NOT NULL,
  `id_pemesanan` int(11) DEFAULT NULL,
  `tanggal_kembali` date DEFAULT NULL,
  `kondisi_mobil` text DEFAULT NULL,
  `denda` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','customer') DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `no_hp` varchar(20) NOT NULL,
  `foto` varchar(255) NOT NULL,
  `nama` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `username`, `password`, `role`, `email`, `no_hp`, `foto`, `nama`) VALUES
(3, 'rosi', '123', 'customer', '237006087@student.unsil.ac.id', '12345678', '680f5faca3a79_HondaBrio.png', 'Rosi Saraswati Alrasid'),
(7, 'admin', '$2y$10$Z66nQL5Nc6JrpCtzmxDlieceH8WF.s6PVipch7s5Jdfc2/e8Q7AaO', 'admin', '', '', '', ''),
(13, 'admin6', '$2y$10$GJd97o3lOP85fo8GrqffkOlTMuxr806ptCmVM84CBclI5biOlWFwa', 'admin', '', '', '', ''),
(14, 'salma', '$2y$10$B0HkfKWJKEovJ0oUL/Pmautdugn8qnTSLjSmRgH.NkqEL/mT4cVea', 'customer', '', '', '', ''),
(16, 'luna', '$2y$10$HK7Iuaa7vQFfR45o8sjdvepASJ1hGliaQAbMTkg7q/c5LgIcC.3kC', 'customer', 'Lunamaya@gmail.com', '12345678', 'lunamaya.jpg', 'Luna Maya');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mobil`
--
ALTER TABLE `mobil`
  ADD PRIMARY KEY (`id_mobil`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD KEY `id_pemesanan` (`id_pemesanan`);

--
-- Indexes for table `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD PRIMARY KEY (`id_pemesanan`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_mobil` (`id_mobil`);

--
-- Indexes for table `pengembalian`
--
ALTER TABLE `pengembalian`
  ADD PRIMARY KEY (`id_pengembalian`),
  ADD KEY `id_pemesanan` (`id_pemesanan`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `mobil`
--
ALTER TABLE `mobil`
  MODIFY `id_mobil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id_pembayaran` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pemesanan`
--
ALTER TABLE `pemesanan`
  MODIFY `id_pemesanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `pengembalian`
--
ALTER TABLE `pengembalian`
  MODIFY `id_pengembalian` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`id_pemesanan`) REFERENCES `pemesanan` (`id_pemesanan`) ON DELETE CASCADE;

--
-- Constraints for table `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD CONSTRAINT `pemesanan_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`),
  ADD CONSTRAINT `pemesanan_ibfk_2` FOREIGN KEY (`id_mobil`) REFERENCES `mobil` (`id_mobil`);

--
-- Constraints for table `pengembalian`
--
ALTER TABLE `pengembalian`
  ADD CONSTRAINT `pengembalian_ibfk_1` FOREIGN KEY (`id_pemesanan`) REFERENCES `pemesanan` (`id_pemesanan`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
