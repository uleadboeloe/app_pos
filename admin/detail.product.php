<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include_once "library/connection.php";
include_once "library/parameter.php";
include_once "library/fungsi.php";
include_once "../lib_dbo/user_functions.php";
$hash16 = CreateUniqueHash16();
//$PromoEndDate = date("Y-m-d", strtotime( "$currdatedb +3 Day" ));
//$GetDetail = $_GET['detail'] ?? "";
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
        <?php
        if(isset($_GET['rcode'])){
            $StrViewQuery="SELECT * from dbo_barang where random_code = '" . $_GET['rcode'] . "'";
            //echo $StrSalesDetails . "<hr>";     
            $callStrViewQuery=mysqli_query($koneksidb, $StrViewQuery);
            while($recView=mysqli_fetch_array($callStrViewQuery))
            {
                $varNoid = $recView['noid'];
                $varSkuProduk = $recView['sku_barang'];
                $varKodeProduk = $recView['kode_barang'];
                $varBarcode = $recView['barcode'];
                $varUomBarcode = $recView['uom'];
                $varPrice = getPriceByKodeBarcode($varBarcode);
                $varBarcode2 = $recView['barcode2'];
                $varUomBarcode2 = $recView['uom2'];
                $varPrice2 = getPriceByKodeBarcode($varBarcode2);
                $varBarcode3 = $recView['barcode3'];
                $varUomBarcode3 = $recView['uom3'];
                $varPrice3 = getPriceByKodeBarcode($varBarcode3);
                $varDivisi = $recView['divisi'];
                $varDept = $recView['dept'];
                $varSubDept = $recView['sub_dept'];    
                $varNamaBarang = $recView['nama_barang'];
                $varKeterangan1 = $recView['keterangan_1'];
                $varKeterangan2 = $recView['keterangan_2'];
                $varHargaJual = $recView['harga_jual'];
                $varBeratProduk = $recView['berat_produk'];
                $ImagesProduk = $recView['images_produk'];
                $Filebarcode = $recView['file_barcode'];
                if($ImagesProduk == "") {
                    $ImagesProduk = "assets/images/logo.png";
                }
                
                ?>
                <div class="col-span-12 p-2 lg:col-span-12">
                    <div class="flex items-center justify-between py-2 px-4">
                        <h2 class="font-bold text-xl uppercase tracking-wide text-slate-700 dark:text-navy-100">Detail Produk</h2>
                        <div class="flex">
                            <button class="btn space-x-2 mr-1 bg-secondary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90" 
                            onclick="window.location.href='edit-product@<?php echo $_GET['rcode'];   ?>'">Edit Product</button>
                            <button class="btn space-x-2 mr-1 bg-warning font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90" 
                            onclick="window.location.href='print-price-tag@<?php echo $varBarcode;   ?>'">Print Price Tag</button>                                          
                        </div>
                    </div>

                    <div class="rounded-lg bg-white py-5 sm:py-6">
                        <div class="px-4 text-primary sm:px-5">
                            <?php
                            if(isset($_GET['action'])){
                            //echo "EDIT";
                            ?>
                            <div class="flex">
                                <div class="w-80"><img id="previewImg" src="<?php echo $ImagesProduk;   ?>" class="mt-3 w-80"></div>
                                <div class="w-full p-2">
                                    <div class="mt-3">
                                        <p class="text-indigo-300">Produk Name</p>
                                        <p class="text-2xl font-semibold tracking-wide"><?php echo $varNamaBarang;   ?> / <?php echo $varBarcode;   ?></p>
                                    </div>                                    
                                    <div class="mt-3">
                                        <form name="formProses" name="frmMasterProduk" id="frmMasterProduk" method="post" action="update-product" enctype="multipart/form-data">
                                        <div class="grid grid-cols-2 my-2 gap-4 sm:gap-5 lg:gap-6">
                                            <div class="col-span-12 sm:col-span-12">
                                                <div class="card p-4 sm:p-5">
                                                    <div class="space-y-4">
                                                        <input type="hidden" id="txtRandomCode" name="txtRandomCode" value="<?php echo $_GET['rcode'];   ?>" readonly>
                                                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                                            <label class="block">
                                                                <span class="text-purple-500 font-bold">Keterangan 1<div class="badge rounded-full bg-warning/10 text-warning dark:bg-accent-light/15 dark:text-accent-light">Opsional</div></span>
                                                                <span class="relative mt-1.5 flex">
                                                                    <textarea rows="4" placeholder="Keterangan 1" id="txtKeterangan1" name="txtKeterangan1"
                                                                    class="form-textarea mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent p-2.5 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"><?php echo $varKeterangan1;   ?></textarea>
                                                                </span>
                                                            </label>
                                                            <label class="block">
                                                                <span class="text-purple-500 font-bold">Keterangan 2<div class="badge rounded-full bg-warning/10 text-warning dark:bg-accent-light/15 dark:text-accent-light">Opsional</div></span>
                                                                <span class="relative mt-1.5 flex">
                                                                    <textarea rows="4" placeholder="Keterangan 2" id="txtKeterangan2" name="txtKeterangan2"
                                                                    class="form-textarea mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent p-2.5 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"><?php echo $varKeterangan2;   ?></textarea>
                                                                </span>
                                                            </label>                                                           
                                                        </div>
                                                        
   
                                                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">  
                                                            <label class="text-purple-500 font-bold">Product Images <div class="badge rounded-full bg-warning/10 text-warning dark:bg-accent-light/15 dark:text-accent-light">Opsional</div></span>
                                                                <span class="relative mt-1.5 flex">
                                                                    <input tabindex="-1" type="file" name="picture" id="picture" onchange="previewFile(this);" style="display:none;" class="pointer-events-none"/>
                                                                    <div class="flex items-center space-x-2 h-12 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs+ hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M21 17H7V3h14m0-2H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2M3 5H1v16a2 2 0 0 0 2 2h16v-2H3m12.96-10.71l-2.75 3.54l-1.96-2.36L8.5 15h11z"/></svg>
                                                                        <span>Silahkan Pilih FIle Gambar</span>
                                                                    </div>
                                                                </span>
                                                            </label>  
                                                                                                                
                                                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                                                                <label class="block">
                                                                    <span class="relative mt-1.5 flex">						
                                                                        <input type="submit" name="btnSubmit" id="btnSubmit" value="Update Produk"
                                                                        class="btn w-full p-4 space-x-2 bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
                                                                    </span>
                                                                </label>                                                      
                                                            </div>                                                              
                                                        </div>  
                                                    </div>
                                                </div>
                                            </div>
                                        </div>  
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <?php
                            }else{
                            ?>
                            <div class="flex">
                                <div class="w-80"><img src="<?php echo $ImagesProduk;   ?>" class="mt-3 w-80"></div>
                                <div class="w-2/3 p-2">
                                    <div class="mt-3">
                                        <p class="text-indigo-300">Produk Name</p>
                                        <p class="text-2xl font-semibold tracking-wide"><?php echo $varNamaBarang;   ?></p>
                                    </div>
                                    <div class="mt-3">
                                        <p class="text-indigo-300">Produk Description</p>
                                        <p class="text-base font-semibold"><?php echo $varKeterangan1;   ?><br><?php echo $varKeterangan2;   ?></p>
                                    </div>
                                    <div class="mt-3">
                                        <p class="text-indigo-300">Kode Barcode / Kemasan / Harga</p>
                                        <p class="text-base font-medium"><?php echo $varBarcode;   ?> - <?php echo $varUomBarcode;   ?>  - <?php echo number_format($varPrice,2);   ?></p>
                                    </div> 
                                    <div class="mt-3">
                                        <p class="text-indigo-300">Kode Barcode / Kemasan / Harga</p>
                                        <p class="text-base font-medium"><?php echo $varBarcode2;   ?> - <?php echo $varUomBarcode2;   ?>  - <?php echo number_format($varPrice2,2);   ?></p>
                                    </div> 
                                    <div class="mt-3">
                                        <p class="text-indigo-300">Kode Barcode / Kemasan / Harga</p>
                                        <p class="text-base font-medium"><?php echo $varBarcode3;   ?> - <?php echo $varUomBarcode3;   ?>  - <?php echo number_format($varPrice3,2);   ?></p>
                                    </div>                                                                         
                                    <div class="mt-3">
                                        <p class="text-indigo-300">Departemen</p>
                                        <p class="text-base font-medium"><?php echo $varDivisi;   ?> - <?php echo getDeptNameByCode($varDivisi);   ?></p>
                                    </div> 
                                    <div class="mt-3">
                                        <p class="text-indigo-300">Kategori</p>
                                        <p class="text-base font-medium"><?php echo $varDept;   ?> - <?php echo getKategoriNameByCode($varDept);   ?></p>
                                    </div> 
                                    <div class="mt-3">
                                        <p class="text-indigo-300">Sub Kategori</p>
                                        <p class="text-base font-medium"><?php echo $varSubDept;   ?> - <?php echo getSubKategoriNameByCode($varSubDept);   ?></p>
                                    </div>
                                </div> 
                                <div class="w-1/3 p-2">
                                    <div class="mt-3">
                                        <p class="text-indigo-300">Price Tag</p>
                                        <table width="100%" class="border-2 border-gray-300">
                                            <tr>
                                            <?php
                                            echo "<td style='padding:5px;'>";
                                            // Tampilkan data sesuai kebutuhan. Contoh:
                                            echo "<span style='font-size:12px;'>" . $recView["nama_barang"] . "</span><br>";
                                            echo "<div style='text-align:right;'>";
                                            $strSQLx = "SELECT noid,sku_barang,barcode,uom,harga_jual,isi_kemasan FROM dbo_price where sku_barang = '" . $varSkuProduk . "' order by noid ASC";
                                            $CallstrSQLx=mysqli_query($koneksidb, $strSQLx);
                                            while($resultx=mysqli_fetch_array($CallstrSQLx))
                                            {
                                                echo "Rp. <span style='font-size:22px;color:#FF0000;'>" . number_format($resultx["harga_jual"],2) . "</span> / " . $resultx["uom"] . " / " . $resultx["isi_kemasan"] . "<br>";
                                            }
                                            echo "</div>";
                                            echo "<div style='display: flex;'>";
                                            echo "<img src='" . $Filebarcode . "' width='70%'/>";
                                            echo "<span style='font-size:15px;margin-top:20px;font-weight:800;text-align:center;'>" . $recView["sku_barang"] . "<br>" . $currdatedbx . "</span>";
                                            echo "</div>";
                                            echo "</td>"; 
                                            ?>
                                            </tr>
                                        </table>
                                    </div>
                            </div>
                            <?php
                            }
                            ?> 
                        </div>
                    </div>
                </div>  

                <div class="col-span-12 p-2 lg:col-span-12">
                    <div class="flex items-center justify-between py-2 px-4">
                        <h2 class="font-bold text-xl uppercase tracking-wide text-slate-700 dark:text-navy-100">Tambah Price Level</h2>
                    </div>

                    <form name="formProses" name="frmMasterPromoDetail" id="frmMasterPromoDetail" method="post" action="proses-promo-pricelevel" enctype="multipart/form-data">
                        <div class="grid grid-cols-2 gap-4 sm:gap-5 lg:gap-6">
                            <div class="col-span-12 sm:col-span-12">
                                <div class="card p-4 sm:p-5">
                                    <div class="space-y-4">
                                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                                            <label class="block">
                                                <span class="text-purple-500 font-bold">Nama Produk<div class="badge rounded-full bg-primary/10 text-primary dark:bg-accent-light/15 dark:text-accent-light">Wajib</div></span>
                                                <span class="relative mt-1.5 flex">
                                                    <input type="hidden" id="txtRandomCodes" name="txtRandomCodes" value="<?php echo $_GET['rcode'];   ?>" readonly>
                                                    <input type="hidden" id="txtKodePromo" name="txtKodePromo" value="PRM0" readonly>
                                                    <input type="hidden" id="txtBarcodeInduk" name="txtBarcodeInduk" value="<?php echo $varBarcode;   ?>" readonly>
                                                    <input placeholder="Nama Produk" type="text" id="NamaProduk" name="NamaProduk" value="<?php echo getNamaBarangByBarcode($varBarcode);   ?>" readonly  required
                                                    class="form-input peer h-12 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"/>
                                                    <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                                                        <i class="fa-regular fa-building text-base"></i>
                                                    </span>
                                                </span>
                                            </label>  
                                            <label class="block">
                                                <span class="text-purple-500 font-bold">Harga Normal<div class="badge rounded-full bg-primary/10 text-primary dark:bg-accent-light/15 dark:text-accent-light">Wajib</div></span>
                                                <span class="relative mt-1.5 flex">
                                                    <input placeholder="Harga Normal" type="text" id="txtNormalPrice" name="txtNormalPrice" value="<?php echo getPriceByKodeBarcode($varBarcode);   ?>" readonly  required
                                                    class="form-input peer h-12 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"/>
                                                    <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                                                        <i class="fa-regular fa-building text-base"></i>
                                                    </span>
                                                </span>
                                            </label>    
    
                                            <label class="block">
                                                <span class="text-purple-500 font-bold">Qty Jual<div class="badge rounded-full bg-primary/10 text-primary dark:bg-accent-light/15 dark:text-accent-light">Wajib</div></span>
                                                <span class="relative mt-1.5 flex">
                                                    <input placeholder="Masukan Qty Jual" type="text" id="txtPromoKriteria" name="txtPromoKriteria"  required
                                                    class="form-input peer mr-4 h-12 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"/>
                                                    <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                                                        <i class="fa-regular fa-building text-base"></i>
                                                    </span>                                                    
                                                </span>
                                            </label> 
    
                                            <label class="block">
                                                <span class="text-purple-500 font-bold">Harga Jual<div class="badge rounded-full bg-primary/10 text-primary dark:bg-accent-light/15 dark:text-accent-light">Wajib</div></span>
                                                <span class="relative mt-1.5 flex">

                                                    <input placeholder="Masukan Harga Promo" type="text" id="txtPromoValue" name="txtPromoValue" required
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
                                                    <input type="submit" name="btnSubmit" id="btnSubmit" value="Tambah Item Promo"
                                                    class="btn space-x-2 bg-warning font-medium text-white hover:bg-warning-focus focus:bg-warning-focus active:bg-warning-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
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
                        <h2 class="font-bold text-xl uppercase tracking-wide text-slate-700 dark:text-navy-100">Detail Promo Price Level</h2>
                    </div>

                    <div class="card p-4 rounded-lg bg-white py-5 sm:py-6">
                        <table id="table1" class="is-hoverable w-full" width="100%">     
                            <thead>
                            <tr>
                                <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Kode Promo</th>
                                <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Kode Barcode</th>
                                <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Tipe Promo</th>
                                <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Periode Promo</th>
                                <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Qty Promo</th>
                                <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Harga Promo</th>
                                <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Status Promo</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            /*==========================*/
                            $StrViewPromo="SELECT a.random_code,b.promo_start_date,b.fl_active,b.promo_end_date,b.kode_promo,b.promo_name,b.promo_type,b.promo_parameter,b.kriteria_promo,kode_barcode_induk,a.barcode,a.sku_barang,a.kode_barang,a.uom,a.qty_jual,a.harga_jual from dbo_promo_detail a";
                            $StrViewPromo=$StrViewPromo . " join dbo_promo b on a.kode_promo = b.kode_promo";
                            $StrViewPromo=$StrViewPromo . " where a.barcode = '" . $varBarcode. "' and b.promo_parameter = 'E'";
                            //echo $StrSalesDetails . "<hr>";     
                            $callStrViewPromo=mysqli_query($koneksidb, $StrViewPromo);
                            while($recPromo=mysqli_fetch_array($callStrViewPromo))
                            {
                                $RandomCodePromo = $recPromo['random_code'];
                                $KodePromo = $recPromo['kode_promo'];
                                $PromoName = $recPromo['promo_name'];
                                $PromoType = $recPromo['promo_type'];
                                $PromoParameter = $recPromo['promo_parameter'];
                                $QtyJual = $recPromo['qty_jual'];
                                $HargaJual = $recPromo['harga_jual'];
                                $PromoStartDate = $recPromo['promo_start_date'];
                                $PromoEndDate = $recPromo['promo_end_date'];
                                $Status = $recPromo['promo_end_date'];
                                $varStatus = $recPromo['fl_active'];
                                if($varStatus == 0){
                                    $varStatusPromo = "Price Level Non Aktif";
                                }else{
                                    $varStatusPromo = "Aktif - Price Level Berlaku";
                                }
                                ?>
                                <tr class="border-y border-transparent border-b-slate-200 dark:border-b-navy-500">
                                <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $KodePromo; ?></td>   
                                <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $varBarcode; ?></td>    
                                <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $PromoType; ?></td>     
                                <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $PromoStartDate; ?> - <?php   echo $PromoEndDate; ?></td>     
                                <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $QtyJual; ?></td>           
                                <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo number_format($HargaJual,2); ?></td>    
                                <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $varStatusPromo; ?></td>     
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                        </table>
                    </div>
                </div>   

                <div class="col-span-12 p-2 lg:col-span-12">
                    <div class="flex items-center justify-between py-2 px-4">
                        <h2 class="font-bold text-xl uppercase tracking-wide text-slate-700 dark:text-navy-100">Detail Promo Reguler</h2>
                    </div>

                    <div class="card p-4 rounded-lg bg-white py-5 sm:py-6">
                        <table id="table2" class="is-hoverable w-full" width="100%">     
                            <thead>
                            <tr>
                                <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Kode Promo</th>
                                <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Kode Barcode</th>
                                <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Tipe Promo</th>
                                <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Parameter</th>
                                <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Qty Promo</th>
                                <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Harga Promo</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            /*==========================*/
                            $StrViewPromo="SELECT a.random_code,b.promo_start_date,b.promo_end_date, b.kode_promo,b.promo_name,b.promo_type,b.promo_parameter,b.kriteria_promo,kode_barcode_induk,a.barcode,a.sku_barang,a.kode_barang,a.uom,a.qty_jual,a.harga_jual from dbo_promo_detail a";
                            $StrViewPromo=$StrViewPromo . " join dbo_promo b on a.kode_promo = b.kode_promo";
                            $StrViewPromo=$StrViewPromo . " where a.barcode = '" . $varBarcode. "' and b.promo_parameter <> 'E'";
                            //echo $StrSalesDetails . "<hr>";     
                            $callStrViewPromo=mysqli_query($koneksidb, $StrViewPromo);
                            while($recPromo=mysqli_fetch_array($callStrViewPromo))
                            {
                                $RandomCodePromo = $recPromo['random_code'];
                                $KodePromo = $recPromo['kode_promo'];
                                $PromoName = $recPromo['promo_name'];
                                $PromoType = $recPromo['promo_type'];
                                $PromoParameter = $recPromo['promo_parameter'];
                                $QtyJual = $recPromo['qty_jual'];
                                $HargaJual = $recPromo['harga_jual'];
                                $PromoStartDate = $recPromo['promo_start_date'];
                                $PromoEndDate = $recPromo['promo_end_date'];
                                ?>
                                <tr class="border-y border-transparent border-b-slate-200 dark:border-b-navy-500">
                                <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $KodePromo; ?></td>   
                                <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $PromoName; ?></td>    
                                <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $PromoType; ?></td>     
                                <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $PromoParameter; ?></td>   
                                <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $QtyJual; ?></td>           
                                <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo number_format($HargaJual,2); ?></td>     
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                        </table>
                    </div>
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
<script type="text/javascript" src="assets/js/autocomplete/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="assets/js/autocomplete/jquery-ui.min.js"></script>
<script type="text/javascript" src="assets/js/autocomplete/jquery.select-to-autocomplete.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
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

    let tableTransaksi1 = new DataTable('#table1', {
        colReorder: false,
        rowReorder: false,
        paging: true,
        responsive: true,
        searching: true,
        info: true,
        sort: true,
        zeroRecords: "",
    });
    let tableTransaksi2 = new DataTable('#table2', {
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


    
function previewFile(input){
    var file = $("#picture").get(0).files[0];
    if(file){
        var reader = new FileReader();
        reader.onload = function(){
            $("#previewImg").attr("src", reader.result);
            $("#previewImg").css("display", "block");
        }
        reader.readAsDataURL(file);
    }
}
</script>