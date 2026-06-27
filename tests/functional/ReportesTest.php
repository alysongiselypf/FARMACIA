<?php
use PHPUnit\Framework\TestCase;

class ReportesTest extends TestCase
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

    public function test_reporte_medicamentos_por_tipo()
    {
        $stmt = $this->pdo->query(
            "SELECT tipo, COUNT(*) as total FROM medicamento GROUP BY tipo"
        );
        $reporte = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->assertNotEmpty($reporte, 'Debe generar reporte de medicamentos por tipo');
    }

    public function test_reporte_citas_por_estado()
    {
        $stmt = $this->pdo->query(
            "SELECT estado, COUNT(*) as total FROM cita GROUP BY estado"
        );
        $reporte = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->assertNotEmpty($reporte, 'Debe generar reporte de citas por estado');
    }

    public function test_reporte_pedidos_por_estado()
    {
        $stmt = $this->pdo->query(
            "SELECT estado, COUNT(*) as total FROM pedido GROUP BY estado"
        );
        $reporte = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->assertNotEmpty($reporte, 'Debe generar reporte de pedidos por estado');
    }

    public function test_reporte_usuarios_por_rol()
    {
        $stmt = $this->pdo->query(
            "SELECT rol, COUNT(*) as total FROM usuario GROUP BY rol"
        );
        $reporte = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->assertNotEmpty($reporte, 'Debe generar reporte de usuarios por rol');
        $this->assertGreaterThanOrEqual(2, count($reporte), 'Debe haber al menos 2 roles distintos');
    }

    public function test_reporte_stock_bajo()
    {
        $stmt = $this->pdo->query("SELECT * FROM medicamento WHERE stock < 100 ORDER BY stock ASC");
        $reporte = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->assertIsArray($reporte, 'Debe generar reporte de medicamentos con stock bajo');
    }

    public function test_reporte_ventas_por_ciudad()
    {
        $stmt = $this->pdo->query(
            "SELECT ciudad, COUNT(*) as pedidos, SUM(total) as total_ventas
             FROM pedido GROUP BY ciudad"
        );
        $reporte = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->assertNotEmpty($reporte, 'Debe generar reporte de ventas por ciudad');
    }

    public function test_reporte_consultas_por_doctor()
    {
        $stmt = $this->pdo->query(
            "SELECT id_doctor, COUNT(*) as total FROM consulta GROUP BY id_doctor"
        );
        $reporte = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->assertNotEmpty($reporte, 'Debe generar reporte de consultas por doctor');
    }

    public function test_reporte_medicamentos_mas_recetados()
    {
        $stmt = $this->pdo->query(
            "SELECT m.nombre, COUNT(r.id) as veces_recetado
             FROM receta r JOIN medicamento m ON r.id_medicamento = m.id
             GROUP BY m.nombre ORDER BY veces_recetado DESC"
        );
        $reporte = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->assertNotEmpty($reporte, 'Debe generar reporte de medicamentos mas recetados');
    }

    public function test_reporte_pacientes_con_mas_citas()
    {
        $stmt = $this->pdo->query(
            "SELECT id_paciente, COUNT(*) as total_citas
             FROM cita GROUP BY id_paciente ORDER BY total_citas DESC"
        );
        $reporte = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->assertNotEmpty($reporte, 'Debe generar reporte de pacientes con mas citas');
    }

    public function test_reporte_ingresos_totales()
    {
        $stmt = $this->pdo->query("SELECT SUM(total) as ingresos FROM pedido WHERE estado = 'completado'");
        $ingresos = $stmt->fetchColumn();
        $this->assertGreaterThan(0, $ingresos, 'Los ingresos totales deben ser mayores a 0');
    }
}
