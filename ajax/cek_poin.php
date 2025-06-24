<?php
include("../lib/mysql_pdo.php");
include("../lib/mysql_connect.php");
include("../lib/general_lib.php");

if(isset($_POST['member_id'])) {
    $member_id = $_POST['member_id'];

    // Query untuk menjumlahkan total_poin berdasarkan member_id
    $stmt = $db->prepare("SELECT SUM(nilai_poin) AS total FROM dbo_poin_member WHERE member_id = :member_id");
    //SELECT SUM(nilai_poin) AS total FROM dbo_poin_member WHERE member_id = '00234007'
    $stmt->bindParam(':member_id', $member_id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Jika hasil ditemukan, kirim total, jika tidak, kirim 0
    $totalpoin = $result['total'] ?? 0;
    $totalpoin = roundCustom($totalpoin);

    echo formatRupiah($totalpoin);
} else {
    $totalpoin = 0;
    echo formatRupiah($totalpoin);
}
?>