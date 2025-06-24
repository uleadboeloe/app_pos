<div class="widget-body">
    <div class="row-fluid">
        <div class="span12">
            <div class="control-group">
                <label class="control-label" for="NoStruk">No. Struk</label>
                <div class="controls"><input class="span12" id="NoStruk" type="text" /></div>
                <label class="control-label"><span id="struknotfound" style="font-size: 110%; color: red; display: none;">No. struk tidak ditemukan atau sudah VOID.</span></label>
            </div>
            <div class="control-group">
                <label class="control-label">Pastikan No. Struk benar dan klik CONFIRM untuk void transaksi!</label>
                <div class="controls"><span id="confirmbtn" class="btn btn-block btn-danger" disabled style="pointer-events: none;">CONFIRM</span></div>
            </div>
            <div style="border-top: 2px dotted #744646;"></div>
            <div style="font-weight: bold; font-size: 110%; margin-bottom: 10px; padding-top:20px;">
                <span>VOID LINE</span>
            </div>
            <hr style="margin-top: 10px;"/>
            <div class="control-group" style="margin-top: -15px;">
                <label class="control-label" style="margin-bottom: -10px;">Scan Item</label>
                <div class="controls">
                    <table style="width:100%;">
                        <tr>
                            <td style="padding-top: 10px;"><input class="span12" id="ScanItem" type="text" /></td>
                            <td><span id="clearscanitem" class="btn btn-block btn-default">&#10006;</span></td>
                        </tr>
                    </table>
                </div>
                <label class="control-label"><span id="itemnotfound" style="font-size: 110%; color: red; display: none;">Item tidak ditemukan atau barang promo!</span></label>
                <label class="control-label"><span id="namabarang" style="font-size: 110%; color: #7d0e0e; display: none;"></span></label>
            </div>
            <div class="control-group" style="margin-top: 0px;">
                <div class="controls">
                    <table id="tbl">
                        <tr>
                            <td>Quantity</td>
                            <td>&nbsp;</td>
                            <td>New Quantity</td>
                        </tr>
                        <tr>
                            <td><input class="span12" id="Qty" type="text" readonly style="text-align: right;padding-right: 10px;" /></td>
                            <td style="padding-bottom: 10px;"><span style="font-size: 120%;">&#10132;</span></td>
                            <td><input class="span12" id="NewQty" type="text" readonly style="text-align: right;padding-right: 10px;" /></td>
                        </tr>
                    </table>
                </div>
                
            </div>
        </div>
    </div>
</div>
<div style="border-top: 2px dotted #744646;"></div>

<style>
    #NoStruk{
        text-transform:uppercase;
    }
    #tbl {
        width: 100%;
    }

    #tbl td {
        line-height: 20px !important;
    }
</style>
<script>

    // CLEAR SCAN ITEM
    $('#clearscanitem').click(function () {
        $('#ScanItem').val('');
        $('#Qty').val('');
        $('#NewQty').val('');
        $('#ScanItem').focus();
    });

    // CONFIRM BUTTON
    $('#confirmbtn').click(function () {

        if ($("#struknotfound").is(":visible")) {
            alert('Struk tidak ditemukan, tidak dapat melakukan void transaksi!');
            return;
        }

        var nostruk = $("#NoStruk").val();
        if (nostruk === '') {
            alert('Masukkan No. Struk!');
            return;
        }

        /*addtional log aktivitas*/
        var log_description = "Void Transaksi#" + nostruk;
        var log_tipe = "DANGER";
        $.ajax({
        type: "POST",
        url: "lib_dbo/simpan_log_aktivitas.php",
            data: {
                proses_user: current_user_id,
                log_description: log_description,
                log_tipe: log_tipe,
                source: "kasir",
            },
        });
        /*addtional log aktivitas*/

        $.ajax({
            url: "lib_dbo/void_transaksi.php",
            type: "GET",
            dataType: "html",
            data: {
                nostruk: nostruk
            },
            success: function (response) {
                //console.log(response);
                var responseData = JSON.parse(response);
                if(responseData.success) {
                    Swal.fire({
                        title: "Sukses",
                        text: "Transaksi berhasil di-void!",
                        icon: "success",
                    }).then(() => {
                        $("#DialogVoid").dialog("close");
                    });
                } else {
                    Swal.fire({
                        title: "Error",
                        text: "Transaksi Gagal di-void!",
                        icon: "error",
                    });
                }
            }
        });
    });

    // INPUT NO STRUK
    $('#NoStruk').keypress(function (e) {
        var key = e.which;
        if(key == 13)  // the enter key code
        {
            var nostruk = $("#NoStruk").val();
            console.log(nostruk);
            
            if (nostruk === '') {
                alert('Masukkan No. Struk!');
                return;
            } 
            var nostruk = $("#NoStruk").val();

            $.ajax({
                url: "lib_dbo/search_transaction_for_void.php",
                type: "GET",
                dataType: "html",
                data: {
                    no: nostruk
                },
                success: function (data) {
                    var json = JSON.parse(data);    
                    var header = json.header;
                    var detail = json.detail;

                    if(header == false)
                    {
                        $("#struknotfound").show();
                        return;
                    }
                    else
                    {
                        $("#confirmbtn").removeAttr("disabled");
                        $("#confirmbtn").css("pointer-events", "auto");
                        $("#struknotfound").hide();

                        console.log(header);
                        console.log(detail);
                    }

                    $("#ScanItem").focus();
                }
            });
        }
    }); 

    // SCAN ITEM FOR VOID LINE
    $('#ScanItem').keypress(function (e) {
        var key = e.which;
        if(key == 13)  // the enter key code
        {
            var nostruk = $("#NoStruk").val();
            if (nostruk === '') {
                alert('Masukkan No. Struk!');
                return;
            }

            var barcode = $("#ScanItem").val();
            if (barcode === '') {
                alert('Masukkan Barcode Barang!');
                return;
            } 

            $.ajax({
                url: "lib_dbo/get_item_qty_for_void.php",
                type: "GET",
                dataType: "json",
                data: {
                    nostruk: nostruk, 
                    //kodebarang: kodebarang
                    barcode: barcode
                },
                success: function (data) {
                    //var json = JSON.parse(data);
                    console.log(data);
                    if (data.success) {
                        $("#itemnotfound").hide();
                        var qty = data.qty_sales;
                        $("#Qty").val(qty);

                        var namabarang = data.nama_barang;
                        $("#namabarang").show();
                        $("#namabarang").text(namabarang);

                        $("#NewQty").removeAttr("readonly");
                        $("#NewQty").val(qty);
                        $("#NewQty").focus();
                    } else {
                        $("#itemnotfound").show();
                        $("#NewQty").addAttr("readonly");
                    }
                }
            });
        }
    });
</script>