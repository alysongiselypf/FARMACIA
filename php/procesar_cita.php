<?php
declare(strict_types=1);
session_start();
ob_start();

require_once __DIR__ . '/conexion_bd.php';
$conn = $conexion;

// Solo un paciente logueado puede reservar cita.
if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true || $_SESSION['rol'] !== 'paciente') {
    header("Location: /farmacia/diseno/pages/login.html?error=" . urlencode("Debes iniciar sesión como paciente."));
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $id_paciente = $_SESSION['id_usuario'];
    $id_doctor   = trim(htmlspecialchars($_POST['id_doctor'] ?? ''));
    $fecha_cita  = trim(htmlspecialchars($_POST['fecha_cita'] ?? ''));
    $hora_cita   = trim(htmlspecialchars($_POST['hora_cita'] ?? ''));
    $motivo      = trim(htmlspecialchars($_POST['motivo_cita'] ?? ''));

    if ($id_doctor === '' || $fecha_cita === '' || $hora_cita === '') {
        header("Location: /farmacia/diseno/pages/citapaciente.php?error=" . urlencode("Completa todos los campos obligatorios."));
        exit();
    }

    $sql = "INSERT INTO cita (id_paciente, id_doctor, fecha_cita, hora_cita, motivo, estado)
            VALUES (?, ?, ?, ?, ?, 'pendiente')";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("iisss", $id_paciente, $id_doctor, $fecha_cita, $hora_cita, $motivo);

        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            header("Location: /farmacia/diseno/pages/citapaciente.php?exito=1");
            exit();
        } else {
            error_log("Error al insertar cita: " . $stmt->error);
            header("Location: /farmacia/diseno/pages/citapaciente.php?error=" . urlencode("No se pudo guardar la cita."));
            exit();
        }
    } else {
        error_log("Error al preparar la consulta: " . $conn->error);
        header("Location: /farmacia/diseno/pages/citapaciente.php?error=" . urlencode("Hubo un problema con el servidor."));
        exit();
    }
}

ob_end_flush();
?>