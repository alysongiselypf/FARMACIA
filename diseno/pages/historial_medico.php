<?php
require_once __DIR__ . '/../../php/sesion.php';
require_once __DIR__ . '/../../php/conexion_bd.php';
$conn = $conexion;

// Solo un doctor puede ver esta página.
if (!$usuarioLogueado || $rolUsuario !== 'doctor') {
    header("Location: /farmacia/diseno/pages/login.php?error=" . urlencode("Debes iniciar sesión como doctor."));
    exit();
}

$pacienteEncontrado = null;
$consultasPaciente = [];
$dniBuscado = trim(htmlspecialchars($_GET['dni_buscar'] ?? ''));
$mensajeError = '';

if ($dniBuscado !== '') {
    // Buscar al paciente por número de documento.
    $sqlPaciente = "SELECT id, nombres, apellidos, numero_documento, telefono, fecha_nacimiento
                     FROM usuario
                     WHERE numero_documento = ? AND rol = 'paciente'
                     LIMIT 1";
    $stmt = $conn->prepare($sqlPaciente);
    $stmt->bind_param("s", $dniBuscado);
    $stmt->execute();
    $resultPaciente = $stmt->get_result();

    if ($resultPaciente->num_rows === 1) {
        $pacienteEncontrado = $resultPaciente->fetch_assoc();
        $stmt->close();

        // Traer todas las consultas de ese paciente (con cualquier doctor).
        $sqlConsultas = "SELECT c.fecha_consulta, c.motivo, c.diagnostico, u.nombres, u.apellidos
                          FROM consulta c
                          INNER JOIN usuario u ON c.id_doctor = u.id
                          WHERE c.id_paciente = ?
                          ORDER BY c.fecha_consulta DESC";
        $stmtConsultas = $conn->prepare($sqlConsultas);
        $stmtConsultas->bind_param("i", $pacienteEncontrado['id']);
        $stmtConsultas->execute();
        $resultConsultas = $stmtConsultas->get_result();
        while ($row = $resultConsultas->fetch_assoc()) {
            $consultasPaciente[] = $row;
        }
        $stmtConsultas->close();
    } else {
        $stmt->close();
        $mensajeError = 'No se encontró ningún paciente con ese número de documento.';
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UCSP</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoYz1FQGpGJc65i1rZl+cbkz9z5n0U6z5l9QmZ4l5Q5hZ4N"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/style1.css">
    <script src="../js/script.js"></script>
    <style>
        .page-content-historial{
            max-width: 800px;
            margin: 40px auto;
            padding: 0 16px;
            font-family: 'Segoe UI', sans-serif;
        }
        .page-content-historial h1{
            text-align:center;
            margin-bottom: 30px;
            color:#1b2a56;
        }
        .card-buscar{
            background:#fff;
            border-radius:14px;
            box-shadow: 0 8px 24px rgba(27,42,86,0.10);
            padding:24px;
            margin-bottom:30px;
            display:flex;
            gap:12px;
        }
        .card-buscar input{
            flex:1;
            padding:11px 12px;
            border:1.5px solid #dfe2ea;
            border-radius:8px;
            font-size:14px;
        }
        .card-buscar button{
            padding:11px 20px;
            border:none;
            border-radius:8px;
            background:#1b2a56;
            color:#fff;
            font-weight:700;
            cursor:pointer;
        }
        .card-buscar button:hover{ background:#2e4a8f; }

        .paciente-info-card{
            background:#eef1fa;
            border-radius:14px;
            padding:20px;
            margin-bottom:24px;
        }
        .paciente-info-card .nombre-paciente{
            font-size:18px;
            font-weight:700;
            color:#1c1c22;
            margin-bottom:6px;
        }
        .paciente-info-card .dato-paciente{
            font-size:13px;
            color:#1c1c22;
            margin-bottom:2px;
        }

        .subtitulo-historial{
            font-size:16px;
            font-weight:700;
            color:#1b2a56;
            margin-bottom:14px;
        }
        .consulta-card-h{
            background:#fff;
            border-radius:12px;
            box-shadow: 0 6px 18px rgba(27,42,86,0.08);
            padding:18px 20px;
            margin-bottom:14px;
            border-left: 5px solid #1b2a56;
        }
        .consulta-fecha-h{
            font-size:12px;
            color:#8a8f98;
            font-weight:600;
            margin-bottom:6px;
        }
        .consulta-h-detalle{
            font-size:14px;
            color:#1c1c22;
            margin-bottom:4px;
        }
        .consulta-h-detalle strong{ color:#2e4a8f; }

        .mensaje-error-historial{
            background:#fbe9e7;
            color:#c0392b;
            padding:14px;
            border-radius:8px;
            margin-bottom:20px;
            text-align:center;
            font-size:14px;
            font-weight:600;
        }
        .sin-consultas-h{
            text-align:center;
            color:#8a8f98;
            padding:20px 0;
        }
    </style>
</head>
<body>
<header>
    <div class="container-hero">
        <div class="container hero">
            <div class="customer-support">
                <i class="fa-solid fa-headset"></i>
                <div class="content-customer-support">
                    <span class="text">Soporte al cliente</span>
                    <span class="number">123-456-7890</span>
                </div>
            </div>

            <div class="container-logo">
                <h1 class="logo"><a href="/">UCSP</a></h1>
                <i class="fa-solid fa-hospital"></i>
                <h1 class="logo"><a href="/">FARMACIA</a></h1>
            </div>

            <div class="container-icons">
                <div class="container-user">
                    <i class="fas fa-user"></i>
                    <div class="user-panel" style="display: none;">
                        <div class="user-panel-content">
                            <button class="close-user-btn">&times;</button>
                            <?php if ($usuarioLogueado): ?>
                                <p style="padding: 10px; font-weight: bold;">Hola, <?php echo $nombreUsuario; ?> 👋</p>
                                <ul>
                                    <li><a href="/farmacia/php/logout.php">Cerrar sesión</a></li>
                                </ul>
                            <?php else: ?>
                                <ul>
                                    <li><a href="../pages/login.php">Ingresar</a></li>
                                    <li><a href="../pages/signup.php">Crear cuenta</a></li>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="container-cart">
                    <i class="fa-solid fa-basket-shopping"></i>
                    <div class="content-shopping-cart">
                        <span class="number">(0)</span>
                    </div>
                </div>
            </div>

            <div class="cart-panel">
                <div class="cart-panel-content">
                    <button class="close-cart-btn">&times;</button>
                    <h2>Carrito:</h2>
                    <div class="cart-items"></div>
                    <div class="cart-summary">
                        <p class="cart-quantity">Cantidad: <span class="total-quantity">0</span></p>
                        <p class="cart-total">Subtotal: S/ <span class="total-price">0.00</span></p>
                        <button class="checkout-btn">Finalizar pedido</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <div class="container__menu">
            <div class="menu">
                <input type="checkbox" id="check__menu" name="menu">
                <label for="check__menu" id="label__check">Abrir menú</label>
                    <i class="fas fa-bars icon__menu"></i>
                </label>
                <nav>
                    <ul>
                        <?php if ($rolUsuario === 'paciente'): ?>

                            <li><a href="../pages/index.php">Inicio</a></li>
                            <li><a href="../pages/farmacia.php">Farmacia</a></li>
                            <li><a href="../pages/consultapaciente.php">Resultados Médicos</a></li>
                            <li><a href="../pages/citapaciente.php">Reservar Cita</a></li>
                            <li><a href="../pages/recetapaciente.php">Receta</a></li>

                        <?php elseif ($rolUsuario === 'doctor'): ?>

                            <li><a href="../pages/index.php">Inicio</a></li>
                            <li><a href="#">Paciente</a>
                                <ul>
                                    <li><a href="../pages/registro_paciente.php">Registro de Paciente</a></li>
                                    <li><a href="../pages/historial_medico.php">Historial Médico del Paciente</a></li>
                                </ul>
                            </li>
                            <li><a href="#">Doctor</a>
                                <ul>
                                    <li><a href="../pages/perfildoctor.php">Perfil</a></li>
                                    <li><a href="../pages/citas_doctor.php">Mis Citas Pendientes</a></li>
                                </ul>
                            </li>

                        <?php elseif ($rolUsuario === 'administrador'): ?>

                            <li><a href="../pages/index.php">Inicio</a></li>
                            <li><a href="../pages/farmacia.php">Farmacia</a>
                                <ul>
                                    <li><a href="../pages/agregar_medicamento.php">Agregar Medicamento</a></li>
                                    <li><a href="../pages/agregar_suplemento.php">Agregar Suplemento</a></li>
                                </ul>
                            </li>

                        <?php else: ?>

                            <li><a href="../pages/index.php">Inicio</a></li>
                            <li><a href="../pages/farmacia.php">Farmacia</a></li>
                            <li><a href="../pages/login.php">Ingresar</a></li>
                            <li><a href="../pages/signup.php">Crear cuenta</a></li>

                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </div>
</header>

    <div class="page-content-historial">
        <h1>Historial Médico del Paciente</h1>

        <form class="card-buscar" method="GET" action="historial_medico.php">
            <input type="text" name="dni_buscar" placeholder="Buscar paciente por DNI..." value="<?php echo htmlspecialchars($dniBuscado); ?>">
            <button type="submit">Buscar</button>
        </form>

        <?php if ($mensajeError !== ''): ?>
            <div class="mensaje-error-historial"><?php echo htmlspecialchars($mensajeError); ?></div>
        <?php endif; ?>

        <?php if ($pacienteEncontrado !== null): ?>

            <div class="paciente-info-card">
                <div class="nombre-paciente">
                    <?php echo htmlspecialchars($pacienteEncontrado['nombres'] . ' ' . $pacienteEncontrado['apellidos']); ?>
                </div>
                <div class="dato-paciente">DNI: <?php echo htmlspecialchars($pacienteEncontrado['numero_documento']); ?></div>
                <div class="dato-paciente">Teléfono: <?php echo htmlspecialchars($pacienteEncontrado['telefono']); ?></div>
                <div class="dato-paciente">Fecha de nacimiento: <?php echo date('d/m/Y', strtotime($pacienteEncontrado['fecha_nacimiento'])); ?></div>
            </div>

            <div class="subtitulo-historial">Consultas anteriores</div>

            <?php if (count($consultasPaciente) > 0): ?>
                <?php foreach ($consultasPaciente as $consulta): ?>
                    <div class="consulta-card-h">
                        <div class="consulta-fecha-h">
                            <?php echo date('d/m/Y', strtotime($consulta['fecha_consulta'])); ?>
                            - Dr(a). <?php echo htmlspecialchars($consulta['nombres'] . ' ' . $consulta['apellidos']); ?>
                        </div>
                        <div class="consulta-h-detalle"><strong>Motivo:</strong> <?php echo htmlspecialchars($consulta['motivo'] ?: 'No especificado'); ?></div>
                        <div class="consulta-h-detalle"><strong>Diagnóstico:</strong> <?php echo htmlspecialchars($consulta['diagnostico']); ?></div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="sin-consultas-h">Este paciente no tiene consultas registradas.</div>
            <?php endif; ?>

        <?php endif; ?>

    </div>

</body>
</html>

