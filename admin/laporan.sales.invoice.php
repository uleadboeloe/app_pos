<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include_once "library/connection.php";
include_once "library/parameter.php";
include_once "library/fungsi.php";
include_once "../lib_dbo/user_functions.php";

if(isset($_SESSION['SESS_user_id'])){
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
            <div class="flex items-center justify-between py-2 px-4">
                <h2 class="font-bold text-xl uppercase tracking-wide text-slate-700 dark:text-navy-100">Sales Invoice Per Periode <?php    echo $varStartDateIndo; ?> sd <?php echo $varEndDateIndo;   ?></h2>
                <button class="btn space-x-2 mr-1 bg-success font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90" onclick="PrintDoc()">Print Detail</button>
            </div>
            <div class="card p-5 mt-3">
                <table id="table1" class="is-hoverable w-full" width="100%">     
                    <thead>
                    <tr>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">No Strukr</th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Kode / Nama Kasir</th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Tanggal</th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Total Struk</th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Pembayaran</th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Customer</th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    /*==========================*/
                    $StrViewQuery="SELECT * from dbo_header where kode_store = '" . $_SESSION['SESS_kode_store'] . "' and tanggal between '" . $varStartDate. "' and '" . $varEndDate. "' order by noid DESC";   
                    //echo $StrViewQuery;
                    $callStrViewQuery=mysqli_query($koneksidb, $StrViewQuery);
                    while($recView=mysqli_fetch_array($callStrViewQuery))
                    {
                        $NomorStruk = $recView['no_struk'];
                        $KodeKasir = $recView['kode_kasir'];
                        $Tanggal = $recView['tanggal'];
                        $Jam = $recView['jam'];
                        $DisplayDate = date("d-m-Y", strtotime($Tanggal)) . " " . $Jam;
                        $totalBayar = $recView['total_bayar'];
                        $totalStruk = $recView['total_struk'];
                        $kembalian = $recView['kembalian'];
                        $jenisBayar = $recView['jenis_bayar'];
                        $KodeCustomer = $recView['kode_customer'];
                        ?>
                        <tr class="border-y border-transparent border-b-slate-200 dark:border-b-navy-500">
                            <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $NomorStruk; ?></td>     
                            <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $KodeKasir; ?> - <?php   echo getNamaUser($KodeKasir); ?></td>     
                            <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $DisplayDate; ?></td>         
                            <td class="whitespace-nowrap px-4 py-3 sm:px-5 text-right"><?php   echo number_format($totalStruk,2); ?></td>         
                            <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $jenisBayar; ?></td>
                            <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $KodeCustomer; ?></td>        
                            <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                                <a href="sales-invoice@<?php   echo $NomorStruk; ?>" class="btn h-8 rounded bg-success px-3 text-xs font-medium text-white hover:bg-error-focus focus:bg-error-focus active:bg-error-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">Show Detail</a>
                            </td>
                        </tr>
                        <?php                     
                    }
                    ?>
                    </tbody>
                </table>              
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
                <table width="100%" style="border:solid 1px #000;">     
                    <thead>
                    <tr style="font-size:12px;font-weight:800;">
                        <th style="border-bottom:solid 1px #000;">No Struk / Nama Barang</th>
                        <th style="border-bottom:solid 1px #000;">Nama Kasir / Harga</th>
                        <th style="border-bottom:solid 1px #000;">Tanggal / Qty Jual</th>
                        <th style="border-bottom:solid 1px #000;">Total Struk</th>
                        <th style="border-bottom:solid 1px #000;">Pembayaran / Diskon</th>
                        <th style="border-bottom:solid 1px #000;">Customer / Gross Sales</th>
                        <th style="border-bottom:solid 1px #000;">Netto Sales</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    /*==========================*/
                    $StrViewQuery="SELECT * from dbo_header where kode_store = '" . $_SESSION['SESS_kode_store'] . "' and tanggal = '" . $currdatedb. "' order by noid DESC";   
                    $callStrViewQuery=mysqli_query($koneksidb, $StrViewQuery);
                    while($recView=mysqli_fetch_array($callStrViewQuery))
                    {
                        $NomorStruk = $recView['no_struk'];
                        $KodeKasir = $recView['kode_kasir'];
                        $Tanggal = $recView['tanggal'];
                        $Jam = $recView['jam'];
                        $DisplayDate = date("d-m-Y", strtotime($Tanggal)) . " " . $Jam;
                        $totalBayar = $recView['total_bayar'];
                        $totalStruk = $recView['total_struk'];
                        $kembalian = $recView['kembalian'];
                        $jenisBayar = $recView['jenis_bayar'];
                        $KodeCustomer = $recView['kode_customer'];
                        ?>
                        <tr style="font-size:12px;">
                            <td class=""><?php   echo $NomorStruk; ?></td>     
                            <td class=""><?php   echo $KodeKasir; ?> - <?php   echo getNamaUser($KodeKasir); ?></td>     
                            <td class=""><?php   echo $DisplayDate; ?></td>         
                            <td class=""><?php   echo number_format($totalStruk,2); ?></td>         
                            <td class=""><?php   echo $jenisBayar; ?></td>
                            <td class=""><?php   echo $KodeCustomer; ?></td>
                        </tr>
                        <?php
                        $VarTotalSales = 0;
                        $VarTotalGross = 0;
                        $VarTotalDiskon = 0;
                        $VarTotalNetSales = 0;
                        $StrViewQueryDetail="SELECT * from dbo_detail where no_struk = '" . $NomorStruk. "' order by noid DESC";   
                        $callStrViewQueryDetail=mysqli_query($koneksidb, $StrViewQueryDetail);
                        while($recViewDetail=mysqli_fetch_array($callStrViewQueryDetail))
                        {
                            $DetailKodeBarang = $recViewDetail['kode_barang'];
                            $DetailQtySales = $recViewDetail['qty_sales'];
                            $DetailHarga = $recViewDetail['harga'];
                            $DetailSatuan = $recViewDetail['satuan'];
                            $DetailTotalSales = $recViewDetail['total_sales'];
                            $DetailVarDiskon = $recViewDetail['var_diskon'];
                            $DetailGrossSales = $recViewDetail['gross_sales'];
                            $DetailNettoSales = $recViewDetail['netto_sales'];
                            $DetailQtyVoid = $recViewDetail['qty_voided'];
                            $VarTotalSales+=$DetailTotalSales;
                            $VarTotalGross+=$DetailGrossSales;
                            $VarTotalDiskon+=$DetailVarDiskon;
                            $VarTotalNetSales+=$DetailNettoSales;
                            ?>
                            <tr style="font-size:12px;">
                                <td class=""><?php   echo $DetailKodeBarang; ?> <?php   echo getNamaBarangByKodeBarang($DetailKodeBarang); ?></td>     
                                <td class=""><?php   echo $DetailHarga; ?></td>     
                                <td class=""><?php   echo $DetailQtySales; ?> <?php   echo $DetailSatuan; ?></td>         
                                <td style="text-align:right"><?php   echo number_format($DetailTotalSales,2); ?></td>         
                                <td style="text-align:right"><?php   echo number_format($DetailVarDiskon,2); ?></td>
                                <td style="text-align:right"><?php   echo number_format($DetailGrossSales,2); ?></td>
                                <td style="text-align:right"><?php   echo number_format($DetailNettoSales,2); ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                            <tr style="font-size:12px;">  
                                <td style="text-align:right;font-weight:800;" colspan="3">TOTAL</td>         
                                <td style="text-align:right"><?php   echo number_format($VarTotalSales,2); ?></td>         
                                <td style="text-align:right"><?php   echo number_format($VarTotalDiskon,2); ?></td>
                                <td style="text-align:right"><?php   echo number_format($VarTotalGross,2); ?></td>
                                <td style="text-align:right"><?php   echo number_format($VarTotalNetSales,2); ?></td>
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
<?php
}
?>