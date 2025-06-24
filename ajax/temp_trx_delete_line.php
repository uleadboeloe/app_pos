<?php
    include("../lib/mysql_pdo.php");
    include("../lib/mysql_connect.php");

    $order_no = $_POST['order_no'] ?? '';
    $kode_barang = $_POST['kode_barang'] ?? '';
    $uom = $_POST['uom'] ?? '';

    $response = [
        'success' => false,
        'message' => ''
    ];
    
    // Validasi sederhana
    if (!empty($order_no) && !empty($kode_barang)) {
        try {
            $sql = "DELETE FROM temp_transaksi 
                    WHERE order_no = :order_no 
                    AND kode_barang LIKE :kode_barang and uom = :uom";
    
            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':order_no' => $order_no,
                ':kode_barang' => $kode_barang . '%',
                ':uom' => $uom
            ]);
    
            if ($stmt->rowCount() > 0) {
                $response['success'] = true;
                $response['message'] = "Data berhasil dihapus.";
            } else {
                $response['message'] = "Tidak ada data yang cocok untuk dihapus.";
            }
    
        } catch (PDOException $e) {
            $response['message'] = "Gagal menghapus data: " . $e->getMessage();
        }
    } else {
        $response['message'] = "Parameter tidak lengkap.";
    }
    
    // Kembalikan hasil
    header('Content-Type: application/json');
    echo json_encode($response);
?>
