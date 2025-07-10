<?php
include("../lib/mysql_pdo.php");
include("../lib/mysql_connect.php");
include("../admin/library/fungsi.php");

session_start();
$kode_kasir = $_SESSION['SESS_user_id'];
$kode_store = $_SESSION['SESS_kode_store'];
$kode_register = $_SESSION['SESS_kode_register'];
$NamaKasir = getNamaUser($kode_kasir);

?>
    <div class="widget-body">
    <div class="row-fluid">
        <div class="span12">
            <div class="control-group" style="margin-bottom: 0px;">
                <label class="control-label">Kode Kasir</label>
                <div class="controls"><input class="span12" id="KodeKasir" type="text" value="" readonly/></div>
            </div>
            <div class="control-group" style="margin-bottom: 0px;">
                <label class="control-label">Nama Kasir</label>
                <div class="controls"><input class="span12" id="NamaKasir" type="text" value="<?php echo $NamaKasir;    ?>" readonly/></div>
            </div>            
            <div class="control-group" style="margin-bottom: 0px;">
                <label class="control-label">Tanggal Check In</label>
                <div class="controls"><input class="span12" id="Tanggal" type="text" value="" readonly/></div>
            </div>
            <div class="control-group" style="margin-bottom: 0px;">
                <label class="control-label">Jam Check In</label>
                <div class="controls"><input class="span12" id="Jam" type="text" value="" readonly/></div>
            </div>
            <div class="control-group" style="margin-bottom: 0px;">
                <label class="control-label">Modal Awal</label>
                <div class="controls"><input class="span12" id="ModalAwal" type="text" value="" readonly/></div>
            </div>
            <div class="control-group" style="margin-bottom: 0px;">
                <label class="control-label">Kode Register</label>
                <div class="controls"><input class="span12" id="KodeRegister" type="text" value="" readonly/></div>
            </div>
            <div class="control-group" style="margin-bottom: 0px;display:none;">
                <label class="control-label">On Hold Transaction No.</label>
                <div class="controls"><input class="span12" id="OnHold" type="text" value="" readonly/></div>
            </div>
        </div>
    </div>
</div>