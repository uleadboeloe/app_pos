<?php

class ApiClient
{
    private $apiUrl;
    private $bearerToken;

    public function __construct($apiUrl, $bearerToken)
    {
        $this->apiUrl = $apiUrl;
        $this->bearerToken = $bearerToken;
    }

    public function postRequest($endpoint, $jsonBody)
    {
        $ch = curl_init();
        $url = $this->apiUrl . $endpoint;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonBody);
        $headers = [
            'Authorization: Bearer ' . $this->bearerToken,
            'Content-Type: application/json',
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new Exception('Request Error: ' . curl_error($ch));
        }
        curl_close($ch);

        return $response;
    }
    
}
?>

