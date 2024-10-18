<?php
require_once '../../config/config.php';  
require_once '../models/UserModel.php';  
require_once '../controllers/LoginController.php';  

// Inicializar el controlador de login
$loginController = new LoginController();

// Verificar si el usuario está logueado
if (!$loginController->isLoggedIn()) {
    header('Location: LoginView.php'); // Redirigir al login si no está logueado
    exit();
}

// Clase para gestionar la API de usuario
class UserController
{
    private $api_url_user;
    private $token;

    public function __construct()
    {
        // Inicializar URL y token de la API
        $this->api_url_user = API_URL_USER;
        $this->token = API_TOKEN;
    }

    public function fectUserData()
    {
        // Opciones para la solicitud HTTP
        $options = [
            "http" => [
                "header" => "Authorization: Bearer " . $this->token . "\r\n" .
                    "Accept: application/json\r\n",
                "method" => "GET",
            ]
        ];

        $context = stream_context_create($options);
        // Obtener la respuesta desde la API
        $response = file_get_contents($this->api_url_user, false, $context);

        if ($response === FALSE) {
            throw new Exception('Error fetching User data');
        }
        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Error decoding JSON: ' . json_last_error_msg());
        }

        // Mapear los datos al objeto usuario
        return array_map(fn($item) => new ModelUser($item), $data);
    }

    public function fecthUserDataJson()
    {
        // Devolver los datos como JSON
        return json_encode($this->fectUserData());
    }
}
