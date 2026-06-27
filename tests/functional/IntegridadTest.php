<?php
use PHPUnit\Framework\TestCase;
class IntegridadTest extends TestCase
{
    private $pdo;
    protected function setUp(): void
    {
        $host = $_ENV['DB_HOST'] ?? '127.0.0.1';
$user = $_ENV['DB_USER'] ?? 'root';
$pass = $_ENV['DB_PASS'] ?? '';
$name = $_ENV['DB_NAME'] ?? 'farmacia_db';
        $this->pdo = new PDO("mysql:host=$host;dbname=$name", $user, $pass);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    public function test_todas_las_tablas_tienen_registros()
    {
        $tablas = ['usuario', 'medicamento', 'pedido', 'detalle_pedido', 'cita', 'consulta', 'receta'];
        foreach ($tablas as $tabla) {
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM $tabla");
            $total = $stmt->fetchColumn();
            $this->assertGreaterThan(0, $total, "La tabla '$tabla' no tiene registros");
        }
    }
    public function test_cita_doctor_es_realmente_doctor()
    {
        $stmt = $this->pdo->query(
            "SELECT ci.id FROM cita ci
             JOIN usuario u ON ci.id_doctor = u.id
             WHERE u.rol != 'doctor'"
        );
        $invalidas = $stmt->fetchAll();
        $this->assertEmpty($invalidas, 'El doctor asignado a una cita debe tener rol doctor');
    }
    public function test_cita_paciente_es_realmente_paciente()
    {
        $stmt = $this->pdo->query(
            "SELECT ci.id FROM cita ci
             JOIN usuario u ON ci.id_paciente = u.id
             WHERE u.rol != 'paciente'"
        );
        $invalidas = $stmt->fetchAll();
        $this->assertEmpty($invalidas, 'El paciente de una cita debe tener rol paciente');
    }
    public function test_pedido_pertenece_a_paciente()
    {
        $stmt = $this->pdo->query(
            "SELECT p.id FROM pedido p
             JOIN usuario u ON p.id_usuario = u.id
             WHERE u.rol != 'paciente'"
        );
        $invalidos = $stmt->fetchAll();
        $this->assertEmpty($invalidos, 'Los pedidos deben pertenecer a usuarios con rol paciente');
    }
    public function test_receta_medicamento_tiene_stock()
    {
        $stmt = $this->pdo->query(
            "SELECT r.id, m.nombre, m.stock FROM receta r
             JOIN medicamento m ON r.id_medicamento = m.id
             WHERE m.stock < 0"
        );
        $invalidos = $stmt->fetchAll();
        $this->assertEmpty($invalidos, 'Los medicamentos en recetas no deben tener stock negativo');
    }
    public function test_consulta_tiene_fecha_registrada()
    {
        $stmt = $this->pdo->query("SELECT * FROM consulta WHERE fecha_consulta IS NULL");
        $invalidas = $stmt->fetchAll();
        $this->assertEmpty($invalidas, 'Toda consulta debe tener una fecha registrada');
    }
    public function test_detalle_nombre_producto_no_vacio()
    {
        $stmt = $this->pdo->query("SELECT * FROM detalle_pedido WHERE nombre_producto IS NULL OR nombre_producto = ''");
        $invalidos = $stmt->fetchAll();
        $this->assertEmpty($invalidos, 'Todo detalle de pedido debe tener nombre de producto');
    }
    public function test_cantidad_tablas_correcta()
    {
        $stmt = $this->pdo->query("SHOW TABLES");
        $tablas = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $this->assertGreaterThanOrEqual(7, count($tablas), 'Debe haber al menos 7 tablas en la base de datos');
    }
    public function test_conexion_base_datos_estable()
    {
        $stmt = $this->pdo->query("SELECT 1");
        $resultado = $stmt->fetchColumn();
        $this->assertEquals(1, $resultado, 'La conexion a la base de datos debe estar estable');
    }
}
