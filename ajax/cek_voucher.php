<?php
include("../lib/mysql_pdo.php");
include("../lib/mysql_connect.php");
include("../lib/general_lib.php");

if(isset($_POST['kode_voucher'])) {
    $kode_voucher = $_POST['kode_voucher'];

    // Query untuk menjumlahkan nilai voucher berdasarkan kode_voucher
    $stmt = $db->prepare("SELECT nominal_voucher FROM dbo_voucher WHERE kode_voucher = :kode_voucher");
    //SELECT nominal FROM dbo_voucher WHERE kode_voucher = '170809469642a16e-0003'
    $stmt->bindParam(':kode_voucher', $kode_voucher);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Jika hasil ditemukan, kirim total, jika tidak, kirim 0
    $nominal = $result['nominal_voucher'] ?? 0;
    $nominal = roundCustom($nominal);

    echo formatRupiah($nominal);
} else {
    $nominal = 0;
    echo formatRupiah($nominal);
}
?>