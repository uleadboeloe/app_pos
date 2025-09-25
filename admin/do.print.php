<?php
include("library/connection.php");
include("library/fungsi.php");
include("library/parameter.php");

$Timestamp = str_replace(":","",$datedb);
$Timestamp = str_replace(" ","-",$Timestamp);
$Timestampx = str_replace("-","",$Timestamp);

//$PostUserID = $_SESSION['UserLoginid'];
//$PostUserPushID = $_SESSION['UserPushid'];
$postPushIDx = (trim($_POST['postDataUser']));
$NikUser = (trim($_POST['postDataUser']));
$postPrintIDx = (trim($_POST['postData']));
$postDataAttributex = (trim($_POST['postDataAttribute']));

$varData = explode("^", $postPrintIDx);
$NoIDProses = $varData[0];
$Source = $varData[1];

$savetxt = $datedb . "#" . $postPushIDx . "#" . $NikUser . "#" . $postPrintIDx . "#" . $postDataAttributex;
$myfile = file_put_contents('logfile/print.txt', $savetxt.PHP_EOL , FILE_APPEND | LOCK_EX);

//$strSQLlog="INSERT INTO dbo_print_proses(`proses_user`,`push_id`,`noid_proses`,`source_proses`,`posting_date`) VALUES ('$NikUser','$postPushIDx','$NoIDProses','$Source','$datedb')";
//$runSQLlog=mysqli_query($GLOBALS["___mysqli_ston"], $strSQLlog);
?>