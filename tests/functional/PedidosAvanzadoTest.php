<?php
use PHPUnit\Framework\TestCase;

class PedidosAvanzadoTest extends TestCase
{
    private $pdo;

    protected function setUp(): void
    {
        $host = getenv('DB_HOST') ?: '127.0.0.1';
        $user = getenv('DB_USER') ?: 'root';
        $pass = getenv('DB_PASS') ?: 'root';
        $name = getenv('DB_NAME') ?: 'farmacia_db';

        $this->pdo = new PDO("mysql:host=$host;dbname=$name", $user, $pass);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function test_contar_pedidos_totales()
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM pedido");
        $total = $stmt->fetchColumn();
        $this->assertGreaterThan(0, $total, 'Debe haber pedidos registrados');
    }

    public function test_pedido_tiene_nombre_envio()
    {
        $stmt = $this->pdo->query("SELECT * FROM pedido WHERE nombre_envio IS NULL OR nombre_envio = ''");
        $invalidos = $stmt->fetchAll();
        $this->assertEmpty($invalidos, 'Todo pedido debe tener nombre de envio');
    }

    public function test_pedido_tiene_telefono()
    {
        $stmt = $this->pdo->query("SELECT * FROM pedido WHERE telefono IS NULL OR telefono = ''");
        $invalidos = $stmt->fetchAll();
        $this->assertEmpty($invalidos, 'Todo pedido debe tener telefono');
    }

    public function test_buscar_pedidos_por_usuario()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM pedido WHERE id_usuario = ?");
        $stmt->execute([1]);
        $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->assertNotEmpty($pedidos, 'Debe encontrar pedidos del usuario 1');
    }

    public function test_total_ventas_sistema()
    {
        $stmt = $this->pdo->query("SELECT SUM(total) FROM pedido WHERE estado = 'completado'");
        $totalVentas = $stmt->fetchColumn();
        $this->assertGreaterThan(0, $totalVentas, 'El total de ventas debe ser mayor a 0');
    }

    public function test_pedido_mas_reciente_existe()
    {
        $stmt = $this->pdo->query("SELECT * FROM pedido ORDER BY fecha DESC LIMIT 1");
        $pedido = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->assertNotFalse($pedido, 'Debe existir al menos un pedido');
    }

    public function test_detalle_pedido_cantidad_maxima()
    {
        $stmt = $this->pdo->query("SELECT MAX(cantidad) FROM detalle_pedido");
        $maxCantidad = $stmt->fetchColumn();
        $this->assertLessThanOrEqual(100, $maxCantidad, 'La cantidad maxima por producto no debe superar 100');
    }

    public function test_promedio_total_pedidos()
    {
        $stmt = $this->pdo->query("SELECT AVG(total) FROM pedido");
        $promedio = $stmt->fetchColumn();
        $this->assertGreaterThan(0, $promedio, 'El promedio de pedidos debe ser mayor a 0');
    }

    public function test_pedido_tiene_fecha()
    {
        $stmt = $this->pdo->query("SELECT * FROM pedido WHERE fecha IS NULL");
        $invalidos = $stmt->fetchAll();
        $this->assertEmpty($invalidos, 'Todo pedido debe tener fecha registrada');
    }

    public function test_productos_mas_vendidos()
    {
        $stmt = $this->pdo->query(
            "SELECT nombre_producto, SUM(cantidad) as total 
             FROM detalle_pedido GROUP BY nombre_producto 
             ORDER BY total DESC LIMIT 3"
        );
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->assertNotEmpty($productos, 'Debe poder consultar los productos mas vendidos');
    }
}
