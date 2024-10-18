<?php
require_once '../../config/config.php';
require_once '../models/SmartlockMolde.php';


// Crear instancia del controlador
$controller = new SmartlockController();

try {
    // Obtener los datos en formato JSON
    header('Content-Type: application/json'); // Asegura que la respuesta sea JSON
    echo $controller->fetchSmartlockDataAsJson(); // Retorna el JSON puro
} catch (Exception $e) {
    http_response_code(500); // Indica un error en el servidor
    echo json_encode(['error' => 'Unable to fetch Smartlock data.']); // Respuesta de error en JSON
}
?>

 index.php
