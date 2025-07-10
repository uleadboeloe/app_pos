$(function () {
  $("#DialogConfirmRecall").dialog({
    autoOpen: false,
    modal: true,
    width: 320,
    position: { my: "center top", at: "top+20%", of: window }, // tengah atas dari bagian atas nambah 20%
    open: function () {
      $("#DialogConfirmRecall").load("dialogbox/dlg_confirm_recall.php");
    },
    close: function () {
      $("#DialogConfirmRecall").html(""); // Clear content on close.
    },
    buttons: {
      OK: function () {
        // update status temp trx
        //var onhold_order_no = window.sessionStorage.getItem("onhold_order_no");
        var onhold_order_no = $("#hold_transaksi").val();
        window.sessionStorage.setItem("onhold_order_no", onhold_order_no);
        window.sessionStorage.setItem("onhold_status", "RECALL");

        var isrecalled = window.sessionStorage.getItem("isrecalled") || "0";
        var statusrecalled =
          window.sessionStorage.getItem("onhold_status") || "";
        window.sessionStorage.setItem("isrecalled", "1");
        console.log("onhold no: " + onhold_order_no);
        console.log("isrecalled: " + isrecalled);
        console.log("status recalled: " + statusrecalled);

        $.ajax({
          type: "GET",
          url: "ajax/temp_trx_update_status.php",
          data: { order_no: onhold_order_no, status: "CURRENT" },
          success: function (response) {
            refreshTable();
            window.sessionStorage.setItem("onhold_order_no", "");
          },
        });

        $(this).dialog("close");
      },
      Cancel: function () {
        $(this).dialog("close");
      },
    },
  });

  /*
  $("#BtnRecall").click(function () {
    var point_member = window.sessionStorage.getItem("poin_member_id") || "0";
    point_member = point_member.replaceAll(".", "");
    var nilai_voucher = window.sessionStorage.getItem("nilai_voucher") || "0";
    nilai_voucher = nilai_voucher.replaceAll(".", "");
    var on_hold_number = window.sessionStorage.getItem("onhold_order_no") || "";

    $.ajax({
      type: "POST",
      url: "ajax/temp_trx_total_amount.php",
      data: {
        order_no: on_hold_number,
        pointmember: point_member,
        nilaivoucher: nilai_voucher,
      },

      success: function (response) {
        console.log("response recall: " + response);
        if (Number(response) > 0) {
          $("#DialogConfirmRecall").dialog("open");
        } else {
          Swal.fire({
            title: "Error",
            text: "Tidak Ada Transaksi Hold yang bisa di recall",
            icon: "error",
          });
        }
      },
    });
  });
  */

  $("#BtnRecall").click(function () {
    $.ajax({
      type: "POST",
      url: "ajax/temp_trx_total_amount_hold.php",
      data: {
        status: "ONHOLD",
      },

      success: function (response) {
        console.log("response recall: " + response);
        if (Number(response) > 0) {
          $("#DialogConfirmRecall").dialog("open");
        } else {
          Swal.fire({
            title: "Error",
            text: "Tidak Ada Transaksi Hold yang bisa di recall",
            icon: "error",
          });
        }
      },
    });
  });
});

function refreshTable() {
  var onhold_order_no = window.sessionStorage.getItem("onhold_order_no");

  var point_member = window.sessionStorage.getItem("poin_member_id") || "0";
  point_member = point_member.replaceAll(".", "");
  var nilai_voucher = window.sessionStorage.getItem("nilai_voucher") || "0";
  nilai_voucher = nilai_voucher.replaceAll(".", "");
  $.ajax({
    url: "lib_dbo/refresh_scanned_item_table.php",
    type: "GET",
    data: {
      orderno: onhold_order_no,
      pointmember: point_member,
      nilaivoucher: nilai_voucher,
    },
    success: function (response) {
      $("#ItemTable tbody").empty().html(response);

      let qty = $("#hiddenQty").val();
      let subtotal = $("#hiddenSubtotal").val();
      let ppnamt = $("#hiddenPpnAmt").val();
      let total = $("#hiddenTotal").val();

      // Update tampilan subtotal & total
      $("#TxtTotalItems").text(qty);
      $("#TxtSubTotal").text(subtotal);
      $("#TxtPPN").text(ppnamt);
      $("#TxtGrandTotal").text(total);

      $("#EditBoxTendered").val(total);
      $("#pTendered").css("color", "black");

      let option1 = $("#hiddenPecahan1").val();
      let option2 = $("#hiddenPecahan2").val();
      let option3 = $("#hiddenPecahan3").val();

      $("#BtnNominalOption1").text(formatRibuan(option1));
      $("#BtnNominalOption2").text(formatRibuan(option2));
      $("#BtnNominalOption3").text(formatRibuan(option3));

      current_order_no = onhold_order_no;

      $("#EditBoxScanItem").val("").focus();
      $("#TxtOrderNo").text(current_order_no);
    },
  });
}
