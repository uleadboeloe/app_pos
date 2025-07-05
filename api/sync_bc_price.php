<?php
include("../lib/mysql_pdo.php");
include("../lib/mysql_connect.php");
include("../lib/general_lib.php");
include("../admin/library/connection.php");
include("../admin/library/fungsi.php");
include("../admin/library/parameter.php");

$Timestamp = str_replace(":","",$currdatedb);
$Timestamp = str_replace(" ","-",$Timestamp);
$Timestampx = str_replace("-","",$Timestamp);
$Source = isset($_GET['source']) ? $_GET['source'] : "";

$local_file = "../file_master/MASTER" . $Timestampx . ".txt";
$Filesize = filesize($local_file);
if($Filesize > 0){
    $myfiles = fopen($local_file, "r") or die("Unable to open file!");
    $KontenFiles = fread($myfiles,filesize($local_file));
    //echo "Data Item : " .$KontenFiles."<br>";
    $KontenFiles = substr($KontenFiles, 0, -1);

    $varArrays = explode("^",$KontenFiles);
    $CountArrays = count($varArrays);
    //echo "Count Array : " .$varArrays."<br>";

    $varDataItem = explode("^", $KontenFiles);
    for($j=0;$j<$CountArrays;$j++){
        $varStrings = $varDataItem[$j];
        $varDataItemLine = explode("|", $varStrings);
        if($Source){
            echo "DATA MASTER PER LINE : " .$varStrings . "<br>";
        }

        $varKdStore = $varDataItemLine[0];
        $varSkuBarang = $varDataItemLine[1];
        $varBarcodeBarang = $varDataItemLine[2];
        $varUom = $varDataItemLine[3];
        $varHarga0 = $varDataItemLine[4];

        $varBarcodeBarang1 = $varDataItemLine[5];
        $varUom1 = $varDataItemLine[6];
        $varHarga1 = $varDataItemLine[7];
        
        $varBarcodeBarang2 = $varDataItemLine[8];
        $varUom2 = $varDataItemLine[9];
        $varHarga2 = $varDataItemLine[10];
        
        $varConv1 = $varDataItemLine[11];
        $varConv2 = $varDataItemLine[12];
        
        $varTimbang = $varDataItemLine[13];
        
        $RandomCode = md5($varSkuBarang);

        if($varKdStore == $KodeStoreOffline){
            if(($varBarcodeBarang == "0")||($varBarcodeBarang == "")){
                $varBarcodeBarang = $varSkuBarang;
            }   
            if($varBarcodeBarang1 == ""){
                $varBarcodeBarang1 = "1" . $varBarcodeBarang;
            }      
            if($varBarcodeBarang2 == ""){
                $varBarcodeBarang2 = "2" . $varBarcodeBarang;
            }      
               
            if($varTimbang == 0){
                $StrDeletePrice="DELETE FROM dbo_price where sku_barang = '" . $varSkuBarang . "'";
                $CallStrDeletePrice=mysqli_query($koneksidb, $StrDeletePrice);
                
                if(($varHarga0 > 0)&&($varUom != "")){
                    $StrCekPrice="SELECT * FROM dbo_price where sku_barang = '" . $varSkuBarang . "' and uom = '$varUom'";
                    $CallStrCekPrice=mysqli_query($koneksidb, $StrCekPrice);
                    $JumbarPrice=mysqli_num_rows($CallStrCekPrice);
                    //echo "<div style='color:#0000FF;'>" . $StrCekPrice . "</div>";
                    if ($JumbarPrice === 0 ){
                        $strSQLUpdateHJ="INSERT INTO dbo_price(`random_code`,`sku_barang`,`kode_barang`,`barcode`,`uom`,`harga_jual`,`start_date`,`end_date`,`posting_date`,`posting_user`,`isi_kemasan`) VALUES ('$RandomCode','$varSkuBarang','$varSkuBarang','$varBarcodeBarang','$varUom','$varHarga0','$currdatedb','$datedb_plus_12_months','$datedb','ADMIN','1')";
                        $executeSQL=mysqli_query($koneksidb, $strSQLUpdateHJ);
                        if($Source){
                            echo "<div style='color:#FF0099;'>MASUKIN HARGA JUAL & UOM Belum ADA : <br>" . $strSQLUpdateHJ . "</div>";
                        }
                    }else{
                        $strSQLUpdateHJ="UPDATE dbo_price set `barcode` = '$varBarcodeBarang',`harga_jual` = '$varHarga0',`start_date` = '$currdatedb' where sku_barang = '$varSkuBarang' and uom = '$varUom'";
                        $executeSQL=mysqli_query($koneksidb, $strSQLUpdateHJ);
                        if($Source){
                            echo "<div style='color:#FFDD99;'>UPDATE_HARGA_JUAL_0#" . $strSQLUpdateHJ . "</div>";
                        }
                    }
                    if($Source){
                        echo "<div style='color:#00FF00;'>UPDATE_HARGA_JUAL_0#" . $varBarcodeBarang . "#" . $varUom . "#" . $varHarga0 . "</div>";
                    }
                }

                if(($varHarga1 > 0)&&($varUom1 != "")){          
                    $StrCekPrice1="SELECT * FROM dbo_price where sku_barang = '" . $varSkuBarang . "' and uom = '$varUom1'";
                    $CallStrCekPrice1=mysqli_query($koneksidb, $StrCekPrice1);
                    $JumbarPrice1=mysqli_num_rows($CallStrCekPrice1);
                    if ($JumbarPrice1 === 0 ){
                        $strSQLUpdateHJ1="INSERT INTO dbo_price(`random_code`,`sku_barang`,`kode_barang`,`barcode`,`uom`,`harga_jual`,`start_date`,`end_date`,`posting_date`,`posting_user`,`isi_kemasan`) VALUES ('$RandomCode','$varSkuBarang','$varSkuBarang','$varBarcodeBarang1','$varUom1','$varHarga1','$currdatedb','$datedb_plus_12_months','$datedb','ADMIN','$varConv1')";
                        $executeSQLx=mysqli_query($koneksidb, $strSQLUpdateHJ1);
                        if($Source){
                            echo "MASUKIN HARGA JUAL 0 & UOM Belum ADA : <br>" . $strSQLUpdateHJ1 . "<br>";
                        }
                    }else{
                        $strSQLUpdateHJ1="UPDATE dbo_price set `barcode` = '$varBarcodeBarang1',`harga_jual` = '$varHarga1',`isi_kemasan` = '$varConv1',`start_date` = '$currdatedb' where sku_barang = '$varSkuBarang' and uom = '$varUom1'";
                        $executeSQLx=mysqli_query($koneksidb, $strSQLUpdateHJ1);
                        if($Source){
                            echo "<div style='color:#FFCC99;'>UPDATE_HARGA_JUAL_1#" . $strSQLUpdateHJ1 . "</div>";
                        }
                    }
                    if($Source){
                        echo "<div style='color:#00FF00;'>UPDATE_HARGA_JUAL_1#" . $varBarcodeBarang1 . "#" . $varUom1 . "#" . $varHarga1 . "</div>";
                    }
                }

                if(($varHarga2 > 0)&&($varUom2 != "")){               
                    $StrCekPrice2="SELECT * FROM dbo_price where sku_barang = '" . $varSkuBarang . "' and uom = '$varUom2'";
                    $CallStrCekPrice2=mysqli_query($koneksidb, $StrCekPrice2);
                    $JumbarPrice2=mysqli_num_rows($CallStrCekPrice2);
                    if ($JumbarPrice2 === 0 ){
                        $strSQLUpdateHJ2="INSERT INTO dbo_price(`random_code`,`sku_barang`,`kode_barang`,`barcode`,`uom`,`harga_jual`,`start_date`,`end_date`,`posting_date`,`posting_user`,`isi_kemasan`) VALUES 
                        ('$RandomCode','$varSkuBarang','$varSkuBarang','$varBarcodeBarang2','$varUom2','$varHarga2','$currdatedb','$datedb_plus_12_months','$datedb','ADMIN','$varConv2')";
                        $executeSQLxx=mysqli_query($koneksidb, $strSQLUpdateHJ2);
                        if($Source){
                            echo "MASUKIN HARGA JUAL 0 & UOM Belum ADA : <br>" . $strSQLUpdateHJ2 . "<br>";
                        }
                    }else{
                        $strSQLUpdateHJ2="UPDATE dbo_price set `barcode` = '$varBarcodeBarang2',`harga_jual` = '$varHarga2',`isi_kemasan` = '$varConv2',`start_date` = '$currdatedb' where sku_barang = '$varSkuBarang' and uom = '$varUom2'";
                        $executeSQLxx=mysqli_query($koneksidb, $strSQLUpdateHJ2);
                        if($Source){
                            echo "<div style='color:#FFAA99;'>UPDATE_HARGA_JUAL_2#" . $strSQLUpdateHJ2 . "</div>";
                        }
                    }
                    if($Source){
                        echo "<div style='color:#00FF00;'>UPDATE_HARGA_JUAL_2#" . $varBarcodeBarang2 . "#" . $varUom1 . "#" . $varHarga2 . "</div>";                
                    }
                } 
            }else{
                $StrDeletePrice="DELETE FROM dbo_price where sku_barang = '" . $varSkuBarang . "'";
                $CallStrDeletePrice=mysqli_query($koneksidb, $StrDeletePrice);

                if(($varHarga2 > 0)&&($varUom2 != "")){               
                    $StrCekPrice2="SELECT * FROM dbo_price where sku_barang = '" . $varSkuBarang . "' and uom = '$varUom2'";
                    $CallStrCekPrice2=mysqli_query($koneksidb, $StrCekPrice2);
                    $JumbarPrice2=mysqli_num_rows($CallStrCekPrice2);
                    if ($JumbarPrice2 === 0 ){
                        $strSQLUpdateHJ2="INSERT INTO dbo_price(`random_code`,`sku_barang`,`kode_barang`,`barcode`,`uom`,`harga_jual`,`start_date`,`end_date`,`posting_date`,`posting_user`,`isi_kemasan`) VALUES 
                        ('$RandomCode','$varSkuBarang','$varSkuBarang','$varBarcodeBarang2','$varUom2','$varHarga2','$currdatedb','$datedb_plus_12_months','$datedb','ADMIN','$varConv2')";
                        $executeSQLxx=mysqli_query($koneksidb, $strSQLUpdateHJ2);
                        if($Source){
                            echo "MASUKIN HARGA JUAL 0 & UOM Belum ADA : <br>" . $strSQLUpdateHJ2 . "<br>";
                        }
                    }else{
                        $strSQLUpdateHJ2="UPDATE dbo_price set `barcode` = '$varBarcodeBarang2',`harga_jual` = '$varHarga2',`isi_kemasan` = '$varConv2',`start_date` = '$currdatedb' where sku_barang = '$varSkuBarang' and uom = '$varUom2'";
                        $executeSQLxx=mysqli_query($koneksidb, $strSQLUpdateHJ2);
                        if($Source){
                            echo "<div style='color:#FFAA99;'>UPDATE_HARGA_JUAL_2#" . $strSQLUpdateHJ2 . "</div>";
                        }
                    }
                    if($Source){
                        echo "<div style='color:#00FF00;'>UPDATE_HARGA_JUAL_2#" . $varBarcodeBarang2 . "#" . $varUom1 . "#" . $varHarga2 . "</div>";                
                    }
                } 
            }
        }
    }
}
?>