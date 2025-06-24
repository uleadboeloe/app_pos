$(function () {
  $("#DialogCreditCard").dialog({
    autoOpen: false,
    modal: true,
    width: 400,
    position: { my: "center top+50", at: "center top", of: window },
    open: function () {
      $("#DialogCreditCard").load(
        "dialogbox/dlg_payment_creditcard.php",
        function () {
          $("#4digit").focus();
        }
      );
    },
    close: function () {
      $("#DialogCreditCard").html(""); // Clear content on close.
    },
    buttons: {
      OK: function () {
        // do here if OK pressed
        if ($("#4digit").val().length != 4) {
          $("#4digit").focus();
          return;
        } else if ($("#refno").val().length == 0) {
          $("#refno").focus();
          return;
        } else if ($("#approvecode").val().length != 6) {
          $("#approvecode").focus();
          return;
        } else {
          // do here if OK pressed
          let tendered = $("#EditBoxTendered").val();
          tendered = tendered.replaceAll(".", "");

          var member_id = window.sessionStorage.getItem("member_id");
          var point_member =
            window.sessionStorage.getItem("poin_member_id") || "0";
          point_member = point_member.replaceAll(".", "");
          var paymentType = "KREDIT";
          //console.log("Point Amount : " + point_member);
          //console.log("Member ID : " + member_id);

          let isChecked = $("#chkboxUsePoint").is(":checked") ? 1 : 0;
          //console.log("Checked Status : " + isChecked);
          if (isChecked == 0) point_member = 0; // jika tidak di centang, set pointmemberid ke 0
          var kode_voucher = window.sessionStorage.getItem("kode_voucher");
          var nilai_voucher =
            window.sessionStorage.getItem("nilai_voucher") || "0";
          nilai_voucher = nilai_voucher.replaceAll(".", "");

          //console.log("Nilai Voucer : " + nilai_voucher);
          //console.log("Kode Voucer : " + kode_voucher);
          /*addtional log aktivitas*/
          var log_description = "TENDER CREDIT CARD: " + current_order_no;
          var log_tipe = "SUCCESS";
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
              payment_type: paymentType,
              point_member: point_member,
              nilai_voucher: nilai_voucher,
              kode_voucher: kode_voucher,
              card_number: $("#4digit").val(),
              reff_code: $("#refno").val(),
              approval_code: $("#approvecode").val(),
              nama_kartu: $("#jenis_kartu").val(),
              mesin_edc: $("#mesin_edc").val(),
            },
            success: function (response) {
              //console.log(paymentType);
              if (response != "") {
                /*$.ajax({
                  type: "POST",
                  url: "lib_dbo/simpan_log.php",
                  data: {
                    proses_no: current_order_no,
                    current_no: proses_order_no,
                    is_recalled: isrecalled,
                    current_lastno: current_lastno_struk_no,
                  },
                });*/

                // update status temp trx
                $.ajax({
                  type: "GET",
                  url: "ajax/temp_trx_update_status.php",
                  data: { order_no: current_order_no, status: "PAID" },
                  success: function (response) {
                    //current_lastno_struk_no++;
                    //current_order_no =
                    //  prefix_struk_no + pad(current_lastno_struk_no, 6);
                    //update nomor struk
                    var isrecalled =
                      window.sessionStorage.getItem("isrecalled");
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
                          source: "KREDITPAYMENT",
                        },
                      });

                      window.sessionStorage.setItem("onhold_order_no", "");
                      window.sessionStorage.setItem("isrecalled", "0");
                    }

                    $.ajax({
                      type: "GET",
                      url: "lib_dbo/update_noseries_struk.php",
                      data: {
                        nomor_akhir: current_lastno_struk_no,
                        kode_store: current_kode_store,
                        kode_kasir: current_kode_kasir,
                      },
                      success: function (response) {
                        // redirect to kasir.php
                        if (isrecalled == "0") {
                          window.location.href =
                            "displaystruk!" + proses_order_no;
                        } else {
                          window.location.href =
                            "displaystruk!" + current_order_no;
                        }
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

  $("#BtnCreditCard").click(function () {
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
        if (Number(response) > 0) {
          $("#DialogCreditCard").dialog("open");
        } else {
          Swal.fire({
            title: "Error",
            text: "Silahkan Input Transaksi Terlebih Dahulu",
            icon: "error",
          });
        }
      },
    });
  });
});
