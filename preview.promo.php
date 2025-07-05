<?php
error_reporting(0);
ini_set('display_errors', 0);

include("lib/mysql_pdo.php");
include("lib/mysql_connect.php");	
include("lib/general_lib.php");
include("admin/library/fungsi.php");
include("admin/library/parameter.php");
include("admin/library/connection.php");

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
        if(isset($_GET['msgcode'])){
            echo "<input type='hidden' id='txtMsg' name='txtMsg' value='" . $_GET['msgcode'] . "' class='text-primary' />";
        }else{
            echo "<input type='hidden' id='txtMsg' name='txtMsg' value='' class='text-primary' />";
        }
        $NamaStore = getStoreName($KodeStoreOffline);
        $HeaderStruk = getHeaderStruk($KodeStoreOffline);
        $FooterStruk = getFooterStruk($KodeStoreOffline);
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
                    <div class="span9" style="height: 50px;">
                        <p style="font-weight: 900; font-size: 150%; color: #fff; padding: 9px 0px 0px 15px; text-shadow: 1px 1px #253d2d;">Daftar Promo Berlaku</p>
                    </div>
                </div>
            </div>
            <div class="span5">
                <!-- Header Buttons -->
                <div style="padding: 7px 35px 0px 10px; float: right;">
                    <table>
                        <tr>
                            
                            <td class="td-no-line-height"><span id="BtnBack" onclick="toggleFullScreen();" class="btn btn-block btn-default btn-icon glyphicons home"><i></i>FULL SCREEN</span></td>
                            <td class="td-no-line-height">&nbsp;</td>
                            <td class="td-no-line-height"><span id="BtnBack" onclick="closeWindow();" class="btn btn-block btn-default btn-icon glyphicons home"><i></i>CLOSE</span></td>
                            <td class="td-no-line-height">&nbsp;</td>
                            <td class="td-no-line-height"><span id="BtnPrint" onclick="PrintDoc();" class="btn btn-block btn-default btn-icon glyphicons print"><i></i>PRINT PROMO</span></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="row-fluid">
            <div id="PrintArea">
                <div class="span2">&nbsp;</div>
                <div class="span8" align="center"> 
                    <img src="admin/assets/images/logo_struk.png" alt="Logo InsanPOS" width="40%">
                    <div style="color:#000;font-size:12px;"><?php echo $NamaStore;    ?></div>
                    <div style="color:#000;font-size:12px;">
                        <?php echo $HeaderStruk;    ?>
                    </div>
                    <hr>
                    <div style="color:#000;font-size:12px;">DAFTAR PROMO BERLAKU <?php  echo $currdatedb;   ?></div>
                    <table id="tableData" style="color:#000;font-size:12px;">
                        <tr>
                            <th>LABEL PROMO</th>
                            <th>JENIS</th>
                            <th>FREE</th>
                        </tr>
                        <?php
                        $strQueryHeader = $db->prepare("SELECT * FROM dbo_promo WHERE fl_active = 1");
                        $strQueryHeader->execute();
                        $recHeader = $strQueryHeader->fetchAll();
                        foreach ($recHeader as $row) {
                        ?>    
                        <tr>
                            <td style="color:#000;font-size:12px;"><?php   echo $row['promo_name'];    ?></td>
                            <td style="color:#000;font-size:12px;"><?php   echo $row['kriteria_promo'];    ?></td>
                            <td style="color:#000;font-size:12px;"><?php   echo $row['qty_free_item'];    ?></td>
                        </tr>       
                            <?php
                            $strQueryDetail = $db->prepare("SELECT * FROM dbo_promo_detail WHERE kode_promo = :kode_promo");
                            $strQueryDetail->execute(array(':kode_promo' => $row['kode_promo']));
                            $recDetail = $strQueryDetail->fetchAll();
                            foreach ($recDetail as $rows) {
                            ?>    
                            <tr>
                                <td colspan="3" style="color:#000;font-size:10px;"><?php   echo $rows['sku_barang'];    ?> / <?php   echo getNamaBarangBySkuBarang($rows['sku_barang']);    ?></td>
                            </tr>                             
                            <tr>
                                <td style="color:#000;font-size:10px;"><?php   echo $rows['sku_barang'];    ?></td>
                                <td style="color:#000;font-size:10px;"><?php   echo $rows['barcode'];    ?></td>
                                <td style="color:#000;font-size:10px;"><?php   echo $rows['uom'];    ?></td>
                            </tr>                        
                            <?php
                            }
                            ?>                                          
                        <?php
                        }
                        ?> 
                        </table>               
                    <div style="color:#000;font-size:10px;">
                        <?php echo $FooterStruk;    ?>
                    </div>
                </div>
                <div class="span2">&nbsp;</div>
            </div> 
        </div>
        <!-- End of Header -->
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
    popupWin.document.write('<html><title>Daftar Promo</title><link rel="stylesheet" type="text/css" href="theme/css/print.css" media="print"/></head><body onload="window.print();window.close();">')
    popupWin.document.write(toPrint.innerHTML);
    popupWin.document.write('</body></html>');
    popupWin.document.close();
}
function closeWindow() {
    window.open('', '_self', '');
    window.close();
}
function toggleFullScreen() {
    if (!document.fullscreenElement) {
        document.documentElement.requestFullscreen();
    } else {
        if (document.exitFullscreen) {
            document.exitFullscreen();
        }
    }
}
</script>