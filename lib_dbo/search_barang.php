<?php
include("../lib/mysql_pdo.php");
include("../lib/mysql_connect.php");
include("../lib/general_lib.php");

$keyword = $_GET['keyword'];

/*
code 1 : 
    $query = "SELECT b.kode_barang, b.nama_barang, b.uom, p.harga_jual
          FROM dbo_barang b
          JOIN dbo_price p ON b.barcode = p.barcode
          WHERE b.nama_barang LIKE :keyword
          LIMIT 20";
code 2 :
    $query = "SELECT b.kode_barang, b.barcode, b.nama_barang, b.sku_barang, p.uom, p.harga_jual
          FROM dbo_barang b
          JOIN dbo_price p ON b.sku_barang = p.sku_barang
          WHERE b.nama_barang LIKE :keyword or b.kode_barang LIKE :keyword or b.barcode LIKE :keyword
          LIMIT 50"; */      
    $query = "SELECT b.kode_barang, b.barcode, b.nama_barang, b.sku_barang, p.uom, p.harga_jual, c.`kode_promo`, d.`kriteria_promo`, d.`value_promo`, p.isi_kemasan
        FROM dbo_barang b
        JOIN dbo_price p ON b.sku_barang = p.sku_barang
        LEFT JOIN dbo_promo_detail c ON b.sku_barang = c.`sku_barang` AND p.uom = c.`uom`
        LEFT JOIN dbo_promo d ON c.`kode_promo` = d.`kode_promo`
        WHERE b.nama_barang LIKE :keyword OR b.kode_barang LIKE :keyword OR b.barcode LIKE :keyword
        LIMIT 50";

$stmt = $db->prepare($query);
$searchTerm = "%$keyword%";
$stmt->bindParam(':keyword', $searchTerm, PDO::PARAM_STR);
$stmt->execute();

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<tr>";
    echo "<td>{$row['kode_barang']}</td>";
    echo "<td>" . limitOnly45Chars($row['nama_barang']) . "</td>";
    echo "<td align='right'>{$row['harga_jual']}</td>";
    echo "<td>{$row['uom']}</td>";
    echo "<td>{$row['isi_kemasan']}</td>";
    echo "<td>{$row['kriteria_promo']}</td>";
    echo "<td>{$row['value_promo']}</td>";
    echo "</tr>";
}
?>
