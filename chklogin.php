<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include("lib/mysql_pdo.php");
include("lib/mysql_connect.php");
include("lib/general_lib.php");

$myuserid=$_POST['myuserid'];
$mypassword=$_POST['mypassword'];

$myuserid = stripslashes($myuserid);
$mypassword = stripslashes($mypassword);

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