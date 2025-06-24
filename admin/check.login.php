<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("library/connection.php");
include("library/parameter.php");
include("../lib/mysql_pdo.php");
include("../lib/mysql_connect.php");

$myuserid=$_POST['txtUid'];
$mypassword=$_POST['txtUserpass'];

$myuserid = stripslashes($myuserid);
$mypassword = stripslashes($mypassword);

// pretty much secure from sql injection
$stmt = $db->prepare("SELECT * FROM dbo_user WHERE userid = :userid AND fl_active = 1");
$stmt->execute(array(':userid' => $myuserid));
$results = $stmt->fetchAll();

//var_dump($results); exit();
$Passwords = crypt($mypassword, $Salt);

$valid =  password_verify ($mypassword, $results[0]['userpass']);
// password_verify() is compatible with crypt()
// https://onlinephp.io/password-verify
// https://onlinephp.io/crypt
/*
echo $myuserid . "<br>";
echo $mypassword . "<br>";
echo $results[0]['userpass'] . "<br>";
echo $valid . "<hr>";
exit();
*/

if($valid)
{
    $user_id = $results[0]["userid"];
    $user_name = $results[0]["nama_user"];
    $kode_kasir = $results[0]["kode_kasir"];
    $kode_store = $results[0]["kode_store"];
    $hak_akses = $results[0]["hak_akses"];


    session_regenerate_id();

    $_SESSION['SESS_user_id'] = $user_id;
    $_SESSION['SESS_user_name'] = $user_name;
    $_SESSION['SESS_kode_store'] = $kode_store;
    $_SESSION['SESS_kode_kasir'] = $kode_kasir;
    $_SESSION['SESS_hak_akses'] = $hak_akses;
    $_SESSION['sts'] = 'OK';

    session_write_close();

    if($hak_akses > 8) // jika user admin buka dashboard otherwise kasir
        header("location:dashboard");
    else
        header("location:dashboard");
        
    exit();
}
else {
    $_SESSION['ls'] = "nok";
    header("location:index.php");
    exit();
}

?>