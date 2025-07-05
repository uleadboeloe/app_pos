$(function () {
  $("#DialogEditPrice").dialog({
    autoOpen: false,
    modal: true,
    width: 400,
    position: { my: "center top+50", at: "center top", of: window },
    open: function () {
      $("#DialogEditPrice").load("dialogbox/dlg_edit_price.php", function () {
        $("#KodeBarang").val(window.kodeBarang);
        $("#Qty").val(window.qty);
        $("#Harga").val(window.harga);
        $("#Diskon").val(window.diskon);
        $("#UomBarang").val(window.uom);
        $("#IsPromo").val(window.promo);

        $("#Diskon").prop("readonly", false);
        $("#Harga").prop("readonly", false);

        if (window.promo === 1) {
          $("#Diskon").prop("readonly", true);
          $("#Harga").prop("readonly", true);
        }

        if (window.uom === "KG") {
          $("#Qty").prop("readonly", true);
        } else {
          $("#Qty").prop("readonly", false);
        }

        /*addtional log aktivitas*/
        var log_description = "Open Pages Edit Harga#" + current_order_no;
        var log_tipe = "DANGER";
        $.ajax({
          type: "POST",
          url: "lib_dbo/simpan_log_aktivitas.php",
          data: {
            proses_user: current_user_id,
            log_description: log_description,
            log_tipe: log_tipe,
            source: "dlg_edit_price",
            no_struk: current_order_no,
            kode_barang: window.kodeBarang,
            qty_lama: window.qty,
            harga_lama: window.harga,
            diskon_lama: window.diskon,
            uom_barang: window.uom,
          },
        });
        /*addtional log aktivitas*/
      });
    },
    close: function () {
      $("#DialogEditPrice").html(""); // Clear content on close.
    },
    buttons: {
      OK: function () {
        // do here if OK pressed
        var harga_jual = $("#Harga").val();
        if (harga_jual === "" || Number(harga_jual) <= 0)
          harga_jual = window.harga;

        var disc = $("#Diskon").val();
        if (disc === "" || Number(disc) < 0) disc = window.diskon;

        var qty = $("#Qty").val();
        if (qty === "" || Number(qty) <= 0) qty = window.qty;

        /*addtional log aktivitas*/
        var log_description = "Simpan Edit Harga#" + current_order_no;
        var log_tipe = "DANGER";
        $.ajax({
          type: "POST",
          url: "lib_dbo/simpan_log_aktivitas.php",
          data: {
            proses_user: current_user_id,
            log_description: log_description,
            log_tipe: log_tipe,
            source: "dlg_edit_price",
            no_struk: current_order_no,
            kode_barang: window.kodeBarang,
            qty_baru: qty,
            harga_baru: harga_jual,
            diskon_baru: disc,
            uom_barang: window.uom,
          },
        });
        /*addtional log aktivitas*/

        $.ajax({
          url: "ajax/temp_trx_update_harga_diskon.php",
          type: "GET",
          dataType: "html",
          data: {
            orderno: current_order_no,
            kode_barang: window.kodeBarang,
            harga_jual: harga_jual,
            disc: disc,
            uom: window.uom,
            qty: qty,
          },
          success: function (data) {
            window.kodeBarang = "";
            window.harga = "";
            window.diskon = "";
            window.uom = "";
            window.qty = "";
            refreshTable();
          },
        });
        $(this).dialog("close");
      },
      Cancel: function () {
        $(this).dialog("close");
      },
    },
  });

  $(document).on("keydown", "#Harga", function (e) {
    if (
      $.inArray(e.keyCode, [8, 9, 13, 27, 35, 36, 37, 38, 39, 40, 46]) !== -1 ||
      (e.ctrlKey === true && [65, 67, 86, 88].indexOf(e.keyCode) !== -1)
    ) {
      return;
    }
    if (
      (e.keyCode < 48 || e.keyCode > 57) &&
      (e.keyCode < 96 || e.keyCode > 105)
    ) {
      e.preventDefault();
    }
  });
  $(document).on("keydown", "#Diskon", function (e) {
    if (
      $.inArray(e.keyCode, [8, 9, 13, 27, 35, 36, 37, 38, 39, 40, 46]) !== -1 ||
      (e.ctrlKey === true && [65, 67, 86, 88].indexOf(e.keyCode) !== -1)
    ) {
      return;
    }
    if (
      (e.keyCode < 48 || e.keyCode > 57) &&
      (e.keyCode < 96 || e.keyCode > 105)
    ) {
      e.preventDefault();
    }
  });
  $(document).on("keydown", "#Qty", function (e) {
    if (
      $.inArray(e.keyCode, [8, 9, 13, 27, 35, 36, 37, 38, 39, 40, 46]) !== -1 ||
      (e.ctrlKey === true && [65, 67, 86, 88].indexOf(e.keyCode) !== -1)
    ) {
      return;
    }
    if (
      (e.keyCode < 48 || e.keyCode > 57) &&
      (e.keyCode < 96 || e.keyCode > 105)
    ) {
      e.preventDefault();
    }
  });
});
