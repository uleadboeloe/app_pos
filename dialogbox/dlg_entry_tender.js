$(function() {
    $("#DialogEntryTender").dialog({
        autoOpen: false,
        modal: true,
        width: 400,
        position: { my: "right bottom", at: "right-5%", of: window }, // kanan bawah dari bagian kanan kurang 5%
        open: function() {
                $("#DialogEntryTender").load("dialogbox/dlg_entry_tender.php", function() {
                    $("#entryTender").focus();
                });
        },
        close: function() {
            $("#pTendered").css("color", "black");
            $("#DialogEntryTender").html(""); // Clear content on close.
        },
        buttons: {
        "OK": function() {
            var e = $("#entryTender").val();
            if (e != "") 
            {
                // bandingkan dg total
                var m = $("#hiddenTotal").val();
                m = m.replaceAll(".", "");

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
            }

            $( this ).dialog( "close" );
        },
        Cancel: function() {
            $( this ).dialog( "close" );
        }
    }
    });

    $("#EditBoxTendered").click(function() {
        $(this).blur();
        $("#DialogEntryTender").dialog("open");
    });
    
});