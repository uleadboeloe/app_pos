<?php
include_once "library/connection.php";
include_once "library/parameter.php";

$variable = $_POST['noid'];

$strSQL = "SELECT * FROM dbo_global where noid = $variable";
$CallstrSQL=mysqli_query($koneksidb, $strSQL);
while($result=mysqli_fetch_array($CallstrSQL))
{
$varNoid = $result['noid'];
$varLabelGlobalName = $result['label_name'];
$varParameter = $result['parameter'];
$varKategoriGlobal = $result['kategori_global'];
    //echo $varNoid . "#" . $varLabelGlobalName . "#" . $varParameter . "#" . $varKategoriGlobal . "<br>";
	switch ($varParameter)
	{
		case "A": //Diskon Rupiah - Done
            ?>
            <label class="block">
                <span class="text-purple-500 font-bold">Qty Pembelian <div class="badge rounded-full bg-primary/10 text-primary dark:bg-accent-light/15 dark:text-accent-light">Wajib</div></span>
                <span class="relative mt-1.5 flex">						
                    <input placeholder="Masukan Minimum Pembelian" type="number" id="txtPromoKriteria" name="txtPromoKriteria" value="1" required readonly
                    class="form-input peer h-12 w-full rounded-lg border border-slate-300 bg-slate-200 px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"/>
                    <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                        <i class="fa-regular fa-building text-base"></i>
                    </span>
                </span> 
            </label>   

            <label class="block">
                <span class="text-purple-500 font-bold">Value Diskon Rupiah<div class="badge rounded-full bg-primary/10 text-primary dark:bg-accent-light/15 dark:text-accent-light">Wajib</div></span>
                <span class="relative mt-1.5 flex">	
                    <input type="hidden" id="txtVariabelPromo" name="txtVariabelPromo" value="RUPIAH" required>
                    <input type="hidden" id="txtQtyPromo" name="txtQtyPromo" value="0" required>		
                    <input type="hidden" id="txtFreeItemValue" name="txtFreeItemValue" value="">	
                    <input placeholder="Masukan Value Rupiah" type="text" id="txtPromoValue" name="txtPromoValue" required
                    class="form-input peer h-12 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"/>
                    <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                        <i class="fa-regular fa-building text-base"></i>
                    </span>
                </span> 
            </label>  
            <?php
        break;   
		case "B": //Diskon Persentase - Done
            ?>
            <label class="block">
                <span class="text-purple-500 font-bold">Qty Pembelian <div class="badge rounded-full bg-primary/10 text-primary dark:bg-accent-light/15 dark:text-accent-light">Wajib</div></span>
                <span class="relative mt-1.5 flex">						
                    <input placeholder="Masukan Minimum Pembelian" type="number" id="txtPromoKriteria" name="txtPromoKriteria" value="1" required readonly
                    class="form-input peer h-12 w-full rounded-lg border border-slate-300 bg-slate-200 px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"/>
                    <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                        <i class="fa-regular fa-building text-base"></i>
                    </span>
                </span> 
            </label>   

            <label class="block">
                <span class="text-purple-500 font-bold">Value Diskon Persentase<div class="badge rounded-full bg-primary/10 text-primary dark:bg-accent-light/15 dark:text-accent-light">Wajib</div></span>
                <span class="relative mt-1.5 flex">		
                    <input type="hidden" id="txtVariabelPromo" name="txtVariabelPromo" value="PERSEN" required>
                    <input type="hidden" id="txtQtyPromo" name="txtQtyPromo" value="0" required>		
                    <input type="hidden" id="txtFreeItemValue" name="txtFreeItemValue" value="">				
                    <input placeholder="Masukan Value Persentase" maxlength="2" type="text" id="txtPromoValue" name="txtPromoValue" required
                    class="form-input peer h-12 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"/>
                    <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                        <i class="fa-regular fa-building text-base"></i>
                    </span>
                </span> 
            </label>            
            <?php            
        break;    
        case "C": //PROMO FREE ITEM - Done
            ?>
            <input type="hidden" id="txtVariabelPromo" name="txtVariabelPromo" value="FREEITEM" required>
            <label class="block">
                <span class="text-purple-500 font-bold">Qty Pembelian <div class="badge rounded-full bg-primary/10 text-primary dark:bg-accent-light/15 dark:text-accent-light">Wajib</div></span>
                <span class="relative mt-1.5 flex">						
                    <input placeholder="Masukan Minimum Pembelian" type="number" id="txtPromoKriteria" name="txtPromoKriteria" value="1" readonly required
                    class="form-input peer h-12 w-full rounded-lg border border-slate-300 bg-slate-200 px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"/>
                    <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                        <i class="fa-regular fa-building text-base"></i>
                    </span>
                </span> 
            </label>

            <label class="block">
                <span class="text-purple-500 font-bold">PLU Barang Promo<div class="badge rounded-full bg-primary/10 text-primary dark:bg-accent-light/15 dark:text-accent-light">Wajib</div></span>
                <span class="relative mt-1.5 flex">						
                    <input placeholder="Masukan Kode PLU Produk Promo" type="number" id="txtSkuPromoValue" name="txtSkuPromoValue" required
                    class="form-input peer h-12 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"/>
                    <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                        <i class="fa-regular fa-building text-base"></i>
                    </span>
                </span> 
            </label> 
            <?php
        break;    
        case "D": //PROMO BUY 1 GET 1 - Done
            ?>
            <label class="block">
                <span class="text-purple-500 font-bold">Qty Pembelian <div class="badge rounded-full bg-primary/10 text-primary dark:bg-accent-light/15 dark:text-accent-light">Wajib</div></span>
                <span class="relative mt-1.5 flex">				
                    <input type="hidden" id="txtPromoValue" name="txtPromoValue" value="0">	
                    <input type="hidden" id="txtFreeItemValue" name="txtFreeItemValue" value="FREETHIS">
                    <input type="hidden" id="txtQtyPromo" name="txtQtyPromo" value="1"> 
                    <input type="hidden" id="txtVariabelPromo" name="txtVariabelPromo" value="BUY1GET1" required>	
                    <input type="hidden" id="txtSkuPromoValue" name="txtSkuPromoValue" value="" required>
                    
                    <input placeholder="Masukan Minimum Pembelian" type="number" id="txtPromoKriteria" name="txtPromoKriteria" value="1" readonly required
                    class="form-input peer h-12 w-full rounded-lg border border-slate-300 bg-slate-200 px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"/>
                    <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                        <i class="fa-regular fa-building text-base"></i>
                    </span>
                </span> 
            </label> 
            <?php
        break;            
        case "E": //Price Level - Done
            ?>
            <label class="block">
                <span class="text-purple-500 font-bold">Qty Pembelian <div class="badge rounded-full bg-primary/10 text-primary dark:bg-accent-light/15 dark:text-accent-light">Wajib</div></span>
                <span class="relative mt-1.5 flex">						
                    <input placeholder="Masukan Minimum Pembelian" type="number" id="txtPromoKriteria" name="txtPromoKriteria" value="1" required
                    class="form-input peer h-12 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"/>
                    <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                        <i class="fa-regular fa-building text-base"></i>
                    </span>
                </span> 
            </label>   

            <label class="block">
                <span class="text-purple-500 font-bold">Sku Barang Promo<div class="badge rounded-full bg-primary/10 text-primary dark:bg-accent-light/15 dark:text-accent-light">Wajib</div></span>
                <span class="relative mt-1.5 flex">		
                    <input type="hidden" id="txtVariabelPromo" name="txtVariabelPromo" value="PRICELEVEL" required>				
                    <select id="txtSkuPromoValue" name="txtSkuPromoValue" required placeholder="Masukan Kode Barcode / Nama Barang"
                    class="form-select h-12 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs+ hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">
                        <option value="">Kode Sku Barang Promo</option>
                        <?php
                        $strSQL="SELECT noid,sku_barang,kode_barang,nama_barang,barcode FROM `dbo_barang` where fl_active = 1";
                        $CallstrSQL=mysqli_query($koneksidb, $strSQL);
                        while($rec=mysqli_fetch_array($CallstrSQL)){
                        ?>
                        <option value="<?php    echo $rec['barcode']; ?>"><?php    echo $rec['barcode']; ?> - <?php    echo $rec['nama_barang']; ?></option>
                        <?php
                        }
                        ?>                                                 
                    </select>
                </span> 
            </label>
            <?php
        break;  
  
        case "F": //PROMO BUY 2 GET 1 - Done
            ?>
            <label class="block">
                <span class="text-purple-500 font-bold">Qty Pembelian <div class="badge rounded-full bg-primary/10 text-primary dark:bg-accent-light/15 dark:text-accent-light">Wajib</div></span>
                <span class="relative mt-1.5 flex">				
                    <input type="hidden" id="txtPromoValue" name="txtPromoValue" value="0">	
                    <input type="hidden" id="txtFreeItemValue" name="txtFreeItemValue" value="FREETHIS">
                    <input type="hidden" id="txtQtyPromo" name="txtQtyPromo" value="1"> 
                    <input type="hidden" id="txtVariabelPromo" name="txtVariabelPromo" value="BUY2GET1" required>	
                    <input type="hidden" id="txtSkuPromoValue" name="txtSkuPromoValue" value="" required>
                    
                    <input placeholder="Masukan Minimum Pembelian" type="number" id="txtPromoKriteria" name="txtPromoKriteria" value="2" readonly required
                    class="form-input peer h-12 w-full rounded-lg border border-slate-300 bg-slate-200 px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"/>
                    <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                        <i class="fa-regular fa-building text-base"></i>
                    </span>
                </span> 
            </label> 
            <?php
        break;          
        /*NON AKTIF SEMENTARA
        case "F":
            ?>
            <label class="block">
                <span class="text-purple-500 font-bold">Qty Pembelian <div class="badge rounded-full bg-primary/10 text-primary dark:bg-accent-light/15 dark:text-accent-light">Wajib</div></span>
                <span class="relative mt-1.5 flex">						
                    <input placeholder="Masukan Minimum Pembelian" type="text" id="txtPromoKriteria" name="txtPromoKriteria" required
                    class="form-input peer h-12 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"/>
                    <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                        <i class="fa-regular fa-building text-base"></i>
                    </span>
                </span> 
            </label>

            <label class="block">
                <span class="text-purple-500 font-bold">Value Diskon Rupiah<div class="badge rounded-full bg-primary/10 text-primary dark:bg-accent-light/15 dark:text-accent-light">Wajib</div></span>
                <span class="relative mt-1.5 flex">		
                    <input type="hidden" id="txtKriteriaPromo" name="txtKriteriaPromo" value="TEBUSMURAH" required>				
                    <input placeholder="Masukan Value Rupiah" type="text" id="txtPromoValue" name="txtPromoValue" required
                    class="form-input peer h-12 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"/>
                    <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                        <i class="fa-regular fa-building text-base"></i>
                    </span>
                </span> 
            </label>  
            <?php
        break; 
        */                                                           
    }
}
?>
   
<script type="text/javascript" src="assets/js/autocomplete/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="assets/js/autocomplete/jquery-ui.min.js"></script>
<script type="text/javascript" src="assets/js/autocomplete/jquery.select-to-autocomplete.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
(function($){
	$(function(){
	  $('select[id="txtSkuPromoValue"]').selectToAutocomplete();
	});
})(jQuery);
</script>
