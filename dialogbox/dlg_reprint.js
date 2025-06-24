$(function () {
  $("#DialogReprint").dialog({
    autoOpen: false,
    modal: true,
    width: 400,
    position: { my: "center top+50", at: "center top", of: window },
    open: function () {
      $("#DialogReprint").load("dialogbox/dlg_reprint.php", function () {
        $("#NoStruk").focus();
      });
    },
    close: function () {},
    buttons: {
      OK: function () {
        var valueStatus = $("#Status").val();
        if (valueStatus == "") {
          Swal.fire({
            title: "Error",
            text: "Masukan Nomor Struk yang akan di reprint",
            icon: "error",
          });
          $("#NoStruk").focus();
          return;
        } else {
          /*addtional log aktivitas*/
          var log_description = "Reprint Struk#" + valueStatus;
          var log_tipe = "WARNING";
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

          window.location.href = "reprint!" + valueStatus;
        }
        /*$(this).dialog("close");*/
      },
      Cancel: function () {
        $(this).dialog("close");
      },
    },
  });

  $("#BtnReprint").click(function () {
    $("#DialogReprint").dialog("open");
  });
});
