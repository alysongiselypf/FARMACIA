<?php
use PHPUnit\Framework\TestCase;

class CitasAvanzadoTest extends TestCase
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

    public function test_contar_citas_totales()
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM cita");
        $total = $stmt->fetchColumn();
        $this->assertGreaterThan(0, $total, 'Debe haber citas registradas en el sistema');
    }

    public function test_citas_tienen_fecha_registrada()
    {
        $stmt = $this->pdo->query("SELECT * FROM cita WHERE fecha_cita IS NULL");
        $invalidas = $stmt->fetchAll();
        $this->assertEmpty($invalidas, 'Toda cita debe tener fecha registrada');
    }

    public function test_citas_tienen_hora_registrada()
    {
        $stmt = $this->pdo->query("SELECT * FROM cita WHERE hora_cita IS NULL");
        $invalidas = $stmt->fetchAll();
        $this->assertEmpty($invalidas, 'Toda cita debe tener hora registrada');
    }

    public function test_ver_citas_por_paciente()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM cita WHERE id_paciente = ?");
        $stmt->execute([1]);
        $citas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->assertIsArray($citas, 'Debe retornar las citas del paciente');
    }

    public function test_ver_citas_por_doctor()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM cita WHERE id_doctor = ?");
        $stmt->execute([3]);
        $citas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->assertIsArray($citas, 'Debe retornar las citas del doctor');
    }

    public function test_cita_atendida_tiene_consulta()
    {
        $stmt = $this->pdo->query(
            "SELECT ci.id FROM cita ci
             LEFT JOIN consulta c ON ci.id = c.id_cita
             WHERE ci.estado = 'atendida' AND c.id IS NULL"
        );
        $sinConsulta = $stmt->fetchAll();
        $this->assertEmpty($sinConsulta, 'Toda cita atendida debe tener una consulta registrada');
    }

    public function test_cancelar_cita()
    {
        $stmt = $this->pdo->prepare("UPDATE cita SET estado = 'cancelada' WHERE id = 3");
        $result = $stmt->execute();
        $this->assertTrue($result, 'Debe poder cancelar una cita');
    }

    public function test_cita_cancelada_existe()
    {
        $stmt = $this->pdo->query("SELECT * FROM cita WHERE estado = 'cancelada'");
        $canceladas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->assertIsArray($canceladas, 'Debe poder consultar citas canceladas');
    }

    public function test_cita_tiene_creado_en()
    {
        $stmt = $this->pdo->query("SELECT * FROM cita WHERE creado_en IS NULL");
        $invalidas = $stmt->fetchAll();
        $this->assertEmpty($invalidas, 'Toda cita debe tener fecha de creacion');
    }

    public function test_citas_ordenadas_por_fecha()
    {
        $stmt = $this->pdo->query("SELECT fecha_cita FROM cita ORDER BY fecha_cita ASC");
        $fechas = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $ordenadas = $fechas;
        sort($ordenadas);
        $this->assertEquals($ordenadas, $fechas, 'Las citas deben poder ordenarse por fecha');
    }
}
