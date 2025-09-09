<?php
    session_start();

    unset($_SESSION['ls']); 
    unset($_SESSION['SESS_user_id']); 
    unset($_SESSION['SESS_user_name']); 
    unset($_SESSION['SESS_kode_kasir']); 

    $_SESSION = array();
    session_destroy();
    header("location:index.php");
    exit();
    
?>