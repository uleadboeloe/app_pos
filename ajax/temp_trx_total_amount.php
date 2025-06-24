<?php
    include("../lib/mysql_pdo.php");
    include("../lib/mysql_connect.php");
    include("../lib/general_lib.php");

    if(isset($_POST['order_no'])) {
        $order_no = $_POST['order_no'];
        $point_member = isset($_POST['pointmember']) ? $_POST['pointmember'] : 0;
        $nilai_voucher = isset($_POST['nilaivoucher']) ? $_POST['nilaivoucher'] : 0;

        // Query untuk menjumlahkan total_harga berdasarkan order_no
        $stmt = $db->prepare("SELECT SUM(total_harga) AS total FROM temp_transaksi WHERE order_no = :order_no");
        $stmt->bindParam(':order_no', $order_no);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Jika hasil ditemukan, kirim total, jika tidak, kirim 0
        $total = $result['total'] ?? 0;
        $total = $total + ($total * $ppn) - $point_member - $nilai_voucher;
        if($total < 0) $total = 0;
        $total = roundCustom($total);

        echo $total;
    } else {
        echo "Invalid request";
    }
?>
