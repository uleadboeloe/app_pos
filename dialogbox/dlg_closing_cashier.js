$(function () {
  $("#DialogClosingCashier").dialog({
    autoOpen: false,
    modal: true,
    width: 700,
    position: { my: "center top+50", at: "center top", of: window },
    open: function () {
      $("#DialogClosingCashier").load(
        "dialogbox/dlg_closing_cashier.php",
        function () {
          var jumlahTotal = $("#JumlahTotal").val();
          console.log("Jumlah Total: " + jumlahTotal);
          let okButton = $(this)
            .parent()
            .find(".ui-dialog-buttonpane button:contains('OK')");
          if (parseInt(jumlahTotal, 10) === 0) {
            okButton.hide();
          } else {
            okButton.show();
          }
        }
      );
    },
    close: function () {},
    buttons: {
      OK: function () {
        // set jumlah denominasi
        var denom = "";
        if ($("#c100000").val() == "") denom = denom + "0,";
        else denom = denom + $("#c100000").val() + ",";
        if ($("#c50000").val() == "") denom = denom + "0,";
        else denom = denom + $("#c50000").val() + ",";
        if ($("#c20000").val() == "") denom = denom + "0,";
        else denom = denom + $("#c20000").val() + ",";
        if ($("#c10000").val() == "") denom = denom + "0,";
        else denom = denom + $("#c10000").val() + ",";
        if ($("#c5000").val() == "") denom = denom + "0,";
        else denom = denom + $("#c5000").val() + ",";
        if ($("#c2000").val() == "") denom = denom + "0,";
        else denom = denom + $("#c2000").val() + ",";
        if ($("#c1000").val() == "") denom = denom + "0,";
        else denom = denom + $("#c1000").val() + ",";

        if ($("#c1000k").val() == "") denom = denom + "0,";
        else denom = denom + $("#c1000k").val() + ",";
        if ($("#c500k").val() == "") denom = denom + "0,";
        else denom = denom + $("#c500k").val() + ",";
        if ($("#c200k").val() == "") denom = denom + "0";
        else denom = denom + $("#c200k").val() + ",";
        if ($("#c100k").val() == "") denom = denom + "0";
        else denom = denom + $("#c100k").val();

        console.log(denom);

        $(this).dialog("close");

        // jalankan ajax utk update dbo_register
        var koderegister = window.sessionStorage.getItem("kode_register");
        console.log("Kode Register: " + koderegister);
        $.ajax({
          url: "lib_dbo/update_register_cashier.php",
          type: "GET",
          data: {
            koderegister: koderegister,
            denom: denom,
          },
          success: function (response) {
            window.location.href =
              "preview.closing.php?koderegister=" + koderegister;
          },
        });
      },
    },
  });

  $(document).on("keydown", "#c100000", function (e) {
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
  $(document).on("keydown", "#c50000", function (e) {
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
  $(document).on("keydown", "#c20000", function (e) {
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
  $(document).on("keydown", "#c10000", function (e) {
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
  $(document).on("keydown", "#c5000", function (e) {
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
  $(document).on("keydown", "#c2000", function (e) {
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
  $(document).on("keydown", "#c1000", function (e) {
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
  $(document).on("keydown", "#c1000k", function (e) {
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
  $(document).on("keydown", "#c500k", function (e) {
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
  $(document).on("keydown", "#c200k", function (e) {
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
  $(document).on("keydown", "#c100k", function (e) {
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

  $("#BtnLogout").click(function () {
    $("#DialogClosingCashier").dialog("open");
  });
});
