<?php
require_once __DIR__ . '/../../php/sesion.php';
require_once __DIR__ . '/../../php/conexion_bd.php';
$conn = $conexion;

$medicamentos = [];
$suplementos  = [];

$sql = "SELECT * FROM medicamento ORDER BY id ASC";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    if ($row['tipo'] === 'medicamento') {
        $medicamentos[] = $row;
    } else {
        $suplementos[] = $row;
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
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous">
   <link rel="stylesheet" href="../css/style.css">
   <link rel="stylesheet" href="../css/style1.css">
   <script src="../js/script.js" defer></script>
   <style>
    .stock-disponible {
        font-size: 1.3rem;
        color: #27ae60;
        font-weight: 500;
        margin: 0.5rem 0;
    }
    .stock-disponible.agotado {
        color: #e74c3c;
    }
</style>
</head>
<body>
<header>
    <div class="container-hero">
        <div class="container hero">
            <div class="customer-support">
                <i class="fas fa-headset"></i>
                <div class="content-customer-support">
                    <span class="text">Soporte al cliente</span>
                    <span class="number">123-456-7890</span>
                </div>
            </div>

            <div class="container-logo">
                <h1 class="logo"><a href="/">UCSP</a></h1>
                <i class="fas fa-hospital"></i>
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

        <section class="container top-categories">
            <h1 class="heading-1">Categorías principales de la farmacia</h1>
            <div class="container-categories">
                <div class="card-category category-med">
                    <p>Medicamentos</p>
                </div>
                <div class="card-category category-sup">
                    <p>Suplementos</p>
                </div>
            </div>
        </section>


        <div class="container">
            <div class="products-container">
                <?php foreach ($medicamentos as $med): ?>
                <div class="product" data-name="p-<?= $med['id'] ?>">
                    <img src="../img/<?= htmlspecialchars($med['imagen']) ?>" alt="<?= htmlspecialchars($med['nombre']) ?>">
                    <h3><?= htmlspecialchars($med['nombre']) ?></h3>
                    <h3>ID: <?= str_pad($med['id'], 3, '0', STR_PAD_LEFT) ?></h3>
                    <p class="stock-disponible">Stock: <?= $med['stock'] ?></p>
                    <div class="price">S/ <?= number_format($med['precio'], 2) ?></div>
                    <button type="button" class="cart">Agregar al carrito</button>
                </div>
                <?php endforeach; ?>

                <?php foreach ($suplementos as $sup): ?>
                <div class="product" data-name="p-<?= $sup['id'] ?>">
                    <img src="../img/<?= htmlspecialchars($sup['imagen']) ?>" alt="<?= htmlspecialchars($sup['nombre']) ?>">
                    <h3><?= htmlspecialchars($sup['nombre']) ?></h3>
                    <h3>ID: S<?= str_pad($sup['id'], 3, '0', STR_PAD_LEFT) ?></h3>
                    <p class="stock-disponible">Stock: <?= $sup['stock'] ?></p>
                    <div class="price">S/ <?= number_format($sup['precio'], 2) ?></div>
                    <button type="button" class="cart">Agregar al carrito</button>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
</body>
</html>
