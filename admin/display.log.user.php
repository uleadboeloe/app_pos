<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include_once "library/connection.php";
include_once "library/parameter.php";
include_once "library/fungsi.php";

echo "<div class='font-bold text-primary'>Log User Activity " . $datedb . "</div>";
$StrViewQuery="SELECT * from dbo_log where date(create_at) = '$currdatedb' order by noid desc limit 10";
//echo $StrViewQuery . "<hr>";     
$callStrViewQuery=mysqli_query($koneksidb, $StrViewQuery);
while($recView=mysqli_fetch_array($callStrViewQuery))
{
    $varNoid = $recView['noid'];
    $varLogDescription = $recView['log_description'];
    $varLogType = $recView['log_type'];
    $varLogUser = $recView['log_user'];
    $varNamaLogUser = getNamaUser($varLogUser);
    $varSource = $recView['source_data'];

    switch ($varLogType) {
        case 'INFO':
            $varStyle = "bg-info text-white my-1 p-2";
            break;
        case 'WARNING':
            $varStyle = "bg-warning text-white my-1 p-2";
            break;
        case 'DANGER':
            $varStyle = "bg-error text-white my-1 p-2";
            break;
        case 'SUCCESS':
            $varStyle = "bg-success text-white my-1 p-2";
            break;
    }

    echo "<div class='" . $varStyle . "'>" . $varLogDescription . " # " . $varLogType . " # " . $varLogUser . " - " . $varNamaLogUser . " # " . $varSource . "</div>";
}
?>