<?php
require_once '../../config/config.php';

class LoginModel {
    private $db;

    public function __construct() {
        $this->db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->db->connect_error) {
            die('Connection error: ' . $this->db->connect_error);
        }
    }

    // Register user with hashed password
    public function registerUser($email, $password) {
        // Hash the password before saving
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Prepare the SQL statement
        $stmt = $this->db->prepare("INSERT INTO users (email, password) VALUES (?, ?)");

        if (!$stmt) {
            return ['success' => false, 'error' => 'Error preparing the query: ' . $this->db->error];
        }

        // Bind the email and hashed password to the statement
        $stmt->bind_param("ss", $email, $hashedPassword);
        
        // Execute the statement
        if ($stmt->execute()) {
            return ['success' => true];
        }

        return ['success' => false, 'error' => 'Error executing the query: ' . $stmt->error];
    }

    public function getUserByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        if (!$stmt) {
            echo "Error preparing the query: " . $this->db->error;
            return null;
        }
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            echo "Error: User not found.";
            return null;
        }
    }

    public function __destruct() {
        $this->db->close(); // Close connection
    }
}
?>
