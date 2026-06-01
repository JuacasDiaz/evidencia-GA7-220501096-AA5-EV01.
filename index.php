<?php
/**
 * Punto de entrada de la API.
 * Analiza la URI y dirige la petición al controlador correspondiente.
 * 
 * URLs esperadas:
 *   POST /api/auth/registro
 *   POST /api/auth/login
 */
header("Content-Type: application/json; charset=UTF-8");

// Obtener la URI sin parámetros
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$metodo = $_SERVER['REQUEST_METHOD'];

// Solo se permiten peticiones POST en los endpoints definidos
if ($metodo !== 'POST') {
    http_response_code(405);
    echo json_encode(["exito" => false, "mensaje" => "Método no permitido"]);
    exit();
}

// Enrutamiento simple
switch ($uri) {
    case '/api/auth/registro':
        require_once __DIR__ . '/controladores/RegistroController.php';
        handleRegistro();
        break;

    case '/api/auth/login':
        require_once __DIR__ . '/controladores/LoginController.php';
        handleLogin();
        break;

    default:
        http_response_code(404);
        echo json_encode(["exito" => false, "mensaje" => "Ruta no encontrada"]);
        break;
}
?>