<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include_once "library/connection.php";
include_once "library/parameter.php";
include_once "library/fungsi.php";
include_once "../lib_dbo/user_functions.php";
$hash16 = CreateUniqueHash16();

    $StrViewQuery="SELECT * from dbo_user where userid = '" . $_SESSION['SESS_user_id'] . "'";   
    $callStrViewQuery=mysqli_query($koneksidb, $StrViewQuery);
    while($recView=mysqli_fetch_array($callStrViewQuery))
    {
        $varStartDate = $recView['start_date'];
        // Format tanggal lokal Indonesia
        $varStartDateIndo = date("d M Y", strtotime($varStartDate));

        $varEndDate = $recView['end_date'];
        $varEndDateIndo = date("d M Y", strtotime($varEndDate));
    }
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
    <?php   include "sidebar.laporan.php";  ?>
    <!-- Main Content Wrapper -->
    <main class="main-content w-full px-[var(--margin-x)] pb-8 bg-green-100">
        <div class="col-span-12 p-2 lg:col-span-12">
            <form name="form1" id="form1" method="post" action="proses-clear-header">
                <div class="mt-2 grid grid-cols-12 bg-slate-200 transition-all duration-[.25s] sm:mt-5 lg:mt-6">
                    <div class="card col-span-12 p-4 m-4 sm:px-5 lg:col-span-12">
                        <div class="flex items-center justify-between py-2 px-4">
                            <h2 class="font-bold text-xl uppercase tracking-wide text-slate-700 dark:text-navy-100">List Sales Header - Proses Failed - Periode <?php    echo $varStartDateIndo; ?> sd <?php echo $varEndDateIndo;   ?></h2>
                            <input type="hidden" id="txtSDate" name="txtSDate" value="<?php   echo $varStartDate; ?>">
                            <input type="hidden" id="txtEDate" name="txtEDate" value="<?php   echo $varEndDate; ?>">
                            <input type="submit" class="btn space-x-2 mr-1 bg-success font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90" value="Set Cleared">
                        </div>
                        <div class="card p-5 mt-3">
                            <table id="table1" class="is-hoverable w-full" width="100%">     
                                <thead>
                                <tr>
                                    <th width="25%" class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">No Struk</th>
                                    <th width="25%" class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Kode / Nama Kasir</th>
                                    <th width="25%" class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Tanggal</th>
                                    <th width="25%" class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Total Struk</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                /*==========================*/
                                $StrViewQuery="SELECT * from dbo_header where kode_store = '" . $_SESSION['SESS_kode_store'] . "' and fl_sync = -1 and tanggal between '" . $varStartDate . "' and '" . $varEndDate . "' order by noid DESC";   
                                $callStrViewQuery=mysqli_query($koneksidb, $StrViewQuery);
                                while($recView=mysqli_fetch_array($callStrViewQuery))
                                {
                                    $HeaderID = $recView['noid'];
                                    $NoStruk = $recView['no_struk'];
                                    $KodeKasir = $recView['kode_kasir'];
                                    $Tanggal = $recView['tanggal'];
                                    $Jam = $recView['jam'];
                                    $DisplayDate = date("d-m-Y", strtotime($Tanggal)) . " " . $Jam;
                                    $TotalStruk = $recView['total_struk'];
                                    ?>
                                    <tr class="border-y border-transparent border-b-slate-200 dark:border-b-navy-500">
                                        <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                                        <input type="checkbox" checked name="checkbox[]" style="display:none;" id="checkbox<?php	echo	$HeaderID;	?>" class="w-5 h-5 bg-primary" value="<?php	echo	$HeaderID;	?>">
                                        <?php   echo $NoStruk; ?></td>     
                                        <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $KodeKasir; ?> - <?php   echo getNamaUser($KodeKasir); ?></td>     
                                        <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $DisplayDate; ?></td>       
                                        <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php echo number_format($TotalStruk,2,',','.'); ?></td>  
                                    </tr>
                                    <?php
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </form>
            <form name="form1" id="form1" method="post" action="proses-clear-detail">            
                <div class="mt-2 grid grid-cols-12 bg-slate-200 transition-all duration-[.25s] sm:mt-5 lg:mt-6">
                    <div class="card col-span-12 p-4 m-4 sm:px-5 lg:col-span-12">
                        <div class="flex items-center justify-between py-2 px-4">
                            <h2 class="font-bold text-xl uppercase tracking-wide text-slate-700 dark:text-navy-100">List Sales Detail - Proses Failed</h2>
                            <input type="hidden" id="txtSDate" name="txtSDate" value="<?php   echo $varStartDate; ?>">
                            <input type="hidden" id="txtEDate" name="txtEDate" value="<?php   echo $varEndDate; ?>">
                            <input type="submit" class="btn space-x-2 mr-1 bg-success font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90" value="Set Cleared">
                        </div>
                        <div class="card p-5 mt-3">
                            <table id="table2" class="is-hoverable w-full" width="100%">     
                                <thead>
                                <tr>
                                    <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">No Struk</th>
                                    <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Kode / Nama Kasir</th>
                                    <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Kode Barang</th>
                                    <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Qty Sales</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                /*==========================*/
                                $StrViewQuery="SELECT * from dbo_detail where kode_store = '" . $_SESSION['SESS_kode_store'] . "' and fl_sync = -1 and no_struk in (select no_struk from dbo_header where tanggal between '" . $varStartDate . "' and '" . $varEndDate . "') order by noid DESC";   
                                $callStrViewQuery=mysqli_query($koneksidb, $StrViewQuery);
                                while($recView=mysqli_fetch_array($callStrViewQuery))
                                {
                                    $DetailID = $recView['noid'];
                                    $NoStruk = $recView['no_struk'];
                                    $KodeKasir = $recView['posting_user'];
                                    $KodeBarang = $recView['kode_barang'];
                                    $QtySales = $recView['qty_sales'];
                                    $Satuan = $recView['satuan'];
                                    ?>
                                    <tr class="border-y border-transparent border-b-slate-200 dark:border-b-navy-500">
                                        <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                                            <input type="checkbox" checked name="checkboxx[]" id="checkboxx<?php	echo	$DetailID;	?>" style="display:none;" class="w-5 h-5 bg-primary" value="<?php	echo	$DetailID;	?>">
                                            <?php   echo $NoStruk; ?>
                                        </td>     
                                        <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $KodeKasir; ?> - <?php   echo getNamaUser($KodeKasir); ?></td>     
                                        <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $KodeBarang; ?></td>  
                                        <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $QtySales; ?> <?php   echo $Satuan; ?></td>  
                                    </tr>
                                    <?php
                                }
                                ?>
                                </tbody>
                            </table>
                        </div> 
                    </div>
                </div>
            </form>
            <form name="form1" id="form1" method="post" action="proses-clear-payment">
                <div class="mt-2 grid grid-cols-12 bg-slate-200 transition-all duration-[.25s] sm:mt-5 lg:mt-6">
                    <div class="card col-span-12 p-4 m-4 sm:px-5 lg:col-span-12">            
                        <div class="flex items-center justify-between py-2 px-4">
                            <h2 class="font-bold text-xl uppercase tracking-wide text-slate-700 dark:text-navy-100">List Payment - Proses Failed</h2>
                            <input type="hidden" id="txtSDate" name="txtSDate" value="<?php   echo $varStartDate; ?>">
                            <input type="hidden" id="txtEDate" name="txtEDate" value="<?php   echo $varEndDate; ?>">
                            <input type="submit" class="btn space-x-2 mr-1 bg-success font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90" value="Set Cleared">
                        </div>
                        <div class="card p-5 mt-3">
                            <table id="table3" class="is-hoverable w-full" width="100%">     
                                <thead>
                                <tr>
                                    <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">No Struk</th>
                                    <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Kode / Nama Kasir</th>
                                    <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Tanggal</th>
                                    <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Total Bayar</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                /*==========================*/
                                $StrViewQuery="SELECT * from dbo_payment where kode_store = '" . $_SESSION['SESS_kode_store'] . "' and fl_sync = -1 and tanggal between '" . $varStartDate . "' and '" . $varEndDate . "' order by noid DESC";   
                                $callStrViewQuery=mysqli_query($koneksidb, $StrViewQuery);
                                while($recView=mysqli_fetch_array($callStrViewQuery))
                                {
                                    $PaymentID = $recView['noid'];
                                    $NoStruk = $recView['no_struk'];
                                    $KodeKasir = $recView['posting_user'];
                                    $Tanggal = $recView['tanggal'];
                                    $TotalBayar = $recView['total_bayar'];
                                    ?>
                                    <tr class="border-y border-transparent border-b-slate-200 dark:border-b-navy-500">
                                        <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                                        x<input type="checkbox" checked name="checkboxxx[]" id="checkboxxx<?php	echo	$PaymentID;	?>" style="display:none;" class="w-5 h-5 bg-primary" value="<?php	echo	$PaymentID;	?>">
                                        <?php   echo $NoStruk; ?>
                                        </td>     
                                        <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $KodeKasir; ?> - <?php   echo getNamaUser($KodeKasir); ?></td>     
                                        <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $Tanggal; ?></td>       
                                        <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $TotalBayar; ?></td>  
                                    </tr>
                                    <?php
                                }
                                ?>
                                </tbody>
                            </table>
                        </div> 
                    </div>
                </div>
            </form>    
            <div class="mt-2 grid grid-cols-12 bg-slate-200 transition-all duration-[.25s] sm:mt-5 lg:mt-6">
                <div class="card col-span-12 p-4 m-4 sm:px-5 lg:col-span-12">               
                    <div class="flex items-center justify-between py-2 px-4">
                        <h2 class="font-bold text-xl uppercase tracking-wide text-slate-700 dark:text-navy-100">List Sales No Detail</h2>
                    </div>
                    <div class="card p-5 mt-3">
                        <table id="table4" class="is-hoverable w-full" width="100%">     
                            <thead>
                            <tr>
                                <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">No Struk</th>
                                <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Kode / Nama Kasir</th>
                                <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Tanggal</th>
                                <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Qty</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            /*==========================*/
                            $StrViewQuery="SELECT * from dbo_header where kode_store = '" . $_SESSION['SESS_kode_store'] . "' and tanggal between '" . $varStartDate . "' and '" . $varEndDate . "' order by noid DESC";   
                            $callStrViewQuery=mysqli_query($koneksidb, $StrViewQuery);
                            while($recView=mysqli_fetch_array($callStrViewQuery))
                            {
                                $NoStruk = $recView['no_struk'];
                                $KodeKasir = $recView['posting_user'];
                                $Tanggal = $recView['tanggal'];
                                $QtyItem = getTotalItemPerStruk($NoStruk);
                                if($QtyItem == 0){
                                ?>
                                <tr class="border-y border-transparent border-b-slate-200 dark:border-b-navy-500">
                                    <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $NoStruk; ?></td>     
                                    <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $KodeKasir; ?> - <?php   echo getNamaUser($KodeKasir); ?></td>     
                                    <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $Tanggal; ?></td>  
                                    <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $QtyItem; ?></td>  
                                </tr>
                                <?php
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>      
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

    let tableHeader = new DataTable('#table1', {
        colReorder: false,
        rowReorder: false,
        paging: true,
        responsive: true,
        searching: false,
        info: true,
        sort: true,
        zeroRecords: "",
    });   

    let tableDetail = new DataTable('#table2', {
        colReorder: false,
        rowReorder: false,
        paging: true,
        responsive: true,
        searching: false,
        info: true,
        sort: true,
        zeroRecords: "",
    });

    let tablePayment = new DataTable('#table3', {
        colReorder: false,
        rowReorder: false,
        paging: true,
        responsive: true,
        searching: false,
        info: true,
        sort: true,
        zeroRecords: "",
    });    

    let tableNoDetail = new DataTable('#table4', {
        colReorder: false,
        rowReorder: false,
        paging: true,
        responsive: true,
        searching: false,
        info: true,
        sort: true,
        zeroRecords: "",
    });   
});
</script>
