<div class="widget-body">
    <div class="row-fluid">
        <div class="span12">     
            <div class="control-group">
                <label class="control-label" for="VoucherID">Scan Kode Voucher</label>
                <div class="controls">
                    <input class="span12" id="VoucherID" type="text" required/>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="NilaiVoucher">Nilai Voucher</label>
                <div class="controls"><input class="span12" id="NilaiVoucher" type="text" style="color:#FF0000; font-weight:800;" required disabled/></div>
                <input class="span12" id="ValidIDMember" type="hidden" disabled/>
            </div>          
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#VoucherID').keypress(function (e) {
        var key = e.which;
            if(key == 13)  // the enter key code
            {
                var kodevoucher = $('#VoucherID').val();
                if (kodevoucher == '') {
                    alert('Masukkan Kode Voucher!');
                    return;
                } 

                if(kodevoucher != ""){
                    $.ajax({
                    type: "POST",
                        url: "ajax/cek_voucher.php",
                        data: { kode_voucher: kodevoucher },
                        success: function (response) {
                            if (Number(response) >= 0) $("#NilaiVoucher").val(response);
                        },
                    });             
                }                
            }
        });
    });
</script>