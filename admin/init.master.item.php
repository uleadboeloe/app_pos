<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once "library/connection.php";
include_once "library/parameter.php";
include_once "library/fungsi.php";

echo "MASUK INI MASTER ITEM <br>";

//$ftp_server = "ftp.amanahmart.id";
//$ftp_username = "IT";
//$ftp_password = "It@123";
//$ftp_port = "21";

$local_file = "../dbo_schema/ExportItem4NewPOS.txt";
$Filesize = filesize($local_file);
if($Filesize > 0){
    $myfiles = fopen($local_file, "r") or die("Unable to open file!");
    $KontenFiles = fread($myfiles,filesize($local_file));
    //echo "Data Item : " .$KontenFiles."<br>";

    $varArrays = explode("^",$KontenFiles);
    $CountArrays = count($varArrays);
    //echo "Count Array : " .$varArrays."<br>";

    $varDataItem = explode("^", $KontenFiles);
    for($j=0;$j<$CountArrays;$j++){
        $varStrings = $varDataItem[$j];
        $varDataItemLine = explode("|", $varStrings);
        //echo "String to explode: " .$varStrings . "<br>";
        
        $varKdStore = $varDataItemLine[0];
        $varSkuBarang = $varDataItemLine[1];
        $varKodeBarang = $varDataItemLine[1];
        $varBarcodeBarang = $varDataItemLine[2] ?? "0";
        $varNamaBarang = cleanWeirdCharacters($varDataItemLine[3]);
        $varNamaBarang = str_replace("'", "`", $varNamaBarang);

        $RandomCode = md5($varNamaBarang);
        $CreateUrl = create_url($varNamaBarang);            

        $varPrincipal = $varDataItemLine[4];
        $varDivisi = $varDataItemLine[7];
        $varDept = $varDataItemLine[6];
        $varSubDept = $varDataItemLine[5];
        $varPPN = $varDataItemLine[9];
        if($varPPN == "VAT"){
            $varPPNx = "11";
        }else{
            $varPPNx = "0";
        }

        $varTimbang = $varDataItemLine[10];
        if($varTimbang == "No"){
            $varTimbangx = "False";
            $FlTimbang = 0;
        }else{
            $varTimbangx = "True";
            $FlTimbang = 1;
        }
        $varKemasanSedang = replacenumbers($varDataItemLine[11] ?? "0");
        $varKemasanBesar = replacenumbers($varDataItemLine[12] ?? "0");
        $varMinStok = replacenumbers($varDataItemLine[13] ?? "0");
        $varMaxStok = replacenumbers($varDataItemLine[14] ?? "0");
        $varVendor = $varDataItemLine[15];
        $varUom3 = $varDataItemLine[8];
        $varUom2 = $varDataItemLine[16];
        $varUom1 = $varDataItemLine[17];

        $varHargaJual1 = replacenumbers($varDataItemLine[18]);
        $varHargaBeli1 = replacenumbers($varDataItemLine[19]);
        
        $varHargaJual2 = replacenumbers($varDataItemLine[20]);
        $varHargaBeli2 = replacenumbers($varDataItemLine[21]);
        
        $varHargaJual3 = replacenumbers($varDataItemLine[22]);
        $varHargaBeli3 = replacenumbers($varDataItemLine[23]);
        $varBarcode2 = $varDataItemLine[24] ?? "0";
        $varBarcode3 = $varDataItemLine[25] ?? "0";
        if($varBarcodeBarang == ""){
            $varBarcodeBarang = $varSkuBarang;
        }
        if($varBarcode2 == ""){
            $varBarcode2 = "0" . $varSkuBarang;
        }
        if($varBarcode3 == ""){
            $varBarcode3 = "0" . $varSkuBarang;
        }   
        if($varTimbangx == "True"){
            $StrMstItem="SELECT * FROM dbo_barang where sku_barang = '" . $varSkuBarang . "'";
            $CallStrMstItem=mysqli_query($koneksidb, $StrMstItem);
            $Jumbar=mysqli_num_rows($CallStrMstItem);
            if ($Jumbar === 0 ){
                $strSQLMaster="INSERT INTO dbo_barang(
                `random_code`,`sku_barang`,`kode_barang`,`barcode`,`barcode2`,`barcode3`,
                `nama_barang`,`keterangan_1`,
                `sub_dept`,`dept`,`divisi`,`vendor_no`,`isi_kemasan_kecil`,
                `isi_kemasan_sedang`,`fl_timbang`,`url_named`,`uom`,`uom2`,`uom3`,
                `ppn`,`posting_date`,`posting_user`) VALUES(
                '$RandomCode','$varSkuBarang','$varSkuBarang','$varBarcodeBarang','$varBarcode2','$varBarcode3',
                '$varNamaBarang','$varNamaBarang',
                '$varSubDept','$varDept','$varDivisi','$varVendor','$varKemasanSedang',
                '$varKemasanBesar','$FlTimbang','$CreateUrl','$varUom1','$varUom2','$varUom3',
                '$varPPNx','$datedb','ADMIN')";
                $executeSQLxz=mysqli_query($koneksidb, $strSQLMaster);
                echo "MASUK MASTER TIMBANG : <br>" . $strSQLMaster . "<br>";

                if(($varHargaJual1 > 0)&&($varUom1 != "")){
                    if(($varBarcodeBarang == "") || ($varBarcodeBarang == "0")){
                        $varBarcodeBarang = $varSkuBarang;
                    }
                    $strSQLUpdateHJ1="INSERT INTO dbo_price(`random_code`,`sku_barang`,`kode_barang`,`barcode`,`uom`,`harga_jual`,`start_date`,`end_date`,`posting_date`,`posting_user`,`isi_kemasan`) VALUES ('$RandomCode','$varSkuBarang','$varSkuBarang','$varBarcodeBarang','$varUom1','$varHargaJual1','$datedb','$datedb_plus_12_months','$datedb','ADMIN','1')";
                    $executeSQLxx=mysqli_query($koneksidb, $strSQLUpdateHJ1);
                    //echo "MASUKIN HARGA JUAL 0 : <br>" . $strSQLUpdateHJ1 . "<br>";
                }
                //echo "<hr>";
            }else{
                    if(($varHargaJual1 > 0)&&($varUom3 != "")){
                        $StrCekPrice="SELECT * FROM dbo_price where sku_barang = '" . $varSkuBarang . "' and uom = '$varUom3'";
                        $CallStrCekPrice=mysqli_query($koneksidb, $StrCekPrice);
                        $JumbarPrice=mysqli_num_rows($CallStrCekPrice);
                        //echo "<div style='color:#0000FF;'>" . $StrCekPrice . "</div>";
                        if ($JumbarPrice === 0 ){
                            $strSQLUpdateHJ="INSERT INTO dbo_price(`random_code`,`sku_barang`,`kode_barang`,`barcode`,`uom`,`harga_jual`,`start_date`,`end_date`,`posting_date`,`posting_user`,`isi_kemasan`) VALUES 
                            ('$RandomCode','$varSkuBarang','$varSkuBarang','$varBarcodeBarang','$varUom3','$varHargaJual1','$currdatedb','$datedb_plus_12_months','$datedb','ADMIN','1')";
                            $executeSQL=mysqli_query($koneksidb, $strSQLUpdateHJ);
                            echo "<div style='color:#FF0099;'>MASUKIN HARGA JUAL TIMBANG & UOM BELUM ADA : #" . $strSQLUpdateHJ . "</div>";
                        }else{
                            $strSQLUpdateHJ="UPDATE dbo_price set `barcode` = '$varBarcodeBarang',`harga_jual` = '$varHargaJual1',`start_date` = '$currdatedb' where sku_barang = '$varSkuBarang' and uom = '$varUom3'";
                            $executeSQL=mysqli_query($koneksidb, $strSQLUpdateHJ);
                            echo "<div style='color:#FFDD99;'>UPDATE_HARGA_JUAL_TIMBANG#" . $strSQLUpdateHJ . "</div>";
                        }
                    }
            }

            $SkuTimbang = "2" . $varSkuBarang; 
            $varHargaTimbang = str_replace(".00", "", $varHargaJual1);                  
            $csvFile = fopen("../file_master/digipos.csv", "w");
            if ($csvFile && $varTimbangx == "True") {
                $row = [
                    $SkuTimbang,
                    $SkuTimbang,
                    $varNamaBarang,
                    $varHargaTimbang
                ];
                fputcsv($csvFile, $row);
                fclose($csvFile);
            }
        }else{
            //if($varBarcodeBarang != "0"){
                $StrMstItem="SELECT * FROM dbo_barang where sku_barang = '" . $varSkuBarang . "'";
                $CallStrMstItem=mysqli_query($koneksidb, $StrMstItem);
                $Jumbar=mysqli_num_rows($CallStrMstItem);
                if ($Jumbar === 0 ){
                    $strSQLMaster="INSERT INTO dbo_barang(
                    `random_code`,`sku_barang`,`kode_barang`,`barcode`,`barcode2`,`barcode3`,
                    `nama_barang`,`keterangan_1`,
                    `sub_dept`,`dept`,`divisi`,`vendor_no`,`isi_kemasan_kecil`,
                    `isi_kemasan_sedang`,`fl_timbang`,`url_named`,`uom`,`uom2`,`uom3`,
                    `ppn`,`posting_date`,`posting_user`) VALUES(
                    '$RandomCode','$varSkuBarang','$varSkuBarang','$varBarcodeBarang','$varBarcode2','$varBarcode3',
                    '$varNamaBarang','$varNamaBarang',
                    '$varSubDept','$varDept','$varDivisi','$varVendor','$varKemasanSedang',
                    '$varKemasanBesar','$FlTimbang','$CreateUrl','$varUom3','$varUom2','$varUom1',
                    '$varPPNx','$datedb','ADMIN')";
                    $executeSQLxz=mysqli_query($koneksidb, $strSQLMaster);
                    echo "MASUK MASTER NONTIMBANG : <br>" . $strSQLMaster . "<br>";

                    if(($varHargaJual3 > 0)&&($varUom3 != "")){
                        if($varBarcodeBarang == ""){
                            $varBarcodeBarang = "0";
                        }
                        $strSQLUpdateHJ1="INSERT INTO dbo_price(`random_code`,`sku_barang`,`kode_barang`,`barcode`,`uom`,`harga_jual`,`start_date`,`end_date`,`posting_date`,`posting_user`,`isi_kemasan`) VALUES ('$RandomCode','$varSkuBarang','$varSkuBarang','$varBarcodeBarang','$varUom3','$varHargaJual3','$datedb','$datedb_plus_12_months','$datedb','ADMIN','1')";
                        $executeSQLxx=mysqli_query($koneksidb, $strSQLUpdateHJ1);
                        echo "MASUKIN HARGA JUAL 0 : <br>" . $strSQLUpdateHJ1 . "<br>";
                    }

                    if(($varHargaJual2 > 0)&&($varUom2 != "")){
                        if($varBarcode2 == "0"){
                            $varBarcode2 = "1" . $varBarcodeBarang;
                        }
                        $strSQLUpdateHJ1="INSERT INTO dbo_price(`random_code`,`sku_barang`,`kode_barang`,`barcode`,`uom`,`harga_jual`,`start_date`,`end_date`,`posting_date`,`posting_user`,`isi_kemasan`) VALUES ('$RandomCode','$varSkuBarang','$varSkuBarang','$varBarcode2','$varUom2','$varHargaJual2','$datedb','$datedb_plus_12_months','$datedb','ADMIN','$varKemasanSedang')";
                        $executeSQLxx=mysqli_query($koneksidb, $strSQLUpdateHJ1);
                        echo "MASUKIN HARGA JUAL 0 : <br>" . $strSQLUpdateHJ1 . "<br>";
                    }
                    
                    if(($varHargaJual1 > 0)&&($varUom1 != "")){
                        if($varBarcode3 == "0"){
                            $varBarcode3 = "2" . $varBarcodeBarang;
                        }                        
                        $strSQLUpdateHJ1="INSERT INTO dbo_price(`random_code`,`sku_barang`,`kode_barang`,`barcode`,`uom`,`harga_jual`,`start_date`,`end_date`,`posting_date`,`posting_user`,`isi_kemasan`) VALUES ('$RandomCode','$varSkuBarang','$varSkuBarang','$varBarcode3','$varUom1','$varHargaJual1','$datedb','$datedb_plus_12_months','$datedb','ADMIN','$varKemasanBesar')";
                        $executeSQLxx=mysqli_query($koneksidb, $strSQLUpdateHJ1);
                        echo "MASUKIN HARGA JUAL 0 : <br>" . $strSQLUpdateHJ1 . "<br>";
                    }
                }else{
                    if(($varHargaJual3 > 0)&&($varUom3 != "")){
                        $StrCekPrice="SELECT * FROM dbo_price where sku_barang = '" . $varSkuBarang . "' and uom = '$varUom3'";
                        $CallStrCekPrice=mysqli_query($koneksidb, $StrCekPrice);
                        $JumbarPrice=mysqli_num_rows($CallStrCekPrice);
                        //echo "<div style='color:#0000FF;'>" . $StrCekPrice . "</div>";
                        if ($JumbarPrice === 0 ){
                            $strSQLUpdateHJ="INSERT INTO dbo_price(`random_code`,`sku_barang`,`kode_barang`,`barcode`,`uom`,`harga_jual`,`start_date`,`end_date`,`posting_date`,`posting_user`,`isi_kemasan`) VALUES 
                            ('$RandomCode','$varSkuBarang','$varSkuBarang','$varBarcodeBarang','$varUom3','$varHargaJual3','$currdatedb','$datedb_plus_12_months','$datedb','ADMIN','1')";
                            $executeSQL=mysqli_query($koneksidb, $strSQLUpdateHJ);
                            echo "<div style='color:#FF0099;'>MASUKIN HARGA JUAL 0& UOM BELUM ADA : #" . $strSQLUpdateHJ . "</div>";
                        }else{
                            $strSQLUpdateHJ="UPDATE dbo_price set `barcode` = '$varBarcodeBarang',`harga_jual` = '$varHargaJual3',`start_date` = '$currdatedb' where sku_barang = '$varSkuBarang' and uom = '$varUom3'";
                            $executeSQL=mysqli_query($koneksidb, $strSQLUpdateHJ);
                            echo "<div style='color:#FFDD99;'>UPDATE_HARGA_JUAL_0#" . $strSQLUpdateHJ . "</div>";
                        }
                    }

                    if(($varHargaJual2 > 0)&&($varUom1 != "")){          
                        $StrCekPrice1="SELECT * FROM dbo_price where sku_barang = '" . $varSkuBarang . "' and uom = '$varUom2'";
                        $CallStrCekPrice1=mysqli_query($koneksidb, $StrCekPrice1);
                        $JumbarPrice1=mysqli_num_rows($CallStrCekPrice1);
                        if ($JumbarPrice1 === 0 ){
                            $strSQLUpdateHJ1="INSERT INTO dbo_price(`random_code`,`sku_barang`,`kode_barang`,`barcode`,`uom`,`harga_jual`,`start_date`,`end_date`,`posting_date`,`posting_user`,`isi_kemasan`) VALUES 
                            ('$RandomCode','$varSkuBarang','$varSkuBarang','$varBarcode2','$varUom1','$varHargaJual2','$currdatedb','$datedb_plus_12_months','$datedb','ADMIN','$varKemasanSedang')";
                            $executeSQLx=mysqli_query($koneksidb, $strSQLUpdateHJ1);
                            echo "<div style='color:#FF0099;'>MASUKIN HARGA JUAL 0& UOM BELUM ADA : #" . $strSQLUpdateHJ1 . "</div>";
                        }else{
                            $strSQLUpdateHJ1="UPDATE dbo_price set `barcode` = '$varBarcode2',`harga_jual` = '$varHargaJual2',`isi_kemasan` = '$varKemasanSedang',`start_date` = '$currdatedb' where sku_barang = '$varSkuBarang' and uom = '$varUom1'";
                            $executeSQLx=mysqli_query($koneksidb, $strSQLUpdateHJ1);
                            echo "<div style='color:#FFCC99;'>UPDATE_HARGA_JUAL_1#" . $strSQLUpdateHJ1 . "</div>";
                        }
                    }
                    
                    if(($varHargaJual1 > 0)&&($varUom2 != "")){               
                        $StrCekPrice2="SELECT * FROM dbo_price where sku_barang = '" . $varSkuBarang . "' and uom = '$varUom2'";
                        $CallStrCekPrice2=mysqli_query($koneksidb, $StrCekPrice2);
                        $JumbarPrice2=mysqli_num_rows($CallStrCekPrice2);
                        if ($JumbarPrice2 === 0 ){
                            $strSQLUpdateHJ2="INSERT INTO dbo_price(`random_code`,`sku_barang`,`kode_barang`,`barcode`,`uom`,`harga_jual`,`start_date`,`end_date`,`posting_date`,`posting_user`,`isi_kemasan`) VALUES 
                            ('$RandomCode','$varSkuBarang','$varSkuBarang','$varBarcode3','$varUom2','$varHargaJual1','$currdatedb','$datedb_plus_12_months','$datedb','ADMIN','$varKemasanBesar')";
                            $executeSQLxx=mysqli_query($koneksidb, $strSQLUpdateHJ2);
                            echo "<div style='color:#FF0099;'>MASUKIN HARGA JUAL 2& UOM BELUM ADA : #" . $strSQLUpdateHJ2 . "</div>";
                        }else{
                            $strSQLUpdateHJ2="UPDATE dbo_price set `barcode` = '$varBarcode3',`harga_jual` = '$varHargaJual1',`isi_kemasan` = '$varKemasanBesar',`start_date` = '$currdatedb' where sku_barang = '$varSkuBarang' and uom = '$varUom2'";
                            $executeSQLxx=mysqli_query($koneksidb, $strSQLUpdateHJ1);
                            echo "<div style='color:#FFAA99;'>UPDATE_HARGA_JUAL_2#" . $strSQLUpdateHJ2 . "</div>";
                        }
                    } 
                }
            //}
            
        }
    }
    fclose($myfiles);
}
?>
