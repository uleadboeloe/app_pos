$(function () {
  $("#DialogVoucher").dialog({
    autoOpen: false,
    modal: true,
    width: 400,
    position: { my: "center top+50", at: "center top", of: window },
    open: function () {
      $("#DialogVoucher").load(
        "dialogbox/dlg_validasi_voucher.php",
        function () {
          $("#VoucherID").focus();
        }
      );
    },
    close: function () {
      $("#DialogVoucher").html(""); // Clear content on close.
    },
    buttons: {
      OK: function () {
        var ValidVoucher = $("#VoucherID").val();
        var ValidNilaiVoucher = $("#NilaiVoucher").val();
        // do here if OK pressed
        if (ValidVoucher === "" || ValidNilaiVoucher === "") {
          Swal.fire({
            title: "Error",
            text: "Masukan Kode Voucher yang Valid",
            icon: "error",
          });
          $("#VoucherID").focus();
          return;
        }

        if (ValidNilaiVoucher === "0") {
          Swal.fire({
            title: "Error",
            text: "Masukan Kode Voucher Sudah digunakan, Nilai Voucher 0",
            icon: "error",
          });
          $("#VoucherID").focus();
          return;
        }

        window.sessionStorage.setItem("kode_voucher", ValidVoucher);
        window.sessionStorage.setItem("nilai_voucher", ValidNilaiVoucher);
        //console.log("VALID KODE VOUCHER: " + ValidVoucher);
        //console.log("VALID NILAI VOUCHER: " + ValidNilaiVoucher);

        ReloadTable();
        //console.log(current_order_no);
        $(this).dialog("close");
      },
      Cancel: function () {
        $(this).dialog("close");
      },
    },
  });

  $("#BtnVoucher").click(function () {
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
          $("#DialogVoucher").dialog("open");
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

  function ReloadTable() {
    var kodevoucher = window.sessionStorage.getItem("kode_voucher");
    var nilaivoucher = window.sessionStorage.getItem("nilai_voucher");
    var element = document.getElementById("ItemTableVoucher");

    $("#txtKodeVoucher").val(kodevoucher);
    $("#txtNilaiVoucher").val(nilaivoucher);
    $("#TxtVoucher").text(nilaivoucher);
    element.classList.remove("hidden");

    /* refresh here */
    var point_member = window.sessionStorage.getItem("poin_member_id") || "0";
    point_member = point_member.replaceAll(".", "");
    var nilai_voucher = window.sessionStorage.getItem("nilai_voucher") || "0";
    nilai_voucher = nilai_voucher.replaceAll(".", "");
    $.ajax({
      url: "lib_dbo/refresh_scanned_item_table.php",
      type: "GET",
      data: {
        orderno: current_order_no,
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

        //current_order_no = onhold_order_no;

        $("#EditBoxScanItem").val("").focus();
        $("#TxtOrderNo").text(current_order_no);
      },
    });
  }
});
