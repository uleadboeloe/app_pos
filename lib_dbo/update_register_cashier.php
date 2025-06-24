<?php
    include("../lib/mysql_pdo.php");
    include("../lib/mysql_connect.php");

    session_start();
    $kode_kasir = $_SESSION['SESS_user_id'];
    $kode_store = $_SESSION['SESS_kode_store'];
    $kode_register = $_GET['koderegister'];
    $denom = $_GET['denom'];

    $denom_arr = explode(',', $denom); var_dump($denom);

    date_default_timezone_set('Asia/Jakarta');

    $query = <<<SQL
        SELECT jenis_bayar AS Metode, 
            COUNT(total_struk) AS Jumlah, 
            SUM(total_struk) AS Total, 
            CASE WHEN jenis_bayar = "CASH" THEN SUM(total_bayar - kembalian) ELSE 0 END AS Tunai, 
            IFNULL(SUM(var_poin),0) AS Poin, 
            IFNULL(SUM(var_voucher),0) AS Voucher
        FROM dbo_header
        WHERE tanggal = CURDATE() AND kode_store = :kodestore AND kode_kasir = :kodekasir
        GROUP BY jenis_bayar
        ORDER BY jenis_bayar
    SQL;

    $stmt = $db->prepare($query);
    $stmt->execute(['kodestore' => $kode_store, 'kodekasir' => $kode_kasir]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $total_voucher = $results[0]['Voucher'];
    $total_poin    = $results[0]['Poin'];
    $setoran_akhir = $results[0]['Tunai'];

    foreach ($results as $row) {
        if ($row['Metode'] === 'CASH') {
            $jumlah_struk_cash = $row['Jumlah']; 
            $total_pembayaran_cash = $row['Total']; } 
        else
        if ($row['Metode'] === 'DEBIT') {
            $jumlah_struk_debit = $row['Jumlah']; 
            $total_pembayaran_debit = $row['Total']; }
        else
        if ($row['Metode'] === 'KREDIT') {
            $jumlah_struk_kredit = $row['Jumlah']; 
            $total_pembayaran_kredit = $row['Total']; }
        else
        if ($row['Metode'] === 'EWALET') {
            $jumlah_struk_qris = $row['Jumlah']; 
            $total_pembayaran_qris = $row['Total']; }
    }

    $updateQuery = <<<SQL
        UPDATE dbo_register
        SET jumlah_struk_cash = :jumlah_struk_cash,
            jumlah_struk_kredit = :jumlah_struk_kredit,
            jumlah_struk_debit = :jumlah_struk_debit,
            jumlah_struk_qris = :jumlah_struk_qris,
            total_pembayaran_cash = :total_pembayaran_cash,
            total_pembayaran_kredit = :total_pembayaran_kredit,
            total_pembayaran_debit = :total_pembayaran_debit,
            total_pembayaran_qris = :total_pembayaran_qris,
            total_voucher = :total_voucher,
            total_poin = :total_poin,
            setoran_akhir = :setoran_akhir,
            posting_user = :posting_user,
            posting_date = CURDATE(),
            closing_date = CURDATE(),
            closing_time = CURTIME(),
            closing_user = :closing_user,
            c100000 = :c100000,
            c50000  = :c50000,
            c20000  = :c20000,
            c10000  = :c10000,
            c5000   = :c5000,
            c2000   = :c2000,
            c1000   = :c1000,
            c1000k  = :c1000k,
            c500k   = :c500k,
            c200k   = :c200k,
            c100k   = :c100k
        WHERE kode_register = :kode_register
    SQL;

    $updateStmt = $db->prepare($updateQuery);
    $updateStmt->execute([
        'jumlah_struk_cash' => $jumlah_struk_cash,
        'jumlah_struk_kredit' => $jumlah_struk_kredit,
        'jumlah_struk_debit' => $jumlah_struk_debit,
        'jumlah_struk_qris' => $jumlah_struk_qris,
        'total_pembayaran_cash' => $total_pembayaran_cash,
        'total_pembayaran_kredit' => $total_pembayaran_kredit,
        'total_pembayaran_debit' => $total_pembayaran_debit,
        'total_pembayaran_qris' => $total_pembayaran_qris,
        'total_voucher' => $total_voucher,
        'total_poin' => $total_poin,
        'setoran_akhir' => $setoran_akhir,
        'posting_user' => $kode_kasir,
        'closing_user' => $kode_kasir,
        'kode_register' => $kode_register,
        'c100000'   => $denom_arr[0],
        'c50000'    => $denom_arr[1],
        'c20000'    => $denom_arr[2],
        'c10000'    => $denom_arr[3],
        'c5000'     => $denom_arr[4],
        'c2000'     => $denom_arr[5],
        'c1000'     => $denom_arr[6],
        'c1000k'    => $denom_arr[7],
        'c500k'     => $denom_arr[8],
        'c200k'     => $denom_arr[9],
        'c100k'     => $denom_arr[10]
    ]);

    if ($updateStmt->rowCount() > 0) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'no_changes']);
    }
?>