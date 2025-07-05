<?php
include("../lib/mysql_pdo.php");
include("../lib/mysql_connect.php");
include("../lib/general_lib.php");
include("../admin/library/fungsi.php");

// Endpoint & Auth
$Company = "TEST";
$bcUrlHeader    = "http://64.20.58.66:7048/BC200/ODataV4/Company('$Company')/PRSHeader";
$bcUrlDetail    = "http://64.20.58.66:7048/BC200/ODataV4/Company('$Company')/PRSLine";
$bcUrlPayment   = "http://64.20.58.66:7048/BC200/ODataV4/Company('$Company')/PRSPayment";
$bcUser = "IT";
$bcPass = "It@123";

$NoStrukFailed = "";
$ItemLineFailed = "";
$PaymentFailed = "";

// Ambil record header hari ini & belum sinkron
// Ambil tanggal dari parameter GET, default ke hari ini jika tidak ada
//$Tanggal = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d');
$Tanggal = "2025-07-01";

$sql = "SELECT * FROM dbo_header WHERE tanggal = :tanggal AND fl_sync = -1 order by noid ASC";
$stmt = $db->prepare($sql);
$stmt->bindParam(':tanggal', $Tanggal);
$stmt->execute();
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);

// SYNC HEADER //
foreach ($records as $row) {

    $dataHeader = [
        "nomorstruk"           => $row['no_struk'],
        "kdstore"              => $row['kode_store'],
        "tanggal"              => $row['tanggal'],
        "jam"                  => $row['jam'],
        "kodekasir"            => $row['kode_kasir'],
        "namakasir"            => $row['nama_kasir'],
        "totalbayar"           => floatval($row['total_bayar']),
        "amount"               => floatval($row['total_struk']),
        "kembalian"            => floatval($row['kembalian']),
        "jenispembayaran"      => $row['jenis_bayar'],
        "namakartu"            => $row['nama_kartu'] ?? "",
        "kodecustomer"         => $row['kode_customer'] ?? "",
        "Cash"                 => floatval($row['var_cash']),
        "NonCash"              => floatval($row['var_noncash']),
        "Pembulatan"           => floatval($row['var_pembulatan']),
        "trntype"              => 0,
        "invoice_disc_Amount"  => floatval($row['var_diskon']),
        "member_tag"           => $row['status_customer'],
        "member_name"          => $row['nama_customer'],
        "member_phone_no"      => $row['telp_customer'],
    ];

    $jsonHeader = json_encode($dataHeader);
    // Kirim Header
    $ch = curl_init($bcUrlHeader);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonHeader);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json',
        'Content-Length: ' . strlen($jsonHeader)
    ]);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM); // NTLM Auth
    curl_setopt($ch, CURLOPT_USERPWD, "$bcUser:$bcPass");

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $errorNo = curl_errno($ch);
    $errorMsg = curl_error($ch);
    
    echo $jsonHeader . "<br>";
    echo "ERROR HTTP CODE HEADER : " . $httpCode . "#" . $errorNo . "#" . $errorMsg . "<br>";
    curl_close($ch);

    $no_struk = $row['no_struk'];
    $updateHeaderStmt = $db->prepare("UPDATE dbo_header SET fl_sync = :status WHERE no_struk = :no_struk");

    if ($errorNo || $httpCode != 201) {
        // gagal!
        //$NoStrukFailed = $NoStrukFailed . $no_struk . ", ";
        $NoStrukFailed = $NoStrukFailed . $row['noid'] . ", ";
        $updateHeaderStmt->execute([':status' => -1, ':no_struk' => $no_struk]);
        continue;
    }

    // berhasil!
    $updateHeaderStmt->execute([':status' => 1, ':no_struk' => $no_struk]);
} // foreach header



// SYNC DETAIL/LINE BY no_struk //
//$sqlDetail = "SELECT * FROM dbo_detail WHERE no_struk in(select no_struk from dbo_header where tanggal = :tanggal order by noid ASC) AND fl_sync = 0";
$sqlDetail = "SELECT * FROM dbo_detail WHERE no_struk in (SELECT no_struk FROM dbo_header WHERE tanggal = :tanggal) and  fl_sync = -1";
$stmtDetail = $db->prepare($sqlDetail);
$stmtDetail->execute([':tanggal' => $Tanggal]);
$details = $stmtDetail->fetchAll(PDO::FETCH_ASSOC);

$updateDetailStmt = $db->prepare("UPDATE dbo_detail SET fl_sync = :status WHERE noid = :noid");

foreach ($details as $detail) {

    $kode_barang = $detail['kode_barang'];

    if (strpos($kode_barang, '(P)FREETHIS') !== false) {
        $kode_barang = str_replace("(P)FREETHIS","",$kode_barang);
    }

    if (strpos($kode_barang, '(P)') !== false) {
        $kode_barang = getTextAfterP($kode_barang);
    }

    if($x > 0){
        $dataDetail = [
            "nomorstruk"         => $detail['no_struk'],
            "kdplu"              => $kode_barang,
            "itemno"             => $kode_barang,
            "lineno"             => $x,
            "quantity"           => floatval($detail['qty_sales']),
            "hargajual"          => floatval($detail['harga']),
            "satuan"             => $detail['satuan'],
            "lineamount"         => floatval($detail['total_sales']),
            "kodestore"          => $detail['kode_store'],
            "Item_No_"           => "",
            "disc_amount"        => floatval($detail['var_diskon']),
            "prs_uom_code"       => $detail['satuan'],
            "ppn_amount"         => 0,
            "total_netto"        => floatval($detail['netto_sales']),
            "Item_doesnt_exist"  => false
        ];
    }$x++;

    $jsonDetail = json_encode($dataDetail);

    $ch = curl_init($bcUrlDetail);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDetail);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json',
        'Content-Length: ' . strlen($jsonDetail)
    ]);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM); // NTLM Auth
    curl_setopt($ch, CURLOPT_USERPWD, "$bcUser:$bcPass");

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $errorNo = curl_errno($ch);
    $errorMsg = curl_error($ch);
    

    echo "ERROR HTTP CODE DETAIL : " . $httpCode . "#" . $errorNo . "#" . $errorMsg . "<br>";
    curl_close($ch);

    if ($errorNo || $httpCode != 201) {
        // gagal!
        //$ItemLineFailed = $ItemLineFailed . $detail['kode_barang'] . ", ";
        $ItemLineFailed = $ItemLineFailed . $detail['noid'] . ", ";
        $updateDetailStmt->execute([':status' => -1, ':noid' => $detail['noid']]);
    }
    else
        $updateDetailStmt->execute([':status' => 1, ':noid' => $detail['noid']]);

} // foreach detail / line

// SYNC PAYMENT //
$sqlPayment = "SELECT * FROM dbo_payment WHERE tanggal = :tanggal AND fl_sync = -1 order by noid ASC";
$stmtPayment = $db->prepare($sqlPayment);
$stmtPayment->bindParam(':tanggal', $Tanggal);
$stmtPayment->execute();
$payments = $stmtPayment->fetchAll(PDO::FETCH_ASSOC);

$updatePaymentStmt = $db->prepare("UPDATE dbo_payment SET fl_sync = :status WHERE noid = :noid");

foreach ($payments as $payment) {

    $dataPayment = [
        "docno"       => $payment['no_struk'],
        "paymethod"   => $payment['jenis_bayar'],
        "location"    => $payment['kode_store'],
        "docdate"     => $payment['tanggal'],
        "Amount"      => floatval($payment['total_bayar']),
        "kodeedc"     => $payment['kode_edc'] ?? "",
        "namaedc"     => $payment['nama_edc'] ?? "",
    ];

    $jsonPayment = json_encode($dataPayment);

    $ch = curl_init($bcUrlPayment);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayment);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json',
        'Content-Length: ' . strlen($jsonPayment)
    ]);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM); // NTLM Auth
    curl_setopt($ch, CURLOPT_USERPWD, "$bcUser:$bcPass");

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $errorNo = curl_errno($ch);
    $errorMsg = curl_error($ch);
    
    echo "ERROR HTTP CODE PAYMENT : " . $httpCode . "#" . $errorNo . "#" . $errorMsg . "<br>";
    curl_close($ch);

    if ($errorNo || $httpCode != 201) {
        // gagal!
        //$PaymentFailed = $PaymentFailed . $payment['no_struk'] . ", ";
        $PaymentFailed = $PaymentFailed . $payment['noid'] . ", ";
        $updatePaymentStmt->execute([':status' => -1, ':noid' => $payment['noid']]);
    }

    // berhasil!
    $updatePaymentStmt->execute([':status' => 1, ':noid' => $payment['noid']]);

} // foreach payment


echo json_encode(['Header failed' => $NoStrukFailed, 'Detail failed' => $ItemLineFailed, 'Payment failed' => $PaymentFailed]);

?>
