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
            $NoRegister = $_GET['rcode'];
            $StrViewQuery="SELECT * from dbo_header where no_struk = '" . $_GET['rcode'] . "'";
            //echo $StrViewQuery . "<hr>";     
            $callStrViewQuery=mysqli_query($koneksidb, $StrViewQuery);
            while($recView=mysqli_fetch_array($callStrViewQuery))
            {
                $varNoid = $recView['noid'];
                $Tanggal = $recView['tanggal'];
                $Jam = $recView['jam'];
                $DisplayDate = date("d-m-Y", strtotime($Tanggal)) . " " . $Jam;
                $TotalStruk = $recView['total_struk'];
                $TotalBayar = $recView['total_bayar'];
                $Kembalian = $recView['kembalian'];
                $Pembulatan = $recView['var_pembulatan'];
                $NominalPoin = $recView['var_poin'];
                $NominalVoucher = $recView['var_voucher'];
                $Diskon = $recView['var_diskon'];
                $NamaCustomer = $recView['nama_customer'];
                $KodeCustomer = $recView['kode_customer'];
                $NoRegister = $recView['kode_register'];
                ?>
                <div class="col-span-12 p-2 lg:col-span-12">
                    <div class="flex items-center justify-between py-2 px-4">
                        <h2 class="font-bold text-xl uppercase tracking-wide text-slate-700 dark:text-navy-100">Detail Struk</h2>
                        <div class="flex">
                        <a href="sales-invoice" class="btn space-x-2 mr-1 bg-warning font-medium text-white hover:bg-warning-focus focus:bg-warning-focus active:bg-warning-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">Kembali ke daftar</a>
                        <button class="btn space-x-2 mr-1 bg-success font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90" 
                        onclick="PrintDoc()">Print Detail</button>                                                
                        </div>
                    </div>

                    <div class="rounded-lg bg-white py-5 sm:py-6" id="PrintArea">
                        <div class="flex p-4">
                            <div class="w-1/3 p-4">            
                                <div class="">
                                    <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                                        <div class="bg-gradient-to-r from-green-400 to-blue-500 p-4">
                                            <h3 class="text-white text-lg font-bold">Informasi Struk</h3>
                                        </div>
                                        <div class="w-full p-6">
                                            <div class="mb-4">
                                                <span class="block text-gray-500 text-sm">No Struk:</span>
                                                <span class="text-xl font-semibold text-gray-800"><?php echo htmlspecialchars($recView['no_struk']); ?></span>
                                            </div>
                                            <div class="mb-4">
                                                <span class="block text-gray-500 text-sm">No Register:</span>
                                                <span class="text-xl font-semibold text-gray-800"><?php echo htmlspecialchars($recView['kode_register']); ?></span>
                                            </div>
                                            <div class="mb-4">
                                                <span class="block text-gray-500 text-sm">Tanggal Struk:</span>
                                                <span class="text-lg font-medium text-green-600"><?php echo htmlspecialchars($DisplayDate); ?></span>
                                            </div>
                                            <div class="mb-4">
                                                <span class="block text-gray-500 text-sm">Nama Kasir:</span>
                                                <span class="text-lg font-medium text-green-600"><?php echo $recView['kode_kasir']; ?> - <?php echo getNamaUser($recView['kode_kasir']); ?></span>
                                            </div>
                                            <div class="mb-4">
                                                <span class="block text-gray-500 text-sm">Total Struk:</span>
                                                <div class="text-lg font-medium text-green-600">Rp <?php echo number_format($TotalStruk,2,',','.'); ?></div>
                                            </div>
                                            <div class="mb-4">
                                                <span class="block text-gray-500 text-sm">Total Bayar:</span>
                                                <div class="text-lg font-medium text-green-600">Rp <?php echo number_format($TotalBayar,2,',','.'); ?></div>
                                            </div>
                                            <div class="mb-4">
                                                <span class="block text-gray-500 text-sm">Poin:</span>
                                                <div class="text-lg font-medium text-green-600">Rp <?php echo number_format($NominalPoin,2,',','.'); ?></div>
                                            </div>
                                            <div class="mb-4">
                                                <span class="block text-gray-500 text-sm">Voucher:</span>
                                                <div class="text-lg font-medium text-green-600">Rp <?php echo number_format($NominalVoucher,2,',','.'); ?></div>
                                            </div>
                                            <div class="mb-4">
                                                <span class="block text-gray-500 text-sm">Kembalian:</span>
                                                <div class="text-lg font-medium text-green-600">Rp <?php echo number_format($Kembalian,2,',','.'); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                            <div class="w-2/3 p-4"> 
                                <div class="">
                                    <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                                        <div class="bg-gradient-to-r from-orange-400 to-yellow-200 p-4">
                                            <h3 class="text-white text-lg font-bold">Detail Struk</h3>
                                        </div>
                                        <div class="flex">
                                            <div class="w-full p-6">
                                                <table id="table1" class="table-print is-hoverable w-full" width="100%">     
                                                    <thead>
                                                        <tr>
                                                            <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Kode Barang</th>
                                                            <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Nama Barang</th>
                                                            <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Harga Barang</th>
                                                            <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Qty</th>
                                                            <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Diskon</th>
                                                            <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Total</th>
                                                            <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Netto Sales</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        /*==========================*/
                                                        $StrViewDetail="SELECT * from dbo_detail where no_struk = '" . $_GET['rcode'] . "' order by noid ASC";   
                                                        $callStrViewDetail=mysqli_query($koneksidb, $StrViewDetail);
                                                        while($recDetail=mysqli_fetch_array($callStrViewDetail))
                                                        {
                                                            $NomorStruk = $recDetail['no_struk'];
                                                            $KodeKasir = $recDetail['posting_user'];
                                                            $KodeBarang = $recDetail['kode_barang'];
                                                            $NamaBarang = getNamaBarangBySkuBarang($recDetail['kode_barang']);
                                                            $RcodeBarang = getRandomCodeBySkuBarang($recDetail['kode_barang']);
                                                            $QtySales = $recDetail['qty_sales'];
                                                            $HargaSales = $recDetail['harga'];
                                                            $TotalSales = $recDetail['total_sales'];
                                                            $varDiskon = $recDetail['var_diskon'];
                                                            $Netto = $recDetail['netto_sales'];
                                                            ?>
                                                            <tr class="border-y border-transparent border-b-slate-200 dark:border-b-navy-500">
                                                                <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                                                                    <div onclick="window.open('detail.product.php?rcode=<?php echo $RcodeBarang; ?>', '_blank')" class="text-primary cursor-pointer hover:underline">
                                                                        <?php echo $KodeBarang; ?>
                                                                    </div>
                                                                </td>
                                                                <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $NamaBarang; ?></td>             
                                                                <td class="whitespace-nowrap px-4 py-3 sm:px-5 text-right"><?php   echo number_format($HargaSales,0); ?></td>   
                                                                <td class="whitespace-nowrap px-4 py-3 sm:px-5 text-right"><?php   echo number_format($QtySales,0); ?></td>         
                                                                <td class="whitespace-nowrap px-4 py-3 sm:px-5 text-right"><?php   echo number_format($varDiskon,0); ?></td>       
                                                                <td class="whitespace-nowrap px-4 py-3 sm:px-5 text-right"><?php   echo number_format($TotalSales,0); ?></td>         
                                                                <td class="whitespace-nowrap px-4 py-3 sm:px-5 text-right"><?php   echo number_format($Netto,0); ?></td>       
                                                            </tr>
                                                            <?php
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>                                                                                                                             
                                            </div>                                                                                  
                                        </div>
                                    </div>

                                    <div class="bg-white mt-2 shadow-lg rounded-xl overflow-hidden border border-gray-200">
                                        <div class="bg-gradient-to-r from-purple-400 to-blue-200 p-4">
                                            <h3 class="text-white text-lg font-bold">Detail Pembayaran</h3>
                                        </div>
                                        <div class="flex">
                                            <?php
                                            $StrViewPayment="SELECT * from dbo_payment where no_struk = '" . $_GET['rcode'] . "' order by noid ASC";   
                                            $callStrViewPayment=mysqli_query($koneksidb, $StrViewPayment);
                                            while($recPayment=mysqli_fetch_array($callStrViewPayment))
                                            {
                                            $KodeKasirBayar = $recPayment['posting_user'];
                                            $TotalBayar = $recPayment['total_bayar'];
                                            $JenisBayar = $recPayment['jenis_bayar'];
                                            $TanggalBayar = $recPayment['tanggal'];
                                            $PostingDate = $recPayment['posting_date'];
                                            $NamaEdc = $recPayment['nama_edc'];
                                            $NamaBank = $recPayment['nama_bank'];
                                            $RefNo = $recPayment['reff_code'];
                                            $ApprovalCode = $recPayment['approval_code'];
                                            $CardNo = $recPayment['card_number'];
                                            ?>                                            
                                            <div class="w-1/3 p-6">
                                                <div class="mb-4">
                                                    <span class="block text-gray-500 text-sm">Nama Kasir:</span>
                                                    <span class="text-xl font-semibold text-gray-800"><?php   echo $KodeKasirBayar; ?> - <?php   echo getNamaUser($KodeKasirBayar); ?></span>
                                                    <span class="block text-gray-500 text-sm">Tanggal Posting:</span>
                                                    <span class="text-xl font-semibold text-gray-800"><?php   echo $PostingDate; ?></span>
                                                </div>                                                                                                                              
                                            </div>       
                                            <div class="w-1/3 p-6">
                                                <div class="mb-4">
                                                    <span class="block text-gray-500 text-sm">Total Bayar:</span>
                                                    <span class="text-xl font-semibold text-gray-800"><?php echo number_format($TotalBayar,2,',','.'); ?></span>
                                                </div>                                                                                                                              
                                            </div>     
                                            <div class="w-1/3 p-6">
                                                <div class="mb-4">
                                                    <span class="block text-gray-500 text-sm">Jenis Pembayaran:</span>
                                                    <span class="text-xl font-semibold text-gray-800"><?php echo $JenisBayar; ?></span><br>
                                                    <span class="block text-gray-500 text-sm">Mesin Edc:</span>
                                                    <span class="text-xl font-semibold text-gray-800"><?php echo $NamaEdc; ?></span><br>
                                                    <span class="block text-gray-500 text-sm">Nama Bank:</span>
                                                    <span class="text-xl font-semibold text-gray-800"><?php echo $NamaBank; ?></span><br>
                                                    <span class="block text-gray-500 text-sm">No Kartu Pembeli:</span>
                                                    <span class="text-xl font-semibold text-gray-800"><?php echo $CardNo; ?></span><br>
                                                    <span class="block text-gray-500 text-sm">Approval Code:</span>
                                                    <span class="text-xl font-semibold text-gray-800"><?php echo $ApprovalCode; ?></span>
                                                </div>                                                                                                                              
                                            </div>                                              
                                            <?php
                                            }
                                            ?>                                                                        
                                        </div>
                                    </div>

                                    <div class="bg-white mt-2 shadow-lg rounded-xl overflow-hidden border border-gray-200">
                                        <div class="bg-gradient-to-r from-red-400 to-red-200 p-4">
                                            <h3 class="text-white text-lg font-bold">Detail Log Transaksi</h3>
                                        </div>
                                        <div class="flex">
                                            <div class="w-full p-6">
                                                <table id="table2" class="table-print is-hoverable w-full" width="100%">     
                                                    <thead>
                                                        <tr>
                                                            <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Log Description</th>
                                                            <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Tanggal Log</th>
                                                            <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Proses User</th>
                                                            <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Kode Barang</th>
                                                            <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Kode Barcode</th>
                                                            <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Kode Spv</th>
                                                            <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Kode Kasir</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        /*==========================*/
                                                        $StrViewDetail="SELECT * from dbo_log where no_struk = '" . $_GET['rcode'] . "' order by noid DESC";   
                                                        $callStrViewDetail=mysqli_query($koneksidb, $StrViewDetail);
                                                        while($recDetail=mysqli_fetch_array($callStrViewDetail))
                                                        {
                                                            $varLogDescription = $recDetail['log_description'];
                                                            $varLogType = $recDetail['log_type'];
                                                            $varCreateDate = $recDetail['create_at'];
                                                            $varLogUser = $recDetail['log_user'];
                                                            $varKodeBarang = $recDetail['kode_barang'];
                                                            $varKodeBarcode = $recDetail['kode_barcode'];
                                                            $varUomBarang = $recDetail['uom_barang'];
                                                            $varKodeSpv = $recDetail['kode_spv'];
                                                            $varKodeKasir = $recDetail['kode_kasir'];
                                                            
                                                            $NamaBarang = getNamaBarangBySkuBarang($recDetail['kode_barang']);
                                                            $RcodeBarang = getRandomCodeBySkuBarang($recDetail['kode_barang']);
                                                            switch ($varLogType) {
                                                                case 'INFO':
                                                                    $varStyle = "text-info";
                                                                    break;
                                                                case 'WARNING':
                                                                    $varStyle = "text-warning";
                                                                    break;
                                                                case 'DANGER':
                                                                    $varStyle = "text-error";
                                                                    break;
                                                                case 'SUCCESS':
                                                                    $varStyle = "text-success";
                                                                    break;
                                                            }
                                                            ?>
                                                            <tr class="border-y border-transparent border-b-slate-200 dark:border-b-navy-500 <?php   echo $varStyle; ?>">
                                                                <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $varLogDescription; ?></td>    
                                                                <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $varCreateDate; ?></td> 
                                                                <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $varLogUser; ?></td> 
                                                                <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $varKodeBarang; ?></td> 
                                                                <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $varKodeBarcode; ?></td> 
                                                                <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $varKodeSpv; ?></td>      
                                                                <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $varKodeKasir; ?></td>      
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
   function PrintDoc() {
    var toPrint = document.getElementById('PrintArea');
    var popupWin = window.open('', '_blank', 'width=800,height=600,location=no,left=50px');
    popupWin.document.open();
    popupWin.document.write('<html><title>Preview Voucher</title><link rel="stylesheet" type="text/css" href="assets/css/print.css" media="print"/></head><body onload="window.print();window.close();">')
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

    let tableLogTransaksi1 = new DataTable('#table2', {
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