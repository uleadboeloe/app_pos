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
$StrMstItem="SELECT `card`,`init` FROM `_card` where debet_kred = 'D' group by `card`";
$CallStrMstItem=mysqli_query($koneksidb, $StrMstItem);
$Jumbar=mysqli_num_rows($CallStrMstItem);
while($recResult=mysqli_fetch_array($CallStrMstItem))
{
    $varCard = $recResult['card'];
    $varInit = $recResult['init'];     

    if(($varCard != "0") && ($varCard != "")){
        echo "<h3>" . $varCard . "#" . $varInit . "</h3>";
        
        $StrCekPrice="SELECT * FROM dbo_kartu where nama_kartu = '" . $varCard . "'";
        $CallStrCekPrice=mysqli_query($koneksidb, $StrCekPrice);
        $JumbarPrice=mysqli_num_rows($CallStrCekPrice);
        //echo "<div style='color:#0000FF;'>" . $StrCekPrice . "</div>";
        if ($JumbarPrice === 0 ){
            $strSQLUpdateHJ="INSERT INTO dbo_kartu(`debet_kredit`,`nama_kartu`,`init_bank`,`posting_date`,`posting_user`) VALUES 
            ('D','$varCard','$varInit','$datedb','ADMIN')";
            $executeSQL=mysqli_query($koneksidb, $strSQLUpdateHJ);
            echo "<div style='color:#FF0099;'>MASUKIN HARGA JUAL TIMBANG & UOM BELUM ADA : #" . $strSQLUpdateHJ . "</div>";
        }
    }
    

    /*
    */
}

    

?>
