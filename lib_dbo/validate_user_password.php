<?php
    include("../lib/mysql_pdo.php");
    include("../lib/mysql_connect.php");

    $response = [
        'success' => false
    ];

    // Ambil password dari request (POST)
    $UserId         = $_POST['userid'] ?? '';
    $UserPassword   = $_POST['userpassword'] ?? '';

    if (!empty($UserPassword)) {

        $stmt = $db->prepare("SELECT * FROM dbo_user WHERE userid = :userid AND fl_active = 1");
        $stmt->execute(array(':userid' => $UserId));
        $results = $stmt->fetchAll();

        $valid =  password_verify ($UserPassword, $results[0]['userpass'] );

        if ($valid) {
            // Password cocok
            $response['success'] = true;
        } else {
            // Password tidak cocok
            $response['success'] = false;
        }
        
    }

    // Kembalikan hasil sebagai JSON
    header('Content-Type: application/json');
    echo json_encode($response);
?>
