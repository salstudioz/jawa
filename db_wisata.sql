-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 14, 2025 at 04:34 PM
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
-- Database: `db_wisata`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_logs`
--

CREATE TABLE `admin_logs` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `action` varchar(100) NOT NULL,
  `details` text DEFAULT NULL,
  `timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `destinasi`
--

CREATE TABLE `destinasi` (
  `id_destinasi` int(11) NOT NULL,
  `nama_destinasi` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `id_provinsi` int(11) NOT NULL,
  `gambar_url` varchar(500) DEFAULT 'https://images.unsplash.com/photo-1588668214407-6ea9a6d8c272?q=80&w=600'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `destinasi`
--

INSERT INTO `destinasi` (`id_destinasi`, `nama_destinasi`, `deskripsi`, `id_provinsi`, `gambar_url`) VALUES
(1, 'Gunung Bromo', 'Gunung berapi aktif dengan pemandangan matahari terbit yang spektakuler dan lautan pasir.', 3, 'https://images.unsplash.com/photo-1588668214407-6ea9a6d8c272?q=80&w=600'),
(2, 'Candi Borobudur', 'Candi Buddha terbesar di dunia dari abad ke-9 dengan arsitektur megah.', 2, 'https://plus.unsplash.com/premium_photo-1700954976732-cd14c80a02ba?q=80&w=715&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D'),
(3, 'Kawah Ijen', 'Danau kawah asam dengan fenomena api biru yang unik dan pemandangan alam yang menakjubkan.', 3, 'https://images.unsplash.com/photo-1535918101892-db2c60f81b58?q=80&w=600'),
(4, 'Pantai Parangtritis', 'Pantai dengan ombak besar, pemandangan sunset, dan mitos Ratu Kidul.', 4, 'https://images.unsplash.com/photo-1506929562872-bb421503ef21?q=80&w=600'),
(5, 'Tangkuban Perahu', 'Gunung berapi dengan kawah yang masih aktif dan legenda Sangkuriang.', 1, 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?q=80&w=600'),
(6, 'Candi Prambanan', 'Kompleks candi Hindu terbesar di Indonesia dengan arsitektur yang megah.', 4, 'https://images.unsplash.com/photo-1560179707-f14e90ef3623?q=80&w=600'),
(7, 'Pantai Papuma', 'Pantai dengan pasir putih dan batu karang yang eksotis di Jember.', 3, 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?q=80&w=600'),
(8, 'Kebun Raya Bogor', 'Kebun botani terbesar di Indonesia dengan koleksi tanaman langka.', 1, 'https://images.unsplash.com/photo-1591439721847-9836c8499f3e?q=80&w=600'),
(9, 'Dieng Plateau', 'Dataran tinggi dengan candi-candi kuno dan telaga warna.', 2, 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?q=80&w=600'),
(10, 'Taman Nasional Baluran', 'Savana dengan pemandangan seperti Afrika di Jawa Timur.', 3, 'https://images.unsplash.com/photo-1505118380757-91f5f5632de0?q=80&w=600'),
(11, 'Kota Tua Jakarta', 'Kawasan sejarah dengan bangunan kolonial Belanda.', 5, 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?q=80&w=600'),
(12, 'Green Canyon', 'Ngarai hijau dengan air jernih dan tebing batu kapur.', 1, 'https://images.unsplash.com/photo-1439066615861-d1af74d74000?q=80&w=600'),
(13, 'Gunung Merbabu', 'Gunung dengan jalur pendakian yang populer dan pemandangan indah.', 2, 'https://images.unsplash.com/photo-1464278533981-50106e6176b1?q=80&w=600'),
(14, 'Pantai Karang Bolong', 'Pantai dengan batu karang berlubang alami di Anyer.', 5, 'https://images.unsplash.com/photo-1519046904884-53103b34b206?q=80&w=600'),
(15, 'Taman Sari Yogyakarta', 'Kompleks pemandian keraton dengan arsitektur yang unik.', 4, 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?q=80&w=600');

-- --------------------------------------------------------

--
-- Table structure for table `destinasi_pemesanan`
--

CREATE TABLE `destinasi_pemesanan` (
  `id_pemesanan` int(11) NOT NULL,
  `id_destinasi` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `destinasi_pemesanan`
--

INSERT INTO `destinasi_pemesanan` (`id_pemesanan`, `id_destinasi`) VALUES
(12, 6);

-- --------------------------------------------------------

--
-- Table structure for table `detail_paket_destinasi`
--

CREATE TABLE `detail_paket_destinasi` (
  `id_paket` int(11) NOT NULL,
  `id_destinasi` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_paket_destinasi`
--

INSERT INTO `detail_paket_destinasi` (`id_paket`, `id_destinasi`) VALUES
(1, 1),
(2, 2),
(2, 6),
(2, 15),
(3, 5),
(3, 8),
(4, 1),
(5, 13),
(6, 3),
(7, 8),
(8, 9),
(9, 14),
(10, 4),
(11, 11),
(12, 12),
(13, 13),
(14, 11),
(15, 2),
(15, 4),
(15, 6),
(15, 15);

-- --------------------------------------------------------

--
-- Table structure for table `detail_pemesanan`
--

CREATE TABLE `detail_pemesanan` (
  `id_detail` int(11) NOT NULL,
  `id_pemesanan` int(11) NOT NULL,
  `id_provinsi` int(11) NOT NULL,
  `id_guide` int(11) DEFAULT NULL,
  `id_hotel` int(11) DEFAULT NULL,
  `jumlah_malam` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_pemesanan`
--

INSERT INTO `detail_pemesanan` (`id_detail`, `id_pemesanan`, `id_provinsi`, `id_guide`, `id_hotel`, `jumlah_malam`) VALUES
(1, 12, 4, NULL, 2, 3);

-- --------------------------------------------------------

--
-- Table structure for table `hotel`
--

CREATE TABLE `hotel` (
  `id_hotel` int(11) NOT NULL,
  `nama_hotel` varchar(255) NOT NULL,
  `bintang` int(11) DEFAULT NULL CHECK (`bintang` >= 1 and `bintang` <= 5),
  `harga_per_malam` decimal(10,2) NOT NULL,
  `id_provinsi` int(11) NOT NULL,
  `alamat` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hotel`
--

INSERT INTO `hotel` (`id_hotel`, `nama_hotel`, `bintang`, `harga_per_malam`, `id_provinsi`, `alamat`) VALUES
(1, 'Aston Bromo Hotel & Resort', 4, 750000.00, 3, 'Jl. Raya Bromo, Probolinggo'),
(2, 'Plataran Borobudur Resort', 5, 2500000.00, 2, 'Jl. Badrawati, Borobudur, Magelang'),
(3, 'Ijen Resort & Villas', 3, 550000.00, 3, 'Jl. Raya Ijen, Banyuwangi'),
(4, 'Phoenix Hotel Yogyakarta', 4, 850000.00, 4, 'Jl. Jenderal Sudirman No. 9, Yogyakarta'),
(5, 'The Gaia Hotel Bandung', 5, 1800000.00, 1, 'Jl. Setiabudi No. 220, Bandung'),
(6, 'Aston Inn Malang', 3, 450000.00, 3, 'Jl. Veteran No. 12, Malang'),
(7, 'Amaris Hotel Solo', 2, 350000.00, 2, 'Jl. Slamet Riyadi No. 324, Solo'),
(8, 'Swiss-Belinn Tangerang', 3, 500000.00, 5, 'Jl. Jenderal Sudirman Kav. 1, Tangerang'),
(9, 'Favehotel Bogor', 2, 400000.00, 1, 'Jl. Raya Pajajaran No. 27, Bogor'),
(10, 'Grand Mercure Yogyakarta', 4, 1200000.00, 4, 'Jl. Laksda Adisucipto No. 80, Yogyakarta'),
(11, 'Ibis Styles Surabaya', 3, 600000.00, 3, 'Jl. Raya Darmo No. 120, Surabaya'),
(12, 'Hotel Santika Premiere Semarang', 4, 800000.00, 2, 'Jl. Pandanaran No. 116, Semarang'),
(13, 'Novotel Bandung', 4, 1100000.00, 1, 'Jl. Cihampelas No. 23, Bandung'),
(14, 'The Jayakarta Suites Bali', 4, 950000.00, 5, 'Jl. Gajah Mada, Serang'),
(15, 'Greenhost Boutique Hotel', 3, 650000.00, 4, 'Jl. Prawirotaman No. 30, Yogyakarta');

-- --------------------------------------------------------

--
-- Table structure for table `kontak_messages`
--

CREATE TABLE `kontak_messages` (
  `id` int(11) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `pesan` text NOT NULL,
  `tanggal` datetime DEFAULT current_timestamp(),
  `dibaca` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kontak_messages`
--

INSERT INTO `kontak_messages` (`id`, `nama`, `email`, `pesan`, `tanggal`, `dibaca`) VALUES
(1, 'Andi Wijaya', 'andi@gmail.com', 'Apakah paket Bromo bisa untuk anak usia 5 tahun?', '2025-10-10 09:15:00', 1),
(2, 'Sari Dewi', 'sari.dewi@yahoo.com', 'Saya ingin membuat paket kustom untuk honeymoon di Yogyakarta. Bisa dibantu?', '2025-10-12 14:30:00', 1),
(3, 'Bambang Setyo', 'bambang@company.com', 'Kami ingin booking paket untuk 20 orang karyawan. Ada diskon untuk grup?', '2025-10-15 11:45:00', 0),
(4, 'Lisa Anggraeni', 'lisa@gmail.com', 'Bagaimana prosedur pembatalan paket yang sudah dibayar?', '2025-10-18 16:20:00', 1),
(5, 'Rudi Hartono', 'rudi.hartono@mail.com', 'Apakah ada paket khusus untuk fotografi di Kawah Ijen?', '2025-10-20 10:05:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `paket_wisata`
--

CREATE TABLE `paket_wisata` (
  `id_paket` int(11) NOT NULL,
  `nama_paket` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `harga_total` decimal(12,2) NOT NULL,
  `jumlah_hari` int(11) NOT NULL,
  `id_provinsi` int(11) NOT NULL,
  `rating` decimal(3,2) DEFAULT 4.00,
  `gambar_url` varchar(500) DEFAULT 'https://images.unsplash.com/photo-1588668214407-6ea9a6d8c272?q=80&w=600'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `paket_wisata`
--

INSERT INTO `paket_wisata` (`id_paket`, `nama_paket`, `deskripsi`, `harga_total`, `jumlah_hari`, `id_provinsi`, `rating`, `gambar_url`) VALUES
(1, 'Paket Bromo 3D2N', 'Tour eksklusif Gunung Bromo dengan sunrise view, lautan pasir, dan kawah aktif. Termasuk transportasi, penginapan hotel bintang 4, dan pemandu wisata profesional.', 2400000.00, 3, 3, 4.50, 'https://images.unsplash.com/photo-1588668214407-6ea9a6d8c272?q=80&w=600'),
(2, 'Paket Jogja Heritage 4D3N', 'Wisata budaya Yogyakarta mengunjungi Candi Borobudur, Prambanan, Keraton Yogyakarta, dan Malioboro. Dengan penginapan hotel bintang 4 dan kuliner khas Jogja.', 3200000.00, 4, 4, 4.80, 'https://images.unsplash.com/photo-1555404634-f8b6afa61c5e?q=80&w=600'),
(3, 'Paket Bandung Adventure 3D2N', 'Explore Bandung dengan mengunjungi Tangkuban Perahu, Kawah Putih, dan floating market. Termasuk penginapan resort dan wisata kuliner.', 2100000.00, 3, 1, 4.60, 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?q=80&w=600'),
(4, 'Paket Malang Bromo 4D3N', 'Kombinasi wisata kota Malang dengan Batu Night Spectacular dan petualangan ke Gunung Bromo. Cocok untuk keluarga dan backpacker.', 2800000.00, 4, 3, 4.70, 'https://images.unsplash.com/photo-1593693399710-1e8e8c43b55a?q=80&w=600'),
(5, 'Paket Solo Cultural 3D2N', 'Wisata keraton Solo, batik, dan kuliner khas Solo. Termasuk workshop membatik dan menginap di hotel heritage.', 1900000.00, 3, 2, 4.40, 'https://images.unsplash.com/photo-1591439721847-9836c8499f3e?q=80&w=600'),
(6, 'Paket Ijen Midnight 2D1N', 'Petualangan malam ke Kawah Ijen untuk melihat api biru dan sunrise dari puncak. Termasuk transport dan homestay.', 1500000.00, 2, 3, 4.90, 'https://images.unsplash.com/photo-1535918101892-db2c60f81b58?q=80&w=600'),
(7, 'Paket Bogor Puncak 2D1N', 'Weekend getaway ke Kebun Raya Bogor, Taman Safari, dan wisata Puncak. Cocok untuk keluarga dengan anak-anak.', 1200000.00, 2, 1, 4.30, 'https://images.unsplash.com/photo-1591439721847-9836c8499f3e?q=80&w=600'),
(8, 'Paket Dieng Plateau 3D2N', 'Explore dataran tinggi Dieng dengan candi-candi kuno, telaga warna, dan kawah Sikidang. Penginapan di villa dengan view pegunungan.', 2200000.00, 3, 2, 4.65, 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?q=80&w=600'),
(9, 'Paket Banten Beach 3D2N', 'Wisata pantai Anyer dan Karang Bolong dengan watersport dan sunset view. Termasuk penginapan resort tepi pantai.', 1800000.00, 3, 5, 4.55, 'https://images.unsplash.com/photo-1506929562872-bb421503ef21?q=80&w=600'),
(10, 'Paket Jogja Beach 3D2N', 'Kombinasi wisata pantai Parangtritis dengan Gunung Api Purba dan kuliner seafood. Penginapan hotel bintang 3 dengan view laut.', 2000000.00, 3, 4, 4.45, 'https://images.unsplash.com/photo-1505118380757-91f5f5632de0?q=80&w=600'),
(11, 'Paket Surabaya City Tour 2D1N', 'Explore kota Surabaya dengan Tugu Pahlawan, House of Sampoerna, dan wisata kuliner khas Surabaya.', 1100000.00, 2, 3, 4.20, 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?q=80&w=600'),
(12, 'Paket Green Canyon 2D1N', 'Adventure di Green Canyon Pangandaran dengan arung jeram dan explore gua-gua. Cocok untuk pecinta alam.', 1400000.00, 2, 1, 4.75, 'https://images.unsplash.com/photo-1439066615861-d1af74d74000?q=80&w=600'),
(13, 'Paket Merbabu Hiking 2D1N', 'Pendakian Gunung Merbabu via Selo dengan pemandu profesional. Termasuk perlengkapan camping dan makanan.', 1300000.00, 2, 2, 4.85, 'https://images.unsplash.com/photo-1464278533981-50106e6176b1?q=80&w=600'),
(14, 'Paket Jakarta History 2D1N', 'Wisata sejarah Kota Tua Jakarta, Museum Nasional, dan Monas. Dengan penginapan hotel di pusat kota.', 1250000.00, 2, 5, 4.25, 'https://images.unsplash.com/photo-1519046904884-53103b34b206?q=80&w=600'),
(15, 'Paket Luxury Jogja 5D4N', 'Paket premium Yogyakarta dengan penginapan hotel bintang 5, kuliner eksklusif, dan private tour dengan mobil mewah.', 5000000.00, 5, 4, 4.95, 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?q=80&w=600');

-- --------------------------------------------------------

--
-- Table structure for table `pemesanan`
--

CREATE TABLE `pemesanan` (
  `id_pemesanan` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_paket` int(11) DEFAULT NULL,
  `tanggal_pemesanan` datetime DEFAULT current_timestamp(),
  `tanggal_mulai` date NOT NULL,
  `total_harga` decimal(12,2) NOT NULL,
  `is_custom` tinyint(1) NOT NULL DEFAULT 0,
  `status_pemesanan` varchar(50) NOT NULL DEFAULT 'Pending',
  `jumlah_orang` int(11) DEFAULT 1,
  `catatan_khusus` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pemesanan`
--

INSERT INTO `pemesanan` (`id_pemesanan`, `id_user`, `id_paket`, `tanggal_pemesanan`, `tanggal_mulai`, `total_harga`, `is_custom`, `status_pemesanan`, `jumlah_orang`, `catatan_khusus`) VALUES
(12, 198, NULL, '2025-12-14 22:10:25', '2025-12-16', 16900000.00, 1, 'Pending', 2, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `provinsi`
--

CREATE TABLE `provinsi` (
  `id_provinsi` int(11) NOT NULL,
  `nama_provinsi` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `provinsi`
--

INSERT INTO `provinsi` (`id_provinsi`, `nama_provinsi`) VALUES
(5, 'Banten'),
(4, 'DI Yogyakarta'),
(1, 'Jawa Barat'),
(2, 'Jawa Tengah'),
(3, 'Jawa Timur');

-- --------------------------------------------------------

--
-- Table structure for table `tour_guide`
--

CREATE TABLE `tour_guide` (
  `id_guide` int(11) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `rating` decimal(3,2) DEFAULT NULL,
  `harga_harian` decimal(10,2) NOT NULL,
  `nomor_telepon` varchar(20) DEFAULT NULL,
  `pengalaman` int(11) DEFAULT 0,
  `foto_url` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tour_guide`
--

INSERT INTO `tour_guide` (`id_guide`, `nama`, `rating`, `harga_harian`, `nomor_telepon`, `pengalaman`, `foto_url`) VALUES
(1, 'Budi Santoso', 4.80, 350000.00, '081234567890', 8, 'https://randomuser.me/api/portraits/men/32.jpg'),
(2, 'Sari Dewi', 4.90, 400000.00, '081298765432', 10, 'https://randomuser.me/api/portraits/women/44.jpg'),
(3, 'Agus Wijaya', 4.70, 300000.00, '081345678901', 5, 'https://randomuser.me/api/portraits/men/67.jpg'),
(4, 'Maya Indah', 4.85, 380000.00, '081356789012', 7, 'https://randomuser.me/api/portraits/women/68.jpg'),
(5, 'Rudi Hartono', 4.60, 280000.00, '081367890123', 4, 'https://randomuser.me/api/portraits/men/75.jpg'),
(6, 'Dewi Lestari', 4.95, 450000.00, '081378901234', 12, 'https://randomuser.me/api/portraits/women/26.jpg'),
(7, 'Hendra Pratama', 4.75, 320000.00, '081389012345', 6, 'https://randomuser.me/api/portraits/men/81.jpg'),
(8, 'Fitri Anjani', 4.88, 420000.00, '081390123456', 9, 'https://randomuser.me/api/portraits/women/33.jpg'),
(9, 'Joko Susilo', 4.65, 290000.00, '081301234567', 5, 'https://randomuser.me/api/portraits/men/22.jpg'),
(10, 'Linda Sari', 4.92, 430000.00, '081312345678', 11, 'https://randomuser.me/api/portraits/women/56.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nomor_telepon` varchar(20) DEFAULT NULL,
  `tanggal_daftar` datetime DEFAULT current_timestamp(),
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `alamat` text DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `nama`, `email`, `password`, `nomor_telepon`, `tanggal_daftar`, `is_admin`, `alamat`, `tanggal_lahir`) VALUES
(1, 'Admin Jawa Travel', 'admin@jawa.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '081234567890', '2025-01-01 10:00:00', 1, 'Jl. Admin No. 1, Jakarta', '1985-05-15'),
(2, 'Budi Santoso', 'budi@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '081234567891', '2025-02-15 14:30:00', 0, 'Jl. Merdeka No. 12, Bandung', '1990-08-20'),
(3, 'Siti Aminah', 'siti@yahoo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '081234567892', '2025-03-10 09:15:00', 0, 'Jl. Sudirman No. 45, Surabaya', '1988-12-05'),
(4, 'Ahmad Rizki', 'ahmad@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '081234567893', '2025-04-05 16:45:00', 0, 'Jl. Gatot Subroto No. 78, Yogyakarta', '1992-03-25'),
(5, 'Dewi Lestari', 'dewi@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '081234567894', '2025-05-20 11:20:00', 0, 'Jl. Pahlawan No. 23, Semarang', '1995-07-10'),
(196, 'salma', 'min@a.com', '$2y$10$zYqJcAnnYEcIyNOQCZ9IC.RWv1xs3.Piw./6K3sWuKihbqikH0E.K', '081290977985', '2025-12-14 11:30:40', 1, NULL, NULL),
(198, 'senku', 'senku@a.com', '$2y$10$R1jlQiS1KRB/U0UtVdN.AO/.77cfVLRjGNbeDtpcdwjp1xC2mwSA2', '', '2025-12-14 21:52:44', 0, '', NULL),
(199, 'tttes', 'oy@a.com', '$2y$10$BSbcJ.gxmh5V7r9xKHZNP.TxbAZZ6ns6.gkbwslt1uflgg2w2fq6m', '', '2025-12-14 21:55:13', 0, '', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_logs`
--
ALTER TABLE `admin_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `destinasi`
--
ALTER TABLE `destinasi`
  ADD PRIMARY KEY (`id_destinasi`),
  ADD KEY `id_provinsi` (`id_provinsi`);

--
-- Indexes for table `destinasi_pemesanan`
--
ALTER TABLE `destinasi_pemesanan`
  ADD PRIMARY KEY (`id_pemesanan`,`id_destinasi`),
  ADD KEY `id_destinasi` (`id_destinasi`);

--
-- Indexes for table `detail_paket_destinasi`
--
ALTER TABLE `detail_paket_destinasi`
  ADD PRIMARY KEY (`id_paket`,`id_destinasi`),
  ADD KEY `id_destinasi` (`id_destinasi`);

--
-- Indexes for table `detail_pemesanan`
--
ALTER TABLE `detail_pemesanan`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_pemesanan` (`id_pemesanan`),
  ADD KEY `id_provinsi` (`id_provinsi`),
  ADD KEY `id_guide` (`id_guide`),
  ADD KEY `id_hotel` (`id_hotel`);

--
-- Indexes for table `hotel`
--
ALTER TABLE `hotel`
  ADD PRIMARY KEY (`id_hotel`),
  ADD KEY `id_provinsi` (`id_provinsi`);

--
-- Indexes for table `kontak_messages`
--
ALTER TABLE `kontak_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `paket_wisata`
--
ALTER TABLE `paket_wisata`
  ADD PRIMARY KEY (`id_paket`),
  ADD KEY `id_provinsi` (`id_provinsi`);

--
-- Indexes for table `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD PRIMARY KEY (`id_pemesanan`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_paket` (`id_paket`);

--
-- Indexes for table `provinsi`
--
ALTER TABLE `provinsi`
  ADD PRIMARY KEY (`id_provinsi`),
  ADD UNIQUE KEY `nama_provinsi` (`nama_provinsi`);

--
-- Indexes for table `tour_guide`
--
ALTER TABLE `tour_guide`
  ADD PRIMARY KEY (`id_guide`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_logs`
--
ALTER TABLE `admin_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `destinasi`
--
ALTER TABLE `destinasi`
  MODIFY `id_destinasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `detail_pemesanan`
--
ALTER TABLE `detail_pemesanan`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hotel`
--
ALTER TABLE `hotel`
  MODIFY `id_hotel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `kontak_messages`
--
ALTER TABLE `kontak_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `paket_wisata`
--
ALTER TABLE `paket_wisata`
  MODIFY `id_paket` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `pemesanan`
--
ALTER TABLE `pemesanan`
  MODIFY `id_pemesanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `provinsi`
--
ALTER TABLE `provinsi`
  MODIFY `id_provinsi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tour_guide`
--
ALTER TABLE `tour_guide`
  MODIFY `id_guide` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=200;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_logs`
--
ALTER TABLE `admin_logs`
  ADD CONSTRAINT `admin_logs_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `user` (`id_user`);

--
-- Constraints for table `destinasi`
--
ALTER TABLE `destinasi`
  ADD CONSTRAINT `destinasi_ibfk_1` FOREIGN KEY (`id_provinsi`) REFERENCES `provinsi` (`id_provinsi`);

--
-- Constraints for table `destinasi_pemesanan`
--
ALTER TABLE `destinasi_pemesanan`
  ADD CONSTRAINT `destinasi_pemesanan_ibfk_1` FOREIGN KEY (`id_pemesanan`) REFERENCES `pemesanan` (`id_pemesanan`),
  ADD CONSTRAINT `destinasi_pemesanan_ibfk_2` FOREIGN KEY (`id_destinasi`) REFERENCES `destinasi` (`id_destinasi`);

--
-- Constraints for table `detail_paket_destinasi`
--
ALTER TABLE `detail_paket_destinasi`
  ADD CONSTRAINT `detail_paket_destinasi_ibfk_1` FOREIGN KEY (`id_paket`) REFERENCES `paket_wisata` (`id_paket`),
  ADD CONSTRAINT `detail_paket_destinasi_ibfk_2` FOREIGN KEY (`id_destinasi`) REFERENCES `destinasi` (`id_destinasi`);

--
-- Constraints for table `detail_pemesanan`
--
ALTER TABLE `detail_pemesanan`
  ADD CONSTRAINT `detail_pemesanan_ibfk_1` FOREIGN KEY (`id_pemesanan`) REFERENCES `pemesanan` (`id_pemesanan`),
  ADD CONSTRAINT `detail_pemesanan_ibfk_2` FOREIGN KEY (`id_provinsi`) REFERENCES `provinsi` (`id_provinsi`),
  ADD CONSTRAINT `detail_pemesanan_ibfk_3` FOREIGN KEY (`id_guide`) REFERENCES `tour_guide` (`id_guide`),
  ADD CONSTRAINT `detail_pemesanan_ibfk_4` FOREIGN KEY (`id_hotel`) REFERENCES `hotel` (`id_hotel`);

--
-- Constraints for table `hotel`
--
ALTER TABLE `hotel`
  ADD CONSTRAINT `hotel_ibfk_1` FOREIGN KEY (`id_provinsi`) REFERENCES `provinsi` (`id_provinsi`);

--
-- Constraints for table `paket_wisata`
--
ALTER TABLE `paket_wisata`
  ADD CONSTRAINT `paket_wisata_ibfk_1` FOREIGN KEY (`id_provinsi`) REFERENCES `provinsi` (`id_provinsi`);

--
-- Constraints for table `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD CONSTRAINT `pemesanan_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`),
  ADD CONSTRAINT `pemesanan_ibfk_2` FOREIGN KEY (`id_paket`) REFERENCES `paket_wisata` (`id_paket`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
