<?php
include("../lib/mysql_pdo.php");
include("../lib/mysql_connect.php");

try {
    $db->exec("TRUNCATE TABLE temp_transaksi WHERE status = 'CURRENT'");
    echo "Transaksi berhasil dikosongkan.";
} catch (PDOException $e) {
    echo "Gagal mengosongkan transaksi: " . $e->getMessage();
}
?>
