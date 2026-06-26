<?php
declare(strict_types=1);
session_start();
ob_start();

require_once __DIR__ . '/conexion_bd.php';
$conn = $conexion;

// Solo el administrador puede agregar productos.
if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true || $_SESSION['rol'] !== 'administrador') {
    header("Location: /farmacia/diseno/pages/login.html?error=" . urlencode("No tienes permiso para esta acción."));
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $tipo   = trim(htmlspecialchars($_POST['tipo'] ?? ''));
    $nombre = trim(htmlspecialchars($_POST['nombre'] ?? ''));
    $clase  = trim(htmlspecialchars($_POST['clase'] ?? ''));
    $stock  = trim(htmlspecialchars($_POST['stock'] ?? ''));
    $precio = trim(htmlspecialchars($_POST['precio'] ?? ''));

    $tipos_validos = ['medicamento', 'suplemento'];
    if (!in_array($tipo, $tipos_validos, true)) {
        die("Tipo de producto inválido.");
    }

    if ($nombre === '' || $clase === '' || $stock === '' || $precio === '') {
        die("Todos los campos son obligatorios.");
    }

    // Validar que se haya subido un archivo correctamente.
    if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
        die("Debes subir una imagen válida.");
    }

    // Carpeta donde se guardan las imágenes (ruta real en el servidor).
    $carpeta_destino = __DIR__ . '/../diseno/img/';

    // Tomamos la extensión del archivo original (jpg, png, etc.)
    $extension = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));

    // Validar que sea un tipo de imagen permitido.
    $extensiones_validas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    if (!in_array($extension, $extensiones_validas, true)) {
        die("Formato de imagen no permitido. Usa jpg, png, gif o webp.");
    }

    // Generamos un nombre único para evitar que se sobrescriban imágenes con el mismo nombre.
    $nombre_archivo = 'img_' . time() . '_' . uniqid() . '.' . $extension;
    $ruta_completa = $carpeta_destino . $nombre_archivo;

    // Movemos el archivo subido desde la carpeta temporal a la carpeta de imágenes.
    if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_completa)) {
        die("Error: No se pudo guardar la imagen en el servidor.");
    }

    // Este es el valor que se guarda en la base de datos (solo el nombre del archivo).
    $imagen = $nombre_archivo;

    $sql = "INSERT INTO medicamento (nombre, clase, stock, precio, imagen, tipo) VALUES (?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssidss", $nombre, $clase, $stock, $precio, $imagen, $tipo);

        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            header("Location: /farmacia/diseno/pages/farmacia.php");
            exit();
        } else {
            error_log("Error al insertar producto: " . $stmt->error);
            echo "Error: No se pudo agregar el producto.";
            $stmt->close();
        }
    } else {
        error_log("Error al preparar la consulta: " . $conn->error);
        echo "Error: Hubo un problema con el servidor.";
    }
    $conn->close();
}

ob_end_flush();
?>