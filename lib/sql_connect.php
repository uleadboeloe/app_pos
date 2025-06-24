<?php
$serverName = "188.88.1.7"; // IP dan DB instance BC
$connectionInfo = array( "Database"=>"SSI_ACCOUNTING", "UID"=>"rs", "PWD"=>"rainbow", "TrustServerCertificate"=>true);
$conn = sqlsrv_connect( $serverName, $connectionInfo);

if( !$conn ) 
{
     echo "Connection to BC server could not be established.<br />";
     die( print_r( sqlsrv_errors(), true));
}
?>