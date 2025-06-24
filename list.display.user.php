<?php
$datedb = date("Y-m-d H:i:s");
include_once "admin/library/connection.php";
include_once "admin/library/parameter.php";
include_once "admin/library/fungsi.php";
?>
<table class="w-full">
    <tr>
        <th class="bg-primary text-xs text-white p-2">NO</th>
        <th class="bg-primary text-xs text-white p-2">NAMA BARANG</th>
        <th class="bg-primary text-xs text-white p-2">HARGA</th>
        <th class="bg-primary text-xs text-white p-2">QTY</th>
        <th class="bg-primary text-xs text-white p-2">TOTAL</th>
    </tr>
    <?php
    $i=1;
    $TempQty=0;
    $TempTotalHarga=0;
    $StrViewQuery="SELECT * from temp_transaksi where status='CURRENT' order by id ASC";
    $callStrViewQuery=mysqli_query($koneksidb, $StrViewQuery);
    while($recView=mysqli_fetch_array($callStrViewQuery))
    {
        $varNoid = $recView['id'];
        $varNamaBarang = $recView['nama_barang'];    
        $varHargaJual = $recView['harga_jual'];    
        $varDiskon = $recView['disc'];       
        $varUom = $recView['uom'];    
        $varQty = $recView['qty'];
        $varTotalHarga = $recView['total_harga'];
        $TempQty+= $varQty;
        $TempTotalHarga+= $varTotalHarga;

        if($varTotalHarga == 0)
        {
            $Style="text-warning";
        }else{
            $Style="text-primary";
        }


        if($i>0){
            ?>
            <tr>
                <td class="font-bold text-xs p-2"><?php   echo $i;  ?></td>
                <td class="font-bold text-xs p-2 <?php   echo $Style;  ?>"><?php   echo $recView['nama_barang'];  ?><br>
                <span class="text-slate-800">Harga : <?php   echo number_format($recView['harga_jual'],2);  ?></span></td>
                <td class="text-xs p-2 text-right"><?php   echo $recView['qty'];  ?> / <?php   echo $recView['uom'];  ?></td>
                <td class="text-xs p-2 text-right"><?php   echo number_format($recView['total_harga'],2);  ?></td>
            </tr>
            <?php
            if($varDiskon > 0)
            {
            ?>
            <tr>
                <td colspan="4" class="font-bold text-xs text-right">DISKON</td>
                <td class="font-bold text-right"><?php   echo number_format($recView['disc'],2);  ?></td>
            </tr>
            <?php
            }
        }$i++;
    }
    ?>
    <tbody>
        <?php
        for($i = 0; $i < 12; $i++)
            echo '<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>';
        ?>
    </tbody>    
    <tr>
        <td class="bg-primary text-white p-2" colspan="3">TOTAL BELANJA (<?php   echo $TempQty;  ?>)</td>
        <td class="bg-primary text-white p-2 text-right" colspan="2"><?php   echo number_format($TempTotalHarga,2);  ?></td>
    </tr>                        
</table>
