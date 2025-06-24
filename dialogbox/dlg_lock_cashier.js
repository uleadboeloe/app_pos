$(function() {
    $("#DialogLockCashier").dialog({
        closeOnEscape: false,
        autoOpen: false,
        modal: true,
        width: 400,
        position: { my: "center top+50", at: "center top", of: window },
        open: function() {
            $("#DialogLockCashier").load("dialogbox/dlg_lock_cashier.php", function() {
                const $passwordInput = $("#UserPassword");
                $passwordInput.attr("autocomplete", "new-password").focus();
                $("<input>", {
                    type: "text",
                    style: "display:none"
                }).insertBefore($passwordInput);
            });

            $(this).parent().find(".ui-dialog-titlebar-close").hide(); // Hide the close button.
        },
        close: function() {
            $("#DialogLockCashier").html(""); // Clear content on close.
        },
        buttons: {
        "OK": function() {
            // do here if OK pressed
            $thisDialog = $(this);
            userid = $("#UserId").val();
            userpassword = $("#UserPassword").val();

            $.post("lib_dbo/validate_user_password.php", { userid: userid, userpassword: userpassword }, function(response) {
                if (response.success) {
                    $thisDialog.dialog("close");
                } else {
                    $('#wrongpass').show();
                }
            }, "json");
            // OK button
        }
    }
    });

    $("#BtnLock").click(function() {
        $("#DialogLockCashier").dialog("open");
    });

});