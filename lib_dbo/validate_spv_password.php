<?php
    include("../lib/mysql_pdo.php");
    include("../lib/mysql_connect.php");

    $response = [
        'success' => false,
        'userid' => null
    ];

    // Ambil password dari request (POST)
    $spvPassword = $_POST['spvpassword'] ?? '';

    if (!empty($spvPassword)) {
        // Query user dengan job_title mengandung 'SUPER'
        //$stmt = $db->prepare("SELECT userid, userpass FROM dbo_user WHERE job_title LIKE ?");
        $stmt = $db->prepare("SELECT userid, userpass, approval_code FROM dbo_user WHERE job_title LIKE ?");
        $stmt->execute(['%SUPER%']);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            /*
            if (password_verify($spvPassword, $row['userpass'])) {
                // Password cocok
                $response['success'] = true;
                $response['userid'] = $row['userid'];
                break;
            }
            */
            if ($spvPassword === $row['approval_code']) {
                // Approval cocok
                $response['success'] = true;
                $response['userid'] = $row['userid'];
                break;
            }
        }
    }

    // Kembalikan hasil sebagai JSON
    header('Content-Type: application/json');
    echo json_encode($response);
?>
