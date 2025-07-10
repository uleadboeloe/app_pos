<?php
    include("../lib/mysql_pdo.php");
    include("../lib/mysql_connect.php");
    include("../lib/general_lib.php");

    if(isset($_POST['status'])) {
        $status = $_POST['status'];
        // Query untuk menjumlahkan total_harga berdasarkan order_no
        $stmt = $db->prepare("SELECT SUM(total_harga) AS total FROM temp_transaksi WHERE status = :status");
        $stmt->bindParam(':status', $status);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Jika hasil ditemukan, kirim total, jika tidak, kirim 0
        $total = $result['total'] ?? 0;
        echo $total;
    } else {
        echo "Invalid request";
    }
?>
