$(function () {
  $("#DialogVoid").dialog({
    autoOpen: false,
    modal: true,
    width: 410,
    position: { my: "center top+50", at: "center top", of: window },
    open: function () {
      var $dialog = $(this);
      var $buttonPane = $dialog.parent().find(".ui-dialog-buttonpane");
      var $checkboxContainer = $(
        '<div style="float: left; margin-right: 10px; display: flex; align-items: center;">'
      );
      var $checkbox = $(
        '<input type="checkbox" id="chkboxKonfirmasi" style="margin-right: 5px;">'
      );
      var $label = $(
        '<label for="chkboxKonfirmasi" style="margin-top: 10px;font-weight: bold;color: #7d0e0e;">Konfirmasi void line!</label>'
      );

      $checkboxContainer.append($checkbox).append($label);
      $buttonPane.prepend($checkboxContainer);

      $("#DialogVoid").load("dialogbox/dlg_void.php", function () {
        $("#NoStruk").focus();
      });
    },
    close: function () {
      var $dialog = $(this);
      var $buttonPane = $dialog.parent().find(".ui-dialog-buttonpane");
      $buttonPane.find("div:first-child").remove();
    },
    buttons: {
      OK: function () {
        if (!$("#chkboxKonfirmasi").is(":checked")) {
          alert(
            "Harap centang checkbox 'Konfirmasi void line!' sebelum melanjutkan."
          );
          return;
        }

        $thisDialog = $(this);

        var nostruk = $("#NoStruk").val();
        var kodebarang = $("#ScanItem").val();
        var qtyAwal = $("#Qty").val();
        var qty = $("#NewQty").val();
        var harga = $("#hargabarang").val();
        var diskon = $("#diskonbarang").val();
        var uom = $("#uombarang").val();
        var skubarang = $("#skubarang").val();

        $.ajax({
          url: "lib_dbo/void_line.php",
          type: "GET",
          dataType: "html",
          data: {
            nostruk: nostruk,
            kodebarang: kodebarang,
            qty: qty,
            harga: harga,
            diskon: diskon,
            qtyawal: qtyAwal,
            uom: uom,
            skubarang: skubarang,
          },
          success: function (response) {
            window.location.href = "displaystruk!" + nostruk;
            $thisDialog.dialog("close");
          },
        });
      },
      Cancel: function () {
        $(this).dialog("close");
      },
    },
  });

  $("#BtnVoid").click(function () {
    // open dialog spv approval
    window.isvoid = true;
    $("#DialogSpvApproval").dialog("open");
  });
});
