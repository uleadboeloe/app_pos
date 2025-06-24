<?php
include("../lib/mysql_pdo.php");
include("../lib/mysql_connect.php");
include("../lib/general_lib.php");

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
$Tanggal = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d');

$sql = "SELECT * FROM dbo_header WHERE tanggal = :tanggal AND fl_sync = 0";
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
        "totalbayar"           => $row['total_bayar'],
        "amount"               => $row['total_struk'],
        "kembalian"            => $row['kembalian'],
        "jenispembayaran"      => $row['jenis_bayar'],
        "namakartu"            => "",
        "kodecustomer"         => "",
        "Cash"                 => 0,
        "NonCash"              => 0,
        "Pembulatan"           => 0,
        "trntype"              => 0,
        "invoice_disc_Amount"  => 0,
        "member_tag"           => "",
        "member_name"          => "",
        "member_phone_no"      => ""
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
    curl_close($ch);

    $no_struk = $row['no_struk'];
    $updateHeaderStmt = $db->prepare("UPDATE dbo_header SET fl_sync = :status WHERE no_struk = :no_struk");

    if ($errorNo || $httpCode != 201) {
        // gagal!
        $NoStrukFailed = $NoStrukFailed . $no_struk . ", ";
        $updateHeaderStmt->execute([':status' => -1, ':no_struk' => $no_struk]);
        continue;
    }

    // berhasil!
    $updateHeaderStmt->execute([':status' => 1, ':no_struk' => $no_struk]);

    // SYNC DETAIL/LINE BY no_struk //
    $sqlDetail = "SELECT * FROM dbo_detail WHERE no_struk = :no_struk AND fl_sync = 0";
    $stmtDetail = $db->prepare($sqlDetail);
    $stmtDetail->execute([':no_struk' => $no_struk]);
    $details = $stmtDetail->fetchAll(PDO::FETCH_ASSOC);

    $updateDetailStmt = $db->prepare("UPDATE dbo_detail SET fl_sync = :status WHERE noid = :noid");

    foreach ($details as $detail) {

        $dataDetail = [
            "nomorstruk"         => $detail['no_struk'],
            "kdplu"              => $detail['sku_barang'] === null ? $detail['kode_barang'] : $detail['sku_barang'],
            "itemno"             => $detail['kode_barang'],
            "quantity"           => $detail['qty_sales'],
            "hargajual"          => $detail['harga'],
            "satuan"             => $detail['satuan'],
            "lineamount"         => $detail['total_sales'],
            "kodestore"          => $detail['kode_store'],
            "Item_No_"           => "",
            "disc_amount"        => 0,
            "prs_uom_code"       => "",
            "ppn_amount"         => 0,
            "total_netto"        => 0,
            "Item_doesnt_exist"  => false
        ];

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
        curl_close($ch);

        if ($errorNo || $httpCode != 201) {
            // gagal!
            $ItemLineFailed = $ItemLineFailed . $detail['kode_barang'] . ", ";
            $updateDetailStmt->execute([':status' => -1, ':noid' => $detail['noid']]);
        }
        else
            $updateDetailStmt->execute([':status' => 1, ':noid' => $detail['noid']]);

    } // foreach detail / line
} // foreach header

// SYNC PAYMENT //
$sqlPayment = "SELECT * FROM dbo_payment WHERE tanggal = :tanggal AND fl_sync = 0";
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
        "Amount"      => $payment['total_bayar'],
        "kodeedc"     => "",
        "namaedc"     => ""
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
    curl_close($ch);

    if ($errorNo || $httpCode != 201) {
        // gagal!
        $PaymentFailed = $PaymentFailed . $payment['no_struk'] . ", ";
        $updatePaymentStmt->execute([':status' => -1, ':noid' => $payment['noid']]);
    }

    // berhasil!
    $updatePaymentStmt->execute([':status' => 1, ':noid' => $payment['noid']]);

} // foreach payment

echo json_encode(['Header failed' => $NoStrukFailed, 'Detail failed' => $ItemLineFailed, 'Payment failed' => $PaymentFailed]);

?>
