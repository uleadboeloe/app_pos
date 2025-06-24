<?php
    include("../lib/mysql_pdo.php");
    include("../lib/mysql_connect.php");

    $nomorAkhir = $_GET['nomor_akhir'];
    $kodeStore = $_GET['kode_store'];
    $kodeKasir = $_GET['kode_kasir'];

    try {
        $query = <<<SQL
            UPDATE dbo_noseries SET nomor_akhir = :nomor_akhir WHERE kode_store = :kode_store AND kode_kasir = :kode_kasir
        SQL;

        $stmt = $db->prepare($query);
        $stmt->execute([
            'nomor_akhir' => $nomorAkhir,
            'kode_store' => $kodeStore,
            'kode_kasir' => $kodeKasir
        ]);

        //echo "Nomor akhir berhasil diperbarui.";
        echo "success";
    } catch (PDOException $e) {
        echo "Gagal memperbarui nomor akhir: " . $e->getMessage();
    }

?>