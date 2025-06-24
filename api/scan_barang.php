<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

include("../lib/mysql_pdo.php");
include("../lib/mysql_connect.php");

date_default_timezone_set('Asia/Jakarta');

// Ambil keyword dari GET
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';

if (empty($keyword)) {
    echo json_encode(["status" => false, "message" => "Keyword is required"]);
    exit();
}

// Tanggal hari ini
$tanggal_hari_ini = date('Y-m-d');

// Query hanya berdasarkan barcode pada dbo_price, join ke dbo_barang
$sql = "
    SELECT 
        b.sku_barang,
        b.kode_barang,
        b.nama_barang,
        p.uom,
        p.harga_jual,
        0 AS gram_barang
    FROM 
        dbo_price p
    LEFT JOIN 
        dbo_barang b ON p.sku_barang = b.sku_barang
    WHERE 
        p.fl_active = 1
        AND p.start_date <= :tanggal_hari_ini
        AND p.end_date >= :tanggal_hari_ini
        AND p.barcode = :keyword
";

$stmt = $db->prepare($sql);
$searchTerm = "$keyword";
$stmt->bindParam(':keyword', $searchTerm, PDO::PARAM_STR);
$stmt->bindParam(':tanggal_hari_ini', $tanggal_hari_ini, PDO::PARAM_STR);
$stmt->execute();
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if ($data) {
    echo json_encode([
        "status" => true,
        "message" => "Data ditemukan",
        "data" => $data
    ]);
} else {
    // cek barangkali barcode untuk tipe barang fresh / timbangan
    if (substr($keyword, 0, 1) === '2') {
        $kode_barang = substr($keyword, 1, 5);
        $gram_barang = (float) substr($keyword, 6, 6);

        /*$sql_fresh = "
            SELECT 
            p.harga_jual,
            p.uom,
            b.nama_barang
            FROM 
            dbo_price p
            LEFT JOIN 
            dbo_barang b ON p.sku_barang = b.sku_barang
            WHERE 
            p.kode_barang = :kode_barang
            AND p.fl_active = 1
            AND p.start_date <= :tanggal_hari_ini
            AND p.end_date >= :tanggal_hari_ini
        ";*/
        
        $sql_fresh = "SELECT b.kode_barang, b.barcode, b.nama_barang, b.sku_barang, p.uom, p.harga_jual, c.`kode_promo`, d.`kriteria_promo`, d.`value_promo`
        FROM dbo_barang b
        JOIN dbo_price p ON b.sku_barang = p.sku_barang
        LEFT JOIN dbo_promo_detail c ON b.sku_barang = c.`sku_barang` AND p.uom = c.`uom`
        LEFT JOIN dbo_promo d ON c.`kode_promo` = d.`kode_promo`
        WHERE p.kode_barang = :kode_barang
            AND p.fl_active = 1
            AND p.start_date <= :tanggal_hari_ini
            AND p.end_date >= :tanggal_hari_ini";


        $stmt_fresh = $db->prepare($sql_fresh);
        $stmt_fresh->bindParam(':kode_barang', $kode_barang, PDO::PARAM_STR);
        $stmt_fresh->bindParam(':tanggal_hari_ini', $tanggal_hari_ini, PDO::PARAM_STR);
        $stmt_fresh->execute();
        $result_fresh = $stmt_fresh->fetch(PDO::FETCH_ASSOC);

        if ($result_fresh) {
            echo json_encode([
                "status" => true,
                "message" => "Data ditemukan",
                "data" => [
                    "kode_barang" => $kode_barang,
                    "gram_barang" => $gram_barang,
                    "harga_jual" => $result_fresh['harga_jual'],
                    "nama_barang" => $result_fresh['nama_barang'],
                    "uom" => $result_fresh['uom'],
                ]
            ]);
            exit();
        }
    }

    echo json_encode([
        "status" => false,
        "message" => "Data tidak ditemukan",
        "data" => []
    ]);
}

?>
