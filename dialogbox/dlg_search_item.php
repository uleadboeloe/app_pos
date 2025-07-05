<div class="widget-body">
    <div class="row-fluid">
        <div class="span12">
            <div class="control-group">
                <label class="control-label" for="firstname">PLU / NAMA BARANG / BARCODE</label>
                <div class="controls"><input class="span12" id="SearchText" type="text" onclick="this.select();"/></div>
            </div>
            <div class="control-group">
                <div id="scrollable" style="height: 340px;">
                <table id="SearchResultTable" class="table table-bordered table-striped table-condensed">
                    <thead>
                        <tr>
                            <th style="width: 70px; ">PLU</th>
                            <th>NAMA BARANG</th>
                            <th style="width: 60px; text-align: right;">HARGA</th>
                            <th style="width: 40px; text-align: center;">UOM</th>
                            <th style="width: 60px; text-align: center;">ISI</th>
                            <th style="width: 60px; text-align: center;">PROMO</th>
                            <th style="width: 40px; text-align: center;">VALUE</th>
                            <th style="width: 70px; ">BARCODE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        for($i = 0; $i < 9; $i++)
                            echo '<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>';
                        ?>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
     #SearchText{
        text-transform:uppercase;
    }
</style>

<!-- Fungsi2 pengolahan string -->
<script src="lib_js/string_utils.js"></script>

<script>
    function refreshTable() {

        let point_member = sessionStorage.getItem("poin_member_id") || "0";
        point_member = point_member.replaceAll(".", "");
        let nilai_voucher = window.sessionStorage.getItem("nilai_voucher") || "0";
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
    
    $('#SearchText').keypress(function (e) {
        var key = e.which;
        if(key == 13)  // the enter key code
        {
            let keyword = $('#SearchText').val();
            if (keyword === '') {
                alert('Masukkan keyword!');
                return;
            } 
            var keywordbarang = $("#SearchText").val();

            var log_description = "Cari Barang: " + keywordbarang;
            var log_tipe = "INFO";
            $.ajax({
                type: "POST",
                url: "lib_dbo/simpan_log_aktivitas.php",
                data: {
                    proses_user: current_user_id,
                    log_description: log_description,
                    log_tipe: log_tipe,
                    no_struk: current_order_no,
                    source: "dlg_search_item",
                },
            });
            
            $.ajax({
                url: "lib_dbo/search_barang.php",
                type: "GET",
                dataType: "html",
                data: {
                    keyword: keywordbarang
                },
                success: function (data) {
                    $("#SearchResultTable tbody").html(data);

                    if (window.navigator && window.navigator.keyboard && window.navigator.keyboard.hide) {
                        window.navigator.keyboard.hide();
                    } else if (document.activeElement) {
                        document.activeElement.blur();
                    }
                }
            });
        }
    }); 

    $(document).off("click", "#SearchResultTable tbody tr").on("click", "#SearchResultTable tbody tr", function () {
    let selectedItem = $(this).find("td").map(function () {
        return $(this).text();
    }).get();

    // AJAX tambah barang
    $.ajax({
        url: 'ajax/temp_trx_addscan.php',
        type: 'POST',
        data: {
            order_no: current_order_no,
            kode_barang: selectedItem[0],
            nama_barang: selectedItem[1],
            harga_jual: selectedItem[2],
            uom: selectedItem[3],
            qty: 1 // default 1, bisa diatur user
        },
        success: function(res) {
                
        /*addtional log aktivitas*/
        var log_description = "Pilih Produk#" + current_order_no + "#Kode Barang:" + selectedItem[0] + "#UOM:" + selectedItem[3] ;
        var log_tipe = "INFO";
        $.ajax({
            type: "POST",
            url: "lib_dbo/simpan_log_aktivitas.php",
            data: {
            proses_user: current_user_id,
            log_description: log_description,
            log_tipe: log_tipe,
            source: "dlg_search_item",
            kode_barang: selectedItem[0],
            uom_barang: selectedItem[3],
            no_struk: current_order_no
            },
        });
        /*addtional log aktivitas*/
        
            refreshTable();
        }
    });


    $("#DialogSearch").dialog("close");

    });
</script>