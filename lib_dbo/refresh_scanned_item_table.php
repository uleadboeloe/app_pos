<?php
    include("../lib/mysql_pdo.php");
    include("../lib/mysql_connect.php");
    include("../lib/general_lib.php");

    // hardcode
	$current_order_no = $_GET['orderno'];
    $point_member = isset($_GET['pointmember']) ? $_GET['pointmember'] : 0;
    $nilai_voucher = isset($_GET['nilaivoucher']) ? $_GET['nilaivoucher'] : 0;

	$stmt = $db->prepare("SELECT * FROM temp_transaksi WHERE order_no = :orderno AND status = 'CURRENT'");

	$stmt->execute(array(':orderno' => $current_order_no));
	$list_scanned_item = $stmt->fetchAll();

    $i = 1;
	$qty = 0;
    $subtot = 0;
	$ppn_amt = 0;
	$grandtot = 0;

    foreach ($list_scanned_item as $item) {
        $isPromo = $item['promo'];
        echo '<tr>';
        echo '<td class="center">' . $i . '</td>';
        echo '<td>' . $item['kode_barang'] . '</td>';
        switch ($isPromo) {
            case '0':
                echo '<td>' . limitOnly45Chars($item['nama_barang']) . '</td>';
                break;
            case '1':
                echo '<td style="color:#C00000">' . limitOnly45Chars($item['nama_barang']) . '</td>';
                break;
            case '2':
                echo '<td style="color:#FF9900">' . limitOnly45Chars($item['nama_barang']) . '</td>';
                break;
            default:
                echo '<td>' . limitOnly45Chars($item['nama_barang']) . '</td>';
        }

        if ($item['disc'] == 100) //aslinya $isPromo
            echo '<td style="text-align: right;">&nbsp;' . formatRupiah($item['harga_jual']) . '</td>';
        else
            echo '<td class="harga-cell" style="text-align: right;">✏️&nbsp;' . formatRupiah($item['harga_jual']) . '</td>';
            echo '<td style="text-align: center;">' . $item['uom'] . '</td>';
        // VERSI INI TIDAK MENGGUNAKAN TOMBOL PLUS MINUS
        // echo '<td style="text-align: center;"><span class="qtybtn qtybtnmin btn-minus">&minus;</span>&nbsp;&nbsp;<span class="quantity">' . $item['qty'] . '</span>&nbsp;&nbsp;<span class="qtybtn qtybtnplus btn-plus">&plus;</span></td>';
        // ---------------------------------------------
        // VERSI INI MENGGUNAKAN TOMBOL MINUS & CROSS (DELETE)
        if ($item['uom'] == 'KG' || $item['qty'] < 2){
            echo '<td style="text-align: right;">';
            echo '<span class="quantity" style="width:50px;">' . $item['qty'] . '</span>';

            if ($item['disc'] == 100) {
                echo '&nbsp;&nbsp;';
            } else {
                // Untuk barang non-timbangan, tampilkan tombol plus
                echo '&nbsp;&nbsp;<span class="qtybtn qtybtndel btn-del">&times;</span>';
            }            
            echo '</td>';
        }else{
            echo '<td style="text-align: right;">';
            if ($item['disc'] == 100) {
                echo '';
            } else {
                // Untuk barang non-timbangan, tampilkan tombol plus
                echo '<span class="qtybtn qtybtnmin btn-minus">&minus;</span>&nbsp;&nbsp;';
            }

            echo '<span class="quantity" style="width:50px;">' . $item['qty'] . '</span>';

            if ($item['disc'] == 100) {
                echo '&nbsp;&nbsp;';
            } else {
                // Untuk barang non-timbangan, tampilkan tombol plus
                echo '&nbsp;&nbsp;<span class="qtybtn qtybtndel btn-del">&times;</span>';
            }
            
            echo '</td>';            
        }


        // Tampilkan tanpa desimal jika disc bilangan bulat, jika tidak tampilkan 2 desimal
        if (fmod($item['disc'], 1) == 0) {
            echo '<td style="text-align: right;">' . number_format($item['disc'], 0) . '</td>';
        } else {
            echo '<td style="text-align: right;">' . number_format($item['disc'], 2) . '</td>';
        }
        echo '<td style="text-align: right;">' . formatRupiah($item['total_harga']) . '</td>';
        echo '</tr>';
        $i++;
        if ($item['uom'] == 'KG') {
            $qty++; // untuk barang timbangan, qty tambah 1
        } else {
            $qty += $item['qty'];
        }
        
        $subtot += $item['total_harga'];
    }

    $ppn_amt = $subtot * $ppn;
    $grandtot = $subtot + $ppn_amt - $point_member - $nilai_voucher;
    // jika nominal voucher lebih besar dari total belanja
    if($grandtot < 0) 
    {
        // sesuaikan nilai_voucher, nanti buat simpan ke var_voucher
        $nilai_voucher += $grandtot;
        // grandtot gak boleh minus
        $grandtot = 0;
    }

    // Tambahan baris kosong jika kurang dari 12
    if ($i < 12) {
        for ($j = 0; $j < 12 - $i; $j++) {
            echo '<tr>';
            echo '<td>&nbsp;</td>';
            echo '<td>&nbsp;</td>';
            echo '<td>&nbsp;</td>';
            echo '<td>&nbsp;</td>';
            echo '<td>&nbsp;</td>';
            echo '<td>&nbsp;</td>';
            echo '<td>&nbsp;</td>';
            echo '<td>&nbsp;</td>';
            echo '</tr>';
        }
    }

    // Opsi pembayaran pecahan
    function OpsiPembayaranPecahan($totalBelanja): array {
        
        $pecahan = [];
        for ($i = 10000; $i <= 90000000; $i += 10000) 
        {
            $pecahan[] = $i;
        }

        $possiblePayments = [];
        
        foreach ($pecahan as $uang) 
        {
            if ($uang > $totalBelanja) 
            {
                $possiblePayments[] = $uang;
            }
        }
        return $possiblePayments;
    }

    $possiblePayments = OpsiPembayaranPecahan($grandtot);

    if (count($possiblePayments) == 1)
    {
        echo "<input type='hidden' id='hiddenPecahan1' value='$possiblePayments[0]'>";
        echo "<input type='hidden' id='hiddenPecahan2' value='$possiblePayments[0]'>";
        echo "<input type='hidden' id='hiddenPecahan3' value='$possiblePayments[0]'>";
    }
    else
    {
        $limapuluhRibuTerdekat = roundUpToHundredThousand(2 * $possiblePayments[1]) / 2;
        $ratusanRibuTerdekat = roundUpToHundredThousand($possiblePayments[2]);
        echo "<input type='hidden' id='hiddenPecahan1' value='$possiblePayments[0]'>";
        echo "<input type='hidden' id='hiddenPecahan2' value='$limapuluhRibuTerdekat'>";
        echo "<input type='hidden' id='hiddenPecahan3' value='$ratusanRibuTerdekat'>";
    }

    $subtot = formatRupiah($subtot);
    $ppn_amt = formatRupiah($ppn_amt);
    $grandtot = formatRupiah(roundCustom($grandtot));

    // Cetak hidden input untuk passing subtotal, ppnamt & total
    echo "<input type='hidden' id='hiddenQty' value='$qty'>";
    echo "<input type='hidden' id='hiddenSubtotal' value='$subtot'>";
    echo "<input type='hidden' id='hiddenPpnAmt' value='$ppn_amt'>";
    echo "<input type='hidden' id='hiddenTotal' value='$grandtot'>";

    function roundUpToHundredThousand($amount) {
        return ceil($amount / 100000) * 100000;
    }
?>
