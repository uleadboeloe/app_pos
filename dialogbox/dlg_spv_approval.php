<div class="widget-body">
    <div class="row-fluid">
        <div class="span12">
            <div class="control-group">
                <label class="control-label" for="SpvPassword">Enter Password</label>
                <div class="controls"><input class="span12 numeric-input4" id="SpvPassword" type="password" value="" required /></div>
                <label class="control-label" for="SpvPassword"><span id="wrongpass" style="font-size: 125%; color: red; display: none;">Wrong Password!</span></label>
            </div>
        </div>
    </div>
</div>

<!-- Fungsi2 pengolahan string -->
<script src="lib_js/string_utils.js"></script>

<script>
    function refreshTable() {
        var point_member = window.sessionStorage.getItem("poin_member_id") || "0";
        point_member = point_member.replaceAll(".", "");
        var nilai_voucher = window.sessionStorage.getItem("nilai_voucher") || "0";
        nilai_voucher = nilai_voucher.replaceAll(".", "");
        $.ajax({
            url: "lib_dbo/refresh_scanned_item_table.php",
            type: "GET",
            data: {
                orderno: current_order_no,
                pointmember: point_member,
                nilaivoucher: nilai_voucher
            },
            success: function(response) {
                $("#ItemTable tbody").empty().html(response);

                let qty = $("#hiddenQty").val();
                let subtotal = $("#hiddenSubtotal").val();
                let ppnamt = $("#hiddenPpnAmt").val();
                let total = $("#hiddenTotal").val();

                // Update tampilan subtotal & total
                $("#TxtTotalItems").text(qty);
                $("#TxtSubTotal").text(subtotal);
                $("#TxtPPN").text(ppnamt);
                $("#TxtGrandTotal").text(total);

                $("#EditBoxTendered").val(total); 
                $("#pTendered").css("color", "black");

                let option1 = $("#hiddenPecahan1").val();
                let option2 = $("#hiddenPecahan2").val();
                let option3 = $("#hiddenPecahan3").val();

                $("#BtnNominalOption1").text(formatRibuan(option1));
                $("#BtnNominalOption2").text(formatRibuan(option2));
                $("#BtnNominalOption3").text(formatRibuan(option3));

                $("#EditBoxScanItem").val("").focus();
            }
        });
    }
</script>