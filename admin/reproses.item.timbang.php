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
$Source = isset($_GET['source']) ? $_GET['source'] : "";

$FileCsv = "../file_master/digipos_new.csv";
if (file_exists($FileCsv)) {
    unlink($FileCsv);
}

// Endpoint & Auth
//$Company = "TEST";
$Company = "AMANAHLIVE";
$bcUrlHeader = "http://64.20.58.66:7048/BC200/ODataV4/Company('$Company')/PRSHeader";
$bcUrlDetail = "http://64.20.58.66:7048/BC200/ODataV4/Company('$Company')/PRSLine";
$bcUrlPayment = "http://64.20.58.66:7048/BC200/ODataV4/Company('$Company')/PRSPayment";
$bcUrlAllItem = "http://64.20.58.66:7048/BC200/ODataV4/Company('$Company')/AllItem";
$bcUrlItem = "http://64.20.58.66:7048/BC200/ODataV4/Company('$Company')/Item";
$bcUrlCategory = "http://64.20.58.66:7048/BC200/ODataV4/Company('$Company')/Category";
$bcUrlSubCategory = "http://64.20.58.66:7048/BC200/ODataV4/Company('$Company')/SubCategory";
$bcUrlDepartemen = "http://64.20.58.66:7048/BC200/ODataV4/Company('$Company')/Departemen";
$bcUrlVendor = "http://64.20.58.66:7048/BC200/ODataV4/Company('$Company')/Principal";
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

$varMasterData = "";
$ResultData = getBcHeaderData($bcUrlAllItem, $bcUser, $bcPass);
//echo json_encode($ResultData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
// Ambil array "value" dari $data2
$ResultDataValue = isset($ResultData['value']) ? $ResultData['value'] : [];
// Contoh: ambil semua kodecustomer
foreach ($ResultDataValue as $field) {
    $kdstore = isset($field['kdstore']) ? $field['kdstore'] : null;
    $plu = isset($field['plu']) ? $field['plu'] : null;
    $MinStock = isset($field['MinStock']) ? $field['MinStock'] : null;
    $MaxStock = isset($field['MaxStock']) ? $field['MaxStock'] : null;
    $Vendor = isset($field['Vendor']) ? $field['Vendor'] : null;
    $Harga0 = isset($field['Harga0']) ? $field['Harga0'] : "0";
    $Harga1 = isset($field['Harga1']) ? $field['Harga1'] : "0";
    $Harga2 = isset($field['Harga2']) ? $field['Harga2'] : "0";
    $item_n = isset($field['item_n']) ? $field['item_n'] : "0";
    $description = isset($field['description']) ? $field['description'] : null;
    $principal = isset($field['principal']) ? $field['principal'] : null;
    $subcat = isset($field['subcat']) ? $field['subcat'] : null;
    $category = isset($field['category']) ? $field['category'] : null;
    $department = isset($field['department']) ? $field['department'] : null;
    $Uom = isset($field['Uom']) ? $field['Uom'] : null;
    $VAT = isset($field['VAT']) ? $field['VAT'] : null;
    $VarTimbang = isset($field['Timbang']) ? $field['Timbang'] : null;
    $Conv1 = isset($field['Conv1']) ? $field['Conv1'] : "0";
    $Conv2 = isset($field['Conv2']) ? $field['Conv2'] : "0";
    $Tail1 = isset($field['Tail1']) ? $field['Tail1'] : null;
    $Tail2 = isset($field['Tail2']) ? $field['Tail2'] : null;
    $Barcode1 = isset($field['Barcode1']) ? $field['Barcode1'] : "0";
    $Barcode2 = isset($field['Barcode2']) ? $field['Barcode2'] : "0";

    $varNamaBarang = cleanWeirdCharacters($description);
    $varNamaBarang = str_replace("'", "`", $varNamaBarang);

    if($VAT == "VAT11"){
        $varPPNx = "11";
    }else{
        $varPPNx = "0";
    }
    
    if($VarTimbang === false){
        $FlTimbang = 0;
    }else{
        $FlTimbang = 1;
    }

    $CreateUrl = create_url($varNamaBarang);
    $RandomCode = md5($plu);

    if($kdstore == $KodeStoreOffline){
        $StrMstItem="SELECT * FROM dbo_barang where sku_barang = '" . $plu . "'";
        $CallStrMstItem=mysqli_query($koneksidb, $StrMstItem);
        $Jumbar=mysqli_num_rows($CallStrMstItem);
        if ($Jumbar > 0 ){
            $strSQLMaster="UPDATE dbo_barang set `fl_timbang` = '$FlTimbang' where sku_barang = '" . $plu . "'";
            $executeSQLxz=mysqli_query($koneksidb, $strSQLMaster);
            //if($Source!=""){
            //    echo "<div style='color:#FF9900;'>UPDATE ITEM :" . $strSQLMaster . "</div>";  
            //}

            if($FlTimbang == 1){
                if($Source!=""){
                    echo "<div style='color:#FF9900;'>" . $kdstore . "#" . $plu . "#" . $VarTimbang . "#" . $item_n . "#" . $Uom . "#" . $Harga0 . "#" . $Barcode1 . "#" . $Tail1 . "#" . $Harga1 . "#" . $Barcode2 . "#" . $Tail2 . "#" . $Harga2 . "</div>";
                }

                $SkuTimbang = "2" . $plu; 
                if (strlen($SkuTimbang) < 7) {
                    $varHargaTimbang = str_replace(".00", "", $Harga0);   
                    if($varHargaTimbang == 0){
                        $varHargaTimbang = str_replace(".00", "", $Harga2); 
                    }               
                    $csvFile = fopen("../file_master/digipos_new.csv", "a");
                    if ($csvFile && $FlTimbang == 1) {
                        $row = [
                            $SkuTimbang,
                            $SkuTimbang,
                            $varNamaBarang,
                            $varHargaTimbang
                        ];
                        fputcsv($csvFile, $row);
                        fclose($csvFile);
                    }
                }
            }
        }else{
            /*$strSQLMaster="INSERT INTO dbo_barang(
            `random_code`,`sku_barang`,`kode_barang`,`barcode`,`barcode2`,`barcode3`,
            `nama_barang`,`keterangan_1`,
            `sub_dept`,`dept`,`divisi`,`vendor_no`,`isi_kemasan_kecil`,
            `isi_kemasan_sedang`,`fl_timbang`,`url_named`,`uom`,`uom2`,`uom3`,
            `ppn`,`posting_date`,`posting_user`) VALUES(
            '$RandomCode','$plu','$plu','$item_n','$Barcode1','$Barcode2',
            '$varNamaBarang','$varNamaBarang',
            '$subcat','$category','$department','$principal','$Conv1',
            '$Conv2','$FlTimbang','$CreateUrl','$Uom','$Tail2','$Tail1',
            '$varPPNx','$datedb','ADMIN')";
            $executeSQLxz=mysqli_query($koneksidb, $strSQLMaster);*/
            //echo "<div style='color:#FF0990;'>INSERT ITEMDB : <br>" . $strSQLMaster . "</div>"; 
        }


    }

}
?>