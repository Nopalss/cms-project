-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 03 Des 2025 pada 03.54
-- Versi server: 8.4.3
-- Versi PHP: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Basis data: `cms_database`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `admin_key` int NOT NULL,
  `admin_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `name` varchar(200) NOT NULL,
  `phone` varchar(200) NOT NULL,
  `username` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`admin_key`, `admin_id`, `name`, `phone`, `username`) VALUES
(1, 'A202509261249', 'Nopal', '0818181811', 'admin'),
(2, 'A202511061621', 'napo', '0823855484', 'napo59');

-- --------------------------------------------------------

--
-- Struktur dari tabel `customers`
--

CREATE TABLE `customers` (
  `netpay_key` int NOT NULL,
  `netpay_id` varchar(100) NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `perumahan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `location` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `paket_internet` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_active` enum('Active','Inactive') DEFAULT 'Inactive',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `customers`
--

INSERT INTO `customers` (`netpay_key`, `netpay_id`, `name`, `perumahan`, `location`, `phone`, `paket_internet`, `updated_at`, `is_active`, `created_at`) VALUES
(8582, '123456789', 'admin', 'Jangan Dihapus', 'Jangan Dihapus', '62816562554', '100', '2025-11-27 14:27:48', 'Inactive', '2025-11-27 14:27:48');

-- --------------------------------------------------------

--
-- Struktur dari tabel `dismantle_reports`
--

CREATE TABLE `dismantle_reports` (
  `dismantle_key` int NOT NULL,
  `dismantle_id` varchar(100) NOT NULL DEFAULT 'AUTO_INCREMENT',
  `schedule_key` int NOT NULL DEFAULT (0),
  `netpay_key` int NOT NULL DEFAULT (0),
  `tanggal` date NOT NULL,
  `jam` time NOT NULL,
  `alasan` text NOT NULL,
  `action` text NOT NULL,
  `part_removed` text,
  `kondisi_perangkat` text,
  `pic` varchar(100) NOT NULL,
  `keterangan` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ikr`
--

CREATE TABLE `ikr` (
  `ikr_key` int NOT NULL,
  `ikr_id` varchar(50) NOT NULL,
  `netpay_key` int DEFAULT NULL,
  `group_ikr` varchar(50) DEFAULT NULL,
  `ikr_an` varchar(100) DEFAULT NULL,
  `alamat` text,
  `rt` varchar(10) DEFAULT NULL,
  `rw` varchar(10) DEFAULT NULL,
  `desa` varchar(50) DEFAULT NULL,
  `kec` varchar(50) DEFAULT NULL,
  `kab` varchar(50) DEFAULT NULL,
  `telp` varchar(20) DEFAULT NULL,
  `sn` varchar(100) DEFAULT NULL,
  `paket` varchar(50) DEFAULT NULL,
  `type_ont` varchar(50) DEFAULT NULL,
  `redaman` varchar(20) DEFAULT NULL,
  `odp_no` varchar(50) DEFAULT NULL,
  `odc_no` varchar(50) DEFAULT NULL,
  `jc_no` varchar(50) DEFAULT NULL,
  `mac_sebelum` varchar(50) DEFAULT NULL,
  `mac_sesudah` varchar(50) DEFAULT NULL,
  `odp` varchar(50) DEFAULT NULL,
  `odc` varchar(50) DEFAULT NULL,
  `enclosure` varchar(50) DEFAULT NULL,
  `paket_no` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `schedule_key` int DEFAULT NULL,
  `pic` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `issues_report`
--

CREATE TABLE `issues_report` (
  `issue_key` int NOT NULL,
  `issue_id` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `schedule_id` varchar(100) NOT NULL DEFAULT '0',
  `reported_by` varchar(200) NOT NULL DEFAULT '',
  `issue_type` enum('Absence','Equipment','Customer not available','Other') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT (now()),
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `queue_scheduling`
--

CREATE TABLE `queue_scheduling` (
  `queue_key` int NOT NULL,
  `queue_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `type_queue` enum('Install','Maintenance','Dismantle') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `request_id` varchar(200) DEFAULT NULL,
  `status` enum('Accepted','Rejected','Pending') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `queue_scheduling`
--

INSERT INTO `queue_scheduling` (`queue_key`, `queue_id`, `type_queue`, `request_id`, `status`, `created_at`) VALUES
(2, 'Q20251129184228', 'Maintenance', 'RM2025112918422157', 'Accepted', '2025-11-29 11:42:28');

-- --------------------------------------------------------

--
-- Struktur dari tabel `register`
--

CREATE TABLE `register` (
  `registrasi_key` int NOT NULL,
  `registrasi_id` varchar(100) NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `location` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `paket_internet` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `is_verified` enum('Verified','Unverified') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'Unverified',
  `date` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `time` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `request_dismantle`
--

CREATE TABLE `request_dismantle` (
  `rd_key` int NOT NULL,
  `rd_id` varchar(100) NOT NULL,
  `netpay_key` int DEFAULT NULL,
  `type_dismantle` enum('Pindah Alamat','Biaya Mahal','Jarang Digunakan','Pelayanan Buruk','Gangguan Berkepanjangan','Ganti Provider','Lainnya') NOT NULL,
  `deskripsi_dismantle` text,
  `request_by` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `request_ikr`
--

CREATE TABLE `request_ikr` (
  `rikr_key` int NOT NULL,
  `rikr_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `netpay_key` int DEFAULT NULL,
  `registrasi_key` int DEFAULT NULL,
  `jadwal_pemasangan` varchar(100) DEFAULT NULL,
  `catatan` text,
  `request_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `request_maintenance`
--

CREATE TABLE `request_maintenance` (
  `rm_key` int NOT NULL,
  `rm_id` varchar(100) NOT NULL,
  `netpay_key` int DEFAULT NULL,
  `type_issue` enum('Signal Lemah','Modem Rusak','Kabel Bermasalah','Gangguan Internet','Upgrade Paket','Lainnya') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `deskripsi_issue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `request_by` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `request_maintenance`
--

INSERT INTO `request_maintenance` (`rm_key`, `rm_id`, `netpay_key`, `type_issue`, `deskripsi_issue`, `request_by`, `created_at`) VALUES
(2, 'RM2025112918422157', 8582, 'Upgrade Paket', 'kas', 'admin', '2025-11-29 11:42:28');

-- --------------------------------------------------------

--
-- Struktur dari tabel `schedules`
--

CREATE TABLE `schedules` (
  `schedule_key` int NOT NULL,
  `schedule_id` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `tech_id` varchar(100) NOT NULL DEFAULT '0',
  `netpay_key` int DEFAULT NULL,
  `date` date NOT NULL DEFAULT (0),
  `time` time NOT NULL DEFAULT (0),
  `job_type` enum('Instalasi','Maintenance','Dismantle') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `status` enum('Pending','Actived','Rescheduled','Cancelled','Done') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'Pending',
  `queue_key` int DEFAULT NULL,
  `catatan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `schedules`
--

INSERT INTO `schedules` (`schedule_key`, `schedule_id`, `tech_id`, `netpay_key`, `date`, `time`, `job_type`, `status`, `queue_key`, `catatan`) VALUES
(1, 'S20251129190133', 'T202511291801', 8582, '2025-11-29', '13:00:00', 'Maintenance', 'Done', 2, 'kas');

-- --------------------------------------------------------

--
-- Struktur dari tabel `service_reports`
--

CREATE TABLE `service_reports` (
  `srv_key` int NOT NULL,
  `srv_id` varchar(100) NOT NULL,
  `tanggal` date NOT NULL,
  `jam` varchar(50) NOT NULL DEFAULT '0',
  `netpay_key` int NOT NULL DEFAULT (0),
  `problem` text NOT NULL,
  `action` text NOT NULL,
  `part` varchar(255) NOT NULL,
  `red_bef` varchar(50) NOT NULL,
  `red_aft` varchar(50) NOT NULL,
  `pic` varchar(100) NOT NULL,
  `keterangan` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `schedule_key` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `service_reports`
--

INSERT INTO `service_reports` (`srv_key`, `srv_id`, `tanggal`, `jam`, `netpay_key`, `problem`, `action`, `part`, `red_bef`, `red_aft`, `pic`, `keterangan`, `created_at`, `updated_at`, `schedule_key`) VALUES
(1, 'SR20251129190517', '2025-11-29', '19:05', 8582, 'n', 'n', 'n', 'n', 'n', 'T202511291801', 'n', '2025-11-29 12:05:24', '2025-11-29 12:05:24', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `technician`
--

CREATE TABLE `technician` (
  `tech_key` int NOT NULL,
  `tech_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `name` varchar(200) NOT NULL,
  `phone` varchar(200) NOT NULL,
  `username` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `technician`
--

INSERT INTO `technician` (`tech_key`, `tech_id`, `name`, `phone`, `username`) VALUES
(1, 'T202511291801', 'Desta', '0829348338', 'desta87');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `role` enum('admin','teknisi') NOT NULL,
  `avatar` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `avatar`) VALUES
(1, 'admin', '$2y$10$1aVdAIzK.P5cgStKJT6OF.dpIOV4ux.0b4P6r9rGhFFg0zaltytfy', 'admin', '691f196c0c9fe.jpg'),
(2, 'desta28', '$2y$10$vAp0nDtNc7pFbRuhC8KmV.C2dA1SgNWuXoGtVCDL/JoRQA.FIeXeu', 'teknisi', '691f196c0c9fe.jpg'),
(3, 'ifannard60', '$2y$10$WF8Tl2XZvBLQbetYebLdiOT7VubggnT0L9PNfveZuDbVDDW4zwm.i', 'teknisi', 'blank.png'),
(4, 'muhammad96', '$2y$10$DKJKtTsbaz7m.7YpPRQLOeZ05Dhyjs1e261AeQeTmVt6rNiBdfSu.', 'teknisi', 'blank.png'),
(5, 'napo59', '$2y$10$OqH7BpkVexLgzAES7djXeOkSAPD29oAWoTjqBkozQsqJDIdz9PrJa', 'admin', 'blank.png'),
(6, 'hadi86', '$2y$10$o5u9qx.6uPM04wt1FRzBWeb7faT.EyqsNKPfj8YdaLLJl6o9PpZim', 'teknisi', 'blank.png'),
(7, 'desta87', '$2y$10$9DotFsbvxiq7ZegbCl5jqeEja7OflKumOid4g9/BVc.NI1Q6LQFaq', 'teknisi', 'blank.png');

--
-- Indeks untuk tabel yang dibuang
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_key`) USING BTREE,
  ADD UNIQUE KEY `UNIQUE KEY` (`admin_id`) USING BTREE,
  ADD KEY `username` (`username`);

--
-- Indeks untuk tabel `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`netpay_key`) USING BTREE,
  ADD UNIQUE KEY `UNIQUE KEY` (`netpay_id`) USING BTREE;

--
-- Indeks untuk tabel `dismantle_reports`
--
ALTER TABLE `dismantle_reports`
  ADD PRIMARY KEY (`dismantle_key`) USING BTREE,
  ADD UNIQUE KEY `UNIQUE KEY` (`dismantle_id`),
  ADD KEY `schedule_key` (`schedule_key`,`netpay_key`),
  ADD KEY `pic` (`pic`);

--
-- Indeks untuk tabel `ikr`
--
ALTER TABLE `ikr`
  ADD PRIMARY KEY (`ikr_key`) USING BTREE,
  ADD UNIQUE KEY `UNIQUE KEY` (`ikr_id`) USING BTREE,
  ADD KEY `netpay_key` (`netpay_key`,`pic`);

--
-- Indeks untuk tabel `issues_report`
--
ALTER TABLE `issues_report`
  ADD PRIMARY KEY (`issue_key`) USING BTREE,
  ADD UNIQUE KEY `UNIQUE KEY` (`issue_id`),
  ADD KEY `schedule_id` (`schedule_id`,`reported_by`);

--
-- Indeks untuk tabel `queue_scheduling`
--
ALTER TABLE `queue_scheduling`
  ADD PRIMARY KEY (`queue_key`) USING BTREE,
  ADD UNIQUE KEY `UNIQUE KEY` (`queue_id`),
  ADD KEY `request_id` (`request_id`);

--
-- Indeks untuk tabel `register`
--
ALTER TABLE `register`
  ADD PRIMARY KEY (`registrasi_key`) USING BTREE,
  ADD UNIQUE KEY `UNIQUE KEY` (`registrasi_id`);

--
-- Indeks untuk tabel `request_dismantle`
--
ALTER TABLE `request_dismantle`
  ADD PRIMARY KEY (`rd_key`) USING BTREE,
  ADD UNIQUE KEY `UNIQUE KEY` (`rd_id`) USING BTREE,
  ADD KEY `netpay_key` (`netpay_key`);

--
-- Indeks untuk tabel `request_ikr`
--
ALTER TABLE `request_ikr`
  ADD PRIMARY KEY (`rikr_key`) USING BTREE,
  ADD UNIQUE KEY `UNIQUE KEY` (`rikr_id`),
  ADD KEY `netpay_key` (`netpay_key`,`registrasi_key`);

--
-- Indeks untuk tabel `request_maintenance`
--
ALTER TABLE `request_maintenance`
  ADD PRIMARY KEY (`rm_key`) USING BTREE,
  ADD UNIQUE KEY `UNIQUE KEY` (`rm_id`),
  ADD KEY `netpay_key` (`netpay_key`);

--
-- Indeks untuk tabel `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`schedule_key`) USING BTREE,
  ADD UNIQUE KEY `UNIQUE KEY` (`schedule_id`),
  ADD KEY `tech_id` (`tech_id`,`netpay_key`,`queue_key`),
  ADD KEY `date` (`date`,`job_type`,`status`);

--
-- Indeks untuk tabel `service_reports`
--
ALTER TABLE `service_reports`
  ADD PRIMARY KEY (`srv_key`) USING BTREE,
  ADD UNIQUE KEY `UNIQUE KEY` (`srv_id`),
  ADD KEY `netpay_key` (`netpay_key`,`schedule_key`),
  ADD KEY `pic` (`pic`);

--
-- Indeks untuk tabel `technician`
--
ALTER TABLE `technician`
  ADD PRIMARY KEY (`tech_key`) USING BTREE,
  ADD UNIQUE KEY `UNIQUE KEY` (`tech_id`),
  ADD KEY `username` (`username`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `UNIQUE KEY` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_key` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `customers`
--
ALTER TABLE `customers`
  MODIFY `netpay_key` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8583;

--
-- AUTO_INCREMENT untuk tabel `dismantle_reports`
--
ALTER TABLE `dismantle_reports`
  MODIFY `dismantle_key` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `ikr`
--
ALTER TABLE `ikr`
  MODIFY `ikr_key` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `issues_report`
--
ALTER TABLE `issues_report`
  MODIFY `issue_key` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `queue_scheduling`
--
ALTER TABLE `queue_scheduling`
  MODIFY `queue_key` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `register`
--
ALTER TABLE `register`
  MODIFY `registrasi_key` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `request_dismantle`
--
ALTER TABLE `request_dismantle`
  MODIFY `rd_key` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `request_ikr`
--
ALTER TABLE `request_ikr`
  MODIFY `rikr_key` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `request_maintenance`
--
ALTER TABLE `request_maintenance`
  MODIFY `rm_key` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `schedules`
--
ALTER TABLE `schedules`
  MODIFY `schedule_key` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `service_reports`
--
ALTER TABLE `service_reports`
  MODIFY `srv_key` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `technician`
--
ALTER TABLE `technician`
  MODIFY `tech_key` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
