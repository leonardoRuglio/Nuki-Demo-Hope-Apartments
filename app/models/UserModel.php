<?php 

require_once '../../config/config.php';

//class that implements JsonSerializable
class ModelUser implements JsonSerializable {
 
    private $userId;
    private $accountId;
    private $email;
    private $userName;  // Agregado userName
    private $name;
    private $creationDate;

    // Constructor initializes the Smartlock object with data
    public function __construct($data)
    {
        $this->userId = $data['accountUserId'] ?? "Server Error";
        $this->accountId = $data['accountId'] ?? "Server Error";
        $this->email = $data['email'] ?? "Server Error";
        $this->userName = $data['userName'] ?? "Server Error"; // Inicializa userName
        $this->name = $data['name'] ?? "Server Error";
        $this->creationDate = $data['creationDate'] ?? null;
    }

    // Implement jsonSerialize method to specify data to be serialized to JSON
    public function jsonSerialize(): array
    {
       return [
          "userId" => $this->userId,
          "accountId" => $this->accountId,
          "email" => $this->email,
          "userName" => $this->userName, // Incluye userName
          "name" => $this->name,
          "creationDate" => $this->creationDate,
       ];
    }

    // Métodos para obtener datos del usuario
    public function getUserId() { return $this->userId; }
    public function getAccountId() { return $this->accountId; }
    public function getEmail() { return $this->email; }
    public function getUserName() { return $this->userName; } // Método para obtener userName
    public function getName() { return $this->name; }
    public function getCreationDate() { return $this->creationDate; }
}

?>
