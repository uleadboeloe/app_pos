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
/*
$StoreName = "Amanmart - Kranggan";
$ftp_server = "id-7.hostddns.us";
$ftp_username = "aman9";
$ftp_password = "@123123";
$ftp_port = "10281";

$varKdStore = "A101";
$RemotePathTest = "/TEST 01/TESTDATA/";
$RemotePathMaster = "/MASTER/A101/";
$RemotePathTransaksi = "/TRANSAKSI/A101/";
$RemotePathMasterBC = "/MASTERBC/";
$RemotePathMasterLocal = "/MASTER/";*/
?>