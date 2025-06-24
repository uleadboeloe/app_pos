$(function () {
  $("#DialogSpvApproval").dialog({
    autoOpen: false,
    modal: true,
    width: 400,
    position: { my: "center top+50", at: "center top", of: window },
    open: function () {
      $("#DialogSpvApproval").load(
        "dialogbox/dlg_spv_approval.php",
        function () {
          $("#SpvPassword").focus();
        }
      );
    },
    close: function () {
      $("#DialogSpvApproval").html(""); // Clear content on close.
    },
    buttons: {
      OK: function () {
        // do here if OK pressed
        $thisDialog = $(this);
        spvpassword = $("#SpvPassword").val();

        $.post(
          "lib_dbo/validate_spv_password.php",
          { spvpassword: spvpassword },
          function (response) {
            if (response.success) {
              var rowValues = [];
              //data userid nanti akan di simpan di table hostory minus qty dan delete item
              //console.log("Supervisor user id:", response.userid);
              //console.log("Order No:", current_order_no);
              //console.log("User Id:", current_user_id);
              //console.log("User Id:", rowValues[1]);
              //console.log("User Id:", rowValues[5]);

              // dialog spv password di trigger dari edit harga
              if (window.editharga) {
                editharga = false;

                $("#DialogEditPrice").dialog("open");

                /*addtional log aktivitas*/
                var log_description =
                  "Approval Edit Harga SPV#" + current_order_no;
                var log_tipe = "WARNING";
                $.ajax({
                  type: "POST",
                  url: "lib_dbo/simpan_log_aktivitas.php",
                  data: {
                    proses_user: current_user_id,
                    log_description: log_description,
                    log_tipe: log_tipe,
                    source: "dlg_spv_approval",
                    kode_barang: kode_barang,
                    no_struk: current_order_no,
                    kode_spv: response.userid,
                  },
                });
                /*addtional log aktivitas*/

                $thisDialog.dialog("close");
                return;
              }

              // dialog spv password di trigger dari tombol VOID
              if (window.isvoid) {
                isvoid = false; // reset isvoid
                $("#DialogVoid").dialog("open");

                /*addtional log aktivitas*/
                var log_description = "Approval Void#" + response.userid;
                var log_tipe = "DANGER";
                $.ajax({
                  type: "POST",
                  url: "lib_dbo/simpan_log_aktivitas.php",
                  data: {
                    proses_user: current_user_id,
                    log_description: log_description,
                    log_tipe: log_tipe,
                    source: "dlg_spv_approval",
                  },
                });
                /*addtional log aktivitas*/

                $thisDialog.dialog("close");
                return;
              }

              btnminusdelete
                .closest("tr")
                .find("td")
                .each(function () {
                  rowValues.push($(this).text());
                });

              var disc = rowValues[6];
              if (disc == "100") {
                $thisDialog.dialog("close");
                return; // jika barang diskon 100% tidak bisa di kurang qty
              }

              var uom = rowValues[4];

              //console.log("UOM NYA: " + uom);
              // cek apakah qty minus atau delete item
              if (window.isdeleteline) {
                var kode_barang = rowValues[1];
                let qtyInput = btnminusdelete.siblings(".quantity");
                let currentQty = parseInt(qtyInput.text());
                //console.log("QTY NYA: " + currentQty);
                /*addtional log aktivitas*/
                var log_description =
                  "Hapus Line Transaksi#" +
                  kode_barang +
                  " Nama Barang: " +
                  rowValues[2] +
                  " Qty: " +
                  rowValues[5] +
                  " UOM: " +
                  uom;
                var log_tipe = "DANGER";
                $.ajax({
                  type: "POST",
                  url: "lib_dbo/simpan_log_aktivitas.php",
                  data: {
                    proses_user: current_user_id,
                    log_description: log_description,
                    log_tipe: log_tipe,
                    source: "dlg_spv_approval",
                    kode_spv: response.userid,
                    no_struk: current_order_no,
                    kode_barang: kode_barang,
                    qty_lama: currentQty,
                    uom_barang: uom,
                  },
                });
                /*addtional log aktivitas*/

                $.post(
                  "ajax/temp_trx_delete_line.php",
                  {
                    order_no: current_order_no,
                    kode_barang: kode_barang,
                    uom: uom,
                  },
                  function (response) {
                    if (response.success) {
                      $thisDialog.dialog("close");
                      refreshTable();
                    }
                  },
                  "json"
                );
              } // minus qty
              else {
                let qtyInput = btnminusdelete.siblings(".quantity");
                let currentQty = parseInt(qtyInput.text());
                if (currentQty > 1) {
                  // Agar tidak kurang dari 1
                  qtyInput.text(currentQty - 1);

                  var kode_barang = rowValues[1];
                  var harga_jual = rowValues[3];
                  harga_jual = harga_jual.substring(3);
                  harga_jual = harga_jual.replaceAll(".", "");

                  /*addtional log aktivitas*/
                  var log_description = "Edit Qty Barang#" + current_order_no;
                  var log_tipe = "DANGER";
                  $.ajax({
                    type: "POST",
                    url: "lib_dbo/simpan_log_aktivitas.php",
                    data: {
                      proses_user: current_user_id,
                      log_description: log_description,
                      log_tipe: log_tipe,
                      source: "dlg_spv_approval",
                      kode_spv: response.userid,
                      no_struk: current_order_no,
                      kode_barang: kode_barang,
                      qty_lama: currentQty,
                      qty_baru: currentQty - 1,
                      uom_barang: rowValues[4],
                    },
                  });
                  /*addtional log aktivitas*/

                  $.ajax({
                    url: "ajax/temp_trx_update_qty.php",
                    type: "GET",
                    dataType: "html",
                    data: {
                      orderno: current_order_no,
                      kode_barang: kode_barang,
                      qty: -1,
                      harga_jual: harga_jual,
                      disc: disc,
                      uom: uom,
                    },
                    success: function (data) {
                      $thisDialog.dialog("close");
                      refreshTable();
                    },
                  });
                } else $thisDialog.dialog("close"); // qty 1 tidak bisa di kurang
              }
            } else {
              $("#wrongpass").show();
            }
          },
          "json"
        );
        // OK button
      },
      Cancel: function () {
        $(this).dialog("close");
      },
    },
  });

  $(document).on("keydown", "#SpvPassword", function (e) {
    if (e.key === "Enter") {
      // Cegah form submit default (kalau ada)
      e.preventDefault();

      // Trigger tombol OK di dialog
      $("#DialogSpvApproval")
        .parent()
        .find("button:contains('OK')")
        .trigger("click");
    }
  });
});
