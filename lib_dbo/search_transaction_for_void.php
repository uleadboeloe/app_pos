<?php
    include("../lib/mysql_pdo.php");
    include("../lib/mysql_connect.php");

    $no = $_GET['no'];

    $header = array();
    $detail = array();

    // Query for header
    $stmt = $db->prepare("SELECT * FROM dbo_header WHERE no_struk = :no AND is_voided = 0");
    $stmt->bindParam(':no', $no, PDO::PARAM_STR);
    $stmt->execute();
    $header = $stmt->fetch(PDO::FETCH_ASSOC);

    // Query for detail
    $stmt = $db->prepare("SELECT * FROM dbo_detail WHERE no_struk = :no AND qty_voided = 0");
    $stmt->bindParam(':no', $no, PDO::PARAM_STR);
    $stmt->execute();
    $detail = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Output as JSON
    echo json_encode(array(
        'header' => $header,
        'detail' => $detail
    ));
?>