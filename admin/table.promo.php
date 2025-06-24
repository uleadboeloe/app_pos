<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include_once "library/connection.php";
include_once "library/parameter.php";
include_once "library/fungsi.php";
include_once "../lib_dbo/user_functions.php";

if(isset($_GET['detail'])){
    $StrViewQuery="SELECT * from dbo_promo where random_code = '" . $_GET['detail'] . "'";
    //echo $StrSalesDetails . "<hr>";     
    $callStrViewQuery=mysqli_query($koneksidb, $StrViewQuery);
    while($recView=mysqli_fetch_array($callStrViewQuery))
    {
        $varStatus = $recView['fl_active']; 
        $varParameterPromo = $recView['promo_parameter'];
    ?>
    <form name="formProses" name="frmListPromoDetail" id="frmListPromoDetail" method="post" action="setup-promo-detail" enctype="multipart/form-data">
        <div class="card p-5 mt-3">
        <input type="hidden" id="txtRandomCodes" name="txtRandomCodes" value="<?php echo $_GET['detail'];   ?>" readonly>
            <table id="table2" class="is-hoverable w-full" width="100%">     
                <thead>
                <tr>
                    <th class="whitespace-nowrap mt-2 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">PLU</th>
                    <th class="whitespace-nowrap mt-2 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Barcode Barang</th>
                    <th class="whitespace-nowrap mt-2 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Nama Barang</th>
                    <th class="whitespace-nowrap mt-2 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Satuan</th>
                    <?php
                    if($varParameterPromo == "E"){
                    ?> 
                    <th class="whitespace-nowrap mt-2 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Qty</th>
                    <th class="whitespace-nowrap mt-2 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Harga</th>
                    <?php
                    }
                    ?>
                    <?php
                    if($varStatus == 0){
                    ?>                    
                    <th class="whitespace-nowrap mt-2 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Action</th>
                    <?php
                    }
                    ?>
                </tr>
                </thead>
                <tbody>
                <?php
                /*==========================*/
                $StrViewQuery="SELECT * from dbo_promo_detail where random_code = '" . $_GET['detail'] . "'";
                //echo $StrSalesDetails . "<hr>";     
                $callStrViewQuery=mysqli_query($koneksidb, $StrViewQuery);
                while($recView=mysqli_fetch_array($callStrViewQuery))
                {
                    $varNoid = $recView['noid'];
                    $varDetailKode = $recView['kode_promo'];
                    $varDetailBarcode = $recView['barcode'];
                    $varDetailSKU = $recView['sku_barang'];
                    $varRandomCode = getRandomCodeBySkuBarang($varDetailSKU);
                    $varDetailNamaBarang = getNamaBarangBySkuBarang($varDetailSKU);
                    $varDetailKodeBarang = $recView['kode_barang'];
                    $varDetailUOM = $recView['uom'];
                    $varDetailQty = $recView['qty_jual'];
                    $varDetailHarga = $recView['harga_jual'];
                    ?>
                    <tr class="border-y border-transparent border-b-slate-200 dark:border-b-navy-500">  
                    <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                    <input type="checkbox" checked style="display:none;" class="form-check-input mt-2 w-5 h-5 bg-primary" name="checkboxxx[]" id="checkboxxx<?php	echo $varNoid;	?>" value="<?php	echo $varNoid;	?>">
                    <a href="detail-product@<?php   echo $varRandomCode; ?>" class="text-primary font-bold hover:text-warning"><?php   echo $varDetailSKU; ?></a></td>   
                    <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $varDetailBarcode; ?></td>    
                    <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $varDetailNamaBarang; ?></td>       
                    <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $varDetailUOM; ?></td>
                    <?php
                    if($varParameterPromo == "E"){
                    ?>       
                    <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $varDetailQty; ?></td>      
                    <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $varDetailHarga; ?></td>                         
                    <?php
                    }
                    ?>
                    <?php
                    if($varStatus == 0){
                    ?>
                    <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                    <div class="btn bg-error font-medium text-white hover:bg-error-focus focus:bg-error-focus active:bg-error-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90" onClick="remove(<?php echo $varNoid;   ?>);">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2" width="20" height="20" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 22c-.818 0-1.6-.33-3.163-.99C3.946 19.366 2 18.543 2 17.16V7m9 15V11.355M11 22c.617 0 1.12-.188 2-.563M20 7v5m-4 3l3 3m0 0l3 3m-3-3l-3 3m3-3l3-3M7.326 9.691L4.405 8.278C2.802 7.502 2 7.114 2 6.5s.802-1.002 2.405-1.778l2.92-1.413C9.13 2.436 10.03 2 11 2s1.871.436 3.674 1.309l2.921 1.413C19.198 5.498 20 5.886 20 6.5s-.802 1.002-2.405 1.778l-2.92 1.413C12.87 10.564 11.97 11 11 11s-1.871-.436-3.674-1.309M5 12l2 1m9-9L6 9" color="currentColor"/></svg> Hapus
                    </div>
                    </td>                         
                    <?php
                    }
                    ?>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>

            <label class="block inline-flex">
                <?php
                if($varStatus == 0){
                ?>
                <span class="relative mt-1.5 flex mr-2">
                    <input type="hidden" id="txtRandomCodes" name="txtRandomCodes" value="<?php echo $_GET['detail'];   ?>" readonly>						
                    <input type="submit" name="btnSubmit" id="btnSubmit" value="Hapus Semua" 
                    class="btn space-x-2 bg-error font-medium text-white hover:bg-error-focus focus:bg-error-focus active:bg-error-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
                </span> 
                <span class="relative mt-1.5 flex">						
                    <input type="submit" name="btnSubmit" id="btnSubmit" value="Aktifkan Promo"
                    class="btn space-x-2 bg-info font-medium text-white hover:bg-info-focus focus:bg-info-focus active:bg-info-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
                </span>                        
                <?php
                }else{
                ?>
                <span class="relative mt-1.5 flex mr-2">						
                    <input type="submit" name="btnSubmit" id="btnSubmit" value="Non Aktifkan Promo"
                    class="btn space-x-2 bg-warning font-medium text-white hover:bg-warning-focus focus:bg-warning-focus active:bg-warning-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
                </span>                          
                <?php                    
                }
                ?>                 
            </label>  
        </div>
    </form>

    <?php
    }
}
?>

<script type="text/javascript" src="assets/js/autocomplete/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="assets/js/autocomplete/jquery-ui.min.js"></script>
<script type="text/javascript" src="assets/js/autocomplete/jquery.select-to-autocomplete.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function (){
    let tableListData = new DataTable('#table2', {
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