<?php
// Credenciales de la base de datos.
// Usa variables de entorno si existen (Docker), o valores por defecto para XAMPP local.
$servername = getenv('DB_HOST') ?: "localhost";
$username   = getenv('DB_USER') ?: "root";
$password   = getenv('DB_PASS') ?: "";
$dbname     = getenv('DB_NAME') ?: "farmacia_db";

// Crear la conexión a la base de datos
$conexion = new mysqli($servername, $username, $password, $dbname);

// Verificar si la conexión falló
if ($conexion->connect_error) {
    // Detener la ejecución y mostrar el error si la conexión no se puede establecer
    die("Error de conexión a la base de datos: " . $conexion->connect_error);
}

// Opcional: Establecer el conjunto de caracteres a utf8mb4 para soportar caracteres especiales y emojis
$conexion->set_charset("utf8mb4");
