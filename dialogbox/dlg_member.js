$(function () {
  $("#DialogMember").dialog({
    autoOpen: false,
    modal: true,
    width: 400,
    position: { my: "center top+50", at: "center top", of: window },
    open: function () {
      $("#DialogMember").load("dialogbox/dlg_member.php", function () {
        $("#SearchID").focus();
      });
    },
    close: function () {
      $("#DialogMember").html(""); // Clear content on close.
    },
    buttons: {
      OK: function () {
        var ValidIDMember = $("#ValidIDMember").val();
        var ValidPoinMember = $("#ValidPoinMember").val();
        // do here if OK pressed
        if (ValidIDMember === "") {
          Swal.fire({
            title: "Error",
            text: "Member Tidak Terdaftar. Silahkan Cek Kembali",
            icon: "error",
          });
          $("#SearchID").focus();
          return;
        }
        //alert("NO STRUK" + current_order_no);
        window.sessionStorage.setItem("member_id", ValidIDMember);
        window.sessionStorage.setItem("poin_member_id", ValidPoinMember);
        //console.log("VALID MEMBER ID: " + ValidIDMember);
        //console.log(current_order_no);
        ReloadTable();
        $(this).dialog("close");
      },
      Cancel: function () {
        $(this).dialog("close");
      },
    },
  });

  $("#BtnMember").click(function () {
    var point_member = window.sessionStorage.getItem("poin_member_id") || "0";
    point_member = point_member.replaceAll(".", "");
    var nilai_voucher = window.sessionStorage.getItem("nilai_voucher") || "0";
    nilai_voucher = nilai_voucher.replaceAll(".", "");

    console.log(current_order_no);
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
          $("#DialogMember").dialog("open");
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
    var memberid = window.sessionStorage.getItem("member_id");
    var pointmemberid = window.sessionStorage.getItem("poin_member_id");
    var element = document.getElementById("ItemTablePointMember");

    $("#txtMemberID").val(memberid);
    $("#TxtGrandTotalPoins").val(pointmemberid);
    $("#TxtGrandTotalPoin").text(pointmemberid);
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
