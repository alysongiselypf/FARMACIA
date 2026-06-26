<?php
require_once __DIR__ . '/../../php/sesion.php';
require_once __DIR__ . '/../../php/conexion_bd.php';
$conn = $conexion;

// Traer la lista de doctores reales (incluyendo su especialidad).
$doctores = [];
$sqlDoctores = "SELECT id, nombres, apellidos, especialidad FROM usuario WHERE rol = 'doctor' ORDER BY nombres ASC";
$resultDoctores = $conn->query($sqlDoctores);
while ($row = $resultDoctores->fetch_assoc()) {
    $doctores[] = $row;
}

// Traer las próximas citas del paciente logueado.
$misCitas = [];
if ($usuarioLogueado) {
    $idPaciente = $_SESSION['id_usuario'];
    $sqlCitas = "SELECT c.fecha_cita, c.hora_cita, u.nombres, u.apellidos, u.especialidad
                 FROM cita c
                 INNER JOIN usuario u ON c.id_doctor = u.id
                 WHERE c.id_paciente = ? AND c.estado = 'pendiente'
                 ORDER BY c.fecha_cita ASC, c.hora_cita ASC";
    $stmt = $conn->prepare($sqlCitas);
    $stmt->bind_param("i", $idPaciente);
    $stmt->execute();
    $resultCitas = $stmt->get_result();
    while ($row = $resultCitas->fetch_assoc()) {
        $misCitas[] = $row;
    }
    $stmt->close();
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
        .page-content-cita{
            max-width: 800px;
            margin: 40px auto;
            padding: 0 16px;
            font-family: 'Segoe UI', sans-serif;
        }
        .page-content-cita h1{
            text-align:center;
            margin-bottom: 30px;
            color:#1b2a56;
        }
        .card-form-cita{
            background:#fff;
            border-radius:14px;
            box-shadow: 0 8px 24px rgba(27,42,86,0.10);
            padding:28px;
            margin-bottom:36px;
        }
        .campo-cita{
            margin-bottom:18px;
        }
        .campo-cita label{
            display:block;
            font-size:13px;
            font-weight:600;
            margin-bottom:6px;
            color:#1c1c22;
        }
        .campo-cita input,
        .campo-cita select{
            width:100%;
            padding:11px 12px;
            border:1.5px solid #dfe2ea;
            border-radius:8px;
            font-size:14px;
            box-sizing: border-box;
        }
        .fila-doble{
            display:flex;
            gap:16px;
        }
        .fila-doble .campo-cita{
            flex:1;
        }
        .btn-confirmar{
            width:100%;
            padding:13px;
            border:none;
            border-radius:8px;
            background:#1b2a56;
            color:#fff;
            font-size:15px;
            font-weight:700;
            cursor:pointer;
        }
        .btn-confirmar:hover{ background:#2e4a8f; }

        .subtitulo-citas{
            font-size:18px;
            font-weight:700;
            color:#1b2a56;
            margin-bottom:16px;
        }
        .cita-card{
            background:#fff;
            border-radius:12px;
            box-shadow: 0 6px 18px rgba(27,42,86,0.08);
            padding:18px 20px;
            margin-bottom:14px;
            display:flex;
            justify-content:space-between;
            align-items:center;
            border-left: 5px solid #2e4a8f;
        }
        .cita-info-doctor{
            font-weight:700;
            font-size:15px;
            color:#1c1c22;
        }
        .cita-info-especialidad{
            font-size:13px;
            color:#8a8f98;
        }
        .cita-fecha-hora{
            text-align:right;
            font-size:14px;
            color:#1b2a56;
            font-weight:600;
        }
        .sin-citas{
            text-align:center;
            color:#8a8f98;
            padding:20px 0;
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

    <div class="page-content-cita">
        <h1>Reservar Cita</h1>

        <?php if (isset($_GET['exito'])): ?>
            <div class="mensaje-exito">Tu cita fue reservada correctamente.</div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="mensaje-error"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>

        <div class="card-form-cita">
            <form action="../../php/procesar_cita.php" method="POST">
                <div class="campo-cita">
                    <label for="doctor">Doctor</label>
                    <select id="doctor" name="id_doctor" required>
                        <option value="" disabled selected>Selecciona un doctor</option>
                        <?php foreach ($doctores as $doc): ?>
                            <option value="<?php echo $doc['id']; ?>">
                                Dr(a). <?php echo htmlspecialchars($doc['nombres'] . ' ' . $doc['apellidos']); ?>
                                <?php if (!empty($doc['especialidad'])): ?>
                                    - <?php echo htmlspecialchars($doc['especialidad']); ?>
                                <?php endif; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="fila-doble">
                    <div class="campo-cita">
                        <label for="fecha_cita">Fecha</label>
                        <input type="date" id="fecha_cita" name="fecha_cita" required>
                    </div>

                    <div class="campo-cita">
                        <label for="hora_cita">Hora</label>
                        <input type="time" id="hora_cita" name="hora_cita" required>
                    </div>
                </div>

                <div class="campo-cita">
                    <label for="motivo_cita">Motivo de la consulta (opcional)</label>
                    <input type="text" id="motivo_cita" name="motivo_cita" placeholder="Ej. Control general">
                </div>

                <button type="submit" class="btn-confirmar">Confirmar Cita</button>
            </form>
        </div>

        <div class="subtitulo-citas">Mis Próximas Citas</div>

        <?php if (count($misCitas) > 0): ?>
            <?php foreach ($misCitas as $cita): ?>
                <div class="cita-card">
                    <div>
                        <div class="cita-info-doctor">
                            Dr(a). <?php echo htmlspecialchars($cita['nombres'] . ' ' . $cita['apellidos']); ?>
                        </div>
                        <div class="cita-info-especialidad">
                            <?php echo htmlspecialchars($cita['especialidad'] ?? ''); ?>
                        </div>
                    </div>
                    <div class="cita-fecha-hora">
                        <?php echo date('d/m/Y', strtotime($cita['fecha_cita'])); ?><br>
                        <?php echo date('h:i A', strtotime($cita['hora_cita'])); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="sin-citas">Aún no tienes citas reservadas.</div>
        <?php endif; ?>

    </div>

</body>
</html>