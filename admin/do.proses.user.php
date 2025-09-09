<?php
session_start();
include "library/parameter.php";
include "library/connection.php";
include "library/fungsi.php";
include "library/qrcode/qrlib.php";

ini_set('max_execution_time', 36000); // 5 minutes

$Timestamp = str_replace(":","",$datedb);
$Timestamp = str_replace(" ","-",$Timestamp);
$Timestampx = str_replace("-","",$Timestamp);

$Userid = $_SESSION['ADMSESS_user_id'];
$Username = $_SESSION['ADMSESS_user_name'];

if(isset($_SESSION['ADMSESS_user_id'])){
    $txtSourcex = (trim($_GET['source']));

    switch ($txtSourcex){
        case "user":
            $txtKodeKasirx = (trim($_POST['txtKodeKasir']));
            $txtRandomCodex = (trim($_POST['txtRandomCode']));
            $txtNamaKasirx = (trim($_POST['txtNamaKasir']));
            $txtKodeStorex = (trim($_POST['txtKodeStore']));
            $txtNoKontakx = (trim($_POST['txtNoKontak']));
            $txtPasswordx = (trim($_POST['txtPassword']));
            $txtJabatanx = (trim($_POST['txtJabatan']));
            $txtAlamatEmailx = (trim($_POST['txtAlamatEmail']));
            $HakAkses = getHakAkses($txtJabatanx);
            $Passwords = crypt($txtPasswordx, $Salt);
            $txtApproveCode = $txtKodeKasirx . "-" . $txtRandomCodex;
            $FileBarcode = "";
            $KodePrefix = $txtKodeStorex . "-";
            $txtApproveCode = str_replace(" ","-",$txtApproveCode);
            /*
            echo "<h1>" . $txtRandomCodex . "</h1>";
            echo "<h1>" . $txtKodeKasirx . "</h1>";
            echo "<h1>" . $txtNamaKasirx . "</h1>";
            echo "<h1>" . $txtKodeStorex . "</h1>";
            echo "<h1>" . $txtNoKontakx . "</h1>";
            echo "<h1>" . $HakAkses . "</h1>";
            echo "<h1>" . $txtJabatanx . "</h1>";
            echo "<h1>" . $txtApproveCode . "</h1>";
            echo "<h1>" . $Passwords . "</h1>";
            echo "<h1>" . $txtPasswordx . "</h1>";
            */  
            $strQuery="SELECT * FROM dbo_table_user WHERE kode_kasir = '" . $txtKodeStorex . "' and userid = '" . $txtNamaKasirx . "'";
            $callstrQuery=mysqli_query($koneksicloud, $strQuery);
            $Jumbar=mysqli_num_rows($callstrQuery);
            if($Jumbar == 0){
                $strInsertUser="INSERT INTO dbo_table_user(
                `random_code`,`userid`,`userpass`,`kode_kasir`,`nama_user`,`hak_akses`,`kode_store`,`approval_code`,
                `job_title`,`nomor_kontak`,`alamat_email`,`password_`) VALUES (
                '$txtRandomCodex','$txtKodeKasirx','$Passwords','$txtKodeKasirx','$txtNamaKasirx','$HakAkses','$txtKodeStorex','$txtApproveCode',
                '$txtJabatanx','$txtNoKontakx','$txtAlamatEmailx','$txtPasswordx')";
                $executeSQL=mysqli_query($koneksicloud, $strInsertUser); 
                
                //echo "<h1>" . $strInsertUser . "</h1>";

                if($executeSQL === false){
                    header("Location: akses-user-msg!save-failed");
                }else{
                    header("Location: akses-user-msg!save-success");
                }
            }              
        break;  
        case "store":
            $txtKodeStorex = (trim($_POST['txtKodeStore']));
            $txtRandomCodex = (trim($_POST['txtRandomCode']));
            $txtNamaStorex = (trim($_POST['txtNamaStore']));
            $txtAlamatStorex = (trim($_POST['txtAlamatStore']));
            $txtProvinsix = (trim($_POST['txtProvinsi']));
            $txtKotax = (trim($_POST['txtKota']));
            $txtKecamatanx = (trim($_POST['txtKecamatan']));
            $txtKodePosx = (trim($_POST['txtKodePos']));
            $txtNoKontakx = (trim($_POST['txtNoKontak']));
            $txtHeaderStrukx = nl2br((trim($_POST['txtHeaderStruk'])));
            $txtFooterStrukx = nl2br((trim($_POST['txtFooterStruk'])));
            $txtLongx = (trim($_POST['Long']));
            $txtLatx = (trim($_POST['Lat']));

            exit();
            $strQuery="SELECT * FROM dbo_store WHERE kode_store = '" . $txtKodeStorex . "' and nama_store = '" . $txtNamaStorex . "'";
            $callstrQuery=mysqli_query($koneksidb, $strQuery);
            $Jumbar=mysqli_num_rows($callstrQuery);
            if($Jumbar == 0){
                $strInsert="INSERT INTO dbo_store(`random_code`,`kode_store`,`nama_store`,`alamat_store`,`provinsi`,`kota`,`kecamatan`,`kode_pos`,`no_kontak`,`coord_long`,`coord_lat`,`posting_date`,`posting_user`,`header_struk`,`footer_struk`) VALUES ('$txtRandomCodex','$txtKodeStorex','$txtNamaStorex','$txtAlamatStorex','$txtProvinsix','$txtKotax','$txtKecamatanx','$txtKodePosx','$txtNoKontakx','$txtLongx','$txtLatx','$datedb','$Userid','$txtHeaderStrukx','$txtFooterStrukx')";
                $executeSQL=mysqli_query($koneksidb, $strInsert); 

                if($executeSQL === false){
                    header("Location: setup-toko-msg!save-failed");
                }else{
                    header("Location: setup-toko-msg!save-success");
                }
            }else{
                while($recResult=mysqli_fetch_array($callStrQuery))
                {
                    $varNoFaktur = $recResult['kode_store'];
                    $varKodeCustomer = $recResult['nama_store'];     
                }
            }
        break;   
        case "reset-login":
            $txtKodeKasirx = (trim($_POST['txtKodeKasir']));
            $txtRandomCodex = (trim($_POST['txtRandomCode']));
            
            $strQuery="SELECT * FROM dbo_table_user WHERE kode_kasir = '" . $txtKodeKasirx . "'";
            $callstrQuery=mysqli_query($koneksicloud, $strQuery);
            $Jumbar=mysqli_num_rows($callstrQuery);
            if($Jumbar == 1){
                $strInsert="UPDATE dbo_table_user set is_login = 0 where kode_kasir = '" . $txtKodeKasirx . "'";
                $executeSQL=mysqli_query($koneksicloud, $strInsert); 

                if($executeSQL === false){
                    header("Location: profile-showmsg!save-failed");
                }else{
                    header("Location: profile-showmsg!save-success");
                }
            }            
        break;                  
    }
}else{
    echo "Belum Login.";
}