<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include_once "library/connection.php";
include_once "library/parameter.php";
include_once "library/fungsi.php";
include_once "../lib/general_lib.php";

if(isset($_SESSION['SESS_user_id'])){
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
      <main class="main-content w-full px-[var(--margin-x)] pb-8">
        <?php
        $TotalStruk = getCountSalesHarianStore($currdatedb,$_SESSION['SESS_kode_store']);
        $Pembulatan = getTotalPembulatanHarianStore($currdatedb,$_SESSION['SESS_kode_store']);
        if($Pembulatan < 0){
            $Pembulatan = $Pembulatan*-1;
            $StatusPembulatan = "-";
        }else{
            $StatusPembulatan = "+";
        }
        $GrossSales = getTotalGrossHarianStore($currdatedb,$_SESSION['SESS_kode_store']);
        $TotalKembalian = getTotalKembalianHarianStore($currdatedb,$_SESSION['SESS_kode_store']);
        $TotalCash = getTotalCashHarianStore($currdatedb,$_SESSION['SESS_kode_store']);
        $TotalNonCash = getTotalNonCashHarianStore($currdatedb,$_SESSION['SESS_kode_store']);
        $TotalDiskon = getTotalDiskonHarianStore($currdatedb,$_SESSION['SESS_kode_store']);
        $NetSales = getTotalNettoHarianStore($currdatedb,$_SESSION['SESS_kode_store']);
        ?>        
      <div class="flex gap-2">
          <div class="w-1/3 border-2 border-green-600 card bg-gradient-to-r from-blue-500 to-blue-300 px-4 pb-4 sm:px-5">
              <div class="my-3 flex h-8 items-center justify-between">
                  <h2 class="text-sm font-medium tracking-wide text-slate-700 dark:text-navy-100">Total Struk</h2>
              </div>
              <div class="space-y-3.5">
                  <div class="flex cursor-pointer justify-between">
                      <div class="w-full flex space-x-3.5 text-center">
                          <p class="text-3xl font-bold text-slate-700 dark:text-navy-100 text-right"><?php  echo $TotalStruk; ?> Transaksi</p>
                      </div>
                  </div>
              </div>
          </div>
          <div class="w-1/3 border-2 border-green-600 card bg-gradient-to-r from-green-500 to-green-300 px-4 pb-4 sm:px-5">
              <div class="my-3 flex h-8 items-center justify-between">
                  <h2 class="text-sm font-medium tracking-wide text-slate-700 dark:text-navy-100">Gross Sales</h2>
              </div>
              <div class="space-y-3.5">
                  <div class="flex cursor-pointer justify-between">
                      <div class="w-full flex space-x-3.5 text-center">
                              <p class="text-3xl font-bold text-slate-700 dark:text-navy-100">Rp. <?php echo number_format($GrossSales,2,',','.'); ?></p>
                      </div>
                  </div>
              </div>
          </div> 
          <div class="w-1/3 border-2 border-green-600 card bg-gradient-to-r from-yellow-500 to-yellow-300 px-4 pb-4 sm:px-5">
              <div class="my-3 flex h-8 items-center justify-between">
                  <h2 class="text-sm font-medium tracking-wide text-slate-700 dark:text-navy-100">Total Net Sales</h2>
              </div>
              <div class="space-y-3.5">
                  <div class="flex cursor-pointer justify-between">
                      <div class="w-full flex space-x-3.5 text-center">
                          <p class="text-3xl font-bold text-slate-700 dark:text-navy-100">Rp. <?php echo number_format($NetSales,2,',','.'); ?></p>
                      </div>
                  </div>
              </div>
          </div>      
          <div class="w-1/3 border-2 border-green-600 card bg-gradient-to-r from-purple-500 to-purple-300 px-4 pb-4 sm:px-5">
              <div class="my-3 flex h-8 items-center justify-between">
                  <h2 class="text-sm font-medium tracking-wide text-slate-700 dark:text-navy-100">Non Cash</h2>
              </div>
              <div class="space-y-3.5">
                  <div class="flex cursor-pointer justify-between">
                      <div class="w-full flex space-x-3.5 text-center">               
                          <p class="text-3xl font-bold text-slate-700 dark:text-navy-100">Rp. <?php echo number_format($TotalNonCash,2,',','.'); ?></p>
                      </div>
                  </div>
              </div>
          </div>
          <div class="w-1/3 border-2 border-green-600 card bg-gradient-to-r from-teal-500 to-teal-300 px-4 pb-4 sm:px-5">
              <div class="my-3 flex h-8 items-center justify-between">
                  <h2 class="text-sm font-medium tracking-wide text-slate-700 dark:text-navy-100">Cash</h2>
              </div>
              <div class="space-y-3.5">
                  <div class="flex cursor-pointer justify-between">
                      <div class="w-full flex space-x-3.5 text-center">
                          <p class="text-3xl font-bold text-slate-700 dark:text-navy-100">Rp. <?php echo number_format($TotalCash,2,',','.'); ?></p>
                      </div>
                  </div>
              </div>
          </div>
      </div>    

      <!--Row 2 Baris-->
      <div class="mt-4 grid grid-cols-12 bg-slate-200 transition-all duration-[.25s] sm:mt-5 lg:mt-6">
          <div class="card col-span-12 p-4 m-4 sm:px-5 lg:col-span-8">
                <div class='font-bold text-primary uppercase'>OPEN REGISTER</div>
                <table id="table1" class="is-hoverable w-full" width="100%">     
                    <thead>
                    <tr>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Action</th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Kode Register</th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Kode / Nama Kasir</th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Tanggal Open</th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Modal Awal</th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Nama SPV</th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Tanggal Close</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    /*==========================*/
                    $StrViewQuery="SELECT * from dbo_register where kode_store = '" . $_SESSION['SESS_kode_store'] . "' and tanggal = '" . $currdatedb . "' order by noid DESC";   
                    $callStrViewQuery=mysqli_query($koneksidb, $StrViewQuery);
                    while($recView=mysqli_fetch_array($callStrViewQuery))
                    {
                        $KodeRegister = $recView['kode_register'];
                        $KodeKasir = $recView['kode_kasir'];
                        $Tanggal = $recView['tanggal'];
                        $Jam = $recView['jam'];
                        $DisplayDate = date("d-m-Y", strtotime($Tanggal)) . " " . $Jam;
                        $TanggalClose = $recView['closing_date'] ? $recView['closing_date'] : '2024-01-01';
                        $JamClose = $recView['closing_time'];
                        if($TanggalClose == '2024-01-01') {
                            $DisplayDateClose = '';
                        }else{
                            $DisplayDateClose = date("d-m-Y", strtotime($TanggalClose)) . " " . $JamClose;
                        }
                        $ModalAwal = $recView['modal_awal'];
                        $KodeSpv = $recView['kode_spv'];
                        ?>
                        <tr class="border-y border-transparent border-b-slate-200 dark:border-b-navy-500">        
                            <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                                <a href="register@<?php   echo $KodeRegister; ?>" target="_blank" class="btn h-8 rounded bg-success px-3 text-xs font-medium text-white hover:bg-error-focus focus:bg-error-focus active:bg-error-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">Show Detail</a>
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $KodeRegister; ?></td>     
                            <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $KodeKasir; ?> - <?php   echo getNamaUser($KodeKasir); ?></td>     
                            <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $DisplayDate; ?></td>         
                            <td class="whitespace-nowrap px-4 py-3 sm:px-5 text-right"><?php   echo number_format($ModalAwal,2); ?></td>         
                            <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo getNamaUser($KodeSpv); ?></td>
                            <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $DisplayDateClose; ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
          </div>
          <div class="card col-span-12 p-4 m-4 sm:px-5 lg:col-span-4">
              <div id="display_log"></div>
          </div>      
      </div>

      <div class="mt-4 grid grid-cols-12 bg-slate-200 transition-all duration-[.25s] sm:mt-5 lg:mt-6">
          <div class="card col-span-12 p-4 m-4 sm:px-5 lg:col-span-12">
                <div class='font-bold text-primary uppercase'>List Sales Harian</div>
                <table id="table2" class="is-hoverable w-full" width="100%">     
                    <thead>
                    <tr>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">No Struk</th>
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
                    $StrViewQuery="SELECT * from dbo_header where kode_store = '" . $_SESSION['SESS_kode_store'] . "' and tanggal = '" . $currdatedb . "' and is_voided in ('0','2') order by noid DESC";   
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
                        $varDiskon = $recView['total_struk'];
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
                                <a href="sales-invoice@<?php   echo $NomorStruk; ?>" target="_blank" class="btn h-8 rounded bg-success px-3 text-xs font-medium text-white hover:bg-error-focus focus:bg-error-focus active:bg-error-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">Show Detail</a>
                            </td>
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
(function($){
	$(function(){
	  $('select[id="txtSearch"]').selectToAutocomplete();
	});
})(jQuery);
setInterval(function(){
    $('#display_log').load("display.log.user.php").fadeIn("slow");
},1000);

$(document).ready(function(){
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
</script>
<?php
}else{
header("Location: home");
}
?>