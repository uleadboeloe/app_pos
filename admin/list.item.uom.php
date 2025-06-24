<?php
include_once "library/connection.php";
include_once "library/parameter.php";
include_once "library/fungsi.php";

$variable1 = $_POST['subkat'] ?? "";
$variable2 = $_POST['itemid'] ?? "";
?>
<table class="is-hoverable w-full" width="100%">
    <thead>            
        <tr class="mt-2">
            <th width="10%" class="whitespace-nowrap px-3 py-3 font-semibold uppercase bg-purple-300 text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Kode PLU</th>
            <th width="40%" class="whitespace-nowrap px-3 py-3 font-semibold uppercase bg-purple-300 text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Nama Barang</th>
            <th width="5%" class="whitespace-nowrap px-3 py-3 font-semibold uppercase bg-purple-300 text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Jenis Barang</th>
            <th width="10%" class="whitespace-nowrap px-3 py-3 font-semibold uppercase bg-purple-300 text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5"><input type="checkbox" class="form-check-input w-5 h-5 bg-primary mr-2" name="checkbox0" id="checkbox0" value="0" onclick="toggleCheckAll1(this)"> Kemasan 1</th>
            <th width="10%" class="whitespace-nowrap px-3 py-3 font-semibold uppercase bg-purple-300 text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5"><input type="checkbox" class="form-check-input w-5 h-5 bg-primary mr-2" name="checkbox0" id="checkbox0" value="0" onclick="toggleCheckAll2(this)"> Kemasan 2</th>
            <th width="10%" class="whitespace-nowrap px-3 py-3 font-semibold uppercase bg-purple-300 text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5"><input type="checkbox" class="form-check-input w-5 h-5 bg-primary mr-2" name="checkbox0" id="checkbox0" value="0" onclick="toggleCheckAll3(this)"> Kemasan 3</th>
        </tr>                            
    </thead>
    <tbody>
    <?php
    $strSQL = "SELECT noid,sku_barang,nama_barang,fl_timbang FROM dbo_barang where fl_active = 1";
    if($variable1!=""){
        $strSQL = $strSQL . " and sub_dept = '$variable1'";
    }
    if($variable2!=""){
        $strSQL = $strSQL . " and nama_barang like '%$variable2%'";
    }
    //$strSQL = $strSQL . " and sku_barang in (select sku_barang from dbo_price WHERE sku_barang NOT IN(SELECT sku_barang FROM dbo_promo_detail) group by sku_barang)";
    $strSQL = $strSQL . " and sku_barang in (select sku_barang from dbo_price group by sku_barang)";
    $strSQL = $strSQL . " order by nama_barang ASC";

    $CallstrSQL=mysqli_query($koneksidb, $strSQL);
    $x=1;
    while($result=mysqli_fetch_array($CallstrSQL))
    {
        $varNoid = $result['noid'];
        $varSkuBarang = $result['sku_barang'];
        $varNamaBarang = $result['nama_barang'];
        $varBarangTimbang = $result['fl_timbang'];
        if($varBarangTimbang == 1){
            $varStatusTimbang = "Timbang";
        }else{
            $varStatusTimbang = "Non Timbang";
        }
        
        if($x>0){
        ?>                
        <tr class="border border-transparent border-b-slate-200 dark:border-b-navy-500">
            <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                <input type="checkbox" style="display:none;" checked class="form-check-input mt-2 w-5 h-5 bg-primary" name="checkbox[]" id="checkbox<?php	echo $x;	?>" value="<?php	echo $x;	?>">              
                <p class="text-xs mt-2 line-clamp-1"><?php	echo $varSkuBarang;	?></p>
            </td>
            <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                <p class="text-xs mt-2 line-clamp-1"><?php	echo $varNamaBarang;	?></p>
            </td>
            <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                <p class="text-xs mt-2 line-clamp-1"><?php	echo $varStatusTimbang;	?></p>
            </td>            
            <?php
                $strSQLx = "SELECT noid,sku_barang,barcode,uom,harga_jual FROM dbo_price where sku_barang = '$varSkuBarang' order by noid ASC";
                $CallstrSQLx=mysqli_query($koneksidb, $strSQLx);
	            $JumBar=mysqli_num_rows($CallstrSQLx);
                $a=1;
                while($resultx=mysqli_fetch_array($CallstrSQLx))
                {
                    $varNoid = $resultx['noid'];
                    $varSkuBarang = $resultx['sku_barang'];
                    if($varBarangTimbang == 0){
                        $varKode = $resultx['barcode'];
                    }else{
                        $varKode = $resultx['sku_barang'];
                    }
                    
                    $varUom = $resultx['uom'];
                    
                    $isPromoExist = getPromoExist($varSkuBarang,$varUom);
                    $varID = $x.$a;
                    if($a>0){
                        $ClassName="row-check" . $a;
                        ?>
                        <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                            <?php
                            if($isPromoExist == ""){
                            ?>
                            <input type="checkbox" class="<?php	echo $ClassName;	?> form-check-input mt-2 w-5 h-5 bg-primary" name="checkboxx[]" id="checkboxx<?php	echo $varID;	?>" value="<?php	echo $varID;	?>">                            
                            <?php
                            }else{
                            ?>
                            <?php
                            }
                            ?>
                            <input type="hidden" class="form-control" id="txtSkuBarang<?php	echo $varID;	?>" name="txtSkuBarang<?php	echo $varID;	?>" value="<?php	echo $varSkuBarang;	?>">
                            <input type="hidden" class="form-control" id="txtBarcode<?php	echo $varID;	?>" name="txtBarcode<?php	echo $varID;	?>" value="<?php	echo $varKode;	?>">
                            <input type="hidden" class="form-control" id="txtUom<?php	echo $varID;	?>" name="txtUom<?php	echo $varID;	?>" value="<?php	echo $varUom;	?>">
                            <input type="hidden" class="form-control" id="txtNoid<?php	echo $varID;	?>" name="txtNoid<?php	echo $varID;	?>" value="<?php	echo $varNoid;	?>">  
                            <?php	echo $varUom;	?> # <?php	echo $isPromoExist;	?>
                        </td>
                        <?php
                    }$a++;
                }
                if($JumBar < 3){
                    for($b=$JumBar+1; $b<=3; $b++){
                    ?>
                    <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                        &nbsp;
                    </td>
                    <?php
                    }
                }
                ?>
        </tr>
        <?php
        }$x++;
    }
    ?>
    </tbody>
</table>
                             
<div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
    <label class="block">
        <span class="relative mt-1.5 flex">						
            <input type="submit" name="btnSubmit" id="btnSubmit" value="Tambah Item Promo"
            class="btn space-x-2 bg-warning font-medium text-white hover:bg-warning-focus focus:bg-warning-focus active:bg-warning-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
        </span>
    </label>                                    
</div>  
<script type="text/javascript" src="assets/js/autocomplete/jquery-1.11.1.min.js"></script>
<script>

function toggleCheckAll1(source) {
  const checkboxes = document.querySelectorAll(".row-check1");
  checkboxes.forEach((checkbox) => {
    checkbox.checked = source.checked;
  });
}

function toggleCheckAll2(source) {
  const checkboxes = document.querySelectorAll(".row-check2");
  checkboxes.forEach((checkbox) => {
    checkbox.checked = source.checked;
  });
}

function toggleCheckAll3(source) {
  const checkboxes = document.querySelectorAll(".row-check3");
  checkboxes.forEach((checkbox) => {
    checkbox.checked = source.checked;
  });
}
</script>