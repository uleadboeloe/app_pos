<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include_once "library/connection.php";
include_once "library/parameter.php";
include_once "library/fungsi.php";

if(isset($_SESSION['SESS_user_id'])){
    $StrViewQuery="SELECT * from dbo_barang where fl_active=1";
    //$StrViewQuery="SELECT * from dbo_barang where fl_timbang=0 and nama_barang like '%Wardah%'";
    //echo $StrSalesDetails . "<hr>";     
    $callStrViewQuery=mysqli_query($koneksidb, $StrViewQuery);
    while($recView=mysqli_fetch_array($callStrViewQuery))
    {
        $varNoid = $recView['noid'];
        $varBarcode = $recView['barcode'];
        $varBarcode2 = $recView['barcode2'];
        $varBarcode3 = $recView['barcode3'];
        echo $varNoid . " - " . $varBarcode . " - " . $varBarcode2 . " - " . $varBarcode3 . "<br>";

        /*CREATEBARCODE*/
        if($varBarcode != ""){
            // Deteksi apakah protokolnya http atau https
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
            // Buat URL ke barcode generator
            $file_gambar = $protocol . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/barcode.php?text=" . urlencode($varBarcode) . "&print=true&size=50&orientation=horizontal&code_type=code128";
            $folderPath = __DIR__ . '/qrcode_produk/barcodes/';
            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0777, true); // buat folder jika belum ada
            }
            $filename = $folderPath . $varBarcode . '.png';
            $FileBarcode = "qrcode_produk/barcodes/" . $varBarcode . ".png";
            // Ambil data gambar dari URL
            $barcode_data = file_get_contents($file_gambar);
            if ($barcode_data !== false) {
                file_put_contents($filename, $barcode_data);
            }
        }
        /*CREATEBARCODE*/
        $strInsert="UPDATE dbo_barang set file_barcode = '$FileBarcode' WHERE noid = '" . $varNoid . "'";
        $executeSQL=mysqli_query($koneksidb, $strInsert); 
                
    }
}
?>
