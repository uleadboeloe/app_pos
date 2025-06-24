$(function () {
  $("#DialogConfirmHold").dialog({
    autoOpen: false,
    modal: true,
    width: 320,
    position: { my: "center top", at: "top+20%", of: window }, // tengah atas dari bagian atas nambah 20%
    open: function () {
      $("#DialogConfirmHold").load("dialogbox/dlg_confirm_hold.php");
    },
    close: function () {
      $("#DialogConfirmHold").html(""); // Clear content on close.
    },
    buttons: {
      OK: function () {
        // update status temp trx
        $.ajax({
          type: "GET",
          url: "ajax/temp_trx_update_status.php",
          data: { order_no: current_order_no, status: "ONHOLD" },
          success: function (response) {
            window.sessionStorage.setItem("onhold_order_no", current_order_no);
            current_lastno_struk_no++;
            current_order_no = prefix_struk_no + pad(current_lastno_struk_no, 6);
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
                //console.log("Onhold No: " + window.sessionStorage.getItem("onhold_order_no"));
                window.location.href = "kasir.php";
                $("#TxtOrderNo").text(current_order_no);
                $("#EditBoxScanItem").val("").focus();
              },
            });
          },
        });

        $(this).dialog("close");
      },
      Cancel: function () {
        $(this).dialog("close");
      },
    },
  });

  $("#BtnHold").click(function () {
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
          $("#DialogConfirmHold").dialog("open");
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
