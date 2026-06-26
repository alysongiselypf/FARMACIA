<?php
declare(strict_types=1);
session_start();
ob_start();

require_once __DIR__ . '/conexion_bd.php';
$conn = $conexion;

// Solo un doctor logueado puede registrar consultas.
if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true || $_SESSION['rol'] !== 'doctor') {
    header("Location: /farmacia/diseno/pages/login.php?error=" . urlencode("Debes iniciar sesión como doctor."));
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $id_doctor      = $_SESSION['id_usuario'];
    $id_cita        = trim(htmlspecialchars($_POST['id_cita'] ?? ''));
    $id_paciente    = trim(htmlspecialchars($_POST['id_paciente'] ?? ''));
    $diagnostico    = trim(htmlspecialchars($_POST['diagnostico'] ?? ''));
    $id_medicamento = trim(htmlspecialchars($_POST['id_medicamento'] ?? ''));
    $dosis          = trim(htmlspecialchars($_POST['dosis'] ?? ''));
    $instrucciones  = trim(htmlspecialchars($_POST['instrucciones'] ?? ''));

    if ($id_cita === '' || $id_paciente === '' || $diagnostico === '') {
        header("Location: /farmacia/diseno/pages/citas_doctor.php?error=" . urlencode("Completa el diagnóstico antes de guardar."));
        exit();
    }

    // Traer la fecha y el motivo originales de la cita.
    $sqlCita = "SELECT fecha_cita, motivo FROM cita WHERE id = ? AND id_doctor = ? LIMIT 1";
    $stmtCita = $conn->prepare($sqlCita);
    $stmtCita->bind_param("ii", $id_cita, $id_doctor);
    $stmtCita->execute();
    $resultCita = $stmtCita->get_result();

    if ($resultCita->num_rows !== 1) {
        $stmtCita->close();
        $conn->close();
        header("Location: /farmacia/diseno/pages/citas_doctor.php?error=" . urlencode("La cita no existe o no te pertenece."));
        exit();
    }

    $datosCita = $resultCita->fetch_assoc();
    $fecha_consulta = $datosCita['fecha_cita'];
    $motivo = $datosCita['motivo'];
    $stmtCita->close();

    // Insertar la consulta.
    $sqlInsert = "INSERT INTO consulta (id_cita, id_paciente, id_doctor, fecha_consulta, motivo, diagnostico)
                  VALUES (?, ?, ?, ?, ?, ?)";

    if ($stmtInsert = $conn->prepare($sqlInsert)) {
        $stmtInsert->bind_param("iiisss", $id_cita, $id_paciente, $id_doctor, $fecha_consulta, $motivo, $diagnostico);

        if ($stmtInsert->execute()) {
            $idConsultaNueva = $stmtInsert->insert_id;
            $stmtInsert->close();

            // Si el doctor eligió un medicamento, guardamos la receta.
            if ($id_medicamento !== '' && $dosis !== '') {
                $sqlReceta = "INSERT INTO receta (id_consulta, id_medicamento, dosis, instrucciones)
                              VALUES (?, ?, ?, ?)";
                $stmtReceta = $conn->prepare($sqlReceta);
                $stmtReceta->bind_param("iiss", $idConsultaNueva, $id_medicamento, $dosis, $instrucciones);
                $stmtReceta->execute();
                $stmtReceta->close();
            }

            // Marcar la cita como atendida.
            $sqlUpdate = "UPDATE cita SET estado = 'atendida' WHERE id = ?";
            $stmtUpdate = $conn->prepare($sqlUpdate);
            $stmtUpdate->bind_param("i", $id_cita);
            $stmtUpdate->execute();
            $stmtUpdate->close();

            $conn->close();
            header("Location: /farmacia/diseno/pages/citas_doctor.php?exito=1");
            exit();
        } else {
            error_log("Error al insertar consulta: " . $stmtInsert->error);
            header("Location: /farmacia/diseno/pages/citas_doctor.php?error=" . urlencode("No se pudo guardar la consulta."));
            exit();
        }
    } else {
        error_log("Error al preparar la consulta: " . $conn->error);
        header("Location: /farmacia/diseno/pages/citas_doctor.php?error=" . urlencode("Hubo un problema con el servidor."));
        exit();
    }
}

ob_end_flush();
?>