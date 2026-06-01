# Servicio Web de Registro e Inicio de Sesion - PHP

Proyecto correspondiente a la evidencia de desempeno GA7-220501096-AA5-EV01.
Implementa una API REST en PHP puro con base de datos MySQL que permite registrar usuarios e iniciar sesion, devolviendo respuestas en formato JSON.

## Requisitos del sistema

- PHP 7.4 o superior (recomendado PHP 8.x)
- Servidor MySQL o MariaDB
- Servidor web Apache con modulo mod_rewrite habilitado, o el servidor embebido de PHP
- Git (para clonar el repositorio y versionar)
- Cliente HTTP para pruebas: Postman, Insomnia o curl

## Estructura del proyecto
```
|JUAN_CASTRILLON_AA5-EV01
├── config/
│ └── database.php # Clase de conexion PDO a la base de datos
├── controladores/
│ ├── RegistroController.php # Logica del endpoint de registro
│ └── LoginController.php # Logica del endpoint de inicio de sesion
├── modelos/
│ └── Usuario.php # Operaciones de registro y autenticacion
├── index.php # Enrutador frontal de la API
├── .htaccess # Reglas de reescritura para Apache
├── base_de_datos.sql # Script de creacion de base de datos y tabla
└── README.md
```

## Configuracion de la base de datos

1. Iniciar el servidor MySQL.
2. Ejecutar el script incluido para crear la base de datos y la tabla:
   
   mysql -u root -p < base_de_datos.sql

Tambien puede importarse desde phpMyAdmin ejecutando el contenido de `base_de_datos.sql`.

3. Si las credenciales de conexion difieren de las predeterminadas (usuario: root, sin contrasena, host: localhost), editar el archivo `config/database.php` y modificar los atributos `$host`, `$usuario`, `$contrasena` y `$nombre_bd` segun corresponda.

```php
private $host = "localhost";
private $nombre_bd = "auth_db";
private $usuario = "root";
private $contrasena = "";
```

## Ejecucion de la API

Opcion A: Usar el servidor embebido de PHP (recomendado)
Abrir una terminal en la raiz del proyecto y ejecutar:


php -S localhost:8080

La API estara disponible en http://localhost:8080. Los endpoints se acceden mediante las rutas /api/auth/registro y /api/auth/login.

Opcion B: Usar Apache con .htaccess
Copiar la carpeta del proyecto en el directorio de publicacion del servidor web (htdocs, www, etc.).

Verificar que el modulo mod_rewrite este habilitado en Apache.

La API sera accesible desde http://localhost/NOMBRE_APELLIDO_AA5_EV01/ (o la ruta configurada). Si se coloca en una subcarpeta, el archivo .htaccess y el enrutador en index.php redirigiran automaticamente las peticiones.

## Endpoints disponibles
Registro de usuario
Metodo: POST

Ruta: /api/auth/registro

Cabecera: Content-Type: application/json

Cuerpo de ejemplo:

```json
{
  "username": "juan",
  "password": "1234"
}
```
```json
{
  "exito": true,
  "mensaje": "Registro exitoso"
}
```
## Posibles errores (400):

{"exito": false, "mensaje": "El usuario ya esta registrado"}

{"exito": false, "mensaje": "Usuario y contrasena son obligatorios"}

## Inicio de sesion
Metodo: POST

Ruta: /api/auth/login

Cabecera: Content-Type: application/json

Cuerpo de ejemplo:

```json
{
  "username": "juan",
  "password": "1234"
}
```

```json
{
  "exito": true,
  "mensaje": "Autenticacion satisfactoria"
}
```

## Posibles errores (400):

{"exito": false, "mensaje": "Error en la autenticacion"}

{"exito": false, "mensaje": "Usuario y contrasena son obligatorios"}

## Pruebas con curl
Desde otra terminal, mientras la API esta en ejecucion:

# Registro
curl -X POST http://localhost:8080/api/auth/registro -H "Content-Type: application/json" -d "{\"username\":\"juan\",\"password\":\"1234\"}"

# Login correcto
curl -X POST http://localhost:8080/api/auth/login -H "Content-Type: application/json" -d "{\"username\":\"juan\",\"password\":\"1234\"}"

# Login con contrasena incorrecta
curl -X POST http://localhost:8080/api/auth/login -H "Content-Type: application/json" -d "{\"username\":\"juan\",\"password\":\"incorrecta\"}"



## Notas tecnicas
Las contrasenas se cifran con el algoritmo bcrypt mediante la funcion password_hash, y se verifican con password_verify, nunca se almacenan en texto plano.

El enrutamiento se realiza en index.php mediante un analisis de la URI; se puede extender agregando nuevos casos al switch.

Todos los mensajes de respuesta estan en formato JSON y los codigos de estado HTTP reflejan el resultado (200 para exito, 400 para errores controlados, 405 para metodo no permitido, 404 para rutas inexistentes).

El codigo incluye comentarios explicativos en cada clase y funcion, atendiendo el requerimiento de la evidencia.
