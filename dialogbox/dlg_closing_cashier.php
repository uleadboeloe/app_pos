<?php
    include("../lib/mysql_pdo.php");
    include("../lib/mysql_connect.php");

    session_start();
    $kode_kasir = $_SESSION['SESS_user_id'];
    $kode_store = $_SESSION['SESS_kode_store'];
    $kode_register = $_SESSION['SESS_kode_register'];

    //echo $kode_kasir . " " . $kode_store . " " . $kode_register . "<br>";;
    $query = <<<SQL
        SELECT jenis_bayar AS Metode, 
            COUNT(total_struk) AS Jumlah, 
            SUM(total_struk) AS Total, 
            CASE WHEN jenis_bayar = "CASH" THEN SUM(total_bayar - kembalian) ELSE 0 END AS Tunai, 
            IFNULL(SUM(var_poin),0) AS Poin, 
            IFNULL(SUM(var_voucher),0) AS Voucher
        FROM dbo_header
        WHERE tanggal = CURDATE() AND kode_store = :kodestore AND kode_kasir = :kodekasir AND kode_register = :kode_register and is_voided in ('0','2')
        GROUP BY jenis_bayar
        ORDER BY jenis_bayar
    SQL;

    $stmt = $db->prepare($query);
    $stmt->execute(['kodestore' => $kode_store, 'kodekasir' => $kode_kasir, 'kode_register' => $kode_register]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //var_dump($stmt);
    //exit();


?>
<div class="widget-body" style="display:none;">
    <div class="row-fluid">
        <div class="span12">
            <div class="control-group">
                <table id="SearchResultTable" class="table table-bordered table-striped table-condensed">
                    <thead>
                        <tr>
                            <th style="width: 20%; ">Metode</th>
                            <th style="width: 10%; text-align: right;">Jumlah</th>
                            <th style="width: 20%; text-align: right;">Total</th>
                            <th style="width: 20%; text-align: right;">Tunai</th>
                            <th style="width: 15%; text-align: right;">Poin</th>
                            <th style="width: 15%; text-align: right;">Voucher</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $j = 0; $t = 0;
                            foreach ($results as $row) {
                                echo "<tr>";
                                echo "<td>{$row['Metode']}</td>";
                                echo "<td style='text-align: right;'>{$row['Jumlah']}</td>";
                                echo "<td style='text-align: right;'>" . number_format($row['Total'], 0) . "</td>";
                                if ($j == 0)
                                    echo "<td style='text-align: right; font-weight: bold; color: blue;'>" . number_format($row['Tunai'], 0) . "</td>";
                                else
                                    echo "<td style='text-align: right;'>" . number_format($row['Tunai'], 0) . "</td>";
                                echo "<td style='text-align: right;'>" . number_format($row['Poin'], 0) . "</td>";
                                echo "<td style='text-align: right;'>" . number_format($row['Voucher'], 0) . "</td>";
                                echo "</tr>";
                                $j += $row['Jumlah'];
                                $t += $row['Total'];
                            }
                        ?>
                        <tr>
                            <td style="text-align: right; font-weight: bold; color: Brown;">Total</td>
                            <td style="text-align: right; font-weight: bold; color: BlueViolet;"><?php echo $j; ?></td>
                            <td style="text-align: right; font-weight: bold; color: ForestGreen;"><?php echo number_format($t, 0); ?></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    </tbody>
                </table>
                <input type="hidden" id="JumlahTotal" value="<?php echo $j; ?>">
            </div>
        </div>
    </div>
</div>
<div style="padding-top: 10px; margin-bottom: 20px; border-bottom: 1px solid #e2e2e2; ">
    <div style="font-size: 110%; font-weight: bold;">Uang Kertas</div>
    <div style="font-size: 110%;">Masukan Jumlah Pecahan Uang kertas yang ada di Cash Drawer</div>
</div>
<div class="widget-body">
    <div class="row-fluid">
        <div class="span3">
            <div class="input-prepend">
                <span class="add-on">100.000</span>
                <input id="c100000" type="number" style="width: 50%;" onclick="this.select();"/>
            </div>
        </div>
        <div class="span3">
            <div class="input-prepend">
                <span class="add-on">50.000</span>
                <input id="c50000" type="number" style="width: 50%;" onclick="this.select();"/>
            </div>
        </div>
        <div class="span3">
            <div class="input-prepend">
                <span class="add-on">20.000</span>
                <input id="c20000" type="number" style="width: 50%;" onclick="this.select();"/>
            </div>
        </div>
        <div class="span3">
            <div class="input-prepend">
                <span class="add-on">10.000</span>
                <input id="c10000" type="number" style="width: 50%;" onclick="this.select();"/>
            </div>
        </div>    
    </div>
    <div class="row-fluid">
        <div class="span3">
            <div class="input-prepend">
                <span class="add-on">&nbsp;&nbsp;&nbsp;&nbsp;5.000</span>
                <input id="c5000" type="number" style="width: 50%;" onclick="this.select();"/>
            </div>
        </div>
        <div class="span3">
            <div class="input-prepend">
                <span class="add-on">&nbsp;&nbsp;2.000</span>
                <input id="c2000" type="number" style="width: 50%;" onclick="this.select();"/>
            </div>
        </div>
        <div class="span3">
            <div class="input-prepend">
                <span class="add-on">&nbsp;&nbsp;1.000</span>
                <input id="c1000" type="number" style="width: 50%;" onclick="this.select();"/>
            </div>
        </div>
        <div class="span3">
        </div>      
    </div>
</div>
<div style="padding-top: 10px; margin-bottom: 20px; border-bottom: 1px solid #e2e2e2; ">
    <div style="font-size: 110%; font-weight: bold;">Uang Logam</div>
    <div style="font-size: 110%;">Masukan Jumlah Pecahan Uang Logam yang ada di Cash Drawer</div>
</div>
<div class="widget-body">
    <div class="row-fluid">
        <div class="span3">
            <div class="input-prepend">
                <span class="add-on">1.000</span>
                <input id="c1000k" type="number" style="width: 50%;" onclick="this.select();"/>
            </div>
        </div>
        <div class="span3">
            <div class="input-prepend">
                <span class="add-on">500</span>
                <input id="c500k" type="number" style="width: 50%;" onclick="this.select();"/>
            </div>
        </div>
        <div class="span3">
            <div class="input-prepend">
                <span class="add-on">200</span>
                <input id="c200k" type="number" style="width: 50%;" onclick="this.select();"/>
            </div>
        </div>
        <div class="span3">
            <div class="input-prepend">
                <span class="add-on">100</span>
                <input id="c100k" type="number" style="width: 50%;" onclick="this.select();"/>
            </div>
        </div>    
    </div>

</div>

