<?php
use PHPUnit\Framework\TestCase;

class ValidacionesTest extends TestCase
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

    public function test_fecha_nacimiento_no_es_futura()
    {
        $stmt = $this->pdo->query("SELECT * FROM usuario WHERE fecha_nacimiento > CURDATE()");
        $invalidos = $stmt->fetchAll();
        $this->assertEmpty($invalidos, 'Ningun usuario debe tener fecha de nacimiento futura');
    }

    public function test_fecha_cita_no_es_pasada_para_pendientes()
    {
        $stmt = $this->pdo->query("SELECT * FROM cita WHERE estado = 'pendiente' AND fecha_cita < CURDATE()");
        $invalidas = $stmt->fetchAll();
        $this->assertEmpty($invalidas, 'No debe haber citas pendientes con fecha pasada');
    }

    public function test_hora_cita_tiene_formato_valido()
    {
        $stmt = $this->pdo->query("SELECT hora_cita FROM cita");
        $horas = $stmt->fetchAll(PDO::FETCH_COLUMN);
        foreach ($horas as $hora) {
            $this->assertMatchesRegularExpression('/^\d{2}:\d{2}:\d{2}$/', $hora, "La hora '$hora' no tiene formato valido");
        }
    }

    public function test_tipo_documento_es_valido()
    {
        $stmt = $this->pdo->query("SELECT DISTINCT tipo_documento FROM usuario");
        $tipos = $stmt->fetchAll(PDO::FETCH_COLUMN);
        foreach ($tipos as $tipo) {
            $this->assertContains($tipo, ['DNI', 'Pasaporte', 'CE'], "Tipo de documento '$tipo' no es valido");
        }
    }

    public function test_numero_documento_no_esta_vacio()
    {
        $stmt = $this->pdo->query("SELECT * FROM usuario WHERE numero_documento IS NULL OR numero_documento = ''");
        $invalidos = $stmt->fetchAll();
        $this->assertEmpty($invalidos, 'Ningun usuario debe tener numero de documento vacio');
    }

    public function test_nombres_no_estan_vacios()
    {
        $stmt = $this->pdo->query("SELECT * FROM usuario WHERE nombres IS NULL OR nombres = ''");
        $invalidos = $stmt->fetchAll();
        $this->assertEmpty($invalidos, 'Ningun usuario debe tener nombres vacios');
    }

    public function test_apellidos_no_estan_vacios()
    {
        $stmt = $this->pdo->query("SELECT * FROM usuario WHERE apellidos IS NULL OR apellidos = ''");
        $invalidos = $stmt->fetchAll();
        $this->assertEmpty($invalidos, 'Ningun usuario debe tener apellidos vacios');
    }

    public function test_medicamento_tiene_nombre()
    {
        $stmt = $this->pdo->query("SELECT * FROM medicamento WHERE nombre IS NULL OR nombre = ''");
        $invalidos = $stmt->fetchAll();
        $this->assertEmpty($invalidos, 'Todo medicamento debe tener nombre');
    }

    public function test_medicamento_tiene_clase()
    {
        $stmt = $this->pdo->query("SELECT * FROM medicamento WHERE clase IS NULL OR clase = ''");
        $invalidos = $stmt->fetchAll();
        $this->assertEmpty($invalidos, 'Todo medicamento debe tener clase');
    }

    public function test_tipo_medicamento_es_valido()
    {
        $stmt = $this->pdo->query("SELECT DISTINCT tipo FROM medicamento");
        $tipos = $stmt->fetchAll(PDO::FETCH_COLUMN);
        foreach ($tipos as $tipo) {
            $this->assertContains($tipo, ['medicamento', 'suplemento'], "Tipo '$tipo' no es valido");
        }
    }

    public function test_pedido_tiene_direccion_envio()
    {
        $stmt = $this->pdo->query("SELECT * FROM pedido WHERE direccion IS NULL OR direccion = ''");
        $invalidos = $stmt->fetchAll();
        $this->assertEmpty($invalidos, 'Todo pedido debe tener direccion de envio');
    }

    public function test_pedido_tiene_ciudad()
    {
        $stmt = $this->pdo->query("SELECT * FROM pedido WHERE ciudad IS NULL OR ciudad = ''");
        $invalidos = $stmt->fetchAll();
        $this->assertEmpty($invalidos, 'Todo pedido debe tener ciudad');
    }
}
