<div class="widget-body">
    <div class="row-fluid">
        <div class="span12">
            <div class="control-group">
                <label class="control-label" for="MemberID">ID Member</label>
                <div class="controls"><input class="span12" id="MemberID" type="text" value="" onfocusout="cekMemberID()" required/></div>
            </div>
            <div class="control-group">
                <label class="control-label" for="PhoneNo">Nomor Handphone</label>
                <div class="controls"><input class="span12 numeric-input12" id="PhoneNo" type="number" value="" required/></div>
            </div>
            <div class="control-group">
                <label class="control-label" for="ValidMember">Status Member</label>
                <div class="controls"><input class="span12" id="ValidMember" type="text" style="color:#FF0000; font-weight:800;" required disabled/></div>
            </div>            
            <div class="control-group">
                <label class="control-label" for="MemberPoin">Total Poin</label>
                <div class="controls"><input class="span12 numeric-input6" id="MemberPoin" type="text" value="" required/></div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        document.getElementById("MemberID").addEventListener("focusout", MemberIDLostFocus);
        function MemberIDLostFocus() {
            var valueMemberID = $("#MemberID").val();
            
            if(valueMemberID != ""){
                $.ajax({
                type: "POST",
                    url: "ajax/cek_member.php",
                    data: { memberid: valueMemberID,source:'memberid' },
                    success: function (response) {
                        //alert('MASUK' + response);
                        if (response != '') $("#ValidMember").val(response);
                        $.ajax({
                        type: "POST",
                            url: "ajax/cek_poin.php",
                            data: { memberid: valueMemberID,source:'memberid' },
                            success: function (response) {
                                //alert('MASUK' + response);
                                if (response != '') $("#MemberPoin").val(response);
                            },
                        });
                    },
                });
            }
        }

        document.getElementById("PhoneNo").addEventListener("focusout", PhoneNoLostFocus);
        function PhoneNoLostFocus() {
            var valueMemberID = $("#PhoneNo").val();
            if(valueMemberID != ""){
            $.ajax({
                type: "POST",
                    url: "ajax/cek_member.php",
                    data: { memberid: valueMemberID,source:'phoneno' },
                    success: function (response) {
                        //alert('MASUK' + response);
                        if (response != '') $("#ValidMember").val(response);
                    },
                });
            }
        }        

        $(".numeric-input12").on("input", function () {
            let value = $(this).val();
            
            // Hanya angka dan maksimal 4 karakter
            value = value.replace(/\D/g, "").substring(0, 12);
            
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
                    $("#DialogLoyalti").closest(".ui-dialog").find(".ui-dialog-buttonset button:contains('OK')").trigger("click");
                }
            }
        });
    });
</script>