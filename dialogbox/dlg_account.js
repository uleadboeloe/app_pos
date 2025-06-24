$(function () {
  $("#DialogAccount").dialog({
    autoOpen: false,
    modal: true,
    width: 400,
    position: { my: "center top+50", at: "center top", of: window },
    open: function () {
      $("#DialogAccount").load("dialogbox/dlg_account.php", function () {
        $.ajax({
          url: "lib_dbo/get_register_cashier.php",
          type: "GET",
          data: {
            kodekasir: window.current_kode_kasir,
            kodestore: window.current_kode_store,
          },
          success: function (response) {
            var responseData = JSON.parse(response);
            var modalawal = responseData.modal_awal;
            var tanggal = responseData.tanggal;
            var jam = responseData.jam;
            var koderegister = responseData.kode_register;
            modalawal = formatRibuan(modalawal);
            $("#KodeKasir").val(window.current_kode_kasir);
            $("#Tanggal").val(tanggal);
            $("#Jam").val(jam);
            $("#ModalAwal").val(modalawal);
            $("#KodeRegister").val(koderegister);
            var onhold = window.sessionStorage.getItem("onhold_order_no");
            console.log(onhold);
            $("#OnHold").val(onhold);
          },
        });
      });
    },
    close: function () {
      $("#DialogAccount").html(""); // Clear content on close.
    },
    buttons: {
      OK: function () {
        // do here if OK pressed
        $(this).dialog("close");
      },
    },
  });

  $("#BtnAccount").click(function () {
    $("#DialogAccount").dialog("open");
  });
});
