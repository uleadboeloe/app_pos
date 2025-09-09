<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include_once "library/connection.php";
include_once "library/parameter.php";
include_once "library/fungsi.php";
include_once "../lib_dbo/user_functions.php";
include_once "../lib/general_lib.php";
$hash16 = CreateUniqueHash16();
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
<script type="text/javascript" src="assets/js/export.excel.js"></script>
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
                <h2 class="font-bold text-xl uppercase tracking-wide text-slate-700 dark:text-navy-100">Search Produk</h2>
                <div class="flex">
                <iframe id="txtArea1" style="display:none"></iframe>
                <button id="btnExport" onclick="fnExcelReport();" class="btn space-x-2 mr-1 bg-warning font-medium text-white hover:bg-warning-focus focus:bg-warning-focus active:bg-warning-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90"> Export List Produk</button>
                <button class="btn space-x-2 mr-1 bg-success font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90" onclick="PrintDoc()">Print Detail</button>
                </div>
            </div>

            <form name="formProses" name="frmMasterProduk" id="frmMasterProduk" method="post" action="list.produk.market.php?search=true" enctype="multipart/form-data">
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
                                            <input placeholder="Masukan Kode Barcode" type="text" id="txtBarcode" name="txtBarcode"
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
                <h2 class="font-bold text-xl uppercase tracking-wide text-slate-700 dark:text-navy-100">List Produk Market</h2>
            </div>
            <div class="card p-5 mt-3">
                <div class="table-responsive">
                <table id="table1" class="is-hoverable w-full" width="100%">     
                    <thead>
                    <tr>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">PLU</th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Kode Barcode</th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Nama Barang</th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Harga Jual</th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Harga Jual</th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Harga Jual</th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Harga Promo</th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Action</th>
                    </tr>
                    </thead>
                    <tbody>
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
                        //echo $StrSalesDetails . "<hr>";     
                        $callStrViewQuery=mysqli_query($koneksidb, $StrViewQuery);
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
                            $varUomBarcode1 = $recView['uom'];
                            $varPrice1 = getPriceByKodeBarcode($KodeBarcode1);
                            $varUomBarcode2 = $recView['uom2'];
                            $varPrice2 = getPriceByKodeBarcode($KodeBarcode2);
                            $varUomBarcode3 = $recView['uom3'];
                            $varPrice3 = getPriceByKodeBarcode($KodeBarcode3);
                            $varPromoPrice1 = cekValuePromoBarang($SKUBarang,$varUomBarcode1);
                            if($ImagesProduk == "") {
                                $ImagesProduk = "assets/images/logo.png";
                            }else{
                                $ImagesProduk = $ImagesProduk;
                            }
                            ?>
                            <tr class="border-y border-transparent border-b-slate-200 dark:border-b-navy-500">
                                <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $SKUBarang; ?></td>     
                                <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $KodeBarcode1; ?></td>     
                                <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $NamaBarang; ?></td>      
                                <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $varPrice1; ?>/<?php   echo $varUomBarcode1; ?></td>      
                                <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $varPrice2; ?> <?php   echo $varUomBarcode2; ?></td>      
                                <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $varPrice3; ?> <?php   echo $varUomBarcode3; ?></td>      
                                <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $varPromoPrice1; ?></td>     
                                <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                                    <a href="detail.product.php?rcode=<?php   echo $RandomCode; ?>" target="_blank" class="btn h-8 rounded bg-success px-3 text-xs font-medium text-white hover:bg-error-focus focus:bg-error-focus active:bg-error-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">Show Detail</a>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                        </div>
                        <?php
                    }else{
                        ?>
                        <input type="hidden" id="txtProdukName" name="txtProdukName" value="">
                        <input type="hidden" id="txtDeptCode" name="txtDeptCode" value="">
                        <input type="hidden" id="txtKategoriCode" name="txtKategoriCode" value="">
                        <input type="hidden" id="txtSubKategoriCode" name="txtSubKategoriCode" value="">
                        <input type="hidden" id="txtJenisTimbang" name="txtJenisTimbang" value="">
                        <input type="hidden" id="txtKodeBarcode" name="txtKodeBarcode" value="">
                        <?php
                        /*==========================*/
                        $StrViewQuery="SELECT * from dbo_barang where fl_active = 1 order by noid DESC limit 50";
                        //echo $StrSalesDetails . "<hr>";     
                        $callStrViewQuery=mysqli_query($koneksidb, $StrViewQuery);
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
                            $varUomBarcode1 = $recView['uom'];
                            $varPrice1 = getPriceByKodeBarcode($KodeBarcode1);
                            $varUomBarcode2 = $recView['uom2'];
                            $varPrice2 = getPriceByKodeBarcode($KodeBarcode2);
                            $varUomBarcode3 = $recView['uom3'];
                            $varPrice3 = getPriceByKodeBarcode($KodeBarcode3);
                            $varPromoPrice1 = cekValuePromoBarang($SKUBarang,$varUomBarcode1);
                                                        
                            $ImagesProduk = $recView['images_produk'];
                            if($ImagesProduk == "") {
                                $ImagesProduk = "assets/images/logo.png";
                            }else{
                                $ImagesProduk = $ImagesProduk;
                            }
                            ?>
                            <tr class="border-y border-transparent border-b-slate-200 dark:border-b-navy-500">
                                <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $SKUBarang; ?></td>     
                                <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $KodeBarcode1; ?></td>     
                                <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $NamaBarang; ?></td>      
                                <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $varPrice1; ?>/<?php   echo $varUomBarcode1; ?></td>      
                                <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $varPrice2; ?> <?php   echo $varUomBarcode2; ?></td>      
                                <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $varPrice3; ?> <?php   echo $varUomBarcode3; ?></td>      
                                <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $varPromoPrice1; ?></td>   
                                <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                                    <a href="detail.product.php?rcode=<?php   echo $RandomCode; ?>" target="blank_"  class="btn h-8 rounded bg-success px-3 text-xs font-medium text-white hover:bg-error-focus focus:bg-error-focus active:bg-error-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">Show Detail</a>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                        </div>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
                </div>
            </div>
            <div id="PrintArea" style="display:none;">
            <?php
            $NamaStore = getStoreName($KodeStoreOffline);
            $HeaderStruk = getHeaderStruk($KodeStoreOffline);
            $FooterStruk = getFooterStruk($KodeStoreOffline); 
            ?>
            <div style="page-break-before: always;">
                <div style="font-size:12px;color:#000;font-weight:800;"><?php echo $NamaStore; ?></div>
                <div style="font-size:12px;color:#000;"><?php echo $HeaderStruk; ?></div>
            </div>                
            <table id="exportExcel" style="border:solid 1px #000; border-collapse:collapse;font-size:10px;" width="100%">     
                <thead>
                <tr>
                    <th>PLU</th>
                    <th>Kode Barcode</th>
                    <th>Nama Barang</th>
                    <th>Harga Jual</th>
                    <th>Harga Jual</th>
                    <th>Harga Jual</th>
                    <th>Harga Promo</th>
                </tr>
                </thead>
                <tbody>
                <?php
                /*==========================*/
                $callStrViewQuery=mysqli_query($koneksidb, $StrViewQuery);
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
                    $varUomBarcode1 = $recView['uom'];
                    $varPrice1 = getPriceByKodeBarcode($KodeBarcode1);
                    $varUomBarcode2 = $recView['uom2'];
                    $varPrice2 = getPriceByKodeBarcode($KodeBarcode2);
                    $varUomBarcode3 = $recView['uom3'];
                    $varPrice3 = getPriceByKodeBarcode($KodeBarcode3);
                    $varPromoPrice1 = cekValuePromoBarang($SKUBarang,$varUomBarcode1);
                    ?>
                    <tr style="border:solid 1px #000; border-collapse:collapse;font-size:10px;">
                        <td style="border:solid 1px #000; border-collapse:collapse;font-size:10px;"><?php   echo $SKUBarang; ?></td>     
                        <td style="border:solid 1px #000; border-collapse:collapse;font-size:10px;"><?php   echo $KodeBarcode1; ?></td>     
                        <td style="border:solid 1px #000; border-collapse:collapse;font-size:10px;"><?php   echo $NamaBarang; ?></td>             
                        <td style="border:solid 1px #000; border-collapse:collapse;font-size:10px;"><?php   echo $varPrice1; ?>/<?php   echo $varUomBarcode1; ?></td>     
                        <td style="border:solid 1px #000; border-collapse:collapse;font-size:10px;"><?php   echo $varPrice2; ?>/<?php   echo $varUomBarcode2; ?></td>     
                        <td style="border:solid 1px #000; border-collapse:collapse;font-size:10px;"><?php   echo $varPrice3; ?>/<?php   echo $varUomBarcode3; ?></td>     
                        <td style="border:solid 1px #000; border-collapse:collapse;font-size:10px;"><?php   echo $varPromoPrice1; ?></td>         
                    </tr>
                    <?php                     
                }
                ?>
                </tbody>
            </table>
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
<script>
function PrintDoc() {
    var toPrint = document.getElementById('PrintArea');
    var popupWin = window.open('', '_blank', 'width=800,height=600,location=no,left=50px');
    popupWin.document.open();
    popupWin.document.write('<html><title>Preview Print</title><body onload="window.print();window.close();">')
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
    let tableTransaksi = new DataTable('#table1', {
        colReorder: false,
        rowReorder: false,
        paging: true,
        responsive: true,
        searching: true,
        info: true,
        sort: true,
        zeroRecords: "",
    });        

});
</script>
