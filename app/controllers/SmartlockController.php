<?php

require_once '../../config/config.php';
require_once '../models/SmartlockModel.php';

class SmartlockController
{
    private $authApiUrl;
    private $deviceApiUrl;
    private $token;

    public function __construct()
    {
        $this->authApiUrl = API_URL;
        $this->deviceApiUrl = API_URL_NUKI_DEVICES;  // Devices API URL
        $this->token = API_TOKEN;
    }

    // Fetches and combines data from both the auth and devices APIs
    private function fetchSmartlockAuthData()
    {
        $authData = $this->fetchDataFromApi($this->authApiUrl); // Fetch from auth API
        $deviceData = $this->fetchDataFromApi($this->deviceApiUrl); // Fetch from devices API

        // Create a map of smartlockId to device name
        $deviceNameMap = [];
        foreach ($deviceData as $device) {
            $deviceNameMap[$device['smartlockId']] = $device['name'];
        }

        // Combine auth data and device data, use userName instead of authName
        return array_map(function ($item) use ($deviceNameMap) {
            $smartlockId = $item['smartlockId'] ?? "Unknown";
            $deviceName = $deviceNameMap[$smartlockId] ?? "Unknown Device";
            $userName = $item['name'] ?? "Unknown User";
            $authId = $item['authId'] ?? null;

            if (!$authId) {
                throw new Exception("Missing Auth ID for Smartlock: " . $smartlockId);
            }

            return new Smartlock(
                $item['id'] ?? null,
                $smartlockId,
                $deviceName,
                $userName,
                $item['creationDate'] ?? null,
                $item['allowedFromDate'] ?? null,
                $item['allowedUntilDate'] ?? null,
                $authId
            );
        }, $authData);
    }

    // Helper method to fetch data from a given API
    private function fetchDataFromApi($apiUrl)
    {
        $options = [
            "http" => [
                "header" => "Authorization: Bearer " . $this->token . "\r\n" .
                    "Accept: application/json\r\n",
                "method" => "GET",
            ]
        ];

        $context = stream_context_create($options);
        $response = @file_get_contents($apiUrl, false, $context);

        if ($response === FALSE) {
            throw new Exception('Error fetching data from API');
        }

        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Error decoding JSON: ' . json_last_error_msg());
        }

        return $data;
    }

    // Converts smartlock data to JSON, sorting it alphabetically by device name
    public function fetchSmartlockDataAsJson()
    {
        $smartlocks = $this->fetchSmartlockAuthData();

        // Sort the smartlocks array by deviceName alphabetically
        usort($smartlocks, function ($a, $b) {
            return strcmp($a->getDeviceName(), $b->getDeviceName());
        });

        $formattedData = array_map(function ($smartlock) {
            return [
                'id' => $smartlock->getId(),
                'smartlockId' => $smartlock->getSmartlockId(),
                'deviceName' => $smartlock->getDeviceName(),
                'userName' => $smartlock->getUserName(),
                'creationDate' => date('Y-m-d', strtotime($smartlock->getCreationDate())),
                'allowedFromDate' => date('Y-m-d', strtotime($smartlock->getAllowedFromDate())),
                'allowedUntilDate' => date('Y-m-d', strtotime($smartlock->getAllowedUntilDate()))
            ];
        }, $smartlocks);

        return json_encode($formattedData);
    }
}
?>

