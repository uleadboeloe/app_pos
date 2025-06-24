<?php
include("../lib/mysql_pdo.php");
include("../lib/mysql_connect.php");

header('Content-Type: application/json');

$nostruk = $_GET['nostruk'];
$barcode = $_GET['barcode'];

function getKodeBarangByBarcode($db, $barcode) {
    $stmt = $db->prepare("SELECT kode_barang FROM dbo_barang WHERE barcode = :barcode LIMIT 1");
    $stmt->bindParam(':barcode', $barcode, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? $row['kode_barang'] : null;
}

function getNamaBarangByBarcode($db, $barcode) {
    $stmt = $db->prepare("SELECT nama_barang FROM dbo_barang WHERE barcode = :barcode LIMIT 1");
    $stmt->bindParam(':barcode', $barcode, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? $row['nama_barang'] : null;
}

$kodebarang = getKodeBarangByBarcode($db, $barcode);
$namabarang = getNamaBarangByBarcode($db, $barcode);

try {
    $stmt = $db->prepare("SELECT qty_sales FROM dbo_detail WHERE no_struk = :nostruk AND kode_barang = :kodebarang AND is_promo_item = 0 AND qty_voided = 0");
    $stmt->bindParam(':nostruk', $nostruk, PDO::PARAM_STR);
    $stmt->bindParam(':kodebarang', $kodebarang, PDO::PARAM_STR);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        echo json_encode(['success' => true, 'qty_sales' => $result['qty_sales'], 'nama_barang' => $namabarang]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Data not found']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

?>
