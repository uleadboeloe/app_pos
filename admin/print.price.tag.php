<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include_once "library/connection.php";
include_once "library/parameter.php";
include_once "library/fungsi.php";
include_once "../lib_dbo/user_functions.php";
$hash16 = CreateUniqueHash16();

$rcode = isset($_GET['rcode']) ? $_GET['rcode'] : "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<!-- Meta tags  -->
<meta charset="UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"/>

<title><?php    echo $TitleApps;    ?></title>
<link rel="icon" type="image/png" href="images/favicon.png" />
<!-- CSS Assets -->
<link rel="stylesheet" href="assets/lineone/css/app.css" />
<link rel="stylesheet" href="assets/css/custom.css" />
<!-- Javascript Assets -->
<script src="assets/lineone/js/app.js" defer></script>
<script src="https://cdn.tailwindcss.com"></script>

<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/colreorder/1.7.0/css/colReorder.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/rowreorder/1.4.1/css/rowReorder.dataTables.min.css" rel="stylesheet">


<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/colreorder/1.7.0/js/dataTables.colReorder.min.js"></script>
<script src="https://cdn.datatables.net/rowreorder/1.4.1/js/dataTables.rowReorder.min.js"></script>

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
        <div class="col-span-12 p-2 lg:col-span-12">
            <div class="flex items-center justify-between py-3 px-4">
                <h2 class="font-bold text-xl uppercase tracking-wide text-slate-700 dark:text-navy-100">Print Price Tag</h2>
            </div>

            <form name="formProses" name="frmMasterProduk" id="frmMasterProduk" method="post" action="print.price.tag.php?search=true" enctype="multipart/form-data">
                <div class="grid grid-cols-2 my-2 gap-4 sm:gap-5 lg:gap-6">
                    <div class="col-span-12 sm:col-span-12">
                        <div class="card p-4 sm:p-5">
                            <div class="space-y-4">
                                <label class="block">
                                    <span class="text-purple-500 font-bold">Nama Produk <div class="badge rounded-full bg-primary/10 text-primary dark:bg-accent-light/15 dark:text-accent-light">Wajib</div></span>
                                    <span class="relative mt-1.5 flex">						
                                        <input placeholder="Masukan Nama Produk" type="text" id="txtNamaProduk" name="txtNamaProduk"
                                        class="form-input peer h-12 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"/>
                                        <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                                            <i class="fa-regular fa-building text-base"></i>
                                        </span>
                                    </span>
                                </label>

                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-5">
                                    <label class="block">
                                        <span class="text-purple-500 font-bold">Departemen Code <div class="badge rounded-full bg-warning/10 text-warning dark:bg-accent-light/15 dark:text-accent-light">Opsional</div></span>
                                        <span class="relative mt-1.5 flex">
                                            <select id="txtDepartemen" name="txtDepartemen"
                                            class="form-select h-12 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs+ hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">
                                                <option value="">Departemen Code</option>
                                                <?php
                                                $strSQL="SELECT kode_departemen,nama_departemen FROM `dbo_departemen` where fl_active = 1";
                                                $CallstrSQL=mysqli_query($koneksidb, $strSQL);
                                                while($rec=mysqli_fetch_array($CallstrSQL)){
                                                ?>
                                                <option value="<?php    echo $rec['kode_departemen']; ?>"><?php    echo $rec['nama_departemen']; ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </span>
                                    </label>   
                                    <div id="DivKategori">   
                                        <label class="block">
                                            <span class="text-purple-500 font-bold">Kategori Produk<div class="badge rounded-full bg-warning/10 text-warning dark:bg-accent-light/15 dark:text-accent-light">Opsional</div></span>
                                            <span class="relative mt-1.5 flex">
                                                <select id="txtKategoriProduk" name="txtKategoriProduk" 
                                                class="form-select h-12 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs+ hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">
                                                    <option value="">Pilih Kategori Produk</option>                                             
                                                </select>
                                            </span>
                                        </label>     
                                    </div>
                                    <div id="DivSubKategori">
                                        <label class="block">
                                            <span class="text-purple-500 font-bold">Sub Kategori Produk<div class="badge rounded-full bg-warning/10 text-warning dark:bg-accent-light/15 dark:text-accent-light">Opsional</div></span>
                                            <span class="relative mt-1.5 flex">
                                                <select id="txtSubKategoriProduk" name="txtSubKategoriProduk"
                                                class="form-select h-12 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs+ hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">
                                                    <option value="">Pilih Sub Kategori Produk</option>                                                
                                                </select>
                                            </span>
                                        </label>                 
                                    </div>        
                                    <label class="block">
                                        <span class="text-purple-500 font-bold">Jenis Timbang <div class="badge rounded-full bg-warning/10 text-warning dark:bg-accent-light/15 dark:text-accent-light">Opsional</div></span>
                                        <span class="relative mt-1.5 flex">
                                            <select id="txtTimbang" name="txtTimbang"
                                            class="form-select h-12 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs+ hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">
                                                <option value="">Jenis Barang</option>
                                                <option value="1">Barang Timbang</option>
                                                <option value="0">Barang Non Timbang</option>
                                            </select>
                                        </span>
                                    </label>        
                                    <label class="block">
                                        <span class="text-purple-500 font-bold">Barcode <div class="badge rounded-full bg-warning/10 text-warning dark:bg-accent-light/15 dark:text-accent-light">Opsional</div></span>
                                        <span class="relative mt-1.5 flex">
                                            <input placeholder="Masukan Kode Barcode" type="text" id="txtBarcode" name="txtBarcode" value="<?php    echo $rcode;    ?>"
                                            class="form-input peer h-12 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"/>
                                            <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                                                <i class="fa-regular fa-building text-base"></i>
                                            </span>
                                        </span>
                                    </label>                                                                                                                         				
                                </div>    
                                
                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    <label class="block">
                                        <span class="relative mt-1.5 flex">						
                                            <input type="submit" name="btnSubmit" id="btnSubmit" value="Tampilkan Produk"
                                            class="btn space-x-2 bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
                                        </span>
                                    </label>
                                </div>   
                            </div>
                        </div>
                    </div>
                </div>  
            </form>
        </div>

        <div class="col-span-12 p-2 lg:col-span-12">
            <div class="flex items-center justify-between py-2 px-4">
                <h2 class="font-bold text-xl uppercase tracking-wide text-slate-700 dark:text-navy-100">List Produk</h2>
                <span id="BtnPrint" onclick="PrintDoc();" class="btn space-x-2 mr-1 bg-success font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90"><i></i>PRINT PRICE TAG</span>
            </div>
            <div class="card p-5 mt-3">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-1 sm:gap-5 lg:gap-6" id="PrintArea">
                <?php
                if(isset($_GET['search'])){
                    $txtNamaProdukx = (trim($_POST['txtNamaProduk']));
                    $txtDepartemenx = (trim($_POST['txtDepartemen']));
                    $txtKategoriProdukx = (trim($_POST['txtKategoriProduk']));
                    $txtSubKategoriProdukx = (trim($_POST['txtSubKategoriProduk']));
                    $txtTimbangx = (trim($_POST['txtTimbang']));
                    $txtBarcodex = (trim($_POST['txtBarcode']));
                    ?>
                    <input type="hidden" id="txtProdukName" name="txtProdukName" value="<?php echo $txtNamaProdukx; ?>">
                    <input type="hidden" id="txtDeptCode" name="txtDeptCode" value="<?php echo $txtDepartemenx; ?>">
                    <input type="hidden" id="txtKategoriCode" name="txtKategoriCode" value="<?php echo $txtKategoriProdukx; ?>">
                    <input type="hidden" id="txtSubKategoriCode" name="txtSubKategoriCode" value="<?php echo $txtSubKategoriProdukx; ?>">
                    <input type="hidden" id="txtJenisTimbang" name="txtJenisTimbang" value="<?php echo $txtTimbangx; ?>">
                    <input type="hidden" id="txtKodeBarcode" name="txtKodeBarcode" value="<?php echo $txtBarcodex; ?>">
                    <?php
                    /*==========================*/
                    $StrViewQuery="SELECT * from dbo_barang where fl_active = 1";
                    if($txtNamaProdukx != "") {
                        $StrViewQuery = $StrViewQuery . " and (sku_barang like '%" . $txtNamaProdukx . "%' or kode_barang like '%" . $txtNamaProdukx . "%' or nama_barang like '%" . $txtNamaProdukx . "%')";
                    }
                    if($txtDepartemenx != "") {
                        $StrViewQuery = $StrViewQuery . " and divisi = '" . $txtDepartemenx . "'";
                    }
                    if($txtKategoriProdukx != "") {
                        $StrViewQuery = $StrViewQuery . " and dept = '" . $txtKategoriProdukx . "'";
                    }
                    if($txtSubKategoriProdukx != "") {
                        $StrViewQuery = $StrViewQuery . " and sub_dept = '" . $txtSubKategoriProdukx . "'";
                    }
                    if($txtTimbangx != "") {
                        $StrViewQuery = $StrViewQuery . " and fl_timbang = '" . $txtTimbangx . "'";
                    }
                    if($txtBarcodex != "") {
                        $StrViewQuery = $StrViewQuery . " and (barcode like '%" . $txtBarcodex . "%' or barcode2 like '%" . $txtBarcodex . "%' or barcode3 like '%" . $txtBarcodex . "%')";
                    }                
                    $StrViewQuery = $StrViewQuery . " order by nama_barang asc";   
                    $callStrViewQuery=mysqli_query($koneksidb, $StrViewQuery);

	                $JumBar=mysqli_num_rows($callStrViewQuery);
                    if($JumBar > 0){
                        $counter = 0;
                        ?>
                        <table class="table border table-bordered table-striped">
                        <?php
                        while($recView=mysqli_fetch_array($callStrViewQuery))
                        {
                            $Noid = $recView['noid'];
                            $RandomCode = $recView['random_code'];
                            $SKUBarang = $recView['sku_barang'];
                            $KodeBarang = $recView['kode_barang'];
                            $NamaBarang = $recView['nama_barang'];
                            $KodeBarcode1 = $recView['barcode'];
                            $KodeBarcode2 = $recView['barcode2'];
                            $KodeBarcode3 = $recView['barcode3'];
                            $KeteranganBarang = $recView['keterangan_1'] . "<hr>" . $recView['keterangan_2'];
                            $ImagesProduk = $recView['images_produk'];
                            if($ImagesProduk == "") {
                                $ImagesProduk = "assets/images/logo.png";
                            }else{
                                $ImagesProduk = $ImagesProduk;
                            }                           

                            //create barcode
                            // Deteksi apakah protokolnya http atau https
                            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
                            // Buat URL ke barcode generator
                            $file_gambar = $protocol . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/barcode.php?text=" . urlencode($KodeBarcode1) . "&print=true&size=50&orientation=horizontal&code_type=code128";
                            $folderPath = __DIR__ . '/qrcode_produk/barcodes/';
                            if (!file_exists($folderPath)) {
                                mkdir($folderPath, 0777, true); // buat folder jika belum ada
                            }
                            $filename = $folderPath . $KodeBarcode1 . '.png';
                            $Filebarcode = "qrcode_produk/barcodes/" . $KodeBarcode1 . ".png";
                            // Ambil data gambar dari URL
                            $barcode_data = file_get_contents($file_gambar);
                            if ($barcode_data !== false) {
                                file_put_contents($filename, $barcode_data);
                            }
                            //create barcode    
                                      
                            $strInsert="UPDATE dbo_barang set `file_barcode` = '$Filebarcode' where sku_barang = '" . $SKUBarang . "'";
                            $executeSQL=mysqli_query($koneksidb, $strInsert);                             

                            
                            $Filebarcode = getFileBarcodeByKodeBarang($KodeBarang);

                            if($JumBar < 4){
                                $KolomKosong = 4 - $JumBar;
                                echo "<td width='25%' style='padding:5px;'>";
                                // Tampilkan data sesuai kebutuhan. Contoh:
                                echo "<span style='font-size:12px;'>" . $recView["nama_barang"] . "</span><br>";
                                echo "<div style='text-align:right;'>";
                                $strSQLx = "SELECT noid,sku_barang,barcode,uom,harga_jual,isi_kemasan FROM dbo_price where sku_barang = '" . $SKUBarang . "' order by noid ASC";
                                $CallstrSQLx=mysqli_query($koneksidb, $strSQLx);
                                while($resultx=mysqli_fetch_array($CallstrSQLx))
                                {
                                                                        //$PromoPrice = getPromoExist($SKUBarang,$resultx["uom"]);
                                    $PromoBarang = cekPromoBarang($SKUBarang,$resultx["uom"]);
                                    $KriteriaPromo = cekValuePromoBarang($SKUBarang,$resultx["uom"]);
                                    switch ($PromoBarang) {
                                        case 'RUPIAH':
                                            $HargaPromo = $resultx["harga_jual"]-$KriteriaPromo;
                                            $StatusPromoPrice = "<span style='font-size:22px;color:#0000FF;'>Rp. " . number_format($HargaPromo,0) . "</span>";
                                            $StatusPromoPrice.="<span style='font-size:22px;color:#FF0000;text-decoration: line-through red;'> Rp. ". number_format($resultx["harga_jual"],0) . "</span> / " . $resultx["uom"] . " / " . $resultx["isi_kemasan"] . "<br>";
                                            break;
                                        case 'PERSEN':
                                            $HargaPromo = $resultx["harga_jual"]-($resultx["harga_jual"] * $KriteriaPromo / 100);
                                            $StatusPromoPrice = "<span style='font-size:22px;color:#0000FF;'>Rp. " . number_format($HargaPromo,0) . "</span>";
                                            $StatusPromoPrice.="<span style='font-size:22px;color:#FF0000;text-decoration: line-through red;'> Rp. ". number_format($resultx["harga_jual"],0) . "</span> / " . $resultx["uom"] . " / " . $resultx["isi_kemasan"] . "<br>";
                                            break;
                                        case 'BUY1GET1':
                                            $StatusPromoPrice = "<span style='font-size:22px;color:#0000FF;'>" . $PromoBarang . "</span>";
                                            $StatusPromoPrice.="<span style='font-size:22px;color:#FF0000;'> Rp. ". number_format($resultx["harga_jual"],0) . "</span> / " . $resultx["uom"] . " / " . $resultx["isi_kemasan"] . "<br>";
                                            break;
                                        case 'BUY2GET1':
                                            $StatusPromoPrice = "<span style='font-size:22px;color:#0000FF;'>" . $PromoBarang . "</span>";
                                            $StatusPromoPrice.="<span style='font-size:22px;color:#FF0000;'> Rp. ". number_format($resultx["harga_jual"],0) . "</span> / " . $resultx["uom"] . " / " . $resultx["isi_kemasan"] . "<br>";
                                            break;
                                        case 'FREEITEM':
                                            $StatusPromoPrice = "<span style='font-size:22px;color:#0000FF;'>" . $PromoBarang . "</span>";
                                            $StatusPromoPrice.="<span style='font-size:22px;color:#FF0000;'> Rp. ". number_format($resultx["harga_jual"],0) . "</span> / " . $resultx["uom"] . " / " . $resultx["isi_kemasan"] . "<br>";
                                            break;
                                        default:
                                            $StatusPromoPrice = "";
                                            break;
                                    }
                                    if($StatusPromoPrice != ""){
                                    echo $StatusPromoPrice;
                                    }else{
                                    echo "<span style='font-size:22px;color:#FF0000;'> Rp. ". number_format($resultx["harga_jual"],0) . "</span> / " . $resultx["uom"] . " / " . $resultx["isi_kemasan"] . "<br>";
                                    }
                                }
                                echo "</div>";
                                echo "<div style='display: flex;'>";
                                echo "<img src='" . $Filebarcode . "' width='70%'/>";
                                echo "<span style='font-size:15px;margin-top:20px;font-weight:800;text-align:center;'>" . $recView["sku_barang"] . "<br>" . $currdatedbx . "</span>";
                                echo "</div>";
                                echo "</td>"; 
                                for($i=1;$i<=$KolomKosong;$i++){
                                    echo "<td width='25%' style='padding:5px;'>";
                                    echo "</td>";
                                }
                            }else{
                                echo "<td width='25%' style='padding:5px;'>";
                                // Tampilkan data sesuai kebutuhan. Contoh:
                                echo "<span style='font-size:12px;'>" . $recView["nama_barang"] . "</span><br>";
                                echo "<div style='text-align:right;'>";
                                $strSQLx = "SELECT noid,sku_barang,barcode,uom,harga_jual,isi_kemasan FROM dbo_price where sku_barang = '" . $SKUBarang . "' order by noid ASC";
                                $CallstrSQLx=mysqli_query($koneksidb, $strSQLx);
                                while($resultx=mysqli_fetch_array($CallstrSQLx))
                                {
                                    //$PromoPrice = getPromoExist($SKUBarang,$resultx["uom"]);
                                    $PromoBarang = cekPromoBarang($SKUBarang,$resultx["uom"]);
                                    $KriteriaPromo = cekValuePromoBarang($SKUBarang,$resultx["uom"]);
                                    switch ($PromoBarang) {
                                        case 'RUPIAH':
                                            $HargaPromo = $resultx["harga_jual"]-$KriteriaPromo;
                                            $StatusPromoPrice = "<span style='font-size:22px;color:#0000FF;'>Rp. " . number_format($HargaPromo,0) . "</span>";
                                            $StatusPromoPrice.="<span style='font-size:22px;color:#FF0000;text-decoration: line-through red;'> Rp. ". number_format($resultx["harga_jual"],0) . "</span> / " . $resultx["uom"] . " / " . $resultx["isi_kemasan"] . "<br>";
                                            break;
                                        case 'PERSEN':
                                            $HargaPromo = $resultx["harga_jual"]-($resultx["harga_jual"] * $KriteriaPromo / 100);
                                            $StatusPromoPrice = "<span style='font-size:22px;color:#0000FF;'>Rp. " . number_format($HargaPromo,0) . "</span>";
                                            $StatusPromoPrice.="<span style='font-size:22px;color:#FF0000;text-decoration: line-through red;'> Rp. ". number_format($resultx["harga_jual"],0) . "</span> / " . $resultx["uom"] . " / " . $resultx["isi_kemasan"] . "<br>";
                                            break;
                                        case 'BUY1GET1':
                                            $StatusPromoPrice = "<span style='font-size:22px;color:#0000FF;'>" . $PromoBarang . "</span>";
                                            $StatusPromoPrice.="<span style='font-size:22px;color:#FF0000;'> Rp. ". number_format($resultx["harga_jual"],0) . "</span> / " . $resultx["uom"] . " / " . $resultx["isi_kemasan"] . "<br>";
                                            break;
                                        case 'BUY2GET1':
                                            $StatusPromoPrice = "<span style='font-size:22px;color:#0000FF;'>" . $PromoBarang . "</span>";
                                            $StatusPromoPrice.="<span style='font-size:22px;color:#FF0000;'> Rp. ". number_format($resultx["harga_jual"],0) . "</span> / " . $resultx["uom"] . " / " . $resultx["isi_kemasan"] . "<br>";
                                            break;
                                        case 'FREEITEM':
                                            $StatusPromoPrice = "<span style='font-size:22px;color:#0000FF;'>" . $PromoBarang . "</span>";
                                            $StatusPromoPrice.="<span style='font-size:22px;color:#FF0000;'> Rp. ". number_format($resultx["harga_jual"],0) . "</span> / " . $resultx["uom"] . " / " . $resultx["isi_kemasan"] . "<br>";
                                            break;
                                        default:
                                            $StatusPromoPrice = "";
                                            break;
                                    }
                                    if($StatusPromoPrice != ""){
                                    echo $StatusPromoPrice;
                                    }else{
                                    echo "<span style='font-size:22px;color:#FF0000;'> Rp. ". number_format($resultx["harga_jual"],0) . "</span> / " . $resultx["uom"] . " / " . $resultx["isi_kemasan"] . "<br>";
                                    }
                                    
                                }
                                echo "</div>";
                                echo "<div style='display: flex;'>";
                                echo "<img src='" . $Filebarcode . "' width='70%'/>";
                                echo "<span style='font-size:15px;margin-top:20px;font-weight:800;text-align:center;'>" . $recView["sku_barang"] . "<br>" . $currdatedbx . "</span>";
                                echo "</div>";
                                echo "</td>"; 
                            }
                            $counter++;

                            if ($counter % 4 == 0) {
                                echo "</tr><tr>";
                            }
                        }
                        
                        if ($counter % 4 != 0) {
                        echo "</tr>";
                        }
                        echo "</table>";
                    }

                    ?>
                    </div>
                    <?php
                }
                ?>
                </div>
            </div>
        </div>
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
<script type="text/javascript" src="assets/js/autocomplete/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="assets/js/autocomplete/jquery-ui.min.js"></script>
<script type="text/javascript" src="assets/js/autocomplete/jquery.select-to-autocomplete.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
function PrintDoc() {
    var toPrint = document.getElementById('PrintArea');
    var popupWin = window.open('', '_blank', 'width=800,height=600,location=no,left=50px');
    popupWin.document.open();
    popupWin.document.write('<html><title><?php echo $hash16; ?></title><link rel="stylesheet" type="text/css" href="theme/css/print.css" media="print"/></head><body onload="window.print();">')
    popupWin.document.write(toPrint.innerHTML);
    popupWin.document.write('</body></html>');
    popupWin.document.close();
}

$(document).ready(function (){
    var txtErrorType = $("#txtErrorType").val();
    var txtErrorDescription = $("#txtErrorDescription").val();

    if(txtErrorDescription != ""){
        Swal.fire({
            position: 'center',
            icon: txtErrorType,
            html: txtErrorDescription,
            confirmButtonColor: "#3085d6",
            confirmButtonText: "Tutup Pesan Ini",
        })
    }
    
    $("#txtDepartemen").change(function (e) {
        var DeptID = $(this).val();
        $.ajax({
        type: "POST",
        url: "list.kategori.php",
        data: "dept=" + DeptID,
        success: function (data) {
            $("#DivKategori").html(data);
        },
        });
    });
    $("#DivKategori").change(function (e) {
        var SubID = $("#txtKategoriProduk").val();
        $.ajax({
        type: "POST",
        url: "list.subkategori.php",
        data: "kat=" + SubID,
        success: function (data) {
            $("#DivSubKategori").html(data);
        },
        });
    });
    $("#DivSubKategori").change(function (e) {
        var SubID = $("#txtSubKategoriProduk").val();
        $.ajax({
        type: "POST",
            url: "list.item.php",
            data: "subkat=" + SubID,
            success: function (data) {
                $("#DivItemDetail").html(data);
            },
        });
    });
});
</script>
