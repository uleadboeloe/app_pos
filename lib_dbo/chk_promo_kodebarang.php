<?php
    include("../lib/mysql_pdo.php");
    include("../lib/mysql_connect.php");

    $kode_barang = $_GET['kodebarang'];

   // Cek apakah ada kode promo untuk barang ini
    $sql = "SELECT kode_barang, kode_promo FROM dbo_promo_detail WHERE kode_barang = :kode_barang";
    $stmt = $db->prepare($sql);
    $stmt->execute([':kode_barang' => $kode_barang]);
    $promo = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($promo) {
        $kode_promo = $promo['kode_promo'];

        $hari_ini_index = date('N'); // Mendapatkan index hari (1 = Senin, 7 = Minggu)
        $hari_array = ['SENIN', 'SELASA', 'RABU', 'KAMIS', 'JUMAT', 'SABTU', 'MINGGU'];
        $hari_ini = '%' . $hari_array[$hari_ini_index - 1] . '%'; // Menggunakan wildcard untuk LIKE
        
        // Cek apakah promo masih berlaku
        $sql = "SELECT * 
            FROM dbo_promo 
            WHERE kode_promo = :kode_promo 
            AND CURDATE() BETWEEN promo_start_date AND promo_end_date 
            AND promo_day LIKE :hari_ini";
        $stmt = $db->prepare($sql);
        $stmt->execute([':kode_promo' => $kode_promo, ':hari_ini' => $hari_ini]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) 
            echo json_encode(['ada_promo' => 1]);
        else
            echo json_encode(['ada_promo' => 0]);
    }
    else
        echo json_encode(['ada_promo' => 0]);

?>
