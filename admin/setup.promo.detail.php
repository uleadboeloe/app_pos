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
<div class="app-preloader fixed z-50 grid h-full w-full place-content-center bg-slate-50 dark:bg-navy-900">
    <div class="app-preloader-inner relative inline-block h-48 w-48"></div>
</div>

<!-- Page Wrapper -->
<div id="root" class="min-h-100vh flex grow bg-slate-50 dark:bg-navy-900" x-cloak>
    <?php   include "sidebar.php";  ?>
    <!-- Main Content Wrapper -->
    <main class="main-content w-full px-[var(--margin-x)] pb-8 bg-green-100">
        <?php
        if(isset($_GET['detail'])){
            $StrViewQuery="SELECT * from dbo_promo where random_code = '" . $_GET['detail'] . "'";
            //echo $StrSalesDetails . "<hr>";     
            $callStrViewQuery=mysqli_query($koneksidb, $StrViewQuery);
            while($recView=mysqli_fetch_array($callStrViewQuery))
            {
                $varNoid = $recView['noid'];
                $varPromoCode = $recView['kode_promo'];
                $varPromoName = $recView['promo_name'];
                $varPromoDesc = $recView['promo_desc'];
                $varStartDate = $recView['promo_start_date'];
                $varDisplayStartDate = date("d M Y", strtotime($varStartDate));
                $varEndDate = $recView['promo_end_date'];
                $varDispalyEndDate = date("d M Y", strtotime($varEndDate));
                $varMinPembelian = $recView['kriteria_value'];
                $varValuePromo = $recView['value_promo'];
                $varValueType = $recView['kriteria_promo'];
                $varParameter = $recView['promo_parameter'];
                $varPromoDay = substr_replace($recView['promo_day'], '', -1);
                $varBannerPromo = $recView['banner_header'];
                $varImagesPromo = $recView['promo_images'];
                $varFreeItem = $recView['free_item'];
                $varStatus = $recView['fl_active'];
                if($varStatus == 0){
                    $varStatusPromo = "Non Aktif";
                }else{
                    $varStatusPromo = "Aktif - Promo Sedang Berlangsung";
                }
                $varBarcodeInduk = $recView['kode_barcode_induk'];
                ?>

                <div class="col-span-12 p-2 lg:col-span-12">
                    <div class="flex items-center justify-between py-2 px-4">
                        <h2 class="font-bold text-xl uppercase tracking-wide text-slate-700 dark:text-navy-100">Detail Promo</h2>
                    </div>

                    <div class="rounded-lg bg-gradient-to-r from-blue-500 to-indigo-600 py-5 sm:py-6">
                        <div class="hidden mt-3 grid grid-cols-1 px-4 text-white gap-4 sm:grid-cols-2 lg:grid-cols-1">
                            <div class="flex">
                                <div class="w-80">
                                    <img src="<?php echo $varImagesPromo;   ?>" class="mt-3 w-80">
                                </div>
                                <div class="w-full p-2">
                                    <img src="<?php echo $varBannerPromo;   ?>" width="100%" class="h-80">
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-3 grid grid-cols-1 px-4 text-white gap-4 sm:grid-cols-2 lg:grid-cols-1">
                            <div class="">
                                <p class="text-indigo-100">Promo Name</p>
                                <div class="mt-1 flex items-center space-x-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 72 72"><rect width="62.599" height="38.705" x="4.7" y="20.147" fill="#d0cfce" rx="3.409" ry="3.409"/><rect width="9.003" height="15.673" x="31.396" y="10.599" fill="#9b9b9a" rx="1.557" ry="1.557"/><path fill="#9b9b9a" d="M17.403 30.65h37.704a5.747 5.747 0 0 1 5.747 5.748v14.304a2.92 2.92 0 0 1-2.919 2.919H12.783a2.92 2.92 0 0 1-2.918-2.919V38.19a7.54 7.54 0 0 1 7.538-7.538"/><rect width="48" height="19.731" x="11.474" y="31.367" fill="#fff" rx="2.918" ry="2.918"/><g fill="none" stroke="#000" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M40.502 20.647H63.89a3.41 3.41 0 0 1 3.41 3.41v31.886a3.41 3.41 0 0 1-3.41 3.41H8.11a3.41 3.41 0 0 1-3.41-3.41V24.057a3.41 3.41 0 0 1 3.41-3.41h23.286"/><rect width="48" height="19.731" x="11.474" y="31.367" stroke-linecap="round" stroke-linejoin="round" rx="2.918" ry="2.918"/><path stroke-linecap="round" stroke-linejoin="round" d="M52.396 38.141h-4v7h4m-4-3.5h3m-17 3.5l-3-7l-3 7m1-1.676h4m-14 1.676v-7l5 7v-7m20 7v-7l-3 6l-3-6v7"/><rect width="9.003" height="15.673" x="31.499" y="10.237" stroke-miterlimit="10" rx="1.557" ry="1.557"/></g></svg>
                                    <p class="text-2xl font-medium"><?php echo $varPromoName;   ?></p>
                                </div>
                            </div>               
                            <div class="">
                                <p class="text-indigo-100">Promo Description</p>
                                <div class="mt-1 flex items-center space-x-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 48 48"><path fill="currentColor" fill-rule="evenodd" d="M24 1.5c-7.403 0-12.592.239-15.857.466S2.281 4.66 1.991 7.96C1.742 10.794 1.5 15.074 1.5 21v22.652c0 2.99 3.507 4.603 5.778 2.657l6.96-5.966c2.687.093 5.927.157 9.762.157c7.403 0 12.592-.239 15.857-.466s5.862-2.693 6.152-5.993c.249-2.835.491-7.115.491-13.041s-.242-10.206-.491-13.041c-.29-3.3-2.887-5.765-6.152-5.993C36.592 1.74 31.403 1.5 24 1.5m2.882 25.452c2.218-1.238 3.36-2.588 3.538-4.88a96 96 0 0 1-2.028-.027c-1.33-.034-2.326-1.032-2.361-2.362a99 99 0 0 1-.031-2.61c0-1.16.015-2.069.035-2.77c.036-1.252.939-2.202 2.191-2.254C28.921 12.021 29.828 12 31 12s2.079.02 2.774.05c1.252.05 2.155 1.001 2.191 2.254c.02.695.035 1.594.035 2.74v5.03h-.023c-.296 4.235-3.425 6.759-6.97 7.85c-.499.153-1.05.08-1.427-.281c-.438-.419-.813-.937-1.088-1.368c-.295-.464-.09-1.055.39-1.323m-10.462-4.88c-.178 2.292-1.32 3.642-3.538 4.88c-.48.268-.685.86-.39 1.323c.275.431.65.949 1.088 1.368c.378.36.928.434 1.427.28c3.545-1.09 6.674-3.614 6.97-7.85H22v-5.029c0-1.146-.015-2.045-.035-2.74c-.036-1.253-.939-2.204-2.191-2.255C19.079 12.021 18.172 12 17 12s-2.079.02-2.774.05c-1.252.05-2.155 1.001-2.191 2.254c-.02.7-.035 1.608-.035 2.768c0 1.075.013 1.933.03 2.61c.036 1.33 1.031 2.329 2.362 2.363c.546.013 1.215.024 2.028.027" clip-rule="evenodd"/></svg>
                                    <p class="text-2xl font-medium"><?php echo $varPromoDesc;   ?></p>
                                </div>
                            </div>        
                            <div class="">
                                <p class="text-indigo-100">Status Promo</p>
                                <div class="mt-1 flex items-center space-x-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="2" d="M12.703 2.703a.99.99 0 0 0-1.406 0l-8.594 8.594a.99.99 0 0 0 0 1.406l8.594 8.594a.99.99 0 0 0 1.406 0l8.594-8.594a.99.99 0 0 0 0-1.406zM8.983 14.7L14.7 8.983m-5.717 0L14.7 14.7"/></svg>
                                    <p class="text-2xl font-medium"><?php echo $varStatusPromo;   ?></p>
                                </div>
                            </div> 
                        </div>
                        
                        <div class="mt-3 grid grid-cols-1 px-4 text-white gap-4 sm:grid-cols-2 lg:grid-cols-4">
                            <div class="">
                                <p class="text-indigo-100">Start Date</p>
                                <div class="mt-1 flex items-center space-x-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><path fill="currentColor" d="M5.673 0a.7.7 0 0 1 .7.7v1.309h7.517v-1.3a.7.7 0 0 1 1.4 0v1.3H18a2 2 0 0 1 2 1.999v13.993A2 2 0 0 1 18 20H2a2 2 0 0 1-2-1.999V4.008a2 2 0 0 1 2-1.999h2.973V.699a.7.7 0 0 1 .7-.699M1.4 7.742v10.259a.6.6 0 0 0 .6.6h16a.6.6 0 0 0 .6-.6V7.756zm5.267 6.877v1.666H5v-1.666zm4.166 0v1.666H9.167v-1.666zm4.167 0v1.666h-1.667v-1.666zm-8.333-3.977v1.666H5v-1.666zm4.166 0v1.666H9.167v-1.666zm4.167 0v1.666h-1.667v-1.666zM4.973 3.408H2a.6.6 0 0 0-.6.6v2.335l17.2.014V4.008a.6.6 0 0 0-.6-.6h-2.71v.929a.7.7 0 0 1-1.4 0v-.929H6.373v.92a.7.7 0 0 1-1.4 0z"/></svg>
                                    <p class="text-2xl font-medium"><?php echo $varDisplayStartDate;   ?></p>
                                </div>
                            </div>
                            <div class="">
                                <p class="text-indigo-100">End Date</p>
                                <div class="mt-1 flex items-center space-x-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><path fill="currentColor" d="M5.673 0a.7.7 0 0 1 .7.7v1.309h7.517v-1.3a.7.7 0 0 1 1.4 0v1.3H18a2 2 0 0 1 2 1.999v13.993A2 2 0 0 1 18 20H2a2 2 0 0 1-2-1.999V4.008a2 2 0 0 1 2-1.999h2.973V.699a.7.7 0 0 1 .7-.699M1.4 7.742v10.259a.6.6 0 0 0 .6.6h16a.6.6 0 0 0 .6-.6V7.756zm5.267 6.877v1.666H5v-1.666zm4.166 0v1.666H9.167v-1.666zm4.167 0v1.666h-1.667v-1.666zm-8.333-3.977v1.666H5v-1.666zm4.166 0v1.666H9.167v-1.666zm4.167 0v1.666h-1.667v-1.666zM4.973 3.408H2a.6.6 0 0 0-.6.6v2.335l17.2.014V4.008a.6.6 0 0 0-.6-.6h-2.71v.929a.7.7 0 0 1-1.4 0v-.929H6.373v.92a.7.7 0 0 1-1.4 0z"/></svg>
                                    <p class="text-2xl font-medium"><?php echo $varDispalyEndDate;   ?></p>
                                </div>
                            </div>

                            <div class="">
                                <p class="text-indigo-100">Value Promo</p>
                                <div class="mt-1 flex items-center space-x-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M9.5 7c.83 0 1.5.67 1.5 1.5S10.33 10 9.5 10S8 9.33 8 8.5S8.67 7 9.5 7m5 7c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5s-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5m-6.09 3L7 15.59L15.59 7L17 8.41zM18.65 2.85l.61 3.86l3.51 1.79L21 12l1.78 3.5l-3.54 1.79l-.61 3.86l-3.89-.61l-2.77 2.77l-2.78-2.81l-3.86.64l-.62-3.89l-3.49-1.78L3 11.97L1.23 8.5l3.51-1.81l.61-3.82l3.87.63L12 .695l2.76 2.765zm1.45 6.52L17.5 8L17 5.11l-2.9.42L12 3.5L9.9 5.53L7 5.11L6.5 8L3.9 9.37L5.2 12l-1.3 2.63L6.5 16l.5 2.89l2.9-.42L12 20.5l2.1-2.03l2.9.42l.5-2.89l2.6-1.37L18.8 12z"/></svg>
                                <p class="text-2xl font-medium"><?php echo $varValuePromo;   ?></p>
                                </div>
                            </div>   
                            <div class="">
                                <p class="text-indigo-100">Kriteria Promo</p>
                                <div class="mt-1 flex items-center space-x-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M12 4L6.075 7.425L12 10.85l5.925-3.425zM3 15.975v-7.95q0-.55.263-1T4 6.3l7-4.025q.25-.125.488-.2T12 2t.525.075t.475.2L20 6.3q.475.275.738.725t.262 1V11q0 .425-.288.713T20 12t-.712-.288T19 11V9.1l-6.025 3.475q-.475.275-1 .275t-1-.275L5 9.1v6.85l5.5 3.2q.225.125.363.35T11 20q0 .575-.5.863t-1 .012L4 17.7q-.475-.275-.737-.725t-.263-1M18 24q-2.075 0-3.537-1.463T13 19t1.463-3.537T18 14t3.538 1.463T23 19t-1.463 3.538T18 24m-.5-3.9l-1.45-1.45q-.15-.15-.35-.15t-.35.15t-.15.35t.15.35l1.95 1.95q.15.15.325.213t.375.062t.375-.062t.325-.213l1.95-1.95q.15-.15.15-.35t-.15-.35t-.35-.15t-.35.15L18.5 20.1v-3.6q0-.2-.15-.35T18 16t-.35.15t-.15.35z"/></svg>
                                <p class="text-2xl font-medium"><?php echo $varValueType;   ?> - <?php echo getNamaBarangBySkuBarang($varFreeItem);   ?></p>
                                </div>
                            </div>                           

                        </div>

                        <div class="mt-3 grid grid-cols-1 px-4 text-white gap-4 sm:grid-cols-1 lg:grid-cols-1">
                            <div class="">
                                <p class="text-indigo-100">Promo Day</p>
                                <div class="mt-1 flex items-center space-x-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M6.577 19.039q-1.42 0-2.421-1.003t-1.002-2.42q0-1.343.944-2.287t2.286-.944q.95 0 1.751.515t1.194 1.39l.384.922h1.024q.8 0 1.339.551t.54 1.352t-.562 1.362t-1.362.561zm7.956-2.724q-.235-1.286-1.205-2.148q-.97-.861-2.276-.925q-.544-1.138-1.549-1.868t-2.272-.913q.496-1.555 1.811-2.508Q10.358 7 12 7q2.077 0 3.539 1.462T17 12q0 1.337-.666 2.484q-.665 1.147-1.801 1.832M11.5 5.422V2.039h1v3.384zm5.496 2.289l-.707-.727l2.395-2.408l.714.733zm1.581 4.788v-1h3.385v1zm.108 6.904l-2.396-2.408l.727-.727l2.407 2.397zM7.023 7.73l-2.44-2.44l.751-.676l2.397 2.408z"/></svg>
                                <p class="text-2xl font-medium"><?php echo $varPromoDay;   ?></p>
                                </div>
                            </div>
                        </div>  
                    </div>
                </div>
                '        
                <?php
                if($varStatus == 0){
                    ?>
                    <div class="col-span-12 p-2 lg:col-span-12">
                        <div class="flex items-center justify-between py-2 px-4">
                            <h2 class="font-bold text-xl uppercase tracking-wide text-slate-700 dark:text-navy-100">Tambah Produk Promo</h2>
                        </div>
                        <form name="formProses" name="frmMasterPromoDetail" id="frmMasterPromoDetail" method="post" action="proses-promo-detail" enctype="multipart/form-data">
                            <div class="grid grid-cols-2 gap-4 sm:gap-5 lg:gap-6">
                                <div class="col-span-12 sm:col-span-12">
                                    <div class="card p-4 sm:p-5">
                                        <div class="space-y-4">
                                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-1 lg:grid-cols-1">
                                                <label class="block">
                                                    <span class="text-purple-500 font-bold">Nama Barang<div class="badge rounded-full bg-primary/10 text-primary dark:bg-accent-light/15 dark:text-accent-light">Wajib</div></span>
                                                    <span class="relative mt-1.5 flex">						
                                                        <input placeholder="Masukan Nama Produk Yang Akan Ditambahkan" type="text" id="txtItemDetail" name="txtItemDetail" required
                                                        class="form-input peer h-12 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"/>
                                                        <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                                                            <i class="fa-regular fa-building text-base"></i>
                                                        </span>
                                                    </span>
                                                </label>                         
                                            </div>                    
                                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 lg:grid-cols-3">
                                                <label class="block">
                                                    <span class="text-purple-500 font-bold">Departemen Produk<div class="badge rounded-full bg-warning/10 text-warning dark:bg-accent-light/15 dark:text-accent-light">Opsional</div></span>
                                                    <span class="relative mt-1.5 flex">
                                                        <input type="hidden" id="txtRandomCodes" name="txtRandomCodes" value="<?php echo $_GET['detail'];   ?>" readonly>
                                                        <select id="txtDepartemen" name="txtDepartemen"
                                                        class="form-select h-12 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs+ hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">
                                                            <option value="">Pilih Departemen Produk</option>
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
                                            </div>
                                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 lg:grid-cols-1">
                                                <div id="DivItemDetail"></div>
                                            </div>
                                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 lg:grid-cols-1">
                                                <div id="DivItemUomDetail"></div>
                                            </div>                                
                                        </div>
                                    </div>                            
                                </div>
                            </div>
                        </form>
                    </div>            
                    <?php
                }
                ?>
                <div class="col-span-12 p-2 lg:col-span-12">
                    <div class="flex items-center justify-between py-2 px-4">
                        <h2 class="font-bold text-xl uppercase tracking-wide text-slate-700 dark:text-navy-100">Daftar Produk Yang Disertakan</h2>
                    </div>
                    <div id="contentpromo"></div>
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
    reloadDiv();

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
    /*UNTUK SETUP PROMO DETAIL*/
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
        url: "list.item.uom.php",
        data: "subkat=" + SubID,
        success: function (data) {
            $("#DivItemUomDetail").html(data);
        },
        });
    });

    $("#txtItemDetail").change(function (e) {
        var ItemDetail = $("#txtItemDetail").val();
        $.ajax({
        type: "POST",
        url: "list.item.uom.php",
        data: "itemid=" + ItemDetail,
        success: function (data) {
            $("#DivItemUomDetail").html(data);
        },
        });
    });
    /*UNTUK SETUP PROMO DETAIL*/
});    

document.querySelectorAll('input[type="text"]').forEach(function(input) {
  input.addEventListener('keydown', function(event) {
    if (event.key === 'Enter') {
      event.preventDefault();
    }
  });
});

function remove(id) {
    $.ajax({
    type: "POST",
    url: "delete.item.php",
    data: "itemid=" + id + "&source=promodetail",
    success: function (data) {

        var timerInterval
		Swal.fire({
		    position: 'center',
		    icon: 'success',
		    showConfirmButton: false,
		    text: 'Proses Hapus Berhasil',
			timer: 1000,
			onBeforeOpen: () => {
				Swal.showLoading()
				timerInterval = setInterval(() => {
					Swal.getContent().querySelector('strong')
						.textContent = Swal.getTimerLeft()
				}, 1000)
			},
			onClose: () => {
				clearInterval(timerInterval)
			}
		}).then((result) => {
			if (result.dismiss === Swal.DismissReason.timer) {
				reloadDiv();
			}
		})

    },
    });
}


function reloadDiv() {
  $.ajax({
    url: "table.promo.php?detail=<?php echo $_GET['detail'];   ?>", // The PHP script to fetch content
    type: "GET", // HTTP method
    success: function (data) {
      $("#contentpromo").html(data); // Update the div content
      /*
      Swal.fire({
        icon: "error",
        text: "Reloaded ",
        confirmButtonText: "Tutup Pesan",
      });*/
    },
    error: function (xhr, status, error) {
      console.error("Error:", error);
    },
  });
}
</script>