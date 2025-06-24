<?php
include_once "library/connection.php";
include_once "library/parameter.php";
include_once "library/fungsi.php";

function readPromoOutputFromUrl($url) {
    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'timeout' => 30
        ]
    ]);
    $output = @file_get_contents($url, false, $context);
    return $output !== false ? $output : false;
}

$strCleanHeader="TRUNCATE TABLE dbo_promo";
$executeSQL=mysqli_query($koneksidb, $strCleanHeader); 

$strCleanDetail="TRUNCATE TABLE dbo_promo_detail";
$executeSQL=mysqli_query($koneksidb, $strCleanDetail); 

$urlOutputHeader = readPromoOutputFromUrl("https://service.amanmart.my.id/current.promo.php");
if ($urlOutputHeader !== false) {
    //echo $urlOutput;

    $data2 = json_decode($urlOutputHeader, true);
    if (!is_array($data2)) {
        return false;
    }

    foreach ($data2 as $header) {
        // Pastikan field sesuai dengan struktur tabel dan JSON
        $h_random_code = $header['random_code'];
        $h_kode_promo = $header['kode_promo'];
        $h_promo_name = $header['promo_name'];
        $h_promo_desc = $header['promo_desc'];
        $h_promo_start_date = $header['promo_start_date'];
        $h_promo_end_date = $header['promo_end_date'];
        $h_fl_promo_day = $header['fl_promo_day'];
        $h_promo_day = $header['promo_day'];
        $h_promo_type = $header['promo_type'];
        $h_promo_images = $header['promo_images'];
        $h_url_promo = $header['url_promo'];
        $h_banner_header = $header['banner_header'];
        $h_fl_free_item = $header['fl_free_item'];
        $h_free_item = $header['free_item'];
        $h_qty_free_item = $header['qty_free_item'];
        $h_promo_parameter = $header['promo_parameter'];
        $h_kriteria_promo = $header['kriteria_promo'];
        $h_kriteria_value = $header['kriteria_value'];
        $h_value_promo = $header['value_promo'];
        $h_fl_active = $header['fl_active'];
        $h_posting_date = $header['posting_date'];
        $h_posting_user = $header['posting_user'];
        $h_fl_delete = $header['fl_delete'];
        $h_delete_user = $header['delete_user'];
        $h_delete_date = $header['delete_date'];
        $h_kode_store = $header['kode_store'];
        $h_kode_barcode_induk = $header['kode_barcode_induk'];

        if(($h_kode_store = "") || ($h_kode_store = $KodeStoreOffline)){
            if($h_kode_store = ""){
                $h_kode_store = $KodeStoreOffline;
            }
            $strInsertHeader="INSERT INTO dbo_promo(
            `random_code`,`kode_promo`,`promo_name`,`promo_desc`,`promo_start_date`,`promo_end_date`,`fl_promo_day`,`promo_day`,`promo_type`,
            `promo_images`,`url_promo`,`banner_header`,`fl_free_item`,`promo_parameter`,`kriteria_promo`,
            `kriteria_value`,`value_promo`,`free_item`,`qty_free_item`,`posting_date`,
            `posting_user`,`kode_store`,`fl_active`,`kode_barcode_induk`) VALUES (
            '$h_random_code','$h_kode_promo','$h_promo_name','$h_promo_desc','$h_promo_start_date','$h_promo_end_date','$h_fl_promo_day','$h_promo_day','$h_promo_type',
            '$h_promo_images','$h_url_promo','$h_banner_header','$h_fl_free_item','$h_promo_parameter','$h_kriteria_promo',
            '$h_kriteria_value','$h_value_promo','$h_free_item','$h_qty_free_item','$datedb','SYSTEM','$h_kode_store','$h_fl_active','$h_kode_barcode_induk')";
            $executeSQL=mysqli_query($koneksidb, $strInsertHeader); 
            //echo $strInsertHeader . "<br>";
        }


    }
}
// Contoh penggunaan dari URL:
$urlOutputDetail = readPromoOutputFromUrl("https://service.amanmart.my.id/current.promo.detail.php");
if ($urlOutputDetail !== false) {
    //echo $urlOutput;

    $data1 = json_decode($urlOutputDetail, true);
    if (!is_array($data1)) {
        return false;
    }

    foreach ($data1 as $detail) {
        // Pastikan field sesuai dengan struktur tabel dan JSON
        $d_random_code = $detail['random_code'];
        $d_barcode = $detail['barcode'];
        $d_kode_promo = $detail['kode_promo'];
        $d_sku_barang = $detail['sku_barang'];
        $d_kode_barang = $detail['kode_barang'];
        $d_uom = $detail['uom'];
        $d_posting_date = $detail['posting_date'];
        $d_qty_jual = $detail['qty_jual'] ?? "0";
        $d_harga_jual = $detail['harga_jual'] ?? "0";
        $d_posting_user = $detail['posting_user'];
        $d_kode_store = $detail['kode_store'];
        $d_fl_active = $detail['fl_active'];

        if(($d_kode_store = "") || ($d_kode_store = $KodeStoreOffline)){
            if($d_kode_store = ""){
                $d_kode_store = $KodeStoreOffline;
            }
            $strInsertDetail="INSERT INTO dbo_promo_detail(`random_code`,`barcode`,`kode_promo`,`sku_barang`,`kode_barang`,`uom`,`posting_date`,`qty_jual`,`harga_jual`,`posting_user`) VALUES ('$d_random_code','$d_barcode','$d_kode_promo','$d_sku_barang','$d_kode_barang','$d_uom','$datedb','$d_qty_jual','$d_harga_jual','SYSTEM')";
            $executeSQL=mysqli_query($koneksidb, $strInsertDetail); 
            //echo $strInsertDetail . "<br>";
        }
    }
}
?>