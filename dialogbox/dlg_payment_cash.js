$(function () {
  var okButton;
  $("#DialogPaymentCash").dialog({
    autoOpen: false,
    modal: true,
    width: 400,
    position: { my: "center top+50", at: "center top", of: window },
    open: function () {
      $("#DialogPaymentCash").load(
        "dialogbox/dlg_payment_cash.php",
        function () {
          var total = formatRibuan(window.totaltrx);
          $("#Total").val(total);
          $("#Tendered").focus();
          okButton = $(this)
            .parent()
            .find(".ui-dialog-buttonpane button:contains('OK')");
          okButton.hide();
        }
      );
    },
    close: function () {
      $("#DialogPaymentCash").html(""); // Clear content on close.
    },
    buttons: {
      OK: function () {
        // do here if OK pressed
        var member_id = window.sessionStorage.getItem("member_id");
        var point_member =
          window.sessionStorage.getItem("poin_member_id") || "0";
        point_member = point_member.replaceAll(".", "");
        var kode_register = window.sessionStorage.getItem("kode_register");
        if (window.ispoinchecked == 0) point_member = 0;

        console.log("Point Amount : " + point_member);
        console.log("Member ID : " + member_id);
        console.log("Kode Register : " + kode_register);

        var kode_voucher = window.sessionStorage.getItem("kode_voucher");
        var nilai_voucher =
          window.sessionStorage.getItem("nilai_voucher") || "0";
        nilai_voucher = nilai_voucher.replaceAll(".", "");

        console.log("Nilai Voucer : " + nilai_voucher);
        console.log("Kode Voucer : " + kode_voucher);

        var tendered = $("#Tendered").val();
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
            cust_code: member_id,
            point_member: point_member,
            nilai_voucher: nilai_voucher,
            kode_voucher: kode_voucher,
            payment_type: "CASH",
            kode_register: kode_register,
          },
          success: function (response) {
            if (response != "") {
              // update status temp trx
              $.ajax({
                type: "GET",
                url: "ajax/temp_trx_update_status.php",
                data: { order_no: current_order_no, status: "PAID" },
                success: function (response) {
                  var isrecalled = window.sessionStorage.getItem("isrecalled");
                  if (isrecalled == "0") {
                    // update nomor struk hanya jika tidak ada onhold
                    current_lastno_struk_no++;
                    current_order_no =
                      prefix_struk_no + pad(current_lastno_struk_no, 6);
                  } else {
                    //console.log("EMPTY DARI CASH");

                    $.ajax({
                      type: "POST",
                      url: "lib_dbo/simpan_log.php",
                      data: {
                        proses_no: current_order_no,
                        source: "CASHPAYMENT",
                      },
                    });

                    window.sessionStorage.setItem("onhold_order_no", "");
                    window.sessionStorage.setItem("isrecalled", "0");
                    window.sessionStorage.setItem("onhold_status", "");
                  }

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
                      // cetak struk, di sini
                      if (isrecalled == "0") {
                        window.location.href =
                          "preview.kasir.php?nobon=" +
                          proses_order_no +
                          "&kasir=" +
                          current_user_id +
                          "&store=" +
                          current_kode_store;
                        //"displaystruk!" + proses_order_no;
                      } else {
                        window.location.href =
                          "preview.kasir.php?nobon=" +
                          current_order_no +
                          "&kasir=" +
                          current_user_id +
                          "&store=" +
                          current_kode_store;
                        //"displaystruk!" + current_order_no;
                      }
                    },
                  });
                },
              });
            }
          },
        });

        $(this).dialog("close");
      },
      Cancel: function () {
        $(this).dialog("close");
      },
    },
  });

  $(document).on("keydown", "#Tendered", function (e) {
    // Allow: backspace (8), delete (46), tab (9), escape (27), enter (13), arrows (37–40), home (36), end (35)
    if (
      $.inArray(e.keyCode, [8, 9, 13, 27, 35, 36, 37, 38, 39, 40, 46]) !== -1 ||
      // Allow: Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
      (e.ctrlKey === true && [65, 67, 86, 88].indexOf(e.keyCode) !== -1)
    ) {
      return;
    }

    // Ensure that it is a number and stop the keypress if not a number
    if (
      (e.keyCode < 48 || e.keyCode > 57) &&
      (e.keyCode < 96 || e.keyCode > 105)
    ) {
      e.preventDefault();
    }
  });

  $(document).on("keyup", "#Tendered", function (e) {
    var value = $(this).val();
    value = Number(value);
    var totaltrx = $("#Total").val();
    totaltrx = totaltrx.replaceAll(".", "");
    totaltrx = Number(totaltrx);
    var kembali = value - totaltrx;
    if (kembali >= 0) {
      $("#Kembali").val(formatRibuan(kembali));
      okButton.show();
    } else {
      $("#Kembali").val("");
      okButton.hide();
    }
  });
});
