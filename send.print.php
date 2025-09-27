<?php
session_start();
include("lib/mysql_pdo.php");
include("lib/mysql_connect.php");	
include("lib/general_lib.php");
include "admin/library/connection.php";
include "admin/library/fungsi.php";
include "admin/library/parameter.php";

$Timestamp = str_replace(":","",$datedb);
$Timestamp = str_replace(" ","-",$Timestamp);
$Timestampx = str_replace("-","",$Timestamp);

$SourcePages="PRINTOUT";
$InitPages = "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" name="viewport">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-touch-fullscreen" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<title>WINNER</title>
<link rel="stylesheet" href="css/custom.css">
<link rel="stylesheet" href="css/card.css">
<script src="https://cdn.tailwindcss.com"></script>
<style>
.not-selectable {
	-webkit-touch-callout: none;
	-webkit-user-select: none;
	-khtml-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
}
</style>
<body>
<div class="card-contains">
<?php
//echo "MASUK SEND PRINT";
if(isset($_SESSION['SESS_user_id'])){
    $strSQL = "SELECT * FROM dbo_print_proses where proses_user = '" . $_SESSION['SESS_user_id'] . "' order by noid DESC limit 1";
    $CallstrSQL=mysqli_query($koneksidb, $strSQL);
    $FoundRecord=mysqli_num_rows($CallstrSQL);
    while($result=mysqli_fetch_array($CallstrSQL)){
        $kode_kasir = $result['proses_user'];
        $NoBon = $result['noid_proses'];
        $RecSourceID = $result['source_proses'];
        ?>
        <input type="hidden" name="txtUserIDDevice" id="txtUserIDDevice" value="<?php   echo $NoBon; ?>">
        <input type="hidden" name="txtUserIDDevice" id="txtUserIDDevice" value="<?php   echo $kode_kasir; ?>">
        <input type="hidden" name="txtPushIDDevice" id="txtPushIDDevice" value="<?php   echo $RecSourceID; ?>">
        <?php
        $strQuery = $db->prepare("SELECT * FROM dbo_header WHERE kode_kasir = :kode_kasir and no_struk = :nofaktur and is_voided in ('0','2')");
        $strQuery->execute(array(':nofaktur' => $NoBon, ':kode_kasir' => $kode_kasir));
        $rec = $strQuery->fetchAll();
        $JumlahRecord = count($rec);

        if($JumlahRecord > 0){
            $PostingDate = $rec[0]["posting_date"];
            $KodeStore = $rec[0]["kode_store"];
            $TangalSales = $rec[0]["tanggal"];
            $JamSales = $rec[0]["jam"];
            $ShowTanggalSales = date("d-m-Y", strtotime($TangalSales)) . " " . date("H:i:s", strtotime($rec[0]["jam"]));
            //$ShowTanggalSales = "2025-07-24 15:30:12";
            $KodeKasir = $rec[0]["kode_kasir"];
            $NamaKasir = $rec[0]["nama_kasir"];
            $TotalBayar = $rec[0]["total_bayar"];
            $TotalStruk = $rec[0]["total_struk"];
            $Kembalian = $rec[0]["kembalian"];
            $Pembulatan = $rec[0]["var_pembulatan"];
            $PoinMember = $rec[0]["var_poin"];
            $Voucher = $rec[0]["var_voucher"];
            $JenisBayar = $rec[0]["jenis_bayar"];
            $ValueVoid = $rec[0]["value_void"] ?? 0;
            $ValueVoid = $ValueVoid*-1;
            $NamaKartu = $rec[0]["nama_kartu"] ?? "TUNAI";
            $CustomerCode = $rec[0]["kode_customer"];
            $TotalQtySales = getTotalItemPerStruk($NoBon);

            $CheckMemberHNI = CheckMemberByPhone($CustomerCode);
            $dataMemberid = json_decode($CheckMemberHNI, true);

            $codex = $dataMemberid['code'];
            $msgx = $dataMemberid['msg'] ?? "";
            $datax = $dataMemberid['data'];
            if($codex == "000"){
                $MemberID = $datax['customer_id'];
                $MemberName = $datax['customer_name'];
                $MemberName = str_replace("'","`",$MemberName);
                $PhoneByMemberid = $datax['customer_hp'];
                $varStatusMember = "MEMBER_HNI";
            }else{
                // Query untuk menjumlahkan nilai voucher berdasarkan kode_voucher
                $stmt = $db->prepare("SELECT nama_member,nomor_kontak FROM dbo_member WHERE kode_member = :kode_member");
                //SELECT nominal FROM dbo_voucher WHERE kode_voucher = '170809469642a16e-0003'
                $stmt->bindParam(':kode_member', $cust_code);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                $MemberID = $cust_code;
                $MemberName = $result['nama_member'] ?? '';
                $PhoneByMemberid = $result['nomor_kontak'] ?? '';
                $varStatusMember = "MEMBER TOKO";
            }

            $strQueryPoin = $db->prepare("SELECT * FROM dbo_poin_member WHERE refer_id = :nofaktur");
            $strQueryPoin->execute(array(':nofaktur' => $NoBon));
            $recPoin = $strQueryPoin->fetchAll();

            $NilaiPoin = $recPoin[0]["nilai_poin"] ?? 0;  
            
            $NamaStore = getStoreName($KodeStore);
            $HeaderStruk = getHeaderStruk($KodeStore);
            $FooterStruk = getFooterStruk($KodeStore);
            ?>            
            <!-- End of Header -->
            <div class="row-fluid" >
                <div align="center"> 
                    <img src="<?php echo $ImagesLogo;    ?>" alt="Logo InsanPOS" width="30%">
                    <div style="color:#000;font-size:12px;">
                        <?php echo $NamaStore;    ?>
                    </div>
                    <div style="color:#000;font-size:12px;">
                        <?php echo $HeaderStruk;    ?>
                    </div>
                    <hr>
                    <table width="100%" style="color:#000;font-size:12px;">
                        <tr>
                            <td width="50%" style="color:#000;font-size:10px;">Nomor Struk :</td>
                            <td width="50%" align="right" style="color:#000;font-size:10px;"><?php echo $NoBon;    ?></td>
                        </tr>                            
                        <tr>
                            <td width="50%" style="color:#000;font-size:10px;">Tanggal :</td>
                            <td width="50%" align="right" style="color:#000;font-size:10px;"><?php echo $ShowTanggalSales;    ?></td>
                        </tr>
                        <?php
                        if($CustomerCode != ""){
                            ?>
                            <tr>
                                <td width="50%" style="color:#000;font-size:10px;">Customer : </td>
                                <td width="50%" align="right" style="color:#000;font-size:10px;"><?php echo $varStatusMember;    ?></td>
                            </tr> 
                            <?php
                        }
                        ?>   
                    </table> 
                    <hr>
                    <div>
                        <table id="tableData" width="100%" style="color:#000;font-size:12px;">
                            <tr>
                                <th>DESKRIPSI</th>
                                <th>QTY</th>
                                <th>TOTAL</th>
                            </tr>
                            <?php
                            $TotalDiskon = 0;
                            $strQueryDetail = $db->prepare("SELECT * FROM dbo_detail WHERE no_struk = :nofaktur");
                            $strQueryDetail->execute(array(':nofaktur' => $NoBon));
                            $recDetail = $strQueryDetail->fetchAll();
                            foreach ($recDetail as $row) {
                                
                                $QtyVoidLine=$row['qty_voided'] ?? 0;
                                if($row['var_diskon'] > 0){
                                    $TotalDiskon+=$row['var_diskon'];
                                    $varDiskon = "#<b>Diskon : (" . number_format($row['var_diskon'],2) . ")</b>";
                                    $persenDiskon = ($row['harga'] > 0) ? round(($row['var_diskon'] / $row['harga']) * 100, 2) : 0;
                                    $persenDiskon = $persenDiskon/$row['qty_sales'];
                                    $VariabelDiskon = " (" . $persenDiskon . "%)";
                                }else{
                                    $varDiskon = "";
                                    $VariabelDiskon = "";
                                }
                                $FlTimbang = getTimbangByKodeBarang($row['kode_barang']);
                                if($FlTimbang == 1){
                                    $Satuan = "KG";
                                }else{
                                    $Satuan = $row['satuan'];
                                }
                            ?>
                            <tr>
                                <td style="color:#000;font-size:10px;" colspan="3"><?php   echo getNamaBarangByKodeBarang($row['kode_barang']);  ?>
                                </td>
                            </tr>
                            <?php
                            if($QtyVoidLine > 0){
                            ?>
                            <tr>
                                <td style="color:#000;font-size:10px;">
                                Harga : <?php   echo number_format($row['harga'],0);  ?> <?php   echo $varDiskon;  ?> <?php   echo $VariabelDiskon;  ?>
                                </td>
                                <td style="color:#000;font-size:10px;"><?php   echo $row['qty_sales'];  ?> <?php   echo $Satuan;  ?> (Void : <?php   echo number_format($QtyVoidLine,0);  ?> <?php   echo getUomByKodeBarang($row['kode_barang']);  ?>)</td>
                                <td style="text-align:right;font-size:10px;"><?php   echo number_format($row['total_sales'],0);  ?></td>
                            </tr> 
                            <?php
                            }else{
                            ?>
                            <tr>
                                <td style="color:#000;font-size:10px;">
                                Harga : <?php   echo number_format($row['harga'],0);  ?> <?php   echo $varDiskon;  ?> <?php   echo $VariabelDiskon;  ?>
                                </td>
                                <td style="color:#000;font-size:10px;"><?php   echo $row['qty_sales'];  ?> <?php   echo $Satuan;  ?></td>
                                <td style="text-align:right;font-size:10px;"><?php   echo number_format($row['total_sales'],0);  ?></td>
                            </tr> 
                            <?php
                            }
                            ?>
                        
                            <?php
                            }
                            ?>                            
                        </table>
                    </div>
                    <hr>
                    <table width="100%" style="color:#000;font-size:12px;">     
                        <tr>
                            <td width="30%" align="right">Total Qty</td>
                            <td width="70%" align="right" style="text-align:right;font-size:10px;"><?php echo number_format($TotalQtySales,0);    ?></td>
                        </tr>                                                        
                        <tr>
                            <td width="30%" align="right">Total Belanja</td>
                            <td width="70%" align="right" style="text-align:right;font-size:10px;"><?php echo number_format($TotalStruk,0);    ?></td>
                        </tr>
                        <?php
                        if($Pembulatan != 0){
                            ?>
                            <tr>
                                <td width="30%" align="right">Pembulatan</td>
                                <td width="70%" align="right" style="text-align:right;font-size:10px;"><?php echo number_format($Pembulatan,0);    ?></td>
                            </tr>
                            <?php
                        } 
                        ?>    
                        <tr>
                            <td width="30%" align="right">Total Bayar</td>
                            <td width="70%" align="right" style="text-align:right;font-size:10px;"><?php echo number_format($TotalBayar,0);    ?></td>
                        </tr>
                        <?php
                        if($Kembalian > 0){
                            ?>
                            <tr>
                                <td width="30%" align="right">Kembalian</td>
                                <td width="70%" align="right" style="text-align:right;font-size:10px;"><?php echo number_format($Kembalian,0);    ?></td>
                            </tr>
                            <?php
                        } 
                        ?>
                        <?php
                        if($PoinMember > 0){
                            ?>
                            <tr>
                                <td width="30%" align="right">Poin Dipakai</td>
                                <td width="70%" align="right" style="text-align:right;font-size:10px;"><?php echo number_format($PoinMember,0);    ?></td>
                            </tr> 
                            <?php
                        } 
                        ?>
                        <?php
                        if($Voucher > 0){
                            ?>
                            <tr>
                                <td width="30%" align="right">Nilai Voucher</td>
                                <td width="70%" align="right" style="text-align:right;font-size:10px;"><?php echo number_format($Voucher,0);    ?></td>
                            </tr> 
                            <?php
                        } 
                        ?>
                        <tr>
                            <td width="30%" align="right">Pembayaran</td>
                            <td width="70%" align="right" style="text-align:right;font-size:10px;"><?php echo $JenisBayar;    ?> / <?php echo $NamaKartu;    ?></td>
                        </tr> 
                        <?php
                        if($ValueVoid != 0){
                            ?>
                            <tr style="border: solid 1px #FF0000;">
                                <td width="30%" align="right">Total Void</td>
                                <td width="70%" align="right" style="text-align:right;font-size:10px;"><?php echo number_format($ValueVoid,0);    ?></td>
                            </tr>
                            <?php
                        } 
                        ?>                                                 
                    </table>    

                    <hr><img src="<?php   echo $FileBarcode;  ?>" width="30%">
                    <?php
                    if($TotalDiskon >= 1){
                        ?>
                        <div style="color:#000;font-size:10px;font-weight:800;">
                            Anda Mendapatkan Diskon Sebesar <?php echo number_format($TotalDiskon,0);    ?>
                        </div>
                        <?php
                    }                         
                    if($NilaiPoin >= 1){
                        ?>
                        <div style="color:#000;font-size:10px;font-weight:800;">
                            Anda Mendapatkan Poin <?php echo number_format($NilaiPoin,0);    ?>
                        </div>
                        <?php
                    } 
                    ?>
                    <div style="color:#000;font-size:10px;">
                        <?php echo $FooterStruk;    ?>
                    </div>
                    <?php
                    if(isset($_GET['reprint'])){
                    ?>
                    <div class="TextMiring" style="color:#CCCCCC;font-size:30px;">COPY STRUK</div>
                    <?php
                    }
                    ?>
                </div>           
            </div>
            <?php
        }        
    }
}
?>
</div>
</body>
</html>