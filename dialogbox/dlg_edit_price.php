<div class="widget-body">
    <div class="row-fluid">
        <div class="span12">
            <input type="hidden" id="Nomor" value="0" />
            <input type="text" id="IsPromo" readonly/>
            <input type="hidden" id="NoId" />
            <div class="control-group" style="margin-bottom: 0px;">
                <label class="control-label">Kode Barang</label>
                <div class="controls"><input class="span12" id="KodeBarang" type="text" value="" readonly/></div>
            </div>
            <div class="control-group" style="margin-bottom: 0px;">
                <label class="control-label">UoM Barang</label>
                <div class="controls"><input class="span12" id="UomBarang" type="text" value="" readonly/></div>
            </div>
            <div class="control-group" style="margin-bottom: 0px;">
                <label class="control-label">Harga (Rp.)</label>
                <div class="controls"><input class="span12" id="Harga" type="number" value="" onclick="this.select();"/></div>
            </div>
            <div class="control-group" style="margin-bottom: 0px;">
                <label class="control-label">Quantity</label>
                <div class="controls"><input class="span12" id="Qty" type="number" value=""/></div>
            </div>
            <div class="control-group" style="margin-bottom: 0px;">
                <label class="control-label">Diskon (%)</label>
                <div class="controls"><input class="span12" id="Diskon" type="number" value="" maxlength="3"/></div>
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