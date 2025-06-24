<div class="widget-body">
    <div class="row-fluid">
        <div class="span12">
            <div class="control-group hidden">
                <label class="control-label" for="jenis_member">Jenis Member</label>
                <select class="span12" id="jenis_member">
                    <option value="HNI">MEMBER HNI</option>
                    <option value="POS">MEMBER POS</option>
                </select>
            </div>            
            <div class="control-group">
                <label class="control-label" for="SearchID">Member ID / Nomor Handphone</label>
                <div class="controls">
                    <input class="span12 numeric-input12" id="SearchID" type="text" required/>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="ValidMember">Status Member</label>
                <div class="controls"><input class="span12" id="ValidMember" type="text" style="color:#FF0000; font-weight:800;" required disabled/></div>
                <input class="span12" id="ValidIDMember" type="text" style="color:#00FF00; font-weight:800;" disabled/>
                <input class="span12" id="ValidPoinMember" type="text" style="color:#FF0000; font-weight:800;" disabled/>
            </div>          
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#SearchID').keypress(function (e) {
        var key = e.which;
            if(key == 13)  // the enter key code
            {
                var kodemember = $('#SearchID').val();
                if (kodemember == '') {
                    Swal.fire({
                        title: "Error",
                        text: "Masukkan ID Member!",
                        icon: "error",
                    });
                    $("#SearchID").focus();
                    return;
                } 

                if(kodemember != ""){
                    $.ajax({
                    type: "POST",
                        url: "ajax/cek_member.php",
                        data: { memberid: kodemember,source:'phoneno' },
                        success: function (response) {
                            if (response != '') $("#ValidMember").val(response);
                        },
                    });

                    $.ajax({
                    type: "POST",
                        url: "ajax/cek_member.php",
                        data: { memberid: kodemember, source:'verify' },
                        success: function (response) {
                            console.log(response);
                            if (response != '') $("#ValidIDMember").val(response);
                            
                            var valueIDMember = $("#ValidIDMember").val();
                            $.ajax({
                            type: "POST",
                                url: "ajax/cek_poin.php",
                                data: { member_id: valueIDMember },
                                success: function (response) {
                                    if (Number(response) >= 0) $("#ValidPoinMember").val(response);
                                },
                            });   
                        },
                    });            
                }                
            }
        });
    });

</script>