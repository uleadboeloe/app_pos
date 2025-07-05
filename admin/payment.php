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
<div class="app-preloader fixed z-50 grid h-full w-full place-content-center bg-orange-50 dark:bg-navy-900 bg-[url(assets/images/please-wait.avif)] bg-no-repeat bg-center">
    <div class="app-preloader-inner relative inline-block h-48 w-48"></div>
</div>
<!-- Page Wrapper -->
<div id="root" class="min-h-100vh flex grow bg-slate-50 dark:bg-navy-900" x-cloak>
    <?php   include "sidebar.php";  ?>
    <!-- Main Content Wrapper -->
    <main class="main-content w-full px-[var(--margin-x)] pb-8 bg-green-100">
        <div class="col-span-12 p-2 lg:col-span-12">
            <div class="flex items-center justify-between py-2 px-4">
                <h2 class="font-bold text-xl uppercase tracking-wide text-slate-700 dark:text-navy-100">List Payment CASH</h2>
                <button class="btn space-x-2 mr-1 bg-success font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90" onclick="PrintDoc()">Print Detail</button>
            </div>
            <div class="card p-5 mt-3">
                <table id="table1" class="is-hoverable w-full" width="100%">     
                    <thead>
                    <tr>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">No Struk</th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Kode / Nama Kasir</th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Tanggal</th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Pembayaran</th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Jenis</th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Mesin EDC</th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Approval Code</th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    /*==========================*/
                    $StrViewQuery="SELECT * from dbo_payment where kode_store = '" . $_SESSION['SESS_kode_store'] . "' and tanggal = '" . $currdatedb. "' and jenis_bayar = 'CASH' order by noid DESC";   
                    $callStrViewQuery=mysqli_query($koneksidb, $StrViewQuery);
                    while($recView=mysqli_fetch_array($callStrViewQuery))
                    {
                        $NoStruk = $recView['no_struk'];
                        $KodeKasir = getKodeKasirByInvoiceNo($NoStruk);
                        $Tanggal = $recView['tanggal'];
                        $DisplayDate = date("d-m-Y", strtotime($Tanggal));
                        $JenisBayar = $recView['jenis_bayar'];
                        $TotalBayar = $recView['total_bayar'];
                        $KodeEdc = $recView['kode_edc'];
                        $NamaEdc = $recView['nama_edc'];
                        $ReffNo = $recView['reff_code'];
                        $ApprovalCode = $recView['approval_code'];
                        $CardNo = $recView['card_number'];
                        ?>
                        <tr class="border-y border-transparent border-b-slate-200 dark:border-b-navy-500">
                            <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $NoStruk; ?></td>     
                            <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $KodeKasir; ?> - <?php   echo getNamaUser($KodeKasir); ?></td>     
                            <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $DisplayDate; ?></td>         
                            <td class="whitespace-nowrap px-4 py-3 sm:px-5 text-right"><?php   echo number_format($TotalBayar,2); ?></td>         
                            <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $JenisBayar; ?></td>
                            <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $NamaEdc; ?></td> 
                            <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $ApprovalCode; ?></td>        
                            <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                                <div class="btn h-8 rounded bg-success px-3 text-xs font-medium text-white hover:bg-error-focus focus:bg-error-focus active:bg-error-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">Show Detail</div>
                            </td>
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
                <h2 class="font-bold text-xl uppercase tracking-wide text-slate-700 dark:text-navy-100">List Payment NON CASH</h2>
            </div>
            <div class="card p-5 mt-3">
                <table id="table2" class="is-hoverable w-full" width="100%">     
                    <thead>
                    <tr>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">No Struk</th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Kode / Nama Kasir</th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Tanggal</th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Pembayaran</th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Jenis</th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Mesin EDC</th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Approval Code</th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    /*==========================*/
                    $StrViewQuery="SELECT * from dbo_payment where kode_store = '" . $_SESSION['SESS_kode_store'] . "' and tanggal = '" . $currdatedb. "' and jenis_bayar <> 'CASH' order by noid DESC";   
                    $callStrViewQuery=mysqli_query($koneksidb, $StrViewQuery);
                    while($recView=mysqli_fetch_array($callStrViewQuery))
                    {
                        $NoStruk = $recView['no_struk'];
                        $KodeKasir = getKodeKasirByInvoiceNo($NoStruk);
                        $Tanggal = $recView['tanggal'];
                        $DisplayDate = date("d-m-Y", strtotime($Tanggal));
                        $JenisBayar = $recView['jenis_bayar'];
                        $TotalBayar = $recView['total_bayar'];
                        $KodeEdc = $recView['kode_edc'];
                        $NamaEdc = $recView['nama_edc'];
                        $ReffNo = $recView['reff_code'];
                        $ApprovalCode = $recView['approval_code'];
                        $CardNo = $recView['card_number'];
                        ?>
                        <tr class="border-y border-transparent border-b-slate-200 dark:border-b-navy-500">
                            <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $NoStruk; ?></td>     
                            <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $KodeKasir; ?> - <?php   echo getNamaUser($KodeKasir); ?></td>     
                            <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $DisplayDate; ?></td>         
                            <td class="whitespace-nowrap px-4 py-3 sm:px-5 text-right"><?php   echo number_format($TotalBayar,2); ?></td>         
                            <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $JenisBayar; ?></td>
                            <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $NamaEdc; ?></td> 
                            <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $ApprovalCode; ?></td>        
                            <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                                <div class="btn h-8 rounded bg-success px-3 text-xs font-medium text-white hover:bg-error-focus focus:bg-error-focus active:bg-error-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">Show Detail</div>
                            </td>
                        </tr>
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
            <table width="100%" style="border:1px solid #000;">
                <tr>
                    <td>
                        <div style="font-size:12px;color:#000;font-weight:800;"><?php   echo $NamaStore; ?></div>
                        <div style="font-size:12px;color:#000;"><?php   echo $HeaderStruk; ?></div>
                    </td>
                </tr> 
            </table>      
            <hr>         
            <table width="100%" style="border:solid 1px #000;">     
                <thead>
                <tr style="font-size:12px;font-weight:800;">
                    <th style="border-bottom:solid 1px #000;" width="70%">Metode Pembayaran</th>
                    <th style="border-bottom:solid 1px #000;" width="30%">Total Bayar</th>
                </tr>
                </thead>
                <tbody>
                <?php
                /*==========================*/
                $StrViewQuery="SELECT sum(total_bayar) as TotalBayar,jenis_bayar from dbo_payment where kode_store = '" . $_SESSION['SESS_kode_store'] . "' and tanggal = '" . $currdatedb. "' group by jenis_bayar";   
                $callStrViewQuery=mysqli_query($koneksidb, $StrViewQuery);
                while($recView=mysqli_fetch_array($callStrViewQuery))
                {
                    $TotalBayar = $recView['TotalBayar'];
                    $JenisBayar = $recView['jenis_bayar'];
                    ?>
                    <tr style="font-size:12px;">
                        <td class=""><?php   echo $JenisBayar; ?></td>           
                        <td class="" style="text-align:right"><?php   echo number_format($TotalBayar,2); ?></td>   
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table> 
            <hr>         
            <table width="100%" style="border:solid 1px #000;">     
                <thead>
                <tr style="font-size:12px;font-weight:800;">
                    <th style="border-bottom:solid 1px #000;" width="30%">Mesin EDC</th>
                    <th style="border-bottom:solid 1px #000;" width="40%">Bank Penerbit</th>
                    <th style="border-bottom:solid 1px #000;" width="30%">Total Bayar</th>
                </tr>
                </thead>
                <tbody>
                <?php
                /*==========================*/
                $StrViewQuery="SELECT sum(total_bayar) as TotalBayar,nama_edc,kode_edc from dbo_payment where kode_store = '" . $_SESSION['SESS_kode_store'] . "' and tanggal = '" . $currdatedb. "' group by jenis_bayar";   
                $callStrViewQuery=mysqli_query($koneksidb, $StrViewQuery);
                while($recView=mysqli_fetch_array($callStrViewQuery))
                {
                    $TotalBayar = $recView['TotalBayar'];
                    $BankPenerbit = getBankPenerbitMesinEdc($recView['kode_edc']);
                    $MesinEdc = $recView['nama_edc'];
                    ?>
                    <tr style="font-size:12px;">
                        <td class=""><?php   echo $MesinEdc; ?></td>        
                        <td class=""><?php   echo $BankPenerbit; ?></td>           
                        <td class="" style="text-align:right"><?php   echo number_format($TotalBayar,2); ?></td>   
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>            
            <hr>         
            <table width="100%" style="border:solid 1px #000;">     
                <thead>
                <tr style="font-size:12px;font-weight:800;">
                    <th style="border-bottom:solid 1px #000;" width="30%">Metode Pembayaran</th>
                    <th style="border-bottom:solid 1px #000;" width="40%">Kartu / Bank Penerbit</th>
                    <th style="border-bottom:solid 1px #000;" width="30%">Total Bayar</th>
                </tr>
                </thead>
                <tbody>
                <?php
                /*==========================*/
                $StrViewQuery="SELECT sum(total_struk) as TotalBayar,jenis_bayar,nama_kartu from dbo_header where kode_store = '" . $_SESSION['SESS_kode_store'] . "' and tanggal = '" . $currdatedb. "' group by jenis_bayar";   
                $callStrViewQuery=mysqli_query($koneksidb, $StrViewQuery);
                while($recView=mysqli_fetch_array($callStrViewQuery))
                {
                    $TotalBayar = $recView['TotalBayar'];
                    $NamaBank = $recView['nama_kartu'];
                    $JenisBayar = $recView['jenis_bayar'];
                    ?>
                    <tr style="font-size:12px;">
                        <td class=""><?php   echo $JenisBayar; ?></td>    
                        <td class=""><?php   echo $NamaBank; ?></td>          
                        <td class="" style="text-align:right"><?php   echo number_format($TotalBayar,2); ?></td>   
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>                 
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
    popupWin.document.write('<html><title>Preview Print</title><link rel="stylesheet" type="text/css" href="/assets/css/print.css" media="print"/></head><body onload="window.print();window.close();">')
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

    let tableTransaksiCash = new DataTable('#table1', {
        colReorder: false,
        rowReorder: false,
        paging: true,
        responsive: true,
        searching: true,
        info: true,
        sort: true,
        zeroRecords: "",
    });    

    let tableTransaksiNonCash = new DataTable('#table2', {
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
