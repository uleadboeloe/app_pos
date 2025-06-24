<?php
error_reporting(0);
ini_set('display_errors', 0);

session_start();

include("lib/mysql_pdo.php");
include("lib/mysql_connect.php");	
include("lib/general_lib.php");
include("admin/library/fungsi.php");
include("admin/library/qrcode/qrlib.php");

if(!isset($_GET['koderegister'])){
    header("Location: kasir.php");
    exit();
}else{

$user_id = $_SESSION['SESS_user_id']; 
$user_name = $_SESSION['SESS_user_name'];
$kode_kasir = $_SESSION['SESS_kode_kasir'];

//require_once('securepage.php');
//chkpage(600); // set idle max 60 seconds

$encryptedKodeToko = getConfig('KodeToko');
$kode_store = $encryptedKodeToko ? decryptData($encryptedKodeToko, $cryptkey) : "ERROR! KODE TOKO TIDAK VALID!";

?>

<!DOCTYPE html>
<!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>    <html class="lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>    <html class="lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html> <!--<![endif]-->
<head>
	<title>InsanPOS</title>
	<link rel="shortcut icon" type="image/png" href="pos.png">
	
	<!-- Meta -->
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="mobile-web-app-capable" content="yes">
	<!-- Bootstrap -->
	<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" />
	<link href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" />
	
	<!-- Glyphicons -->
	<link rel="stylesheet" href="theme/css/glyphicons.css" />
	
	<!-- JQueryUI v1.9.2 -->
	<link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css">

	<!-- JQuery -->
	<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
  	<script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>

	<!-- google-code-prettify -->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/prettify/r298/prettify.css" type="text/css" rel="stylesheet" />
	
	<!-- Theme -->
	<link rel="stylesheet" href="theme/css/style.min.css?1362656653" />
	<!-- Theme Eddy -->
	<link rel="stylesheet" href="theme/css/preview.css" />    
	
	<!-- LESS 2 CSS -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/less.js/3.9.0/less.js"></script>

	<!-- DataTables -->
	<link rel="stylesheet" media="screen" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css" />
	<script src="https://cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>

    <!-- Moment.js -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.js"></script>

	<!-- SELECT2 -->
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <link rel="stylesheet" href="admin/assets/lineone/css/app.css" />
    <link rel="stylesheet" href="admin/assets/css/custom.css" />
    <!-- Javascript Assets -->
    <script src="admin/assets/lineone/js/app.js" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>

	<style>
		label, input { display:block; }
		input.text { margin-bottom:12px; width:95%; padding: .4em; }
		fieldset { padding:0; border:0; margin-top:25px; }
		h1 { font-size: 1.2em; margin: .6em 0; }
		.ui-dialog .ui-state-error { padding: .3em; }
		.validateTips { border: 1px solid transparent; padding: 0.3em; }
		.iconcolor { color: #7A7474; }
	</style>

	<style>
		.ui-widget-header{
			border-width: 0px 0px 1px 0px;
			border-color: #dddddd;
			background: #fff;
			color: #000;
		}
		p {
			font-size: 15px;
		}
		
		table {
			user-select: none; 
			-webkit-user-select: none; 
			-moz-user-select: none; 
			-ms-user-select: none; 
		}

		/* table tr td { height: 40px; } */
		table td { line-height: 40px !important; }
		.keypad { line-height: 0px !important; }
		.keypad-divider { line-height: 20px !important; }

		.td-no-line-height { line-height: 0px !important; }
		
		.qtybtn {
			border: none;
			color: white;
			text-align: center;
			text-decoration: none;
			display: inline-block;
			font-size: 16px;
			padding: 0px 15px;
			border-radius: 12px;
			cursor: pointer;
		}

		.qtybtnmin {
			background-color:rgb(209, 103, 32);
		}
		.qtybtnplus {
			background-color:rgb(18, 92, 64);
		}
		.qtybtndel {
			background-color:rgb(192, 0, 0);
		}

		#scrollable {
            width: 100%;
            height: 345px;
            overflow: hidden;
            position: relative;
            /* cursor: grab; */
        }
        #listscanneditems {
            width: 100%;
            height: 100%;
        }
		#TxtSubTotal, #TxtPPN, #TxtGrandTotal {
			font-weight: bold;
		}

        .TextMiring{
            position: absolute;
            top: 5%;
            left: 50%;
            z-index: 50;
            transform: translate(-50%,-50%);
            -ms-transform: translate(-50%,-50%);
            transform: rotate(45deg);
            transform-origin: 0 0; /* Menentukan titik rotasi (sudut kiri atas) */ 
        }        
	</style>
</head>

<!-- Start Content -->
<div class="container-fluid">
	<div id="wrapper">
		<!-- Header -->
        <?php
        if(isset($_GET['koderegister'])){
            $KodeRegister = $_GET['koderegister'];

            /*GENERATE QRCODE LOGO*/
			$tempdir="admin/qrcode_closing/";
			$file_name = $KodeRegister.".png";
			//$record_value = $Timestampx . "#" . $labelKegiatanx . "#" . $txtTglAwalx . "#^" . $txtTglAkhirx;
			$record_value = $KodeRegister;
			$file_path = $tempdir.$file_name;
			$forecolor = "0,0,0";
			$backcolor = "255,255,255";
			$logo = "admin/assets/images/barcodelogo.png";
			/* param (1)qrcontent,(2)filename,(3)errorcorrectionlevel,(4)pixelwidth,(5)margin,(6)saveandprint,(7)forecolor,(8)backcolor */
			QRcode::png($record_value, $file_path, "H", 6, 1, 0, $forecolor, $backcolor, $logo);
			$FileBarcode = $tempdir.$file_name;
			/*GENERATE QRCODE*/

            /*CREATEBARCODE*/
            // Deteksi apakah protokolnya http atau https
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
            // Buat URL ke barcode generator
            $file_gambar = $protocol . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/admin/barcode.php?text=" . urlencode($KodeRegister) . "&print=true&size=50&orientation=horizontal&code_type=code128";
            $folderPath = __DIR__ . '/admin/qrcode_transaksi/barcodes/';
            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0777, true); // buat folder jika belum ada
            }
            $filename = $folderPath . $KodeRegister . '.png';
            $FileBarcodes = "admin/qrcode_transaksi/barcodes/" . $KodeRegister . ".png";
            // Ambil data gambar dari URL
            $barcode_data = file_get_contents($file_gambar);
            if ($barcode_data !== false) {
                file_put_contents($filename, $barcode_data);
            }
            
            if(isset($_GET['msgcode'])){
                echo "<input type='hidden' id='txtMsg' name='txtMsg' value='" . $_GET['msgcode'] . "' class='text-primary' />";
            }else{
                echo "<input type='hidden' id='txtMsg' name='txtMsg' value='' class='text-primary' />";
            }
            $strQuery = $db->prepare("SELECT * FROM dbo_register WHERE kode_register = :kode_register");
            $strQuery->execute(array(':kode_register' => $KodeRegister));
            $rec = $strQuery->fetchAll();

            $KodeKasir = $rec[0]["kode_kasir"];
            $KodeStore = $rec[0]["kode_store"];
            $TangalRegister = $rec[0]["tanggal"];
            $JamRegister = $rec[0]["jam"];
            $ShowTanggal = date('d-m-Y', strtotime($TangalRegister)) . " " . $JamRegister;
            $ModalAwal = $rec[0]["modal_awal"];
            $KodeSPV = $rec[0]["kode_spv"];
            $JumlahStrukCash = $rec[0]["jumlah_struk_cash"];
            $JumlahStrukKredit = $rec[0]["jumlah_struk_kredit"];
            $JumlahStrukDebit = $rec[0]["jumlah_struk_debit"];
            $JumlahStrukQris = $rec[0]["jumlah_struk_qris"];
            $TotalPembayaranCash = $rec[0]["total_pembayaran_cash"] ?? 0;
            $TotalPembayaranKredit = $rec[0]["total_pembayaran_kredit"] ? $rec[0]["total_pembayaran_kredit"] : 0;
            $TotalPembayaranDebit = $rec[0]["total_pembayaran_debit"]? $rec[0]["total_pembayaran_debit"] : 0;
            $TotalPembayaranQris = $rec[0]["total_pembayaran_qris"]? $rec[0]["total_pembayaran_qris"] : 0;
            $TotalVoucher = $rec[0]["total_voucher"];
            $TotalPoin = $rec[0]["total_poin"];
            $TotalPembayaran = $TotalPembayaranCash + $TotalPembayaranKredit + $TotalPembayaranDebit + $TotalPembayaranQris + $TotalVoucher + $TotalPoin;
            $SetoranAkhir = $rec[0]["setoran_akhir"];
            $PostingUser = $rec[0]["posting_user"];
            $PostingDate = $rec[0]["posting_date"];
            $ClosingDate = $rec[0]["closing_date"];
            $ClosingTime = $rec[0]["closing_time"];
            $ClosingUser = $rec[0]["closing_user"];
            $PK100000= $rec[0]["c100000"]*100000;
            $PK50000= $rec[0]["c50000"]*50000;
            $PK20000= $rec[0]["c20000"]*20000;
            $PK10000= $rec[0]["c10000"]*10000;
            $PK5000= $rec[0]["c5000"]*5000;
            $PK2000= $rec[0]["c2000"]*2000;
            $PK1000= $rec[0]["c1000"]*1000;
            $TotalPecahanKertas = $PK100000+$PK50000+$PK20000+$PK10000+$PK5000+$PK2000+$PK1000;
            $PC1000= $rec[0]["c1000k"]*1000;
            $PC500= $rec[0]["c500k"]*500;
            $PC200= $rec[0]["c200k"]*200;
            $TotalPecahanLogam = $PC1000+$PC500+$PC200;
            $TotalPecahanKasir = $TotalPecahanKertas+$TotalPecahanLogam;
            $TotalSetoranKasir = $TotalPecahanKertas+$TotalPecahanLogam+$ModalAwal;
            $TotalSetoran = $ModalAwal+$SetoranAkhir;
            $SelisihSetoran = $TotalSetoranKasir-$TotalSetoran;        
            if($SelisihSetoran < 0){
                $StatusSelisih = "Setoran Kurang";
            }else{
                $StatusSelisih = "Setoran Lebih";
            }

            $HeaderStruk = getHeaderStruk($kode_store);
            $FooterStruk = getFooterStruk($kode_store);
            ?>            
            <!-- Header -->
            <div class="row-fluid" style="background-color: #4e8f64;">
                <div class="span7">
                    <div>
                        <!-- App Brand -->
                        <div class="span3" style="border-right: 1px solid #9bb6cf; height: 50px;">
                            <p style="font-weight: 900; font-size: 150%; color: #fff; padding: 9px 0px 0px 15px; text-shadow: 1px 1px #253d2d;">InsanPOS <span style="font-size: 60%;">v.1.00</span></p>
                        </div>
                        <!-- Input Scan -->
                        <div class="span9" style="padding-top: 8px;">
                            <div class="span12" style="padding-top: 6px; text-align: right;">
                                <span style="font-weight: 600; font-size: 115%; color: #fff; text-shadow: 1px 1px #253d2d;">No. Register:&nbsp;</span>
                                <span id="TxtOrderNo" style="font-weight: 600; font-size: 115%; color: #fff; text-shadow: 1px 1px #253d2d;"><?php echo $KodeRegister;    ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="span5">
                    <!-- Header Buttons -->
                    <div style="padding: 7px 35px 0px 10px; float: right;">
                        <table>
                            <tr>
                                <td class="td-no-line-height"><span id="BtnBack" onclick="window.location.href ='index.php'" class="btn btn-block btn-default btn-icon glyphicons home"><i></i>CLOSE</span></td>
                                <td class="td-no-line-height">&nbsp;</td>
                                <td class="td-no-line-height"><span id="BtnPrint" onclick="PrintDoc();" class="btn btn-block btn-default btn-icon glyphicons print"><i></i>PRINT STRUK</span></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <!-- End of Header -->
            <div class="row-fluid">
                <div id="PrintArea">
                    <div class="span2">&nbsp;</div>
                    <div class="span8" align="center"> 
                        <?php 
                        echo "<img src='admin/assets/images/logo_struk.png' alt='logo' width='70%'/>";    
                        ?>
                        <div style="color:#000;font-size:10px;"><?php echo $HeaderStruk;    ?></div>
                        <hr>
                        <table width="100%" style="font-size: 12px; color: #000;">
                            <tr>
                                <td width="50%" style="font-size: 12px; color: #000;">Nomor Register :</td>
                                <td width="50%" align="right" style="font-size: 12px; color: #000;"><?php echo $KodeRegister;    ?></td>
                            </tr>                            
                            <tr>
                                <td width="50%">Tanggal :</td>
                                <td width="50%" align="right" style="font-size: 12px; color: #000;"><?php echo $ShowTanggal;    ?></td>
                            </tr>
                            <tr>
                                <td width="50%">Kode / Nama Kasir :</td>
                                <td width="50%" align="right" style="font-size: 12px; color: #000;"><?php echo $KodeKasir;    ?> / <?php echo getNamaUser($KodeKasir);    ?></td>
                            </tr>
                            <tr>
                                <td width="50%">Store :</td>
                                <td width="50%" align="right" style="font-size: 12px; color: #000;"><?php echo $kode_store;    ?> <?php echo getNamaStore($kode_store);    ?></td>
                            </tr>
                            <tr>
                                <td width="50%">Kode / Supervisor :</td>
                                <td width="50%" align="right" style="font-size: 12px; color: #000;"><?php echo $KodeSPV;    ?> / <?php echo getNamaUser($KodeSPV);    ?></td>
                            </tr>                                                
                        </table> 
                        <hr>
                        Pecahan Uang Kertas:
                        <table id="tableFooter" width="100%">
                            <tr>
                                <td width="40%" style="font-size: 12px; color: #000;">Pecahan 100.000 X</td>
                                <td width="30%" align="right" style="font-size: 12px; color: #000;"><?php echo $rec[0]["c100000"];    ?></td>
                                <td width="40%" align="right" style="font-size: 12px; color: #000;"><?php echo number_format($rec[0]["c100000"]*100000,2);    ?></td>
                            </tr>
                            <tr>
                                <td width="40%" style="font-size: 12px; color: #000;">Pecahan 50.000</td>
                                <td width="30%" align="right" style="font-size: 12px; color: #000;"><?php echo $rec[0]["c50000"];    ?></td>
                                <td width="40%" align="right" style="font-size: 12px; color: #000;"><?php echo number_format($rec[0]["c50000"]*0000,2);    ?></td>
                            </tr>
                            <tr>
                                <td width="40%" style="font-size: 12px; color: #000;">Pecahan 20.000</td>
                                <td width="30%" align="right" style="font-size: 12px; color: #000;"><?php echo $rec[0]["c20000"];    ?></td>
                                <td width="40%" align="right" style="font-size: 12px; color: #000;"><?php echo number_format($rec[0]["c20000"]*20000,2);    ?></td>
                            </tr>
                            <tr>
                                <td width="40%" style="font-size: 12px; color: #000;">Pecahan 10.000</td>
                                <td width="30%" align="right" style="font-size: 12px; color: #000;"><?php echo $rec[0]["c10000"];    ?></td>
                                <td width="30%" align="right" style="font-size: 12px; color: #000;"><?php echo number_format($rec[0]["c10000"]*10000,2);    ?></td>
                            </tr>   
                            <tr>
                                <td width="40%" style="font-size: 12px; color: #000;">Pecahan 5.000</td>
                                <td width="30%" align="right" style="font-size: 12px; color: #000;"><?php echo $rec[0]["c5000"];    ?></td>
                                <td width="30%" align="right" style="font-size: 12px; color: #000;"><?php echo number_format($rec[0]["c5000"]*5000,2);    ?></td>
                            </tr>
                            <tr>
                                <td width="40%" style="font-size: 12px; color: #000;">Pecahan 2.000</td>
                                <td width="30%" align="right" style="font-size: 12px; color: #000;"><?php echo $rec[0]["c2000"];    ?></td>
                                <td width="30%" align="right" style="font-size: 12px; color: #000;" style="font-size: 12px; color: #000;"><?php echo number_format($rec[0]["c2000"]*2000,2);    ?></td>
                            </tr>  
                            <tr>
                                <td width="40%" style="font-size: 12px; color: #000;">Pecahan 1.000</td>
                                <td width="30%" align="right" style="font-size: 12px; color: #000;"><?php echo $rec[0]["c1000"];    ?></td>
                                <td width="30%" align="right" style="font-size: 12px; color: #000;"><?php echo number_format($rec[0]["c1000"]*100000,2);    ?></td>
                            </tr>   
                            <tr>
                                <td width="60%" colspan="2" align="right" style="font-size: 12px; color: #000;">Total Pecahan Kertas</td>
                                <td width="30%" align="right" style="font-size: 12px; color: #000;"><?php echo number_format($TotalPecahanKertas,2);    ?></td>
                            </tr>                                                                         
                        </table>  
                        Pecahan Uang Logam:
                        <table id="tableFooter" width="100%">
                            <tr>
                                <td width="40%" style="font-size: 12px; color: #000;">Pecahan 1.000</td>
                                <td width="30%" align="right" style="font-size: 12px; color: #000;"><?php echo $rec[0]["c1000k"];    ?></td>
                                <td width="30%" align="right" style="font-size: 12px; color: #000;"><?php echo number_format($rec[0]["c1000k"]*1000,2);    ?></td>
                            </tr> 
                            <tr>
                                <td width="40%" style="font-size: 12px; color: #000;">Pecahan 500</td>
                                <td width="30%" align="right" style="font-size: 12px; color: #000;"><?php echo $rec[0]["c500k"];    ?></td>
                                <td width="30%" align="right" style="font-size: 12px; color: #000;"><?php echo number_format($rec[0]["c500k"]*500,2);    ?></td>
                            </tr>
                            <tr>
                                <td width="40%" style="font-size: 12px; color: #000;">Pecahan 200</td>
                                <td width="30%" align="right" style="font-size: 12px; color: #000;"><?php echo $rec[0]["c200k"];    ?></td>
                                <td width="30%" align="right" style="font-size: 12px; color: #000;"><?php echo number_format($rec[0]["c200k"]*200,2);    ?></td>
                            </tr>
                            <tr>
                                <td width="60%" colspan="2" align="right" style="font-size: 12px; color: #000;">Total Pecahan Logam</td>
                                <td width="30%" align="right" style="font-size: 12px; color: #000;"><?php echo number_format($TotalPecahanLogam,2);    ?></td>
                            </tr>                                                                        
                        </table> 
                        <hr>
                        Detail Setoran:
                        <table id="tableFooter" width="100%">
                            <tr>
                                <td width="50%" style="font-size: 12px; color: #000;">Total Pecahan Kasir :</td>
                                <td width="50%" align="right" style="font-size: 12px; color: #000;"><?php echo number_format($TotalPecahanKasir,2);    ?></td>
                            </tr>
                            <tr>
                                <td width="50%" style="font-size: 12px; color: #000;">Modal Register :</td>
                                <td width="50%" align="right" style="font-size: 12px; color: #000;"><?php echo number_format($ModalAwal,2);    ?></td>
                            </tr>
                            <tr>
                                <td width="50%" style="font-size: 12px; color: #000;">Total Setoran Akhir :</td>
                                <td width="50%" align="right" style="font-size: 12px; color: #000;"><?php echo number_format($TotalSetoran,2);    ?></td>
                            </tr>
                            <tr>
                                <td width="50%" style="font-size: 12px; color: #000;">Selisih Setoran :</td>
                                <td width="50%" align="right" style="font-size: 12px; color: #000;"><?php echo $StatusSelisih;    ?> : <?php echo number_format($SelisihSetoran,2);    ?></td>
                            </tr>                                                                                    
                        </table>
                        Rincian Transaksi:
                        <?php
                        $Pembulatan = getTotalPembulatanHarianRegister($TangalRegister,$KodeRegister);
                        if($Pembulatan < 0){
                            $Pembulatan = $Pembulatan*-1;
                            $StatusPembulatan = "Pembulatan -";
                        }else{
                            $StatusPembulatan = "Pembulatan +";
                        }
                        $GrossSales = getTotalGrossHarianRegister($TangalRegister,$KodeRegister);
                        $TotalKembalian = getTotalKembalianHarianRegister($TangalRegister,$KodeRegister);
                        $TotalCash = getTotalCashHarianRegister($TangalRegister,$KodeRegister);
                        $TotalNonCash = getTotalNonCashHarianRegister($TangalRegister,$KodeRegister);
                        $TotalDiskon = getTotalDiskonHarianRegister($TangalRegister,$KodeRegister);
                        $NetSales = getTotalNettoHarianRegister($TangalRegister,$KodeRegister);
                        ?>                            
                        <table id="tableFooter" width="100%">
                            <tr>
                                <td width="50%" style="font-size: 12px; color: #000;">Jumlah Struk / Total Struk Cash</td>
                                <td width="50%" align="right" style="font-size: 12px; color: #000;"><?php echo number_format($TotalPembayaranCash,2);    ?></td>
                            </tr>
                            <tr>
                                <td width="50%" style="font-size: 12px; color: #000;">Jumlah Struk / Total Struk Kredit</td>
                                <td width="50%" align="right" style="font-size: 12px; color: #000;"><?php echo number_format($TotalPembayaranKredit,2);    ?></td>
                            </tr>
                            <tr>
                                <td width="50%" style="font-size: 12px; color: #000;">Jumlah Struk / Total Struk Debit</td>
                                <td width="50%" align="right" style="font-size: 12px; color: #000;"><?php echo number_format($TotalPembayaranDebit,2);    ?></td>
                            </tr>
                            <tr>
                                <td width="50%" style="font-size: 12px; color: #000;">Jumlah Struk / Total Struk Ewallet</td>
                                <td width="50%" align="right" style="font-size: 12px; color: #000;"><?php echo number_format($TotalPembayaranQris,2);    ?></td>
                            </tr>    
                            <tr>
                                <td width="50%" style="font-size: 12px; color: #000;">Total Pembulatan</td>
                                <td width="50%" align="right" style="font-size: 12px; color: #000;"><?php echo $StatusPembulatan;    ?><?php echo number_format($Pembulatan,2);    ?></td>
                            </tr>      
                            <tr>
                                <td width="50%" style="font-size: 12px; color: #000;">Total Kembalian</td>
                                <td width="50%" align="right" style="font-size: 12px; color: #000;"><?php echo number_format($TotalKembalian,2);    ?></td>
                            </tr>  
                            <tr>
                                <td width="50%" style="font-size: 12px; color: #000;">Total Poin</td>
                                <td width="50%" align="right" style="font-size: 12px; color: #000;"><?php echo number_format($TotalPoin,2);    ?></td>
                            </tr>    
                            <tr>
                                <td width="50%" style="font-size: 12px; color: #000;">Total Voucher</td>
                                <td width="50%" align="right" style="font-size: 12px; color: #000;"><?php echo number_format($TotalVoucher,2);    ?></td>
                            </tr>                                                                       
                        </table>
                        <hr>
                        Ringkasan Penjualan:                                           
                        <table id="tableFooter" width="100%">
                            <tr>
                                <td width="50%" style="font-size: 12px; color: #000;">Gross Sales</td>
                                <td width="50%" align="right" style="font-size: 12px; color: #000;"><?php echo number_format($GrossSales,2);    ?></td>
                            </tr>                       
                            <tr>
                                <td width="50%" style="font-size: 12px; color: #000;">Total Diskon</td>
                                <td width="50%" align="right" style="font-size: 12px; color: #000;"><?php echo number_format($TotalDiskon,2);    ?></td>
                            </tr>                        
                            <tr>
                                <td width="50%" style="font-size: 12px; color: #000;">Net Sales</td>
                                <td width="50%" align="right" style="font-size: 12px; color: #000;"><?php echo number_format($NetSales,2);    ?></td>
                            </tr>                                
                            <tr>
                                <td width="50%" style="font-size: 12px; color: #000;">Total Cash</td>
                                <td width="50%" align="right" style="font-size: 12px; color: #000;"><?php echo number_format($TotalCash,2);    ?></td>
                            </tr>                         
                            <tr>
                                <td width="50%" style="font-size: 12px; color: #000;">Total Non Cash</td>
                                <td width="50%" align="right" style="font-size: 12px; color: #000;"><?php echo number_format($TotalNonCash,2);    ?></td>
                            </tr>                                                                    
                        </table>                        
                        <hr>
                        <img src="<?php   echo $FileBarcode;  ?>" width="30%">
                        <?php
                        if(isset($_GET['reprint'])){
                        ?>
                        <div class="TextMiring" style="color:#CCCCCC;font-size:30px;">COPY STRUK</div>
                        <?php
                        }
                        ?>
                    </div>
                    <div class="span2">&nbsp;</div>
                </div>            
            </div>
            <?php
        }
        ?>
    </div>
</div>

</body>
</html>
<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
function PrintDoc() {
    var toPrint = document.getElementById('PrintArea');
    var popupWin = window.open('', '_blank', 'width=800,height=600,location=no,left=50px');
    popupWin.document.open();
    popupWin.document.write('<html><title><?php echo $KodeRegister; ?></title><link rel="stylesheet" type="text/css" href="theme/css/print.css" media="print"/></head><body onload="window.print();window.close();">')
    popupWin.document.write(toPrint.innerHTML);
    popupWin.document.write('</body></html>');
    popupWin.document.close();
}
</script>

<?php
}
?>