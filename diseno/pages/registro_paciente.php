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
        .page-content-registro{
            max-width: 600px;
            margin: 40px auto;
            padding: 0 16px;
            font-family: 'Segoe UI', sans-serif;
        }
        .page-content-registro h1{
            text-align:center;
            margin-bottom: 30px;
            color:#1b2a56;
        }
        .card-registro{
            background:#fff;
            border-radius:14px;
            box-shadow: 0 8px 24px rgba(27,42,86,0.10);
            padding:28px;
        }
        .campo-registro{
            margin-bottom:18px;
        }
        .campo-registro label{
            display:block;
            font-size:13px;
            font-weight:600;
            margin-bottom:6px;
            color:#1c1c22;
        }
        .campo-registro input,
        .campo-registro select{
            width:100%;
            padding:11px 12px;
            border:1.5px solid #dfe2ea;
            border-radius:8px;
            font-size:14px;
            box-sizing: border-box;
        }
        .fila-doble-registro{
            display:flex;
            gap:16px;
        }
        .fila-doble-registro .campo-registro{
            flex:1;
        }
        .btn-guardar-registro{
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
        .btn-guardar-registro:hover{ background:#2e4a8f; }
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

    <div class="page-content-registro">
        <h1>Registrar Nuevo Paciente</h1>

        <div class="card-registro">
            <form>
                <div class="fila-doble-registro">
                    <div class="campo-registro">
                        <label for="tipo_documento_p">Tipo de documento</label>
                        <select id="tipo_documento_p" name="tipo_documento" required>
                            <option value="" disabled selected>Selecciona</option>
                            <option value="DNI">DNI</option>
                            <option value="CE">Carné de Extranjería</option>
                            <option value="PASAPORTE">Pasaporte</option>
                        </select>
                    </div>
                    <div class="campo-registro">
                        <label for="numero_documento_p">Número de documento</label>
                        <input type="text" id="numero_documento_p" name="numero_documento" placeholder="Ej. 70123456" required>
                    </div>
                </div>

                <div class="fila-doble-registro">
                    <div class="campo-registro">
                        <label for="nombres_p">Nombres</label>
                        <input type="text" id="nombres_p" name="nombres" placeholder="Ej. María" required>
                    </div>
                    <div class="campo-registro">
                        <label for="apellidos_p">Apellidos</label>
                        <input type="text" id="apellidos_p" name="apellidos" placeholder="Ej. López Vega" required>
                    </div>
                </div>

                <div class="fila-doble-registro">
                    <div class="campo-registro">
                        <label for="fecha_nacimiento_p">Fecha de nacimiento</label>
                        <input type="date" id="fecha_nacimiento_p" name="fecha_nacimiento" required>
                    </div>
                    <div class="campo-registro">
                        <label for="telefono_p">Teléfono</label>
                        <input type="tel" id="telefono_p" name="telefono" placeholder="Ej. 987654321" required>
                    </div>
                </div>

                <button type="submit" class="btn-guardar-registro">Registrar Paciente</button>
            </form>
        </div>
    </div>

</body>
</html>


