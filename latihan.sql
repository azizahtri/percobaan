-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 27 Nov 2025 pada 13.22
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `latihan`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id`, `name`, `username`, `password`) VALUES
(1, 'Admin Utama', 'admin', 'admin123');

-- --------------------------------------------------------

--
-- Struktur dari tabel `alternatives`
--

CREATE TABLE `alternatives` (
  `id` int(11) NOT NULL,
  `field_id` int(11) NOT NULL,
  `data_id` int(11) NOT NULL,
  `code` varchar(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `status` enum('Aktif','Tidak Aktif') DEFAULT 'Aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `alternatives`
--

INSERT INTO `alternatives` (`id`, `field_id`, `data_id`, `code`, `name`, `status`) VALUES
(7, 3, 0, 'TEST01', 'Cek Manual', 'Aktif'),
(15, 0, 0, 'K003', 'Azizah Tri Kusumadewi', 'Aktif'),
(16, 0, 0, 'K005', 'Rani', 'Aktif');

-- --------------------------------------------------------

--
-- Struktur dari tabel `criteria`
--

CREATE TABLE `criteria` (
  `id` int(11) NOT NULL,
  `field_id` int(11) NOT NULL,
  `code` varchar(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `bobot` float NOT NULL,
  `type` enum('Benefit','Cost') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `criteria`
--

INSERT INTO `criteria` (`id`, `field_id`, `code`, `name`, `bobot`, `type`) VALUES
(1, 3, 'C1', 'Pendidikan', 4, 'Benefit'),
(2, 3, 'C2', 'Pengalaman', 3, 'Benefit'),
(3, 6, 'C1', 'Pendidikan Terakhir', 4, 'Benefit'),
(4, 5, 'C1', 'Pendidikan Terakhir', 4, 'Benefit');

-- --------------------------------------------------------

--
-- Struktur dari tabel `data`
--

CREATE TABLE `data` (
  `id` int(11) NOT NULL,
  `id_lowongan` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `pesan` text DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `status` enum('proses','memenuhi','tidak memenuhi') DEFAULT 'proses',
  `spk_score` float DEFAULT NULL,
  `is_history` int(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `data`
--

INSERT INTO `data` (`id`, `id_lowongan`, `nama`, `email`, `no_hp`, `pesan`, `link`, `status`, `spk_score`, `is_history`, `created_at`) VALUES
(1, 2, 'azizah', 'azizahtrikusuma.13@gmail.com', '081385398213', '', 'https://drive.google.com/drive/folders/1Uez6A99RZweOWPLM7Z0ZqvhTcQsfr8Hl?usp=drive_link', 'tidak memenuhi', 3, 1, '2025-11-26 23:27:34'),
(2, 2, 'Azizah Tri Kusumadewi', 'azizahtrikusuma.13@gmail.com', '081385398213', '', NULL, 'tidak memenuhi', 0, 1, '2025-11-26 23:50:42'),
(3, 1, 'Azizah Tri Kusumadewi', 'azizahtrikusuma.13@gmail.com', '081385398213', 'saya', 'https://drive.google.com/drive/folders/1Uez6A99RZweOWPLM7Z0ZqvhTcQsfr8Hl?usp=drive_link', 'memenuhi', 3, 1, '2025-11-26 23:57:32'),
(4, 3, 'elsa', 'elsa@gmail.com', '081385398213', 'dia', 'https://drive.google.com/drive/folders/1Uez6A99RZweOWPLM7Z0ZqvhTcQsfr8Hl?usp=drive_link', 'proses', NULL, 0, '2025-11-27 16:12:49'),
(5, 1, 'Rani', 'rani@gmail.com', '123132132132', 'asek', 'https://drive.google.com/drive/folders/1Uez6A99RZweOWPLM7Z0ZqvhTcQsfr8Hl?usp=drive_link', 'memenuhi', 2.37957, 1, '2025-11-27 16:18:55');

-- --------------------------------------------------------

--
-- Struktur dari tabel `lowongan`
--

CREATE TABLE `lowongan` (
  `id` int(11) NOT NULL,
  `field_id` int(11) NOT NULL,
  `judul_lowongan` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `jenis` enum('Magang','Full Time','Part Time') NOT NULL,
  `link_google_form` varchar(255) DEFAULT NULL,
  `tanggal_posting` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `lowongan`
--

INSERT INTO `lowongan` (`id`, `field_id`, `judul_lowongan`, `deskripsi`, `jenis`, `link_google_form`, `tanggal_posting`) VALUES
(1, 3, 'Accounting Staff', 'Mengurus pajak', 'Full Time', NULL, '2025-11-27 11:43:04'),
(2, 6, 'Programmer', 'membuat program', 'Full Time', 'https://forms.gle/DU3MiFDx9WKJBuqY9', '2025-11-26 00:00:00'),
(3, 5, 'Human Resource', 'tes', 'Full Time', NULL, '2025-11-26 00:00:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pekerjaan`
--

CREATE TABLE `pekerjaan` (
  `id` int(11) NOT NULL,
  `nama_pekerjaan` varchar(255) NOT NULL,
  `standar_spk` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pekerjaan`
--

INSERT INTO `pekerjaan` (`id`, `nama_pekerjaan`, `standar_spk`) VALUES
(3, 'Finance', 2.52),
(5, 'HR', 7.00),
(6, 'IT', 7.50);

-- --------------------------------------------------------

--
-- Struktur dari tabel `subcriteria`
--

CREATE TABLE `subcriteria` (
  `id` int(11) NOT NULL,
  `criteria_id` int(11) NOT NULL,
  `field_id` int(11) NOT NULL,
  `keterangan` varchar(100) NOT NULL,
  `bobot_sub` int(11) NOT NULL,
  `type` enum('Benefit','Cost') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `subcriteria`
--

INSERT INTO `subcriteria` (`id`, `criteria_id`, `field_id`, `keterangan`, `bobot_sub`, `type`) VALUES
(1, 1, 0, 'S1', 3, 'Benefit'),
(2, 1, 0, 'D3', 2, 'Benefit'),
(3, 3, 6, 'S1', 3, 'Benefit'),
(7, 2, 3, '> 3 Tahun', 3, 'Benefit'),
(8, 2, 3, 'Freshgraduate', 1, 'Benefit'),
(9, 2, 3, '> 1 Tahun', 2, 'Benefit'),
(10, 1, 3, 'SMA', 1, 'Benefit'),
(11, 4, 5, 'S1', 3, 'Benefit');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `alternatives`
--
ALTER TABLE `alternatives`
  ADD PRIMARY KEY (`id`),
  ADD KEY `field_id` (`field_id`),
  ADD KEY `data_id` (`data_id`);

--
-- Indeks untuk tabel `criteria`
--
ALTER TABLE `criteria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `field_id` (`field_id`);

--
-- Indeks untuk tabel `data`
--
ALTER TABLE `data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_lowongan` (`id_lowongan`);

--
-- Indeks untuk tabel `lowongan`
--
ALTER TABLE `lowongan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `field_id` (`field_id`);

--
-- Indeks untuk tabel `pekerjaan`
--
ALTER TABLE `pekerjaan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `subcriteria`
--
ALTER TABLE `subcriteria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `criteria_id` (`criteria_id`),
  ADD KEY `field_id` (`field_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `alternatives`
--
ALTER TABLE `alternatives`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `criteria`
--
ALTER TABLE `criteria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `data`
--
ALTER TABLE `data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `lowongan`
--
ALTER TABLE `lowongan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `pekerjaan`
--
ALTER TABLE `pekerjaan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `subcriteria`
--
ALTER TABLE `subcriteria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `criteria`
--
ALTER TABLE `criteria`
  ADD CONSTRAINT `criteria_ibfk_1` FOREIGN KEY (`field_id`) REFERENCES `pekerjaan` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `data`
--
ALTER TABLE `data`
  ADD CONSTRAINT `data_ibfk_1` FOREIGN KEY (`id_lowongan`) REFERENCES `lowongan` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `lowongan`
--
ALTER TABLE `lowongan`
  ADD CONSTRAINT `lowongan_ibfk_1` FOREIGN KEY (`field_id`) REFERENCES `pekerjaan` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `subcriteria`
--
ALTER TABLE `subcriteria`
  ADD CONSTRAINT `subcriteria_ibfk_1` FOREIGN KEY (`criteria_id`) REFERENCES `criteria` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
