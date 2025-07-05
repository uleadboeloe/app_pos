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
            $StrViewQuery="SELECT * from dbo_store where random_code = '" . $_GET['rcode'] . "'";
            //echo $StrSalesDetails . "<hr>";     
            $callStrViewQuery=mysqli_query($koneksidb, $StrViewQuery);
            while($recView=mysqli_fetch_array($callStrViewQuery))
            {
                $varNoid = $recView['noid'];
                ?>
                <div class="col-span-12 p-2 lg:col-span-12">
                    <div class="flex items-center justify-between py-2 px-4">
                        <h2 class="font-bold text-xl uppercase tracking-wide text-slate-700 dark:text-navy-100">Detail Store</h2>
                        <div class="flex">
                        <button class="btn space-x-2 mr-1 bg-secondary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90" 
                        onclick="window.location.href='edit-product@<?php echo $_GET['rcode'];   ?>'">Edit Product</button>
                        <button class="btn space-x-2 mr-1 bg-warning font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90" 
                        onclick="window.location.href='print-price-tag'">Print Price Tag</button>
                        <button class="btn space-x-2 mr-1 bg-success font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90" 
                        onclick="window.location.href='print-barcode'">Print Barcode</button>                                                
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
                                <div class="w-80"><img src="<?php echo $LogoAmanstore;   ?>" class="mt-3 w-80"></div>
                                <div class="w-2/3 p-2">
                                    <div class="mt-3">
                                        <p class="text-indigo-300">Nama Store</p>
                                        <p class="text-2xl font-semibold tracking-wide"><?php echo $recView['nama_store'];   ?></p>
                                    </div>
                                    <div class="mt-3">
                                        <p class="text-indigo-300">Nama Store</p>
                                        <p class="text-2xl font-semibold tracking-wide"><?php echo $recView['nama_store'];   ?></p>
                                    </div>
                                    <div class="mt-3">
                                        <p class="text-indigo-300">Nama Store</p>
                                        <p class="text-2xl font-semibold tracking-wide"><?php echo $recView['nama_store'];   ?></p>
                                    </div>

                                </div> 
                                <div class="w-1/3 p-2">
                                    <div class="mt-3">
                                        <p class="text-indigo-300">Store Location</p>
                                        <table width="100%" class="border-2 border-gray-300">
                                            <tr>
                                                <td class="p-2">Store Name</td>
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