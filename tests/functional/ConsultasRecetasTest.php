<?php
use PHPUnit\Framework\TestCase;

class ConsultasRecetasTest extends TestCase
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

    public function test_consulta_tiene_cita_valida()
    {
        $stmt = $this->pdo->query(
            "SELECT c.id FROM consulta c
             LEFT JOIN cita ci ON c.id_cita = ci.id
             WHERE ci.id IS NULL"
        );
        $huerfanas = $stmt->fetchAll();
        $this->assertEmpty($huerfanas, 'Toda consulta debe estar ligada a una cita valida');
    }

    public function test_consulta_tiene_paciente_valido()
    {
        $stmt = $this->pdo->query(
            "SELECT c.id FROM consulta c
             LEFT JOIN usuario u ON c.id_paciente = u.id
             WHERE u.id IS NULL"
        );
        $invalidas = $stmt->fetchAll();
        $this->assertEmpty($invalidas, 'Toda consulta debe tener un paciente valido');
    }

    public function test_consulta_tiene_doctor_valido()
    {
        $stmt = $this->pdo->query(
            "SELECT c.id FROM consulta c
             LEFT JOIN usuario u ON c.id_doctor = u.id
             WHERE u.id IS NULL OR u.rol != 'doctor'"
        );
        $invalidas = $stmt->fetchAll();
        $this->assertEmpty($invalidas, 'Toda consulta debe ser atendida por un doctor');
    }

    public function test_diagnostico_no_esta_vacio()
    {
        $stmt = $this->pdo->query("SELECT * FROM consulta WHERE diagnostico IS NULL OR diagnostico = ''");
        $sinDiagnostico = $stmt->fetchAll();
        $this->assertEmpty($sinDiagnostico, 'Toda consulta debe tener un diagnostico');
    }

    public function test_receta_tiene_consulta_valida()
    {
        $stmt = $this->pdo->query(
            "SELECT r.id FROM receta r
             LEFT JOIN consulta c ON r.id_consulta = c.id
             WHERE c.id IS NULL"
        );
        $huerfanas = $stmt->fetchAll();
        $this->assertEmpty($huerfanas, 'Toda receta debe estar ligada a una consulta valida');
    }

    public function test_receta_tiene_medicamento_valido()
    {
        $stmt = $this->pdo->query(
            "SELECT r.id FROM receta r
             LEFT JOIN medicamento m ON r.id_medicamento = m.id
             WHERE m.id IS NULL"
        );
        $invalidas = $stmt->fetchAll();
        $this->assertEmpty($invalidas, 'Toda receta debe tener un medicamento valido');
    }

    public function test_dosis_no_esta_vacia()
    {
        $stmt = $this->pdo->query("SELECT * FROM receta WHERE dosis IS NULL OR dosis = ''");
        $sinDosis = $stmt->fetchAll();
        $this->assertEmpty($sinDosis, 'Toda receta debe tener una dosis indicada');
    }

    public function test_instrucciones_no_estan_vacias()
    {
        $stmt = $this->pdo->query("SELECT * FROM receta WHERE instrucciones IS NULL OR instrucciones = ''");
        $sinInstrucciones = $stmt->fetchAll();
        $this->assertEmpty($sinInstrucciones, 'Toda receta debe tener instrucciones');
    }

    public function test_cita_tiene_paciente_y_doctor_distintos()
    {
        $stmt = $this->pdo->query("SELECT * FROM cita WHERE id_paciente = id_doctor");
        $invalidas = $stmt->fetchAll();
        $this->assertEmpty($invalidas, 'El paciente y el doctor de una cita deben ser distintos');
    }

    public function test_motivo_cita_no_esta_vacio()
    {
        $stmt = $this->pdo->query("SELECT * FROM cita WHERE motivo IS NULL OR motivo = ''");
        $sinMotivo = $stmt->fetchAll();
        $this->assertEmpty($sinMotivo, 'Toda cita debe tener un motivo');
    }
}
