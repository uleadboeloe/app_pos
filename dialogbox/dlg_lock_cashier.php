<?php
    session_start();
    $user_id = $_SESSION['SESS_user_id'];
?>

<div class="widget-body">
    <div class="row-fluid">
        <div class="span12">
            <div class="control-group">
            <label class="control-label" for="UserId">User ID</label>
                <div class="controls"><input class="span12 numeric-input4" id="UserId" type="text" value="<?php echo $user_id; ?>" readonly /></div>
                <label class="control-label" for="UserPassword">Enter Password</label>
                <div class="controls"><input class="span12 numeric-input4" id="UserPassword" type="password" value="" autocomplete="new-password" required /></div>
                <label class="control-label" for="SpvPassword"><span id="wrongpass" style="font-size: 125%; color: red; display: none;">Wrong Password!</span></label>
            </div>
        </div>
    </div>
</div>
