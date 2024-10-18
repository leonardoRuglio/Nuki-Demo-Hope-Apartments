<?php
require_once '../../config/config.php';
require_once '../models/LoginModel.php';

class LoginController {
    private $model;

    public function __construct() {
        $this->model = new LoginModel();
        
        // Start the session if it hasn't been started yet
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Function to register a user
    public function register($email, $password) {
        // Register the user in the database
        $result = $this->model->registerUser($email, $password);
        if (!$result['success']) {
            echo "Error registering user: " . $result['error'];
        } else {
            echo "User registered successfully.";
        }
    }

    // User authentication
    public function authenticate($email, $password) {
        // Get the user from the model
        $user = $this->model->getUserByEmail($email);

        // Verify if the user exists and the password is correct
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_email'] = $email; // Store email in session
            return true;
        }

        echo "Error: Incorrect credentials.";
        return false;
    }

    public function isLoggedIn() {
        return isset($_SESSION['user_email']);
    }

    public function logout() {
        session_destroy(); // Destroy session on logout
    }
}
?>
