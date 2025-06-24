<?php
include("../lib/mysql_pdo.php");
include("../lib/mysql_connect.php");

$id = $_POST['id']; // ID dari temp_transaksi

try {
    $stmt = $pdo->prepare("DELETE FROM temp_transaksi WHERE id = :id");
    $stmt->execute([':id' => $id]);

    echo "Barang berhasil dihapus dari transaksi.";
} catch (PDOException $e) {
    echo "Gagal menghapus barang: " . $e->getMessage();
}
?>
