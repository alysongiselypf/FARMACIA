<?php
use PHPUnit\Framework\TestCase;

class PedidosTest extends TestCase
{
    private $pdo;

    protected function setUp(): void
    {
        $host = getenv('DB_HOST') ?: '127.0.0.1';
        $user = getenv('DB_USER') ?: 'root';
        $pass = getenv('DB_PASS') ?: '';
        $name = getenv('DB_NAME') ?: 'farmacia_db';

        $this->pdo = new PDO("mysql:host=$host;dbname=$name", $user, $pass);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function test_pedidos_tienen_usuario_valido()
    {
        $stmt = $this->pdo->query(
            "SELECT p.id FROM pedido p
             LEFT JOIN usuario u ON p.id_usuario = u.id
             WHERE u.id IS NULL"
        );
        $huerfanos = $stmt->fetchAll();
        $this->assertEmpty($huerfanos, 'Todo pedido debe pertenecer a un usuario valido');
    }

    public function test_detalle_pedido_tiene_pedido_valido()
    {
        $stmt = $this->pdo->query(
            "SELECT dp.id FROM detalle_pedido dp
             LEFT JOIN pedido p ON dp.id_pedido = p.id
             WHERE p.id IS NULL"
        );
        $huerfanos = $stmt->fetchAll();
        $this->assertEmpty($huerfanos, 'Todo detalle debe pertenecer a un pedido valido');
    }

    public function test_total_pedido_es_positivo()
    {
        $stmt = $this->pdo->query("SELECT * FROM pedido WHERE total <= 0");
        $invalidos = $stmt->fetchAll();
        $this->assertEmpty($invalidos, 'El total de todo pedido debe ser mayor a 0');
    }

    public function test_cantidad_detalle_es_positiva()
    {
        $stmt = $this->pdo->query("SELECT * FROM detalle_pedido WHERE cantidad <= 0");
        $invalidos = $stmt->fetchAll();
        $this->assertEmpty($invalidos, 'La cantidad en detalle debe ser mayor a 0');
    }

    public function test_precio_unitario_es_positivo()
    {
        $stmt = $this->pdo->query("SELECT * FROM detalle_pedido WHERE precio_unitario <= 0");
        $invalidos = $stmt->fetchAll();
        $this->assertEmpty($invalidos, 'El precio unitario debe ser mayor a 0');
    }

    public function test_estados_validos_pedido()
    {
        $stmt = $this->pdo->query("SELECT DISTINCT estado FROM pedido");
        $estados = $stmt->fetchAll(PDO::FETCH_COLUMN);
        foreach ($estados as $estado) {
            $this->assertContains($estado, ['pendiente', 'completado', 'cancelado'], "Estado '$estado' no es valido");
        }
    }

    public function test_subtotal_es_correcto()
    {
        $stmt = $this->pdo->query("SELECT cantidad, precio_unitario, subtotal FROM detalle_pedido");
        $detalles = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($detalles as $d) {
            $esperado = round($d['cantidad'] * $d['precio_unitario'], 2);
            $real = round($d['subtotal'], 2);
            $this->assertEquals($esperado, $real, 'El subtotal debe ser cantidad x precio_unitario');
        }
    }

    public function test_consultar_pedidos_completados()
    {
        $stmt = $this->pdo->query("SELECT * FROM pedido WHERE estado = 'completado'");
        $completados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->assertIsArray($completados, 'Debe poder consultar pedidos completados');
    }

    public function test_medicamento_tiene_stock_no_negativo()
    {
        $stmt = $this->pdo->query("SELECT * FROM medicamento WHERE stock < 0");
        $invalidos = $stmt->fetchAll();
        $this->assertEmpty($invalidos, 'Ningun medicamento debe tener stock negativo');
    }

    public function test_precio_medicamento_es_positivo()
    {
        $stmt = $this->pdo->query("SELECT * FROM medicamento WHERE precio <= 0");
        $invalidos = $stmt->fetchAll();
        $this->assertEmpty($invalidos, 'El precio de todo medicamento debe ser mayor a 0');
    }
}
