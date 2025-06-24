$(function () {
  var SudahAda = 0;

  $("#DialogOpeningCashier").dialog({
    closeOnEscape: false,
    autoOpen: true,
    modal: true,
    width: 400,
    position: { my: "center top+50", at: "center top", of: window },
    open: function () {
      $("#DialogOpeningCashier").load(
        "dialogbox/dlg_opening_cashier.php",
        function () {
          // pertama cek apakah ada register berjalan belum closing
          $.ajax({
            url: "lib_dbo/get_register_cashier.php",
            type: "GET",
            data: {
              kodekasir: window.current_kode_kasir,
              kodestore: window.current_kode_store,
            },
            success: function (response) {
              var responseData = JSON.parse(response);

              SudahAda = responseData.sudah_ada;

              if (SudahAda == 1) {
                var modalawal = responseData.modal_awal;
                console.log(modalawal);
                window.sessionStorage.setItem("modal_awal", modalawal);

                var koderegister = responseData.kode_register;
                window.sessionStorage.setItem("kode_register", koderegister);

                console.log("Modal Awal: " + modalawal);
                console.log("Kode Register: " + koderegister);

                console.log(
                  "Onhold No: " +
                    window.sessionStorage.getItem("onhold_order_no")
                );

                $("#DialogOpeningCashier").dialog("close");
              }
            },
          });

          const $passwordInput = $("#SpvPassword");
          $passwordInput.attr("autocomplete", "new-password").focus();
          $("<input>", {
            type: "text",
            style: "display:none",
          }).insertBefore($passwordInput);
          $("#ModalAwal").focus();
        }
      );

      $(this).parent().find(".ui-dialog-titlebar-close").hide(); // Hide the close button.
    },
    close: function () {
      $("#DialogLockCashier").html(""); // Clear content on close.
    },
    buttons: {
      OK: function () {
        // do here if OK pressed
        $thisDialog = $(this);
        if (SudahAda == 1) $thisDialog.dialog("close");

        spvpassword = $("#SpvPassword").val();

        $.post(
          "lib_dbo/validate_spv_password.php",
          { spvpassword: spvpassword },
          function (response) {
            if (response.success) {
              var spv_userid = response.userid;
              var modalawal = $("#ModalAwal").val();
              modalawal = modalawal.replaceAll(".", "");
              window.sessionStorage.setItem("modal_awal", modalawal);

              $.ajax({
                url: "lib_dbo/insert_register_cashier.php",
                type: "GET",
                data: {
                  kodekasir: window.current_kode_kasir,
                  kodestore: window.current_kode_store,
                  kodespv: spv_userid,
                  modalawal: modalawal,
                },
                success: function (response) {
                  var responseData = JSON.parse(response);
                  var koderegister = responseData.kode_register;
                  window.sessionStorage.setItem("kode_register", koderegister);

                  //console.log("Modal Awal: " + modalawal);
                  //console.log("Kode Register: " + koderegister);
                  /*addtional log aktivitas*/
                  var log_description =
                    "Open Register#" +
                    current_kode_kasir +
                    "#Modal Awal: " +
                    modalawal +
                    "#Kode Register: " +
                    koderegister;
                  var log_tipe = "INFO";
                  $.ajax({
                    type: "POST",
                    url: "lib_dbo/simpan_log_aktivitas.php",
                    data: {
                      proses_user: current_user_id,
                      log_description: log_description,
                      log_tipe: log_tipe,
                      source: "dlg_opening_cashier",
                    },
                  });
                  /*addtional log aktivitas*/

                  $("#ModalAwal").prop("readonly", false);
                  $("#ModalBelumSetor").hide();
                  $thisDialog.dialog("close");
                },
              });

              // response spv pass
            } else {
              $("#wrongpass").show();
            }
          },
          "json"
        );
        // OK button
      },
    },
  });
});
