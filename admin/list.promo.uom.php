<?php
include_once "library/connection.php";
include_once "library/parameter.php";
include_once "library/fungsi.php";

$variable = $_POST['noid'];
$varpromo = $_POST['promoid'];
//echo $variable . "#" . $varpromo;
switch ($varpromo)
{
    case "5":
        $HargaNormal = getPriceByKodeBarcode($variable);
        ?>
        <label class="block">
            <span class="text-purple-500 font-bold">Normal Price<div class="badge rounded-full bg-warning/10 text-warning dark:bg-accent-light/15 dark:text-accent-light">informasi harga</div></span>
            <span class="relative mt-1.5 flex">					
                <input placeholder="Masukan Qty Free Item" type="number" id="txtNormalPrice" name="txtNormalPrice" value="<?php echo $HargaNormal;  ?>" readonly
                class="form-input peer h-12 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"/>
                <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                    <i class="fa-regular fa-building text-base"></i>
                </span>
            </span> 
        </label>     
        <label class="block">
            <span class="text-purple-500 font-bold">Harga Jual<div class="badge rounded-full bg-primary/10 text-primary dark:bg-accent-light/15 dark:text-accent-light">Wajib</div></span>
            <span class="relative mt-1.5 flex">			
            <input type="hidden" id="txtQtyPromo" name="txtQtyPromo" value="0">				
            <input type="hidden" id="txtFreeItemValue" name="txtFreeItemValue" value="0">			
                <input placeholder="Masukan Harga Jual" type="number" id="txtPromoValue" name="txtPromoValue" value="0"
                class="form-input peer h-12 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"/>
                <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                    <i class="fa-regular fa-building text-base"></i>
                </span>
            </span> 
        </label> 
        <?php
    break;
    case "4":

    break;    
    default:
    ?>
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-1 sm:col-span-2">
        <label class="block">
            <span class="text-purple-500 font-bold">Pilih Kode Barang Free<div class="badge rounded-full bg-primary/10 text-primary dark:bg-accent-light/15 dark:text-accent-light">Wajib</div></span>
            <span class="relative mt-1.5 flex">		
                <input type="hidden" id="txtPromoValue" name="txtPromoValue" placeholder="txtPromoValue" value="0">					
                <select id="txtFreeItemValue" name="txtFreeItemValue" required 
                class="form-select h-12 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs+ hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">
                    <option value="">Kode Pilih Kode Barang Free</option>
                    <?php
                    $strSQL="SELECT a.noid,a.sku_barang,a.barcode,a.uom,a.harga_jual,b.nama_barang,b.fl_timbang FROM `dbo_price` a join `dbo_barang` b on a.sku_barang = b.sku_barang where a.sku_barang = '" . $variable . "' and a.fl_active = 1 and b.fl_timbang=0 order by a.barcode DESC limit 1";
                    $CallstrSQL=mysqli_query($koneksidb, $strSQL);
                    while($rec=mysqli_fetch_array($CallstrSQL)){
                    ?>
                    <option value="<?php    echo $rec['barcode']; ?>"><?php    echo $rec['barcode']; ?> - <?php    echo $rec['nama_barang']; ?> - <?php    echo $rec['uom']; ?> - <?php    echo number_format($rec['harga_jual'],2); ?></option>
                    <?php
                    }
                    ?>                                                 
                </select>
            </span> 
        </label>       
    </div>

    <label class="block">
        <span class="text-purple-500 font-bold">Qty Free Item<div class="badge rounded-full bg-primary/10 text-primary dark:bg-accent-light/15 dark:text-accent-light">Wajib</div></span>
        <span class="relative mt-1.5 flex">						
            <input placeholder="Masukan Qty Free Item" type="number" id="txtQtyPromo" name="txtQtyPromo" value="1" readonly require
            class="form-input peer h-12 w-full rounded-lg border border-slate-300 bg-slate-200 px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"/>
            <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                <i class="fa-regular fa-building text-base"></i>
            </span>
        </span> 
    </label> 
    <?php
    break;
}
?>