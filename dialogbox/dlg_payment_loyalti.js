$(function () {
  $("#DialogLoyalti").dialog({
    autoOpen: false,
    modal: true,
    width: 400,
    position: { my: "center top+50", at: "center top", of: window },
    open: function () {
      $("#DialogLoyalti").load(
        "dialogbox/dlg_payment_loyalti.php",
        function () {
          $("#MemberID").focus();
        }
      );
    },
    close: function () {
      $("#DialogLoyalti").html(""); // Clear content on close.
    },
    buttons: {
      OK: function () {
        // do here if OK pressed
        if ($("#refno").val().length == 0) {
          $("#refno").focus();
          return;
        } else {
          // do here if OK pressed
          let tendered = $("#EditBoxTendered").val();
          tendered = tendered.replace(".", "");

          $.ajax({
            type: "GET",
            url: "lib_dbo/insert_header_detail.php",
            dataType: "html",
            data: {
              order_no: current_order_no,
              tendered: tendered,
              user_id: current_user_id,
              user_name: current_user_name,
              kode_store: current_kode_store,
              payment_type: "EWALET",
              card_number: "QRIS",
              reff_code: $("#refno").val(),
              approval_code: $("#refno").val(),
            },
            success: function (response) {
              if (response == "success") {
                // update status temp trx
                $.ajax({
                  type: "GET",
                  url: "ajax/temp_trx_update_status.php",
                  data: { order_no: current_order_no, status: "PAID" },
                  success: function (response) {
                    current_lastno_struk_no++;
                    current_order_no =
                      prefix_struk_no + pad(current_lastno_struk_no, 6);
                    //update nomor struk
                    $.ajax({
                      type: "GET",
                      url: "lib_dbo/update_noseries_struk.php",
                      data: {
                        nomor_akhir: current_lastno_struk_no,
                        kode_store: current_kode_store,
                        kode_kasir: current_kode_kasir,
                      },
                      success: function (response) {
                        // cetak struk, di sini?
                        // redirect to kasir.php
                        window.location.href =
                          "preview.kasir.php?nobon=" + proses_order_no;
                      },
                    });
                  },
                });
              }
            },
          });

          $(this).dialog("close");
        }
      },
      Cancel: function () {
        $(this).dialog("close");
      },
    },
  });

  $("#BtnLoyalty").click(function () {
    var point_member = window.sessionStorage.getItem("poin_member_id") || "0";
    point_member = point_member.replaceAll(".", "");
    var nilai_voucher = window.sessionStorage.getItem("nilai_voucher") || "0";
    nilai_voucher = nilai_voucher.replaceAll(".", "");

    $.ajax({
      type: "POST",
      url: "ajax/temp_trx_total_amount.php",
      data: {
        order_no: current_order_no,
        pointmember: point_member,
        nilaivoucher: nilai_voucher,
      },
      success: function (response) {
        if (Number(response) > 0) $("#DialogLoyalti").dialog("open");
      },
    });
  });
});
