<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include_once "library/connection.php";
include_once "library/parameter.php";
include_once "library/fungsi.php";
include_once "../lib_dbo/user_functions.php";
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
        <div class="col-span-12 p-2 lg:col-span-12">
            <div class="flex items-center justify-between py-3 px-4">
                <h2 class="font-bold text-xl uppercase tracking-wide text-slate-700 dark:text-navy-100">Akses User</h2>
            </div>

            <form name="formProses" name="frmMasterProduk" id="frmMasterProduk" method="post" action="proses-user">
                <div class="grid grid-cols-2 my-2 gap-4 sm:gap-5 lg:gap-6">
                    <div class="col-span-12 sm:col-span-12">
                        <div class="card p-4 sm:p-5">
                            <div class="space-y-4">
                                <input type="hidden" id="txtRandomCode" name="txtRandomCode" value="<?php echo $hash16;   ?>" readonly>
                                
                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-5">
                                    <label class="block">
                                        <span class="text-purple-500 font-bold">Kode Store <div class="badge rounded-full bg-primary/10 text-primary dark:bg-accent-light/15 dark:text-accent-light">Wajib</div></span>
                                        <span class="relative mt-1.5 flex">
                                            <select id="txtKodeStore" name="txtKodeStore" required 
                                            class="form-select h-12 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs+ hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">
                                                <option value="">Pilih Store</option>
                                                <?php
                                                $strSQL="SELECT kode_store,nama_store FROM `dbo_store` where fl_active = 1";
                                                $CallstrSQL=mysqli_query($koneksidb, $strSQL);
                                                while($rec=mysqli_fetch_array($CallstrSQL)){
                                                ?>
                                                <option value="<?php    echo $rec['kode_store']; ?>"><?php    echo $rec['kode_store']; ?> - <?php    echo $rec['nama_store']; ?></option>
                                                <?php
                                                }
                                                ?>                                                
                                            </select>
                                        </span>
                                    </label>  

                                    <label class="block">
                                        <span class="text-purple-500 font-bold">Kode Kasir <div class="badge rounded-full bg-primary/10 text-primary dark:bg-accent-light/15 dark:text-accent-light">Wajib, Maksimal 3 Karakter</div></span>
                                        <span class="relative mt-1.5 flex">						
                                            <input placeholder="Masukan Kode Kasir" type="number" id="txtKodeKasir" name="txtKodeKasir" maxlength="3" required
                                            class="form-input peer h-12 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"/>
                                            <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                                                <i class="fa-regular fa-building text-base"></i>
                                            </span>
                                        </span>
                                    </label>
                                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-1 sm:col-span-2">
                                        <label class="block">
                                            <span class="text-purple-500 font-bold">Nama Kasir <div class="badge rounded-full bg-primary/10 text-primary dark:bg-accent-light/15 dark:text-accent-light">Wajib</div></span>
                                            <span class="relative mt-1.5 flex">						
                                                <input placeholder="Masukan Nama Kasir" type="text" id="txtNamaKasir" name="txtNamaKasir" required
                                                class="form-input peer h-12 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"/>
                                                <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                                                    <i class="fa-regular fa-building text-base"></i>
                                                </span>
                                            </span>
                                        </label>    
                                    </div> 

                                    <label class="block">
                                        <span class="text-purple-500 font-bold">Password User <div class="badge rounded-full bg-primary/10 text-primary dark:bg-accent-light/15 dark:text-accent-light">Wajib</div></span>
                                        <span class="relative mt-1.5 flex">						
                                            <input placeholder="Masukan Password User" type="password" id="txtPassword" name="txtPassword" required
                                            class="form-input peer h-12 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"/>
                                            <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                                                <i class="fa-regular fa-building text-base"></i>
                                            </span>
                                        </span> 
                                    </label>                                                                                               
                                </div>

                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-5">
                                    <label class="block">
                                        <span class="text-purple-500 font-bold">Nomor Kontak <div class="badge rounded-full bg-warning/10 text-warning dark:bg-accent-light/15 dark:text-accent-light">Opsional</div></span>
                                        <span class="relative mt-1.5 flex">						
                                            <input placeholder="Masukan Nomor Kontak" type="text" id="txtNoKontak" name="txtNoKontak"
                                            class="form-input peer h-12 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"/>
                                            <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                                                <i class="fa-regular fa-building text-base"></i>
                                            </span>
                                        </span>
                                    </label>                           

                                    <label class="block">
                                        <span class="text-purple-500 font-bold">Jabatan <div class="badge rounded-full bg-primary/10 text-primary dark:bg-accent-light/15 dark:text-accent-light">Wajib</div></span>
                                        <span class="relative mt-1.5 flex">
                                            <select id="txtJabatan" name="txtJabatan" required
                                            class="form-select mt-1 h-12 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs+ hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">
                                                <option value="">Pilih Jabatan</option>
                                                <?php
                                                $strSQL="SELECT job_title FROM `dbo_jobtitle` where fl_active = 1";
                                                $CallstrSQL=mysqli_query($koneksidb, $strSQL);
                                                while($rec=mysqli_fetch_array($CallstrSQL)){
                                                ?>
                                                <option value="<?php    echo $rec['job_title']; ?>"><?php    echo $rec['job_title']; ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </span>
                                    </label>                                                                                         				
                                </div>    
                                
                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    <label class="block">
                                        <span class="relative mt-1.5 flex">						
                                            <input type="submit" name="btnSubmit" id="btnSubmit" value="Proses Simpan"
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
                <h2 class="font-bold text-xl uppercase tracking-wide text-slate-700 dark:text-navy-100">List Akses User</h2>
            </div>
            <div class="card p-5 mt-3">
                <table id="table1" class="is-hoverable w-full" width="100%">     
                    <thead>
                    <tr>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Kode User</th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Nama User</th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Jabatan</th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Nomor Kontak</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    /*==========================*/
                    $StrViewQuery="SELECT * from dbo_user where fl_active = 1 and hak_akses < 9 order by kode_store, kode_kasir";
                    //echo $StrSalesDetails . "<hr>";     
                    $callStrViewQuery=mysqli_query($koneksidb, $StrViewQuery);
                    while($recView=mysqli_fetch_array($callStrViewQuery))
                    {
                        $RandomCode = $recView['random_code'];
                        $KodeStore = $recView['kode_store'];
                        $NamaStore = getStoreName($KodeStore);
                        $UserID = $recView['userid'];
                        $KodeKasir = $recView['kode_kasir'];
                        $NamaUser = $recView['nama_user'];
                        $AlamatUser = $recView['alamat_user'];
                        $JobTitle = $recView['job_title'];
                        $NoIdentitas = $recView['nomor_identitas'];
                        $AlamatEmail = $recView['alamat_email'];
                        $NoKontak = $recView['nomor_kontak'];
                        ?>
                        <tr class="border-y border-transparent border-b-slate-200 dark:border-b-navy-500">
                        <td class="whitespace-nowrap px-4 py-3 sm:px-5"><a href="detail-user@<?php   echo $RandomCode; ?>" class="font-bold text-primary hover:text-orange-500"><?php   echo $KodeKasir; ?></a></td>   
                        <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $NamaUser; ?></td>     
                        <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $JobTitle; ?></td>           
                        <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $NoKontak; ?></td>     
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
