<?php require_once __DIR__ . '/../../php/sesion.php'; ?>
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
        .page-content-perfil{
            max-width: 600px;
            margin: 40px auto;
            padding: 0 16px;
            font-family: 'Segoe UI', sans-serif;
        }
        .page-content-perfil h1{
            text-align:center;
            margin-bottom: 30px;
            color:#1b2a56;
        }
        .perfil-card{
            background:#fff;
            border-radius:14px;
            box-shadow: 0 8px 24px rgba(27,42,86,0.10);
            padding:32px;
            text-align:center;
        }
        .perfil-avatar{
            width:90px;
            height:90px;
            border-radius:50%;
            background:#1b2a56;
            color:#fff;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:34px;
            margin:0 auto 18px;
        }
        .perfil-nombre{
            font-size:20px;
            font-weight:700;
            color:#1c1c22;
            margin-bottom:4px;
        }
        .perfil-especialidad{
            font-size:14px;
            color:#2e4a8f;
            margin-bottom:24px;
        }
        .perfil-detalle{
            text-align:left;
            border-top:1px solid #dfe2ea;
            padding-top:18px;
            margin-top:10px;
        }
        .perfil-fila{
            display:flex;
            justify-content:space-between;
            padding:10px 0;
            border-bottom:1px solid #f3f4f8;
            font-size:14px;
        }
        .perfil-fila label{
            color:#8a8f98;
            font-weight:600;
        }
        .perfil-fila span{
            color:#1c1c22;
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

    <div class="page-content-perfil">
        <h1>Mi Perfil</h1>

        <div class="perfil-card">
            <div class="perfil-avatar">
                <i class="fa-solid fa-user-doctor"></i>
            </div>
            <div class="perfil-nombre">
                Dr. <?php echo htmlspecialchars(($_SESSION['nombres'] ?? '') . ' ' . ($_SESSION['apellidos'] ?? '')); ?>
            </div>
            <div class="perfil-especialidad">Médico General</div>

            <div class="perfil-detalle">
                <div class="perfil-fila">
                    <label>Nombres</label>
                    <span><?php echo htmlspecialchars($_SESSION['nombres'] ?? ''); ?></span>
                </div>
                <div class="perfil-fila">
                    <label>Apellidos</label>
                    <span><?php echo htmlspecialchars($_SESSION['apellidos'] ?? ''); ?></span>
                </div>
                <div class="perfil-fila">
                    <label>Rol</label>
                    <span><?php echo htmlspecialchars($_SESSION['rol'] ?? ''); ?></span>
                </div>
            </div>
        </div>
    </div>

</body>
</html>


