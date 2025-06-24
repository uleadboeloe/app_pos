<?php
include("../lib/mysql_pdo.php");
include("../lib/mysql_connect.php");
?>
<div class="widget-body">
    <div class="row-fluid">
        <div class="span12">
            <div class="control-group">
                <label class="control-label" for="4digit">4 Digit Nomor Kartu</label>
                <div class="controls"><input class="span12 numeric-input4" id="4digit" type="number" value="" placeholder="Masukan 4 Digit Terakhir Kartu Kredit" required /></div>
            </div>
            <div class="control-group">
                <label class="control-label" for="refno">Jenis Kartu Kredit</label>
                <div class="controls"><input class="span12" id="jenis_kartu" type="text" value="" placeholder="Masukan Jenis Kartu Kredit" required/></div>
            </div>
            <div class="control-group">
                <label class="control-label" for="refno">Reference Number</label>
                <div class="controls"><input class="span12" id="refno" type="text" value="" placeholder="Masukan Nomor Reff" required/></div>
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
                <label class="control-label" for="approvecode">Approval Code</label>
                <div class="controls"><input class="span12 numeric-input6" id="approvecode" type="number" placeholder="Masukan approval Code" value="" required/></div>
            </div>
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
        $(".numeric-input4").on("input", function () {
            let value = $(this).val();
            
            // Hanya angka dan maksimal 4 karakter
            value = value.replace(/\D/g, "").substring(0, 4);
            
            $(this).val(value);
        });

        $(".numeric-input6").on("input", function () {
            let value = $(this).val();
            
            // Hanya angka dan maksimal 4 karakter
            value = value.replace(/\D/g, "").substring(0, 6);
            
            $(this).val(value);
        });

        $(document).on("keydown", "input, select, textarea", function(e) {
            if (e.key === "Enter") {
                e.preventDefault(); // Mencegah form submit
                let inputs = $("input, select, textarea");
                let index = inputs.index(this);
                
                if (index !== -1 && index < inputs.length - 1) {
                    inputs.eq(index + 1).focus();
                } else {
                    // Jika input terakhir, trigger tombol OK di jQuery dialog
                    $("#DialogCreditCard").closest(".ui-dialog").find(".ui-dialog-buttonset button:contains('OK')").trigger("click");
                }
            }
        });

    });
</script>