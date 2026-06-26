<?php
declare(strict_types=1);
session_start();
ob_start();

require_once __DIR__ . '/conexion_bd.php';
$conn = $conexion;

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $rol_seleccionado = trim(htmlspecialchars($_POST['rol'] ?? ''));
    $tipo_documento   = trim(htmlspecialchars($_POST['tipo_documento'] ?? ''));
    $numero_documento = trim(htmlspecialchars($_POST['numero_documento'] ?? ''));
    $password_raw     = $_POST['contraseña'] ?? '';

    $roles_validos = ['paciente', 'doctor', 'administrador'];

    if ($rol_seleccionado === '' || $tipo_documento === '' || $numero_documento === '' || $password_raw === '') {
        header("Location: /farmacia/diseno/pages/login.html?error=" . urlencode("Por favor completa todos los campos."));
        exit();
    }

    if (!in_array($rol_seleccionado, $roles_validos, true)) {
        header("Location: /farmacia/diseno/pages/login.html?error=" . urlencode("Tipo de usuario inválido."));
        exit();
    }

    $sql = "SELECT id, nombres, apellidos, password, rol FROM usuario WHERE tipo_documento = ? AND numero_documento = ? AND rol = ? LIMIT 1";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sss", $tipo_documento, $numero_documento, $rol_seleccionado);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $usuario = $result->fetch_assoc();

            if (password_verify($password_raw, $usuario['password'])) {
                $_SESSION['id_usuario']  = $usuario['id'];
                $_SESSION['nombres']     = $usuario['nombres'];
                $_SESSION['apellidos']   = $usuario['apellidos'];
                $_SESSION['rol']         = $usuario['rol'];
                $_SESSION['logueado']    = true;

                $stmt->close();
                $conn->close();

                // Por ahora, todos los roles van al inicio.
                // Más adelante se puede personalizar la redirección por rol.
                header("Location: /farmacia/diseno/pages/index.php");
                exit();

            } else {
                $stmt->close();
                $conn->close();
                header("Location: /farmacia/diseno/pages/login.html?error=" . urlencode("Usuario o contraseña incorrectos."));
                exit();
            }
        } else {
            $stmt->close();
            $conn->close();
            header("Location: /farmacia/diseno/pages/login.html?error=" . urlencode("Usuario o contraseña incorrectos para ese tipo de cuenta."));
            exit();
        }
    } else {
        error_log("Error al preparar la consulta: " . $conn->error);
        header("Location: /farmacia/diseno/pages/login.html?error=" . urlencode("Hubo un problema con el servidor."));
        exit();
    }
} else {
    header("Location: /farmacia/diseno/pages/login.html");
    exit();
}

ob_end_flush();
?>