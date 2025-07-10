<?php
include("../lib/mysql_pdo.php");
include("../lib/mysql_connect.php");
?>
<div class="widget-body">
    <div class="row-fluid">
        <div class="span12">
            <table>
                <tr>
                    <td><span class="glyphicons warning_sign" style="margin-bottom: 7px;"><i></i></span></td>
                    <td><h1>Konfirmasi RECALL Transaction!</h1></td>
                </tr>
            </table>
        </div>
        <div class="control-group">
            <label class="control-label" for="hold_transaksi">Hold Transaksi</label>
            <select class="span12" id="hold_transaksi" placeholder="Pilih Nomor Transaksi" required>
                <option value="">Pilih Nomor Transaksi</option>
                <?php
                $query = "SELECT order_no,note_kasir FROM temp_transaksi where status = 'ONHOLD' group by order_no,note_kasir";
                $stmt = $db->prepare($query);
                $stmt->execute();
                /* Pastikan query sudah benar dengan GROUP BY */
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value='" . $row['order_no'] . "'>{$row['order_no']} - {$row['note_kasir']}</option>";
                }
                ?>
            </select>
        </div>   
    </div>
</div>
<!-- Fungsi2 pengolahan string -->
<script src="lib_js/string_utils.js"></script>

