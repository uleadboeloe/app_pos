<?php
    include("../lib/mysql_pdo.php");
    include("../lib/mysql_connect.php");

    $order_no = $_GET['orderno'];
    $kode_barang = $_GET['kode_barang'];
    $harga_jual = $_GET['harga_jual'];
    $disc = $_GET['disc'];
    $uom = $_GET['uom'];
    $qty = $_GET['qty'];

    // Fungsi untuk mengambil qty dari temp_transaksi
    /*
    function getQtyTempTransaksi($db, $order_no, $kode_barang, $uom) {
        $sql = "SELECT qty FROM temp_transaksi WHERE order_no = :order_no AND kode_barang = :kode_barang AND uom = :uom";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':order_no' => $order_no,
            ':kode_barang' => $kode_barang,
            ':uom' => $uom
        ]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? (int)$result['qty'] : 0;
    }
    $qty = getQtyTempTransaksi($db, $order_no, $kode_barang, $uom);
    */
    // TIDAK BERLAKU KARENA SUDAH DIKIRIM DARI DIALOGBOX

    $total_harga = $qty * $harga_jual;
    $total_harga = $total_harga - ($total_harga * $disc / 100); // Hitung total harga baru dengan diskon

    if ($uom == "KG") // barang timbang, where clause pakai qty! untuk menghindari update lebih dari 1 record
    {
        $update = $db->prepare("UPDATE temp_transaksi SET harga_jual = :harga_jual, disc = :disc, total_harga = :total_harga WHERE order_no = :order_no AND kode_barang = :kode_barang and qty = :qty");
        $update->execute([
            ':harga_jual' => $harga_jual,
            ':qty' => $qty,
            ':disc' => $disc,
            ':total_harga' => $total_harga,
            ':order_no' => $order_no,
            ':kode_barang' => $kode_barang
        ]);
    }
    else
    {
        $update = $db->prepare("UPDATE temp_transaksi SET harga_jual = :harga_jual, qty = :qty, disc = :disc, total_harga = :total_harga WHERE order_no = :order_no AND kode_barang = :kode_barang and uom =:uom");
        $update->execute([
            ':harga_jual' => $harga_jual,
            ':qty' => $qty,
            ':disc' => $disc,
            ':total_harga' => $total_harga,
            ':order_no' => $order_no,
            ':kode_barang' => $kode_barang,
            ':uom' => $uom
        ]);
    }
?>
