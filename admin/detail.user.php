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
<style>
    .voucher-bg {
        background-image: url('assets/images/voucher-bg.png');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }
</style>

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
            $NoVoucher = $_GET['rcode'];
            $StrViewQuery="SELECT * from dbo_user where random_code = '" . $_GET['rcode'] . "'";
            //echo $StrViewQuery . "<hr>";     
            $callStrViewQuery=mysqli_query($koneksidb, $StrViewQuery);
            while($recView=mysqli_fetch_array($callStrViewQuery))
            {
                $varNoid = $recView['noid'];
                ?>
                <div class="col-span-12 p-2 lg:col-span-12">
                    <div class="flex items-center justify-between py-2 px-4">
                        <h2 class="font-bold text-xl uppercase tracking-wide text-slate-700 dark:text-navy-100">Detail User</h2>
                        <div class="flex">
                        <button class="btn space-x-2 mr-1 bg-success font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90" 
                        onclick="PrintDoc();">Print Kartu Akses</button>                                                
                        </div>
                    </div>

                    <div class="rounded-lg bg-white py-5 sm:py-6">
                        <div class="voucher-bg px-4 text-primary sm:px-5">
                            <div class="flex" id="PrintArea">
                                <div class="w-80 p-2"><img src="<?php echo $recView['qrcode_user'];   ?>" class="mt-3 w-80"></div>
                                <div class="w-full p-2">
                                    <div class="mt-3">
                                        <p class="text-indigo-300">Nama User</p>
                                        <p class="text-3xl font-semibold tracking-wide"><?php echo $recView['nama_user'];   ?></p>
                                    </div>
                                    <div class="mt-3">
                                        <p class="text-indigo-300">Nomor Whatsapp</p>
                                        <p class="text-3xl font-semibold tracking-wide"><?php echo $recView['nomor_kontak'];   ?></p>
                                    </div>
                                    <div class="mt-3">
                                        <p class="text-indigo-300">Jabatan</p>
                                        <p class="text-3xl font-semibold tracking-wide"><?php echo $recView['job_title'];   ?></p>
                                    </div>                                                                        
                                </div> 
                            </div>
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
<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
function PrintDoc() {
    var toPrint = document.getElementById('PrintArea');
    var popupWin = window.open('', '_blank', 'width=800,height=600,location=no,left=50px');
    popupWin.document.open();
    popupWin.document.write('<html><title>Preview Voucher</title><link rel="stylesheet" type="text/css" href="../theme/css/print.css" media="print"/></head><body onload="window.print();window.close();">')
    popupWin.document.write(toPrint.innerHTML);
    popupWin.document.write('</body></html>');
    popupWin.document.close();
}

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
});    
</script>