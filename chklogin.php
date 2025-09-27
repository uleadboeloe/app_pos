<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include("lib/mysql_pdo.php");
include("lib/mysql_connect.php");
include("lib/general_lib.php");
include("lib/cloud_connect.php");
include("admin/library/parameter.php");

$Timestamp = str_replace(":","",$datedb);
$Timestamp = str_replace(" ","-",$Timestamp);
$Timestampx = str_replace("-","",$Timestamp);

$myuserid=$_POST['myuserid'];
$mypassword=$_POST['mypassword'];

$myuserid = stripslashes($myuserid);
$mypassword = stripslashes($mypassword);
$Passwords = crypt($txtPasswordx, $Salt);

/*
$strSQL1="SELECT * FROM `dbo_table_user` where userid = '" . $myuserid . "'";
$CallstrSQL1=mysqli_query($koneksicloud, $strSQL1);
$JumBar1=mysqli_num_rows($CallstrSQL1);
if ($JumBar1>0){
    while($recData1=mysqli_fetch_array($CallstrSQL1)){
        $varKodeStore = $recData1['kode_store'];
        $varUserPass = $recData1['userpass'];
        $varJobTitle = $recData1['job_title'];
        $varHakAkses = $recData1['hak_akses'];

        if($varIsLogin == 0){
            if($varKodeStore == $KodeStoreOffline){
                //echo "<h1>" . $varKodeStore . "#" . $varUserPass . "</h1>";
                $valid =  password_verify ($mypassword, $varUserPass );
                //echo $valid;
                if($valid){
                    $user_id = $recData1['userid'];
                    $user_name = $recData1['nama_user'];
                    $kode_kasir = $recData1['kode_kasir'];
                    $KodePrefix = $varKodeStore . "-";

                    $strUpdateLogUser="UPDATE dbo_table_user set is_login = 1, last_login = '$datedb' where userid = '" . $myuserid . "'";
                    $executeSQLx=mysqli_query($koneksicloud, $strUpdateLogUser); 

                    $strxQuery="SELECT * FROM dbo_user WHERE kode_store = '" . $varKodeStore . "' and kode_kasir = '" . $kode_kasir . "'";
                    $callstrxQuery=mysqli_query($koneksidb, $strxQuery);
                    $Jumbarx=mysqli_num_rows($callstrxQuery);
                    if($Jumbarx == 0){
                        $strInsert="INSERT INTO dbo_user(
                        `random_code`,`userid`,`userpass`,`kode_kasir`,`nama_user`,
                        `nomor_kontak`,`job_title`,`hak_akses`,
                        `posting_date`,`posting_user`,`kode_store`) VALUES (
                        '$Timestampx','$user_id','$varUserPass','$kode_kasir','$user_name',
                        '$txtNoKontakx','$varJobTitle','$varHakAkses',
                        '$datedb','SYSTEM','$varKodeStore')";
                        $executeSQL=mysqli_query($koneksidb, $strInsert); 
                    }
                    
                    
                    $strxQuery="SELECT * FROM dbo_noseries WHERE kode_store = '" . $varKodeStore . "' and kode_kasir = '" . $kode_kasir . "'";
                    $callstrxQuery=mysqli_query($koneksidb, $strxQuery);
                    $Jumbarx=mysqli_num_rows($callstrxQuery);
                    if($Jumbarx == 0){
                        $strInsertx="INSERT INTO dbo_noseries(`kode_store`,`kode_kasir`,`kode_noseries`,`kode_prefix`,`nomor_akhir`,`modul_pakai`,`posting_date`,`posting_user`) 
                        VALUES ('$varKodeStore','$kode_kasir','ORDER','$KodePrefix','1','SALES','$datedb','SYSTEM')";
                        $executeSQLx=mysqli_query($koneksidb, $strInsertx); 
                    }

                    //$db->exec("UPDATE dbo_user set is_login = 1 where userid = '" . $user_id . "'");
                    session_regenerate_id();

                    $_SESSION['SESS_user_id'] = $user_id;
                    $_SESSION['SESS_user_name'] = $user_name;
                    $_SESSION['SESS_kode_kasir'] = $kode_kasir;

                    $_SESSION['sts'] = 'OK';
                    session_write_close();

                    if($user_id == 'adm') // jika user admin buka dashboard otherwise kasir
                        header("location:dashboard/dashboard.php");
                    else
                    {
                        // truncate table temp transaksi
                        try {
                            //$db->exec("DELETE FROM temp_transaksi where order_no LIKE '%-" . $kode_kasir . "-%'");
                            header("location:kasir.php");
                        } catch (PDOException $e) {
                            echo "Gagal mengosongkan table temp transaksi: " . $e->getMessage();
                        }
                    }
                        
                    exit();
                }else{
                    $_SESSION['ls'] = "passinvalid";
                    header("location:index.php");
                    exit(); 
                }
            }else{
                $_SESSION['ls'] = "storeinvalid";
                header("location:index.php");
                exit();
            }
        }else{
                $_SESSION['ls'] = "useractive";
                header("location:index.php");
                exit();
        }

    }
}else{
    $_SESSION['ls'] = "userna";
    header("location:index.php");
    exit();
}

exit();
*/

// pretty much secure from sql injection
$stmt = $db->prepare("SELECT * FROM dbo_user WHERE userid = :userid AND fl_active = 1 and is_login = 0");
$stmt->execute(array(':userid' => $myuserid));
$results = $stmt->fetchAll();

//var_dump($results); exit();

$valid =  password_verify ($mypassword, $results[0]['userpass'] );

// password_verify() is compatible with crypt()
// https://onlinephp.io/password-verify
// https://onlinephp.io/crypt

/*
var_dump($myuserid);
var_dump($mypassword);
var_dump($results[0]['userpass']);
var_dump($valid);
exit();
*/

if($valid)
{
    $user_id = $results[0]["userid"];
    $user_name = $results[0]["nama_user"];
    $kode_kasir = $results[0]["kode_kasir"];

    $db->exec("UPDATE dbo_user set is_login = 1 where userid = '" . $user_id . "'");
    session_regenerate_id();

    $_SESSION['SESS_user_id'] = $user_id;
    $_SESSION['SESS_user_name'] = $user_name;
    $_SESSION['SESS_kode_kasir'] = $kode_kasir;

    $_SESSION['sts'] = 'OK';
    session_write_close();

    if($user_id == 'adm') // jika user admin buka dashboard otherwise kasir
        header("location:dashboard/dashboard.php");
    else
    {
        // truncate table temp transaksi
        try {
            //$db->exec("DELETE FROM temp_transaksi where order_no LIKE '%-" . $kode_kasir . "-%'");
            header("location:kasir.php");
        } catch (PDOException $e) {
            echo "Gagal mengosongkan table temp transaksi: " . $e->getMessage();
        }
    }
        
    exit();
}
else {
    $_SESSION['ls'] = "nok";
    header("location:index.php");
    exit();
}
?>