<?php
// models/SmartlockModel.php

require_once '../../config/config.php';

class Smartlock implements JsonSerializable {

    private $id;
    private $smartlockId;
    private $deviceName;
    private $userName;
    private $creationDate;
    private $allowedFromDate;
    private $allowedUntilDate;

    // Constructor to initialize the properties
    public function __construct($id, $smartlockId, $deviceName, $userName, $creationDate, $allowedFromDate, $allowedUntilDate) {
        $this->id = $id;
        $this->smartlockId = $smartlockId;
        $this->deviceName = $deviceName;
        $this->userName = $userName;
        $this->creationDate = $creationDate;
        $this->allowedFromDate = $allowedFromDate;
        $this->allowedUntilDate = $allowedUntilDate;
    }

    // Method to convert object to JSON
    public function jsonSerialize(): array {
        return [
            "id" => $this->id,
            "smartlockId" => $this->smartlockId,
            "deviceName" => $this->deviceName,
            "userName" => $this->userName,
            "creationDate" => $this->formatDate($this->creationDate),
            "allowedFromDate" => $this->formatDate($this->allowedFromDate),
            "allowedUntilDate" => $this->formatDate($this->allowedUntilDate),
        ];
    }

    private function formatDate($date) {
        if (!$date) return ""; // Handle empty dates

        $dateObj = new DateTime($date);
        return $dateObj->format('c'); // ISO 8601 format
    }

    // Getter methods
    public function getId() { return $this->id; }
    public function getSmartlockId() { return $this->smartlockId; }
    public function getDeviceName() { return $this->deviceName; }  
    public function getUserName() { return $this->userName; }
    public function getCreationDate() { return $this->creationDate; }
    public function getAllowedFromDate() { return $this->allowedFromDate; }
    public function getAllowedUntilDate() { return $this->allowedUntilDate; }
}
?>
