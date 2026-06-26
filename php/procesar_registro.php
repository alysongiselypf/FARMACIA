<?php
declare(strict_types=1);
// Iniciar el almacenamiento en búfer de salida para evitar errores de "headers already sent".
ob_start();
session_start();

// Incluir el archivo de conexión a la base de datos.
require_once __DIR__ . '/conexion_bd.php';

// La variable $conexion viene de conexion_bd.php.
$conn = $conexion;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanear los datos del formulario.
    $tipo_documento   = trim(htmlspecialchars($_POST['tipo_documento'] ?? ''));
    $numero_documento = trim(htmlspecialchars($_POST['numero_documento'] ?? ''));
    $fecha_nacimiento = trim(htmlspecialchars($_POST['fecha_nacimiento'] ?? ''));
    $nombres          = trim(htmlspecialchars($_POST['nombres'] ?? ''));
    $apellidos        = trim(htmlspecialchars($_POST['apellidos'] ?? ''));
    $telefono         = trim(htmlspecialchars($_POST['telefono'] ?? ''));
    $password_raw     = $_POST['password'] ?? '';
    $rol              = trim(htmlspecialchars($_POST['rol'] ?? ''));
    $especialidad     = trim(htmlspecialchars($_POST['especialidad'] ?? ''));

    // Validar que el rol sea uno de los permitidos.
    $roles_validos = ['paciente', 'doctor', 'administrador'];
    if (!in_array($rol, $roles_validos, true)) {
        die("Tipo de cuenta inválido.");
    }

    // Validar que la contraseña no esté vacía.
    if (empty($password_raw)) {
        die("La contraseña es obligatoria.");
    }

    // Si el rol es doctor, la especialidad es obligatoria.
    if ($rol === 'doctor' && $especialidad === '') {
        die("La especialidad es obligatoria para doctores.");
    }

    // Si no es doctor, no guardamos especialidad (se queda NULL).
    $especialidad_final = ($rol === 'doctor') ? $especialidad : null;

    // Hashear la contraseña para almacenarla de forma segura.
    $password_hashed = password_hash($password_raw, PASSWORD_DEFAULT);

    // Preparar la consulta SQL para evitar inyecciones SQL.
    $sql = "INSERT INTO usuario (tipo_documento, numero_documento, fecha_nacimiento, nombres, apellidos, telefono, password, rol, especialidad)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param(
            "sssssssss",
            $tipo_documento,
            $numero_documento,
            $fecha_nacimiento,
            $nombres,
            $apellidos,
            $telefono,
            $password_hashed,
            $rol,
            $especialidad_final
        );

        if ($stmt->execute()) {
            // Obtener el id que se le asignó al nuevo usuario.
            $nuevo_id = $stmt->insert_id;
            $stmt->close();

            // Iniciar sesión automáticamente, igual que hace login.php.
            $_SESSION['id_usuario'] = $nuevo_id;
            $_SESSION['nombres']    = $nombres;
            $_SESSION['apellidos']  = $apellidos;
            $_SESSION['rol']        = $rol;
            $_SESSION['logueado']   = true;

            $conn->close();

            // Redirigir directo al inicio, ya logueado.
            header("Location: ../diseno/pages/index.php");
            exit();
        } else {
            // Manejo de errores si la ejecución falla.
            error_log("Error al ejecutar la consulta: " . $stmt->error);
            echo "Error: No se pudo procesar el registro.";
            $stmt->close();
        }
    } else {
        // Manejo de errores si la preparación de la consulta falla.
        error_log("Error al preparar la consulta: " . $conn->error);
        echo "Error: Hubo un problema con el servidor.";
    }
    $conn->close();
}

// Limpiar y enviar el búfer de salida.
ob_end_flush();
?>