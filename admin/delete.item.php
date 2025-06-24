<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include "library/parameter.php";
include "library/connection.php";
include "library/fungsi.php";

ini_set('max_execution_time', 36000); // 5 minutes

$Timestamp = str_replace(":","",$datedb);
$Timestamp = str_replace(" ","-",$Timestamp);
$Timestampx = str_replace("-","",$Timestamp);

$Userid = $_SESSION['SESS_user_id'];
$sourcex = (trim($_POST['source']));
$itemid = (trim($_POST['itemid']));

switch ($sourcex){
    case "promodetail":
        $strQuery="SELECT * FROM dbo_promo_detail WHERE noid = '" . $itemid . "'";
        $callstrQuery=mysqli_query($koneksidb, $strQuery);
        $Jumbar=mysqli_num_rows($callstrQuery);
        if($Jumbar > 0){
            $strDelete="DELETE FROM dbo_promo_detail WHERE noid = '" . $itemid . "'";
            $executeSQL=mysqli_query($koneksidb, $strDelete); 
        }
    break;
}

?>