<?php
include("../lib/mysql_pdo.php");
include("../lib/mysql_connect.php");

$order_no = $_GET['order_no'];
$memberid = $_GET['memberid']; // 'ONHOLD' atau 'PAID' atau 'VOID'

try {
    $sql = "UPDATE temp_transaksi SET kode_cust = :kode_cust WHERE order_no = :order_no";
    $stmt = $db->prepare($sql);
    $stmt->execute([
        ':status' => $memberid,
        ':order_no' => $order_no
    ]);

    echo "Transaksi berhasil diupdate menjadi $status.";
} catch (PDOException $e) {
    echo "Gagal update status: " . $e->getMessage();
}
?>
