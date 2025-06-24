<?php
include_once "library/connection.php";
include_once "library/parameter.php";
include_once "library/fungsi.php";

header('Content-Type: application/json');

$StrViewQuery="SELECT * from dbo_member where fl_active = 1";
$callStrViewQuery=mysqli_query($koneksidb, $StrViewQuery);
mysqli_data_seek($callStrViewQuery, 0); // reset pointer
$promoList = [];
while($recView = mysqli_fetch_assoc($callStrViewQuery)) {
    $promoList[] = $recView;
}
echo json_encode($promoList, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>