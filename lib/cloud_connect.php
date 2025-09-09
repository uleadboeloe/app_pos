<?php
$HostName	= "localhost:3309";
$HostUser	= "root";
$HostPass	= "";
$HostDb	= "insanpos_db";
$koneksidb	= ($GLOBALS["___mysqli_ston_"] = mysqli_connect($HostName,  $HostUser,  $HostPass));
if (! $koneksidb) {
  echo "Koneksi Gagal Terhubung<br>";
  exit;
}
mysqli_select_db($GLOBALS["___mysqli_ston_"], $HostDb) or die ("Database Tidak Ditemukan, Silahkan Hubungi Customer Service Kami!");

$CloudHost	= "116.193.191.182";
$CloudUser	= "rootrmt";
$CloudPass	= "rootrmt";
$CloudDbs	= "ins_services";
$koneksicloud	= ($GLOBALS["___mysqli_ston__"] = mysqli_connect($CloudHost,  $CloudUser,  $CloudPass));
if (! $koneksicloud) {
  echo "<center><h1>Koneksi Cloud Gagal Terhubung</h1></center>";
  exit;
}
mysqli_select_db($GLOBALS["___mysqli_ston__"], $CloudDbs) or die ("Database Tidak Ditemukan, Silahkan Hubungi Customer Service Kami!");
?>