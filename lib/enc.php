<?php

function getConfig($key) {
    $config = json_decode(file_get_contents('../config.json'), true);
    return $config[$key] ?? null;
}

function encryptData($data) {
    $key = getConfig('encryption_key');
    if (!$key) {
        throw new Exception('Encryption key not found in config.');
    }
    $iv = random_bytes(openssl_cipher_iv_length('AES-256-CBC'));
    $encrypted = openssl_encrypt($data, 'AES-256-CBC', $key, 0, $iv);
    return base64_encode($iv . $encrypted);
}

$inputText = 'A102';

$encryptedText = encryptData($inputText);

// Simpan ke config.json
$config = json_decode(file_get_contents('../config.json'), true);
$config['KodeToko'] = $encryptedText;
file_put_contents('../config.json', json_encode($config, JSON_PRETTY_PRINT));

echo "Encrypted text saved to config.json\n";
?>
