<?php
include("../lib/mysql_pdo.php");
include("../lib/mysql_connect.php");
include("../lib/general_lib.php");

$proses_no = $_POST['proses_no'] ?? "-";
$current_no = $_POST['current_no'] ?? "-";
$is_recalled = $_POST['is_recalled'] ?? "-";
$current_lastno = $_POST['current_lastno'] ?? "-";
$source = $_POST['source'] ?? "-";

$savetxt = $datedb . "#simpan_log.php#" . $proses_no . "#" . $proses_no . "#" . $is_recalled . "#" . $current_lastno . "#" . $source;
//$myfile = file_put_contents('savedata.txt', $savetxt.PHP_EOL , FILE_APPEND | LOCK_EX);

switch ($source) {
    case "simpan_log":
        $log_description = "Simpan Log";
        break;
    case "update_log":
        $log_description = "Update Log";
        break;
    case "delete_log":
        $log_description = "Delete Log";
        break;
    default:
        $log_description = "Unknown Action";
}
?>