<?php
session_start();
include_once "library/connection.php";
include_once "library/parameter.php";
include_once "library/fungsi.php";
?>
<div class="grid grid-cols-1 gap-4 sm:grid-cols-3 sm:gap-5 lg:gap-6">
<?php
if(isset($_GET['offset']) && isset($_GET['limit'])) {
    $limit = $_GET['limit'];
    $offset = $_GET['offset'];

    $txtProdukName = $_GET['txtProdukName'];
    $txtDeptCode = $_GET['txtDeptCode'];
    $txtKategoriCode = $_GET['txtKategoriCode'];
    $txtSubKategoriCode = $_GET['txtSubKategoriCode'];
    $txtJenisTimbang = $_GET['txtJenisTimbang'];
    $txtKodeBarcode = $_GET['txtKodeBarcode'];

    $strSQL1 = "SELECT * FROM dbo_barang where fl_active = 1";
    if($txtProdukName != "") {
        $strSQL1 = $strSQL1 . " and (sku_barang like '%" . $txtProdukName . "%' or kode_barang like '%" . $txtProdukName . "%' or nama_barang like '%" . $txtProdukName . "%')";
    }
    if($txtDeptCode != "") {
        $strSQL1 = $strSQL1 . " and divisi = '" . $txtDeptCode . "'";
    }
    if($txtKategoriCode != "") {
        $strSQL1 = $strSQL1 . " and dept = '" . $txtKategoriCode . "'";
    }
    if($txtSubKategoriCode != "") {
        $strSQL1 = $strSQL1 . " and sub_dept = '" . $txtSubKategoriCode . "'";
    }
    if($txtJenisTimbang != "") {
        $strSQL1 = $strSQL1 . " and fl_timbang = '" . $txtJenisTimbang . "'";
    }
    if($txtKodeBarcode != "") {
        $strSQL1 = $strSQL1 . " and (barcode like '%" . $txtKodeBarcode . "%' or barcode2 like '%" . $txtKodeBarcode . "%' or barcode3 like '%" . $txtKodeBarcode . "%')";
    }
    $strSQL1 = $strSQL1 . " order by noid DESC LIMIT {$limit} OFFSET {$offset}";

    $CallstrSQL1=mysqli_query($koneksidb, $strSQL1);
    $FoundRecord1=mysqli_num_rows($CallstrSQL1);
    while($recSql=mysqli_fetch_array($CallstrSQL1)){
        $Noid = $recSql['noid'];
        $RandomCode = $recSql['random_code'];
        $SKUBarang = $recSql['sku_barang'];
        $KodeBarang = $recSql['kode_barang'];
        $NamaBarang = $recSql['nama_barang'];
        $KodeBarcode1 = $recSql['barcode'];
        $KodeBarcode2 = $recSql['barcode2'];
        $KodeBarcode3 = $recSql['barcode3'];
        $KeteranganBarang = $recSql['keterangan_1'] . "<hr>" . $recSql['keterangan_2'];
        $ImagesProduk = $recSql['images_produk'];
        if($ImagesProduk == "") {
            $ImagesProduk = "assets/images/logo.png";
        }else{
            $ImagesProduk = "fileimages/" . $ImagesProduk;
        }
        ?>
        <div class="card lg:flex-row">
            <img src="<?php   echo $ImagesProduk; ?>" alt="image" class="h-48 w-full shrink-0 rounded-t-lg bg-cover bg-center object-cover object-center lg:h-auto lg:w-48 lg:rounded-t-none lg:rounded-l-lg"/>
            <div class="flex w-full grow flex-col px-4 py-3 sm:px-5">
            <div class="text-lg line-clamp-2 font-medium text-slate-700 hover:text-primary focus:text-primary dark:text-navy-100 dark:hover:text-accent-light dark:focus:text-accent-light">
                <?php   echo $NamaBarang; ?>
            </div>
            <p class="mt-1 line-clamp-3">
                <?php   echo $KeteranganBarang; ?>
            </p>
            <div class="grow">
                <div class="mt-2 flex items-center text-xs">
                <span class="line-clamp-1">Sku Barang / PLU</span>
                <div class="mx-3 my-1 w-px self-stretch bg-slate-200 dark:bg-navy-500"></div>
                <span class="shrink-0 text-slate-400 dark:text-navy-300"><?php   echo $SKUBarang; ?></span>
                <div class="mx-3 my-1 w-px self-stretch bg-slate-200 dark:bg-navy-500"></div>
                <span class="shrink-0 text-slate-400 dark:text-navy-300"><?php   echo $KodeBarang; ?></span>
                </div>
            </div>
            <div class="grow">
                <div class="mt-2 flex items-center text-xs">
                <span class="line-clamp-1">Kode Barcode</span>
                <div class="mx-3 my-1 w-px self-stretch bg-slate-200 dark:bg-navy-500"></div>
                <span class="shrink-0 text-slate-400 dark:text-navy-300"><?php   echo $KodeBarcode1; ?></span>
                </div>
            </div>                    
            <div class="mt-1 flex justify-end">
                <a href="detail.product.php?rcode=<?php   echo $RandomCode; ?>" target="_blank" class="btn px-2.5 py-1.5 font-medium text-primary hover:bg-primary/20 focus:bg-primary/20 active:bg-primary/25 dark:text-accent-light dark:hover:bg-accent-light/20 dark:focus:bg-accent-light/20 dark:active:bg-accent-light/25">
                Detail Produk
                </a>
            </div>
            </div>
        </div>
        <?php
    }
}
?>
</div>