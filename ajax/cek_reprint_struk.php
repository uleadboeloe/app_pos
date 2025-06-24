<?php
include("../lib/mysql_pdo.php");
include("../lib/mysql_connect.php");
include("../lib/general_lib.php");

if(isset($_POST['no_struk'])) {
    $no_struk = $_POST['no_struk'];
    $userid = $_POST['user_id'];

    // Query untuk menjumlahkan nilai voucher berdasarkan kode_voucher
    $stmt = $db->prepare("SELECT no_struk FROM dbo_header WHERE no_struk = :no_struk and kode_kasir = :userid and is_voided in('0','2')");
    //SELECT nominal FROM dbo_voucher WHERE kode_voucher = '170809469642a16e-0003'
    $stmt->bindParam(':no_struk', $no_struk);
    $stmt->bindParam(':userid', $userid);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $count = $stmt->rowCount();
    //echo "Jumlah record: " . $count . "\n";

    // Jika hasil ditemukan, kirim total, jika tidak, kirim 0
    $randomcode = $result['no_struk'] ?? "";

    if($count > 0) {
        //echo "Jumlah record: " . $count . "\n";
        echo $randomcode;
    } else {
        //echo "Tidak ada record ditemukan.\n";
        $randomcode = "Tidak Ditemukan / Sudah Divoid";
        echo $randomcode . "\n";
    }

    //echo $count;
} else {
    echo "Invalid request";
}
?>