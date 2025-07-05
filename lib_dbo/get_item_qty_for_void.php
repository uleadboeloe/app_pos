<?php
include("../lib/mysql_pdo.php");
include("../lib/mysql_connect.php");

header('Content-Type: application/json');

$nostruk = $_GET['nostruk'];
$barcode = $_GET['barcode'];

function getKodeBarangByBarcode($db, $barcode) {
    //$stmt = $db->prepare("SELECT kode_barang FROM dbo_barang WHERE barcode = :barcode LIMIT 1");
    $stmt = $db->prepare("SELECT sku_barang FROM dbo_barang WHERE barcode = :barcode LIMIT 1");
    $stmt->bindParam(':barcode', $barcode, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? $row['sku_barang'] : null;
}

function getUomBarangByBarcode($db, $barcode) {
    //$stmt = $db->prepare("SELECT kode_barang FROM dbo_barang WHERE barcode = :barcode LIMIT 1");
    $stmt = $db->prepare("SELECT uom FROM dbo_price WHERE barcode = :barcode LIMIT 1");
    $stmt->bindParam(':barcode', $barcode, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? $row['uom'] : null;
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
$uombarang = getUomBarangByBarcode($db, $barcode);
//echo $kodebarang;

try {
    $stmt = $db->prepare("SELECT qty_sales,harga,var_diskon,kode_barang,satuan FROM dbo_detail WHERE no_struk = :nostruk AND kode_barang = :kodebarang AND satuan = :uom AND is_promo_item = 0 AND qty_voided = 0");
    $stmt->bindParam(':nostruk', $nostruk, PDO::PARAM_STR);
    $stmt->bindParam(':kodebarang', $kodebarang, PDO::PARAM_STR);
    $stmt->bindParam(':uom', $uombarang, PDO::PARAM_STR);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        echo json_encode(['success' => true, 'qty_sales' => $result['qty_sales'], 'harga_barang' => $result['harga'], 'kode_barang' => $result['kode_barang'], 'uom_barang' => $result['satuan'], 'diskon_barang' => $result['var_diskon'], 'nama_barang' => $namabarang]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Data not found']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

?>
