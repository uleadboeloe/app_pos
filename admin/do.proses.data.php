<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "library/parameter.php";
include "library/connection.php";
include "library/fungsi.php";
include "library/qrcode/qrlib.php";

ini_set('max_execution_time', 36000); // 5 minutes

$Timestamp = str_replace(":","",$datedb);
$Timestamp = str_replace(" ","-",$Timestamp);
$Timestampx = str_replace("-","",$Timestamp);

/*txtPromoValue - harga jual
txtNormalPrice - harga normal satuan
txtSkuPromoValue - barcode produk*/                       
$strQueryLine="SELECT * FROM dbo_header WHERE tanggal = '2025-07-21'";
$callstrQueryLine=mysqli_query($koneksidb, $strQueryLine);
$JumbarLine=mysqli_num_rows($callstrQueryLine);
if($JumbarLine > 0){
    while($recView=mysqli_fetch_array($callStrViewQuery))
    {
        $TanggalStruk = $recView['tanggal'];
        $NoStruk = $recView['nomor_struk'];
        echo "<h1>Struk Penjualan</h1>";
        echo "<p>Tanggal: $TanggalStruk</p>";
        echo "<p>No. Struk: $NoStruk</p>";
    }
}