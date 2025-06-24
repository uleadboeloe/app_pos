<?php
function obfuscate_php($code, $mode = 'simple') {
    // Hapus tag pembuka dan penutup PHP
    $code = preg_replace('/^\s*<\?php\s*/i', '', $code);
    $code = preg_replace('/\?>\s*$/i', '', $code);

    // Hapus komentar
    $code = preg_replace('!/\*.*?\*/!s', '', $code);
    $code = preg_replace('/\/\/.*$/m', '', $code);
    $code = preg_replace('/#.*$/m', '', $code);

    // Hapus spasi berlebih
    $code = preg_replace('/\s+/', ' ', $code);

    // Ganti nama variabel
    preg_match_all('/\$[a-zA-Z_]\w*/', $code, $matches);
    $vars = array_unique($matches[0]);

    $replacements = [];
    foreach ($vars as $i => $var) {
        $replacements[$var] = '$v' . $i;
    }
    $code = str_replace(array_keys($replacements), array_values($replacements), $code);

    // Mode encoding
    if ($mode === 'base64' || $mode === 'eval_base64') {
        $code = trim($code); // pastikan bersih
        $encoded = base64_encode($code);
        $code = "<?php eval(base64_decode('$encoded'));";
    } else {
        $code = "<?php " . $code;
    }

    return $code;
}


// === Eksekusi ===
$inputFile  = 'general_lib.php';
$backupFile = 'general_lib.php.bak';
$outputFile = 'general_lib.php'; // sama dengan input (overwrite setelah backup)
$mode = 'simple'; // Pilihan: 'simple' | 'base64' | 'eval_base64'

if (!file_exists($inputFile)) {
    exit("❌ File $inputFile tidak ditemukan!\n");
}

// Backup file asli
if (!rename($inputFile, $backupFile)) {
    exit("❌ Gagal membuat backup sebagai $backupFile\n");
}

// Lanjutkan obfuscation dari backup
$source_code = file_get_contents($backupFile);
$obfuscated_code = obfuscate_php($source_code, $mode);
file_put_contents($outputFile, $obfuscated_code);

echo "✅ Obfuscation selesai. File lama disimpan sebagai $backupFile<br/>";
echo "✅ File obfuscated disimpan sebagai $outputFile<br/>";
?>