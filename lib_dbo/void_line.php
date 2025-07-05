<?php
    include("../lib/mysql_pdo.php");
    include("../lib/mysql_connect.php");
    include("../lib/general_lib.php");

    $nostruk = $_GET['nostruk'];
    $kodebarang = $_GET['kodebarang'];
    $qty = $_GET['qty'];
    $value_harga = $_GET['harga'];
    $value_diskon = $_GET['diskon'];
    $value_qtyawal = $_GET['qtyawal'];
    $sku_barang = $_GET['skubarang'];
    $uom_barang = $_GET['uom'];
    $value_qtyawal = $_GET['qtyawal'];
    $selisih_value = $value_qtyawal-$qty;

    $NilaiDiskon = $value_diskon/$value_qtyawal;
    $UpdateDiskon = $NilaiDiskon*$qty;

    $updateTotal = $value_harga*$qty;
    $UpdateNettSales = $updateTotal-$UpdateDiskon;

    $QtyVoid = $value_qtyawal-$qty;
    $GrossVoid = $value_harga*$qty;
    $NilaiVoid = roundCustomDown(($value_harga-$NilaiDiskon)*$selisih_value);
    $NilaiVoidReal = ($value_harga-$NilaiDiskon)*$selisih_value;
    $PembulatanVoid = $NilaiVoidReal-$NilaiVoid;



    function getValueVoid($db, $nostruk) {
        //$stmt = $db->prepare("SELECT kode_barang FROM dbo_barang WHERE barcode = :barcode LIMIT 1");
        $stmt = $db->prepare("SELECT value_void FROM dbo_header WHERE no_struk = :no_struk");
        $stmt->bindParam(':no_struk', $nostruk, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['value_void'] : 0;
    }

    echo "SELISIH VALUE = " . $selisih_value . "<br>";
    echo "SELISIH VALUE = " . $qty . "<br>";
    echo "SELISIH VALUE = " . $nostruk . "<br>";
    echo "SELISIH VALUE = " . $UpdateDiskon . "<br>";
    echo "SELISIH VALUE = " . $updateTotal . "<br>";
    echo "SELISIH VALUE = " . $UpdateNettSales . "<br>";
    echo "SELISIH VALUE = " . $kodebarang . "<br>";
    echo "SELISIH VALUE = " . $uom_barang . "<br>";
    echo "SELISIH VALUE = " . $sku_barang . "<br>";
    $ValueVoidCurrent = getValueVoid($db, $nostruk);
    // Update dbo_detail table
    $queryDetail = "UPDATE dbo_detail 
                    SET qty_voided = qty_voided +:qty_void, 
                        qty_sales = :qty_sales, 
                        var_diskon = :var_diskon, 
                        total_sales = :total_sales, 
                        gross_sales = :gross_sales, 
                        netto_sales = :netto_sales
                    WHERE no_struk = :no_struk AND kode_barang = :kode_barang";
    $stmtDetail = $db->prepare($queryDetail);
    $stmtDetail->execute([
        ':qty_void' => $selisih_value,
        ':qty_sales' => $qty,
        ':no_struk' => $nostruk,
        ':var_diskon' => $UpdateDiskon,
        ':total_sales' => $updateTotal,
        ':gross_sales' => $updateTotal,
        ':netto_sales' => $UpdateNettSales,
        //':kode_barang' => $kodebarang
        ':kode_barang' => $sku_barang
    ]);

    //var_dump($stmtDetail);
    // Update dbo_header table
    //$UpdateValueVoid = $ValueVoidCurrent+$NilaiVoid;
    $queryHeader = "UPDATE dbo_header 
                    SET is_voided = 2, value_void = value_void +:value_void,var_pembulatan = var_pembulatan +:var_pembulatan, kembalian = kembalian +:value_void, total_struk = total_struk -:value_void, var_cash = var_cash -:value_void  
                    WHERE no_struk = :no_struk";
    $stmtHeader = $db->prepare($queryHeader);
    $stmtHeader->execute([
        ':no_struk' => $nostruk,
        ':value_void' => $NilaiVoid,
        ':var_pembulatan' => $PembulatanVoid,
    ]);

    if ($stmtDetail) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }

?>