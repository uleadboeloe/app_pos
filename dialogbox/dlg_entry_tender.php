<div class="widget-body">
    <div class="row-fluid">
        <div class="span12">
            <div class="control-group">
                <label class="control-label" for="entryTender">Cash Amount</label>
                <div class="controls"><input class="span12" id="entryTender" type="text" value="" /></div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $("#entryTender").on("keydown", function(event) {
            // Mengizinkan angka (0-9), Enter (13), dan Backspace (8)
            if ((event.keyCode >= 48 && event.keyCode <= 57) || // Angka 0-9 (keyboard utama)
                (event.keyCode >= 96 && event.keyCode <= 105) || // Angka 0-9 (numpad)
                event.keyCode === 8 || // Backspace
                event.keyCode === 13) { // Enter
                return; // Izinkan input
            }
            event.preventDefault(); // Cegah input selain yang diizinkan
        });

        $("#entryTender").keydown(function(event) {
            if (event.key === "Enter") 
            {
                var e = $("#entryTender").val();
                if (e != "") 
                {
                    // bandingkan dg total
                    var m = $("#hiddenTotal").val();
                    m = m.replace(".", "");

                    if (parseInt(e) >= parseInt(m)) 
                    {
                        $("#EditBoxTendered").val(formatRibuan(e));
                        var c = e - m;
                        $("#TxtChanges").text("CHANGES Rp." + formatRibuan(c));
                    }
                    else
                    {
                        $("#EditBoxTendered").val(m);
                        $("#TxtChanges").text("CHANGES: Rp.0");
                    }
    
                    $("#pTendered").css("color", "black");
                    $(this).closest(".ui-dialog-content").dialog("close"); // Menutup dialog
                }
            }
        }); 
    });
</script>