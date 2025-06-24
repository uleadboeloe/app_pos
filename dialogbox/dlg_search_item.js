$(function () {
  $("#DialogSearch").dialog({
    autoOpen: false,
    modal: true,
    width: 950,
    position: { my: "center top+50", at: "center top", of: window },
    open: function () {
      $("#DialogSearch").load("dialogbox/dlg_search_item.php", function () {
        $("#SearchText").focus();
      });
    },
    close: function () {},
    buttons: {
      Cancel: function () {
        $(this).dialog("close");
      },
    },
  });

  $("#BtnSearch").click(function () {
    // cek voucher sdh di entry
    var nilai_voucher = window.sessionStorage.getItem("nilai_voucher") || "0";
    if (nilai_voucher == "0") $("#DialogSearch").dialog("open");
  });
});
