-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.4.3 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for cms_database
CREATE DATABASE IF NOT EXISTS `cms_database` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `cms_database`;

-- Dumping structure for table cms_database.admin
CREATE TABLE IF NOT EXISTS `admin` (
  `admin_key` int NOT NULL AUTO_INCREMENT,
  `admin_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `name` varchar(200) NOT NULL,
  `phone` varchar(200) NOT NULL,
  `username` varchar(200) NOT NULL,
  PRIMARY KEY (`admin_key`) USING BTREE,
  UNIQUE KEY `UNIQUE KEY` (`admin_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table cms_database.admin: ~1 rows (approximately)
INSERT INTO `admin` (`admin_key`, `admin_id`, `name`, `phone`, `username`) VALUES
	(1, 'A202509261249', 'Nopal', '08181818111', 'admin');

-- Dumping structure for table cms_database.customers
CREATE TABLE IF NOT EXISTS `customers` (
  `netpay_key` int NOT NULL AUTO_INCREMENT,
  `netpay_id` varchar(100) NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `perumahan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `location` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `paket_internet` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_active` enum('Active','Inactive') DEFAULT 'Inactive',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`netpay_key`) USING BTREE,
  UNIQUE KEY `UNIQUE KEY` (`netpay_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table cms_database.customers: ~9 rows (approximately)
INSERT INTO `customers` (`netpay_key`, `netpay_id`, `name`, `perumahan`, `location`, `phone`, `paket_internet`, `updated_at`, `is_active`, `created_at`) VALUES
	(1, '200000006', 'Fadil Jaidijnn', 'Gramapuri Persada', 'Perumahan GramaPuri Blok D16 No 18', '0823855484', '10', '2025-10-20 07:30:29', 'Inactive', '2025-10-03 21:37:01'),
	(2, '210000001', 'Ifan Nardiansyah', 'Puri Lestari', 'Perumahan Puri Lestari Blok D16 No 18', '081381653386', '30', '2025-10-03 13:21:58', 'Active', '2025-10-03 12:20:04'),
	(3, '210000003', 'Dedi', 'Puri Lestari', 'Cikarang', '08138172718', '30', '2025-10-03 13:12:17', 'Inactive', '2025-10-03 13:12:17'),
	(4, '210000005', 'Asep', 'Grama Puri', 'GramaPuri Persada Blok H1 No 1', '08172518233', '10', '2025-10-03 22:08:33', 'Inactive', '2025-10-03 13:24:31'),
	(5, '520000002', 'MUHAMMAD NAUFAL SAPUTRA', 'Puri Lestari', 'bekasi', '081381653372', '10', '2025-10-20 19:48:24', 'Active', '2025-10-03 13:11:51'),
	(6, '280000007', 'naufall', 'Telaga Harapan', 'bekasi', '081381653389', '10', '2025-10-17 06:45:25', 'Inactive', '2025-10-17 02:52:51'),
	(9, '200000008', 'naufal', 'Telaga Murni', 'bekasi', '081381653386', '30', '2025-10-18 00:04:36', 'Inactive', '2025-10-18 00:04:36'),
	(13, '200000011', 'naufal admin', 'Gramapuri Persada', 'bekasi', '081381653386', '30', '2025-10-20 04:47:38', 'Active', '2025-10-20 04:45:02'),
	(15, '210000012', 'Muhammad Naufal Saputra', 'Telaga Murni', 'Bekasii', '081381653372', '50', '2025-10-20 19:40:51', 'Inactive', '2025-10-20 19:02:06'),
	(17, '270000013', 'Ipan', 'Telaga Harapan', 'Banjar', '08181818111', '100', '2025-10-20 19:05:02', 'Inactive', '2025-10-20 19:05:02');
	(18, '270000014', 'udin', 'Telaga Harapan', 'Banjar', '08181818111', '100', '2025-10-20 19:05:02', 'Inactive', '2025-10-20 19:05:02');

-- Dumping structure for table cms_database.dismantle_reports
CREATE TABLE IF NOT EXISTS `dismantle_reports` (
  `dismantle_key` int NOT NULL AUTO_INCREMENT,
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
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`dismantle_key`) USING BTREE,
  UNIQUE KEY `UNIQUE KEY` (`dismantle_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table cms_database.dismantle_reports: ~1 rows (approximately)
INSERT INTO `dismantle_reports` (`dismantle_key`, `dismantle_id`, `schedule_key`, `netpay_key`, `tanggal`, `jam`, `alasan`, `action`, `part_removed`, `kondisi_perangkat`, `pic`, `keterangan`, `created_at`, `updated_at`) VALUES
	(3, 'DR20251021024043', 14, 15, '2025-10-21', '02:40:00', 'm', 'm', 'm', 'm', 'T202510011009', 'mm', '2025-10-20 19:40:51', '2025-10-20 19:48:39');

-- Dumping structure for table cms_database.ikr
CREATE TABLE IF NOT EXISTS `ikr` (
  `ikr_key` int NOT NULL AUTO_INCREMENT,
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
  `pic` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ikr_key`) USING BTREE,
  UNIQUE KEY `UNIQUE KEY` (`ikr_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table cms_database.ikr: ~0 rows (approximately)
INSERT INTO `ikr` (`ikr_key`, `ikr_id`, `netpay_key`, `group_ikr`, `ikr_an`, `alamat`, `rt`, `rw`, `desa`, `kec`, `kab`, `telp`, `sn`, `paket`, `type_ont`, `redaman`, `odp_no`, `odc_no`, `jc_no`, `mac_sebelum`, `mac_sesudah`, `odp`, `odc`, `enclosure`, `paket_no`, `created_at`, `updated_at`, `schedule_key`, `pic`) VALUES
	(5, 'SI20251020044718', 13, 'a', 'naufal admin', 'bekasi', '2', '3', 'k', 'k', 'k', '081381653386', 'k', '100', 'k', 'k', 'k', 'k', 'k', 'k', 'k', 'k', 'k', 'k', 'k', '2025-10-20 04:47:38', '2025-10-20 04:47:38', 9, 'T202510011009'),
	(6, 'SI20251021022037', 15, 'sk', 'Muhammad Naufal Saputra', 'Bekasiii', '70', '70', 'Sukajayaa', 'Cibitungg', 'Kabupaten Bekasii', '081381653373', '39', '51', 'ZTE F660 V8', '879', 'ODP-SKMJ-02', 'y', 'JC-046', 'D4:F5:13:9A:7B:2D', 'D4:F5::13:9A:7B:3C', 'ODP Sukajaya 2', '54344', 'Enclosure-01', 'XXX', '2025-10-20 19:21:34', '2025-10-20 19:22:37', 12, 'T202510011009');

-- Dumping structure for table cms_database.issues_report
CREATE TABLE IF NOT EXISTS `issues_report` (
  `issue_key` int NOT NULL AUTO_INCREMENT,
  `issue_id` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `schedule_id` varchar(100) NOT NULL DEFAULT '0',
  `reported_by` varchar(200) NOT NULL DEFAULT '',
  `issue_type` enum('Absence','Equipment','Customer not available','Other') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT (now()),
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  PRIMARY KEY (`issue_key`) USING BTREE,
  UNIQUE KEY `UNIQUE KEY` (`issue_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table cms_database.issues_report: ~0 rows (approximately)
INSERT INTO `issues_report` (`issue_key`, `issue_id`, `schedule_id`, `reported_by`, `issue_type`, `description`, `created_at`, `status`) VALUES
	(3, 'I20251021020930', 'S20251021020609', 'T202510011009', 'Customer not available', 'm', '2025-10-20 19:09:30', 'Approved'),
	(5, 'I20251021022646', 'S20251021022524', 'T202510011009', 'Other', 'n', '2025-10-20 19:26:46', 'Approved');

-- Dumping structure for table cms_database.queue_scheduling
CREATE TABLE IF NOT EXISTS `queue_scheduling` (
  `queue_key` int NOT NULL AUTO_INCREMENT,
  `queue_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `type_queue` enum('Install','Maintenance','Dismantle') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `request_id` varchar(200) DEFAULT NULL,
  `status` enum('Accepted','Rejected','Pending') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`queue_key`) USING BTREE,
  UNIQUE KEY `UNIQUE KEY` (`queue_id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table cms_database.queue_scheduling: ~5 rows (approximately)
INSERT INTO `queue_scheduling` (`queue_key`, `queue_id`, `type_queue`, `request_id`, `status`, `created_at`) VALUES
	(25, 'Q20251018005238', 'Maintenance', 'RM20251018005233', 'Accepted', '2025-10-18 00:52:38'),
	(26, 'Q20251018151751', 'Install', 'RIKR20251018151730', 'Pending', '2025-10-18 15:17:51'),
	(27, 'Q20251020044502', 'Install', 'RIKR20251020044429', 'Accepted', '2025-10-20 04:45:02'),
	(28, 'Q20251020070459', 'Dismantle', 'RD20251020070453', 'Accepted', '2025-10-20 07:04:59'),
	(30, 'Q20251021020206', 'Install', 'RIKR20251021020118', 'Accepted', '2025-10-20 19:02:06'),
	(32, 'Q20251021020502', 'Install', 'RIKR20251021020448', 'Pending', '2025-10-20 19:05:02'),
	(33, 'Q20251021022404', 'Maintenance', 'RM20251021022347', 'Accepted', '2025-10-20 19:24:04'),
	(35, 'Q20251021023914', 'Dismantle', 'RD20251021023903', 'Accepted', '2025-10-20 19:39:14');

-- Dumping structure for table cms_database.register
CREATE TABLE IF NOT EXISTS `register` (
  `registrasi_key` int NOT NULL AUTO_INCREMENT,
  `registrasi_id` varchar(100) NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `location` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `paket_internet` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `is_verified` enum('Verified','Unverified') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'Unverified',
  `date` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `time` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`registrasi_key`) USING BTREE,
  UNIQUE KEY `UNIQUE KEY` (`registrasi_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table cms_database.register: ~13 rows (approximately)
INSERT INTO `register` (`registrasi_key`, `registrasi_id`, `name`, `location`, `phone`, `paket_internet`, `is_verified`, `date`, `time`, `updated_at`, `created_at`) VALUES
	(19, 'REG68f68657e7ac2', 'Muhammad Naufal Saputra', 'Bekasi', '081381653372', '50', 'Verified', '2025-10-22', '14:00', '2025-10-20 19:02:06', '2025-10-21 01:58:31'),
	(20, 'REG68f6866e7206d', 'Ipan', 'Banjar', '08181818111', '100', 'Verified', '2025-10-22', '10:00', '2025-10-20 19:05:02', '2025-10-21 01:58:54'),
	(21, 'REG68f68684baeb9', 'Fajar', 'Bandung', '0823855484', '50', 'Unverified', '2025-10-22', '14:00', '2025-10-20 18:59:16', '2025-10-21 01:59:16'),
	(22, 'REG68f6869c104a7', 'Jaya', 'Kerawang', '0823855484', '30', 'Unverified', '2025-10-30', '13:00', '2025-10-20 19:00:30', '2025-10-21 01:59:40'),
	(23, 'REG68f686baaa506', 'Panda', 'Cibitung', '081381653387', '50', 'Unverified', '2025-10-24', '13:00', '2025-10-20 19:00:10', '2025-10-21 02:00:10');

-- Dumping structure for table cms_database.request_dismantle
CREATE TABLE IF NOT EXISTS `request_dismantle` (
  `rd_key` int NOT NULL AUTO_INCREMENT,
  `rd_id` varchar(100) NOT NULL,
  `netpay_key` int DEFAULT NULL,
  `type_dismantle` enum('Pindah Alamat','Biaya Mahal','Jarang Digunakan','Pelayanan Buruk','Gangguan Berkepanjangan','Ganti Provider','Lainnya') NOT NULL,
  `deskripsi_dismantle` text,
  `request_by` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`rd_key`) USING BTREE,
  UNIQUE KEY `UNIQUE KEY` (`rd_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table cms_database.request_dismantle: ~2 rows (approximately)
INSERT INTO `request_dismantle` (`rd_key`, `rd_id`, `netpay_key`, `type_dismantle`, `deskripsi_dismantle`, `request_by`, `created_at`) VALUES
	(9, 'RD20251020070453', 5, 'Gangguan Berkepanjangan', 'n', 'admin', '2025-10-20 07:04:59'),
	(10, 'RD20251021023903', 15, 'Ganti Provider', 'Ingin Ganti Provider', 'admin', '2025-10-20 19:39:14');

-- Dumping structure for table cms_database.request_ikr
CREATE TABLE IF NOT EXISTS `request_ikr` (
  `rikr_key` int NOT NULL AUTO_INCREMENT,
  `rikr_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `netpay_key` int DEFAULT NULL,
  `registrasi_key` int DEFAULT NULL,
  `jadwal_pemasangan` varchar(100) DEFAULT NULL,
  `catatan` text,
  `request_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`rikr_key`) USING BTREE,
  UNIQUE KEY `UNIQUE KEY` (`rikr_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table cms_database.request_ikr: ~2 rows (approximately)
INSERT INTO `request_ikr` (`rikr_key`, `rikr_id`, `netpay_key`, `registrasi_key`, `jadwal_pemasangan`, `catatan`, `request_by`, `updated_at`) VALUES
	(14, 'RIKR20251021020118', 15, 19, '2025-10-21T14:00', 'Jangan Lupa ucap salamm', 'admin', '2025-10-20 19:03:43'),
	(16, 'RIKR20251021020448', 17, 20, '2025-10-22T10:00', 'm', 'admin', '2025-10-20 19:05:02');

-- Dumping structure for table cms_database.request_maintenance
CREATE TABLE IF NOT EXISTS `request_maintenance` (
  `rm_key` int NOT NULL AUTO_INCREMENT,
  `rm_id` varchar(100) NOT NULL,
  `netpay_key` int DEFAULT NULL,
  `type_issue` enum('Signal Lemah','Modem Rusak','Kabel Bermasalah','Gangguan Internet','Upgrade Paket','Lainnya') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `deskripsi_issue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `request_by` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`rm_key`) USING BTREE,
  UNIQUE KEY `UNIQUE KEY` (`rm_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table cms_database.request_maintenance: ~1 rows (approximately)
INSERT INTO `request_maintenance` (`rm_key`, `rm_id`, `netpay_key`, `type_issue`, `deskripsi_issue`, `request_by`, `created_at`) VALUES
	(8, 'RM20251018005233', 5, 'Modem Rusak', 'b', 'admin', '2025-10-18 00:52:38'),
	(9, 'RM20251021022347', 15, 'Gangguan Internet', 'Sering Kendala jaringann', 'admin', '2025-10-20 19:24:04');

-- Dumping structure for table cms_database.schedules
CREATE TABLE IF NOT EXISTS `schedules` (
  `schedule_key` int NOT NULL AUTO_INCREMENT,
  `schedule_id` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `tech_id` varchar(100) NOT NULL DEFAULT '0',
  `netpay_key` int DEFAULT NULL,
  `date` date NOT NULL DEFAULT (0),
  `time` time NOT NULL DEFAULT (0),
  `job_type` enum('Instalasi','Maintenance','Dismantle') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `status` enum('Pending','Actived','Rescheduled','Cancelled','Done') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'Pending',
  `queue_key` int DEFAULT NULL,
  `catatan` text,
  PRIMARY KEY (`schedule_key`) USING BTREE,
  UNIQUE KEY `UNIQUE KEY` (`schedule_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table cms_database.schedules: ~0 rows (approximately)
INSERT INTO `schedules` (`schedule_key`, `schedule_id`, `tech_id`, `netpay_key`, `date`, `time`, `job_type`, `status`, `queue_key`, `catatan`) VALUES
	(9, 'S20251020114521', 'T202510011009', 13, '2025-10-20', '14:00:00', 'Instalasi', 'Done', 27, 'mn'),
	(10, 'S20251020114937', 'T202510011009', 5, '2025-10-20', '13:00:00', 'Maintenance', 'Done', 25, 'b'),
	(11, 'S20251020140509', 'T202510011009', 5, '2025-10-20', '15:00:00', 'Dismantle', 'Pending', 28, 'n'),
	(12, 'S20251021020609', 'T202510011009', 15, '2025-10-21', '14:00:00', 'Instalasi', 'Done', 30, 'Jangan Lupa ucap salamm'),
	(13, 'S20251021022524', 'T202510011009', 15, '2025-10-21', '08:00:00', 'Maintenance', 'Done', 33, 'Sering Kendala jaringannn'),
	(14, 'S20251021023925', 'T202510011009', 15, '2025-10-21', '11:00:00', 'Dismantle', 'Done', 35, 'Ingin Ganti Provider');

-- Dumping structure for table cms_database.service_reports
CREATE TABLE IF NOT EXISTS `service_reports` (
  `srv_key` int NOT NULL AUTO_INCREMENT,
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
  `schedule_key` int DEFAULT NULL,
  PRIMARY KEY (`srv_key`) USING BTREE,
  UNIQUE KEY `UNIQUE KEY` (`srv_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table cms_database.service_reports: ~0 rows (approximately)
INSERT INTO `service_reports` (`srv_key`, `srv_id`, `tanggal`, `jam`, `netpay_key`, `problem`, `action`, `part`, `red_bef`, `red_aft`, `pic`, `keterangan`, `created_at`, `updated_at`, `schedule_key`) VALUES
	(3, 'SR20251020140652', '2025-10-20', '14:06', 5, 'nm', 'n', 'n', 'j', 'n', 'T202510011009', 'j', '2025-10-20 07:07:13', '2025-10-20 07:16:33', 10),
	(5, 'SR20251021024014', '2025-10-21', '02:40', 15, 'k', 'k', 'k', 'k', 'k', 'T202510011009', 'k', '2025-10-20 19:40:24', '2025-10-20 19:40:24', 13);

-- Dumping structure for table cms_database.technician
CREATE TABLE IF NOT EXISTS `technician` (
  `tech_key` int NOT NULL AUTO_INCREMENT,
  `tech_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `name` varchar(200) NOT NULL,
  `phone` varchar(200) NOT NULL,
  `username` varchar(200) NOT NULL,
  PRIMARY KEY (`tech_key`) USING BTREE,
  UNIQUE KEY `UNIQUE KEY` (`tech_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table cms_database.technician: ~2 rows (approximately)
INSERT INTO `technician` (`tech_key`, `tech_id`, `name`, `phone`, `username`) VALUES
	(1, 'T202510011009', 'desta', '081381653387', 'desta28'),
	(2, 'T202510011038', 'Ifan Nardiansah', '081381653372', 'ifannard60'),
	(3, 'T202510210252', 'MUHAMMAD NAUFAL SAPUTRA', '08138172718', 'muhammad96');

-- Dumping structure for table cms_database.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `role` enum('admin','teknisi') NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `UNIQUE KEY` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table cms_database.users: ~3 rows (approximately)
INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
	(1, 'admin', '$2y$10$1aVdAIzK.P5cgStKJT6OF.dpIOV4ux.0b4P6r9rGhFFg0zaltytfy', 'admin'),
	(2, 'desta28', '$2y$10$vAp0nDtNc7pFbRuhC8KmV.C2dA1SgNWuXoGtVCDL/JoRQA.FIeXeu', 'teknisi'),
	(3, 'ifannard60', '$2y$10$WF8Tl2XZvBLQbetYebLdiOT7VubggnT0L9PNfveZuDbVDDW4zwm.i', 'teknisi'),
	(4, 'muhammad96', '$2y$10$DKJKtTsbaz7m.7YpPRQLOeZ05Dhyjs1e261AeQeTmVt6rNiBdfSu.', 'teknisi');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
