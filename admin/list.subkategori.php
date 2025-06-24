<?php
include_once "library/connection.php";
include_once "library/parameter.php";

$variable = $_POST['kat'];
//echo $variable;
?>
<label class="block">
    <span class="text-purple-500 font-bold">Sub Kategori Produk<div class="badge rounded-full bg-primary/10 text-primary dark:bg-accent-light/15 dark:text-accent-light">Wajib</div></span>
    <span class="relative mt-1.5 flex">
        <select id="txtSubKategoriProduk" name="txtSubKategoriProduk" required 
        class="form-select h-12 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs+ hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">
            <option value="">Pilih Sub Kategori Produk</option> 
            <?php
            $strSQL = "SELECT * FROM dbo_subkategori where left(kode_subkategori,4) = '$variable'";
            $CallstrSQL=mysqli_query($koneksidb, $strSQL);
            while($result=mysqli_fetch_array($CallstrSQL))
            {
            $varNoid = $result['noid'];
            $varKode = $result['kode_subkategori'];
            $varLabelName = $result['nama_subkategori'];
            ?>
            <option value="<?php    echo $varKode;  ?>"><?php    echo $varLabelName;  ?></option>
            <?php
            }
            ?>                                            
        </select>
    </span>
</label> 
