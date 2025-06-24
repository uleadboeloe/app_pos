<?php
    include("../lib/mysql_pdo.php");
    include("../lib/mysql_connect.php");
    include("../lib/general_lib.php");
    include("../lib_dbo/user_functions.php");

    session_start();

    date_default_timezone_set('Asia/Jakarta');
    $kode_kasir = $_GET['kodekasir'];
    $kode_store = $_GET['kodestore'];
    $tanggal = date('Y-m-d');

    $sql_check = "SELECT kode_register, modal_awal, tanggal, jam FROM dbo_register 
                  WHERE kode_kasir = :kode_kasir AND kode_store = :kode_store AND tanggal = :tanggal AND setoran_akhir = 0";
    $stmt_check = $db->prepare($sql_check);
    $stmt_check->bindParam(':kode_kasir', $kode_kasir);
    $stmt_check->bindParam(':kode_store', $kode_store);
    $stmt_check->bindParam(':tanggal', $tanggal);
    $stmt_check->execute();

    $result = $stmt_check->fetch(PDO::FETCH_ASSOC);

    // Jika sudah ada sesi berdasarkan kode_kasir, kode_store, tanggal sekarang, setoran_akhir = NULL
    // ( karena barangkali ada kendala register -- mati lampu / system crash )
    if ($result) 
    {
        $_SESSION['SESS_kode_register'] = $result['kode_register']; 
        echo json_encode([
            'kode_register' => $result['kode_register'],
            'modal_awal' => $result['modal_awal'],
            'tanggal' => $result['tanggal'],
            'jam' => $result['jam'],
            'sudah_ada' => 1
        ]);
    }
    else // belum ada sesi berdasarkan kode_kasir, kode_store, tanggal sekarang, setoran_akhir = NULL
        echo json_encode(['sudah_ada' => 0]);

?>
