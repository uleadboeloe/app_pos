<?php
include("../lib/mysql_pdo.php");
include("../lib/mysql_connect.php");

$nostruk = $_GET['nostruk'];

$query = "UPDATE dbo_header SET is_voided = 1 WHERE no_struk = :no";
$stmt = $db->prepare($query);
$stmt->execute([':no' => $nostruk]);

// cek dbo_header ada pakai voucher / point kalau ada di return
// Nilai poin di-set ke 0 untuk member, referensi struk, dan tipe_poin=2
$sql_check = "SELECT no_struk, kode_customer, var_poin, var_voucher FROM dbo_header WHERE no_struk = :no";
$stmt_check = $db->prepare($sql_check);
$stmt_check->execute([':no' => $nostruk]);

while ($result = $stmt_check->fetch(PDO::FETCH_ASSOC)) {
    $varNoStruk = $result['no_struk'];
    $varKodeCustomer = $result['kode_customer'];
    $varVoucher = $result['var_voucher'];

    // jika ada voucher   
    if($varVoucher != "") {
        $queryUpdateVoucherUsed = "UPDATE dbo_voucher SET nominal_voucher = nominal_real_voucher,nominal_pakai = 0,fl_active = 1 WHERE no_struk = :no_struk";
        $stmtUpdateVoucherUsed = $db->prepare($queryUpdateVoucherUsed);
        $stmtUpdateVoucherUsed->execute([':no_struk' => $varNoStruk]);
    }

    // jika ada poin   
    $queryUpdatePoinUsed = "UPDATE dbo_poin_member SET nilai_poin = 0,fl_active = 0 WHERE member_id = :memberid AND refer_id = :referid";
    $stmtUpdatePoinUsed = $db->prepare($queryUpdatePoinUsed);
    $stmtUpdatePoinUsed->execute([
        ':memberid' => $varKodeCustomer,
        ':referid' => $varNoStruk
    ]); 
}

$queryDetail = "UPDATE dbo_detail SET qty_voided = qty_sales WHERE no_struk = :no";
$stmtDetail = $db->prepare($queryDetail);
$stmtDetail->execute([':no' => $nostruk]);

if ($stmtDetail) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
?>