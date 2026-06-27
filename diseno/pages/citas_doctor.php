<?php
require_once __DIR__ . '/../../php/sesion.php';
require_once __DIR__ . '/../../php/conexion_bd.php';
$conn = $conexion;

// Solo un doctor puede ver esta página.
if (!$usuarioLogueado || $rolUsuario !== 'doctor') {
    header("Location: /farmacia/diseno/pages/login.php?error=" . urlencode("Debes iniciar sesión como doctor."));
    exit();
}

$idDoctor = $_SESSION['id_usuario'];

// Traer las citas pendientes de este doctor.
$citasPendientes = [];
$sql = "SELECT c.id, c.fecha_cita, c.hora_cita, c.motivo, u.nombres, u.apellidos, u.id AS id_paciente
        FROM cita c
        INNER JOIN usuario u ON c.id_paciente = u.id
        WHERE c.id_doctor = ? AND c.estado = 'pendiente'
        ORDER BY c.fecha_cita ASC, c.hora_cita ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idDoctor);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $citasPendientes[] = $row;
}
$stmt->close();

// Traer la lista de medicamentos disponibles para el select de receta.
$medicamentosDisponibles = [];
$sqlMed = "SELECT id, nombre FROM medicamento ORDER BY nombre ASC";
$resultMed = $conn->query($sqlMed);
while ($row = $resultMed->fetch_assoc()) {
    $medicamentosDisponibles[] = $row;
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
        .page-content-citas-doc{
            max-width: 800px;
            margin: 40px auto;
            padding: 0 16px;
            font-family: 'Segoe UI', sans-serif;
        }
        .page-content-citas-doc h1{
            text-align:center;
            margin-bottom: 30px;
            color:#1b2a56;
        }
        .cita-doc-card{
            background:#fff;
            border-radius:14px;
            box-shadow: 0 8px 24px rgba(27,42,86,0.10);
            padding:24px;
            margin-bottom:20px;
            border-left: 5px solid #2e4a8f;
        }
        .cita-doc-fecha{
            font-size:13px;
            color:#8a8f98;
            font-weight:600;
            margin-bottom:8px;
        }
        .cita-doc-paciente{
            font-size:17px;
            font-weight:700;
            color:#1c1c22;
            margin-bottom:6px;
        }
        .cita-doc-motivo{
            font-size:14px;
            color:#1c1c22;
            margin-bottom:16px;
        }
        .cita-doc-motivo strong{ color:#2e4a8f; }

        .campo-diagnostico{
            margin-bottom:14px;
        }
        .campo-diagnostico label{
            display:block;
            font-size:13px;
            font-weight:600;
            margin-bottom:6px;
            color:#1c1c22;
        }
        .campo-diagnostico textarea,
        .campo-diagnostico select,
        .campo-diagnostico input{
            width:100%;
            padding:11px 12px;
            border:1.5px solid #dfe2ea;
            border-radius:8px;
            font-size:14px;
            box-sizing: border-box;
            font-family: inherit;
        }
        .campo-diagnostico textarea{ resize: vertical; }

        .receta-toggle{
            margin: 16px 0;
            display:flex;
            align-items:center;
            gap:8px;
            font-size:13px;
            font-weight:600;
            color:#1b2a56;
            cursor:pointer;
        }
        .receta-bloque{
            display:none;
            border-top:1px solid #dfe2ea;
            padding-top:16px;
            margin-top:8px;
        }
        .receta-bloque.visible{ display:block; }

        .btn-atender{
            padding:11px 22px;
            border:none;
            border-radius:8px;
            background:#1b2a56;
            color:#fff;
            font-size:14px;
            font-weight:700;
            cursor:pointer;
        }
        .btn-atender:hover{ background:#2e4a8f; }

        .sin-citas-doc{
            text-align:center;
            color:#8a8f98;
            padding:40px 0;
        }
        .mensaje-exito{
            background:#e7f6ec;
            color:#27ae60;
            padding:14px;
            border-radius:8px;
            margin-bottom:20px;
            text-align:center;
            font-size:14px;
            font-weight:600;
        }
        .mensaje-error{
            background:#fbe9e7;
            color:#c0392b;
            padding:14px;
            border-radius:8px;
            margin-bottom:20px;
            text-align:center;
            font-size:14px;
            font-weight:600;
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

    <div class="page-content-citas-doc">
        <h1>Mis Citas Pendientes</h1>

        <?php if (isset($_GET['exito'])): ?>
            <div class="mensaje-exito">Consulta registrada correctamente.</div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="mensaje-error"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>

        <?php if (count($citasPendientes) > 0): ?>
            <?php foreach ($citasPendientes as $cita): ?>
                <div class="cita-doc-card">
                    <div class="cita-doc-fecha">
                        <?php echo date('d/m/Y', strtotime($cita['fecha_cita'])); ?> -
                        <?php echo date('h:i A', strtotime($cita['hora_cita'])); ?>
                    </div>
                    <div class="cita-doc-paciente">
                        <?php echo htmlspecialchars($cita['nombres'] . ' ' . $cita['apellidos']); ?>
                    </div>
                    <div class="cita-doc-motivo">
                        <strong>Motivo:</strong> <?php echo htmlspecialchars($cita['motivo'] ?: 'No especificado'); ?>
                    </div>

                    <form action="../../php/procesar_consulta.php" method="POST">
                        <input type="hidden" name="id_cita" value="<?php echo $cita['id']; ?>">
                        <input type="hidden" name="id_paciente" value="<?php echo $cita['id_paciente']; ?>">

                        <div class="campo-diagnostico">
                            <label for="diagnostico_<?php echo $cita['id']; ?>">Diagnóstico</label>
                            <textarea name="diagnostico" id="diagnostico_<?php echo $cita['id']; ?>" rows="3" required placeholder="Escribe el diagnóstico de esta consulta..."></textarea>
                        </div>

                        <label class="receta-toggle">
                            <input type="checkbox" class="checkbox-receta" data-target="receta_<?php echo $cita['id']; ?>"> ¿Deseas recetar un medicamento?
                        </label>

                        <div class="receta-bloque" id="receta_<?php echo $cita['id']; ?>">
                            <div class="campo-diagnostico">
                                <label for="medicamento_<?php echo $cita['id']; ?>">Medicamento</label>
                                <select name="id_medicamento" id="medicamento_<?php echo $cita['id']; ?>">
                                    <option value="">-- No recetar medicamento --</option>
                                    <?php foreach ($medicamentosDisponibles as $med): ?>
                                        <option value="<?php echo $med['id']; ?>"><?php echo htmlspecialchars($med['nombre']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="campo-diagnostico">
                                <label for="dosis_<?php echo $cita['id']; ?>">Dosis</label>
                                <input type="text" name="dosis" id="dosis_<?php echo $cita['id']; ?>" placeholder="Ej. 1 tableta cada 8 horas por 5 días">
                            </div>

                            <div class="campo-diagnostico">
                                <label for="instrucciones_<?php echo $cita['id']; ?>">Instrucciones</label>
                                <textarea name="instrucciones" id="instrucciones_<?php echo $cita['id']; ?>" rows="2" placeholder="Ej. Tomar después de los alimentos"></textarea>
                            </div>
                        </div>

                        <button type="submit" class="btn-atender">Marcar como Atendida</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="sin-citas-doc">No tienes citas pendientes por atender.</div>
        <?php endif; ?>

    </div>
    <script>
    document.querySelectorAll('.checkbox-receta').forEach(function(checkbox){
        checkbox.addEventListener('change', function(){
            const targetId = this.getAttribute('data-target');
            const bloque = document.getElementById(targetId);
            if (this.checked) {
                bloque.classList.add('visible');
            } else {
                bloque.classList.remove('visible');
            }
        });
    });
</script>
</body>
</html>