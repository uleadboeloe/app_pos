<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include_once "library/connection.php";
include_once "library/parameter.php";
include_once "library/fungsi.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<!-- Meta tags  -->
<meta charset="UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"/>

<title><?php    echo $TitleApps;    ?></title>
<link rel="shortcut icon" type="image/png" href="../pos.png">
<!-- CSS Assets -->
<link rel="stylesheet" href="assets/lineone/css/app.css" />
<link rel="stylesheet" href="assets/css/custom.css" />
<!-- Javascript Assets -->
<script src="assets/lineone/js/app.js" defer></script>
<script src="https://cdn.tailwindcss.com"></script>
<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"rel="stylesheet"/>

</head>

<body x-data class="is-header-blur" x-bind="$store.global.documentBody">
<!-- App preloader-->
<div class="app-preloader fixed z-50 grid h-full w-full place-content-center bg-orange-50 dark:bg-navy-900 bg-[url(assets/images/please-wait.avif)] bg-no-repeat bg-center">
    <div class="app-preloader-inner relative inline-block h-48 w-48"></div>
</div>

<!-- Page Wrapper -->
<div id="root" class="min-h-100vh flex grow bg-slate-50 dark:bg-navy-900" x-cloak>
    <?php   include "sidebar.php";  ?>
    <!-- Main Content Wrapper -->
    <main class="main-content w-full px-[var(--margin-x)] pb-8 bg-green-100">
        <?php
        if(isset($_GET['rcode'])){
            $NoRegister = $_GET['rcode'];
            $StrViewQuery="SELECT * from dbo_register where kode_register = '" . $_GET['rcode'] . "'";
            //echo $StrViewQuery . "<hr>";     
            $callStrViewQuery=mysqli_query($koneksidb, $StrViewQuery);
            while($recView=mysqli_fetch_array($callStrViewQuery))
            {
                $varNoid = $recView['noid'];
                $Tanggal = $recView['tanggal'];
                $Jam = $recView['jam'];
                $DisplayDate = date("d-m-Y", strtotime($Tanggal)) . " " . $Jam;
                $TanggalClose = $recView['closing_date'] ? $recView['closing_date'] : '2024-01-01';
                $JamClose = $recView['closing_time'];
                if($TanggalClose == '2024-01-01') {
                    $DisplayDateClose = "BELUM CLOSE REGISTER";
                    $AddStyle = "quadratzz";
                    $StatusClose = 0;
                }else{
                    $StatusClose = 1;
                    $AddStyle = "";
                    $DisplayDateClose = date("d-m-Y", strtotime($TanggalClose)) . " " . $JamClose;
                }
                $TotalStruk = $recView['jumlah_struk_cash'] + $recView['jumlah_struk_kredit'] + $recView['jumlah_struk_debit'] + $recView['jumlah_struk_qris'];
                $TotalPembayaran = $recView['total_pembayaran_cash'] + $recView['total_pembayaran_kredit'] + $recView['total_pembayaran_debit'] + $recView['total_pembayaran_qris'];
                if ($TotalPembayaran === "" || $TotalPembayaran === null) {
                    $TotalPembayaran = 0;
                }

                    
                $JumlahStrukCash = $recView['jumlah_struk_cash'] ? $recView['jumlah_struk_cash'] : 0;
                $JumlahStrukKredit = $recView['jumlah_struk_kredit'] ? $recView['jumlah_struk_kredit'] : 0;
                $JumlahStrukDebit = $recView['jumlah_struk_debit'] ? $recView['jumlah_struk_debit'] : 0;
                $JumlahStrukQris = $recView['jumlah_struk_qris'] ? $recView['jumlah_struk_qris'] : 0;
                $TotalPembayaranCash = $recView['total_pembayaran_cash'] ?? 0;
                $TotalPembayaranKredit = $recView['total_pembayaran_kredit'] ? $recView['total_pembayaran_kredit'] : 0;
                $TotalPembayaranDebit = $recView['total_pembayaran_debit']? $recView['total_pembayaran_debit'] : 0;
                $TotalPembayaranQris = $recView['total_pembayaran_qris']? $recView['total_pembayaran_qris'] : 0;
                $TotalVoucher = $recView['total_voucher'];
                $TotalPoin = $recView['total_poin'];
                $ModalAwal = $recView['modal_awal'];
                $TotalPembayaran = $TotalPembayaranCash + $TotalPembayaranKredit + $TotalPembayaranDebit + $TotalPembayaranQris + $TotalVoucher + $TotalPoin;
                $SetoranAkhir = $recView['setoran_akhir'];
                
                $PK100000= $recView['c100000']*100000;
                $PK50000= $recView['c50000']*50000;
                $PK20000= $recView['c20000']*20000;
                $PK10000= $recView['c10000']*10000;
                $PK5000= $recView['c5000']*5000;
                $PK2000= $recView['c2000']*2000;
                $PK1000= $recView['c1000']*1000;
                $TotalPecahanKertas = $PK100000+$PK50000+$PK20000+$PK10000+$PK5000+$PK2000+$PK1000;
                $PC1000= $recView['c1000k']*1000;
                $PC500= $recView['c500k']*500;
                $PC200= $recView['c200k']*200;
                $TotalPecahanLogam = $PC1000+$PC500+$PC200;
                $TotalPecahanKasir = $TotalPecahanKertas+$TotalPecahanLogam;
                $TotalSetoranKasir = $TotalPecahanKertas+$TotalPecahanLogam+$ModalAwal;
                $TotalSetoran = $ModalAwal+$SetoranAkhir;
                $SelisihSetoran = $TotalPecahanKasir-$TotalSetoran;        
                if($SelisihSetoran < 0){
                    $StatusSelisih = "Setoran Kurang";
                }else{
                    $StatusSelisih = "Setoran Lebih";
                }
                
                $StatusRespon = $recView['status_respon'];        
                ?>
                <div class="col-span-12 p-2 lg:col-span-12">
                    <div class="flex items-center justify-between py-2 px-4">
                        <h2 class="font-bold text-xl uppercase tracking-wide text-slate-700 dark:text-navy-100">Detail Register</h2>
                        <div class="flex">
                        <a href="register" class="btn space-x-2 mr-1 bg-warning font-medium text-white hover:bg-warning-focus focus:bg-warning-focus active:bg-warning-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">Kembali ke daftar</a>
                        <button class="btn space-x-2 mr-1 bg-success font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90" 
                        onclick="PrintDoc()">Print Detail</button>                                                
                        </div>
                    </div>

                    <div class="rounded-lg bg-white py-5 sm:py-6">
                        <div class="flex p-4">
                            <div class="w-2/3 p-4"> 
                                <div class="">
                                    <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                                        <div class="bg-gradient-to-r from-orange-400 to-yellow-200 p-4">
                                            <h3 class="text-white text-lg font-bold">Detail Register</h3>
                                        </div>
                                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                                            <div class="w-full p-6">
                                                <div class="mb-4">
                                                    <span class="block text-gray-500 text-sm">Total Struk Cash:</span>
                                                    <span class="text-xl font-semibold text-gray-800"><?php echo $JumlahStrukCash; ?></span>
                                                    <span class="text-xl font-semibold text-gray-800">(Rp <?php echo number_format($TotalPembayaranCash,2,',','.'); ?>)</span>
                                                </div>
                                                <div class="mb-4">
                                                    <span class="block text-gray-500 text-sm">Total Struk Kredit:</span>
                                                    <span class="text-xl font-semibold text-gray-800"><?php echo $JumlahStrukKredit; ?></span>
                                                    <span class="text-xl font-semibold text-gray-800">(Rp <?php echo number_format($TotalPembayaranKredit,2,',','.'); ?>)</span>
                                                </div>
                                                <div class="mb-4">
                                                    <span class="block text-gray-500 text-sm">Total Struk Debit:</span>
                                                    <span class="text-xl font-semibold text-gray-800"><?php echo $JumlahStrukDebit; ?></span>
                                                    <span class="text-xl font-semibold text-gray-800">(Rp <?php echo number_format($TotalPembayaranDebit,2,',','.'); ?>)</span>
                                                </div>
                                                <div class="mb-4">
                                                    <span class="block text-gray-500 text-sm">Total Struk Ewallet:</span>
                                                    <span class="text-xl font-semibold text-gray-800"><?php echo $JumlahStrukQris; ?></span>
                                                    <span class="text-xl font-semibold text-gray-800">(Rp <?php echo number_format($TotalPembayaranQris,2,',','.'); ?>)</span>
                                                </div>     
                                                <div class="mb-4">
                                                    <span class="block text-gray-500 text-sm">Total Struk Penjualan:</span>
                                                    <span class="text-xl font-semibold text-gray-800"><?php echo $TotalStruk; ?></span>
                                                    <span class="text-xl font-semibold text-gray-800">(Rp <?php echo number_format($TotalPembayaran,2,',','.'); ?>)</span>
                                                </div>  
                                                <div class="mb-4">
                                                    <span class="block text-gray-500 text-sm">Total Poin Digunakan:</span>
                                                    <span class="text-xl font-semibold text-gray-800"><?php echo $recView['total_poin']; ?></span>
                                                    <span class="text-xl font-semibold text-gray-800">(Rp <?php echo number_format($recView['total_poin'],2,',','.'); ?>)</span>
                                                </div>
                                                <div class="mb-4">
                                                    <span class="block text-gray-500 text-sm">Total Voucher Digunakan:</span>
                                                    <span class="text-xl font-semibold text-gray-800"><?php echo $recView['total_voucher']; ?></span>
                                                    <span class="text-xl font-semibold text-gray-800">(Rp <?php echo number_format($recView['total_voucher'],2,',','.'); ?>)</span>
                                                </div>                                                                                                                                
                                            </div>
                                            <div class="w-full p-6">
                                                <h3 class="block text-gray-500 text-sm">Pecahan Kertas</h3>
                                                <span class="text-xl font-semibold text-gray-800">Rp 100.000 x <?php echo $recView['c100000']; ?></span>
                                                <span class="text-xl font-semibold text-blue-800">Rp <?php echo number_format($PK100000,2,',','.'); ?></span><br>
                                                <span class="text-xl font-semibold text-gray-800">Rp 50.000 x <?php echo $recView['c50000']; ?></span>
                                                <span class="text-xl font-semibold text-blue-800">Rp <?php echo number_format($PK50000,2,',','.'); ?></span><br>
                                                <span class="text-xl font-semibold text-gray-800">Rp 20.000 x <?php echo $recView['c20000']; ?></span>
                                                <span class="text-xl font-semibold text-blue-800">Rp <?php echo number_format($PK20000,2,',','.'); ?></span><br>
                                                <span class="text-xl font-semibold text-gray-800">Rp 10.000 x <?php echo $recView['c10000']; ?></span>
                                                <span class="text-xl font-semibold text-blue-800">Rp <?php echo number_format($PK10000,2,',','.'); ?></span><br>
                                                <span class="text-xl font-semibold text-gray-800">Rp 5.000 x <?php echo $recView['c5000']; ?></span>
                                                <span class="text-xl font-semibold text-blue-800">Rp <?php echo number_format($PK5000,2,',','.'); ?></span><br>
                                                <span class="text-xl font-semibold text-gray-800">Rp 2.000 x <?php echo $recView['c2000']; ?></span>
                                                <span class="text-xl font-semibold text-blue-800">Rp <?php echo number_format($PK2000,2,',','.'); ?></span><br>
                                                <span class="text-xl font-semibold text-gray-800">Rp 1.000 x <?php echo $recView['c1000']; ?></span>
                                                <span class="text-xl font-semibold text-blue-800">Rp <?php echo number_format($PK1000,2,',','.'); ?></span><br>
                                                <span class="block text-gray-500 text-sm">Total Pecahan Kertas</span>
                                                <span class="text-xl font-semibold text-gray-800">Rp <?php echo number_format($TotalPecahanKertas,2,',','.'); ?></span><br><br>
                                                <h3 class="block text-gray-500 text-sm">Pecahan Koin</h3>
                                                <span class="text-xl font-semibold text-gray-800">Rp 1000 x <?php echo $recView['c1000k']; ?></span>
                                                <span class="text-xl font-semibold text-blue-800">Rp <?php echo number_format($PC1000,2,',','.'); ?></span><br>
                                                <span class="text-xl font-semibold text-gray-800">Rp 500 x <?php echo $recView['c500k']; ?></span>
                                                <span class="text-xl font-semibold text-blue-800">Rp <?php echo number_format($PC500,2,',','.'); ?></span><br>
                                                <span class="text-xl font-semibold text-gray-800">Rp 200 x <?php echo $recView['c200k']; ?></span>
                                                <span class="text-xl font-semibold text-blue-800">Rp <?php echo number_format($PC200,2,',','.'); ?></span><br>
                                                <span class="block text-gray-500 text-sm">Total Pecahan Koin</span>
                                                <span class="text-xl font-semibold text-gray-800">Rp <?php echo number_format($TotalPecahanLogam,2,',','.'); ?></span>   
                                                                                                                             
                                            </div>
                                            <div class="w-full p-6">
                                                <h3 class="block text-gray-500 text-sm">Total Setoran Kasir</h3>
                                                <span class="text-xl font-semibold text-gray-800 quadratzz">Rp <?php echo number_format($TotalPecahanKasir,2,',','.'); ?></span><br>
                                                <h3 class="block text-gray-500 text-sm">Selisih Setoran Kasir</h3>
                                                <span class="text-xl font-semibold text-gray-800">Rp <?php echo number_format($SelisihSetoran,2,',','.'); ?>(<?php echo $StatusSelisih; ?>)</span>                                                                                            
                                            </div>                                                                                 
                                        </div>
                                    </div>
                                    <div class="bg-white mt-2 shadow-lg rounded-xl overflow-hidden border border-gray-200">
                                        <div class="bg-gradient-to-r from-purple-400 to-blue-200 p-4">
                                            <h3 class="text-white text-lg font-bold">Detail Sales</h3>
                                        </div>
                                        <div class="flex">
                                            <div class="w-1/3 p-6">
                                                <?php
                                                $Pembulatan = getTotalPembulatanHarianRegister($Tanggal,$NoRegister);
                                                if($Pembulatan < 0){
                                                    $Pembulatan = $Pembulatan*-1;
                                                    $StatusPembulatan = "Pembulatan -";
                                                    $StatusPembulatans = "-";
                                                }else{
                                                    $StatusPembulatan = "Pembulatan +";
                                                    $StatusPembulatans = "+";
                                                }
                                                $GrossSales = getTotalGrossHarianRegister($Tanggal,$NoRegister);
                                                $TotalKembalian = getTotalKembalianHarianRegister($Tanggal,$NoRegister);
                                                $TotalCash = getTotalCashHarianRegister($Tanggal,$NoRegister);
                                                $TotalNonCash = getTotalNonCashHarianRegister($Tanggal,$NoRegister);
                                                $TotalDiskon = getTotalDiskonHarianRegister($Tanggal,$NoRegister);
                                                $NetSales = getTotalNettoHarianRegister($Tanggal,$NoRegister);
                                                ?>
                                                <div class="mb-4">
                                                    <span class="block text-gray-500 text-sm">Total Gross Sales / Register:</span>
                                                    <span class="text-xl font-semibold text-gray-800"><?php echo number_format($GrossSales,2,',','.'); ?></span>
                                                </div> 
                                                <div class="mb-4">
                                                    <span class="block text-gray-500 text-sm">Total Diskon Sales / Register:</span>
                                                    <span class="text-xl font-semibold text-gray-800"><?php echo number_format($TotalDiskon,2,',','.'); ?></span>
                                                </div>  
                                                <div class="mb-4">
                                                    <span class="block text-gray-500 text-sm">Nett Sales:</span>
                                                    <span class="text-xl font-semibold text-gray-800"><?php echo number_format($NetSales,2,',','.'); ?></span>
                                                </div>                                             
                                                <div class="mb-4">
                                                    <span class="block text-gray-500 text-sm">Kembalian:</span>
                                                    <span class="text-xl font-semibold text-gray-800"><?php echo number_format($TotalKembalian,2,',','.'); ?></span>
                                                </div>
                                                <div class="mb-4">
                                                    <span class="block text-gray-500 text-sm">Pembulatan:</span>
                                                    <span class="text-xl font-semibold text-gray-800"><?php echo number_format($Pembulatan,2,',','.'); ?> <span class="text-warning"><?php echo $StatusPembulatan; ?></span></span>
                                                </div>                                                                                                                               
                                            </div>      
                                            <div class="w-2/3 p-6">
                                                <div class="mb-4">
                                                    <h3 class="block text-gray-500 text-sm">Rincian Transaksi</h3>
                                                    <span class="text-xl font-semibold text-gray-800">Total Transaksi Cash</span>
                                                    <span class="text-xl font-semibold text-gray-800">(Rp <?php echo number_format($TotalCash,2,',','.'); ?>)</span><br>      
                                                    <span class="text-xl font-semibold text-gray-800">Total Transaksi Non Cash</span>
                                                    <span class="text-xl font-semibold text-gray-800">(Rp <?php echo number_format($TotalNonCash,2,',','.'); ?>)</span><br>                                                                                
                                                </div>                                                                                                                              
                                            </div>                                                                                   
                                        </div>
                                    </div>
                                </div>
                            </div>            
                            <div class="w-1/3 p-4">            
                                <div class="">
                                    <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                                        <div class="bg-gradient-to-r from-green-400 to-green-500 p-4">
                                            <h3 class="text-white text-lg font-bold">Informasi Register</h3>
                                        </div>
                                        <div class="p-6">
                                            <div class="mb-4">
                                                <span class="block text-gray-500 text-sm">Kode Register:</span>
                                                <span class="text-xl font-semibold text-gray-800"><?php echo htmlspecialchars($recView['kode_register']); ?></span>
                                            </div>
                                            <div class="mb-4">
                                                <span class="block text-gray-500 text-sm">Tanggal Open Register:</span>
                                                <span class="text-lg font-medium text-green-600"><?php echo htmlspecialchars($DisplayDate); ?></span>
                                            </div>
                                            <div class="mb-4">
                                                <span class="block text-gray-500 text-sm">Modal Awal:</span>
                                                <span class="text-lg font-medium text-green-600">Rp <?php echo number_format($recView['modal_awal'],2,',','.'); ?></span>
                                            </div>
                                            <div class="mb-4">
                                                <span class="block text-gray-500 text-sm">Nama Kasir:</span>
                                                <span class="text-lg font-medium text-green-600"><?php echo $recView['kode_kasir']; ?> - <?php echo getNamaUser($recView['kode_kasir']); ?></span>
                                            </div>
                                            <div class="mb-4">
                                                <span class="block text-gray-500 text-sm">Supervisor:</span>
                                                <span class="text-lg font-medium text-green-600"><?php echo $recView['kode_spv']; ?> - <?php echo getNamaUser($recView['kode_spv']); ?></span>
                                            </div>
                                            <div class="mb-4">
                                                <span class="block text-gray-500 text-sm">Tanggal Close Register:</span>
                                                <span class="text-lg font-medium text-green-600 <?php   echo $AddStyle; ?>"><?php echo htmlspecialchars($DisplayDateClose); ?></span>
                                            </div>
                                            <div class="mb-4">
                                                <span class="block text-gray-500 text-sm">Total Setoran:</span>
                                                <span class="text-lg font-medium text-green-600">Rp <?php echo number_format($recView['setoran_akhir'],2,',','.'); ?></span>
                                            </div>
                                            <div class="mb-4">
                                                <span class="block text-gray-500 text-sm">Total Setoran Kasir:</span>
                                                <span class="text-lg font-medium text-green-600">Rp <?php echo number_format($TotalPecahanKasir,2,',','.'); ?></span>
                                            </div>
                                            <div class="mb-4">
                                                <span class="block text-gray-500 text-sm">Selisih Setoran Kasir</span>
                                                <span class="text-lg font-medium text-green-600">Rp <?php echo number_format($SelisihSetoran,2,',','.'); ?>(<?php echo $StatusSelisih; ?>)</span>
                                            </div>                                       
                                        </div>
                                    </div>
                                </div>
                                <?php
                                if($StatusClose > 0){
                                ?>
                                <div class="mt-2">
                                    <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                                        <div class="bg-gradient-to-r from-red-400 to-red-500 p-4">
                                            <h3 class="text-white text-lg font-bold">Form Konfirmasi Register</h3>
                                        </div>
                                        <div class="p-6">
                                            <form name="formProses" name="frmKonfirmasiRegister" id="frmKonfirmasiRegister" method="post" action="proses-konfirm-register">
                                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-1">
                                                    <?php
                                                    if($StatusRespon == 0){
                                                    ?>                                                      
                                                    <input type="hidden" name="txtNoRegister" id="txtNoRegister" value="<?php echo $_GET['rcode'];    ?>">
                                                    <label class="block">
                                                        <span class="text-purple-500 font-bold">Setoran Diterima <div class="badge rounded-full bg-primary/10 text-primary dark:bg-accent-light/15 dark:text-accent-light">Wajib</div></span>
                                                        <span class="relative mt-1.5 flex">						
                                                            <input placeholder="Masukan Jumlah Setoran Diterima" type="text" id="txtTerimaSetoran" name="txtTerimaSetoran" required
                                                            class="form-input peer h-12 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"/>
                                                            <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                                                                <i class="fa-regular fa-building text-base"></i>
                                                            </span>
                                                        </span>
                                                    </label>                                                    
                                                    <label class="block">
                                                        <span class="text-purple-500 font-bold">Komentar Senior Kasir <div class="badge rounded-full bg-primary/10 text-primary dark:bg-accent-light/15 dark:text-accent-light">Wajib</div></span>
                                                        <span class="relative mt-1.5 flex">						
                                                            <textarea rows="5" placeholder="Komentar Senior Kasir" id="txtKomentarAdmin" name="txtKomentarAdmin" required 
                                                            class="form-textarea mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent p-2.5 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"></textarea>
                                                        </span>
                                                    </label>      
                                                    <label class="inline-flex items-center space-x-2">
                                                        <input checked readonly
                                                        class="form-radio is-basic h-5 w-5 rounded-full border-slate-400/70 checked:border-primary checked:bg-primary hover:border-primary focus:border-primary dark:border-navy-400 dark:checked:border-accent dark:checked:bg-accent dark:hover:border-accent dark:focus:border-accent"
                                                        name="basic"
                                                        type="radio"/>
                                                        <p>Saya sudah melakukan pengecekan Register!</p>
                                                    </label>                                                                                            
                                                    <label class="block">
                                                        <span class="relative mt-1.5 flex">						
                                                            <input type="submit" name="btnSubmit" id="btnSubmit" value="Konfirmasi Close Register"
                                                            class="btn space-x-2 bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
                                                        </span>
                                                    </label>     
                                                    <?php
                                                    }else{
                                                        $TerimaSetoran = $recView['setoran_diterima'];
                                                        $KomentarRespon = $recView['respon_skasir'];
                                                        $KomentarRespon = strip_tags($KomentarRespon);
                                                        $TanggalRespon = $recView['respon_date'];
                                                        $ResponUser = $recView['respon_user'];
                                                    ?>  
                                                    <label class="block">
                                                        <span class="text-purple-500 font-bold">Komentar Senior Kasir <div class="badge rounded-full bg-primary/10 text-primary dark:bg-accent-light/15 dark:text-accent-light">Wajib</div></span>
                                                        <span class="relative mt-1.5 flex">						
                                                            <textarea rows="5" placeholder="Komentar Senior Kasir" id="txtKomentarAdmin" name="txtKomentarAdmin" required readonly
                                                            class="form-textarea mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent p-2.5 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"><?php    echo $KomentarRespon;   ?></textarea>
                                                        </span>
                                                    </label>                                                          
                                                    <label class="inline-flex items-center space-x-2">
                                                        <input checked readonly
                                                        class="form-radio is-basic h-5 w-5 rounded-full border-slate-400/70 checked:border-success checked:bg-success hover:border-success focus:border-success dark:border-navy-400 dark:checked:border-accent dark:checked:bg-accent dark:hover:border-accent dark:focus:border-accent"
                                                        name="basic"
                                                        type="radio"/>
                                                        <p>User sudah melakukan Konfirm Register!</p>
                                                    </label>                                                       
                                                    <div class="text-primary font-medium bg-primary/10 p-2">Setoran Diterima : <?php echo number_format($TerimaSetoran,2,',','.');    ?></div>
                                                    <div class="text-primary font-medium bg-primary/10 p-2">Tanggal Terima Setoran : <?php echo $TanggalRespon;    ?></div>
                                                    <div class="text-primary font-medium bg-primary/10 p-2">Nama Penerima : <?php echo getNamaUser($ResponUser);    ?></div>
                                                    <?php
                                                    }
                                                    ?>                                                                                                               
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div> 
                                <?php
                                }
                                ?>
                            </div>                                                  
                        </div>
                    </div>

                    <?php
                    if($StatusRespon > 0){
                    $NamaStore = getStoreName($KodeStoreOffline);
                    $HeaderStruk = getHeaderStruk($KodeStoreOffline);
                    $FooterStruk = getFooterStruk($KodeStoreOffline); 
                    ?>
                    <div id="PrintArea" style="display:none;">
                        <table width="50%" style="border:1px solid #000;">
                            <tr>
                                <td>
                                    <div style="font-size:12px;color:#000;font-weight:800;"><?php   echo $NamaStore; ?></div>
                                    <div style="font-size:12px;color:#000;"><?php   echo $HeaderStruk; ?></div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                <hr>
                                <div style="font-size:12px;color:#000;font-weight:800;">Detail Register</div>
                                <table width="100%">
                                    <tr>
                                        <td width="30%"><div style="font-size:12px;color:#000;font-weight:800;">Kode Register:</div></td>
                                        <td width="70%"><div style="font-size:12px;color:#000;"><?php echo htmlspecialchars($recView['kode_register']); ?></div></td>
                                    </tr>
                                    <tr>
                                        <td><div style="font-size:12px;color:#000;font-weight:800;">Tanggal Open Register:</div></td>
                                        <td><div style="font-size:12px;color:#000;"><?php echo htmlspecialchars($DisplayDate); ?></div></td>
                                    </tr>
                                    <tr>
                                        <td><div style="font-size:12px;color:#000;font-weight:800;">Nama Kasir:</div></td>
                                        <td><div style="font-size:12px;color:#000;"><?php echo $recView['kode_kasir']; ?> - <?php echo getNamaUser($recView['kode_kasir']); ?></div></td>
                                    </tr>
                                    <tr>
                                        <td><div style="font-size:12px;color:#000;font-weight:800;">Supervisor:</div></td>
                                        <td><div style="font-size:12px;color:#000;"><?php echo $recView['kode_spv']; ?> - <?php echo getNamaUser($recView['kode_spv']); ?></div></td>
                                    </tr>
                                    <tr>
                                        <td><div style="font-size:12px;color:#000;font-weight:800;">Tanggal Close Register:</div></td>
                                        <td><div style="font-size:12px;color:#000;"><?php echo htmlspecialchars($DisplayDateClose); ?></div></td>
                                    </tr>                                                                                                                
                                </table>  
                                <hr>
                                <div style="font-size:12px;color:#000;font-weight:800;">Rincian Penjualan</div>
                                <table width="100%">
                                    <tr>
                                        <td width="30%"><div style="font-size:12px;color:#000;font-weight:800;">Gross Sales</div></td>
                                        <td width="70%"><div style="font-size:12px;color:#000;text-align:right"><?php echo number_format($GrossSales,2,',','.'); ?></div></td>
                                    </tr>
                                    <tr>
                                        <td><div style="font-size:12px;color:#000;font-weight:800;">Diskon</div></td>
                                        <td><div style="font-size:12px;color:#000;text-align:right"><?php echo number_format($TotalDiskon,2,',','.'); ?></div></td>
                                    </tr>
                                    <tr>
                                        <td><div style="font-size:12px;color:#000;font-weight:800;">Pembulatan:</div></td>
                                        <td><div style="font-size:12px;color:#000;text-align:right"><?php echo number_format($Pembulatan,2,',','.'); ?> - <?php echo $StatusPembulatans; ?></div></td>
                                    </tr>
                                    <tr>
                                        <td><div style="font-size:12px;color:#000;font-weight:800;">Net Sales:</div></td>
                                        <td><div style="font-size:12px;color:#000;text-align:right"><?php echo number_format($NetSales,2,',','.'); ?></div></td>
                                    </tr>                                                                                                              
                                </table>
                                <hr>
                                <div style="font-size:12px;color:#000;font-weight:800;">Pembayaran POS</div>
                                <table width="100%">
                                    <tr>
                                        <td width="30%"><div style="font-size:12px;color:#000;font-weight:800;">Cash</div></td>
                                        <td width="70%"><div style="font-size:12px;color:#000;text-align:right"><?php echo number_format($TotalPembayaranCash,2,',','.'); ?></div></td>
                                    </tr>                            
                                    <tr>
                                        <td width="30%"><div style="font-size:12px;color:#000;font-weight:800;">Kartu Kredit</div></td>
                                        <td width="70%"><div style="font-size:12px;color:#000;text-align:right"><?php echo number_format($TotalPembayaranKredit,2,',','.'); ?></div></td>
                                    </tr>
                                    <tr>
                                        <td><div style="font-size:12px;color:#000;font-weight:800;">Kartu Debit</div></td>
                                        <td><div style="font-size:12px;color:#000;text-align:right"><?php echo number_format($TotalPembayaranDebit,2,',','.'); ?></div></td>
                                    </tr>
                                    <tr>
                                        <td><div style="font-size:12px;color:#000;font-weight:800;">Ewallet:</div></td>
                                        <td><div style="font-size:12px;color:#000;text-align:right"><?php echo number_format($TotalPembayaranQris,2,',','.'); ?></div></td>
                                    </tr> 
                                    <tr>
                                        <td><div style="font-size:12px;color:#000;font-weight:800;">Voucher:</div></td>
                                        <td><div style="font-size:12px;color:#000;text-align:right"><?php echo number_format($recView['total_poin'],2,',','.'); ?></div></td>
                                    </tr>
                                    <tr>
                                        <td><div style="font-size:12px;color:#000;font-weight:800;">Point:</div></td>
                                        <td><div style="font-size:12px;color:#000;text-align:right"><?php echo number_format($recView['total_voucher'],2,',','.'); ?></div></td>
                                    </tr>                                                                                                             
                                </table>   
                                <hr>
                                <table width="100%">
                                    <tr>
                                        <td width="30%"><div style="font-size:12px;color:#000;font-weight:800;">Total Yang harus Disetor:</div></td>
                                        <td width="70%"><div style="font-size:12px;color:#000;text-align:right"><?php echo number_format($TotalSetoran,2,',','.'); ?></div></td>
                                    </tr>  
                                    <tr>
                                        <td width="30%"><div style="font-size:12px;color:#000;font-weight:800;">Total Setoran Kasir:</div></td>
                                        <td width="70%"><div style="font-size:12px;color:#000;text-align:right"><?php echo number_format($TotalPecahanKasir,2,',','.'); ?></div></td>
                                    </tr>  
                                    <tr>
                                        <td width="30%"><div style="font-size:12px;color:#000;font-weight:800;">Total Setoran:</div></td>
                                        <td width="70%"><div style="font-size:12px;color:#000;text-align:right"><?php echo number_format($SelisihSetoran,2,',','.'); ?>(<?php echo $StatusSelisih; ?>)</div></td>
                                    </tr> 
                                </table>   
                                <hr>
                                <table width="100%">
                                    <tr>
                                        <td width="30%"><div style="font-size:12px;color:#000;font-weight:800;">Total Transaksi Cash:</div></td>
                                        <td width="70%"><div style="font-size:12px;color:#000;text-align:right"><?php echo number_format($TotalCash,2,',','.'); ?></div></td>
                                    </tr>  
                                    <tr>
                                        <td width="30%"><div style="font-size:12px;color:#000;font-weight:800;">Total Transaksi Non Cash:</div></td>
                                        <td width="70%"><div style="font-size:12px;color:#000;text-align:right"><?php echo number_format($TotalNonCash,2,',','.'); ?></div></td>
                                    </tr>  
                                </table>
                                <hr>
                                <div style="font-size:12px;color:#000;font-weight:800;">Catatan Closing</div>
                                <table width="100%">
                                    <tr>
                                        <td width="30%"><div style="font-size:12px;color:#000;font-weight:800;">Total Diterima:</div></td>
                                        <td width="70%"><div style="font-size:12px;color:#000;text-align:right"><?php echo number_format($TerimaSetoran,2,',','.'); ?></div></td>
                                    </tr> 
                                    <tr>
                                        <td width="30%"><div style="font-size:12px;color:#000;font-weight:800;">Tanggal Diterima:</div></td>
                                        <td width="70%"><div style="font-size:12px;color:#000;text-align:right"><?php echo $TanggalRespon; ?></div></td>
                                    </tr>   
                                    <tr>
                                        <td width="30%"><div style="font-size:12px;color:#000;font-weight:800;">Nama Diterima:</div></td>
                                        <td width="70%"><div style="font-size:12px;color:#000;text-align:right"><?php echo $ResponUser; ?> - <?php echo  getNamaUser($ResponUser); ?></div></td>
                                    </tr>    
                                    <tr>
                                        <td width="30%"><div style="font-size:12px;color:#000;font-weight:800;">Catatan:</div></td>
                                        <td width="70%"><div style="font-size:12px;color:#000;text-align:right"><?php echo $KomentarRespon; ?></div></td>
                                    </tr>                                                         
                                </table>
                                </td>
                            </tr>
                        </table>

                    </div>
                    <?php                      
                    }else{
                    ?>
                    <div id="PrintArea" style="display:none;">
                        <h1>REGISTER MASIH ACTIVE BELUM DI TUTUP</h1>
                        <h3>BELUM BISA DI PRINT</h3>
                        <h3>SILAHKAN PERIKSA APAKAH KASIR SUDAH MELAKUKAN CLOSE REGISTER / BELUM</h3>
                    </div>
                    <?php                      
                    }
                    ?>
                </div>             
                <?php           
            }
          
        }
        ?>
    </main>
</div>
<!-- 
    This is a place for Alpine.js Teleport feature 
    @see https://alpinejs.dev/directives/teleport
    -->
<div id="x-teleport-target"></div>
<script>
    window.addEventListener("DOMContentLoaded", () => Alpine.start());
</script>
</body>
</html>
<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../lib_js/kasir.js"></script>
<script type="text/javascript">
function PrintDoc() {
    var toPrint = document.getElementById('PrintArea');
    var popupWin = window.open('', '_blank', 'width=800,height=600,location=no,left=50px');
    popupWin.document.open();
    popupWin.document.write('<html><title>Preview Print</title><link rel="stylesheet" type="text/css" href="/assets/css/print.css" media="print"/></head><body onload="window.print();window.close();">')
    popupWin.document.write(toPrint.innerHTML);
    popupWin.document.write('</body></html>');
    popupWin.document.close();
}

    $("#txtTerimaSetoran").on("focus", function() {
        $(this).css("background-color", "#e0f7fa"); // Change background color on enter.
        var modalawal = $(this).val();
        modalawal = modalawal.replaceAll(".", "");
        $(this).val(modalawal);
    });

    $("#txtTerimaSetoran").on("blur", function() {
        $(this).css("background-color", ""); // Reset background color on leave.
        var modalawal = $(this).val();
        modalawal = formatRibuan(modalawal);
        $(this).val(modalawal);
    });

$(document).ready(function (){
    var txtErrorType = $("#txtErrorType").val();
    var txtErrorDescription = $("#txtErrorDescription").val();
    var txtGetDetail = $("#txtGetDetail").val();
    
    if(txtErrorDescription != ""){
        Swal.fire({
            position: 'center',
            icon: txtErrorType,
            html: txtErrorDescription,
            confirmButtonColor: "#3085d6",
            confirmButtonText: "Tutup Pesan Ini",
        })
    }
});    


</script>