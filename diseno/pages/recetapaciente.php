<?php
require_once __DIR__ . '/../../php/sesion.php';
require_once __DIR__ . '/../../php/conexion_bd.php';
$conn = $conexion;

// Traer las recetas reales del paciente logueado.
$misRecetas = [];
if ($usuarioLogueado) {
    $idPaciente = $_SESSION['id_usuario'];
    $sql = "SELECT r.dosis, r.instrucciones, c.fecha_consulta, m.nombre AS nombre_medicamento, u.nombres, u.apellidos
            FROM receta r
            INNER JOIN consulta c ON r.id_consulta = c.id
            INNER JOIN medicamento m ON r.id_medicamento = m.id
            INNER JOIN usuario u ON c.id_doctor = u.id
            WHERE c.id_paciente = ?
            ORDER BY c.fecha_consulta DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idPaciente);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $misRecetas[] = $row;
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
        .page-content-receta{
            max-width: 800px;
            margin: 40px auto;
            padding: 0 16px;
            font-family: 'Segoe UI', sans-serif;
        }
        .page-content-receta h1{
            text-align:center;
            margin-bottom: 30px;
            color:#1b2a56;
        }
        .receta-card{
            background:#fff;
            border-radius:14px;
            box-shadow: 0 8px 24px rgba(27,42,86,0.10);
            padding:24px;
            margin-bottom:20px;
            border-left: 5px solid #27ae60;
        }
        .receta-fecha{
            font-size:13px;
            color:#8a8f98;
            font-weight:600;
            margin-bottom:8px;
        }
        .receta-doctor{
            font-size:14px;
            color:#2e4a8f;
            margin-bottom:14px;
        }
        .receta-medicamento{
            font-size:18px;
            font-weight:700;
            color:#1c1c22;
            margin-bottom:10px;
        }
        .receta-detalle{
            margin-bottom:10px;
        }
        .receta-detalle label{
            display:block;
            font-size:12px;
            font-weight:600;
            color:#8a8f98;
            text-transform:uppercase;
            margin-bottom:3px;
        }
        .receta-detalle p{
            font-size:14px;
            color:#1c1c22;
            margin:0;
        }
        .btn-comprar-receta{
            display:inline-block;
            margin-top:10px;
            padding:10px 18px;
            background:#1b2a56;
            color:#fff;
            border-radius:8px;
            font-size:13px;
            font-weight:700;
            text-decoration:none;
        }
        .btn-comprar-receta:hover{ background:#2e4a8f; }
        .sin-resultados{
            text-align:center;
            color:#8a8f98;
            padding:40px 0;
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

    <div class="page-content-receta">
        <h1>Mis Recetas</h1>

        <?php if (count($misRecetas) > 0): ?>
            <?php foreach ($misRecetas as $receta): ?>
                <div class="receta-card">
                    <div class="receta-fecha"><?php echo date('d/m/Y', strtotime($receta['fecha_consulta'])); ?></div>
                    <div class="receta-doctor">Recetado por: Dr(a). <?php echo htmlspecialchars($receta['nombres'] . ' ' . $receta['apellidos']); ?></div>

                    <div class="receta-medicamento"><?php echo htmlspecialchars($receta['nombre_medicamento']); ?></div>

                    <div class="receta-detalle">
                        <label>Dosis</label>
                        <p><?php echo htmlspecialchars($receta['dosis']); ?></p>
                    </div>

                    <?php if (!empty($receta['instrucciones'])): ?>
                        <div class="receta-detalle">
                            <label>Instrucciones</label>
                            <p><?php echo htmlspecialchars($receta['instrucciones']); ?></p>
                        </div>
                    <?php endif; ?>

                    <a href="../pages/farmacia.php" class="btn-comprar-receta">Comprar este medicamento</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="sin-resultados">
                <p>Aún no tienes recetas registradas.</p>
            </div>
        <?php endif; ?>

    </div>

</body>
</html>




