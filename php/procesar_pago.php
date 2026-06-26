<?php
declare(strict_types=1);
session_start();

require_once __DIR__ . '/conexion_bd.php';
$conn = $conexion;

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'mensaje' => 'Método no permitido.']);
    exit();
}

if (empty($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
    echo json_encode(['success' => false, 'mensaje' => 'Debes iniciar sesión para realizar un pedido.']);
    exit();
}

$datos = json_decode(file_get_contents('php://input'), true);

if (!$datos) {
    echo json_encode(['success' => false, 'mensaje' => 'Datos inválidos.']);
    exit();
}

$id_usuario    = $_SESSION['id_usuario'];
$nombre_envio  = trim($datos['nombre_envio'] ?? '');
$direccion     = trim($datos['direccion'] ?? '');
$ciudad        = trim($datos['ciudad'] ?? '');
$telefono      = trim($datos['telefono'] ?? '');
$total         = floatval($datos['total'] ?? 0);
$carrito       = $datos['carrito'] ?? [];

if (!$nombre_envio || !$direccion || !$ciudad || !$telefono) {
    echo json_encode(['success' => false, 'mensaje' => 'Por favor completa todos los campos de envío.']);
    exit();
}

if (empty($carrito)) {
    echo json_encode(['success' => false, 'mensaje' => 'El carrito está vacío.']);
    exit();
}

// Insertar pedido
$sql = "INSERT INTO pedido (id_usuario, nombre_envio, direccion, ciudad, telefono, total, estado) VALUES (?, ?, ?, ?, ?, ?, 'completado')";
$stmt = $conn->prepare($sql);
$stmt->bind_param("issssd", $id_usuario, $nombre_envio, $direccion, $ciudad, $telefono, $total);

if (!$stmt->execute()) {
    echo json_encode(['success' => false, 'mensaje' => 'Error al registrar el pedido.']);
    exit();
}

$id_pedido = $stmt->insert_id;
$stmt->close();

// Insertar detalle de cada producto
$sql2 = "INSERT INTO detalle_pedido (id_pedido, nombre_producto, cantidad, precio_unitario, subtotal) VALUES (?, ?, ?, ?, ?)";
$stmt2 = $conn->prepare($sql2);

foreach ($carrito as $producto) {
    $nombre    = trim($producto['name'] ?? '');
    $cantidad  = intval($producto['quantity'] ?? 1);
    $precio    = floatval($producto['price'] ?? 0);
    $subtotal  = $precio * $cantidad;

    $stmt2->bind_param("isidd", $id_pedido, $nombre, $cantidad, $precio, $subtotal);
    $stmt2->execute();

    // Reducir stock del medicamento
    $sql3 = "UPDATE medicamento SET stock = stock - ? WHERE nombre = ? AND stock >= ?";
    $stmt3 = $conn->prepare($sql3);
    $stmt3->bind_param("isi", $cantidad, $nombre, $cantidad);
    $stmt3->execute();
    $stmt3->close();
}

$stmt2->close();
$conn->close();

echo json_encode([
    'success'   => true,
    'mensaje'   => 'Pedido registrado correctamente.',
    'id_pedido' => $id_pedido
]);
exit();
?>