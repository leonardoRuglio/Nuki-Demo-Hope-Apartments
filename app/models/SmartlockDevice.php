<?php

require_once '../../config/config.php';

//class that implements JsonSerializable
class SmartlockDevice implements JsonSerializable
{

    private $id;
    private $smartlockId;
    private $accountId;
    private $name;


    // Constructor to initialize the properties (if needed)
    public function __construct($id, $smartlockId, $accountId, $name)
    {
        $this->id = $id;
        $this->smartlockId = $smartlockId;
        $this->accountId = $accountId;
        $this->name = $name;
    }

    public function jsonSerialize(): array
    {
        return [
            "id" => $this->id,
            "smartlockId" => $this->smartlockId,
            "accountId" => $this->accountId,
            "name" => $this->name
        ];
    }

    // Getter methods      
    public function getId()
    {
        return $this->id;
    }
    public function getSmartlockId()
    {
        return $this->smartlockId;
    }
    public function getAccountId()
    {
        return $this->accountId;
    }
    public function getName()
    {
        return $this->name;
    }
}
