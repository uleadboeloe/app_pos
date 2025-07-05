<?php
include("../lib/mysql_pdo.php");
include("../lib/mysql_connect.php");
include("../lib/general_lib.php");
include("library/connection.php");
include("library/fungsi.php");
include("library/parameter.php");

$Timestamp = str_replace(":","",$currdatedb);
$Timestamp = str_replace(" ","-",$Timestamp);
$Timestampx = str_replace("-","",$Timestamp);

// Endpoint & Auth
//$Company = "TEST";
$Company = "AMANAHLIVE";
$Source = isset($_GET['source']) ? $_GET['source'] : "";

$bcUrlHeader = "http://64.20.58.66:7048/BC200/ODataV4/Company('$Company')/PRSHeader";
$bcUrlDetail = "http://64.20.58.66:7048/BC200/ODataV4/Company('$Company')/PRSLine";
$bcUrlPayment = "http://64.20.58.66:7048/BC200/ODataV4/Company('$Company')/PRSPayment";
$bcUrlAllItem = "http://64.20.58.66:7048/BC200/ODataV4/Company('$Company')/AllItem";
$bcUrlItem = "http://64.20.58.66:7048/BC200/ODataV4/Company('$Company')/Item";
$bcUrlCategory = "http://64.20.58.66:7048/BC200/ODataV4/Company('$Company')/Category";
$bcUrlSubCategory = "http://64.20.58.66:7048/BC200/ODataV4/Company('$Company')/SubCategory";
$bcUrlDepartemen = "http://64.20.58.66:7048/BC200/ODataV4/Company('$Company')/Departemen";
$bcUrlVendor = "http://64.20.58.66:7048/BC200/ODataV4/Company('$Company')/Principal";
$bcUrlPromo = "http://64.20.58.66:7048/BC200/ODataV4/Company('$Company')/SalesPromo";
$bcUser = "IT";
$bcPass = "It@123";

function getBcHeaderData($bcUrl, $bcUser, $bcPass)
{
    $ch = curl_init($bcUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Accept: application/json'
    ]);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM);
    curl_setopt($ch, CURLOPT_USERPWD, "$bcUser:$bcPass");

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $errorNo = curl_errno($ch);
    $errorMsg = curl_error($ch);
    curl_close($ch);

    if ($errorNo || $httpCode != 200) {
        return false;
    }

    $data = json_decode($response, true);
    return $data;
}

$ResultData = getBcHeaderData($bcUrlPromo, $bcUser, $bcPass);
//echo json_encode($ResultData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
// Ambil array "value" dari $data2
$ResultDataValue = isset($ResultData['value']) ? $ResultData['value'] : [];
if (empty($ResultDataValue)) {
    echo "Tidak ada data promo yang ditemukan. tidak ada proses data promo<br>";
    return;
}else{
    $strCleanHeader="TRUNCATE TABLE dbo_promo";
    $executeSQL=mysqli_query($koneksidb, $strCleanHeader); 

    $strCleanDetail="TRUNCATE TABLE dbo_promo_detail";
    $executeSQL=mysqli_query($koneksidb, $strCleanDetail); 
    // Contoh: ambil semua kodecustomer
    foreach ($ResultDataValue as $field) {
        $KodePromo = isset($field['Code']) ? $field['Code'] : null;
        $NamaPromo = isset($field['Description']) ? $field['Description'] : null;
        $StoreCode = isset($field['Storecode']) ? $field['Storecode'] : null;
        $StartDate = isset($field['StartDate']) ? $field['StartDate'] : null;
        $EndDate = isset($field['EndDate']) ? $field['EndDate'] : null;
        $PromoParameter = isset($field['PromoParementer']) ? $field['PromoParementer'] : "0";
        $KriteriaPromo = isset($field['CriteriaPromo']) ? $field['CriteriaPromo'] : "0";
        $FlagFreeItem = isset($field['FlagFreeItem']) ? $field['FlagFreeItem'] : "0";
        $FreeItem = isset($field['FreeItem']) ? $field['FreeItem'] : "0";
        $QtyFreeItem = isset($field['QtyFreeItem']) ? $field['QtyFreeItem'] : null;
        $ValuePromo = isset($field['ValuePromo']) ? $field['ValuePromo'] : null;
        $LineNo = isset($field['Line_No_']) ? $field['Line_No_'] : null;
        $LineStoreCode = isset($field['LineStorecode']) ? $field['LineStorecode'] : null;
        $Barcode = isset($field['Barcode']) ? $field['Barcode'] : null;
        $SkuProduk = isset($field['SKU']) ? $field['SKU'] : null;
        $LineDescription = isset($field['LineDescription']) ? $field['LineDescription'] : null;
        $Uom = isset($field['Uom']) ? $field['Uom'] : null;
        $RandomCode = md5($KodePromo);
        $FlPromoDay = "1";
        $PromoDay = "SENIN,SELASA,RABU,KAMIS,JUMAT,SABTU,MINGGU,";
        
        switch ($PromoParameter){
            case "Discount Rupiah":
                $ParameterCode = "A";
                $KriteriaPromos = "RUPIAH";
                $PromoType = "PROMO DISKON RUPIAH";
                $BarcodeInduk = "-";
                $FlFreeItem = "0";
                $FreeItem = "0";
                $QtyFreeItem = "0";
                $KriteriaValue = "1";
                //$ValuePromo = "";
            break;
            case "Discount Persentase":
                $ParameterCode = "B";
                $KriteriaPromos = "PERSEN";
                $PromoType = "PROMO DISKON PERSENTASE";
                $BarcodeInduk = "-";
                $FlFreeItem = "0";
                $FreeItem = "0";
                $QtyFreeItem = "0";
                $KriteriaValue = "1";
                //$ValuePromo = "";
            break;
            case "Buy One Get One":
                $ParameterCode = "C";
                $KriteriaPromos = "BUY1GET1";
                $PromoType = "PROMO BUY 1 GET 1";
                $BarcodeInduk = "-";
                $FlFreeItem = "1";
                $FreeItem = "FREETHIS";
                $QtyFreeItem = "1";
                $KriteriaValue = "1";
                //$ValuePromo = "";
            break;
            case "Free Item":
                $ParameterCode = "D";
                $KriteriaPromos = "FREEITEM";
                $PromoType = "PROMO FREE ITEM";
                $BarcodeInduk = "-";
                $FlFreeItem = "1";
                //$FreeItem = "FREETHIS";    
                $QtyFreeItem = "1";   
                $KriteriaValue = "1";    
                //$ValuePromo = ""; 
            break;
            case "Buy 2 Get 1":
                $ParameterCode = "F";
                $KriteriaPromos = "BUY2GET1";
                $PromoType = "PROMO BUY 2 GET 1";
                $BarcodeInduk = "-";
                $FlFreeItem = "1";
                $FreeItem = "FREETHIS";
                $QtyFreeItem = "1";
                $KriteriaValue = "2";
                //$ValuePromo = "";
            break;
        } 

        if($StoreCode == ""){
            $StoreCode = $KodeStoreOffline;
        }
        
        if($Source){
            echo "<div style='color:#FF0099;'>DATA PROMO BC IN LINE#KODE PROMO" . $KodePromo . "#" . $NamaPromo . "#" . $StartDate . "#" . $EndDate . "#" . $KriteriaPromo . "#" . $ValuePromo . "</div>";
        }        
        /*
        echo $KodePromo . "#" . $NamaPromo . "#" . $StoreCode . "#" . $StartDate . "#" . $EndDate . "#" . $PromoParameter . "#" . $KriteriaPromo;
        echo "#" . $FlagFreeItem . "#" . $FreeItem . "#" . $QtyFreeItem . "#" . $ValuePromo . "#" . $LineNo . "<br>";
        echo "#" . $LineStoreCode . "#" . $Barcode . "#" . $SkuProduk . "#" . $LineDescription . "#" . $Uom . "<br>";
        */
        $StrMstPromo="SELECT * FROM dbo_promo where kode_promo = '" . $KodePromo . "'";
        $CallStrMstPromo=mysqli_query($koneksidb, $StrMstPromo);
        $Jumbar=mysqli_num_rows($CallStrMstPromo);
        if ($Jumbar === 0 ){
            $strInsert="INSERT INTO dbo_promo(
            `random_code`,`kode_promo`,`promo_name`,`promo_desc`,`promo_start_date`,`promo_end_date`,`fl_promo_day`,`promo_day`,`promo_type`,
            `fl_free_item`,`promo_parameter`,`kriteria_promo`,
            `kriteria_value`,`value_promo`,`free_item`,`qty_free_item`,`posting_date`,
            `posting_user`,`kode_store`,`fl_active`,`kode_barcode_induk`) VALUES (
            '$RandomCode','$KodePromo','$NamaPromo','DESKRIPSI PROMO','$StartDate','$EndDate','$FlPromoDay','$PromoDay','$PromoType',
            '$FlFreeItem','$ParameterCode','$KriteriaPromos',
            '$KriteriaValue','$ValuePromo','$FreeItem','$QtyFreeItem','$datedb','ADMIN','$StoreCode','1','$BarcodeInduk')";
            $executeSQL=mysqli_query($koneksidb, $strInsert); 
            //echo "<div style='color:#FF0990;'>INSERT ITEMDB : <br>" . $strInsert . "</div>";
        }

        $strQuery="SELECT * FROM dbo_promo_detail WHERE barcode = '" . $Barcode . "'";
        $callstrQuery=mysqli_query($koneksidb, $strQuery);
        $Jumbars=mysqli_num_rows($callstrQuery);
        if($Jumbars == 0){
            $strInsert="INSERT INTO dbo_promo_detail(`random_code`,`barcode`,`kode_promo`,`sku_barang`,`kode_barang`,`uom`,`posting_date`,`posting_user`) VALUES 
            ('$RandomCode','$Barcode','$KodePromo','$SkuProduk','$SkuProduk','$Uom','$datedb','ADMIN')";
            $executeSQL=mysqli_query($koneksidb, $strInsert); 
            //echo "<div style='color:#FF9900;'>INSERT LINE PROMO : <br>" . $strInsert . "</div>";
        }
    }
}
?>
