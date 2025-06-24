<?php
error_reporting(0);
ini_set('display_errors', 0);

session_start();

include("lib/mysql_pdo.php");
include("lib/mysql_connect.php");	
include("lib/general_lib.php");
include("admin/library/fungsi.php");
include("admin/library/qrcode/qrlib.php");

if(!isset($_GET['nobon'])){
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

$stmt = $db->prepare("SELECT * FROM dbo_noseries WHERE kode_store = :kodestore");
$stmt->execute(array(':kodestore' => $kode_store));
$ns = $stmt->fetchAll();

$prefix_struk_no = $kode_store . "-" .  $kode_kasir . "-";
$lastno_struk_no = $ns[0]["nomor_akhir"];
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
            top: 25%;
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
        if(isset($_GET['nobon'])){
            $NoBon = $_GET['nobon'];

            /*GENERATE QRCODE LOGO*/
			$tempdir="admin/qrcode_transaksi/";
			$file_name = $NoBon.".png";
			//$record_value = $Timestampx . "#" . $labelKegiatanx . "#" . $txtTglAwalx . "#^" . $txtTglAkhirx;
			$record_value = $NoBon;
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
            $file_gambar = $protocol . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/admin/barcode.php?text=" . urlencode($NoBon) . "&print=true&size=50&orientation=horizontal&code_type=code128";
            $folderPath = __DIR__ . '/admin/qrcode_transaksi/barcodes/';
            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0777, true); // buat folder jika belum ada
            }
            $filename = $folderPath . $NoBon . '.png';
            $FileBarcodes = "admin/qrcode_transaksi/barcodes/" . $NoBon . ".png";
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
            $strQuery = $db->prepare("SELECT * FROM dbo_header WHERE kode_kasir = :kode_kasir and no_struk = :nofaktur and is_voided in ('0','2')");
            $strQuery->execute(array(':nofaktur' => $NoBon, ':kode_kasir' => $kode_kasir));
            $rec = $strQuery->fetchAll();
            $JumlahRecord = count($rec);

            if($JumlahRecord > 0){
                $PostingDate = $rec[0]["posting_date"];
                $KodeStore = $rec[0]["kode_store"];
                $TangalSales = $rec[0]["tanggal"];
                $JamSales = $rec[0]["jam"];
                $ShowTanggalSales = date("d-m-Y", strtotime($TangalSales)) . " " . date("H:i:s", strtotime($rec[0]["jam"]));
                $KodeKasir = $rec[0]["kode_kasir"];
                $NamaKasir = $rec[0]["nama_kasir"];
                $TotalBayar = $rec[0]["total_bayar"];
                $TotalStruk = $rec[0]["total_struk"];
                $Kembalian = $rec[0]["kembalian"];
                $Pembulatan = $rec[0]["var_pembulatan"];
                $PoinMember = $rec[0]["var_poin"];
                $Voucher = $rec[0]["var_voucher"];
                $JenisBayar = $rec[0]["jenis_bayar"];
                $NamaKartu = $rec[0]["nama_kartu"] ?? "TUNAI";
                $CustomerCode = $rec[0]["kode_customer"];
                $TotalQtySales = getTotalItemPerStruk($NoBon);

                $CheckMemberHNI = CheckMemberByPhone($CustomerCode);
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

                $strQueryPoin = $db->prepare("SELECT * FROM dbo_poin_member WHERE refer_id = :nofaktur");
                $strQueryPoin->execute(array(':nofaktur' => $NoBon));
                $recPoin = $strQueryPoin->fetchAll();

                $NilaiPoin = $recPoin[0]["nilai_poin"] ?? 0;   
                
                $NamaStore = getStoreName($kode_store);
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
                                <div class="span5" style="padding-top: 6px; text-align: right;">
                                    <span style="font-weight: 600; font-size: 115%; color: #fff; text-shadow: 1px 1px #253d2d;">No. Struk:&nbsp;</span>
                                    <span id="TxtOrderNo" style="font-weight: 600; font-size: 115%; color: #fff; text-shadow: 1px 1px #253d2d;"><?php echo $NoBon;    ?></span>
                                </div>

                                <div class="span7">&nbsp;</div>
                            </div>
                        </div>
                    </div>
                    <div class="span5">
                        <!-- Header Buttons -->
                        <div style="padding: 7px 35px 0px 10px; float: right;">
                            <table>
                                <tr>
                                    <td class="td-no-line-height"><span id="BtnBack" onclick="window.location.href ='kasir.php'" class="btn btn-block btn-default btn-icon glyphicons home"><i></i>BACK TO POS</span></td>
                                    <td class="td-no-line-height">&nbsp;</td>
                                    <td class="td-no-line-height"><span id="BtnPrint" onclick="PrintDoc();" class="btn btn-block btn-default btn-icon glyphicons print"><i></i>PRINT STRUK</span></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- End of Header -->
                <div class="row-fluid" >
                    <div id="PrintArea">
                        <div class="span2">&nbsp;</div>
                        <div class="span8" align="center"> 
                            <img src="admin/assets/images/logo_struk.png" alt="Logo InsanPOS" width="40%">
                            <div style="color:#000;font-size:12px;">
                                <?php echo $NamaStore;    ?>
                            </div>
                            <div style="color:#000;font-size:12px;">
                                <?php echo $HeaderStruk;    ?>
                            </div>
                            <hr>
                            <table width="100%" style="color:#000;font-size:12px;">
                                <tr>
                                    <td width="50%" style="color:#000;font-size:10px;">Nomor Struk :</td>
                                    <td width="50%" align="right" style="color:#000;font-size:10px;"><?php echo $NoBon;    ?></td>
                                </tr>                            
                                <tr>
                                    <td width="50%" style="color:#000;font-size:10px;">Tanggal :</td>
                                    <td width="50%" align="right" style="color:#000;font-size:10px;"><?php echo $ShowTanggalSales;    ?></td>
                                </tr>
                                <?php
                                if($CustomerCode != ""){
                                    ?>
                                    <tr>
                                        <td width="50%" style="color:#000;font-size:10px;">Customer : </td>
                                        <td width="50%" align="right" style="color:#000;font-size:10px;"><?php echo $varStatusMember;    ?></td>
                                    </tr> 
                                    <?php
                                }
                                ?>             
                            </table> 
                            <hr>
                            <div>
                                <table id="tableData" style="color:#000;font-size:12px;">
                                    <tr>
                                        <th>DESKRIPSI</th>
                                        <th>QTY</th>
                                        <th>TOTAL</th>
                                    </tr>
                                    <?php
                                    $TotalDiskon = 0;
                                    $strQueryDetail = $db->prepare("SELECT * FROM dbo_detail WHERE qty_voided = 0 and no_struk = :nofaktur");
                                    $strQueryDetail->execute(array(':nofaktur' => $NoBon));
                                    $recDetail = $strQueryDetail->fetchAll();
                                    foreach ($recDetail as $row) {
                                        if($row['var_diskon'] > 0){
                                            $TotalDiskon+=$row['var_diskon'];
                                            $varDiskon = "#<b>Diskon : (" . number_format($row['var_diskon'],2) . ")</b>";
                                            $persenDiskon = ($row['harga'] > 0) ? round(($row['var_diskon'] / $row['harga']) * 100, 2) : 0;
                                            $persenDiskon = $persenDiskon/$row['qty_sales'];
                                            $VariabelDiskon = " (" . $persenDiskon . "%)";
                                        }else{
                                            $varDiskon = "";
                                            $VariabelDiskon = "";
                                        }
                                    ?>
                                    <tr>
                                        <td style="color:#000;font-size:10px;" colspan="3"><?php   echo getNamaBarangByKodeBarang($row['kode_barang']);  ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="color:#000;font-size:10px;">
                                        Harga : <?php   echo number_format($row['harga'],0);  ?> <?php   echo $varDiskon;  ?> <?php   echo $VariabelDiskon;  ?>
                                        </td>
                                        <td style="color:#000;font-size:10px;"><?php   echo $row['qty_sales'];  ?> <?php   echo getUomByKodeBarang($row['kode_barang']);  ?></td>
                                        <td style="text-align:right;font-size:10px;"><?php   echo number_format($row['total_sales'],0);  ?></td>
                                    </tr>                                
                                    <?php
                                    }
                                    ?>                            
                                </table>
                            </div>
                            <hr>
                            <table width="100%" style="color:#000;font-size:12px;">
                                <tr>
                                    <td width="30%" align="right">Total Qty</td>
                                    <td width="70%" align="right" style="text-align:right;font-size:10px;"><?php echo number_format($TotalQtySales,0);    ?></td>
                                </tr>                            
                                <tr>
                                    <td width="30%" align="right">Total Belanja</td>
                                    <td width="70%" align="right" style="text-align:right;font-size:10px;"><?php echo number_format($TotalStruk,0);    ?></td>
                                </tr>
                                <?php
                                if($Pembulatan != 0){
                                    ?>
                                    <tr>
                                        <td width="30%" align="right">Pembulatan</td>
                                        <td width="70%" align="right" style="text-align:right;font-size:10px;"><?php echo number_format($Pembulatan,0);    ?></td>
                                    </tr>
                                    <?php
                                } 
                                ?>    
                                <tr>
                                    <td width="30%" align="right">Total Bayar</td>
                                    <td width="70%" align="right" style="text-align:right;font-size:10px;"><?php echo number_format($TotalBayar,0);    ?></td>
                                </tr>
                                <?php
                                if($Kembalian > 0){
                                    ?>
                                    <tr>
                                        <td width="30%" align="right">Kembalian</td>
                                        <td width="70%" align="right" style="text-align:right;font-size:10px;"><?php echo number_format($Kembalian,0);    ?></td>
                                    </tr>
                                    <?php
                                } 
                                ?>
                                <?php
                                if($PoinMember > 0){
                                    ?>
                                    <tr>
                                        <td width="30%" align="right">Poin Dipakai</td>
                                        <td width="70%" align="right" style="text-align:right;font-size:10px;"><?php echo number_format($PoinMember,0);    ?></td>
                                    </tr> 
                                    <?php
                                } 
                                ?>
                                <?php
                                if($Voucher > 0){
                                    ?>
                                    <tr>
                                        <td width="30%" align="right">Nilai Voucher</td>
                                        <td width="70%" align="right" style="text-align:right;font-size:10px;"><?php echo number_format($Voucher,0);    ?></td>
                                    </tr> 
                                    <?php
                                } 
                                ?>
                                <tr>
                                    <td width="30%" align="right">Pembayaran</td>
                                    <td width="70%" align="right" style="text-align:right;font-size:10px;"><?php echo $JenisBayar;    ?> / <?php echo $NamaKartu;    ?></td>
                                </tr>                                                
                            </table>    

                            <hr><img src="<?php   echo $FileBarcode;  ?>" width="30%">
                            <?php
                            if($TotalDiskon >= 1){
                                ?>
                                <div style="color:#000;font-size:10px;font-weight:800;">
                                    Anda Mendapatkan Diskon Sebesar <?php echo number_format($TotalDiskon,0);    ?>
                                </div>
                                <?php
                            }                         
                            if($NilaiPoin >= 1){
                                ?>
                                <div style="color:#000;font-size:10px;font-weight:800;">
                                    Anda Mendapatkan Poin <?php echo number_format($NilaiPoin,0);    ?>
                                </div>
                                <?php
                            } 
                            ?>
                            <div style="color:#000;font-size:10px;">
                                <?php echo $FooterStruk;    ?>
                            </div>
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
            }else{
                //echo "<script>window.location.href='kasir.php';</script>";
                exit();
            }
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
    popupWin.document.write('<html><title><?php echo $NoBon; ?></title><link rel="stylesheet" type="text/css" href="theme/css/print.css" media="print"/></head><body onload="window.print();window.close();">')
    popupWin.document.write(toPrint.innerHTML);
    popupWin.document.write('</body></html>');
    popupWin.document.close();
}
function showAlert() {
    Swal.fire({
        position: 'center',
        icon: 'success',
        html: 'SUKSES MENCETAK STRUK',
        showConfirmButton: false,
        timer: 2000
    });
}
</script>

<?php
}
?>