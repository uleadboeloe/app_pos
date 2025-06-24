<?php
    include "hni_api_lib.php";

    $ppn = 0; // PPN 10%
    $nilaipoin = 100; // nilai 1000 = 1 poin
    $cryptkey = '9cuQ6qWG4nUSriFU8aFZAdCPxRObRPYv'; // 32 karakter untuk AES-256

    function getConfig($key) {
        $config = json_decode(file_get_contents('config.json'), true);
        return $config[$key] ?? null;
    }
    
    function decryptData($encryptedData, $cryptkey) {
        $data = base64_decode($encryptedData);
        $ivLength = openssl_cipher_iv_length('AES-256-CBC');
        $iv = substr($data, 0, $ivLength);
        $encrypted = substr($data, $ivLength);
        return openssl_decrypt($encrypted, 'AES-256-CBC', $cryptkey, 0, $iv);
    }

    function formatRupiah(float $number): string {
        return number_format($number, 0, ',', '.');
    }

    function limitOnly45Chars(string $text): string {
        return substr($text, 0, 45);
    }

    function roundCustom(float $number): float {
        $lastTwoDigits = $number - floor($number / 100) * 100; // Ambil 2 digit terakhir
        
        if ($lastTwoDigits >= 50) {
            return ceil($number / 100) * 100; // Round up ke ratusan terdekat
        } else {
            return floor($number / 100) * 100; // Round down ke ratusan terdekat
        }
    }

    function getPossiblePayments(int $totalBelanja): array {
        $pecahan = [10000, 20000, 50000, 100000];
        $possiblePayments = [];
        
        foreach ($pecahan as $uang) {
            if ($uang > $totalBelanja) 
            {
                $possiblePayments[] = $uang;
                var_dump($possiblePayments);
            }
        }
        return $possiblePayments;
    }

    function writeLog($message) {
        $file = 'logs/app.log';
        $date = date("Y-m-d H:i:s");
        file_put_contents($file, "[$date] $message" . PHP_EOL, FILE_APPEND);
    }

    function generateNoStruk($pref, $lastno) {
        // Pastikan angka memiliki 6 digit dengan leading zero
        $formattedNumber = str_pad($lastno, 6, '0', STR_PAD_LEFT);
        
        // Gabungkan parameter sesuai format
        return $pref . $formattedNumber;
    }
    
    // Fungsi untuk ngecek member HNI
    function CheckMemberByPhone($memberPhone)
    {
            $apiUrl = 'https://api.hni.net/';
            //$bearerToken = 'MDM1OmF2b3NldmljZS10aBNrZXS=1';
            $bearerToken = 'rs4uQyL3tHZhKIdRItIfa4NRVP5iP38PCTGFQ2Cl1eyPK68HIT';
            $client = new ApiClient($apiUrl, $bearerToken);				
            /*
            $endpoint = '/avo/dev/customer/search_by_phone'; // DEVELOPMENT ONLY!
            FOR PRODCUTION USE BELOW!
            */
            $endpoint = '/avo/customer/search_by_phone';
        
            $jsonBody = json_encode(['customer_phone' => $memberPhone]);				    		
            try { $r = $client->postRequest($endpoint, $jsonBody);} catch (Exception $e) {$r = $e->getMessage();}			
            //return strstr($r, '"code":"000"') ? true : false;
        return $r;
    }    

    function CheckMemberByID($memberID)
    {
            $apiUrl = 'https://api.hni.net/';
            //$bearerToken = 'MDM1OmF2b3NldmljZS10aBNrZXS=1';
            $bearerToken = 'rs4uQyL3tHZhKIdRItIfa4NRVP5iP38PCTGFQ2Cl1eyPK68HIT';
            $client = new ApiClient($apiUrl, $bearerToken);				
            /*
            $endpoint = '/avo/dev/customer/search_by_id'; // DEVELOPMENT ONLY!
            FOR PRODCUTION USE BELOW!
            */	
            $endpoint = '/avo/customer/search_by_id';
        
            $jsonBody = json_encode(['customer_id' => $memberID]);				    		
            try { $r = $client->postRequest($endpoint, $jsonBody);} catch (Exception $e) {$r = $e->getMessage();}			
            //return strstr($r, '"code":"000"') ? true : false;/**/
        return $r;
    } 
    
    
?>