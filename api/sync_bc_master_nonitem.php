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

// Endpoint & Auth
//$Company = "TEST";
$Company = "AMANAHLIVE";

$bcUrlHeader = "http://64.20.58.66:7048/BC200/ODataV4/Company('$Company')/PRSHeader";
$bcUrlDetail = "http://64.20.58.66:7048/BC200/ODataV4/Company('$Company')/PRSLine";
$bcUrlPayment = "http://64.20.58.66:7048/BC200/ODataV4/Company('$Company')/PRSPayment";
$bcUrlAllItem = "http://64.20.58.66:7048/BC200/ODataV4/Company('$Company')/AllItem";
$bcUrlItem = "http://64.20.58.66:7048/BC200/ODataV4/Company('$Company')/Item";
$bcUrlSalesPerson = "http://64.20.58.66:7048/BC200/ODataV4/Company('$Company')/Salespersons";
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

$ResultData = getBcHeaderData($bcUrlDepartemen, $bcUser, $bcPass);
$ResultDataValue = isset($ResultData['value']) ? $ResultData['value'] : [];
// Contoh: ambil semua kodecustomer
foreach ($ResultDataValue as $field) {
    $Code = isset($field['Code']) ? $field['Code'] : null;
    $Name = isset($field['Name']) ? $field['Name'] : null;

    /*
    $StrMstItem="SELECT * FROM dbo_departemen where kode_departemen = '" . $Code . "'";
    $CallStrMstItem=mysqli_query($koneksidb, $StrMstItem);
    $Jumbar=mysqli_num_rows($CallStrMstItem);
    if ($Jumbar === 0 ){
        $strSQLMaster="INSERT INTO dbo_departemen(`kode_departemen`,`nama_departemen`,`posting_date`,`posting_user`,fl_active = 1) VALUES('$Code','$Name','$datedb','ADMIN','1')";
        $executeSQLxz=mysqli_query($koneksidb, $strSQLMaster);
        //echo "<div style='color:#FF0990;'>INSERT ITEMDB : <br>" . $varMasterData . "</div>";
    }else{
        $strSQLMaster="UPDATE dbo_departemen set `nama_departemen` = '$Name' where kode_departemen = '" . $Code . "'";
        $executeSQLxz=mysqli_query($koneksidb, $strSQLMaster);
        //echo "<div style='color:#FF0990;'>INSERT ITEMDB : <br>" . $varMasterData . "</div>";    
    }
    */

}

$ResultData1 = getBcHeaderData($bcUrlCategory, $bcUser, $bcPass);
$ResultData1Value = isset($ResultData1['value']) ? $ResultData1['value'] : [];
// Contoh: ambil semua kodecustomer
foreach ($ResultData1Value as $field1) {
    $Code1 = isset($field1['Code']) ? $field1['Code'] : null;
    $Name1 = isset($field1['Name']) ? $field1['Name'] : null;

    /*
    $StrMstItem="SELECT * FROM dbo_kategori where kode_kategori = '" . $Code1 . "'";
    $CallStrMstItem=mysqli_query($koneksidb, $StrMstItem);
    $Jumbar=mysqli_num_rows($CallStrMstItem);
    if ($Jumbar === 0 ){
        $strSQLMaster="INSERT INTO dbo_kategori(`kode_kategori`,`nama_kategori`,`posting_date`,`posting_user`,fl_active = 1) VALUES('$Code1','$Name1','$datedb','ADMIN','1')";
        $executeSQLxz=mysqli_query($koneksidb, $strSQLMaster);
        //echo "<div style='color:#FF0990;'>INSERT ITEMDB : <br>" . $varMasterData . "</div>";
    }else{
        $strSQLMaster="UPDATE dbo_kategori set `nama_kategori` = '$Name1' where kode_kategori = '" . $Code1 . "'";
        $executeSQLxz=mysqli_query($koneksidb, $strSQLMaster);
        //echo "<div style='color:#FF0990;'>INSERT ITEMDB : <br>" . $varMasterData . "</div>";    
    }
    */

}

$ResultData2 = getBcHeaderData($bcUrlSubCategory, $bcUser, $bcPass);
//echo json_encode($ResultData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
$ResultData2Value = isset($ResultData2['value']) ? $ResultData2['value'] : [];
// Contoh: ambil semua kodecustomer
foreach ($ResultData2Value as $field2) {
    $Code2 = isset($field2['Code']) ? $field2['Code'] : null;
    $Name2 = isset($field2['Name']) ? $field2['Name'] : null;

    /*
    $StrMstItem="SELECT * FROM dbo_subkategori where kode_subkategori = '" . $Code1 . "'";
    $CallStrMstItem=mysqli_query($koneksidb, $StrMstItem);
    $Jumbar=mysqli_num_rows($CallStrMstItem);
    if ($Jumbar === 0 ){
        $strSQLMaster="INSERT INTO dbo_subkategori(`kode_subkategori`,`nama_subkategori`,`posting_date`,`posting_user`,fl_active = 1) VALUES('$Code2','$Name2','$datedb','ADMIN','1')";
        $executeSQLxz=mysqli_query($koneksidb, $strSQLMaster);
        //echo "<div style='color:#FF0990;'>INSERT ITEMDB : <br>" . $varMasterData . "</div>";
    }else{
        $strSQLMaster="UPDATE dbo_subkategori set `nama_subkategori` = '$Name2' where kode_subkategori = '" . $Code2 . "'";
        $executeSQLxz=mysqli_query($koneksidb, $strSQLMaster);
        //echo "<div style='color:#FF0990;'>INSERT ITEMDB : <br>" . $varMasterData . "</div>";    
    }
    */

}
?>