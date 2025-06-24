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

-- Dumping structure for table insanpos_db.dbo_payment
CREATE TABLE IF NOT EXISTS `dbo_payment` (
  `noid` bigint NOT NULL AUTO_INCREMENT,
  `random_code` varchar(16) DEFAULT NULL,
  `kode_store` varchar(20) DEFAULT NULL,
  `no_struk` varchar(100) DEFAULT NULL,
  `tanggal` datetime DEFAULT NULL,
  `jenis_bayar` enum('CASH','KREDIT CARD','DEBIT CARD','EWALLET','VOUCHER','POINT') DEFAULT NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
