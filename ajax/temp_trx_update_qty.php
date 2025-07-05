<?php
    include("../lib/mysql_pdo.php");
    include("../lib/mysql_connect.php");
    include("../admin/library/parameter.php");
    include("../admin/library/fungsi.php");

    $order_no = $_GET['orderno'];
    $kode_barang = $_GET['kode_barang'];
    $harga_jual = $_GET['harga_jual'];
    $disc = $_GET['disc'];
    $uom = $_GET['uom'];
    $qty = $_GET['qty'];

    // cek apakah barang sudah ada di transaksi, kecuali barang timbangan (uom <> KG)
    $sql = "SELECT * FROM temp_transaksi WHERE order_no = :order_no AND kode_barang = :kode_barang AND uom = :uom AND uom <> 'KG' AND status = 'CURRENT'";
    $stmt = $db->prepare($sql);
    $stmt->execute([':order_no' => $order_no, ':kode_barang' => $kode_barang, 'uom' => $uom]);
    $barang = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($barang) { // sudah ada di temp trx
        $new_qty = $barang['qty'] + $qty;
        $disc = $barang['disc'];

        $harga_jual_price_level = getHargaBerdasarkanQty($barang['kode_barang'], $new_qty, $db); // harga jual berdasarkan qty (price level)

        if ($harga_jual_price_level == 0) // jika tidak ada price level, ambil harga jual dari barang
        {
            $new_total_harga = $barang['harga_jual'] * $new_qty;
            $new_total_harga = $new_total_harga - ($new_total_harga * $disc / 100); // Hitung total harga baru dengan diskon
        }
        else // jika ada price level, ambil harga jual dari price level
        {
            $new_total_harga = $harga_jual_price_level * $new_qty;
            // price level tidak ada diskon, jadi tidak perlu dikurangi diskon
        }

        // update item utama
        $sql = "UPDATE temp_transaksi 
                SET qty = :qty, total_harga = :total_harga 
                WHERE order_no = :order_no AND kode_barang = :kode_barang and uom = :uom AND status = 'CURRENT'";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':qty' => $new_qty,
            ':total_harga' => $new_total_harga,
            ':order_no' => $order_no,
            ':kode_barang' => $kode_barang,
            ':uom' => $uom
        ]);
           
        $PromoBarang = cekPromoBarang($kode_barang,$uom);
        $PromoParameter = cekParameterPromoBarang($kode_barang,$uom);
        $KriteriaValue = cekKriteriaPromoBarang($kode_barang,$uom);
        $TagFreeItem = cekTagFreeItem($kode_barang,$uom);
        $QtyFreeItem = cekQtyFree($kode_barang,$uom);
        $QtyBarangOnTemp = cekQtyTempTransaksi($kode_barang,$uom,$order_no);
        $QtyFree = floor($QtyBarangOnTemp/$KriteriaValue);
        if($PromoBarang == "PRICELEVEL"){
            $isPromo = 2;
        }else{
            $isPromo = 1;
        }

        switch ($PromoParameter){
            case "A":
            break;
            case "B":
            break;
            case "C":
            case "F":
                // update item gratisan --yg kode yang ada kode (P)
                $kode_barangx = $kode_barang . '(P)' . $TagFreeItem;
                //echo "Item Promo Exist : " . $kode_barang . "<br>";
                $CekItemPromoExist = cekKodeBarangPromoTempTransaksi($kode_barangx,$uom,$order_no);
                //echo "Item Promo Exist : " . $CekItemPromoExist . "<br>";

                if($CekItemPromoExist > 0){
                    $sql = "UPDATE temp_transaksi 
                            SET qty = :qty
                            WHERE order_no = :order_no AND kode_barang = :kode_barang and uom = :uom AND status = 'CURRENT'";
                    $stmt = $db->prepare($sql);
                    $stmt->execute([
                        ':qty' => $QtyFree, //':qty' => $new_qty,
                        ':order_no' => $order_no,
                        ':kode_barang' => $kode_barangx,
                        ':uom' => $uom // wildcard untuk LIKE
                    ]);        
                }else{
                    //echo "MASUK INSERT LAGI NIH";
                    $total_harga = 0;
                    
                    if($QtyFree >0){
                        $sql = "INSERT INTO temp_transaksi 
                            (order_no, kode_barang, nama_barang, harga_jual, uom, qty, disc, total_harga, status, promo) 
                            VALUES 
                            (:order_no, :kode_barang, :nama_barang, :harga_jual, :uom, :qty, :disc, :total_harga, 'CURRENT', :promo)";
                        
                        $stmt = $db->prepare($sql);
                        $stmt->execute([
                            ':order_no' => $order_no,
                            ':kode_barang' => $kode_barangx,
                            ':nama_barang' => $nama_barang,
                            ':harga_jual' => $harga_jual,
                            ':uom' => $uom,
                            ':qty'=> $QtyFree, //':qty' => 1,
                            ':disc' => 100,
                            ':total_harga' => $total_harga,
                            ':promo' => $isPromo
                        ]);
                    }
                }            
            break;
            case "D":
                $kode_barangx = $kode_barang . '(P)' . $TagFreeItem;
                //echo "Item Promo Exist : " . $kode_barang . "<br>";
                $CekItemExist = cekKodeBarangPromoTempTransaksi($kode_barangx,$uom,$order_no);
                $CekUomExist = cekUomBarangPromoTempTransaksi($kode_barang,$uom,$order_no);
                $CekUomFreeItem = cekUomBarangFreeItem($TagFreeItem);
                //echo "Item Promo Exist : " . $CekItemExist . "<br>";
                //echo "Uom Exist : " . $CekUomExist . "<br>";

                if($CekItemExist > 0){
                    $sql = "UPDATE temp_transaksi 
                            SET qty = :qty
                            WHERE order_no = :order_no AND kode_barang = :kode_barang and uom = :uom AND status = 'CURRENT'";
                    $stmt = $db->prepare($sql);
                    $stmt->execute([
                        ':qty' => $QtyFree, //':qty' => $new_qty,
                        ':order_no' => $order_no,
                        ':kode_barang' => $kode_barangx,
                        ':uom' => $CekUomExist // wildcard untuk LIKE
                    ]);        
                }else{
                    $total_harga = 0;
                    
                    if($QtyFree >0){
                        $sql = "INSERT INTO temp_transaksi 
                            (order_no, kode_barang, nama_barang, harga_jual, uom, qty, disc, total_harga, status, promo) 
                            VALUES 
                            (:order_no, :kode_barang, :nama_barang, :harga_jual, :uom, :qty, :disc, :total_harga, 'CURRENT', :promo)";
                        
                        $stmt = $db->prepare($sql);
                        $stmt->execute([
                            ':order_no' => $order_no,
                            ':kode_barang' => $kode_barangx,
                            ':nama_barang' => $nama_barang,
                            ':harga_jual' => $harga_jual,
                            ':uom' => $uom,
                            ':qty'=> $QtyFree,//':qty' => 1,
                            ':disc' => 100,
                            ':total_harga' => $total_harga,
                            ':promo' => $isPromo
                        ]);
                    }
                }            
            break;
        } 

        /*
        $PromoBarang = cekPromoBarang($kode_barang,$uom);
        $PromoParameter = cekParameterPromoBarang($kode_barang,$uom);
        $KriteriaValue = cekKriteriaPromoBarang($kode_barang,$uom);
        $TagFreeItem = cekTagFreeItem($kode_barang,$uom);
        $QtyFreeItem = cekQtyFree($kode_barang,$uom);
        $QtyBarangOnTemp = cekQtyTempTransaksi($kode_barang,$uom,$order_no);
        $QtyFree = floor($QtyBarangOnTemp/$KriteriaValue);
        if($PromoBarang == "PRICELEVEL"){
            $isPromo = 2;
        }else{
            $isPromo = 1;
        }

        // update item gratisan --yg kode yang ada kode (P)
        $kode_barangx = $kode_barang . '(P)' . $TagFreeItem;

        $CekItemExist = cekKodeBarangPromoTempTransaksi($kode_barang,$uom,$order_no);
        $CekUomExist = cekUomBarangPromoTempTransaksi($kode_barang,$uom,$order_no);

        if($CekItemExist > 0){
            if($QtyFree == 0){
                $sql = "DELETE FROM temp_transaksi 
                        WHERE order_no = :order_no 
                        AND kode_barang = :kode_barang and uom = :uom";
        
                $stmt = $db->prepare($sql);
                $stmt->execute([
                    ':order_no' => $order_no,
                    ':kode_barang' => $kode_barangx,
                    ':uom' => $CekUomExist
                ]);
            }else{
                $sql = "UPDATE temp_transaksi 
                        SET qty = :qty
                        WHERE order_no = :order_no AND kode_barang = :kode_barang and uom = :uom AND status = 'CURRENT'";
                $stmt = $db->prepare($sql);
                $stmt->execute([
                    ':qty' => $QtyFree, //':qty' => $new_qty,
                    ':order_no' => $order_no,
                    ':kode_barang' => $kode_barangx,
                    ':uom' => $CekUomExist // wildcard untuk LIKE
                ]); 
            }
       
        }
        */
    }

    /*
    SCRIP ASLI BRO DIQY
    $harga_jual_price_level = getHargaBerdasarkanQty($kode_barang, $qty, $db); // harga jual berdasarkan qty (price level)
    if ($harga_jual_price_level != 0)
    {
        $harga_jual = $harga_jual_price_level; // jika ada price level, ambil harga jual dari price level
    }

    //$kode_barang = $kode_barang . '%'; // Menggunakan wildcard untuk LIKE

    $total_harga = $qty * $harga_jual;
    $total_harga = $total_harga - ($total_harga * $disc / 100); // Hitung total harga baru dengan diskon

    $update = $db->prepare("UPDATE temp_transaksi SET qty = :qty, total_harga = :total_harga WHERE order_no = :order_no AND kode_barang = :kode_barang and uom = :uom");
    $update->execute([
        ':qty' => $qty,
        ':total_harga' => $total_harga,
        ':order_no' => $order_no,
        ':kode_barang' => $kode_barang,
        ':uom' => $uom
    ]);
    */

    // harga berdasarkan promo price level
    function getHargaBerdasarkanQty($kode_barang, $qty_beli, $db) {

        $sql = "SELECT kode_barang, qty_jual, harga_jual FROM dbo_promo_detail WHERE kode_barang = :kode_barang";
        $stmt = $db->prepare($sql);
        $stmt->execute([':kode_barang' => $kode_barang]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $hargaList = [];

        // Filter data sesuai kode_barang
        foreach ($data as $row) {
            $hargaList[] = [
                'qty_jual' => (float)$row['qty_jual'],
                'harga_jual' => (float)$row['harga_jual']
            ];
        }

        // Urutkan min_qty dari terbesar ke terkecil
        usort($hargaList, function ($a, $b) {
            return $b['qty_jual'] - $a['qty_jual'];
        });

        // Cari harga yang sesuai dengan qty_beli
        foreach ($hargaList as $entry) {
            if ($qty_beli >= $entry['qty_jual']) {
                return $entry['harga_jual'];
            }
        }

        return 0; // Bisa juga return harga default atau 0
    }
?>
