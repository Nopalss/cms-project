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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

-- Dumping structure for table cms_database.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `role` enum('admin','teknisi') NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `UNIQUE KEY` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Data exporting was unselected.

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
