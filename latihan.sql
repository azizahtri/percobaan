-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 09 Des 2025 pada 05.58
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
(1, 'Administrator', 'admin', 'admin123');

-- --------------------------------------------------------

--
-- Struktur dari tabel `alternatives`
--

CREATE TABLE `alternatives` (
  `id` int(11) NOT NULL,
  `pekerjaan_id` int(11) NOT NULL,
  `kode` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `status` enum('Aktif','Tidak Aktif') DEFAULT 'Aktif',
  `created_at` datetime DEFAULT current_timestamp(),
  `skor_akhir` double DEFAULT 0,
  `detail_nilai` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `alternatives`
--

INSERT INTO `alternatives` (`id`, `pekerjaan_id`, `kode`, `nama`, `status`, `created_at`, `skor_akhir`, `detail_nilai`) VALUES
(1, 1, 'K001', 'Azizah Tri Kusumadewi', 'Aktif', '2025-12-02 13:39:41', 4, '{\"1\":\"4\"}');

-- --------------------------------------------------------

--
-- Struktur dari tabel `criteria`
--

CREATE TABLE `criteria` (
  `id` int(11) NOT NULL,
  `pekerjaan_id` int(11) DEFAULT NULL,
  `kode` varchar(10) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `bobot` float NOT NULL,
  `tipe` enum('Benefit','Cost') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `criteria`
--

INSERT INTO `criteria` (`id`, `pekerjaan_id`, `kode`, `nama`, `bobot`, `tipe`) VALUES
(1, 1, 'C1', 'Pendidikan Terakhir', 3, 'Benefit'),
(2, 3, 'C1', 'Pengalaman', 4, 'Benefit'),
(3, 2, 'C1', 'Sertifikat', 2, 'Benefit');

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
  `form_data` text DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `status` enum('proses','memenuhi','tidak memenuhi') DEFAULT 'proses',
  `spk_score` decimal(5,2) DEFAULT 0.00,
  `is_history` int(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `data`
--

INSERT INTO `data` (`id`, `id_lowongan`, `nama`, `email`, `no_hp`, `pesan`, `form_data`, `link`, `status`, `spk_score`, `is_history`, `created_at`) VALUES
(1, 1, 'Azizah Tri Kusumadewi', 'azizahtrikusuma.13@gmail.com', '081385398213', 'asdasdasd', NULL, 'https://drive.google.com/drive/folders/1Uez6A99RZweOWPLM7Z0ZqvhTcQsfr8Hl?usp=drive_link', 'memenuhi', 1.00, 1, '2025-12-02 12:57:59'),
(2, 1, 'Azizah Tri Kusumadewi', 'azizahtrikusuma.13@gmail.com', '081385398213', '', NULL, 'https://drive.google.com/drive/folders/1Uez6A99RZweOWPLM7Z0ZqvhTcQsfr8Hl?usp=drive_link', 'memenuhi', 4.00, 1, '2025-12-03 13:49:19'),
(3, 2, 'Azizah Tri Kusumadewi', 'azizahtrikusuma.13@gmail.com', '081385398213', 'asasas', '{\"apakah?\":\"iya\",\"bisakah?\":\"tidak\",\"bolehkah?\":\"mungkin\"}', 'https://drive.google.com/drive/folders/1Uez6A99RZweOWPLM7Z0ZqvhTcQsfr8Hl?usp=drive_link', 'proses', 1.00, 0, '2025-12-04 09:55:51'),
(5, 10, 'Azizah Tri Kusumadewi', 'azizahtrikusuma.13@gmail.com', '081385398213', '5555555555555555', '{\"apakah anda menguasai salah satu aplikasi ini?\":[\"app 1\",\"app 3\"]}', 'https://drive.google.com/drive/folders/1Uez6A99RZweOWPLM7Z0ZqvhTcQsfr8Hl?usp=drive_link', 'proses', 1.00, 0, '2025-12-04 14:01:03'),
(6, 11, 'Azizah Tri Kusumadewi', 'azizahtrikusuma.13@gmail.com', '081385398213', '', '{\"apakah anda menguasai salah satu aplikasi ini?\":[\"app 1\"],\"Apakah anda siap jika dilakukan mutasi?\":\"Iya\",\"jelaskan alasannya\":\"asdsasd\",\"berapa range gaji yang kamu ekspektasikan?\":\"Antara 1-2 Juta\"}', 'https://drive.google.com/drive/folders/1Uez6A99RZweOWPLM7Z0ZqvhTcQsfr8Hl?usp=drive_link', 'proses', NULL, 0, '2025-12-04 17:53:09');

-- --------------------------------------------------------

--
-- Struktur dari tabel `formulir`
--

CREATE TABLE `formulir` (
  `id` int(11) NOT NULL,
  `nama_template` varchar(100) NOT NULL,
  `config` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `formulir`
--

INSERT INTO `formulir` (`id`, `nama_template`, `config`, `created_at`) VALUES
(4, 'Technical Operation', '[{\"label\":\"apakah anda menguasai salah satu aplikasi ini?\",\"type\":\"checkbox\",\"options\":\"app 1, app 2, app 3\"},{\"label\":\"Apakah anda siap jika dilakukan mutasi?\",\"type\":\"radio\",\"options\":\"Iya, Tidak, bisa akan tetapi\"},{\"label\":\"jelaskan alasannya\",\"type\":\"textarea\",\"options\":\"\"},{\"label\":\"berapa range gaji yang kamu ekspektasikan?\",\"type\":\"radio\",\"options\":\"Lebih dari 1 Juta, Antara 1-2 Juta, Di atas 2 Juta\"}]', '2025-12-04 13:47:37'),
(5, 'Staff', '[{\"label\":\"Apakah anda siap untuk mutasi\",\"type\":\"radio\",\"options\":\"Iya, Tidak, bisa akan tetapi\"},{\"label\":\"berikan alasannya\",\"type\":\"textarea\",\"options\":\"\"}]', '2025-12-04 17:51:27');

-- --------------------------------------------------------

--
-- Struktur dari tabel `lowongan`
--

CREATE TABLE `lowongan` (
  `id` int(11) NOT NULL,
  `pekerjaan_id` int(11) NOT NULL,
  `formulir_id` int(11) DEFAULT NULL,
  `judul_lowongan` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `jenis` enum('Magang','Full Time','Part Time') NOT NULL DEFAULT 'Full Time',
  `link_google_form` varchar(255) DEFAULT NULL,
  `tanggal_posting` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `lowongan`
--

INSERT INTO `lowongan` (`id`, `pekerjaan_id`, `formulir_id`, `judul_lowongan`, `deskripsi`, `jenis`, `link_google_form`, `tanggal_posting`) VALUES
(1, 1, 4, 'Accounting', '<p>Add .navbar-nav-scroll to a .navbar-nav (or other navbar sub-component) to enable vertical scrolling within the toggleable contents of a collapsed navbar. By default, scrolling kicks in at 75vh (or 75% of the viewport height), but you can override that with the local CSS custom property --bs-navbar-height or custom styles. At larger viewports when the navbar is expanded, content will appear as it does in a default navbar.</p><p><br></p><p>Please note that this behavior comes with a potential drawback of overflow—when setting overflow-y: auto (required to scroll the content here), overflow-x is the equivalent of auto, which will crop some horizontal content.</p><p><br></p><p>Here’s an example navbar using .navbar-nav-scroll with style=\"--bs-scroll-height: 100px;\", with some extra margin utilities for optimum spacing.</p>', 'Full Time', '', '2025-12-01 00:00:00'),
(2, 1, 4, 'Tax Staff', '<p>aqxasdasdasd</p>', 'Full Time', '', '2025-12-03 00:00:00'),
(5, 5, 4, 'Design Graphic', '<p>asdf</p>', 'Full Time', '', '2025-12-03 00:00:00'),
(10, 2, 4, 'Programmer', '<p>1234567890</p>', 'Full Time', 'https://docs.google.com/forms/d/e/1FAIpQLSemSpkKBZVpEzbFu1B0gxIy0X7A4h9bP7tAZ6qULa6rTjyfHQ/viewform?usp=header', '2025-12-04 00:00:00'),
(11, 3, 4, 'Intern', '<p>sdfgh</p>', 'Full Time', '', '2025-12-04 00:00:00'),
(12, 6, 4, 'IT Support', '<div class=\"ql-code-block-container\" spellcheck=\"false\"><div class=\"ql-code-block\" data-language=\"plain\">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div><div class=\"ql-code-block\" data-language=\"plain\"><br></div><div class=\"ql-code-block\" data-language=\"plain\">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem.</div><div class=\"ql-code-block\" data-language=\"plain\"><br></div><div class=\"ql-code-block\" data-language=\"plain\">At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus.</div></div><p><br></p>', 'Full Time', '', '2025-12-04 00:00:00'),
(13, 7, 5, 'KOL Specialist', '<div class=\"ql-code-block-container\" spellcheck=\"false\"><div class=\"ql-code-block\" data-language=\"plain\">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div><div class=\"ql-code-block\" data-language=\"plain\"><br></div><div class=\"ql-code-block\" data-language=\"plain\">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem.</div><div class=\"ql-code-block\" data-language=\"plain\"><br></div><div class=\"ql-code-block\" data-language=\"plain\">At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus.</div></div><p><br></p>', 'Full Time', '', '2025-12-04 00:00:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pekerjaan`
--

CREATE TABLE `pekerjaan` (
  `id` int(11) NOT NULL,
  `divisi` varchar(100) NOT NULL,
  `posisi` varchar(100) NOT NULL,
  `standar_spk` decimal(5,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pekerjaan`
--

INSERT INTO `pekerjaan` (`id`, `divisi`, `posisi`, `standar_spk`) VALUES
(1, 'Finance', 'Accounting Staff', 4.00),
(2, 'IT', 'Web Programmer', 1.00),
(3, 'HR', 'Recruitment Staff', 3.00),
(4, 'Finance', 'Accounting Senior', 0.00),
(5, 'Sales', 'Design', 0.00),
(6, 'IT', 'IT Support', 0.00),
(7, 'Sales', 'KOL Specialist', 0.00);

-- --------------------------------------------------------

--
-- Struktur dari tabel `subcriteria`
--

CREATE TABLE `subcriteria` (
  `id` int(11) NOT NULL,
  `criteria_id` int(11) NOT NULL,
  `keterangan` varchar(100) NOT NULL,
  `bobot_sub` int(11) NOT NULL,
  `tipe` enum('Benefit','Cost') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `subcriteria`
--

INSERT INTO `subcriteria` (`id`, `criteria_id`, `keterangan`, `bobot_sub`, `tipe`) VALUES
(1, 1, 'SMA/SMK', 1, 'Benefit'),
(2, 3, 'Workshop', 1, 'Benefit'),
(3, 1, 'S1', 4, 'Benefit');

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
  ADD KEY `pekerjaan_id` (`pekerjaan_id`);

--
-- Indeks untuk tabel `criteria`
--
ALTER TABLE `criteria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pekerjaan_id` (`pekerjaan_id`);

--
-- Indeks untuk tabel `data`
--
ALTER TABLE `data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_lowongan` (`id_lowongan`);

--
-- Indeks untuk tabel `formulir`
--
ALTER TABLE `formulir`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `lowongan`
--
ALTER TABLE `lowongan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pekerjaan_id` (`pekerjaan_id`),
  ADD KEY `fk_lowongan_formulir` (`formulir_id`);

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
  ADD KEY `criteria_id` (`criteria_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `criteria`
--
ALTER TABLE `criteria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `data`
--
ALTER TABLE `data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `formulir`
--
ALTER TABLE `formulir`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `lowongan`
--
ALTER TABLE `lowongan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `pekerjaan`
--
ALTER TABLE `pekerjaan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `subcriteria`
--
ALTER TABLE `subcriteria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `alternatives`
--
ALTER TABLE `alternatives`
  ADD CONSTRAINT `alternatives_ibfk_1` FOREIGN KEY (`pekerjaan_id`) REFERENCES `pekerjaan` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `criteria`
--
ALTER TABLE `criteria`
  ADD CONSTRAINT `criteria_ibfk_1` FOREIGN KEY (`pekerjaan_id`) REFERENCES `pekerjaan` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `data`
--
ALTER TABLE `data`
  ADD CONSTRAINT `data_ibfk_1` FOREIGN KEY (`id_lowongan`) REFERENCES `lowongan` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `lowongan`
--
ALTER TABLE `lowongan`
  ADD CONSTRAINT `fk_lowongan_formulir` FOREIGN KEY (`formulir_id`) REFERENCES `formulir` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `lowongan_ibfk_1` FOREIGN KEY (`pekerjaan_id`) REFERENCES `pekerjaan` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `subcriteria`
--
ALTER TABLE `subcriteria`
  ADD CONSTRAINT `subcriteria_ibfk_1` FOREIGN KEY (`criteria_id`) REFERENCES `criteria` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
