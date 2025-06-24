-- --------------------------------------------------------
-- Host:                         103.82.241.166
-- Server version:               8.2.0 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.10.0.7000
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for insanpos_db
CREATE DATABASE IF NOT EXISTS `insanpos_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `insanpos_db`;

-- Dumping structure for table insanpos_db.dbo_barang
CREATE TABLE IF NOT EXISTS `dbo_barang` (
  `noid` bigint NOT NULL AUTO_INCREMENT,
  `random_code` varchar(100) DEFAULT NULL,
  `sku_barang` varchar(30) DEFAULT NULL,
  `kode_barang` varchar(30) DEFAULT NULL,
  `barcode` varchar(32) DEFAULT NULL,
  `barcode2` varchar(32) NOT NULL,
  `barcode3` varchar(32) NOT NULL,
  `nama_barang` varchar(180) DEFAULT NULL,
  `keterangan_1` text,
  `keterangan_2` text,
  `harga_jual` double DEFAULT NULL,
  `harga_beli` double DEFAULT NULL,
  `principal` varchar(20) DEFAULT NULL,
  `sub_dept` varchar(10) DEFAULT NULL,
  `dept` varchar(10) DEFAULT NULL,
  `divisi` varchar(10) DEFAULT NULL,
  `vendor_no` varchar(20) DEFAULT NULL,
  `kategori` varchar(50) DEFAULT NULL,
  `isi_kemasan_kecil` double DEFAULT NULL,
  `isi_kemasan_sedang` double DEFAULT NULL,
  `jenis_barang` varchar(100) DEFAULT '-',
  `berat_produk` double DEFAULT NULL,
  `fl_timbang` tinyint(1) DEFAULT '0',
  `url_named` text,
  `uom` varchar(20) DEFAULT NULL,
  `uom2` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `uom3` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `panjang` double DEFAULT NULL,
  `lebar` double DEFAULT NULL,
  `tinggi` double DEFAULT NULL,
  `volume_barang` double DEFAULT NULL,
  `tag_promo` text CHARACTER SET latin1 COLLATE latin1_swedish_ci,
  `kode_tag` bigint DEFAULT NULL,
  `asuransi` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `minimum_pembelian` double DEFAULT '1',
  `jenis_pengiriman` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `kondisi_barang` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `ppn` varchar(10) DEFAULT NULL,
  `fl_update_content` bigint DEFAULT NULL,
  `last_update_price` datetime DEFAULT NULL,
  `fl_active` tinyint(1) DEFAULT '1',
  `posting_date` datetime DEFAULT NULL,
  `posting_user` varchar(50) DEFAULT NULL,
  `fl_delete` tinyint(1) DEFAULT '0',
  `delete_user` varchar(50) DEFAULT NULL,
  `delete_date` datetime DEFAULT NULL,
  PRIMARY KEY (`noid`),
  UNIQUE KEY `no_id` (`noid`)
) ENGINE=InnoDB AUTO_INCREMENT=8400 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table insanpos_db.dbo_departemen
CREATE TABLE IF NOT EXISTS `dbo_departemen` (
  `noid` bigint NOT NULL AUTO_INCREMENT,
  `kode_departemen` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `nama_departemen` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `posting_date` datetime DEFAULT NULL,
  `posting_user` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `fl_active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`noid`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table insanpos_db.dbo_detail
CREATE TABLE IF NOT EXISTS `dbo_detail` (
  `noid` bigint NOT NULL AUTO_INCREMENT,
  `random_code` varchar(16) DEFAULT NULL,
  `no_struk` varchar(100) DEFAULT NULL,
  `sku_barang` varchar(30) DEFAULT NULL,
  `kode_barang` varchar(30) DEFAULT NULL,
  `qty_sales` double DEFAULT NULL,
  `harga` double DEFAULT NULL,
  `satuan` varchar(20) DEFAULT NULL,
  `total_sales` double DEFAULT NULL,
  `kode_store` varchar(20) DEFAULT NULL,
  `var_diskon` double DEFAULT '0',
  `var_ppn` double DEFAULT '0',
  `netto_sales` double DEFAULT '0',
  `posting_date` datetime DEFAULT NULL,
  `posting_user` varchar(50) DEFAULT NULL,
  `qty_voided` double DEFAULT (0),
  `is_promo_item` tinyint DEFAULT (0),
  PRIMARY KEY (`noid`)
) ENGINE=InnoDB AUTO_INCREMENT=104 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table insanpos_db.dbo_global
CREATE TABLE IF NOT EXISTS `dbo_global` (
  `noid` bigint NOT NULL AUTO_INCREMENT,
  `random_code` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `kategori_global` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `parameter` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `label_name` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `posting_date` datetime DEFAULT NULL,
  `posting_user` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `fl_active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`noid`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table insanpos_db.dbo_header
CREATE TABLE IF NOT EXISTS `dbo_header` (
  `noid` bigint NOT NULL AUTO_INCREMENT,
  `random_code` varchar(16) DEFAULT NULL,
  `kode_store` varchar(20) DEFAULT NULL,
  `no_struk` varchar(50) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `jam` time DEFAULT NULL,
  `kode_kasir` varchar(20) DEFAULT NULL,
  `nama_kasir` varchar(50) DEFAULT NULL,
  `total_bayar` double DEFAULT NULL,
  `total_struk` double DEFAULT NULL,
  `kembalian` double DEFAULT NULL,
  `jenis_bayar` enum('CASH','CREDIT CARD','DEBIT CARD','EWALLET','VOUCHER','POINT') CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `nama_kartu` varchar(50) DEFAULT NULL,
  `kode_customer` varchar(20) DEFAULT NULL,
  `var_cash` double DEFAULT NULL,
  `var_noncash` double DEFAULT NULL,
  `var_pembulatan` double DEFAULT NULL,
  `var_diskon` double DEFAULT NULL,
  `status_customer` varchar(50) DEFAULT NULL,
  `nama_customer` varchar(100) DEFAULT NULL,
  `telp_customer` varchar(50) DEFAULT NULL,
  `posting_date` datetime DEFAULT NULL,
  `posting_user` varchar(50) DEFAULT NULL,
  `is_voided` tinyint(1) DEFAULT (0),
  PRIMARY KEY (`noid`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table insanpos_db.dbo_jobtitle
CREATE TABLE IF NOT EXISTS `dbo_jobtitle` (
  `noid` bigint NOT NULL AUTO_INCREMENT,
  `job_title` varchar(50) DEFAULT NULL,
  `description` text,
  `fl_active` tinyint(1) DEFAULT '1',
  `posting_date` datetime DEFAULT NULL,
  `posting_user` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`noid`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table insanpos_db.dbo_kategori
CREATE TABLE IF NOT EXISTS `dbo_kategori` (
  `noid` bigint NOT NULL AUTO_INCREMENT,
  `kode_kategori` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `nama_kategori` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `posting_date` datetime DEFAULT NULL,
  `posting_user` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `fl_active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`noid`)
) ENGINE=InnoDB AUTO_INCREMENT=113 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table insanpos_db.dbo_kecamatan
CREATE TABLE IF NOT EXISTS `dbo_kecamatan` (
  `no_id` double DEFAULT NULL,
  `nama_kecamatan` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `id_kota` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table insanpos_db.dbo_kelurahan
CREATE TABLE IF NOT EXISTS `dbo_kelurahan` (
  `no_id` bigint NOT NULL AUTO_INCREMENT,
  `nama_kelurahan` varchar(100) DEFAULT NULL,
  `id_kecamatan` varchar(20) DEFAULT NULL,
  `kode_pos` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`no_id`)
) ENGINE=InnoDB AUTO_INCREMENT=82348 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table insanpos_db.dbo_kota
CREATE TABLE IF NOT EXISTS `dbo_kota` (
  `no_id` bigint NOT NULL AUTO_INCREMENT,
  `nama_kota` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `provinsi_id` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  PRIMARY KEY (`no_id`)
) ENGINE=InnoDB AUTO_INCREMENT=502 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table insanpos_db.dbo_log
CREATE TABLE IF NOT EXISTS `dbo_log` (
  `noid` bigint NOT NULL AUTO_INCREMENT,
  `log_description` text,
  `log_type` varchar(10) DEFAULT NULL,
  `create_at` datetime DEFAULT NULL,
  `log_user` varchar(50) DEFAULT NULL,
  `source_data` varchar(100) DEFAULT NULL COMMENT 'url/pages yang di akses',
  `source_system` varchar(100) DEFAULT NULL COMMENT 'fungsi yang di panggil',
  `variabel1` varchar(100) DEFAULT NULL COMMENT 'variabel',
  `variabel2` varchar(100) DEFAULT NULL,
  `variabel3` varchar(100) DEFAULT NULL,
  `variabel4` varchar(100) DEFAULT NULL,
  `variabel5` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`noid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table insanpos_db.dbo_log_process
CREATE TABLE IF NOT EXISTS `dbo_log_process` (
  `noid` bigint NOT NULL AUTO_INCREMENT,
  `kode_store` varchar(100) DEFAULT NULL,
  `tanggal_log` date DEFAULT NULL,
  `jum_header` double DEFAULT NULL,
  `jum_detail` double DEFAULT NULL,
  `posting_date` datetime DEFAULT NULL,
  `fl_proses_file` tinyint(1) DEFAULT '0',
  `proses_date` datetime DEFAULT NULL,
  `send_file_date` datetime DEFAULT NULL,
  PRIMARY KEY (`noid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table insanpos_db.dbo_message
CREATE TABLE IF NOT EXISTS `dbo_message` (
  `noid` bigint NOT NULL AUTO_INCREMENT,
  `error_code` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `error_msg` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `error_type` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `error_description` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `fl_active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`noid`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table insanpos_db.dbo_noseries
CREATE TABLE IF NOT EXISTS `dbo_noseries` (
  `noid` bigint NOT NULL AUTO_INCREMENT,
  `kode_store` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `kode_noseries` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `kode_prefix` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `nomor_akhir` double DEFAULT NULL,
  `modul_pakai` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `fl_active` tinyint(1) DEFAULT '1',
  `posting_date` datetime DEFAULT NULL,
  `posting_user` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  PRIMARY KEY (`noid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table insanpos_db.dbo_payment
CREATE TABLE IF NOT EXISTS `dbo_payment` (
  `noid` bigint NOT NULL AUTO_INCREMENT,
  `random_code` varchar(16) DEFAULT NULL,
  `kode_store` varchar(20) DEFAULT NULL,
  `no_struk` varchar(100) DEFAULT NULL,
  `tanggal` datetime DEFAULT NULL,
  `jenis_bayar` enum('CASH','CREDIT CARD','DEBIT CARD','EWALLET','VOUCHER','POINT') CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `total_bayar` double DEFAULT NULL,
  `kode_edc` varchar(20) DEFAULT NULL,
  `nama_edc` varchar(50) DEFAULT NULL,
  `nama_bank` varchar(20) DEFAULT NULL,
  `reff_code` varchar(20) DEFAULT NULL,
  `approval_code` varchar(20) DEFAULT NULL,
  `card_number` varchar(16) DEFAULT NULL,
  `vcc_code` varchar(4) DEFAULT NULL,
  `posting_date` datetime DEFAULT NULL,
  `posting_user` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`noid`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table insanpos_db.dbo_price
CREATE TABLE IF NOT EXISTS `dbo_price` (
  `noid` bigint NOT NULL AUTO_INCREMENT,
  `random_code` varchar(100) DEFAULT NULL,
  `sku_barang` varchar(30) DEFAULT NULL,
  `kode_barang` varchar(30) DEFAULT NULL,
  `barcode` varchar(50) DEFAULT NULL,
  `uom` varchar(10) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `harga_jual` double DEFAULT NULL,
  `fl_active` tinyint(1) DEFAULT '1',
  `posting_date` datetime DEFAULT NULL,
  `posting_user` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`noid`)
) ENGINE=InnoDB AUTO_INCREMENT=20847 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table insanpos_db.dbo_promo
CREATE TABLE IF NOT EXISTS `dbo_promo` (
  `noid` bigint NOT NULL AUTO_INCREMENT,
  `random_code` varchar(100) DEFAULT NULL,
  `kode_promo` varchar(10) DEFAULT NULL,
  `promo_name` varchar(100) DEFAULT NULL,
  `promo_desc` text,
  `promo_start_date` date DEFAULT NULL,
  `promo_end_date` date DEFAULT NULL,
  `fl_promo_day` tinyint(1) DEFAULT '0',
  `promo_day` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `promo_type` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `promo_images` varchar(150) DEFAULT NULL,
  `url_promo` varchar(100) DEFAULT NULL,
  `banner_header` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `fl_free_item` tinyint(1) DEFAULT '0',
  `free_item` varchar(100) DEFAULT '0',
  `qty_free_item` double DEFAULT NULL,
  `promo_parameter` varchar(4) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `kriteria_promo` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `kriteria_value` double DEFAULT NULL,
  `value_promo` double DEFAULT NULL,
  `fl_active` tinyint(1) DEFAULT '1',
  `posting_date` datetime DEFAULT NULL,
  `posting_user` varchar(50) DEFAULT NULL,
  `fl_delete` tinyint(1) DEFAULT '0',
  `delete_user` varchar(50) DEFAULT NULL,
  `delete_date` datetime DEFAULT NULL,
  `kode_store` varchar(10) DEFAULT NULL,
  `kode_barcode_induk` varchar(50) DEFAULT '-',
  PRIMARY KEY (`noid`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table insanpos_db.dbo_promo_detail
CREATE TABLE IF NOT EXISTS `dbo_promo_detail` (
  `noid` bigint NOT NULL AUTO_INCREMENT,
  `random_code` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `barcode` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `kode_promo` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `sku_barang` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `kode_barang` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `uom` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `posting_date` datetime DEFAULT NULL,
  `qty_jual` double DEFAULT NULL,
  `harga_jual` double DEFAULT NULL,
  `posting_user` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  PRIMARY KEY (`noid`)
) ENGINE=InnoDB AUTO_INCREMENT=115 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table insanpos_db.dbo_provinsi
CREATE TABLE IF NOT EXISTS `dbo_provinsi` (
  `no_id` bigint NOT NULL,
  `nama_provinsi` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `posting_date` datetime DEFAULT NULL,
  `posting_user` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  PRIMARY KEY (`no_id`),
  UNIQUE KEY `no_id` (`no_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table insanpos_db.dbo_store
CREATE TABLE IF NOT EXISTS `dbo_store` (
  `noid` bigint NOT NULL AUTO_INCREMENT,
  `random_code` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `kode_store` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `nama_store` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `alamat_store` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `provinsi` bigint NOT NULL,
  `kota` bigint NOT NULL,
  `kecamatan` bigint NOT NULL,
  `kode_pos` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
  `no_kontak` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `coord_long` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `coord_lat` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `posting_date` datetime NOT NULL,
  `posting_user` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `fl_active` tinyint(1) NOT NULL DEFAULT '1',
  `header_struk` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `footer_struk` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `line_text` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  PRIMARY KEY (`noid`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table insanpos_db.dbo_subkategori
CREATE TABLE IF NOT EXISTS `dbo_subkategori` (
  `noid` bigint NOT NULL AUTO_INCREMENT,
  `kode_subkategori` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `nama_subkategori` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `posting_date` datetime DEFAULT NULL,
  `posting_user` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `fl_active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`noid`)
) ENGINE=InnoDB AUTO_INCREMENT=424 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table insanpos_db.dbo_user
CREATE TABLE IF NOT EXISTS `dbo_user` (
  `noid` bigint NOT NULL AUTO_INCREMENT,
  `random_code` varchar(16) DEFAULT NULL,
  `userid` varchar(100) DEFAULT NULL,
  `userpass` varchar(16) DEFAULT NULL,
  `menu_id` int DEFAULT NULL,
  `kode_kasir` int DEFAULT NULL,
  `nama_user` varchar(100) DEFAULT NULL,
  `alamat_user` varchar(100) DEFAULT NULL,
  `nomor_kontak` varchar(50) DEFAULT NULL,
  `nomor_identitas` varchar(50) DEFAULT NULL,
  `job_title` varchar(50) DEFAULT NULL,
  `alamat_email` varchar(100) DEFAULT NULL,
  `hak_akses` int DEFAULT NULL,
  `fl_active` tinyint(1) DEFAULT '1',
  `posting_date` datetime DEFAULT NULL,
  `posting_user` varchar(50) DEFAULT NULL,
  `fl_delete` tinyint(1) DEFAULT '0',
  `delete_date` datetime DEFAULT NULL,
  `delete_user` varchar(50) DEFAULT NULL,
  `kode_store` varchar(10) DEFAULT NULL,
  `approval_code` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`noid`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table insanpos_db.temp_transaksi
CREATE TABLE IF NOT EXISTS `temp_transaksi` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `order_no` varchar(50) DEFAULT NULL,
  `kode_barang` varchar(30) DEFAULT NULL,
  `nama_barang` varchar(180) DEFAULT NULL,
  `harga_jual` double DEFAULT '0',
  `uom` varchar(10) DEFAULT NULL,
  `qty` double DEFAULT '1',
  `disc` double DEFAULT '0',
  `total_harga` double DEFAULT '0',
  `status` enum('CURRENT','ONHOLD','PAID') DEFAULT 'CURRENT',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
