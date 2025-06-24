<?php
    //session_start();
    //$user_id = $_SESSION['SESS_user_id'];
?>

<div class="widget-body">
    <div class="row-fluid">
        <div class="span12">
            <div class="control-group">
                <label class="control-label" for="UserId">Modal Awal Rp.</label>
                <label class="control-label"><span id="ModalBelumSetor" style="font-size: 110%; font-weight: bold; color: #00a61e; display: none;">Sudah ada sesi yang belum posting!</span></label>
                <div class="controls"><input class="span12" id="ModalAwal" type="text" value="" /></div>
                <hr/>
                <label class="control-label" for="SpvPassword">Konfirmasi SPV Password</label>
                <div class="controls"><input class="span12" id="SpvPassword" type="password" value="" autocomplete="new-password" required /></div>
                <label class="control-label" for="SpvPassword"><span id="wrongpass" style="font-size: 125%; color: red; display: none;">Wrong Password!</span></label>
            </div>
        </div>
    </div>
</div>

<script>
    $("#ModalAwal").on("focus", function() {
        $(this).css("background-color", "#e0f7fa"); // Change background color on enter.
        var modalawal = $(this).val();
        modalawal = modalawal.replaceAll(".", "");
        $(this).val(modalawal);
    });

    $("#ModalAwal").on("blur", function() {
        $(this).css("background-color", ""); // Reset background color on leave.
        var modalawal = $(this).val();
        modalawal = formatRibuan(modalawal);
        $(this).val(modalawal);
    });
</script>