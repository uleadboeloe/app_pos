<?php
date_default_timezone_set("Asia/Bangkok");
$datedb = date("Y-m-d H:i:s");
$timedb = date("H:i:s");
$currdatedb = date("Y-m-d");
$currdatedbx = date("d/m/Y");
$datedb_plus_12_months = date("Y-m-d H:i:s", strtotime("+12 months", strtotime($datedb)));

$TitleApps = "InsanPOS";
$Salt = "PRSOnline@Amanah_#123";
$ApiGoogle = "AIzaSyDhZga4rJVijJoJpSSuWvLvo-Zdgd4sLH4";
$ImagesLogo = "images/logo_struk.png";
?>