<?php
    session_start();
    $user_id = $_SESSION['SESS_user_id'];
?>
<div class="widget-body">
    <div class="row-fluid">
        <div class="span12">     
            <div class="control-group">
                <label class="control-label" for="SearchID">Nomor Struk</label>
                <div class="controls">
                    <input class="span12" id="NoStruk" type="text" required/>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="StatusID">Status Nomor Struk</label>
                <input class="span12" id="Status" type="text" style="color:#FF0000; font-weight:800;" required disabled/>
                <input class="span12" id="KodeUser" type="text" style="color:#FF9900; font-weight:800;" value="<?php echo $user_id; ?>" required disabled/>
            </div>          
        </div>
    </div>
</div>
<script>
$(document).ready(function () {
    $('#NoStruk').keypress(function (e) {
    var key = e.which;
        if(key == 13)  // the enter key code
        {
            var valueNoStruk = $('#NoStruk').val();
            var valueUserId = $('#KodeUser').val();

            if (valueNoStruk == '') {
                Swal.fire({
                    title: "Error",
                    text: "Nomor struk Harus Diisi!",
                    icon: "error",
                });
                $("#NoStruk").focus();
                return;
            }

            if(valueNoStruk != ""){
                $.ajax({
                    type: "POST",
                    url: "ajax/cek_reprint_struk.php",
                    data: { no_struk: valueNoStruk,user_id: valueUserId },
                    success: function (response) {
                        if (response != ''){
                            $("#Status").val(response);
                        }else{
                            Swal.fire({
                                title: "Error",
                                text: "Nomor struk tidak ditemukan",
                                icon: "error",
                            });
                            $("#NoStruk").val("");
                            $("#Status").val("");
                        }
                    },
                });          
            }                
        }
    });

});
</script>
