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
        if(isset($_GET['rcode'])){
            $StrViewQuery="SELECT * from dbo_mesin_edc where random_code = '" . $_GET['rcode'] . "'";
            $callStrViewQuery=mysqli_query($koneksidb, $StrViewQuery);
            while($recView=mysqli_fetch_array($callStrViewQuery))
            {
                $varNoid = $recView['noid'];
                $varKodeStore = $recView['kode_store'];
                $varNamaMesin = $recView['nama_mesin'];
                ?>
                <div class="col-span-12 p-2 lg:col-span-12">
                    <div class="flex items-center justify-between py-2 px-4">
                        <h2 class="font-bold text-xl uppercase tracking-wide text-slate-700 dark:text-navy-100">Detail Mesin EDC</h2>
                        <div class="flex">
                        <button class="btn space-x-2 mr-1 bg-success font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90" 
                        onclick="PrintDoc();">Print Transaksi Mesin</button>                                                
                        </div>
                    </div>

                    <div class="rounded-lg bg-white py-5 sm:py-6">
                        <div class="px-4 text-primary sm:px-5">
                            <table id="table1" class="is-hoverable w-full" width="100%">     
                                <thead>
                                <tr>
                                    <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Kode Register</th>
                                    <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Tanggal Transaksi</th>
                                    <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Total Bayar</th>
                                    <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Jenis Pembayaran</th>
                                    <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                /*==========================*/
                                $StrViewPayment="SELECT * from dbo_payment where kode_store = '" . $_SESSION['SESS_kode_store'] . "' and kode_edc = '" . $varNoid . "' order by noid DESC";   
                                //echo $StrViewPayment . "<hr>";
                                $callStrViewPayment=mysqli_query($koneksidb, $StrViewPayment);
                                while($recViewPayment=mysqli_fetch_array($callStrViewPayment))
                                {
                                    $RandomCode = $recViewPayment['random_code'];
                                    $NoStrukEdc = $recViewPayment['no_struk'];
                                    $Tanggal = $recViewPayment['tanggal'];
                                    $TotalBayar = $recViewPayment['total_bayar'];
                                    $JenisBayar = $recViewPayment['jenis_bayar'];
                                    ?>
                                    <tr class="border-y border-transparent border-b-slate-200 dark:border-b-navy-500">
                                        <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $NoStrukEdc; ?></td>     
                                        <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $Tanggal; ?></td>         
                                        <td class="whitespace-nowrap px-4 py-3 sm:px-5 text-right"><?php   echo number_format($TotalBayar,2); ?></td>
                                        <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $JenisBayar; ?></td>        
                                        <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                                            <a href="register@<?php   echo $KodeRegister; ?>" target="_blank" class="btn h-8 rounded bg-success px-3 text-xs font-medium text-white hover:bg-error-focus focus:bg-error-focus active:bg-error-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">Show Detail</a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                </tbody>
                            </table>
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