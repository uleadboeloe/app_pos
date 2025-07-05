<?php
include("../lib/mysql_pdo.php");
include("../lib/mysql_connect.php");
?>
<div class="widget-body">
    <div class="row-fluid">
        <div class="control-group hidden">
            <label class="control-label" for="jenis_ewallet">Jenis Ewallet</label>
            <select class="span12" id="jenis_ewallet">
                <option value="QRIS">QRIS</option>
                <option value="OVO">OVO</option>
                <option value="GOPAY">GOPAY</option>
                <option value="SHOPEPAY">SHOPEPAY</option>
                <option value="LINKAJA">LINKAJA</option>
            </select>
        </div>
        <div class="control-group">
            <label class="control-label" for="mesin_edc">Mesin Edc</label>
            <select class="span12" id="mesin_edc" placeholder="Pilih Mesin EDC" required>
                <option value="">Pilih Mesin EDC</option>
                <?php
                $query = "SELECT * FROM dbo_mesin_edc where fl_active = 1";
                $stmt = $db->prepare($query);
                $stmt->execute();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value='" . $row['noid'] . "'>{$row['nama_mesin']} - {$row['bank_penerbit']}</option>";
                }
                ?>
            </select>
        </div>   
        <div class="control-group">
            <label class="control-label" for="refno">Reference Number</label>
            <input class="span12" id="refno" type="text" value=""/>
            <input class="span12" id="jenis_kartu" type="hidden" value="QRIS" required/>
        </div>
    </div>
</div>
<script type="text/javascript" src="admin/assets/js/autocomplete/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="admin/assets/js/autocomplete/jquery-ui.min.js"></script>
<script type="text/javascript" src="admin/assets/js/autocomplete/jquery.select-to-autocomplete.js"></script>

<script>
(function($){
	$(function(){
	  $('select[id="mesin_edc"]').selectToAutocomplete();
	});
})(jQuery);

    $(document).ready(function () {
        $(document).on("keydown", "input, select, textarea", function(e) {
            if (e.key === "Enter") {
                e.preventDefault(); // Mencegah form submit
                let inputs = $("input, select, textarea");
                let index = inputs.index(this);
                
                if (index !== -1 && index < inputs.length - 1) {
                    inputs.eq(index + 1).focus();
                } else {
                    // Jika input terakhir, trigger tombol OK di jQuery dialog
                    $("#DialogEWallet").closest(".ui-dialog").find(".ui-dialog-buttonset button:contains('OK')").trigger("click");
                }
            }
        });

    });
</script>