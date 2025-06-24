<?php
    include("../lib/mysql_pdo.php");
    include("../lib/mysql_connect.php");

    $nostruk = $_GET['nostruk'];
    $kodebarang = $_GET['kodebarang'];
    $qty = $_GET['qty'];

    // Update dbo_detail table
    $queryDetail = "UPDATE dbo_detail 
                    SET qty_voided = qty_sales - :qty 
                    WHERE no_struk = :no_struk AND kode_barang = :kode_barang";
    $stmtDetail = $db->prepare($queryDetail);
    $stmtDetail->execute([
        ':qty' => $qty,
        ':no_struk' => $nostruk,
        ':kode_barang' => $kodebarang
    ]);

    // Update dbo_header table
    $queryHeader = "UPDATE dbo_header 
                    SET is_voided = 2    
                    WHERE no_struk = :no_struk";
    $stmtHeader = $db->prepare($queryHeader);
    $stmtHeader->execute([
        ':no_struk' => $nostruk
    ]);
?>