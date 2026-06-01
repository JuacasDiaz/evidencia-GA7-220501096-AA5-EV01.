<?php
require_once __DIR__ . '/../modelos/Usuario.php';

/**
 * Procesa la solicitud de registro.
 * Espera un JSON con 'username' y 'password'.
 * Devuelve JSON con el resultado.
 */
function handleRegistro() {
    // Obtener datos enviados como JSON
    $data = json_decode(file_get_contents("php://input"), true);

    // Validar que existan los campos
    if (empty($data['username']) || empty($data['password'])) {
        http_response_code(400);
        echo json_encode(["exito" => false, "mensaje" => "Usuario y contraseña son obligatorios"]);
        return;
    }

    $usuario = new Usuario();
    $resultado = $usuario->registrar($data['username'], $data['password']);

    // Responder con código HTTP 200 si éxito, 400 si error
    if ($resultado['exito']) {
        http_response_code(200);
    } else {
        http_response_code(400);
    }
    echo json_encode($resultado);
}
?>