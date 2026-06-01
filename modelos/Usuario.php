<?php
require_once __DIR__ . '/../config/database.php';

/**
 * Clase que interactúa con la tabla 'usuarios'.
 * Contiene métodos para registrar y autenticar.
 */
class Usuario {
    private $conn;
    private $tabla = "usuarios";

    public function __construct() {
        $db = new Database();
        $this->conn = $db->obtenerConexion();
    }

    /**
     * Registra un nuevo usuario.
     * @param string $username Nombre de usuario.
     * @param string $password Contraseña (se almacenará cifrada).
     * @return array Respuesta con clave 'exito' y 'mensaje'.
     */
    public function registrar($username, $password) {
        // Verificar si el usuario ya existe
        $query = "SELECT id FROM " . $this->tabla . " WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return ["exito" => false, "mensaje" => "El usuario ya está registrado"];
        }

        // Cifrar contraseña con bcrypt
        $hash = password_hash($password, PASSWORD_BCRYPT);

        // Insertar nuevo usuario
        $query = "INSERT INTO " . $this->tabla . " (username, password) VALUES (:username, :password)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":password", $hash);

        if ($stmt->execute()) {
            return ["exito" => true, "mensaje" => "Registro exitoso"];
        } else {
            return ["exito" => false, "mensaje" => "Error al registrar"];
        }
    }

    /**
     * Inicia sesión verificando credenciales.
     * @param string $username Nombre de usuario.
     * @param string $password Contraseña en texto plano.
     * @return array Respuesta con clave 'exito' y 'mensaje'.
     */
    public function login($username, $password) {
        // Buscar usuario por username
        $query = "SELECT id, password FROM " . $this->tabla . " WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            return ["exito" => false, "mensaje" => "Error en la autenticación"];
        }

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar contraseña
        if (password_verify($password, $row['password'])) {
            return ["exito" => true, "mensaje" => "Autenticación satisfactoria"];
        } else {
            return ["exito" => false, "mensaje" => "Error en la autenticación"];
        }
    }
}
?>