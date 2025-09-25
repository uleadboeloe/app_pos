<?php
include("admin/library/connection.php");
include("admin/library/fungsi.php");
include("admin/library/parameter.php");

$Timestamp = str_replace(":","",$datedb);
$Timestamp = str_replace(" ","-",$Timestamp);
$Timestampx = str_replace("-","",$Timestamp);

//$PostUserID = $_SESSION['UserLoginid'];
//$PostUserPushID = $_SESSION['UserPushid'];
$postPushIDx = (trim($_POST['postDataUser'] ?? ""));
$NikUser = "9999999";
$postPrintIDx = (trim($_POST['postData'] ?? "0^0^0^0"));
$postDataAttributex = (trim($_POST['postDataAttribute'] ?? ""));

$varData = explode("^", $postPrintIDx);
$NoDokumen = $varData[0];
$KodeStore = $varData[1];
$KodeUser = $varData[2];
$Source = $varData[3];

$savetxt = $datedb . "#" . $postPushIDx . "#" . $NoDokumen . "#" . $KodeStore . "#" . $KodeUser . "#" . $Source;
$myfile = file_put_contents('logfile/print.txt', $savetxt.PHP_EOL , FILE_APPEND | LOCK_EX);

$strSQLlog="INSERT INTO dbo_print_proses(`proses_user`,`push_id`,`noid_proses`,`source_proses`,`posting_date`) VALUES ('$KodeUser','$postPushIDx','$NoDokumen','$Source','$datedb')";
$runSQLlog=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQLlog);
?>