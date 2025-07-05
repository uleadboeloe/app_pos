<?php
include("../lib/mysql_pdo.php");
include("../lib/mysql_connect.php");
include("../admin/library/parameter.php");
include("../admin/library/fungsi.php");

date_default_timezone_set('Asia/Jakarta');
$order_no = $_POST['order_no'];
$kode_barang = $_POST['kode_barang'];
$nama_barang = $_POST['nama_barang'];
$harga_jual = $_POST['harga_jual'];
$uom = $_POST['uom'];
$qty = $_POST['qty']; 
//echo "Qty Insert " . $qty;

$flag_free_this = false;
$flag_free_item = false;
$free_item = '';

$promo_data = [];

$trxStatus = 'ok';

// cek apakah barang sudah ada di transaksi, kecuali barang non timbangan (uom <> KG)
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
                        ':harga_jual' => $harga_jual ?? "99999999",
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
                        ':harga_jual' => $harga_jual ?? "99999999",
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

}
else // barang belum ada di transaksi
{
    // default
    $promo_disc = 0;
    $isPromo = 0;

    //echo "TIMBANG MASUK SINI NIH 1<br>";
    // Cek apakah ada kode promo untuk barang ini
    $sql = "SELECT kode_barang, kode_promo, qty_jual, harga_jual, uom FROM dbo_promo_detail WHERE kode_barang = :kode_barang and uom = :uom";
    $stmt = $db->prepare($sql);
    $stmt->execute([':kode_barang' => $kode_barang,':uom' => $uom]);
    // cek promo PRICE LEVEL (memiliki lebih dari 1 record)
    $rowcount = $stmt->rowCount();
    $promo = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($rowcount > 1) 
    {
        // cek apakah masih berlaku pada hari ini
        $kode_promo = $promo['kode_promo'];
        $hari_ini_index = date('N'); // Mendapatkan index hari (1 = Senin, 7 = Minggu)
        $hari_array = ['SENIN', 'SELASA', 'RABU', 'KAMIS', 'JUMAT', 'SABTU', 'MINGGU'];
        $hari_ini = '%' . $hari_array[$hari_ini_index - 1] . '%'; // Menggunakan wildcard untuk LIKE
        
        // Cek apakah promo masih berlaku
        $sql = "SELECT * FROM dbo_promo 
            WHERE kode_promo = :kode_promo and fl_active = 1
            AND CURDATE() BETWEEN promo_start_date AND promo_end_date 
            AND promo_day LIKE :hari_ini";
        $stmt = $db->prepare($sql);
        $stmt->execute([':kode_promo' => $kode_promo, ':hari_ini' => $hari_ini]);
        $promo_elements = $stmt->fetch(PDO::FETCH_ASSOC);

        // apabila masih berlaku pada hari ini
        $isPromo = 2;
        if ($promo_elements){
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $promo_data[] = $row;
            }
            $harga_jual = $promo_data[0]['harga_jual']; // Ambil harga_jual dari promo_data
        }

    }
    else // PROMO SELAIN PRICE LEVEL
    {

        if ($promo) {
            $kode_promo = $promo['kode_promo'];
            $ParameterPromo = getParameterPromoByKodePromo($kode_promo);
            //echo "Promo Kode: " . $kode_promo . "#" . $ParameterPromo . "<br>";

            $hari_ini_index = date('N'); // Mendapatkan index hari (1 = Senin, 7 = Minggu)
            $hari_array = ['SENIN', 'SELASA', 'RABU', 'KAMIS', 'JUMAT', 'SABTU', 'MINGGU'];
            $hari_ini = '%' . $hari_array[$hari_ini_index - 1] . '%'; // Menggunakan wildcard untuk LIKE
            
            // Cek apakah promo masih berlaku
            $sql = "SELECT free_item, value_promo 
                FROM dbo_promo 
                WHERE kode_promo = :kode_promo  and fl_active = 1
                AND CURDATE() BETWEEN promo_start_date AND promo_end_date 
                AND promo_day LIKE :hari_ini";
            $stmt = $db->prepare($sql);
            $stmt->execute([':kode_promo' => $kode_promo, ':hari_ini' => $hari_ini]);
            $promo_elements = $stmt->fetch(PDO::FETCH_ASSOC);

            //var_dump($promo_elements); // Debugging line

            // PROMO MASIH BERLAKU PADA HARI TRANSAKSI
            if ($promo_elements){
                $isPromo = 1;
                $free_item = $promo_elements['free_item'];
                $value_promo = $promo_elements['value_promo'];

                //var_dump($free_item); // Debugging line

                if ($free_item == 0) // promo rupiah & persen
                {
                    if ($value_promo > 100) // promo rupiah
                    {
                        $promo_disc = hitungPersen($value_promo, $harga_jual);
                    }
                    else // promo persen
                    {
                        $promo_disc = $value_promo;
                    }
                }
                else // promo buy 1 get 1
                {
                    if ($free_item == 'FREETHIS')
                    {
                        // tambahkan barang tersebut ke transaksi dengan discount 100%
                        $flag_free_this = true;
                    }
                    else
                    {
                        // tambahkan barang dengan kode_barang $free_item ke transaksi dengan discount 100%
                        $flag_free_item = true;
                    }
                }
            }
        } 
    } // end ada promo (selain price level)

    // menambahan hasil scan / line barang ke transaksi
    try {
        $total_harga = ($harga_jual * $qty) - ($harga_jual * $qty * $promo_disc / 100); // Hitung total harga dengan diskon
        //$total_harga = pembulatanRatusan($total_harga); // Pembulatan ratusan
        //$total_harga = pembulatanHarga($total_harga, 1); // pembulatan satuan

        $sql = "INSERT INTO temp_transaksi 
            (order_no, kode_barang, nama_barang, harga_jual, uom, qty, disc, total_harga, status, promo, remark) 
            VALUES 
            (:order_no, :kode_barang, :nama_barang, :harga_jual, :uom, :qty, :disc, :total_harga, 'CURRENT', :promo, :remark)";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':order_no' => $order_no,
            ':kode_barang' => $kode_barang,
            ':nama_barang' => $nama_barang,
            ':harga_jual' => $harga_jual ?? "99999999",
            ':uom' => $uom,
            ':qty' => $qty,
            ':disc' => $promo_disc,
            ':total_harga' => $total_harga,
            ':promo' => $isPromo,
            ':remark' => $free_item
        ]);

    } catch (PDOException $e) {
        $trxStatus = 'nok';
    }

    $PromoBarang = cekPromoBarang($kode_barang,$uom);
    $PromoParameter = cekParameterPromoBarang($kode_barang,$uom);
    $KriteriaValue = cekKriteriaPromoBarang($kode_barang,$uom);
    $QtyBarangOnTemp = cekQtyTempTransaksi($kode_barang,$uom,$order_no);
    $QtyFreeItem = cekQtyFree($kode_barang,$uom);

    echo "MASUK INSERT PERTAMA X: " . $PromoParameter . " ### <br>";
    switch ($PromoParameter){
        case "A":
        break;
        case "B":
        break;
        case "C":
        case "F":
            if($QtyBarangOnTemp === $KriteriaValue){
                // apabila ada free item FREETHIS, tambahkan line di bawahnya!
                if ($flag_free_this)
                {
                    // tambahkan suffix (P) untuk keperluan update transaksi
                    $kode_barang = $kode_barang . '(P)' . $free_item;
                    //echo "KODE BARANG : " . $kode_barang;
                    // tambahkan line barang free item ke transaksi
                    try {
                        $total_harga = 0;
                
                        $sql = "INSERT INTO temp_transaksi 
                            (order_no, kode_barang, nama_barang, harga_jual, uom, qty, disc, total_harga, status, promo) 
                            VALUES 
                            (:order_no, :kode_barang, :nama_barang, :harga_jual, :uom, :qty, :disc, :total_harga, 'CURRENT', :promo)";
                        
                        $stmt = $db->prepare($sql);
                        $stmt->execute([
                            ':order_no' => $order_no,
                            ':kode_barang' => $kode_barang,
                            ':nama_barang' => $nama_barang,
                            ':harga_jual' => $harga_jual ?? "99999999",
                            ':uom' => $uom,
                            ':qty'=> $QtyFreeItem,//':qty' => 1,
                            ':disc' => 100,
                            ':total_harga' => $total_harga,
                            ':promo' => $isPromo
                        ]);
                
                    } catch (PDOException $e) {
                        $trxStatus = 'nok';
                    }
                } // FREETHIS
            }            
        break;
        case "D":
            //echo "MASUK SINI KE C";
            $UomFreeItem = cekUomBarangFreeItem($free_item);    
            echo $UomFreeItem . "<br>";    
            if ($flag_free_item)
            {
                
                //echo "Kode Barang Free Item: " . $flag_free_item . "<br>";
                // Tanggal hari ini
                $tanggal_hari_ini = date('Y-m-d');

                //echo $tanggal_hari_ini . "<br>";
                // Query hanya berdasarkan barcode pada dbo_price, join ke dbo_barang
                $sql = "
                    SELECT 
                        b.sku_barang,
                        b.kode_barang,
                        b.nama_barang,
                        p.uom,
                        p.harga_jual
                    FROM 
                        dbo_price p
                    LEFT JOIN 
                        dbo_barang b ON p.sku_barang = b.sku_barang
                    WHERE 
                        p.fl_active = 1
                        AND p.start_date <= :tanggal_hari_ini
                        AND p.end_date >= :tanggal_hari_ini
                        AND p.kode_barang = :keyword";

                //echo "Kode Barang Free Item: " . $sql . "<br>";
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':keyword', $free_item, PDO::PARAM_STR);
                $stmt->bindParam(':tanggal_hari_ini', $tanggal_hari_ini, PDO::PARAM_STR);
                $stmt->execute();
                $line_free_item = $stmt->fetchAll(PDO::FETCH_ASSOC);

                //var_dump($line_free_item); // Debugging line

                if ($line_free_item) {
                    // tambahkan suffix (P) untuk keperluan update transaksi
                    $kode_barangx = $kode_barang . '(P)' . $free_item; // tambahkan kode barang free item
                    echo $kode_barangx . "<br>";    
                    try {
                        $total_harga = 0;
                
                        $sql = "INSERT INTO temp_transaksi 
                            (order_no, kode_barang, nama_barang, harga_jual, uom, qty, disc, total_harga, status, promo, remark) 
                            VALUES 
                            (:order_no, :kode_barang, :nama_barang, :harga_jual, :uom, :qty, :disc, :total_harga, 'CURRENT', :promo, :remark)";
                        
                        $stmt = $db->prepare($sql);
                        $stmt->execute([
                            ':order_no' => $order_no,
                            ':kode_barang' => $kode_barangx,
                            ':nama_barang' => $line_free_item[0]['nama_barang'],
                            ':harga_jual' =>  $line_free_item[0]['harga_jual'],
                            ':uom' => $UomFreeItem,//':uom' => $line_free_item[0]['uom'],
                            ':qty' => 1,
                            ':disc' => 100,
                            ':total_harga' => $total_harga,
                            ':promo' => $isPromo,
                            ':remark' => $free_item
                        ]);
                
                    } catch (PDOException $e) {
                        $trxStatus = 'nok';
                    }
                }
            }             
        break;
    }
      
} // barang belum ada di transaksi


// cek apakah barang sudah ada di transaksi, Barang timbangan (uom <> KG)
$sql = "SELECT * FROM temp_transaksi WHERE order_no = :order_no AND kode_barang = :kode_barang AND uom = :uom AND uom <> 'KG' AND status = 'CURRENT'";
$stmt = $db->prepare($sql);
$stmt->execute([':order_no' => $order_no, ':kode_barang' => $kode_barang, 'uom' => $uom]);
$barang = $stmt->fetch(PDO::FETCH_ASSOC);
if ($barang) { // sudah ada di temp trx
    $new_qty = $barang['qty'] + $qty;

}





function hitungPersen($nilai, $harga_barang) {
    if ($harga_barang == 0) {
        return 0; // Hindari pembagian dengan nol
    }

    $persen = ($nilai / $harga_barang) * 100;
    return round($persen, 4); // Dibulatkan ke 2 desimal
}

function pembulatanRatusan($harga) {
    return round($harga / 100) * 100;
}

function pembulatanHarga($harga, $digit) {
    return round($harga / $digit) * $digit;
}

// promo price level
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
/*
$response = [
    'status' => $trxStatus,
    'promo_price_level' => $promo_data
];
header('Content-Type: application/json');
echo json_encode($response);
*/
?>
