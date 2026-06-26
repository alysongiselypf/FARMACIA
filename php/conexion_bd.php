<?php
// Credenciales de la base de datos (ajusta según tu configuración de XAMPP)
$servername = "localhost";
$username = "root";
$password   = getenv('DB_PASSWORD') ?: "";
$dbname = "farmacia_db"; // Asegúrate de que esta base de datos exista en phpMyAdmin

// Crear la conexión a la base de datos
$conexion = new mysqli($servername, $username, $password, $dbname);

// Verificar si la conexión falló
if ($conexion->connect_error) {
    // Detener la ejecución y mostrar el error si la conexión no se puede establecer
    die("Error de conexión a la base de datos: " . $conexion->connect_error);
}

// Opcional: Establecer el conjunto de caracteres a utf8mb4 para soportar caracteres especiales y emojis
$conexion->set_charset("utf8mb4");
