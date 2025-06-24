<?php
// Fungsi untuk ambil jam sekarang dengan millisecond
function getCurrentTimeWithMilliseconds() {
    $microtime = microtime(true); // float, e.g., 1615974467.1234
    $milliseconds = sprintf("%03d", ($microtime - floor($microtime)) * 1000); // Ambil 3 digit milli
    return date('H:i:s') . '.' . $milliseconds; // Format: HH:MM:SS.mmm
}

// Fungsi utama untuk membuat hash unik 16 karakter (istilah bro edi = random_code )
function CreateUniqueHash16() {
    date_default_timezone_set('Asia/Jakarta');
    $tanggal = date('Y-m-d'); // Ambil tanggal sekarang
    $jam = getCurrentTimeWithMilliseconds(); // Ambil jam + millisecond

    $input = $tanggal . ' ' . $jam; // Gabungkan menjadi satu string
    $hash = hash('sha256', $input); // Hash SHA256
    return substr($hash, 0, 16); // Potong jadi 16 karakter
}

// Contoh penggunaan:
//$hash16 = CreateUniqueHash16();
//echo $hash16; // Output contoh: a3f5b2c6d91e4f7a
?>
