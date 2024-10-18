<?php
require_once '../models/UserModel.php';

class RegisterController
{
    private $db;

    public function __construct()
    {
        // Connect to the database
        $this->db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->db->connect_error) {
            throw new Exception('Connection error: ' . $this->db->connect_error);
        }
    }

    public function register($data)
    {
        // Validate data (you can add more validations)
        if ($data['password'] !== $data['confirmPassword']) {
            throw new Exception("Passwords do not match");
        }

        // Hash the password for security
        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);

        // Insert user data into the database
        $stmt = $this->db->prepare("INSERT INTO users (email, name, password) VALUES (?, ?, ?)");
        $stmt->bind_param('sss', $data['email'], $data['name'], $hashedPassword);

        if ($stmt->execute()) {
            return true;
        } else {
            throw new Exception("Error during registration: " . $stmt->error);
        }
    }
}
?>
