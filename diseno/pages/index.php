<?php require_once __DIR__ . '/../../php/sesion.php'; ?>
<!-- index.php -->
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
                    <i class="fa-solid fa-user"></i>
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
<section class="banner">
    <div class="content-banner">
        <p>Medicamentos</p>
        <h2>A Domicilio</h2>
        <a href="#">Comprar ahora</a>
    </div>
</section>

<section class="section-container">
    <h2>MISIÓN</h2>
    <p>Colaborar en mejorar la salud de la población atendida en nuestro centro, promoviendo el Uso Racional del Medicamento, basado en la evidencia científica con criterios de necesidad, eficacia, seguridad, eficiencia, en definitiva con calidad.</p>
</section>

<section class="section-container">
    <h2>VISIÓN</h2>
    <p>- Queremos ser un Servicio líder en conocimiento farmacoterapeutico que ayuda a obtener resultados de excelencia asistencial contribuyendo a la seguridad de los procesos farmacoterapéuticos y a la eficiencia de la organización</p>
    <p>- Queremos ser un servicio de farmacia hospitalaria referente en nuestra política de calidad, seguridad del paciente y mejora continua</p>
</section>

<section class="section-container">
    <h2>POLÍTICA DE CALIDAD</h2>
    <p>- Calidad del Producto:
        Garantizamos que todos nuestros productos farmacéuticos cumplen con las normativas y estándares de calidad establecidos por las autoridades sanitarias. Seleccionamos cuidadosamente a nuestros proveedores para asegurar la autenticidad y eficacia de los medicamentos.</p>
    <p>- Atención al Cliente:
        Proporcionamos un servicio al cliente excepcional, ofreciendo asesoramiento profesional y personalizado a cada uno de nuestros clientes. Nuestro equipo de farmacéuticos y técnicos está altamente capacitado para brindar información precisa y confiable.</p>
    <p>- Entrega Eficiente:
        Nos comprometemos a entregar los medicamentos de manera rápida y segura, asegurando que lleguen en perfectas condiciones. Implementamos un sistema de entrega a domicilio que permite a nuestros clientes recibir sus medicamentos de forma conveniente y puntual.</p>
    <p>- Innovación Continua:
        Fomentamos la mejora continua de nuestros procesos y servicios mediante la adopción de nuevas tecnologías y prácticas innovadoras. Evaluamos constantemente nuestras operaciones para identificar áreas de mejora y asegurar la satisfacción del cliente.<p>
    <p>- Cumplimiento Legal y Normativo:
        Aseguramos el estricto cumplimiento de todas las leyes y regulaciones aplicables al sector farmacéutico. Nos mantenemos actualizados con los cambios normativos para garantizar que nuestras prácticas sean siempre conformes.</p>
    <p>- Ética y Responsabilidad:
        Actuamos con integridad y responsabilidad en todas nuestras actividades. Promovemos un ambiente de trabajo ético y respetuoso, tanto para nuestros empleados como para nuestros clientes.<p>
    <p>- Educación y Capacitación:
        Invertimos en la educación continua y la capacitación de nuestro personal para mantener altos estándares de conocimiento y competencia. Creemos que un equipo bien informado es fundamental para proporcionar un servicio de calidad.</p>
</section>

<footer class="footer">
    <div class="container container-footer">
        <div class="menu-footer">
            <div class="contact-info">
                <p class="title-footer">UCSP Farmacia</p>
                <ul>
                    <li>Teléfono: 123-456-7890</li>
                    <li>¿Cómo comprar?</li>
                    <li>Facturación electrónica</li>
                    <li>Legales</li>
                </ul>
                <div class="social-icons">
                    <span class="facebook">
                        <i class="fa-brands fa-facebook-f"></i>
                    </span>
                    <span class="whatsapp">
                        <i class="fa-brands fa-whatsapp"></i>
                    </span>
                    <span class="youtube">
                        <i class="fa-brands fa-youtube"></i>
                    </span>
                    <span class="instagram">
                        <i class="fa-brands fa-instagram"></i>
                    </span>
                </div>
            </div>

            <div class="information">
                <p class="title-footer">Atención al cliente</p>
                <ul>
                    <li><a href="#">ucso.farmacia@edu.pe</a></li>
                    <li><a href="#">Tipos de entrega</a></li>
                    <li><a href="#">Preguntas frecuentes</a></li>
                    <li><a href="#">Cambios y devoluciones</a></li>
                    <li><a href="#">Medios de pago </a></li>
                </ul>
            </div>

            <div class="my-account">
                <p class="title-footer">Te ayudamos</p>
                <ul>
                    <li><a href="#">Búsqueda</a></li>
                    <li><a href="#">Términos y condiciones</a></li>
                    <li><a href="#">Política de envíos</a></li>
                    <li><a href="#">Términos del servicio</a></li>
                    <li><a href="#">Política de reembolsos</a></li>
                </ul>
            </div>

            <div class="newsletter">
                <p class="title-footer">Boletín informativo</p>
                <div class="content">
                    <p>
                        Se el primero en recibir nuestras ofertas y novedades
                    </p>
                    <input type="email" placeholder="Ingresa tu email...">
                    <button>Enviar</button>
                </div>
            </div>
        </div>

        <div class="copyright">
            <img src="../img/pagos.png" alt="Pagos">
        </div>
    </div>
</footer>

</body>
</html>
