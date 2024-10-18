<?php

require_once '../../config/config.php';
require_once '../models/SmartlockDevice.php';

class smartlockDeviceController
{

    private  $apiUrl;
    private $token;

    public function __construct()
    {
        $this->apiUrl = API_URL_NUKI_DEVICES;
        $this->token = API_TOKEN;
    }

    public function fetchSmartlockDevice()
    {
        $options = [
            "http" => [
                "header" => "Authorization: Bearer " . $this->token . "\r\n" .
                    "Accept: application/json\r\n",
                "method" => "GET",
            ]
        ];

        $context = stream_context_create($options);
        $response = @file_get_contents($this->apiUrl, false, $context);


        if ($response === FALSE) {
            throw new Exception("Error fetching smarlock devices data");
        }

        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {

            throw new Exception("Error decoding JSON; " . json_last_error_msg());
        }

        return array_map(function ($item) {
            return new SmartlockDevice(
                $item['id'] ?? null,
                $item['smartlockId'] ?? "Server Error",
                $item['accountId'] ?? "Server Error",
                $item['name'] ?? "Server Error"
            );
        }, $data);
    }
}
