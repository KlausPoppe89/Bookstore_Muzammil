-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 19, 2025 at 03:59 PM
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
-- Database: `toko_buku`
--

-- --------------------------------------------------------

--
-- Table structure for table `buku`
--

CREATE TABLE `buku` (
  `id` int(11) NOT NULL,
  `kategori_id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `harga` double NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `detail` text DEFAULT NULL,
  `ketersediaan_stok` enum('habis','tersedia') DEFAULT 'tersedia'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `buku`
--

INSERT INTO `buku` (`id`, `kategori_id`, `nama`, `harga`, `foto`, `detail`, `ketersediaan_stok`) VALUES
(1, 1, 'Laskar Pelangi', 120000, 'UsDI8BXjshwdcoiltxuU.jpg', '                        Novel inspiratif tentang perjuangan anak-anak di Belitung mengejar pendidikan                    ', 'habis'),
(2, 2, 'The Lean Startup', 150000, 'NDtPkEeE4uBrkCXiEu6Y.jpeg', 'Metodologi untuk membangun startup yang sukses dan berkelanjutan', 'habis'),
(3, 3, 'Clean Code', 110000, 'onJiccBENipYaYZThjFI.jpg', 'Panduan menulis kode yang bersih, mudah dibaca, dan mudah dipelihara', 'habis'),
(4, 4, 'Sapiens', 160000, '5MUWPVl8ApEMLoc0sRji.jpeg', 'Sejarah singkat umat manusia dari zaman batu hingga era digital', 'habis'),
(5, 5, 'Atomic Habits', 130000, 'aHwhsnVbBNruleNlUV30.jpeg', 'Panduan praktis membangun kebiasaan baik dan menghilangkan kebiasaan buruk', 'habis'),
(6, 6, 'The 7 Habits', 150000, 'ayFmiorTUgKcWRDnvSau.jpg', 'Tujuh kebiasaan manusia yang sangat efektif untuk kesuksesan pribadi', 'habis'),
(8, 1, 'Test', 100000, 'oQhPIj1qg1UyAansOmgv.png', 'ini adalah test', 'tersedia');

-- --------------------------------------------------------

--
-- Table structure for table `detail_pesanan`
--

CREATE TABLE `detail_pesanan` (
  `id` int(11) NOT NULL,
  `pesanan_id` int(11) NOT NULL,
  `buku_id` int(11) NOT NULL,
  `nama_buku` varchar(255) DEFAULT NULL,
  `kategori` varchar(100) DEFAULT NULL,
  `qty` int(11) NOT NULL,
  `harga_unit` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `detail_pesanan`
--

INSERT INTO `detail_pesanan` (`id`, `pesanan_id`, `buku_id`, `nama_buku`, `kategori`, `qty`, `harga_unit`, `subtotal`) VALUES
(1, 5, 1, 'Laskar Pelangi', 'Novel &amp; Fiksi', 1, 120000.00, 120000.00),
(2, 6, 4, 'Sapiens', 'Sejarah &amp; Budaya', 1, 160000.00, 160000.00),
(3, 7, 1, 'Laskar Pelangi', 'Novel &amp; Fiksi', 1, 120000.00, 120000.00);

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id`, `nama`) VALUES
(1, 'Novel &amp; Fiksi'),
(2, 'Bisnis &amp; Ekonomi'),
(3, 'Teknologi &amp; IT'),
(4, 'Sejarah &amp; Budaya'),
(5, 'Kesehatan &amp; Gaya Hidup'),
(6, 'Pendidikan &amp; Referensi'),
(7, 'Agama &amp; Spritual'),
(8, 'Seni &amp; Desain');

-- --------------------------------------------------------

--
-- Table structure for table `pesan`
--

CREATE TABLE `pesan` (
  `id` int(11) NOT NULL,
  `id_pengirim` int(11) NOT NULL,
  `penerima_id` int(11) NOT NULL,
  `email_tujuan` varchar(100) NOT NULL,
  `subjek` varchar(150) NOT NULL,
  `isi` text NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('terkirim','dibaca') DEFAULT 'terkirim'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pesan`
--

INSERT INTO `pesan` (`id`, `id_pengirim`, `penerima_id`, `email_tujuan`, `subjek`, `isi`, `tanggal`, `status`) VALUES
(1, 6, 7, '', 'Chat', 'halo', '2025-10-17 10:24:00', 'terkirim'),
(2, 7, 1, '', 'Chat', 'halo', '2025-10-17 10:25:00', 'terkirim'),
(3, 6, 7, '', 'Chat', 'p', '2025-10-17 10:52:07', 'terkirim'),
(4, 6, 7, '', 'Chat', 'halo', '2025-10-17 13:48:58', 'terkirim'),
(5, 1, 7, '', 'Chat', 'halo', '2025-10-19 06:38:19', 'terkirim');

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

CREATE TABLE `pesanan` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nama_penerima` varchar(150) NOT NULL,
  `alamat` text NOT NULL,
  `telepon` varchar(50) NOT NULL,
  `total` decimal(12,2) NOT NULL,
  `pembayaran` enum('cod','transfer') DEFAULT 'cod',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','paid','delivered','arrived','cancelled') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pesanan`
--

INSERT INTO `pesanan` (`id`, `user_id`, `nama_penerima`, `alamat`, `telepon`, `total`, `pembayaran`, `created_at`, `status`) VALUES
(5, 6, 'Alam', 'Pangkah', '081225283229', 120000.00, 'cod', '2025-10-10 23:25:34', 'arrived'),
(6, 6, 'Alam', 'cvdscvds', '081225283278', 160000.00, 'cod', '2025-10-18 10:44:34', 'pending'),
(7, 1, 'Admin Toko', 'vdsvsvsvs', 'fdfvsd', 120000.00, 'cod', '2025-10-19 08:44:57', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Admin Toko', 'admin@toko.test', '$2y$10$S64mo19Tl7GfrzhXitREoOo1e9EoP16wDD6YJWPsh2BQwqLY3strm', 'admin', '2025-10-04 23:05:32'),
(7, 'izam', 'izam@gmail.com', '$2y$10$NC32.l9Y8/2LBzMSwS1CfeWbD/8l71sjXnN.MD5zJSjYH1dbi9IVe', 'user', '2025-10-16 07:05:33');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nama` (`nama`),
  ADD KEY `kategori_buku` (`kategori_id`);

--
-- Indexes for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pesanan_id` (`pesanan_id`),
  ADD KEY `buku_id` (`buku_id`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pesan`
--
ALTER TABLE `pesan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `buku`
--
ALTER TABLE `buku`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `pesan`
--
ALTER TABLE `pesan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
