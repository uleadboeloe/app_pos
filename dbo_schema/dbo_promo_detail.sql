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

-- Dumping structure for table insanpos_db.dbo_promo_detail
DROP TABLE IF EXISTS `dbo_promo_detail`;
CREATE TABLE IF NOT EXISTS `dbo_promo_detail` (
  `noid` bigint NOT NULL AUTO_INCREMENT,
  `random_code` varchar(50) DEFAULT NULL,
  `barcode` varchar(50) DEFAULT NULL,
  `kode_promo` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `sku_barang` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `kode_barang` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `uom` varchar(10) DEFAULT NULL,
  `posting_date` datetime DEFAULT NULL,
  PRIMARY KEY (`noid`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table insanpos_db.dbo_promo_detail: ~4 rows (approximately)
INSERT INTO `dbo_promo_detail` (`noid`, `random_code`, `barcode`, `kode_promo`, `sku_barang`, `kode_barang`, `uom`, `posting_date`) VALUES
	(1, 'd9e348eb8dcaeba4', '89997770164271', 'PRM1', '5232', '5232', 'PCS', '2025-04-05 17:49:01'),
	(2, 'd68e737180f99231', '89927725860231', 'PRM2', '1000001', '1000001', 'PCS', '2025-04-05 17:50:20'),
	(3, '8a4e80451d530d38', '8999898969211', 'PRM3', '2700429', '2700429', 'PCS', '2025-04-05 17:51:13'),
	(4, '8208de0374722d78', '9789792526394', 'PRM4', '3002457', '3002457', 'PCS', '2025-04-05 17:51:49');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
