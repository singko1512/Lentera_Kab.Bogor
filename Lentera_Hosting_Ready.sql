-- MySQL dump 10.13  Distrib 8.4.3, for Win64 (x86_64)
--
-- Host: 127.0.1.1    Database: lentera_kab_bogor
-- ------------------------------------------------------
-- Server version	8.4.3

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `absensis`
--

DROP TABLE IF EXISTS `absensis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `absensis` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `magang_application_id` bigint unsigned NOT NULL,
  `tanggal` date NOT NULL,
  `waktu_masuk` time DEFAULT NULL,
  `foto_masuk` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lokasi_masuk` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `waktu_pulang` time DEFAULT NULL,
  `foto_pulang` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lokasi_pulang` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('hadir','sakit','izin','absen') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'absen',
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `absensis_magang_application_id_foreign` (`magang_application_id`),
  CONSTRAINT `absensis_magang_application_id_foreign` FOREIGN KEY (`magang_application_id`) REFERENCES `magang_applications` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bidang`
--

DROP TABLE IF EXISTS `bidang`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bidang` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `dinas_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bidang_dinas_id_foreign` (`dinas_id`),
  CONSTRAINT `bidang_dinas_id_foreign` FOREIGN KEY (`dinas_id`) REFERENCES `dinas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=213 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dinas`
--

DROP TABLE IF EXISTS `dinas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dinas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_kesbangpol` tinyint(1) NOT NULL DEFAULT '0',
  `status_magang` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'otomatis',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `alamat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telepon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `jenis_layanans`
--

DROP TABLE IF EXISTS `jenis_layanans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jenis_layanans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_magang` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `jenis_layanans_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `jurnals`
--

DROP TABLE IF EXISTS `jurnals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jurnals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `magang_application_id` bigint unsigned NOT NULL,
  `tanggal` date NOT NULL,
  `kegiatan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_lampiran` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_verifikasi` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `jurnals_magang_application_id_foreign` (`magang_application_id`),
  CONSTRAINT `jurnals_magang_application_id_foreign` FOREIGN KEY (`magang_application_id`) REFERENCES `magang_applications` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `magang_applications`
--

DROP TABLE IF EXISTS `magang_applications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `magang_applications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `rekrutmen_id` bigint unsigned NOT NULL,
  `dinas_id` bigint unsigned DEFAULT NULL,
  `bidang_id` bigint unsigned DEFAULT NULL,
  `permohonan_layanan_id` bigint unsigned NOT NULL,
  `status` enum('menunggu','diterima','ditolak') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu',
  `file_surat_penerimaan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `catatan_admin` text COLLATE utf8mb4_unicode_ci,
  `pesan_lamaran` text COLLATE utf8mb4_unicode_ci,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `jadwal_wfh_wfo` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `magang_applications_user_id_foreign` (`user_id`),
  KEY `magang_applications_rekrutmen_id_foreign` (`rekrutmen_id`),
  KEY `magang_applications_permohonan_layanan_id_foreign` (`permohonan_layanan_id`),
  KEY `magang_applications_dinas_id_foreign` (`dinas_id`),
  KEY `magang_applications_bidang_id_foreign` (`bidang_id`),
  CONSTRAINT `magang_applications_bidang_id_foreign` FOREIGN KEY (`bidang_id`) REFERENCES `bidang` (`id`) ON DELETE SET NULL,
  CONSTRAINT `magang_applications_dinas_id_foreign` FOREIGN KEY (`dinas_id`) REFERENCES `dinas` (`id`) ON DELETE SET NULL,
  CONSTRAINT `magang_applications_permohonan_layanan_id_foreign` FOREIGN KEY (`permohonan_layanan_id`) REFERENCES `permohonan_layanans` (`id`) ON DELETE CASCADE,
  CONSTRAINT `magang_applications_rekrutmen_id_foreign` FOREIGN KEY (`rekrutmen_id`) REFERENCES `rekrutmens` (`id`) ON DELETE CASCADE,
  CONSTRAINT `magang_applications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `md_absensi`
--

DROP TABLE IF EXISTS `md_absensi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `md_absensi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `task_id` bigint unsigned DEFAULT NULL,
  `tanggal` date NOT NULL,
  `jam_masuk` time DEFAULT NULL,
  `jam_pulang` time DEFAULT NULL,
  `status_id` bigint unsigned DEFAULT NULL,
  `status_masuk_id` bigint unsigned DEFAULT NULL,
  `status_pulang_id` bigint unsigned DEFAULT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto_kamera` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto_masuk` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto_pulang` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lokasi_latitude` decimal(10,7) DEFAULT NULL,
  `lokasi_longitude` decimal(10,7) DEFAULT NULL,
  `lokasi_akurasi` decimal(12,2) DEFAULT NULL,
  `lokasi_diambil_pada` timestamp NULL DEFAULT NULL,
  `lokasi_masuk_latitude` decimal(10,7) DEFAULT NULL,
  `lokasi_masuk_longitude` decimal(10,7) DEFAULT NULL,
  `lokasi_masuk_akurasi` decimal(12,2) DEFAULT NULL,
  `lokasi_masuk_diambil_pada` timestamp NULL DEFAULT NULL,
  `lokasi_pulang_latitude` decimal(10,7) DEFAULT NULL,
  `lokasi_pulang_longitude` decimal(10,7) DEFAULT NULL,
  `lokasi_pulang_akurasi` decimal(12,2) DEFAULT NULL,
  `lokasi_pulang_diambil_pada` timestamp NULL DEFAULT NULL,
  `laporan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `md_absensi_user_id_foreign` (`user_id`),
  KEY `md_absensi_status_id_foreign` (`status_id`),
  KEY `md_absensi_status_masuk_id_foreign` (`status_masuk_id`),
  KEY `md_absensi_status_pulang_id_foreign` (`status_pulang_id`),
  KEY `md_absensi_task_id_foreign` (`task_id`),
  CONSTRAINT `md_absensi_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `md_master_data` (`id`) ON DELETE SET NULL,
  CONSTRAINT `md_absensi_status_masuk_id_foreign` FOREIGN KEY (`status_masuk_id`) REFERENCES `md_master_data` (`id`) ON DELETE SET NULL,
  CONSTRAINT `md_absensi_status_pulang_id_foreign` FOREIGN KEY (`status_pulang_id`) REFERENCES `md_master_data` (`id`) ON DELETE SET NULL,
  CONSTRAINT `md_absensi_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `md_project_tasks` (`id`) ON DELETE SET NULL,
  CONSTRAINT `md_absensi_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `md_activity_logs`
--

DROP TABLE IF EXISTS `md_activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `md_activity_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `project_id` bigint unsigned DEFAULT NULL,
  `aktivitas` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `md_activity_logs_user_id_foreign` (`user_id`),
  KEY `md_activity_logs_project_id_foreign` (`project_id`),
  CONSTRAINT `md_activity_logs_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `md_projects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `md_activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `md_jadwal_mingguan`
--

DROP TABLE IF EXISTS `md_jadwal_mingguan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `md_jadwal_mingguan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `senin_status_id` bigint unsigned DEFAULT NULL,
  `selasa_status_id` bigint unsigned DEFAULT NULL,
  `rabu_status_id` bigint unsigned DEFAULT NULL,
  `kamis_status_id` bigint unsigned DEFAULT NULL,
  `jumat_status_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `md_jadwal_mingguan_user_id_unique` (`user_id`),
  KEY `md_jadwal_mingguan_senin_status_id_foreign` (`senin_status_id`),
  KEY `md_jadwal_mingguan_selasa_status_id_foreign` (`selasa_status_id`),
  KEY `md_jadwal_mingguan_rabu_status_id_foreign` (`rabu_status_id`),
  KEY `md_jadwal_mingguan_kamis_status_id_foreign` (`kamis_status_id`),
  KEY `md_jadwal_mingguan_jumat_status_id_foreign` (`jumat_status_id`),
  CONSTRAINT `md_jadwal_mingguan_jumat_status_id_foreign` FOREIGN KEY (`jumat_status_id`) REFERENCES `md_master_data` (`id`) ON DELETE SET NULL,
  CONSTRAINT `md_jadwal_mingguan_kamis_status_id_foreign` FOREIGN KEY (`kamis_status_id`) REFERENCES `md_master_data` (`id`) ON DELETE SET NULL,
  CONSTRAINT `md_jadwal_mingguan_rabu_status_id_foreign` FOREIGN KEY (`rabu_status_id`) REFERENCES `md_master_data` (`id`) ON DELETE SET NULL,
  CONSTRAINT `md_jadwal_mingguan_selasa_status_id_foreign` FOREIGN KEY (`selasa_status_id`) REFERENCES `md_master_data` (`id`) ON DELETE SET NULL,
  CONSTRAINT `md_jadwal_mingguan_senin_status_id_foreign` FOREIGN KEY (`senin_status_id`) REFERENCES `md_master_data` (`id`) ON DELETE SET NULL,
  CONSTRAINT `md_jadwal_mingguan_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `md_master_data`
--

DROP TABLE IF EXISTS `md_master_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `md_master_data` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `jenis` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `warna` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `urutan` smallint unsigned NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `md_master_data_jenis_kode_unique` (`jenis`,`kode`),
  KEY `md_master_data_jenis_is_active_urutan_index` (`jenis`,`is_active`,`urutan`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `md_pembimbing_magang`
--

DROP TABLE IF EXISTS `md_pembimbing_magang`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `md_pembimbing_magang` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `bidang_id` bigint unsigned DEFAULT NULL,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `md_pembimbing_magang_nama_unique` (`nama`),
  KEY `md_pembimbing_magang_bidang_id_foreign` (`bidang_id`),
  CONSTRAINT `md_pembimbing_magang_bidang_id_foreign` FOREIGN KEY (`bidang_id`) REFERENCES `bidang` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `md_pengaturan`
--

DROP TABLE IF EXISTS `md_pengaturan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `md_pengaturan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kunci` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nilai` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `md_pengaturan_kunci_unique` (`kunci`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `md_project_day_assignments`
--

DROP TABLE IF EXISTS `md_project_day_assignments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `md_project_day_assignments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `tanggal` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `project_day_user_unique` (`project_id`,`user_id`,`tanggal`),
  KEY `md_project_day_assignments_user_id_foreign` (`user_id`),
  KEY `md_project_day_assignments_project_id_tanggal_index` (`project_id`,`tanggal`),
  CONSTRAINT `md_project_day_assignments_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `md_projects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `md_project_day_assignments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `md_project_modules`
--

DROP TABLE IF EXISTS `md_project_modules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `md_project_modules` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint unsigned NOT NULL,
  `timeline_id` bigint unsigned DEFAULT NULL,
  `nama` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `progress` decimal(5,2) NOT NULL DEFAULT '0.00',
  `status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum_dimulai',
  `bobot` decimal(5,2) NOT NULL DEFAULT '0.00',
  `urutan` int unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `md_project_modules_project_id_urutan_index` (`project_id`,`urutan`),
  KEY `md_project_modules_timeline_id_urutan_index` (`timeline_id`,`urutan`),
  CONSTRAINT `md_project_modules_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `md_projects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `md_project_modules_timeline_id_foreign` FOREIGN KEY (`timeline_id`) REFERENCES `md_project_timelines` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `md_project_note_replies`
--

DROP TABLE IF EXISTS `md_project_note_replies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `md_project_note_replies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `submission_id` bigint unsigned DEFAULT NULL,
  `task_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `tipe` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'comment',
  `isi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `lampiran` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `md_project_note_replies_submission_id_foreign` (`submission_id`),
  KEY `md_project_note_replies_user_id_foreign` (`user_id`),
  KEY `md_project_note_replies_task_id_created_at_index` (`task_id`,`created_at`),
  CONSTRAINT `md_project_note_replies_submission_id_foreign` FOREIGN KEY (`submission_id`) REFERENCES `md_work_submissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `md_project_note_replies_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `md_project_tasks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `md_project_note_replies_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `md_project_notes`
--

DROP TABLE IF EXISTS `md_project_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `md_project_notes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `tanggal` date NOT NULL,
  `kategori_id` bigint unsigned DEFAULT NULL,
  `judul` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `selesai_pada` timestamp NULL DEFAULT NULL,
  `user_selesai_pada` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `md_project_notes_project_id_tanggal_index` (`project_id`,`tanggal`),
  KEY `md_project_notes_kategori_id_selesai_pada_index` (`kategori_id`,`selesai_pada`),
  KEY `md_project_notes_user_id_foreign` (`user_id`),
  KEY `md_project_notes_project_id_user_id_tanggal_index` (`project_id`,`user_id`,`tanggal`),
  CONSTRAINT `md_project_notes_kategori_id_foreign` FOREIGN KEY (`kategori_id`) REFERENCES `md_master_data` (`id`) ON DELETE SET NULL,
  CONSTRAINT `md_project_notes_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `md_projects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `md_project_notes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `md_project_task_participants`
--

DROP TABLE IF EXISTS `md_project_task_participants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `md_project_task_participants` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `task_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `joined_at` timestamp NULL DEFAULT NULL,
  `contribution_percentage` decimal(5,2) NOT NULL DEFAULT '0.00',
  `status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'joined',
  `submitted_at` timestamp NULL DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `approved_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `md_project_task_participants_task_id_user_id_unique` (`task_id`,`user_id`),
  KEY `md_project_task_participants_approved_by_foreign` (`approved_by`),
  KEY `md_project_task_participants_user_id_status_index` (`user_id`,`status`),
  CONSTRAINT `md_project_task_participants_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `md_project_task_participants_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `md_project_tasks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `md_project_task_participants_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `md_project_tasks`
--

DROP TABLE IF EXISTS `md_project_tasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `md_project_tasks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint unsigned NOT NULL,
  `module_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `judul` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `join_window_minutes` int unsigned NOT NULL DEFAULT '5',
  `join_dibuka_pada` timestamp NULL DEFAULT NULL,
  `join_ditutup_pada` timestamp NULL DEFAULT NULL,
  `status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum_dikerjakan',
  `catatan_revisi` text COLLATE utf8mb4_unicode_ci,
  `file_lampiran` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `laporan_kerja` text COLLATE utf8mb4_unicode_ci,
  `tanggal_selesai_kerja` timestamp NULL DEFAULT NULL,
  `urutan` int unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `md_project_tasks_module_id_foreign` (`module_id`),
  KEY `md_project_tasks_project_id_module_id_index` (`project_id`,`module_id`),
  KEY `md_project_tasks_status_join_ditutup_pada_index` (`status`,`join_ditutup_pada`),
  KEY `md_project_tasks_user_id_foreign` (`user_id`),
  CONSTRAINT `md_project_tasks_module_id_foreign` FOREIGN KEY (`module_id`) REFERENCES `md_project_modules` (`id`) ON DELETE SET NULL,
  CONSTRAINT `md_project_tasks_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `md_projects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `md_project_tasks_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `md_project_timelines`
--

DROP TABLE IF EXISTS `md_project_timelines`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `md_project_timelines` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint unsigned NOT NULL,
  `nama` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum_dimulai',
  `urutan` int unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `md_project_timelines_project_id_urutan_index` (`project_id`,`urutan`),
  KEY `md_project_timelines_status_tanggal_mulai_tanggal_selesai_index` (`status`,`tanggal_mulai`,`tanggal_selesai`),
  CONSTRAINT `md_project_timelines_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `md_projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `md_project_user`
--

DROP TABLE IF EXISTS `md_project_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `md_project_user` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `md_project_user_project_id_user_id_unique` (`project_id`,`user_id`),
  KEY `md_project_user_user_id_foreign` (`user_id`),
  CONSTRAINT `md_project_user_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `md_projects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `md_project_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `md_projects`
--

DROP TABLE IF EXISTS `md_projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `md_projects` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `nama` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kebutuhan` text COLLATE utf8mb4_unicode_ci,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `status_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `md_projects_user_id_foreign` (`user_id`),
  KEY `md_projects_status_id_foreign` (`status_id`),
  CONSTRAINT `md_projects_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `md_master_data` (`id`) ON DELETE SET NULL,
  CONSTRAINT `md_projects_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `md_work_submissions`
--

DROP TABLE IF EXISTS `md_work_submissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `md_work_submissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `task_participant_id` bigint unsigned NOT NULL,
  `task_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `tanggal` date NOT NULL,
  `isi_laporan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `lampiran` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'submitted',
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `reviewed_by` bigint unsigned DEFAULT NULL,
  `review_note` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `md_work_submissions_task_participant_id_foreign` (`task_participant_id`),
  KEY `md_work_submissions_user_id_foreign` (`user_id`),
  KEY `md_work_submissions_reviewed_by_foreign` (`reviewed_by`),
  KEY `md_work_submissions_task_id_user_id_status_index` (`task_id`,`user_id`,`status`),
  CONSTRAINT `md_work_submissions_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `md_work_submissions_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `md_project_tasks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `md_work_submissions_task_participant_id_foreign` FOREIGN KEY (`task_participant_id`) REFERENCES `md_project_task_participants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `md_work_submissions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `module_members`
--

DROP TABLE IF EXISTS `module_members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `module_members` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `module_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `md_project_module_members_module_id_user_id_unique` (`module_id`,`user_id`),
  KEY `md_project_module_members_user_id_module_id_index` (`user_id`,`module_id`),
  CONSTRAINT `md_project_module_members_module_id_foreign` FOREIGN KEY (`module_id`) REFERENCES `md_project_modules` (`id`) ON DELETE CASCADE,
  CONSTRAINT `md_project_module_members_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pesan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dibaca` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_user_id_foreign` (`user_id`),
  CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `permohonan_layanans`
--

DROP TABLE IF EXISTS `permohonan_layanans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permohonan_layanans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `jenis_layanan_id` bigint unsigned NOT NULL,
  `status_master_id` bigint unsigned NOT NULL,
  `jenis_permohonan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `atas_nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_hp` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `asal_instansi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `judul_kegiatan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tempat_kegiatan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `status_revisi` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `catatan_pemohon` text COLLATE utf8mb4_unicode_ci,
  `dokumen_direvisi` text COLLATE utf8mb4_unicode_ci,
  `tanggal_revisi` timestamp NULL DEFAULT NULL,
  `file_ktp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_ktm` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_surat_permohonan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_surat_pengantar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_surat_lokasi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_proposal` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_surat_kesbangpol_jabar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_surat_kemendagri` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_surat_rekomendasi_lama` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_pendukung` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_surat_keluaran` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `file_daftar_peserta` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_id_card` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_kartu_pelajar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `permohonan_layanans_user_id_foreign` (`user_id`),
  KEY `permohonan_layanans_jenis_layanan_id_foreign` (`jenis_layanan_id`),
  KEY `permohonan_layanans_status_master_id_foreign` (`status_master_id`),
  CONSTRAINT `permohonan_layanans_jenis_layanan_id_foreign` FOREIGN KEY (`jenis_layanan_id`) REFERENCES `jenis_layanans` (`id`) ON DELETE CASCADE,
  CONSTRAINT `permohonan_layanans_status_master_id_foreign` FOREIGN KEY (`status_master_id`) REFERENCES `status_masters` (`id`) ON DELETE CASCADE,
  CONSTRAINT `permohonan_layanans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rekrutmens`
--

DROP TABLE IF EXISTS `rekrutmens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rekrutmens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `dinas_id` bigint unsigned NOT NULL,
  `bidang_id` bigint unsigned DEFAULT NULL,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi_persyaratan` text COLLATE utf8mb4_unicode_ci,
  `kuota` int NOT NULL DEFAULT '0',
  `tanggal_berakhir` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rekrutmens_dinas_id_foreign` (`dinas_id`),
  KEY `rekrutmens_bidang_id_foreign` (`bidang_id`),
  CONSTRAINT `rekrutmens_bidang_id_foreign` FOREIGN KEY (`bidang_id`) REFERENCES `bidang` (`id`) ON DELETE SET NULL,
  CONSTRAINT `rekrutmens_dinas_id_foreign` FOREIGN KEY (`dinas_id`) REFERENCES `dinas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `status_masters`
--

DROP TABLE IF EXISTS `status_masters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `status_masters` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kode` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `warna` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `status_masters_kode_unique` (`kode`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pembimbing_magang` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pembimbing_magang_id` bigint unsigned DEFAULT NULL,
  `bidang_magang` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_mulai_magang` date DEFAULT NULL,
  `tanggal_selesai_magang` date DEFAULT NULL,
  `nik` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_hp` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tempat_lahir` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `asal_instansi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `program_studi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nim` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `activation_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `grup` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'A',
  `status_akun` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `sertifikat_file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sertifikat_file_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sertifikat_file_mime` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sertifikat_diunggah_pada` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `dinas_id` bigint unsigned DEFAULT NULL,
  `bidang_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_username_unique` (`username`),
  KEY `users_dinas_id_foreign` (`dinas_id`),
  KEY `users_bidang_id_foreign` (`bidang_id`),
  KEY `users_pembimbing_magang_id_foreign` (`pembimbing_magang_id`),
  CONSTRAINT `users_bidang_id_foreign` FOREIGN KEY (`bidang_id`) REFERENCES `bidang` (`id`) ON DELETE SET NULL,
  CONSTRAINT `users_dinas_id_foreign` FOREIGN KEY (`dinas_id`) REFERENCES `dinas` (`id`) ON DELETE SET NULL,
  CONSTRAINT `users_pembimbing_magang_id_foreign` FOREIGN KEY (`pembimbing_magang_id`) REFERENCES `md_pembimbing_magang` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=290 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-09-14  1:10:06
-- MySQL dump 10.13  Distrib 8.4.3, for Win64 (x86_64)
--
-- Host: 127.0.1.1    Database: lentera_kab_bogor
-- ------------------------------------------------------
-- Server version	8.4.3

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_08_30_103956_create_dinas_table',1),(5,'2026_08_30_104017_add_role_and_dinas_id_to_users_table',1),(6,'2026_08_30_105140_create_bidang_table',1),(7,'2026_08_30_112308_create_status_masters_table',1),(8,'2026_08_30_112309_create_jenis_layanans_table',1),(9,'2026_08_30_112310_create_permohonan_layanans_table',1),(10,'2026_08_30_112957_add_bidang_id_to_users_table',1),(11,'2026_08_30_113401_add_file_surat_keluaran_to_permohonan_layanans_table',1),(12,'2026_08_30_113946_create_rekrutmens_table',1),(13,'2026_08_30_113947_create_magang_applications_table',1),(14,'2026_08_30_115428_create_absensis_table',1),(15,'2026_08_30_115429_create_jurnals_table',1),(16,'2026_08_30_122509_add_details_to_dinas_table',1),(17,'2026_08_30_165859_add_biodata_to_users_table',1),(18,'2026_08_30_175541_add_peserta_and_id_cards_to_permohonan_layanans_table',1),(19,'2026_08_30_182147_add_foto_and_lokasi_to_absensis_table',1),(20,'2026_09_02_064328_add_activation_token_to_users_table',1),(21,'2026_09_06_132003_add_jadwal_wfh_wfo_to_magang_applications_table',1),(22,'2026_09_22_000001_create_md_master_data_table',1),(23,'2026_09_22_000002_create_md_absensi_table',1),(24,'2026_09_22_000003_create_md_pengaturan_table',1),(25,'2026_09_22_000004_rename_nip_to_email_on_md_user_table',1),(26,'2026_09_22_000005_create_md_jadwal_mingguan_table',1),(27,'2026_09_23_000006_add_magang_dates_to_md_user_table',1),(28,'2026_09_23_000007_update_admin_pin_to_180909',1),(29,'2026_09_23_000008_add_foto_kamera_to_md_absensi_table',1),(30,'2026_09_23_000009_create_md_jadwal_mingguan_table',1),(31,'2026_09_23_000010_add_wfh_location_to_md_absensi_table',1),(32,'2026_09_23_000011_create_md_projects_table',1),(33,'2026_09_23_000012_create_md_project_notes_table',1),(34,'2026_09_23_000013_create_md_project_user_table',1),(35,'2026_09_23_000014_create_md_project_day_assignments_table',1),(36,'2026_09_23_000015_add_user_id_to_md_project_notes_table',1),(37,'2026_09_23_000016_add_magang_profile_to_md_user_table',1),(38,'2026_09_23_000017_convert_enum_columns_to_master_data',1),(39,'2026_09_27_104015_add_user_selesai_pada_to_md_project_notes_table',1),(40,'2026_09_27_120000_add_role_access_to_absensi_system',1),(41,'2026_09_27_121000_add_uploaded_certificate_to_md_user_table',1),(42,'2026_09_27_122000_add_admin_username_password_credentials',1),(43,'2026_09_27_130000_add_account_attendance_and_task_timeline_tables',1),(44,'2026_09_28_090000_create_md_pembimbing_magang_and_user_relations',1),(45,'2026_09_28_100000_add_bidang_id_to_md_pembimbing_magang_table',1),(46,'2026_09_28_110000_add_checkin_checkout_location_to_md_absensi_table',1),(47,'2026_09_28_120000_expand_location_accuracy_columns_on_md_absensi_table',1),(48,'2026_09_28_130000_add_project_timelines_and_module_members_tables',1),(49,'2026_09_28_131000_rename_project_module_members_table',1),(50,'2026_09_29_000001_add_dates_to_md_project_modules_table',1),(51,'2026_09_29_093352_update_tables_for_task_overhaul',1),(52,'2026_09_29_100000_add_grup_to_md_user_table',1),(53,'2026_09_29_120000_create_notifications_table',1),(54,'2026_09_29_130000_add_dinas_fields_to_magang_applications_table',1),(55,'2026_09_29_140000_add_status_magang_to_dinas_table',1),(56,'2026_09_10_231000_add_revision_tracking_to_permohonan_layanans_table',2);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `dinas`
--

LOCK TABLES `dinas` WRITE;
/*!40000 ALTER TABLE `dinas` DISABLE KEYS */;
INSERT INTO `dinas` VALUES (1,'BADAN KEPEGAWAIAN DAN PENGEMBANGAN SUMBER DAYA MANUSIA',0,'otomatis','2026-09-09 06:05:32','2026-09-09 06:05:32',NULL,NULL,NULL,NULL,NULL),(3,'BADAN PENANGGGULANGAN BENCANA DAERAH',0,'otomatis','2026-09-09 06:05:32','2026-09-09 06:05:32',NULL,NULL,NULL,NULL,NULL),(4,'BADAN PENGELOLAAN KEUANGAN DAN ASET DAERAH',0,'otomatis','2026-09-09 06:05:32','2026-09-09 06:05:32',NULL,NULL,NULL,NULL,NULL),(5,'BADAN PENGELOLAAN PENDAPATAN DAERAH',0,'otomatis','2026-09-09 06:05:32','2026-09-09 06:05:32',NULL,NULL,NULL,NULL,NULL),(6,'BADAN PERENCANAAN PEMBANGUNAN, RISET DAN INOVASI DAERAH',0,'otomatis','2026-09-09 06:05:33','2026-09-09 06:05:33',NULL,NULL,NULL,NULL,NULL),(7,'DINAS ARSIP DAN PERPUSTAKAAN',0,'otomatis','2026-09-09 06:05:33','2026-09-09 06:05:33',NULL,NULL,NULL,NULL,NULL),(8,'DINAS KEPENDUDUKAN DAN PENCATATAN SIPIL',0,'otomatis','2026-09-09 06:05:33','2026-09-09 06:05:33',NULL,NULL,NULL,NULL,NULL),(9,'DINAS KESEHATAN',0,'otomatis','2026-09-09 06:05:33','2026-09-09 06:05:33',NULL,NULL,NULL,NULL,NULL),(10,'DINAS KETAHANAN PANGAN',0,'otomatis','2026-09-09 06:05:33','2026-09-09 06:05:33',NULL,NULL,NULL,NULL,NULL),(11,'DINAS KOMUNIKASI DAN INFORMATIKA',0,'otomatis','2026-09-09 06:05:33','2026-09-09 06:05:33',NULL,NULL,NULL,NULL,NULL),(12,'DINAS KOPERASI, USAHA KECIL DAN MENENGAH',0,'otomatis','2026-09-09 06:05:33','2026-09-09 06:05:33',NULL,NULL,NULL,NULL,NULL),(13,'DINAS LINGKUNGAN HIDUP',0,'otomatis','2026-09-09 06:05:33','2026-09-09 06:05:33',NULL,NULL,NULL,NULL,NULL),(14,'DINAS PARIWISATA DAN EKONOMI KREATIF',0,'otomatis','2026-09-09 06:05:33','2026-09-09 06:05:33',NULL,NULL,NULL,NULL,NULL),(15,'DINAS PEKERJAAN UMUM',0,'otomatis','2026-09-09 06:05:33','2026-09-09 06:05:33',NULL,NULL,NULL,NULL,NULL),(16,'DINAS PEMADAM KEBAKARAN DAN PENYELAMATAN',0,'otomatis','2026-09-09 06:05:33','2026-09-09 06:05:33',NULL,NULL,NULL,NULL,NULL),(17,'DINAS PEMBERDAYAAN MASYARAKAT DAN DESA',0,'otomatis','2026-09-09 06:05:33','2026-09-09 06:05:33',NULL,NULL,NULL,NULL,NULL),(18,'DINAS PEMBERDAYAAN PEREMPUAN DAN PERLINDUNGAN ANAK, PENGENDALIAN PENDUDUK DAN KB',0,'otomatis','2026-09-09 06:05:33','2026-09-09 06:05:33',NULL,NULL,NULL,NULL,NULL),(19,'DINAS PEMUDA DAN OLAH RAGA',0,'otomatis','2026-09-09 06:05:33','2026-09-09 06:05:33',NULL,NULL,NULL,NULL,NULL),(20,'DINAS PENANAMAN MODAL DAN PELAYANAN TERPADU SATU PINTU',0,'otomatis','2026-09-09 06:05:33','2026-09-09 06:05:33',NULL,NULL,NULL,NULL,NULL),(21,'DINAS PENDIDIKAN',0,'otomatis','2026-09-09 06:05:33','2026-09-09 06:05:33',NULL,NULL,NULL,NULL,NULL),(22,'DINAS PERDAGANGAN DAN PERINDUSTRIAN',0,'otomatis','2026-09-09 06:05:33','2026-09-09 06:05:33',NULL,NULL,NULL,NULL,NULL),(23,'DINAS PERHUBUNGAN',0,'otomatis','2026-09-09 06:05:33','2026-09-09 06:05:33',NULL,NULL,NULL,NULL,NULL),(24,'DINAS PERIKANAN DAN PETERNAKAN',0,'otomatis','2026-09-09 06:05:34','2026-09-09 06:05:34',NULL,NULL,NULL,NULL,NULL),(25,'DINAS PERTANAHAN DAN TATA RUANG',0,'otomatis','2026-09-09 06:05:34','2026-09-09 06:05:34',NULL,NULL,NULL,NULL,NULL),(26,'DINAS PERUMAHAN DAN KAWASAN PERMUKIMAN',0,'otomatis','2026-09-09 06:05:34','2026-09-09 06:05:34',NULL,NULL,NULL,NULL,NULL),(27,'DINAS SOSIAL',0,'otomatis','2026-09-09 06:05:34','2026-09-09 06:05:34',NULL,NULL,NULL,NULL,NULL),(28,'DINAS TANAMAN PANGAN, HORTIKULTURA DAN PERKEBUNAN',0,'otomatis','2026-09-09 06:05:34','2026-09-09 06:05:34',NULL,NULL,NULL,NULL,NULL),(29,'DINAS TENAGA KERJA',0,'otomatis','2026-09-09 06:05:34','2026-09-09 06:05:34',NULL,NULL,NULL,NULL,NULL),(30,'INSPEKTORAT',0,'otomatis','2026-09-09 06:05:34','2026-09-09 06:05:34',NULL,NULL,NULL,NULL,NULL),(31,'KECAMATAN BABAKAN MADANG',0,'otomatis','2026-09-09 06:05:34','2026-09-09 06:05:34',NULL,NULL,NULL,NULL,NULL),(32,'KECAMATAN BOJONGGEDE',0,'otomatis','2026-09-09 06:05:34','2026-09-09 06:05:34',NULL,NULL,NULL,NULL,NULL),(33,'KECAMATAN CARIU',0,'otomatis','2026-09-09 06:05:34','2026-09-09 06:05:34',NULL,NULL,NULL,NULL,NULL),(34,'KECAMATAN CIAMPEA',0,'otomatis','2026-09-09 06:05:34','2026-09-09 06:05:34',NULL,NULL,NULL,NULL,NULL),(35,'KECAMATAN CIAWI',0,'otomatis','2026-09-09 06:05:34','2026-09-09 06:05:34',NULL,NULL,NULL,NULL,NULL),(36,'KECAMATAN CIBINONG',0,'otomatis','2026-09-09 06:05:34','2026-09-09 06:05:34',NULL,NULL,NULL,NULL,NULL),(37,'KECAMATAN CIBUNGBULANG',0,'otomatis','2026-09-09 06:05:34','2026-09-09 06:05:34',NULL,NULL,NULL,NULL,NULL),(38,'KECAMATAN CIGOMBONG',0,'otomatis','2026-09-09 06:05:34','2026-09-09 06:05:34',NULL,NULL,NULL,NULL,NULL),(39,'KECAMATAN CIGUDEG',0,'otomatis','2026-09-09 06:05:34','2026-09-09 06:05:34',NULL,NULL,NULL,NULL,NULL),(40,'KECAMATAN CIJERUK',0,'otomatis','2026-09-09 06:05:34','2026-09-09 06:05:34',NULL,NULL,NULL,NULL,NULL),(41,'KECAMATAN CILEUNGSI',0,'otomatis','2026-09-09 06:05:34','2026-09-09 06:05:34',NULL,NULL,NULL,NULL,NULL),(42,'KECAMATAN CIOMAS',0,'otomatis','2026-09-09 06:05:34','2026-09-09 06:05:34',NULL,NULL,NULL,NULL,NULL),(43,'KECAMATAN CISARUA',0,'otomatis','2026-09-09 06:05:34','2026-09-09 06:05:34',NULL,NULL,NULL,NULL,NULL),(44,'KECAMATAN CISEENG',0,'otomatis','2026-09-09 06:05:34','2026-09-09 06:05:34',NULL,NULL,NULL,NULL,NULL),(45,'KECAMATAN CITEUREUP',0,'otomatis','2026-09-09 06:05:34','2026-09-09 06:22:36',NULL,NULL,NULL,NULL,NULL),(46,'KECAMATAN DRAMAGA',0,'otomatis','2026-09-09 06:05:34','2026-09-09 06:05:34',NULL,NULL,NULL,NULL,NULL),(47,'KECAMATAN GUNUNG PUTRI',0,'otomatis','2026-09-09 06:05:34','2026-09-09 06:05:34',NULL,NULL,NULL,NULL,NULL),(48,'KECAMATAN GUNUNG SINDUR',0,'otomatis','2026-09-09 06:05:34','2026-09-09 06:05:34',NULL,NULL,NULL,NULL,NULL),(49,'KECAMATAN JASINGA',0,'otomatis','2026-09-09 06:05:34','2026-09-09 06:05:34',NULL,NULL,NULL,NULL,NULL),(50,'KECAMATAN JONGGOL',0,'otomatis','2026-09-09 06:05:34','2026-09-09 06:05:34',NULL,NULL,NULL,NULL,NULL),(51,'KECAMATAN KEMANG',0,'otomatis','2026-09-09 06:05:34','2026-09-09 06:05:34',NULL,NULL,NULL,NULL,NULL),(52,'KECAMATAN KLAPANUNGGAL',0,'otomatis','2026-09-09 06:05:34','2026-09-09 06:05:34',NULL,NULL,NULL,NULL,NULL),(53,'KECAMATAN LEUWILIANG',0,'otomatis','2026-09-09 06:05:34','2026-09-09 06:05:34',NULL,NULL,NULL,NULL,NULL),(54,'KECAMATAN LEUWISADENG',0,'otomatis','2026-09-09 06:05:34','2026-09-09 06:05:34',NULL,NULL,NULL,NULL,NULL),(55,'KECAMATAN NANGGUNG',0,'otomatis','2026-09-09 06:05:34','2026-09-09 06:05:34',NULL,NULL,NULL,NULL,NULL),(56,'KECAMATAN PARUNG',0,'otomatis','2026-09-09 06:05:34','2026-09-09 06:05:34',NULL,NULL,NULL,NULL,NULL),(57,'KECAMATAN PARUNG PANJANG',0,'otomatis','2026-09-09 06:05:35','2026-09-09 06:05:35',NULL,NULL,NULL,NULL,NULL),(58,'KECAMATAN RANCABUNGUR',0,'otomatis','2026-09-09 06:05:35','2026-09-09 06:05:35',NULL,NULL,NULL,NULL,NULL),(59,'KECAMATAN RUMPIN',0,'otomatis','2026-09-09 06:05:35','2026-09-09 06:05:35',NULL,NULL,NULL,NULL,NULL),(60,'KECAMATAN SUKAJAYA',0,'otomatis','2026-09-09 06:05:35','2026-09-09 06:05:35',NULL,NULL,NULL,NULL,NULL),(61,'KECAMATAN SUKAMAKMUR',0,'otomatis','2026-09-09 06:05:35','2026-09-09 06:05:35',NULL,NULL,NULL,NULL,NULL),(62,'KECAMATAN SUKARAJA',0,'otomatis','2026-09-09 06:05:35','2026-09-09 06:05:35',NULL,NULL,NULL,NULL,NULL),(63,'KECAMATAN TAJURHALANG',0,'otomatis','2026-09-09 06:05:35','2026-09-09 06:05:35',NULL,NULL,NULL,NULL,NULL),(64,'KECAMATAN TAMANSARI',0,'otomatis','2026-09-09 06:05:35','2026-09-09 06:05:35',NULL,NULL,NULL,NULL,NULL),(65,'KECAMATAN TANJUNGSARI',0,'otomatis','2026-09-09 06:05:35','2026-09-09 06:05:35',NULL,NULL,NULL,NULL,NULL),(66,'KECAMATAN TENJO',0,'otomatis','2026-09-09 06:05:35','2026-09-09 06:05:35',NULL,NULL,NULL,NULL,NULL),(67,'KECAMATAN TENJOLAYA',0,'otomatis','2026-09-09 06:05:35','2026-09-09 06:05:35',NULL,NULL,NULL,NULL,NULL),(68,'RUMAH SAKIT UMUM DAERAH BAKTI PAJAJARAN',0,'otomatis','2026-09-09 06:05:35','2026-09-09 06:05:35',NULL,NULL,NULL,NULL,NULL),(69,'RUMAH SAKIT UMUM DAERAH DR. KH. IDHAM CHALID',0,'otomatis','2026-09-09 06:05:35','2026-09-09 06:05:35',NULL,NULL,NULL,NULL,NULL),(70,'RUMAH SAKIT UMUM DAERAH R. MOH. NOH NUR',0,'otomatis','2026-09-09 06:05:35','2026-09-09 06:05:35',NULL,NULL,NULL,NULL,NULL),(71,'RUMAH SAKIT UMUM DAERAH RH. SATIBI',0,'otomatis','2026-09-09 06:05:35','2026-09-09 06:05:35',NULL,NULL,NULL,NULL,NULL),(72,'SATUAN POLISI PAMONG PRAJA',0,'otomatis','2026-09-09 06:05:35','2026-09-09 06:05:35',NULL,NULL,NULL,NULL,NULL),(73,'SEKRETARIAT DAERAH',0,'otomatis','2026-09-09 06:05:35','2026-09-09 06:05:35',NULL,NULL,NULL,NULL,NULL),(74,'SEKRETARIAT DPRD',0,'otomatis','2026-09-09 06:05:35','2026-09-09 06:05:35',NULL,NULL,NULL,NULL,NULL),(76,'BADAN KESATUAN BANGSA DAN POLITIK',1,'otomatis',NULL,'2026-09-13 17:27:09',NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `dinas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `bidang`
--

LOCK TABLES `bidang` WRITE;
/*!40000 ALTER TABLE `bidang` DISABLE KEYS */;
INSERT INTO `bidang` VALUES (1,1,'BIDANG MUTASI DAN PROMOSI','2026-09-09 06:05:32','2026-09-09 06:05:32'),(2,1,'BIDANG PENGADAAN, PEMBERHENTIAN DAN INFORMASI','2026-09-09 06:05:32','2026-09-09 06:05:32'),(3,1,'BIDANG PENGEMBANGAN KOMPETENSI APARATUR','2026-09-09 06:05:32','2026-09-09 06:05:32'),(4,1,'BIDANG PENILAIAN KINERJA APARATUR DAN PENGHARGAAN','2026-09-09 06:05:32','2026-09-09 06:05:32'),(5,1,'SEKRETARIAT','2026-09-09 06:05:32','2026-09-09 06:05:32'),(11,3,'BIDANG KEDARURATAN DAN LOGISTIK','2026-09-09 06:05:32','2026-09-09 06:05:32'),(12,3,'BIDANG PENCEGAHAN DAN KESIAPSIAGAAN','2026-09-09 06:05:32','2026-09-09 06:05:32'),(13,3,'BIDANG REHABILITASI DAN REKONSTRUKSI','2026-09-09 06:05:32','2026-09-09 06:05:32'),(14,3,'SEKRETARIAT','2026-09-09 06:05:32','2026-09-09 06:05:32'),(15,4,'BIDANG AKUNTANSI DAN TEKNOLOGI INFORMASI','2026-09-09 06:05:32','2026-09-09 06:05:32'),(16,4,'BIDANG ANGGARAN','2026-09-09 06:05:32','2026-09-09 06:05:32'),(17,4,'BIDANG ASET DAERAH','2026-09-09 06:05:32','2026-09-09 06:05:32'),(18,4,'BIDANG PERBENDAHARAAN','2026-09-09 06:05:32','2026-09-09 06:05:32'),(19,4,'SEKRETARIAT','2026-09-09 06:05:32','2026-09-09 06:05:32'),(20,5,'BIDANG PELAYANAN DAN PENETAPAN','2026-09-09 06:05:32','2026-09-09 06:05:32'),(21,5,'BIDANG PENAGIHAN, KEBERATAN DAN PENGAWASAN PENDAPATAN DAERAH','2026-09-09 06:05:32','2026-09-09 06:05:32'),(22,5,'BIDANG PENDATAAN DAN PENILAIAN','2026-09-09 06:05:32','2026-09-09 06:05:32'),(23,5,'BIDANG PERENCANAAN DAN PENGEMBANGAN PENDAPATAN DAERAH','2026-09-09 06:05:32','2026-09-09 06:05:32'),(24,5,'SEKRETARIAT','2026-09-09 06:05:32','2026-09-09 06:05:32'),(25,6,'BIDANG INFRASTRUKTUR DAN PENGEMBANGAN WILAYAH','2026-09-09 06:05:33','2026-09-09 06:05:33'),(26,6,'BIDANG PEMERINTAHAN DAN PENGEMBANGAN MANUSIA','2026-09-09 06:05:33','2026-09-09 06:05:33'),(27,6,'BIDANG PEREKONOMIAN DAN SUMBER DAYA ALAM','2026-09-09 06:05:33','2026-09-09 06:05:33'),(28,6,'BIDANG PERENCANAAN, PENGENDALIAN DAN EVALUASI PEMBANGUNAN DAERAH','2026-09-09 06:05:33','2026-09-09 06:05:33'),(29,6,'BIDANG RISET DAN INOVASI DAERAH','2026-09-09 06:05:33','2026-09-09 06:05:33'),(30,6,'SEKRETARIAT','2026-09-09 06:05:33','2026-09-09 06:05:33'),(31,7,'BIDANG PEMBINAAN KEARSIPAN','2026-09-09 06:05:33','2026-09-09 06:05:33'),(32,7,'BIDANG PENGELOLAAN KEARSIPAN DAN SISTEM INFORMASI KEARSIPAN','2026-09-09 06:05:33','2026-09-09 06:05:33'),(33,7,'BIDANG PERPUSTAKAAN','2026-09-09 06:05:33','2026-09-09 06:05:33'),(34,7,'SEKRETARIAT','2026-09-09 06:05:33','2026-09-09 06:05:33'),(35,8,'BIDANG PELAYANAN PENCATATAN SIPIL','2026-09-09 06:05:33','2026-09-09 06:05:33'),(36,8,'BIDANG PELAYANAN PENDAFTARAN PENDUDUK','2026-09-09 06:05:33','2026-09-09 06:05:33'),(37,8,'BIDANG PEMANFAATAN DATA DAN INOVASI PELAYANAN','2026-09-09 06:05:33','2026-09-09 06:05:33'),(38,8,'BIDANG PENGELOLAAN INFORMASI ADMINISTRASI KEPENDUDUKAN','2026-09-09 06:05:33','2026-09-09 06:05:33'),(39,8,'SEKRETARIAT','2026-09-09 06:05:33','2026-09-09 06:05:33'),(40,9,'BIDANG PELAYANAN KESEHATAN','2026-09-09 06:05:33','2026-09-09 06:05:33'),(41,9,'BIDANG PENCEGAHAN DAN PENGENDALIAN PENYAKIT','2026-09-09 06:05:33','2026-09-09 06:05:33'),(42,9,'BIDANG SUMBER DAYA KESEHATAN','2026-09-09 06:05:33','2026-09-09 06:05:33'),(43,9,'SEKRETARIAT','2026-09-09 06:05:33','2026-09-09 06:05:33'),(44,10,'BIDANG DISTRIBUSI DAN CADANGAN PANGAN','2026-09-09 06:05:33','2026-09-09 06:05:33'),(45,10,'BIDANG KEAMANAN PANGAN','2026-09-09 06:05:33','2026-09-09 06:05:33'),(46,10,'BIDANG KETERSEDIAAN DAN KERAWANAN PANGAN','2026-09-09 06:05:33','2026-09-09 06:05:33'),(47,10,'BIDANG KONSUMSI DAN PENGANEKARAGAMAN PANGAN','2026-09-09 06:05:33','2026-09-09 06:05:33'),(48,10,'SEKRETARIAT','2026-09-09 06:05:33','2026-09-09 06:05:33'),(49,11,'BIDANG APLIKASI INFORMATIKA','2026-09-09 06:05:33','2026-09-09 06:05:33'),(50,11,'BIDANG INFRASTRUKTUR TEKNOLOGI','2026-09-09 06:05:33','2026-09-09 06:05:33'),(51,11,'BIDANG PERSANDIAN DAN STATISTIK','2026-09-09 06:05:33','2026-09-09 06:05:33'),(52,11,'SEKRETARIAT','2026-09-09 06:05:33','2026-09-09 06:05:33'),(53,12,'BIDANG KELEMBAGAAN DAN PEMBERDAYAAN KOPERASI','2026-09-09 06:05:33','2026-09-09 06:05:33'),(54,12,'BIDANG PEMBERDAYAAN USAHA MIKRO','2026-09-09 06:05:33','2026-09-09 06:05:33'),(55,12,'BIDANG PENGAWASAN DAN PEMERIKSAAN KOPERASI','2026-09-09 06:05:33','2026-09-09 06:05:33'),(56,12,'SEKRETARIAT','2026-09-09 06:05:33','2026-09-09 06:05:33'),(57,13,'BIDANG PENEGAKAN HUKUM DAN PENGELOLAAN LIMBAH BAHAN BERBAHAYA DAN BERACUN (LB3)','2026-09-09 06:05:33','2026-09-09 06:05:33'),(58,13,'BIDANG PENGELOLAAN SAMPAH','2026-09-09 06:05:33','2026-09-09 06:05:33'),(59,13,'BIDANG PENGENDALIAN PENCEMARAN DAN KEMITRAAN','2026-09-09 06:05:33','2026-09-09 06:05:33'),(60,13,'BIDANG TATA LINGKUNGAN','2026-09-09 06:05:33','2026-09-09 06:05:33'),(61,13,'SEKRETARIAT','2026-09-09 06:05:33','2026-09-09 06:05:33'),(62,14,'BIDANG DESTINASI PARIWISATA','2026-09-09 06:05:33','2026-09-09 06:05:33'),(63,14,'BIDANG PEMASARAN PARIWISATA DAN EKONOMI KREATIF','2026-09-09 06:05:33','2026-09-09 06:05:33'),(64,14,'BIDANG PENGEMBANGAN EKONOMI KREATIF','2026-09-09 06:05:33','2026-09-09 06:05:33'),(65,14,'BIDANG SUMBER DAYA MANUSIA DAN EKONOMI KREATIF','2026-09-09 06:05:33','2026-09-09 06:05:33'),(66,14,'SEKRETARIAT','2026-09-09 06:05:33','2026-09-09 06:05:33'),(67,15,'BIDANG IRIGASI DAN SUMBER DAYA AIR','2026-09-09 06:05:33','2026-09-09 06:05:33'),(68,15,'BIDANG JASA KONSTRUKSI','2026-09-09 06:05:33','2026-09-09 06:05:33'),(69,15,'BIDANG PEMBANGUNAN JALAN DAN JEMBATAN','2026-09-09 06:05:33','2026-09-09 06:05:33'),(70,15,'BIDANG PEMELIHARAAN JALAN DAN JEMBATAN','2026-09-09 06:05:33','2026-09-09 06:05:33'),(71,15,'SEKRETARIAT','2026-09-09 06:05:33','2026-09-09 06:05:33'),(72,16,'BIDANG PEMADAMAN','2026-09-09 06:05:33','2026-09-09 06:05:33'),(73,16,'BIDANG PENCEGAHAN DAN KESIAGAAN BAHAYA KEBAKARAN','2026-09-09 06:05:33','2026-09-09 06:05:33'),(74,16,'BIDANG SARANA PRASARANA DAN DATA','2026-09-09 06:05:33','2026-09-09 06:05:33'),(75,16,'SEKRETARIAT','2026-09-09 06:05:33','2026-09-09 06:05:33'),(76,17,'BIDANG KEUANGAN DAN KEKAYAAN DESA','2026-09-09 06:05:33','2026-09-09 06:05:33'),(77,17,'BIDANG PEMBERDAYAAN MASYARAKAT DESA','2026-09-09 06:05:33','2026-09-09 06:05:33'),(78,17,'BIDANG PEMERINTAHAN DESA','2026-09-09 06:05:33','2026-09-09 06:05:33'),(79,17,'BIDANG SARANA PRASARANA DAN KEWILAYAHAN','2026-09-09 06:05:33','2026-09-09 06:05:33'),(80,17,'SEKRETARIAT','2026-09-09 06:05:33','2026-09-09 06:05:33'),(81,18,'BIDANG PEMBINAAN KELUARGA SEJAHTERA','2026-09-09 06:05:33','2026-09-09 06:05:33'),(82,18,'BIDANG PEMENUHAN HAK DAN PERLINDUNGAN KHUSUS ANAK','2026-09-09 06:05:33','2026-09-09 06:05:33'),(83,18,'SEKRETARIAT','2026-09-09 06:05:33','2026-09-09 06:05:33'),(84,19,'BIDANG PEMBUDAYAAN OLAHRAGA','2026-09-09 06:05:33','2026-09-09 06:05:33'),(85,19,'BIDANG PENINGKATAN PRESTASI OLAHRAGA','2026-09-09 06:05:33','2026-09-09 06:05:33'),(86,19,'SEKRETARIAT','2026-09-09 06:05:33','2026-09-09 06:05:33'),(87,20,'BIDANG DATA','2026-09-09 06:05:33','2026-09-09 06:05:33'),(88,20,'BIDANG PELAYANAN PERIZINAN PEMANFAATAN RUANG','2026-09-09 06:05:33','2026-09-09 06:05:33'),(89,20,'BIDANG PENGEMBANGAN DAN PROMOSI','2026-09-09 06:05:33','2026-09-09 06:05:33'),(90,20,'SEKRETARIAT','2026-09-09 06:05:33','2026-09-09 06:05:33'),(91,21,'BIDANG PEMBINAAN PAUD DAN PENDIDIKAN MASYARAKAT','2026-09-09 06:05:33','2026-09-09 06:05:33'),(92,21,'BIDANG PEMBINAAN SEKOLAH DASAR','2026-09-09 06:05:33','2026-09-09 06:05:33'),(93,21,'BIDANG PEMBINAAN SEKOLAH MENENGAH PERTAMA','2026-09-09 06:05:33','2026-09-09 06:05:33'),(94,21,'BIDANG SARANA DAN PRASARANA','2026-09-09 06:05:33','2026-09-09 06:05:33'),(95,21,'SEKRETARIAT','2026-09-09 06:05:33','2026-09-09 06:05:33'),(96,22,'BIDANG PERDAGANGAN','2026-09-09 06:05:33','2026-09-09 06:05:33'),(97,22,'BIDANG TERTIB NIAGA','2026-09-09 06:05:33','2026-09-09 06:05:33'),(98,22,'SEKRETARIAT','2026-09-09 06:05:33','2026-09-09 06:05:33'),(99,23,'BIDANG ANGKUTAN','2026-09-09 06:05:33','2026-09-09 06:05:33'),(100,23,'BIDANG LALU LINTAS JALAN','2026-09-09 06:05:34','2026-09-09 06:05:34'),(101,23,'BIDANG PRASARANA DAN PERLENGKAPAN JALAN','2026-09-09 06:05:34','2026-09-09 06:05:34'),(102,23,'BIDANG SARANA TRANSPORTASI JALAN','2026-09-09 06:05:34','2026-09-09 06:05:34'),(103,23,'SEKRETARIAT','2026-09-09 06:05:34','2026-09-09 06:05:34'),(104,24,'BIDANG KESEHATAN HEWAN DAN KESMAVET','2026-09-09 06:05:34','2026-09-09 06:05:34'),(105,24,'BIDANG PENGUATAN DAYA SAING PRODUK PERIKANAN','2026-09-09 06:05:34','2026-09-09 06:05:34'),(106,24,'BIDANG PERIKANAN BUDIDAYA DAN PERIKANAN TANGKAP','2026-09-09 06:05:34','2026-09-09 06:05:34'),(107,24,'BIDANG PETERNAKAN','2026-09-09 06:05:34','2026-09-09 06:05:34'),(108,24,'SEKRETARIAT','2026-09-09 06:05:34','2026-09-09 06:05:34'),(109,25,'BIDANG PENATAAN BANGUNAN GEDUNG','2026-09-09 06:05:34','2026-09-09 06:05:34'),(110,25,'BIDANG PENGENDALIAN PEMANFAATAN RUANG DAN BANGUNAN','2026-09-09 06:05:34','2026-09-09 06:05:34'),(111,25,'BIDANG PERTANAHAN','2026-09-09 06:05:34','2026-09-09 06:05:34'),(112,26,'BIDANG KAWASAN PERMUKIMAN','2026-09-09 06:05:34','2026-09-09 06:05:34'),(113,26,'BIDANG PENYEHATAN LINGKUNGAN','2026-09-09 06:05:34','2026-09-09 06:05:34'),(114,26,'BIDANG PRASARANA, SARANA DAN UTILITAS UMUM','2026-09-09 06:05:34','2026-09-09 06:05:34'),(115,26,'SEKRETARIAT','2026-09-09 06:05:34','2026-09-09 06:05:34'),(116,27,'BIDANG PEMBERDAYAAN SOSIAL','2026-09-09 06:05:34','2026-09-09 06:05:34'),(117,27,'BIDANG PERLINDUNGAN DAN JAMINAN SOSIAL','2026-09-09 06:05:34','2026-09-09 06:05:34'),(118,27,'BIDANG REHABILITASI SOSIAL','2026-09-09 06:05:34','2026-09-09 06:05:34'),(119,27,'SEKRETARIAT','2026-09-09 06:05:34','2026-09-09 06:05:34'),(120,28,'BIDANG PENYULUHAN','2026-09-09 06:05:34','2026-09-09 06:05:34'),(121,28,'BIDANG PERLINDUNGAN DAN PELAYANAN USAHA PERTANIAN','2026-09-09 06:05:34','2026-09-09 06:05:34'),(122,28,'BIDANG PRASARANA','2026-09-09 06:05:34','2026-09-09 06:05:34'),(123,28,'BIDANG SARANA','2026-09-09 06:05:34','2026-09-09 06:05:34'),(124,28,'SEKRETARIAT','2026-09-09 06:05:34','2026-09-09 06:05:34'),(125,29,'BIDANG HUBUNGAN INDUSTRIAL DAN SYARAT KERJA','2026-09-09 06:05:34','2026-09-09 06:05:34'),(126,29,'BIDANG PELATIHAN DAN PRODUKTIVITAS KERJA','2026-09-09 06:05:34','2026-09-09 06:05:34'),(127,29,'SEKRETARIAT','2026-09-09 06:05:34','2026-09-09 06:05:34'),(128,30,'INSPEKTORAT PEMBANTU I','2026-09-09 06:05:34','2026-09-09 06:05:34'),(129,30,'INSPEKTORAT PEMBANTU II','2026-09-09 06:05:34','2026-09-09 06:05:34'),(130,30,'INSPEKTORAT PEMBANTU III','2026-09-09 06:05:34','2026-09-09 06:05:34'),(131,30,'INSPEKTORAT PEMBANTU IV','2026-09-09 06:05:34','2026-09-09 06:05:34'),(132,30,'INSPEKTORAT PEMBANTU V','2026-09-09 06:05:34','2026-09-09 06:05:34'),(133,30,'SEKRETARIAT','2026-09-09 06:05:34','2026-09-09 06:05:34'),(134,31,'SEKRETARIAT','2026-09-09 06:05:34','2026-09-09 06:05:34'),(135,32,'SEKRETARIAT','2026-09-09 06:05:34','2026-09-09 06:05:34'),(136,33,'SEKRETARIAT','2026-09-09 06:05:34','2026-09-09 06:05:34'),(137,34,'SEKRETARIAT','2026-09-09 06:05:34','2026-09-09 06:05:34'),(138,35,'SEKRETARIAT','2026-09-09 06:05:34','2026-09-09 06:05:34'),(139,36,'SEKRETARIAT','2026-09-09 06:05:34','2026-09-09 06:05:34'),(140,37,'SEKRETARIAT','2026-09-09 06:05:34','2026-09-09 06:05:34'),(141,38,'SEKRETARIAT','2026-09-09 06:05:34','2026-09-09 06:05:34'),(142,39,'SEKRETARIAT','2026-09-09 06:05:34','2026-09-09 06:05:34'),(143,40,'SEKRETARIAT','2026-09-09 06:05:34','2026-09-09 06:05:34'),(144,41,'SEKRETARIAT','2026-09-09 06:05:34','2026-09-09 06:05:34'),(145,42,'SEKRETARIAT','2026-09-09 06:05:34','2026-09-09 06:05:34'),(146,43,'SEKRETARIAT','2026-09-09 06:05:34','2026-09-09 06:05:34'),(147,44,'SEKRETARIAT','2026-09-09 06:05:34','2026-09-09 06:05:34'),(148,45,'SEKRETARIAT','2026-09-09 06:05:34','2026-09-09 06:05:34'),(149,46,'SEKRETARIAT','2026-09-09 06:05:34','2026-09-09 06:05:34'),(150,47,'SEKRETARIAT','2026-09-09 06:05:34','2026-09-09 06:05:34'),(151,48,'SEKRETARIAT','2026-09-09 06:05:34','2026-09-09 06:05:34'),(152,49,'SEKRETARIAT','2026-09-09 06:05:34','2026-09-09 06:05:34'),(153,50,'SEKRETARIAT','2026-09-09 06:05:34','2026-09-09 06:05:34'),(154,51,'SEKRETARIAT','2026-09-09 06:05:34','2026-09-09 06:05:34'),(155,52,'SEKRETARIAT','2026-09-09 06:05:34','2026-09-09 06:05:34'),(156,53,'SEKRETARIAT','2026-09-09 06:05:34','2026-09-09 06:05:34'),(157,54,'SEKRETARIAT','2026-09-09 06:05:34','2026-09-09 06:05:34'),(158,55,'SEKRETARIAT','2026-09-09 06:05:34','2026-09-09 06:05:34'),(159,56,'SEKRETARIAT','2026-09-09 06:05:34','2026-09-09 06:05:34'),(160,57,'SEKRETARIAT','2026-09-09 06:05:35','2026-09-09 06:05:35'),(161,58,'SEKRETARIAT','2026-09-09 06:05:35','2026-09-09 06:05:35'),(162,59,'SEKRETARIAT','2026-09-09 06:05:35','2026-09-09 06:05:35'),(163,60,'SEKRETARIAT','2026-09-09 06:05:35','2026-09-09 06:05:35'),(164,61,'SEKRETARIAT','2026-09-09 06:05:35','2026-09-09 06:05:35'),(165,62,'SEKRETARIAT','2026-09-09 06:05:35','2026-09-09 06:05:35'),(166,63,'SEKRETARIAT','2026-09-09 06:05:35','2026-09-09 06:05:35'),(167,64,'SEKRETARIAT','2026-09-09 06:05:35','2026-09-09 06:05:35'),(168,65,'SEKRETARIAT','2026-09-09 06:05:35','2026-09-09 06:05:35'),(169,66,'SEKRETARIAT','2026-09-09 06:05:35','2026-09-09 06:05:35'),(170,67,'SEKRETARIAT','2026-09-09 06:05:35','2026-09-09 06:05:35'),(171,68,'BAGIAN KEUANGAN RSUD CIBINONG','2026-09-09 06:05:35','2026-09-09 06:05:35'),(172,68,'BAGIAN TATA USAHA RSUD CIBINONG','2026-09-09 06:05:35','2026-09-09 06:05:35'),(173,68,'BIDANG ADMINISTRASI','2026-09-09 06:05:35','2026-09-09 06:05:35'),(174,68,'BIDANG MEDIK RSUD CIBINONG','2026-09-09 06:05:35','2026-09-09 06:05:35'),(175,68,'BIDANG PELAYANAN','2026-09-09 06:05:35','2026-09-09 06:05:35'),(176,69,'BAGIAN KEUANGAN','2026-09-09 06:05:35','2026-09-09 06:05:35'),(177,69,'BAGIAN TATA USAHA','2026-09-09 06:05:35','2026-09-09 06:05:35'),(178,69,'BIDANG ADMINISTRASI','2026-09-09 06:05:35','2026-09-09 06:05:35'),(179,69,'BIDANG KEPERAWATAN','2026-09-09 06:05:35','2026-09-09 06:05:35'),(180,69,'BIDANG MEDIK','2026-09-09 06:05:35','2026-09-09 06:05:35'),(181,69,'BIDANG PELAYANAN','2026-09-09 06:05:35','2026-09-09 06:05:35'),(182,70,'BAGIAN KEUANGAN RSUD LEUWILIANG','2026-09-09 06:05:35','2026-09-09 06:05:35'),(183,70,'BAGIAN TATA USAHA RSUD LEUWILIANG','2026-09-09 06:05:35','2026-09-09 06:05:35'),(184,70,'BIDANG KEPERAWATAN RSUD LEUWILIANG','2026-09-09 06:05:35','2026-09-09 06:05:35'),(185,70,'BIDANG MEDIK RSUD LEUWILIANG','2026-09-09 06:05:35','2026-09-09 06:05:35'),(186,70,'BIDANG PELAYANAN','2026-09-09 06:05:35','2026-09-09 06:05:35'),(187,71,'BAGIAN KEUANGAN','2026-09-09 06:05:35','2026-09-09 06:05:35'),(188,71,'BAGIAN TATA USAHA','2026-09-09 06:05:35','2026-09-09 06:05:35'),(189,71,'BIDANG ADMINISTRASI','2026-09-09 06:05:35','2026-09-09 06:05:35'),(190,71,'BIDANG KEPERAWATAN','2026-09-09 06:05:35','2026-09-09 06:05:35'),(191,71,'BIDANG MEDIK','2026-09-09 06:05:35','2026-09-09 06:05:35'),(192,71,'BIDANG PELAYANAN','2026-09-09 06:05:35','2026-09-09 06:05:35'),(193,72,'BIDANG KETERTIBAN UMUM','2026-09-09 06:05:35','2026-09-09 06:05:35'),(194,72,'BIDANG PEMBINAAN','2026-09-09 06:05:35','2026-09-09 06:05:35'),(195,72,'BIDANG PENEGAKAN PERUNDANG-UNDANGAN DAERAH','2026-09-09 06:05:35','2026-09-09 06:05:35'),(196,72,'BIDANG PERLINDUNGAN MASYARAKAT','2026-09-09 06:05:35','2026-09-09 06:05:35'),(197,73,'BAGIAN ADMINISTRASI PEMBANGUNAN','2026-09-09 06:05:35','2026-09-09 06:05:35'),(198,73,'BAGIAN KERJASAMA DAN BANTUAN HUKUM','2026-09-09 06:05:35','2026-09-09 06:05:35'),(199,73,'BAGIAN KESEJAHTERAAN RAKYAT','2026-09-09 06:05:35','2026-09-09 06:05:35'),(200,73,'BAGIAN ORGANISASI','2026-09-09 06:05:35','2026-09-09 06:05:35'),(201,73,'BAGIAN PENGADAAN BARANG/JASA','2026-09-09 06:05:35','2026-09-09 06:05:35'),(202,73,'BAGIAN PEREKONOMIAN','2026-09-09 06:05:35','2026-09-09 06:05:35'),(203,73,'BAGIAN PERENCANAAN DAN KEUANGAN','2026-09-09 06:05:35','2026-09-09 06:05:35'),(204,73,'BAGIAN PERUNDANG-UNDANGAN','2026-09-09 06:05:35','2026-09-09 06:05:35'),(205,73,'BAGIAN PROTOKOL DAN KOMUNIKASI PIMPINAN','2026-09-09 06:05:35','2026-09-09 06:05:35'),(206,73,'BAGIAN SUMBER DAYA ALAM','2026-09-09 06:05:35','2026-09-09 06:05:35'),(207,73,'BAGIAN TATA PEMERINTAHAN','2026-09-09 06:05:35','2026-09-09 06:05:35'),(208,73,'BAGIAN UMUM','2026-09-09 06:05:35','2026-09-09 06:05:35'),(209,74,'BAGIAN FASILTASI PENGANGGARAN DAN PENGAWASAN','2026-09-09 06:05:35','2026-09-09 06:05:35'),(210,74,'BAGIAN PERSIDANGAN DAN PERUNDANG-UNDANGAN','2026-09-09 06:05:35','2026-09-09 06:05:35'),(211,74,'BAGIAN PROGRAM DAN KEUANGAN','2026-09-09 06:05:35','2026-09-09 06:05:35'),(212,76,'Sekretariat','2026-09-13 17:32:17','2026-09-13 17:32:17');
/*!40000 ALTER TABLE `bidang` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `jenis_layanans`
--

LOCK TABLES `jenis_layanans` WRITE;
/*!40000 ALTER TABLE `jenis_layanans` DISABLE KEYS */;
INSERT INTO `jenis_layanans` VALUES (1,'Rekomendasi Surat Izin Penelitian | Pengambilan Data | Wawancara | Survei','penelitian_pt',0,'2026-09-09 06:05:32','2026-09-09 06:05:32'),(2,'Rekomendasi Surat Izin Penelitian | Pengambilan Data | Wawancara | Survei | Pelaksanaan Kegiatan (Instansi / Lembaga)','penelitian_instansi',0,'2026-09-09 06:05:32','2026-09-09 06:05:32'),(3,'Rekomendasi Surat Izin KKL / PKL','kkl_mahasiswa',1,'2026-09-09 06:05:32','2026-09-09 06:05:32'),(4,'Rekomendasi Surat Izin KKN','kkn_mahasiswa',0,'2026-09-09 06:05:32','2026-09-09 06:05:32'),(5,'Rekomendasi Surat Izin PKL (Siswa Sekolah)','kkl_siswa',1,'2026-09-09 06:05:32','2026-09-09 06:05:32'),(6,'Rekomendasi Pelaksanaan Kegiatan','pelaksanaan_kegiatan',0,'2026-09-09 06:05:32','2026-09-09 06:05:32'),(7,'KHUSUS PERPANJANGAN SURAT REKOMENDASI','perpanjangan',0,'2026-09-09 06:05:32','2026-09-09 06:05:32');
/*!40000 ALTER TABLE `jenis_layanans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `status_masters`
--

LOCK TABLES `status_masters` WRITE;
/*!40000 ALTER TABLE `status_masters` DISABLE KEYS */;
INSERT INTO `status_masters` VALUES (1,'menunggu_verifikasi','Menunggu Verifikasi','#ffc107','2026-09-09 06:05:32','2026-09-09 06:05:32'),(2,'perlu_revisi','Perlu Revisi','#fd7e14','2026-09-09 06:05:32','2026-09-09 06:05:32'),(3,'ditolak','Ditolak','#dc3545','2026-09-09 06:05:32','2026-09-09 06:05:32'),(4,'disetujui','Disetujui','#20c997','2026-09-09 06:05:32','2026-09-09 06:05:32'),(5,'selesai','Selesai','#198754','2026-09-09 06:05:32','2026-09-09 06:05:32');
/*!40000 ALTER TABLE `status_masters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `rekrutmens`
--

LOCK TABLES `rekrutmens` WRITE;
/*!40000 ALTER TABLE `rekrutmens` DISABLE KEYS */;
INSERT INTO `rekrutmens` VALUES (2,76,212,'Rekap data',NULL,5,'2026-09-16',1,'2026-09-13 17:32:42','2026-09-13 17:54:57'),(3,1,2,'asdsad',NULL,10,'2026-09-15',1,'2026-09-13 17:44:49','2026-09-13 17:44:49');
/*!40000 ALTER TABLE `rekrutmens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `md_master_data`
--

LOCK TABLES `md_master_data` WRITE;
/*!40000 ALTER TABLE `md_master_data` DISABLE KEYS */;
INSERT INTO `md_master_data` VALUES (1,'absensi_status','hadir','Hadir','#10b981',1,1,'2026-09-09 06:05:15','2026-09-09 06:05:15'),(2,'absensi_status','wfh','WFH','#6366f1',2,1,'2026-09-09 06:05:15','2026-09-09 06:05:15'),(3,'absensi_status','sakit','Sakit','#ef4444',3,1,'2026-09-09 06:05:15','2026-09-09 06:05:15'),(4,'absensi_status','izin','Izin','#f59e0b',4,1,'2026-09-09 06:05:15','2026-09-09 06:05:15'),(5,'jadwal_status','wfo','WFO','#10b981',1,1,'2026-09-09 06:05:15','2026-09-09 06:05:15'),(6,'jadwal_status','wfh','WFH','#6366f1',2,1,'2026-09-09 06:05:15','2026-09-09 06:05:15'),(7,'project_status','aktif','Aktif','#10b981',1,1,'2026-09-09 06:05:15','2026-09-09 06:05:15'),(8,'project_status','selesai','Selesai','#64748b',2,1,'2026-09-09 06:05:15','2026-09-09 06:05:15'),(9,'note_kategori','rendah','Rendah','#10b981',1,1,'2026-09-09 06:05:15','2026-09-09 06:05:15'),(10,'note_kategori','sedang','Sedang','#f59e0b',2,1,'2026-09-09 06:05:15','2026-09-09 06:05:15'),(11,'note_kategori','tinggi','Tinggi','#ef4444',3,1,'2026-09-09 06:05:15','2026-09-09 06:05:15');
/*!40000 ALTER TABLE `md_master_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `md_pengaturan`
--

LOCK TABLES `md_pengaturan` WRITE;
/*!40000 ALTER TABLE `md_pengaturan` DISABLE KEYS */;
INSERT INTO `md_pengaturan` VALUES (1,'pin_admin','$2y$12$luWTCPIjxMZiwHr4rDaU0O6PuCDPiUbae5fhSV36yTPAvW7FLDWlS','2026-09-09 06:05:16','2026-09-09 06:05:20'),(2,'pin_superadmin','$2y$12$gumkmkloIIjoLyN5VRFjxusv2yN9Ofqn0P/qLPbhugrAhAsOcmdzC','2026-09-09 06:05:20','2026-09-09 06:05:20'),(3,'admin_login_username','admin','2026-09-09 06:05:21','2026-09-09 06:05:21'),(4,'admin_login_password','$2y$12$VaSw039EoI7VuWnknaxylO4gtCIFcggKCoJoPu5JC/q6TZx9P6CwW','2026-09-09 06:05:21','2026-09-09 06:05:21'),(5,'superadmin_login_username','superadmin','2026-09-09 06:05:21','2026-09-09 06:05:21'),(6,'superadmin_login_password','$2y$12$.iJxHl/dGeNRVFDWTOXWv.0PpVQh6EPCyL4B6xkNvvtxnZ9c1/uZy','2026-09-09 06:05:21','2026-09-09 06:05:21');
/*!40000 ALTER TABLE `md_pengaturan` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-09-14  1:10:07
-- MySQL dump 10.13  Distrib 8.4.3, for Win64 (x86_64)
--
-- Host: 127.0.1.1    Database: lentera_kab_bogor
-- ------------------------------------------------------
-- Server version	8.4.3

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Dumping data for table `users`
--
-- WHERE:  role NOT IN ('user', 'peserta')

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin','admin','admin@example.test',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$wvoSueGzs1.y3Od6bysoe.2lwVmOuYxR1OyXZGXd2BoKwd733rhaS','admin','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:05:26','2026-09-13 15:48:21',NULL,NULL),(2,'Super Admin','superadmin','superadmin@example.test',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$P0hS6Uy6j3q751qq4I0nmutMwE6E6b91m4dBkzoQmnk6PBVPW8H5u','superadmin','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:05:26','2026-09-13 15:48:21',NULL,NULL),(3,'Admin BADAN KEPEGAWAIAN DAN PENGEMBANGAN SUMBER DAYA MANUSIA',NULL,'badan_kepegawaian_dan_pengembangan_sumber_daya_manusia@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:36','2026-09-09 06:09:36',1,NULL),(4,'Admin BIDANG MUTASI DAN PROMOSI (BADAN KEPEGAWAIAN DAN PENGEMBANGAN SUMBER DAYA MANUSIA)','mutasi_dan_promosi_kepegawaian_dan_pengembangan','mutasi_dan_promosi.kepegawaian_dan_pengembangan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:36','2026-09-09 06:09:36',1,1),(5,'Admin BIDANG PENGADAAN, PEMBERHENTIAN DAN INFORMASI (BADAN KEPEGAWAIAN DAN PENGEMBANGAN SUMBER DAYA MANUSIA)','pengadaan_pemberhentian_dan_kepegawaian_dan_pengembangan','pengadaan_pemberhentian_dan.kepegawaian_dan_pengembangan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:36','2026-09-09 06:09:36',1,2),(6,'Admin BIDANG PENGEMBANGAN KOMPETENSI APARATUR (BADAN KEPEGAWAIAN DAN PENGEMBANGAN SUMBER DAYA MANUSIA)','pengembangan_kompetensi_aparatur_kepegawaian_dan_pengembangan','pengembangan_kompetensi_aparatur.kepegawaian_dan_pengembangan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:36','2026-09-09 06:09:36',1,3),(7,'Admin BIDANG PENILAIAN KINERJA APARATUR DAN PENGHARGAAN (BADAN KEPEGAWAIAN DAN PENGEMBANGAN SUMBER DAYA MANUSIA)','penilaian_kinerja_aparatur_kepegawaian_dan_pengembangan','penilaian_kinerja_aparatur.kepegawaian_dan_pengembangan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:36','2026-09-09 06:09:36',1,4),(8,'Admin SEKRETARIAT (BADAN KEPEGAWAIAN DAN PENGEMBANGAN SUMBER DAYA MANUSIA)','sekretariat_kepegawaian_dan_pengembangan','sekretariat.kepegawaian_dan_pengembangan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:36','2026-09-09 06:09:36',1,5),(9,'Admin BADAN KESATUAN BANGSA DAN POLITIK',NULL,'badan_kesatuan_bangsa_dan_politik@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:36','2026-09-13 17:27:09',76,NULL),(10,'Admin BIDANG IDEOLOGI, WAWASAN KEBANGSAAN DAN KARAKTER BANGSA (BADAN KESATUAN BANGSA DAN POLITIK)','ideologi_wawasan_kebangsaan_kesatuan_bangsa_dan','ideologi_wawasan_kebangsaan.kesatuan_bangsa_dan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:36','2026-09-13 17:27:09',76,NULL),(11,'Admin BIDANG KETAHANAN EKONOMI, SOSIAL BUDAYA, AGAMA DAN ORGANISASI KEMASYARAKATAN (BADAN KESATUAN BANGSA DAN POLITIK)','ketahanan_ekonomi_sosial_kesatuan_bangsa_dan','ketahanan_ekonomi_sosial.kesatuan_bangsa_dan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:36','2026-09-13 17:27:09',76,NULL),(12,'Admin BIDANG KEWASPADAAN NASIONAL DAN PENANGANAN KONFLIK (BADAN KESATUAN BANGSA DAN POLITIK)','kewaspadaan_nasional_dan_kesatuan_bangsa_dan','kewaspadaan_nasional_dan.kesatuan_bangsa_dan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:36','2026-09-13 17:27:09',76,NULL),(13,'Admin BIDANG POLITIK DALAM NEGERI (BADAN KESATUAN BANGSA DAN POLITIK)','politik_dalam_negeri_kesatuan_bangsa_dan','politik_dalam_negeri.kesatuan_bangsa_dan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:36','2026-09-13 17:27:09',76,NULL),(14,'Admin SEKRETARIAT (BADAN KESATUAN BANGSA DAN POLITIK)','sekretariat_kesatuan_bangsa_dan','sekretariat.kesatuan_bangsa_dan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:36','2026-09-13 17:27:09',76,NULL),(15,'Admin BADAN PENANGGGULANGAN BENCANA DAERAH',NULL,'badan_penangggulangan_bencana_daerah@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:36','2026-09-09 06:09:36',3,NULL),(16,'Admin BIDANG KEDARURATAN DAN LOGISTIK (BADAN PENANGGGULANGAN BENCANA DAERAH)','kedaruratan_dan_logistik_penangggulangan_bencana_daerah','kedaruratan_dan_logistik.penangggulangan_bencana_daerah@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:36','2026-09-09 06:09:36',3,11),(17,'Admin BIDANG PENCEGAHAN DAN KESIAPSIAGAAN (BADAN PENANGGGULANGAN BENCANA DAERAH)','pencegahan_dan_kesiapsiagaan_penangggulangan_bencana_daerah','pencegahan_dan_kesiapsiagaan.penangggulangan_bencana_daerah@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:36','2026-09-09 06:09:36',3,12),(18,'Admin BIDANG REHABILITASI DAN REKONSTRUKSI (BADAN PENANGGGULANGAN BENCANA DAERAH)','rehabilitasi_dan_rekonstruksi_penangggulangan_bencana_daerah','rehabilitasi_dan_rekonstruksi.penangggulangan_bencana_daerah@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:36','2026-09-09 06:09:36',3,13),(19,'Admin SEKRETARIAT (BADAN PENANGGGULANGAN BENCANA DAERAH)','sekretariat_penangggulangan_bencana_daerah','sekretariat.penangggulangan_bencana_daerah@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:36','2026-09-09 06:09:36',3,14),(20,'Admin BADAN PENGELOLAAN KEUANGAN DAN ASET DAERAH',NULL,'badan_pengelolaan_keuangan_dan_aset_daerah@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:36','2026-09-09 06:09:36',4,NULL),(21,'Admin BIDANG AKUNTANSI DAN TEKNOLOGI INFORMASI (BADAN PENGELOLAAN KEUANGAN DAN ASET DAERAH)','akuntansi_dan_teknologi_pengelolaan_keuangan_dan','akuntansi_dan_teknologi.pengelolaan_keuangan_dan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:36','2026-09-09 06:09:36',4,15),(22,'Admin BIDANG ANGGARAN (BADAN PENGELOLAAN KEUANGAN DAN ASET DAERAH)','anggaran_pengelolaan_keuangan_dan','anggaran.pengelolaan_keuangan_dan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:36','2026-09-09 06:09:36',4,16),(23,'Admin BIDANG ASET DAERAH (BADAN PENGELOLAAN KEUANGAN DAN ASET DAERAH)','aset_daerah_pengelolaan_keuangan_dan','aset_daerah.pengelolaan_keuangan_dan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:36','2026-09-09 06:09:36',4,17),(24,'Admin BIDANG PERBENDAHARAAN (BADAN PENGELOLAAN KEUANGAN DAN ASET DAERAH)','perbendaharaan_pengelolaan_keuangan_dan','perbendaharaan.pengelolaan_keuangan_dan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:36','2026-09-09 06:09:36',4,18),(25,'Admin SEKRETARIAT (BADAN PENGELOLAAN KEUANGAN DAN ASET DAERAH)','sekretariat_pengelolaan_keuangan_dan','sekretariat.pengelolaan_keuangan_dan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:36','2026-09-09 06:09:36',4,19),(26,'Admin BADAN PENGELOLAAN PENDAPATAN DAERAH',NULL,'badan_pengelolaan_pendapatan_daerah@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:36','2026-09-09 06:09:36',5,NULL),(27,'Admin BIDANG PELAYANAN DAN PENETAPAN (BADAN PENGELOLAAN PENDAPATAN DAERAH)','pelayanan_dan_penetapan_pengelolaan_pendapatan_daerah','pelayanan_dan_penetapan.pengelolaan_pendapatan_daerah@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:36','2026-09-09 06:09:36',5,20),(28,'Admin BIDANG PENAGIHAN, KEBERATAN DAN PENGAWASAN PENDAPATAN DAERAH (BADAN PENGELOLAAN PENDAPATAN DAERAH)','penagihan_keberatan_dan_pengelolaan_pendapatan_daerah','penagihan_keberatan_dan.pengelolaan_pendapatan_daerah@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:36','2026-09-09 06:09:36',5,21),(29,'Admin BIDANG PENDATAAN DAN PENILAIAN (BADAN PENGELOLAAN PENDAPATAN DAERAH)','pendataan_dan_penilaian_pengelolaan_pendapatan_daerah','pendataan_dan_penilaian.pengelolaan_pendapatan_daerah@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:36','2026-09-09 06:09:36',5,22),(30,'Admin BIDANG PERENCANAAN DAN PENGEMBANGAN PENDAPATAN DAERAH (BADAN PENGELOLAAN PENDAPATAN DAERAH)','perencanaan_dan_pengembangan_pengelolaan_pendapatan_daerah','perencanaan_dan_pengembangan.pengelolaan_pendapatan_daerah@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:36','2026-09-09 06:09:36',5,23),(31,'Admin SEKRETARIAT (BADAN PENGELOLAAN PENDAPATAN DAERAH)','sekretariat_pengelolaan_pendapatan_daerah','sekretariat.pengelolaan_pendapatan_daerah@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:36','2026-09-09 06:09:36',5,24),(32,'Admin BADAN PERENCANAAN PEMBANGUNAN, RISET DAN INOVASI DAERAH',NULL,'badan_perencanaan_pembangunan_riset_dan_inovasi_daerah@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:36','2026-09-09 06:09:36',6,NULL),(33,'Admin BIDANG INFRASTRUKTUR DAN PENGEMBANGAN WILAYAH (BADAN PERENCANAAN PEMBANGUNAN, RISET DAN INOVASI DAERAH)','infrastruktur_dan_pengembangan_perencanaan_pembangunan_riset','infrastruktur_dan_pengembangan.perencanaan_pembangunan_riset@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:36','2026-09-09 06:09:36',6,25),(34,'Admin BIDANG PEMERINTAHAN DAN PENGEMBANGAN MANUSIA (BADAN PERENCANAAN PEMBANGUNAN, RISET DAN INOVASI DAERAH)','pemerintahan_dan_pengembangan_perencanaan_pembangunan_riset','pemerintahan_dan_pengembangan.perencanaan_pembangunan_riset@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:36','2026-09-09 06:09:36',6,26),(35,'Admin BIDANG PEREKONOMIAN DAN SUMBER DAYA ALAM (BADAN PERENCANAAN PEMBANGUNAN, RISET DAN INOVASI DAERAH)','perekonomian_dan_sumber_perencanaan_pembangunan_riset','perekonomian_dan_sumber.perencanaan_pembangunan_riset@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:36','2026-09-09 06:09:36',6,27),(36,'Admin BIDANG PERENCANAAN, PENGENDALIAN DAN EVALUASI PEMBANGUNAN DAERAH (BADAN PERENCANAAN PEMBANGUNAN, RISET DAN INOVASI DAERAH)','perencanaan_pengendalian_dan_perencanaan_pembangunan_riset','perencanaan_pengendalian_dan.perencanaan_pembangunan_riset@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:36','2026-09-09 06:09:36',6,28),(37,'Admin BIDANG RISET DAN INOVASI DAERAH (BADAN PERENCANAAN PEMBANGUNAN, RISET DAN INOVASI DAERAH)','riset_dan_inovasi_perencanaan_pembangunan_riset','riset_dan_inovasi.perencanaan_pembangunan_riset@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:36','2026-09-09 06:09:36',6,29),(38,'Admin SEKRETARIAT (BADAN PERENCANAAN PEMBANGUNAN, RISET DAN INOVASI DAERAH)','sekretariat_perencanaan_pembangunan_riset','sekretariat.perencanaan_pembangunan_riset@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:36','2026-09-09 06:09:36',6,30),(39,'Admin DINAS ARSIP DAN PERPUSTAKAAN',NULL,'dinas_arsip_dan_perpustakaan@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:36','2026-09-09 06:09:36',7,NULL),(40,'Admin BIDANG PEMBINAAN KEARSIPAN (DINAS ARSIP DAN PERPUSTAKAAN)','pembinaan_kearsipan_arsip_dan_perpustakaan','pembinaan_kearsipan.arsip_dan_perpustakaan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:36','2026-09-09 06:09:36',7,31),(41,'Admin BIDANG PENGELOLAAN KEARSIPAN DAN SISTEM INFORMASI KEARSIPAN (DINAS ARSIP DAN PERPUSTAKAAN)','pengelolaan_kearsipan_dan_arsip_dan_perpustakaan','pengelolaan_kearsipan_dan.arsip_dan_perpustakaan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:36','2026-09-09 06:09:36',7,32),(42,'Admin BIDANG PERPUSTAKAAN (DINAS ARSIP DAN PERPUSTAKAAN)','perpustakaan_arsip_dan_perpustakaan','perpustakaan.arsip_dan_perpustakaan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:36','2026-09-09 06:09:36',7,33),(43,'Admin SEKRETARIAT (DINAS ARSIP DAN PERPUSTAKAAN)','sekretariat_arsip_dan_perpustakaan','sekretariat.arsip_dan_perpustakaan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:36','2026-09-09 06:09:36',7,34),(44,'Admin DINAS KEPENDUDUKAN DAN PENCATATAN SIPIL',NULL,'dinas_kependudukan_dan_pencatatan_sipil@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:36','2026-09-09 06:09:36',8,NULL),(45,'Admin BIDANG PELAYANAN PENCATATAN SIPIL (DINAS KEPENDUDUKAN DAN PENCATATAN SIPIL)','pelayanan_pencatatan_sipil_kependudukan_dan_pencatatan','pelayanan_pencatatan_sipil.kependudukan_dan_pencatatan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:36','2026-09-09 06:09:36',8,35),(46,'Admin BIDANG PELAYANAN PENDAFTARAN PENDUDUK (DINAS KEPENDUDUKAN DAN PENCATATAN SIPIL)','pelayanan_pendaftaran_penduduk_kependudukan_dan_pencatatan','pelayanan_pendaftaran_penduduk.kependudukan_dan_pencatatan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:36','2026-09-09 06:09:36',8,36),(47,'Admin BIDANG PEMANFAATAN DATA DAN INOVASI PELAYANAN (DINAS KEPENDUDUKAN DAN PENCATATAN SIPIL)','pemanfaatan_data_dan_kependudukan_dan_pencatatan','pemanfaatan_data_dan.kependudukan_dan_pencatatan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:36','2026-09-09 06:09:36',8,37),(48,'Admin BIDANG PENGELOLAAN INFORMASI ADMINISTRASI KEPENDUDUKAN (DINAS KEPENDUDUKAN DAN PENCATATAN SIPIL)','pengelolaan_informasi_administrasi_kependudukan_dan_pencatatan','pengelolaan_informasi_administrasi.kependudukan_dan_pencatatan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:36','2026-09-09 06:09:36',8,38),(49,'Admin SEKRETARIAT (DINAS KEPENDUDUKAN DAN PENCATATAN SIPIL)','sekretariat_kependudukan_dan_pencatatan','sekretariat.kependudukan_dan_pencatatan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:36','2026-09-09 06:09:36',8,39),(50,'Admin DINAS KESEHATAN',NULL,'dinas_kesehatan@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:36','2026-09-09 06:09:36',9,NULL),(51,'Admin BIDANG PELAYANAN KESEHATAN (DINAS KESEHATAN)','pelayanan_kesehatan_kesehatan','pelayanan_kesehatan.kesehatan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:36','2026-09-09 06:09:36',9,40),(52,'Admin BIDANG PENCEGAHAN DAN PENGENDALIAN PENYAKIT (DINAS KESEHATAN)','pencegahan_dan_pengendalian_kesehatan','pencegahan_dan_pengendalian.kesehatan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:36','2026-09-09 06:09:36',9,41),(53,'Admin BIDANG SUMBER DAYA KESEHATAN (DINAS KESEHATAN)','sumber_daya_kesehatan_kesehatan','sumber_daya_kesehatan.kesehatan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:36','2026-09-09 06:09:36',9,42),(54,'Admin SEKRETARIAT (DINAS KESEHATAN)','sekretariat_kesehatan','sekretariat.kesehatan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:36','2026-09-09 06:09:36',9,43),(55,'Admin DINAS KETAHANAN PANGAN',NULL,'dinas_ketahanan_pangan@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:36','2026-09-09 06:09:36',10,NULL),(56,'Admin BIDANG DISTRIBUSI DAN CADANGAN PANGAN (DINAS KETAHANAN PANGAN)','distribusi_dan_cadangan_ketahanan_pangan','distribusi_dan_cadangan.ketahanan_pangan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:36','2026-09-09 06:09:36',10,44),(57,'Admin BIDANG KEAMANAN PANGAN (DINAS KETAHANAN PANGAN)','keamanan_pangan_ketahanan_pangan','keamanan_pangan.ketahanan_pangan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:36','2026-09-09 06:09:36',10,45),(58,'Admin BIDANG KETERSEDIAAN DAN KERAWANAN PANGAN (DINAS KETAHANAN PANGAN)','ketersediaan_dan_kerawanan_ketahanan_pangan','ketersediaan_dan_kerawanan.ketahanan_pangan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:36','2026-09-09 06:09:36',10,46),(59,'Admin BIDANG KONSUMSI DAN PENGANEKARAGAMAN PANGAN (DINAS KETAHANAN PANGAN)','konsumsi_dan_penganekaragaman_ketahanan_pangan','konsumsi_dan_penganekaragaman.ketahanan_pangan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',10,47),(60,'Admin SEKRETARIAT (DINAS KETAHANAN PANGAN)','sekretariat_ketahanan_pangan','sekretariat.ketahanan_pangan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',10,48),(61,'Admin DINAS KOMUNIKASI DAN INFORMATIKA',NULL,'dinas_komunikasi_dan_informatika@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',11,NULL),(62,'Admin BIDANG APLIKASI INFORMATIKA (DINAS KOMUNIKASI DAN INFORMATIKA)','aplikasi_informatika_komunikasi_dan_informatika','aplikasi_informatika.komunikasi_dan_informatika@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',11,49),(63,'Admin BIDANG INFRASTRUKTUR TEKNOLOGI (DINAS KOMUNIKASI DAN INFORMATIKA)','infrastruktur_teknologi_komunikasi_dan_informatika','infrastruktur_teknologi.komunikasi_dan_informatika@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',11,50),(64,'Admin BIDANG PERSANDIAN DAN STATISTIK (DINAS KOMUNIKASI DAN INFORMATIKA)','persandian_dan_statistik_komunikasi_dan_informatika','persandian_dan_statistik.komunikasi_dan_informatika@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',11,51),(65,'Admin SEKRETARIAT (DINAS KOMUNIKASI DAN INFORMATIKA)','sekretariat_komunikasi_dan_informatika','sekretariat.komunikasi_dan_informatika@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',11,52),(66,'Admin DINAS KOPERASI, USAHA KECIL DAN MENENGAH',NULL,'dinas_koperasi_usaha_kecil_dan_menengah@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',12,NULL),(67,'Admin BIDANG KELEMBAGAAN DAN PEMBERDAYAAN KOPERASI (DINAS KOPERASI, USAHA KECIL DAN MENENGAH)','kelembagaan_dan_pemberdayaan_koperasi_usaha_kecil','kelembagaan_dan_pemberdayaan.koperasi_usaha_kecil@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',12,53),(68,'Admin BIDANG PEMBERDAYAAN USAHA MIKRO (DINAS KOPERASI, USAHA KECIL DAN MENENGAH)','pemberdayaan_usaha_mikro_koperasi_usaha_kecil','pemberdayaan_usaha_mikro.koperasi_usaha_kecil@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',12,54),(69,'Admin BIDANG PENGAWASAN DAN PEMERIKSAAN KOPERASI (DINAS KOPERASI, USAHA KECIL DAN MENENGAH)','pengawasan_dan_pemeriksaan_koperasi_usaha_kecil','pengawasan_dan_pemeriksaan.koperasi_usaha_kecil@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',12,55),(70,'Admin SEKRETARIAT (DINAS KOPERASI, USAHA KECIL DAN MENENGAH)','sekretariat_koperasi_usaha_kecil','sekretariat.koperasi_usaha_kecil@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',12,56),(71,'Admin DINAS LINGKUNGAN HIDUP',NULL,'dinas_lingkungan_hidup@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',13,NULL),(72,'Admin BIDANG PENEGAKAN HUKUM DAN PENGELOLAAN LIMBAH BAHAN BERBAHAYA DAN BERACUN (LB3) (DINAS LINGKUNGAN HIDUP)','penegakan_hukum_dan_lingkungan_hidup','penegakan_hukum_dan.lingkungan_hidup@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',13,57),(73,'Admin BIDANG PENGELOLAAN SAMPAH (DINAS LINGKUNGAN HIDUP)','pengelolaan_sampah_lingkungan_hidup','pengelolaan_sampah.lingkungan_hidup@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',13,58),(74,'Admin BIDANG PENGENDALIAN PENCEMARAN DAN KEMITRAAN (DINAS LINGKUNGAN HIDUP)','pengendalian_pencemaran_dan_lingkungan_hidup','pengendalian_pencemaran_dan.lingkungan_hidup@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',13,59),(75,'Admin BIDANG TATA LINGKUNGAN (DINAS LINGKUNGAN HIDUP)','tata_lingkungan_lingkungan_hidup','tata_lingkungan.lingkungan_hidup@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',13,60),(76,'Admin SEKRETARIAT (DINAS LINGKUNGAN HIDUP)','sekretariat_lingkungan_hidup','sekretariat.lingkungan_hidup@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',13,61),(77,'Admin DINAS PARIWISATA DAN EKONOMI KREATIF',NULL,'dinas_pariwisata_dan_ekonomi_kreatif@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',14,NULL),(78,'Admin BIDANG DESTINASI PARIWISATA (DINAS PARIWISATA DAN EKONOMI KREATIF)','destinasi_pariwisata_pariwisata_dan_ekonomi','destinasi_pariwisata.pariwisata_dan_ekonomi@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',14,62),(79,'Admin BIDANG PEMASARAN PARIWISATA DAN EKONOMI KREATIF (DINAS PARIWISATA DAN EKONOMI KREATIF)','pemasaran_pariwisata_dan_pariwisata_dan_ekonomi','pemasaran_pariwisata_dan.pariwisata_dan_ekonomi@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',14,63),(80,'Admin BIDANG PENGEMBANGAN EKONOMI KREATIF (DINAS PARIWISATA DAN EKONOMI KREATIF)','pengembangan_ekonomi_kreatif_pariwisata_dan_ekonomi','pengembangan_ekonomi_kreatif.pariwisata_dan_ekonomi@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',14,64),(81,'Admin BIDANG SUMBER DAYA MANUSIA DAN EKONOMI KREATIF (DINAS PARIWISATA DAN EKONOMI KREATIF)','sumber_daya_manusia_pariwisata_dan_ekonomi','sumber_daya_manusia.pariwisata_dan_ekonomi@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',14,65),(82,'Admin SEKRETARIAT (DINAS PARIWISATA DAN EKONOMI KREATIF)','sekretariat_pariwisata_dan_ekonomi','sekretariat.pariwisata_dan_ekonomi@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',14,66),(83,'Admin DINAS PEKERJAAN UMUM',NULL,'dinas_pekerjaan_umum@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',15,NULL),(84,'Admin BIDANG IRIGASI DAN SUMBER DAYA AIR (DINAS PEKERJAAN UMUM)','irigasi_dan_sumber_pekerjaan_umum','irigasi_dan_sumber.pekerjaan_umum@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',15,67),(85,'Admin BIDANG JASA KONSTRUKSI (DINAS PEKERJAAN UMUM)','jasa_konstruksi_pekerjaan_umum','jasa_konstruksi.pekerjaan_umum@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',15,68),(86,'Admin BIDANG PEMBANGUNAN JALAN DAN JEMBATAN (DINAS PEKERJAAN UMUM)','pembangunan_jalan_dan_pekerjaan_umum','pembangunan_jalan_dan.pekerjaan_umum@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',15,69),(87,'Admin BIDANG PEMELIHARAAN JALAN DAN JEMBATAN (DINAS PEKERJAAN UMUM)','pemeliharaan_jalan_dan_pekerjaan_umum','pemeliharaan_jalan_dan.pekerjaan_umum@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',15,70),(88,'Admin SEKRETARIAT (DINAS PEKERJAAN UMUM)','sekretariat_pekerjaan_umum','sekretariat.pekerjaan_umum@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',15,71),(89,'Admin DINAS PEMADAM KEBAKARAN DAN PENYELAMATAN',NULL,'dinas_pemadam_kebakaran_dan_penyelamatan@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',16,NULL),(90,'Admin BIDANG PEMADAMAN (DINAS PEMADAM KEBAKARAN DAN PENYELAMATAN)','pemadaman_pemadam_kebakaran_dan','pemadaman.pemadam_kebakaran_dan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',16,72),(91,'Admin BIDANG PENCEGAHAN DAN KESIAGAAN BAHAYA KEBAKARAN (DINAS PEMADAM KEBAKARAN DAN PENYELAMATAN)','pencegahan_dan_kesiagaan_pemadam_kebakaran_dan','pencegahan_dan_kesiagaan.pemadam_kebakaran_dan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',16,73),(92,'Admin BIDANG SARANA PRASARANA DAN DATA (DINAS PEMADAM KEBAKARAN DAN PENYELAMATAN)','sarana_prasarana_dan_pemadam_kebakaran_dan','sarana_prasarana_dan.pemadam_kebakaran_dan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',16,74),(93,'Admin SEKRETARIAT (DINAS PEMADAM KEBAKARAN DAN PENYELAMATAN)','sekretariat_pemadam_kebakaran_dan','sekretariat.pemadam_kebakaran_dan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',16,75),(94,'Admin DINAS PEMBERDAYAAN MASYARAKAT DAN DESA',NULL,'dinas_pemberdayaan_masyarakat_dan_desa@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',17,NULL),(95,'Admin BIDANG KEUANGAN DAN KEKAYAAN DESA (DINAS PEMBERDAYAAN MASYARAKAT DAN DESA)','keuangan_dan_kekayaan_pemberdayaan_masyarakat_dan','keuangan_dan_kekayaan.pemberdayaan_masyarakat_dan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',17,76),(96,'Admin BIDANG PEMBERDAYAAN MASYARAKAT DESA (DINAS PEMBERDAYAAN MASYARAKAT DAN DESA)','pemberdayaan_masyarakat_desa_pemberdayaan_masyarakat_dan','pemberdayaan_masyarakat_desa.pemberdayaan_masyarakat_dan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',17,77),(97,'Admin BIDANG PEMERINTAHAN DESA (DINAS PEMBERDAYAAN MASYARAKAT DAN DESA)','pemerintahan_desa_pemberdayaan_masyarakat_dan','pemerintahan_desa.pemberdayaan_masyarakat_dan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',17,78),(98,'Admin BIDANG SARANA PRASARANA DAN KEWILAYAHAN (DINAS PEMBERDAYAAN MASYARAKAT DAN DESA)','sarana_prasarana_dan_pemberdayaan_masyarakat_dan','sarana_prasarana_dan.pemberdayaan_masyarakat_dan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',17,79),(99,'Admin SEKRETARIAT (DINAS PEMBERDAYAAN MASYARAKAT DAN DESA)','sekretariat_pemberdayaan_masyarakat_dan','sekretariat.pemberdayaan_masyarakat_dan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',17,80),(100,'Admin DINAS PEMBERDAYAAN PEREMPUAN DAN PERLINDUNGAN ANAK, PENGENDALIAN PENDUDUK DAN KB',NULL,'dinas_pemberdayaan_perempuan_dan_perlindungan_anak_pengendalian_penduduk_dan_kb@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',18,NULL),(101,'Admin BIDANG PEMBINAAN KELUARGA SEJAHTERA (DINAS PEMBERDAYAAN PEREMPUAN DAN PERLINDUNGAN ANAK, PENGENDALIAN PENDUDUK DAN KB)','pembinaan_keluarga_sejahtera_pemberdayaan_perempuan_dan','pembinaan_keluarga_sejahtera.pemberdayaan_perempuan_dan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',18,81),(102,'Admin BIDANG PEMENUHAN HAK DAN PERLINDUNGAN KHUSUS ANAK (DINAS PEMBERDAYAAN PEREMPUAN DAN PERLINDUNGAN ANAK, PENGENDALIAN PENDUDUK DAN KB)','pemenuhan_hak_dan_pemberdayaan_perempuan_dan','pemenuhan_hak_dan.pemberdayaan_perempuan_dan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',18,82),(103,'Admin SEKRETARIAT (DINAS PEMBERDAYAAN PEREMPUAN DAN PERLINDUNGAN ANAK, PENGENDALIAN PENDUDUK DAN KB)','sekretariat_pemberdayaan_perempuan_dan','sekretariat.pemberdayaan_perempuan_dan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',18,83),(104,'Admin DINAS PEMUDA DAN OLAH RAGA',NULL,'dinas_pemuda_dan_olah_raga@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',19,NULL),(105,'Admin BIDANG PEMBUDAYAAN OLAHRAGA (DINAS PEMUDA DAN OLAH RAGA)','pembudayaan_olahraga_pemuda_dan_olah','pembudayaan_olahraga.pemuda_dan_olah@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',19,84),(106,'Admin BIDANG PENINGKATAN PRESTASI OLAHRAGA (DINAS PEMUDA DAN OLAH RAGA)','peningkatan_prestasi_olahraga_pemuda_dan_olah','peningkatan_prestasi_olahraga.pemuda_dan_olah@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',19,85),(107,'Admin SEKRETARIAT (DINAS PEMUDA DAN OLAH RAGA)','sekretariat_pemuda_dan_olah','sekretariat.pemuda_dan_olah@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',19,86),(108,'Admin DINAS PENANAMAN MODAL DAN PELAYANAN TERPADU SATU PINTU',NULL,'dinas_penanaman_modal_dan_pelayanan_terpadu_satu_pintu@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',20,NULL),(109,'Admin BIDANG DATA (DINAS PENANAMAN MODAL DAN PELAYANAN TERPADU SATU PINTU)','data_penanaman_modal_dan','data.penanaman_modal_dan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',20,87),(110,'Admin BIDANG PELAYANAN PERIZINAN PEMANFAATAN RUANG (DINAS PENANAMAN MODAL DAN PELAYANAN TERPADU SATU PINTU)','pelayanan_perizinan_pemanfaatan_penanaman_modal_dan','pelayanan_perizinan_pemanfaatan.penanaman_modal_dan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',20,88),(111,'Admin BIDANG PENGEMBANGAN DAN PROMOSI (DINAS PENANAMAN MODAL DAN PELAYANAN TERPADU SATU PINTU)','pengembangan_dan_promosi_penanaman_modal_dan','pengembangan_dan_promosi.penanaman_modal_dan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',20,89),(112,'Admin SEKRETARIAT (DINAS PENANAMAN MODAL DAN PELAYANAN TERPADU SATU PINTU)','sekretariat_penanaman_modal_dan','sekretariat.penanaman_modal_dan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',20,90),(113,'Admin DINAS PENDIDIKAN',NULL,'dinas_pendidikan@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',21,NULL),(114,'Admin BIDANG PEMBINAAN PAUD DAN PENDIDIKAN MASYARAKAT (DINAS PENDIDIKAN)','pembinaan_paud_dan_pendidikan','pembinaan_paud_dan.pendidikan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',21,91),(115,'Admin BIDANG PEMBINAAN SEKOLAH DASAR (DINAS PENDIDIKAN)','pembinaan_sekolah_dasar_pendidikan','pembinaan_sekolah_dasar.pendidikan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',21,92),(116,'Admin BIDANG PEMBINAAN SEKOLAH MENENGAH PERTAMA (DINAS PENDIDIKAN)','pembinaan_sekolah_menengah_pendidikan','pembinaan_sekolah_menengah.pendidikan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',21,93),(117,'Admin BIDANG SARANA DAN PRASARANA (DINAS PENDIDIKAN)','sarana_dan_prasarana_pendidikan','sarana_dan_prasarana.pendidikan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',21,94),(118,'Admin SEKRETARIAT (DINAS PENDIDIKAN)','sekretariat_pendidikan','sekretariat.pendidikan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',21,95),(119,'Admin DINAS PERDAGANGAN DAN PERINDUSTRIAN',NULL,'dinas_perdagangan_dan_perindustrian@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',22,NULL),(120,'Admin BIDANG PERDAGANGAN (DINAS PERDAGANGAN DAN PERINDUSTRIAN)','perdagangan_perdagangan_dan_perindustrian','perdagangan.perdagangan_dan_perindustrian@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',22,96),(121,'Admin BIDANG TERTIB NIAGA (DINAS PERDAGANGAN DAN PERINDUSTRIAN)','tertib_niaga_perdagangan_dan_perindustrian','tertib_niaga.perdagangan_dan_perindustrian@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',22,97),(122,'Admin SEKRETARIAT (DINAS PERDAGANGAN DAN PERINDUSTRIAN)','sekretariat_perdagangan_dan_perindustrian','sekretariat.perdagangan_dan_perindustrian@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',22,98),(123,'Admin DINAS PERHUBUNGAN',NULL,'dinas_perhubungan@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',23,NULL),(124,'Admin BIDANG ANGKUTAN (DINAS PERHUBUNGAN)','angkutan_perhubungan','angkutan.perhubungan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',23,99),(125,'Admin BIDANG LALU LINTAS JALAN (DINAS PERHUBUNGAN)','lalu_lintas_jalan_perhubungan','lalu_lintas_jalan.perhubungan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',23,100),(126,'Admin BIDANG PRASARANA DAN PERLENGKAPAN JALAN (DINAS PERHUBUNGAN)','prasarana_dan_perlengkapan_perhubungan','prasarana_dan_perlengkapan.perhubungan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',23,101),(127,'Admin BIDANG SARANA TRANSPORTASI JALAN (DINAS PERHUBUNGAN)','sarana_transportasi_jalan_perhubungan','sarana_transportasi_jalan.perhubungan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',23,102),(128,'Admin SEKRETARIAT (DINAS PERHUBUNGAN)','sekretariat_perhubungan','sekretariat.perhubungan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',23,103),(129,'Admin DINAS PERIKANAN DAN PETERNAKAN',NULL,'dinas_perikanan_dan_peternakan@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',24,NULL),(130,'Admin BIDANG KESEHATAN HEWAN DAN KESMAVET (DINAS PERIKANAN DAN PETERNAKAN)','kesehatan_hewan_dan_perikanan_dan_peternakan','kesehatan_hewan_dan.perikanan_dan_peternakan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',24,104),(131,'Admin BIDANG PENGUATAN DAYA SAING PRODUK PERIKANAN (DINAS PERIKANAN DAN PETERNAKAN)','penguatan_daya_saing_perikanan_dan_peternakan','penguatan_daya_saing.perikanan_dan_peternakan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',24,105),(132,'Admin BIDANG PERIKANAN BUDIDAYA DAN PERIKANAN TANGKAP (DINAS PERIKANAN DAN PETERNAKAN)','perikanan_budidaya_dan_perikanan_dan_peternakan','perikanan_budidaya_dan.perikanan_dan_peternakan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',24,106),(133,'Admin BIDANG PETERNAKAN (DINAS PERIKANAN DAN PETERNAKAN)','peternakan_perikanan_dan_peternakan','peternakan.perikanan_dan_peternakan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',24,107),(134,'Admin SEKRETARIAT (DINAS PERIKANAN DAN PETERNAKAN)','sekretariat_perikanan_dan_peternakan','sekretariat.perikanan_dan_peternakan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',24,108),(135,'Admin DINAS PERTANAHAN DAN TATA RUANG',NULL,'dinas_pertanahan_dan_tata_ruang@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',25,NULL),(136,'Admin BIDANG PENATAAN BANGUNAN GEDUNG (DINAS PERTANAHAN DAN TATA RUANG)','penataan_bangunan_gedung_pertanahan_dan_tata','penataan_bangunan_gedung.pertanahan_dan_tata@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',25,109),(137,'Admin BIDANG PENGENDALIAN PEMANFAATAN RUANG DAN BANGUNAN (DINAS PERTANAHAN DAN TATA RUANG)','pengendalian_pemanfaatan_ruang_pertanahan_dan_tata','pengendalian_pemanfaatan_ruang.pertanahan_dan_tata@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:37','2026-09-09 06:09:37',25,110),(138,'Admin BIDANG PERTANAHAN (DINAS PERTANAHAN DAN TATA RUANG)','pertanahan_pertanahan_dan_tata','pertanahan.pertanahan_dan_tata@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',25,111),(139,'Admin DINAS PERUMAHAN DAN KAWASAN PERMUKIMAN',NULL,'dinas_perumahan_dan_kawasan_permukiman@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',26,NULL),(140,'Admin BIDANG KAWASAN PERMUKIMAN (DINAS PERUMAHAN DAN KAWASAN PERMUKIMAN)','kawasan_permukiman_perumahan_dan_kawasan','kawasan_permukiman.perumahan_dan_kawasan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',26,112),(141,'Admin BIDANG PENYEHATAN LINGKUNGAN (DINAS PERUMAHAN DAN KAWASAN PERMUKIMAN)','penyehatan_lingkungan_perumahan_dan_kawasan','penyehatan_lingkungan.perumahan_dan_kawasan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',26,113),(142,'Admin BIDANG PRASARANA, SARANA DAN UTILITAS UMUM (DINAS PERUMAHAN DAN KAWASAN PERMUKIMAN)','prasarana_sarana_dan_perumahan_dan_kawasan','prasarana_sarana_dan.perumahan_dan_kawasan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',26,114),(143,'Admin SEKRETARIAT (DINAS PERUMAHAN DAN KAWASAN PERMUKIMAN)','sekretariat_perumahan_dan_kawasan','sekretariat.perumahan_dan_kawasan@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',26,115),(144,'Admin DINAS SOSIAL',NULL,'dinas_sosial@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',27,NULL),(145,'Admin BIDANG PEMBERDAYAAN SOSIAL (DINAS SOSIAL)','pemberdayaan_sosial_sosial','pemberdayaan_sosial.sosial@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',27,116),(146,'Admin BIDANG PERLINDUNGAN DAN JAMINAN SOSIAL (DINAS SOSIAL)','perlindungan_dan_jaminan_sosial','perlindungan_dan_jaminan.sosial@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',27,117),(147,'Admin BIDANG REHABILITASI SOSIAL (DINAS SOSIAL)','rehabilitasi_sosial_sosial','rehabilitasi_sosial.sosial@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',27,118),(148,'Admin SEKRETARIAT (DINAS SOSIAL)','sekretariat_sosial','sekretariat.sosial@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',27,119),(149,'Admin DINAS TANAMAN PANGAN, HORTIKULTURA DAN PERKEBUNAN',NULL,'dinas_tanaman_pangan_hortikultura_dan_perkebunan@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',28,NULL),(150,'Admin BIDANG PENYULUHAN (DINAS TANAMAN PANGAN, HORTIKULTURA DAN PERKEBUNAN)','penyuluhan_tanaman_pangan_hortikultura','penyuluhan.tanaman_pangan_hortikultura@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',28,120),(151,'Admin BIDANG PERLINDUNGAN DAN PELAYANAN USAHA PERTANIAN (DINAS TANAMAN PANGAN, HORTIKULTURA DAN PERKEBUNAN)','perlindungan_dan_pelayanan_tanaman_pangan_hortikultura','perlindungan_dan_pelayanan.tanaman_pangan_hortikultura@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',28,121),(152,'Admin BIDANG PRASARANA (DINAS TANAMAN PANGAN, HORTIKULTURA DAN PERKEBUNAN)','prasarana_tanaman_pangan_hortikultura','prasarana.tanaman_pangan_hortikultura@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',28,122),(153,'Admin BIDANG SARANA (DINAS TANAMAN PANGAN, HORTIKULTURA DAN PERKEBUNAN)','sarana_tanaman_pangan_hortikultura','sarana.tanaman_pangan_hortikultura@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',28,123),(154,'Admin SEKRETARIAT (DINAS TANAMAN PANGAN, HORTIKULTURA DAN PERKEBUNAN)','sekretariat_tanaman_pangan_hortikultura','sekretariat.tanaman_pangan_hortikultura@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',28,124),(155,'Admin DINAS TENAGA KERJA',NULL,'dinas_tenaga_kerja@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',29,NULL),(156,'Admin BIDANG HUBUNGAN INDUSTRIAL DAN SYARAT KERJA (DINAS TENAGA KERJA)','hubungan_industrial_dan_tenaga_kerja','hubungan_industrial_dan.tenaga_kerja@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',29,125),(157,'Admin BIDANG PELATIHAN DAN PRODUKTIVITAS KERJA (DINAS TENAGA KERJA)','pelatihan_dan_produktivitas_tenaga_kerja','pelatihan_dan_produktivitas.tenaga_kerja@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',29,126),(158,'Admin SEKRETARIAT (DINAS TENAGA KERJA)','sekretariat_tenaga_kerja','sekretariat.tenaga_kerja@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',29,127),(159,'Admin INSPEKTORAT',NULL,'inspektorat@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',30,NULL),(160,'Admin INSPEKTORAT PEMBANTU I (INSPEKTORAT)','inspektorat_pembantu_i_inspektorat','inspektorat_pembantu_i.inspektorat@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',30,128),(161,'Admin INSPEKTORAT PEMBANTU II (INSPEKTORAT)','inspektorat_pembantu_ii_inspektorat','inspektorat_pembantu_ii.inspektorat@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',30,129),(162,'Admin INSPEKTORAT PEMBANTU III (INSPEKTORAT)','inspektorat_pembantu_iii_inspektorat','inspektorat_pembantu_iii.inspektorat@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',30,130),(163,'Admin INSPEKTORAT PEMBANTU IV (INSPEKTORAT)','inspektorat_pembantu_iv_inspektorat','inspektorat_pembantu_iv.inspektorat@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',30,131),(164,'Admin INSPEKTORAT PEMBANTU V (INSPEKTORAT)','inspektorat_pembantu_v_inspektorat','inspektorat_pembantu_v.inspektorat@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',30,132),(165,'Admin SEKRETARIAT (INSPEKTORAT)','sekretariat_inspektorat','sekretariat.inspektorat@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',30,133),(166,'Admin KECAMATAN BABAKAN MADANG',NULL,'kecamatan_babakan_madang@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',31,NULL),(167,'Admin SEKRETARIAT (KECAMATAN BABAKAN MADANG)','sekretariat_kec_babakan_madang','sekretariat.kec_babakan_madang@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',31,134),(168,'Admin KECAMATAN BOJONGGEDE',NULL,'kecamatan_bojonggede@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',32,NULL),(169,'Admin SEKRETARIAT (KECAMATAN BOJONGGEDE)','sekretariat_kec_bojonggede','sekretariat.kec_bojonggede@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',32,135),(170,'Admin KECAMATAN CARIU',NULL,'kecamatan_cariu@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',33,NULL),(171,'Admin SEKRETARIAT (KECAMATAN CARIU)','sekretariat_kec_cariu','sekretariat.kec_cariu@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',33,136),(172,'Admin KECAMATAN CIAMPEA',NULL,'kecamatan_ciampea@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',34,NULL),(173,'Admin SEKRETARIAT (KECAMATAN CIAMPEA)','sekretariat_kec_ciampea','sekretariat.kec_ciampea@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',34,137),(174,'Admin KECAMATAN CIAWI',NULL,'kecamatan_ciawi@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',35,NULL),(175,'Admin SEKRETARIAT (KECAMATAN CIAWI)','sekretariat_kec_ciawi','sekretariat.kec_ciawi@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',35,138),(176,'Admin KECAMATAN CIBINONG',NULL,'kecamatan_cibinong@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',36,NULL),(177,'Admin SEKRETARIAT (KECAMATAN CIBINONG)','sekretariat_kec_cibinong','sekretariat.kec_cibinong@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',36,139),(178,'Admin KECAMATAN CIBUNGBULANG',NULL,'kecamatan_cibungbulang@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',37,NULL),(179,'Admin SEKRETARIAT (KECAMATAN CIBUNGBULANG)','sekretariat_kec_cibungbulang','sekretariat.kec_cibungbulang@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',37,140),(180,'Admin KECAMATAN CIGOMBONG',NULL,'kecamatan_cigombong@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',38,NULL),(181,'Admin SEKRETARIAT (KECAMATAN CIGOMBONG)','sekretariat_kec_cigombong','sekretariat.kec_cigombong@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',38,141),(182,'Admin KECAMATAN CIGUDEG',NULL,'kecamatan_cigudeg@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',39,NULL),(183,'Admin SEKRETARIAT (KECAMATAN CIGUDEG)','sekretariat_kec_cigudeg','sekretariat.kec_cigudeg@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',39,142),(184,'Admin KECAMATAN CIJERUK',NULL,'kecamatan_cijeruk@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',40,NULL),(185,'Admin SEKRETARIAT (KECAMATAN CIJERUK)','sekretariat_kec_cijeruk','sekretariat.kec_cijeruk@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',40,143),(186,'Admin KECAMATAN CILEUNGSI',NULL,'kecamatan_cileungsi@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',41,NULL),(187,'Admin SEKRETARIAT (KECAMATAN CILEUNGSI)','sekretariat_kec_cileungsi','sekretariat.kec_cileungsi@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',41,144),(188,'Admin KECAMATAN CIOMAS',NULL,'kecamatan_ciomas@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',42,NULL),(189,'Admin SEKRETARIAT (KECAMATAN CIOMAS)','sekretariat_kec_ciomas','sekretariat.kec_ciomas@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',42,145),(190,'Admin KECAMATAN CISARUA',NULL,'kecamatan_cisarua@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',43,NULL),(191,'Admin SEKRETARIAT (KECAMATAN CISARUA)','sekretariat_kec_cisarua','sekretariat.kec_cisarua@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',43,146),(192,'Admin KECAMATAN CISEENG',NULL,'kecamatan_ciseeng@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',44,NULL),(193,'Admin SEKRETARIAT (KECAMATAN CISEENG)','sekretariat_kec_ciseeng','sekretariat.kec_ciseeng@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',44,147),(194,'Admin KECAMATAN CITEUREUP',NULL,'kecamatan_citeureup@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',45,NULL),(195,'Admin SEKRETARIAT (KECAMATAN CITEUREUP)','sekretariat_kec_citeureup','sekretariat.kec_citeureup@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',45,148),(196,'Admin KECAMATAN DRAMAGA',NULL,'kecamatan_dramaga@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',46,NULL),(197,'Admin SEKRETARIAT (KECAMATAN DRAMAGA)','sekretariat_kec_dramaga','sekretariat.kec_dramaga@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',46,149),(198,'Admin KECAMATAN GUNUNG PUTRI',NULL,'kecamatan_gunung_putri@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',47,NULL),(199,'Admin SEKRETARIAT (KECAMATAN GUNUNG PUTRI)','sekretariat_kec_gunung_putri','sekretariat.kec_gunung_putri@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',47,150),(200,'Admin KECAMATAN GUNUNG SINDUR',NULL,'kecamatan_gunung_sindur@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',48,NULL),(201,'Admin SEKRETARIAT (KECAMATAN GUNUNG SINDUR)','sekretariat_kec_gunung_sindur','sekretariat.kec_gunung_sindur@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',48,151),(202,'Admin KECAMATAN JASINGA',NULL,'kecamatan_jasinga@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',49,NULL),(203,'Admin SEKRETARIAT (KECAMATAN JASINGA)','sekretariat_kec_jasinga','sekretariat.kec_jasinga@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',49,152),(204,'Admin KECAMATAN JONGGOL',NULL,'kecamatan_jonggol@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',50,NULL),(205,'Admin SEKRETARIAT (KECAMATAN JONGGOL)','sekretariat_kec_jonggol','sekretariat.kec_jonggol@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',50,153),(206,'Admin KECAMATAN KEMANG',NULL,'kecamatan_kemang@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',51,NULL),(207,'Admin SEKRETARIAT (KECAMATAN KEMANG)','sekretariat_kec_kemang','sekretariat.kec_kemang@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',51,154),(208,'Admin KECAMATAN KLAPANUNGGAL',NULL,'kecamatan_klapanunggal@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',52,NULL),(209,'Admin SEKRETARIAT (KECAMATAN KLAPANUNGGAL)','sekretariat_kec_klapanunggal','sekretariat.kec_klapanunggal@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',52,155),(210,'Admin KECAMATAN LEUWILIANG',NULL,'kecamatan_leuwiliang@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',53,NULL),(211,'Admin SEKRETARIAT (KECAMATAN LEUWILIANG)','sekretariat_kec_leuwiliang','sekretariat.kec_leuwiliang@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',53,156),(212,'Admin KECAMATAN LEUWISADENG',NULL,'kecamatan_leuwisadeng@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',54,NULL),(213,'Admin SEKRETARIAT (KECAMATAN LEUWISADENG)','sekretariat_kec_leuwisadeng','sekretariat.kec_leuwisadeng@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',54,157),(214,'Admin KECAMATAN NANGGUNG',NULL,'kecamatan_nanggung@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',55,NULL),(215,'Admin SEKRETARIAT (KECAMATAN NANGGUNG)','sekretariat_kec_nanggung','sekretariat.kec_nanggung@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',55,158),(216,'Admin KECAMATAN PARUNG',NULL,'kecamatan_parung@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',56,NULL),(217,'Admin SEKRETARIAT (KECAMATAN PARUNG)','sekretariat_kec_parung','sekretariat.kec_parung@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',56,159),(218,'Admin KECAMATAN PARUNG PANJANG',NULL,'kecamatan_parung_panjang@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:38','2026-09-09 06:09:38',57,NULL),(219,'Admin SEKRETARIAT (KECAMATAN PARUNG PANJANG)','sekretariat_kec_parung_panjang','sekretariat.kec_parung_panjang@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',57,160),(220,'Admin KECAMATAN RANCABUNGUR',NULL,'kecamatan_rancabungur@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',58,NULL),(221,'Admin SEKRETARIAT (KECAMATAN RANCABUNGUR)','sekretariat_kec_rancabungur','sekretariat.kec_rancabungur@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',58,161),(222,'Admin KECAMATAN RUMPIN',NULL,'kecamatan_rumpin@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',59,NULL),(223,'Admin SEKRETARIAT (KECAMATAN RUMPIN)','sekretariat_kec_rumpin','sekretariat.kec_rumpin@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',59,162),(224,'Admin KECAMATAN SUKAJAYA',NULL,'kecamatan_sukajaya@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',60,NULL),(225,'Admin SEKRETARIAT (KECAMATAN SUKAJAYA)','sekretariat_kec_sukajaya','sekretariat.kec_sukajaya@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',60,163),(226,'Admin KECAMATAN SUKAMAKMUR',NULL,'kecamatan_sukamakmur@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',61,NULL),(227,'Admin SEKRETARIAT (KECAMATAN SUKAMAKMUR)','sekretariat_kec_sukamakmur','sekretariat.kec_sukamakmur@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',61,164),(228,'Admin KECAMATAN SUKARAJA',NULL,'kecamatan_sukaraja@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',62,NULL),(229,'Admin SEKRETARIAT (KECAMATAN SUKARAJA)','sekretariat_kec_sukaraja','sekretariat.kec_sukaraja@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',62,165),(230,'Admin KECAMATAN TAJURHALANG',NULL,'kecamatan_tajurhalang@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',63,NULL),(231,'Admin SEKRETARIAT (KECAMATAN TAJURHALANG)','sekretariat_kec_tajurhalang','sekretariat.kec_tajurhalang@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',63,166),(232,'Admin KECAMATAN TAMANSARI',NULL,'kecamatan_tamansari@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',64,NULL),(233,'Admin SEKRETARIAT (KECAMATAN TAMANSARI)','sekretariat_kec_tamansari','sekretariat.kec_tamansari@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',64,167),(234,'Admin KECAMATAN TANJUNGSARI',NULL,'kecamatan_tanjungsari@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',65,NULL),(235,'Admin SEKRETARIAT (KECAMATAN TANJUNGSARI)','sekretariat_kec_tanjungsari','sekretariat.kec_tanjungsari@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',65,168),(236,'Admin KECAMATAN TENJO',NULL,'kecamatan_tenjo@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',66,NULL),(237,'Admin SEKRETARIAT (KECAMATAN TENJO)','sekretariat_kec_tenjo','sekretariat.kec_tenjo@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',66,169),(238,'Admin KECAMATAN TENJOLAYA',NULL,'kecamatan_tenjolaya@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',67,NULL),(239,'Admin SEKRETARIAT (KECAMATAN TENJOLAYA)','sekretariat_kec_tenjolaya','sekretariat.kec_tenjolaya@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',67,170),(240,'Admin RUMAH SAKIT UMUM DAERAH BAKTI PAJAJARAN',NULL,'rumah_sakit_umum_daerah_bakti_pajajaran@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',68,NULL),(241,'Admin BAGIAN KEUANGAN RSUD CIBINONG (RUMAH SAKIT UMUM DAERAH BAKTI PAJAJARAN)','keuangan_rsud_cibinong_rsud_bakti_pajajaran','keuangan_rsud_cibinong.rsud_bakti_pajajaran@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',68,171),(242,'Admin BAGIAN TATA USAHA RSUD CIBINONG (RUMAH SAKIT UMUM DAERAH BAKTI PAJAJARAN)','tata_usaha_rsud_rsud_bakti_pajajaran','tata_usaha_rsud.rsud_bakti_pajajaran@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',68,172),(243,'Admin BIDANG ADMINISTRASI (RUMAH SAKIT UMUM DAERAH BAKTI PAJAJARAN)','administrasi_rsud_bakti_pajajaran','administrasi.rsud_bakti_pajajaran@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',68,173),(244,'Admin BIDANG MEDIK RSUD CIBINONG (RUMAH SAKIT UMUM DAERAH BAKTI PAJAJARAN)','medik_rsud_cibinong_rsud_bakti_pajajaran','medik_rsud_cibinong.rsud_bakti_pajajaran@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',68,174),(245,'Admin BIDANG PELAYANAN (RUMAH SAKIT UMUM DAERAH BAKTI PAJAJARAN)','pelayanan_rsud_bakti_pajajaran','pelayanan.rsud_bakti_pajajaran@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',68,175),(246,'Admin RUMAH SAKIT UMUM DAERAH DR. KH. IDHAM CHALID',NULL,'rumah_sakit_umum_daerah_dr_kh_idham_chalid@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',69,NULL),(247,'Admin BAGIAN KEUANGAN (RUMAH SAKIT UMUM DAERAH DR. KH. IDHAM CHALID)','keuangan_rsud_dr_kh_idham','keuangan.rsud_dr_kh_idham@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',69,176),(248,'Admin BAGIAN TATA USAHA (RUMAH SAKIT UMUM DAERAH DR. KH. IDHAM CHALID)','tata_usaha_rsud_dr_kh_idham','tata_usaha.rsud_dr_kh_idham@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',69,177),(249,'Admin BIDANG ADMINISTRASI (RUMAH SAKIT UMUM DAERAH DR. KH. IDHAM CHALID)','administrasi_rsud_dr_kh_idham','administrasi.rsud_dr_kh_idham@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',69,178),(250,'Admin BIDANG KEPERAWATAN (RUMAH SAKIT UMUM DAERAH DR. KH. IDHAM CHALID)','keperawatan_rsud_dr_kh_idham','keperawatan.rsud_dr_kh_idham@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',69,179),(251,'Admin BIDANG MEDIK (RUMAH SAKIT UMUM DAERAH DR. KH. IDHAM CHALID)','medik_rsud_dr_kh_idham','medik.rsud_dr_kh_idham@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',69,180),(252,'Admin BIDANG PELAYANAN (RUMAH SAKIT UMUM DAERAH DR. KH. IDHAM CHALID)','pelayanan_rsud_dr_kh_idham','pelayanan.rsud_dr_kh_idham@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',69,181),(253,'Admin RUMAH SAKIT UMUM DAERAH R. MOH. NOH NUR',NULL,'rumah_sakit_umum_daerah_r_moh_noh_nur@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',70,NULL),(254,'Admin BAGIAN KEUANGAN RSUD LEUWILIANG (RUMAH SAKIT UMUM DAERAH R. MOH. NOH NUR)','keuangan_rsud_leuwiliang_rsud_r_moh_noh','keuangan_rsud_leuwiliang.rsud_r_moh_noh@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',70,182),(255,'Admin BAGIAN TATA USAHA RSUD LEUWILIANG (RUMAH SAKIT UMUM DAERAH R. MOH. NOH NUR)','tata_usaha_rsud_rsud_r_moh_noh','tata_usaha_rsud.rsud_r_moh_noh@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',70,183),(256,'Admin BIDANG KEPERAWATAN RSUD LEUWILIANG (RUMAH SAKIT UMUM DAERAH R. MOH. NOH NUR)','keperawatan_rsud_leuwiliang_rsud_r_moh_noh','keperawatan_rsud_leuwiliang.rsud_r_moh_noh@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',70,184),(257,'Admin BIDANG MEDIK RSUD LEUWILIANG (RUMAH SAKIT UMUM DAERAH R. MOH. NOH NUR)','medik_rsud_leuwiliang_rsud_r_moh_noh','medik_rsud_leuwiliang.rsud_r_moh_noh@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',70,185),(258,'Admin BIDANG PELAYANAN (RUMAH SAKIT UMUM DAERAH R. MOH. NOH NUR)','pelayanan_rsud_r_moh_noh','pelayanan.rsud_r_moh_noh@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',70,186),(259,'Admin RUMAH SAKIT UMUM DAERAH RH. SATIBI',NULL,'rumah_sakit_umum_daerah_rh_satibi@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',71,NULL),(260,'Admin BAGIAN KEUANGAN (RUMAH SAKIT UMUM DAERAH RH. SATIBI)','keuangan_rsud_rh_satibi','keuangan.rsud_rh_satibi@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',71,187),(261,'Admin BAGIAN TATA USAHA (RUMAH SAKIT UMUM DAERAH RH. SATIBI)','tata_usaha_rsud_rh_satibi','tata_usaha.rsud_rh_satibi@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',71,188),(262,'Admin BIDANG ADMINISTRASI (RUMAH SAKIT UMUM DAERAH RH. SATIBI)','administrasi_rsud_rh_satibi','administrasi.rsud_rh_satibi@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',71,189),(263,'Admin BIDANG KEPERAWATAN (RUMAH SAKIT UMUM DAERAH RH. SATIBI)','keperawatan_rsud_rh_satibi','keperawatan.rsud_rh_satibi@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',71,190),(264,'Admin BIDANG MEDIK (RUMAH SAKIT UMUM DAERAH RH. SATIBI)','medik_rsud_rh_satibi','medik.rsud_rh_satibi@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',71,191),(265,'Admin BIDANG PELAYANAN (RUMAH SAKIT UMUM DAERAH RH. SATIBI)','pelayanan_rsud_rh_satibi','pelayanan.rsud_rh_satibi@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',71,192),(266,'Admin SATUAN POLISI PAMONG PRAJA',NULL,'satuan_polisi_pamong_praja@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',72,NULL),(267,'Admin BIDANG KETERTIBAN UMUM (SATUAN POLISI PAMONG PRAJA)','ketertiban_umum_satuan_polisi_pamong','ketertiban_umum.satuan_polisi_pamong@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',72,193),(268,'Admin BIDANG PEMBINAAN (SATUAN POLISI PAMONG PRAJA)','pembinaan_satuan_polisi_pamong','pembinaan.satuan_polisi_pamong@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',72,194),(269,'Admin BIDANG PENEGAKAN PERUNDANG-UNDANGAN DAERAH (SATUAN POLISI PAMONG PRAJA)','penegakan_perundang_undangan_satuan_polisi_pamong','penegakan_perundang_undangan.satuan_polisi_pamong@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',72,195),(270,'Admin BIDANG PERLINDUNGAN MASYARAKAT (SATUAN POLISI PAMONG PRAJA)','perlindungan_masyarakat_satuan_polisi_pamong','perlindungan_masyarakat.satuan_polisi_pamong@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',72,196),(271,'Admin SEKRETARIAT DAERAH',NULL,'sekretariat_daerah@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',73,NULL),(272,'Admin BAGIAN ADMINISTRASI PEMBANGUNAN (SEKRETARIAT DAERAH)','administrasi_pembangunan_daerah','administrasi_pembangunan.daerah@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',73,197),(273,'Admin BAGIAN KERJASAMA DAN BANTUAN HUKUM (SEKRETARIAT DAERAH)','kerjasama_dan_bantuan_daerah','kerjasama_dan_bantuan.daerah@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',73,198),(274,'Admin BAGIAN KESEJAHTERAAN RAKYAT (SEKRETARIAT DAERAH)','kesejahteraan_rakyat_daerah','kesejahteraan_rakyat.daerah@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',73,199),(275,'Admin BAGIAN ORGANISASI (SEKRETARIAT DAERAH)','organisasi_daerah','organisasi.daerah@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',73,200),(276,'Admin BAGIAN PENGADAAN BARANG/JASA (SEKRETARIAT DAERAH)','pengadaan_barangjasa_daerah','pengadaan_barangjasa.daerah@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',73,201),(277,'Admin BAGIAN PEREKONOMIAN (SEKRETARIAT DAERAH)','perekonomian_daerah','perekonomian.daerah@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',73,202),(278,'Admin BAGIAN PERENCANAAN DAN KEUANGAN (SEKRETARIAT DAERAH)','perencanaan_dan_keuangan_daerah','perencanaan_dan_keuangan.daerah@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',73,203),(279,'Admin BAGIAN PERUNDANG-UNDANGAN (SEKRETARIAT DAERAH)','perundang_undangan_daerah','perundang_undangan.daerah@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',73,204),(280,'Admin BAGIAN PROTOKOL DAN KOMUNIKASI PIMPINAN (SEKRETARIAT DAERAH)','protokol_dan_komunikasi_daerah','protokol_dan_komunikasi.daerah@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',73,205),(281,'Admin BAGIAN SUMBER DAYA ALAM (SEKRETARIAT DAERAH)','sumber_daya_alam_daerah','sumber_daya_alam.daerah@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',73,206),(282,'Admin BAGIAN TATA PEMERINTAHAN (SEKRETARIAT DAERAH)','tata_pemerintahan_daerah','tata_pemerintahan.daerah@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',73,207),(283,'Admin BAGIAN UMUM (SEKRETARIAT DAERAH)','umum_daerah','umum.daerah@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',73,208),(284,'Admin SEKRETARIAT DPRD',NULL,'sekretariat_dprd@dinas.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','dinas','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',74,NULL),(285,'Admin BAGIAN FASILTASI PENGANGGARAN DAN PENGAWASAN (SEKRETARIAT DPRD)','fasiltasi_penganggaran_dan_dprd','fasiltasi_penganggaran_dan.dprd@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',74,209),(286,'Admin BAGIAN PERSIDANGAN DAN PERUNDANG-UNDANGAN (SEKRETARIAT DPRD)','persidangan_dan_perundang_dprd','persidangan_dan_perundang.dprd@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',74,210),(287,'Admin BAGIAN PROGRAM DAN KEUANGAN (SEKRETARIAT DPRD)','program_dan_keuangan_dprd','program_dan_keuangan.dprd@bidang.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qj4gsuiEsA0yvCe6FO.P8uzGeHtAqlgSm0x6H7qLFoZ3WUjkOTTde','bidang','A','aktif',NULL,NULL,NULL,NULL,NULL,'2026-09-09 06:09:39','2026-09-09 06:09:39',74,211);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-09-14  1:10:07
