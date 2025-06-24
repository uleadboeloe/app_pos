<?php
    include("../lib/mysql_pdo.php");
    include("../lib/mysql_connect.php");
    include("../lib/general_lib.php");
    include("../lib_dbo/user_functions.php");
    include("../admin/library/parameter.php");

    date_default_timezone_set('Asia/Jakarta');
    $kode_kasir = $_GET['kodekasir'];
    $kode_store = $_GET['kodestore'];
    $kode_spv = $_GET['kodespv'];
    $modal_awal = $_GET['modalawal'];
    $tanggal = date('Y-m-d');
    $jam = date('H:i:s');


    // TIDAK ADA PENGECEKAN APAKAH SUDAH ADA REGISTER BERJALAN KARENA SUDAH DICEK PADA get_register_cashier.php !!!

    $sql = "INSERT INTO dbo_register (kode_register, kode_kasir, kode_store, tanggal, jam, modal_awal, kode_spv) 
            VALUES (:kode_register, :kode_kasir, :kode_store, :tanggal, :jam, :modal_awal, :kode_spv)";
    
    $kode_register = CreateUniqueHash16();
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':kode_register', $kode_register);
    $stmt->bindParam(':kode_kasir', $kode_kasir);
    $stmt->bindParam(':kode_store', $kode_store);
    $stmt->bindParam(':tanggal', $tanggal);
    $stmt->bindParam(':jam', $jam);
    $stmt->bindParam(':modal_awal', $modal_awal);
    $stmt->bindParam(':kode_spv', $kode_spv);

    /* Insert Log Data */
    $log_description = "Approval Open Register: " . $kode_spv . "#Modal Awal: " . $modal_awal . "#Kode Kasir: " . $kode_kasir . "#Kode Store: " . $kode_store . "#Kode Register: " . $kode_register;
    $log_tipe = "INFO";
    $source = "insert_register_cashier";
    $sql_log = "INSERT INTO dbo_log (log_description, log_type, create_at, log_user, source_data, kode_register, kode_kasir, kode_spv) 
            VALUES (:log_description, :log_type, :create_at, :log_user, :source_data, :kode_register, :kode_kasir, :kode_spv)";
    
    $stmt_log = $db->prepare($sql_log);
    $stmt_log->bindParam(':log_description', $log_description);
    $stmt_log->bindParam(':log_type', $log_tipe);
    $stmt_log->bindParam(':create_at', $datedb);
    $stmt_log->bindParam(':log_user', $kode_spv);
    $stmt_log->bindParam(':source_data', $source);
    $stmt_log->bindParam(':kode_register', $kode_register);
    $stmt_log->bindParam(':kode_kasir', $kode_kasir);
    $stmt_log->bindParam(':kode_spv', $kode_spv);
    $stmt_log->execute();
    /* Insert Log Data */
    
    $_SESSION['SESS_kode_register'] = $kode_register;
    
    if ($stmt->execute()) {
       echo json_encode(['kode_register' => $kode_register]);
    } else {
        echo "Failed to insert data.";
    }
?>