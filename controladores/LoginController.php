<?php
require_once __DIR__ . '/../modelos/Usuario.php';

/**
 * Procesa la solicitud de inicio de sesión.
 * Espera JSON con 'username' y 'password'.
 * Devuelve JSON con el resultado.
 */
function handleLogin() {
    $data = json_decode(file_get_contents("php://input"), true);

    if (empty($data['username']) || empty($data['password'])) {
        http_response_code(400);
        echo json_encode(["exito" => false, "mensaje" => "Usuario y contraseña son obligatorios"]);
        return;
    }

    $usuario = new Usuario();
    $resultado = $usuario->login($data['username'], $data['password']);

    if ($resultado['exito']) {
        http_response_code(200);
    } else {
        http_response_code(400);
    }
    echo json_encode($resultado);
}
?>