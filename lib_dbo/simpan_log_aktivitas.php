<?php
include("../admin/library/parameter.php");
include("../lib/mysql_pdo.php");
include("../lib/mysql_connect.php");
include("../lib/general_lib.php");

$proses_user = $_POST['proses_user'] ?? "-";
$log_description = $_POST['log_description'] ?? "-";
$log_tipe = $_POST['log_tipe'] ?? "-";
$source = $_POST['source'] ?? "-";
$kode_kasir = $_POST['kode_kasir'] ?? "-";
$kode_barang = $_POST['kode_barang'] ?? "-";
$kode_barcode = $_POST['kode_barcode'] ?? "-";
$uom_barang = $_POST['uom_barang'] ?? "-";
$kode_spv = $_POST['kode_spv'] ?? "-";
$kode_register = $_POST['kode_register'] ?? "-";
$no_struk = $_POST['no_struk'] ?? "-";
$harga_lama = $_POST['harga_lama'] ?? "0";
$harga_baru = $_POST['harga_baru'] ?? "0";
$diskon_lama = $_POST['diskon_lama'] ?? "0";
$diskon_baru = $_POST['diskon_baru'] ?? "0";
$qty_lama = $_POST['qty_lama'] ?? "0";
if($qty_lama <0){
    $qty_lama = $qty_lama*-1;
}
$qty_baru = $_POST['qty_baru'] ?? "0";
if($qty_baru <0){
    $qty_baru = $qty_baru*-1;
}

    /* Insert Log Data
    $savetxt = $datedb . "#simpan_log.php#" . $proses_user . "#" . $log_description . "#" . $log_tipe . "#" . $source;
    $myfile = file_put_contents('savedata_aktivitas.txt', $savetxt.PHP_EOL , FILE_APPEND | LOCK_EX);
    */
    $sql_log = "INSERT INTO dbo_log (log_description, log_type, create_at, log_user, source_data, kode_register, kode_kasir, kode_spv, kode_barang, kode_barcode, uom_barang, no_struk, harga_lama, harga_baru, diskon_lama, diskon_baru, qty_lama, qty_baru) 
            VALUES (:log_description, :log_type, :create_at, :log_user, :source_data, :kode_register, :kode_kasir, :kode_spv, :kode_barang, :kode_barcode, :uom_barang, :no_struk, :harga_lama, :harga_baru, :diskon_lama, :diskon_baru, :qty_lama, :qty_baru)";
    
    $stmt_log = $db->prepare($sql_log);
    $stmt_log->bindParam(':log_description', $log_description);
    $stmt_log->bindParam(':log_type', $log_tipe);
    $stmt_log->bindParam(':create_at', $datedb);
    $stmt_log->bindParam(':log_user', $proses_user);
    $stmt_log->bindParam(':source_data', $source);
    $stmt_log->bindParam(':kode_register', $kode_register);
    $stmt_log->bindParam(':kode_kasir', $kode_kasir);
    $stmt_log->bindParam(':kode_spv', $kode_spv);
    $stmt_log->bindParam(':kode_barang', $kode_barang);
    $stmt_log->bindParam(':kode_barcode', $kode_barcode);
    $stmt_log->bindParam(':uom_barang', $uom_barang);
    $stmt_log->bindParam(':no_struk', $no_struk);
    $stmt_log->bindParam(':harga_lama', $harga_lama);
    $stmt_log->bindParam(':harga_baru', $harga_baru);
    $stmt_log->bindParam(':diskon_lama', $diskon_lama);
    $stmt_log->bindParam(':diskon_baru', $diskon_baru);
    $stmt_log->bindParam(':qty_lama', $qty_lama);
    $stmt_log->bindParam(':qty_baru', $qty_baru);
    $stmt_log->execute();
    /* Insert Log Data */

    if ($stmt_log->execute()) {
        echo "Success to insert data.";
    } else {
        echo "Failed to insert data.";
    }

?>