<?php
ini_set('display_errors', 0);
error_reporting(0); 

session_start();

include("lib/mysql_pdo.php");
include("lib/mysql_connect.php");	
include("lib/general_lib.php");

$user_id = $_SESSION['SESS_user_id']; 
$user_name = $_SESSION['SESS_user_name'];
$kode_kasir = $_SESSION['SESS_kode_kasir'];

if(!isset($_SESSION['SESS_user_id'])){
    header("Location: index.php");
    exit();
}else{
//require_once('securepage.php');
//chkpage(600); // set idle max 60 seconds

$encryptedKodeToko = getConfig('KodeToko');
$kode_store = $encryptedKodeToko ? decryptData($encryptedKodeToko, $cryptkey) : "ERROR! KODE TOKO TIDAK VALID!";

$_SESSION['SESS_kode_store'] = $kode_store;

$stmt = $db->prepare("SELECT * FROM dbo_noseries WHERE kode_store = :kodestore AND kode_kasir = :kodekasir");
$stmt->execute(array(':kodestore' => $kode_store, ':kodekasir' => $kode_kasir));
$ns = $stmt->fetchAll();

$prefix_struk_no = $kode_store . "-" .  $kode_kasir . "-";
$lastno_struk_no = $ns[0]["nomor_akhir"] ;
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

	<!-- TAILWIND CSS
	<link rel="stylesheet" href="admin/assets/lineone/css/app.css" />
    <link rel="stylesheet" href="admin/assets/css/custom.css" />
    -- Javascript Assets --
    <script src="admin/assets/lineone/js/app.js" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
	-->

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
		.qtybtnblank {
			background-color:rgb(151, 151, 151);
		}
	</style>

	<!-- Scroll Touchscreen -->
	<style>
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
		#TxtSubTotal, #TxtPPN, #TxtGrandTotal, #TxtGrandTotalPoin,#TxtVoucher {
			font-weight: bold;
		}
	</style>

	<!-- Deklarasi variable PHP -->
	<script>
		var prefix_struk_no = "<?php echo $prefix_struk_no; ?>";
		var lastno_struk_no = "<?php echo $lastno_struk_no; ?>";
		var user_id = "<?php echo $user_id; ?>";
		var user_name = "<?php echo $user_name; ?>";
		var kode_store = "<?php echo $kode_store; ?>";
		var kode_kasir = "<?php echo $kode_kasir; ?>";

		$("TxtOrderNo").focus();
	</script>

	<!-- Fungsi2 pendukung halaman kasir -->
	<script src="lib_js/kasir.js"></script>

</head>
<body>

<!-- Start Content -->
<div class="container-fluid">

	<div id="wrapper">
		<!-- Header -->
        <div class="row-fluid" style="background-color: #4e8f64;">
            <div class="span7">
				<div>
					<!-- App Brand -->
					<div class="span3" style="border-right: 1px solid #9bb6cf; height: 50px;">
                		<p style="font-weight: 900; color: #fff; padding: 9px 0px 0px 15px; text-shadow: 1px 1px #253d2d;">InsanPOS <span style="font-size: 60%;">v.1.00</span></p>
					</div>
					<!-- Input Scan -->
					<div class="span9" style="padding-top: 8px;">
						<div class="span5" style="padding-top: 6px; text-align: right;">
							<span style="font-weight: 600; font-size: 115%; color: #fff; text-shadow: 1px 1px #253d2d;">No. Struk:&nbsp;</span>
							<span id="TxtOrderNo" style="font-weight: 600; font-size: 115%; color: #fff; text-shadow: 1px 1px #253d2d;"><?php echo generateNoStruk($prefix_struk_no, $lastno_struk_no); ?></span>
						</div>

						<div class="span7"><input id="EditBoxScanItem" type="text" placeholder="Scan item" class="span12" /></div>
					</div>
				</div>
            </div>
            <div class="span5">
				<!-- Header Buttons -->
				<div style="padding: 7px 35px 0px 10px; float: right;">
					<table>
						<tr>
							<td class="td-no-line-height"><span id="BtnSearch" class="btn btn-block btn-default btn-icon glyphicons search"><i></i>SEARCH</span></td>
							<td class="td-no-line-height">&nbsp;</td>
							<!--<td class="td-no-line-height"><span id="BtnSettings" class="btn btn-block btn-default btn-icon glyphicons settings"><i></i>SETTINGS</span></td>
							<td class="td-no-line-height">&nbsp;</td>-->
							<td class="td-no-line-height"><span id="BtnAccount" class="btn btn-block btn-default btn-icon glyphicons vcard"><i></i>ACCOUNT</span></td>
							<td class="td-no-line-height">&nbsp;</td>
							<td class="td-no-line-height"><span id="BtnMember" class="btn btn-block btn-default btn-icon glyphicons parents"><i></i>MEMBER</span></td>
							<td class="td-no-line-height">&nbsp;</td>
							<td class="td-no-line-height"><span id="BtnLock" class="btn btn-block btn-default btn-icon glyphicons lock"><i></i>LOCK</span></td>
							<td class="td-no-line-height">&nbsp;</td>
							<td class="td-no-line-height"><span id="BtnLogout" class="btn btn-block btn-default btn-icon glyphicons power"><i></i>LOGOUT</span></td>
						</tr>
					</table>
				</div>
            </div>
        </div>
		<!-- End of Header -->

        <div id="content" style="background: #fff;"> 

			<div class="row-fluid">
				<!-- Scanned Item List -->
				<div class="span8" style="padding: 5px 5px 5px 5px;">
					<!-- MAIN TABLE -->
					<div id="scrollable">
						<div id="listscanneditems">
							<table id="ItemTable" class="table table-bordered table-striped table-condensed">
								<thead>
									<tr>
										<th style="width: 15px; text-align: center;">#</th>
										<th style="width: 70px; ">Kode</th>
										<th>Deskripsi Barang</th>
										<th style="width: 60px; text-align: right;">Harga</th>
										<th style="width: 40px; text-align: center;">UoM</th>
										<th style="width: 130px; text-align: center;">Qty</th>
										<th style="width: 32px; text-align: right;">Disc.</th>
										<th style="width: 70px; text-align: right;">Total</th>
									</tr>
								</thead>
								<tbody>
									<?php
									for($i = 0; $i < 12; $i++)
										echo '<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>';
									?>
								</tbody>
							</table>
						</div>
					</div>
					<!-- SUB TOTAL -->
					<table id="ItemTableSubTotal" class="table table-bordered table-condensed">
						<tr>
							<td class="keypad-divider" style="text-align: right;">Total Items</td>
							<td class="keypad-divider" id="TxtTotalItems" style="width: 130px; text-align: center;"><b></b></td>
							<td class="keypad-divider" style="width: 32px; text-align: right;">SUB</td>
							<td class="keypad-divider" id="TxtSubTotal" style="width: 70px; text-align: right;"><b></b></td>
						</tr>
					</table>
					<!-- PPN -->
					<table id="ItemTablePPN" class="table table-bordered table-condensed" style="display:none;">
						<tr>
							<td class="keypad-divider" style="text-align: right;">PPN</td>
							<td class="keypad-divider" id="TxtPPN" style="width: 70px; text-align: right;"><b></b></td>
						</tr>
					</table>
					<!-- MEMBER POINT -->
					<table id="ItemTablePointMember" class="table table-bordered table-condensed hidden">
						<tr>
							<td class="keypad-divider" style="display: flex; justify-content: flex-end; align-items: center;">
								<label style="display: flex; align-items: center; gap: 8px;">
									<input type="hidden" id="txtMemberID" value="0" required readonly>
									<input type="hidden" id="TxtGrandTotalPoins" value="0" required readonly>
									<input type="checkbox" id="chkboxUsePoint" class="form-control" style="position: relative; top: -2px;" checked>
									POIN MEMBER
								</label>
							</td>
							<td class="keypad-divider" id="TxtGrandTotalPoin" style="width: 70px; text-align: right; color:#FF0000"><b></b></td>
						</tr>
					</table>
					<!-- VOUCHER -->
					<table id="ItemTableVoucher" class="table table-bordered table-condensed hidden">
						<tr>
							<td class="keypad-divider" style="display: flex; justify-content: flex-end; align-items: center;">
								<label style="display: flex; align-items: center; gap: 8px;">
									<input type="hidden" id="txtKodeVoucher" value="0" required readonly>
									<input type="hidden" id="txtNilaiVoucher" value="0" required readonly>
									<input type="checkbox" class="form-control" style="position: relative; top: -2px;" checked disabled>
									VOUCHER
								</label>
							</td>
							<td class="keypad-divider" id="TxtVoucher" style="width: 70px; text-align: right; color:#FF0000"><b></b></td>
						</tr>
					</table>					
					<!-- PAYMENT -->	
					<!-- GRAND TOTAL -->
					<table id="ItemTableGrandTotal" class="table table-bordered table-condensed">
						<tr>
							<td class="keypad-divider" style="text-align: right;">TOTAL</td>
							<td class="keypad-divider" id="TxtGrandTotal" style="width: 70px; text-align: right;"><b></b></td>
						</tr>
					</table>				 
					<div>
						<br/>
						<table style="width: 100%;">
							<tr>
								<td class="center" style="width: 25%;" colspan="2">
									<span id="BtnVoid" class="btn btn-large btn-block btn-danger"><span style="font-size: 150%; font-weight: 600;">VOID</span></span>
								</td>
								<td>&nbsp;</td>
								<td class="center" style="width: 25%;">
									<span id="BtnReprint" class="btn btn-large btn-block btn-inverse"><span style="font-size: 150%; font-weight: 600;">RE-PRINT</span></span>
								</td>
								<td>&nbsp;</td>
								<td class="center" style="width: 25%;">
									<span id="BtnDebitCard" class="btn btn-large btn-block btn-primary"><span style="font-size: 150%; font-weight: 600;">DEBIT</span></span>
								</td>
								<td>&nbsp;</td>
								<td class="center" style="width: 25%;">
									<span id="BtnCreditCard" class="btn btn-large btn-block btn-primary"><span style="font-size: 150%; font-weight: 600;">KREDIT</span></span>
								</td>
							</tr>
							<tr><td style="padding: 3px 0px 3px 0px;"></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
							<tr>
								<td class="center">
									<span id="BtnRecall" class="btn btn-large btn-block btn-warning"><span style="font-size: 150%; font-weight: 600;">&#10226;</span></span>
								</td>
								<td class="center">
									<span id="BtnHold" class="btn btn-large btn-block btn-danger"><span style="font-size: 150%; font-weight: 600;">HOLD</span></span>
								</td>
								<td>&nbsp;</td>
								<td class="center">
									<span id="BtnVoucher" class="btn btn-large btn-block btn-warning"><span style="font-size: 150%; font-weight: 600;">VOUCHER</span></span>
								</td>
								<td>&nbsp;</td>
								<td class="center">
									<span id="BtnEwallet" class="btn btn-large btn-block btn-success"><span style="font-size: 150%; font-weight: 600;">EWALLET</span></span>
								</td>
								<td>&nbsp;</td>
								<td class="center">
									<span id="BtnCash" class="btn btn-large btn-block btn-success btn-cash"><span style="font-size: 150%; font-weight: 600;">CASH</span></span>
								</td>
							</tr>
						</table>
					</div> 
					<!-- END LEFT PANEL -->
					<footer style="font-size: 80%; color: grey; margin-top: 10px;">Copyright &copy; 2025 - INSANTARA</footer>
				</div>

				<!-- Tendered Panel -->
				<div id="TenderedPanel" class="span4" style="padding: 5px 10px 0px 0px; margin-left: 10px; pointer-events: none; opacity: 0.5;">
					<div class="widget">
						<div class="widget-head" style="text-align: center;">
							<p id="pTendered" style="font-size: 150%; font-weight: 600; color:black; padding-top: 5px;">Tendered Rp.</p>
						</div>
						<div class="widget-body">
							<div class="row-fluid">
								<input id="EditBoxTendered" class="span12" type="text" placeholder="" style="font-size: 130%; cursor: pointer;" readonly />
								<input type="hidden" id="hiddenEntryTendered" value="" />
							</div>
							<table style="width: 100%;">
								<tr>
									<td class="center">
										<span id="BtnNominalOption1" class="btn btn-large btn-block btn-default"></span>
									</td>
									<td>&nbsp;&nbsp;</td>
									<td class="center">
										<span id="BtnNominalOption2" class="btn btn-large btn-block btn-default"></span>
									</td>
									<td>&nbsp;&nbsp;</td>
									<td class="center">
										<span id="BtnNominalOption3" class="btn btn-large btn-block btn-default"></span>
									</td>
								</tr>
							</table>
							<hr>
							<div style="text-align: center;">
								<span id="TxtChanges" style="font-size: 170%; font-weight: 700; color:#47759e;">CHANGES: Rp. 0,-</span>
							</div>
						</div>
					</div>
					<!-- Keypad -->
					<div class="widget">
						<div class="widget-body">
							<table style="width: 100%;">
								<tr>
									<td class="center keypad">
										<span id="BtnKeypad1" class="btn btn-large btn-block btn-default"><span style="font-size: 150%; font-weight: 600;">1</span></span>
									</td>
									<td class="keypad">&nbsp;</td> 
									<td class="center keypad">
										<span id="BtnKeypad2" class="btn btn-large btn-block btn-default"><span style="font-size: 150%; font-weight: 600;">2</span></span>
									</td>
									<td class="keypad">&nbsp;</td>
									<td class="center keypad">
										<span id="BtnKeypad3" class="btn btn-large btn-block btn-default"><span style="font-size: 150%; font-weight: 600;">3</span></span>
									</td>
								</tr>
								<tr><td class="keypad-divider">&nbsp;</td><td class="keypad-divider">&nbsp;</td><td class="keypad-divider">&nbsp;</td><td class="keypad-divider">&nbsp;</td><td class="keypad-divider">&nbsp;</td></tr>
								<tr>
									<td class="center keypad">
										<span id="BtnKeypad4" class="btn btn-large btn-block btn-default"><span style="font-size: 150%; font-weight: 600;">4</span></span>
									</td>
									<td class="keypad">&nbsp;</td> 
									<td class="center keypad">
										<span id="BtnKeypad5" class="btn btn-large btn-block btn-default"><span style="font-size: 150%; font-weight: 600;">5</span></span>
									</td>
									<td class="keypad">&nbsp;</td>
									<td class="center keypad">
										<span id="BtnKeypad6" class="btn btn-large btn-block btn-default"><span style="font-size: 150%; font-weight: 600;">6</span></span>
									</td>
								</tr>
								<tr><td class="keypad-divider">&nbsp;</td><td class="keypad-divider">&nbsp;</td><td class="keypad-divider">&nbsp;</td><td class="keypad-divider">&nbsp;</td><td class="keypad-divider">&nbsp;</td></tr>
								<tr>
									<td class="center keypad">
										<span id="BtnKeypad7" class="btn btn-large btn-block btn-default"><span style="font-size: 150%; font-weight: 600;">7</span></span>
									</td>
									<td class="keypad">&nbsp;</td> 
									<td class="center keypad">
										<span id="BtnKeypad8" class="btn btn-large btn-block btn-default"><span style="font-size: 150%; font-weight: 600;">8</span></span>
									</td>
									<td class="keypad">&nbsp;</td>
									<td class="center keypad">
										<span id="BtnKeypad9" class="btn btn-large btn-block btn-default"><span style="font-size: 150%; font-weight: 600;">9</span></span>
									</td>
								</tr>
								<tr><td class="keypad-divider">&nbsp;</td><td class="keypad-divider">&nbsp;</td><td class="keypad-divider">&nbsp;</td><td class="keypad-divider">&nbsp;</td><td class="keypad-divider">&nbsp;</td></tr>
								<tr>
									<td class="center keypad">
										<span id="BtnKeypadBS" class="btn btn-large btn-block btn-default"><span style="font-size: 150%; font-weight: 600;">&laquo;</span></span>
									</td>
									<td class="keypad">&nbsp;</td> 
									<td class="center keypad">
										<span id="BtnKeypad0" class="btn btn-large btn-block btn-default"><span style="font-size: 150%; font-weight: 600;">0</span></span>
									</td>
									<td class="keypad">&nbsp;</td>
									<td class="center keypad">
										<span id="BtnKeypadCL" class="btn btn-large btn-block btn-default"><span style="font-size: 150%; font-weight: 600;">&times;</span></span>
									</td>
								</tr>
							</table>
						</div>
					</div>
					<!-- END RIGHT PANEL -->
				</div>
			</div>
		</div> <!-- content -->
		
	</div> <!-- wrapper -->

</div> <!-- container-fluid -->

<!-- Dialog Search Item -->	
<div id="DialogSearch" title="Search Item"></div>
<script src="dialogbox/dlg_search_item.js"></script>

<!-- Dialog Member -->	
<div id="DialogMember" title="MEMBER"></div>
<script src="dialogbox/dlg_member.js"></script>

<!-- Dialog Account -->	
<div id="DialogAccount" title="Account Kasir"></div>
<script src="dialogbox/dlg_account.js"></script>

<!-- Dialog Entry Tender -->	
<div id="DialogEntryTender" title="Tendered Rp."></div>
<script src="dialogbox/dlg_entry_tender.js"></script>

<!-- Dialog Payment Credit Card -->	
<div id="DialogCreditCard" title="Credit Card Payment"></div>
<script src="dialogbox/dlg_payment_creditcard.js"></script>

<!-- Dialog Payment Debit Card -->	
<div id="DialogDebitCard" title="Debit Card Payment"></div>
<script src="dialogbox/dlg_payment_debitcard.js"></script>

<!-- Dialog Payment EWallet -->	
<div id="DialogEWallet" title="E-Wallet Payment"></div>
<script src="dialogbox/dlg_payment_ewallet.js"></script>

<!-- Dialog Payment Loyalti -->	
<div id="DialogVoucher" title="Voucher"></div>
<script src="dialogbox/dlg_validasi_voucher.js"></script>

<!-- Dialog Confirm Hold -->	
<div id="DialogConfirmHold" title="Confirmation"></div>
<script src="dialogbox/dlg_confirm_hold.js"></script>

<!-- Dialog Confirm Recall -->	
<div id="DialogConfirmRecall" title="Confirmation"></div>
<script src="dialogbox/dlg_confirm_recall.js"></script>

<!-- Dialog Supervisor Approval -->	
<div id="DialogSpvApproval" title="Supervisor Approval"></div>
<script src="dialogbox/dlg_spv_approval.js"></script>

<!-- Dialog Void -->	
<div id="DialogVoid" title="VOID TRANSAKSI"></div>
<script src="dialogbox/dlg_void.js"></script>

<!-- Dialog Reprint Cashier -->	
<div id="DialogReprint" title="Reprint"></div>
<script src="dialogbox/dlg_reprint.js"></script>

<!-- Dialog Lock Cashier -->	
<div id="DialogLockCashier" title="LOCK"></div>
<script src="dialogbox/dlg_lock_cashier.js"></script>

<!-- Dialog Opening Cashier -->	
<div id="DialogOpeningCashier" title="OPENING"></div>
<script src="dialogbox/dlg_opening_cashier.js"></script>

<!-- Dialog Closing Cashier -->	
<div id="DialogClosingCashier" title="CLOSING"></div>
<script src="dialogbox/dlg_closing_cashier.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>
<?php
}
?>