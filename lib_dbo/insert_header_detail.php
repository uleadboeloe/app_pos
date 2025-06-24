<?php
    include("../lib/mysql_pdo.php");
    include("../lib/mysql_connect.php");
    include("../lib/general_lib.php");
    include("../lib_dbo/user_functions.php");
    include("../admin/library/fungsi.php");

    session_start();
    $kode_register = $_SESSION['SESS_kode_register'];

    $order_no = $_GET['order_no'];
    $total_bayar = $_GET['tendered'];
    $user_id = $_GET['user_id'];
    $user_name = $_GET['user_name'];
    $kode_store = $_GET['kode_store'];
    $payment_type = $_GET['payment_type'];
    $cust_code = $_GET['cust_code'];
    $point_member = isset($_GET['point_member']) ? $_GET['point_member'] : 0;
    $voucher = isset($_GET['nilai_voucher']) ? $_GET['nilai_voucher'] : 0;
    $kode_voucher = $_GET['kode_voucher'];
    $nama_kartu = $_GET['nama_kartu'];
    $mesin_edc = $_GET['mesin_edc'] ? $_GET['mesin_edc'] : 0;

    if($mesin_edc > 0){
        $nama_mesin = getNamaMesinNoid($mesin_edc);
        $bank_penerbit = getBankPenerbitNoid($mesin_edc);
    }else{
        $nama_mesin = "";
        $bank_penerbit = "";
    }

    /*
    switch ($payment_type) {
        case "CASH":
            $TotalCash = $total_bayar;
            $TotalNonCash = 0;
            break;
        case "DEBIT":
        case "CREDIT":
        case "EWALET":
            $TotalCash = 0;
            $TotalNonCash = $total_bayar;
            break;
        default:
            $TotalCash = 0;
            $TotalNonCash = 0;
            break;
    }*/
    
    if($cust_code != ""){
        $CheckMemberHNI = CheckMemberByPhone($cust_code);
        $dataMemberid = json_decode($CheckMemberHNI, true);

        $codex = $dataMemberid['code'];
        $msgx = $dataMemberid['msg'] ?? "";
        $datax = $dataMemberid['data'];
        if($codex == "000"){
            $MemberID = $datax['customer_id'];
            $MemberName = $datax['customer_name'];
            $MemberName = str_replace("'","`",$MemberName);
            $PhoneByMemberid = $datax['customer_hp'];
            $varStatusMember = "MEMBER_HNI";
        }else{
            // Query untuk menjumlahkan nilai voucher berdasarkan kode_voucher
            $stmt = $db->prepare("SELECT nama_member,nomor_kontak FROM dbo_member WHERE kode_member = :kode_member");
            //SELECT nominal FROM dbo_voucher WHERE kode_voucher = '170809469642a16e-0003'
            $stmt->bindParam(':kode_member', $cust_code);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            $MemberID = $cust_code;
            $MemberName = $result['nama_member'] ?? '';
            $PhoneByMemberid = $result['nomor_kontak'] ?? '';
            $varStatusMember = "MEMBER TOKO";
        }
    }else{
        $PhoneByMemberid = "";
        $varStatusMember = "";
        $MemberName = "";
    }


    // Credit / Debit Card
    if(ISSET($_GET['card_number']) && ISSET($_GET['reff_code']) && ISSET($_GET['approval_code'])) {
        $card_number = $_GET['card_number'];
        $reff_code = $_GET['reff_code'];
        $approval_code = $_GET['approval_code'];
    } else
        $card_number = $reff_code = $approval_code = "";

    $query_temp = "SELECT * FROM temp_transaksi WHERE order_no = :order_no AND status = 'CURRENT'";
    $stmt_temp = $db->prepare($query_temp);
    $stmt_temp->execute(['order_no' => $order_no]);
    $result_temp = $stmt_temp->fetchAll(PDO::FETCH_ASSOC);

    $query_temp = "SELECT SUM(total_harga) as total FROM temp_transaksi WHERE order_no = :order_no AND status = 'CURRENT'";
    $stmt_temp = $db->prepare($query_temp);
    $stmt_temp->execute(['order_no' => $order_no]);
    $sum_temp = $stmt_temp->fetchAll(PDO::FETCH_ASSOC);

    if (count($result_temp) > 0) {
        // Generate random_code
        $random_code = CreateUniqueHash16();
        $total_struk = $sum_temp[0]['total']  + ($sum_temp[0]['total'] * $ppn);
        //total struk sesuai dengan harga total dari barang yang di beli
        $total_struk_before_round = $total_struk;
        $total_struk = roundCustom($total_struk);
        if($voucher > $total_struk_before_round){
            $nilai_voucher = $total_struk_before_round;
        }else{
            $nilai_voucher = $voucher;
        }

        if($point_member > $total_struk_before_round){
            $point_member = $total_struk_before_round;
        }else{
            $point_member = $point_member;
        }

        // hitung kembalian di sini, setelah voucher & point angkanya di sesuaikan!
        $kembalian = ($total_bayar + $nilai_voucher + $point_member) - $total_struk;
        // ini masih error broowh ! sudah di fixed
        //$pembulatan = ($total_bayar + $nilai_voucher + $point_member + $kembalian) - $total_struk_before_round;
        $pembulatan = $total_struk - $total_struk_before_round;
        
        $TotalBayarCustomer = $total_struk_before_round-$pembulatan-$nilai_voucher-$nilaipoin;
        if($payment_type == "CASH"){
            $TotalCash = $total_bayar-$kembalian;
            $TotalNonCash = 0;
        }else{
            $TotalCash = 0;
            $TotalNonCash = $total_bayar-$kembalian;
        }
        // Insert ke tabel dbo_header
        $query_header = <<<SQL
            INSERT INTO dbo_header (random_code, kode_store, no_struk, tanggal, jam, kode_kasir, nama_kasir, total_bayar, total_struk, kembalian, jenis_bayar, nama_kartu, kode_customer,status_customer,nama_customer,telp_customer, var_poin, var_pembulatan, var_voucher, var_cash, var_noncash, posting_date, posting_user, kode_register) 
            VALUES (:random_code, :kode_store, :no_struk, CURDATE(), CURTIME(), :kode_kasir, :nama_kasir, :total_bayar, :total_struk, :kembalian, :jenis_bayar, :nama_kartu, :kode_customer, :status_customer, :nama_customer, :telp_customer, :var_poin, :var_pembulatan, :var_voucher, :var_cash, :var_noncash, NOW(), :posting_user, :kode_register)
        SQL;

        $stmt_header = $db->prepare($query_header);
        $stmt_header->execute([
            'random_code' => $random_code,
            'kode_store' => $kode_store,
            'no_struk' => $order_no,
            'kode_kasir' => $user_id,
            'nama_kasir' => $user_name,
            'total_bayar' => $total_bayar,
            //'total_struk' => $total_struk,
            'total_struk' => $total_struk_before_round,
            'kembalian' => $kembalian,
            'jenis_bayar' => $payment_type, 
            'kode_customer' => $cust_code, 
            'status_customer' => $varStatusMember,
            'nama_customer' => $MemberName,
            'telp_customer' => $PhoneByMemberid,
            'var_poin' => $point_member, 
            'var_pembulatan' => $pembulatan, 
            'var_voucher' => $nilai_voucher, 
            'var_cash' => $TotalCash,
            'var_noncash' => $TotalNonCash,
            'nama_kartu' => $nama_kartu,
            'posting_user' => $user_id,
            'kode_register' => $kode_register
        ]);

        // Insert ke tabel dbo_payment
        $query_payment = <<<SQL
            INSERT INTO dbo_payment (random_code, kode_store, no_struk, tanggal, jenis_bayar, total_bayar, kode_edc, nama_edc, nama_bank, reff_code, approval_code, card_number, posting_date, posting_user) 
            VALUES (:random_code, :kode_store, :no_struk, NOW(), :jenis_bayar, :total_bayar, :kode_edc, :nama_edc, :nama_bank, :reff_code, :approval_code, :card_number, NOW(), :posting_user)
        SQL;

        $stmt_payment = $db->prepare($query_payment);
        $stmt_payment->execute([
            'random_code' => $random_code,
            'kode_store' => $kode_store,
            'no_struk' => $order_no,
            'jenis_bayar' => $payment_type, 
            'total_bayar' => $total_bayar,
            'kode_edc' => $mesin_edc,
            'nama_edc' => $nama_mesin,
            'nama_bank' => $bank_penerbit,
            'reff_code' => $reff_code,
            'approval_code' => $approval_code,  
            'card_number' => $card_number,
            'posting_user' => $user_id
        ]);

        // Insert ke tabel dbo_detail
        $query_detail = <<<SQL
            INSERT INTO dbo_detail (random_code, no_struk, kode_barang, qty_sales, harga, satuan, total_sales, kode_store, gross_sales, netto_sales, var_diskon, posting_date, posting_user) 
            VALUES (:random_code, :no_struk, :kode_barang, :qty_sales, :harga, :satuan, :total_sales, :kode_store, :gross_sales, :netto_sales, :var_diskon, NOW(), :posting_user)
        SQL;

        $stmt_detail = $db->prepare($query_detail);

        foreach ($result_temp as $row) {
            $stmt_detail->execute([
                'random_code' => $random_code,
                'no_struk' => $order_no,
                'kode_barang' => $row['kode_barang'],
                'qty_sales' => $row['qty'],
                'harga' => $row['harga_jual'],
                'satuan' => $row['uom'],
                'total_sales' => $row['total_harga'],
                'kode_store' => $kode_store,
                'gross_sales' =>($row['qty'] * $row['harga_jual']),
                'netto_sales' =>$row['total_harga'],
                'var_diskon' => ($row['qty'] * $row['harga_jual']) - $row['total_harga'],
                'posting_user' => $user_id
            ]);
        }
        // Insert ke table dbo_poin kalau kode_customer ada
        if($cust_code != ""){
            $TipePoin = "1";
            if($total_bayar > 0){
                $TotalPoinMember = $total_bayar / $nilaipoin;
            }else{
                $TotalPoinMember = 0;
            }

            $query_poin = <<<SQL
                INSERT INTO dbo_poin_member (tipe_poin, member_id, nilai_poin, refer_id, posting_date, posting_user) 
                VALUES (:tipe_poin, :member_id, :nilai_poin, :refer_id , NOW(), :posting_user)
            SQL;

            $stmt_point = $db->prepare($query_poin);
            $stmt_point->execute([
                'tipe_poin' => $TipePoin,
                'member_id' => $cust_code,
                'nilai_poin' => $TotalPoinMember,
                'refer_id' => $order_no, 
                'posting_user' => $user_id
            ]);
        }

        if($point_member > 0){
            $TipePoin = "2";
            $TotalPoinMember = $point_member*-1;

            $query_poin = <<<SQL
                INSERT INTO dbo_poin_member (tipe_poin, member_id, nilai_poin, refer_id, posting_date, posting_user) 
                VALUES (:tipe_poin, :member_id, :nilai_poin, :refer_id , NOW(), :posting_user)
            SQL;

            $stmt_point = $db->prepare($query_poin);
            $stmt_point->execute([
                'tipe_poin' => $TipePoin,
                'member_id' => $cust_code,
                'nilai_poin' => $TotalPoinMember,
                'refer_id' => $order_no, 
                'posting_user' => $user_id
            ]);
        }

        if($kode_voucher !=""){
            try {
                $sql = "UPDATE dbo_voucher SET tanggal_pakai = NOW(),no_struk = :no_struk,user_pakai = :user_pakai,nominal_voucher = :nominal_voucher,nominal_pakai = :nominal_pakai,nominal_real_voucher = :nominal_real_voucher,fl_active = :fl_active WHERE kode_voucher = :kode_voucher";
                $stmt = $db->prepare($sql);
                $stmt->execute([
                    ':no_struk' => $order_no,
                    ':user_pakai' => $user_id,
                    ':fl_active' => '0',
                    ':nominal_voucher' => '0',
                    ':nominal_pakai' => $nilai_voucher,
                    ':nominal_real_voucher' => $voucher,
                    ':kode_voucher' => $kode_voucher
                ]);
            
                //echo "Transaksi berhasil diupdate menjadi $status.";
            } catch (PDOException $e) {
                //echo "Gagal update status: " . $e->getMessage();
            }
        }
        echo "success";
    } else {
        echo "Tidak ada data transaksi untuk order_no: $order_no.";
    }

    // Tutup koneksi
    $db = null;
?>
