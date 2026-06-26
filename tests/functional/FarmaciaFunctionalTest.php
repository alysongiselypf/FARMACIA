<?php
use PHPUnit\Framework\TestCase;

/**
 * Pruebas Funcionales - Sistema Farmacia
 * Verifica que las funciones principales del sistema operen correctamente
 */
class FarmaciaFunctionalTest extends TestCase
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

    // ── PRUEBAS DE BASE DE DATOS ──────────────────────────

    /** @test */
    public function test_conexion_base_de_datos()
    {
        $this->assertNotNull($this->pdo, 'La conexion a la base de datos debe existir');
    }

    /** @test */
    public function test_tabla_medicamento_existe()
    {
        $stmt = $this->pdo->query("SHOW TABLES LIKE 'medicamento'");
        $this->assertNotFalse($stmt->fetch(), 'La tabla medicamento debe existir');
    }

    /** @test */
    public function test_tabla_usuario_existe()
    {
        $stmt = $this->pdo->query("SHOW TABLES LIKE 'usuario'");
        $this->assertNotFalse($stmt->fetch(), 'La tabla usuario debe existir');
    }

    /** @test */
    public function test_tabla_pedido_existe()
    {
        $stmt = $this->pdo->query("SHOW TABLES LIKE 'pedido'");
        $this->assertNotFalse($stmt->fetch(), 'La tabla pedido debe existir');
    }

    /** @test */
    public function test_tabla_receta_existe()
    {
        $stmt = $this->pdo->query("SHOW TABLES LIKE 'receta'");
        $this->assertNotFalse($stmt->fetch(), 'La tabla receta debe existir');
    }

    /** @test */
    public function test_tabla_cita_existe()
    {
        $stmt = $this->pdo->query("SHOW TABLES LIKE 'cita'");
        $this->assertNotFalse($stmt->fetch(), 'La tabla cita debe existir');
    }

    /** @test */
    public function test_tabla_consulta_existe()
    {
        $stmt = $this->pdo->query("SHOW TABLES LIKE 'consulta'");
        $this->assertNotFalse($stmt->fetch(), 'La tabla consulta debe existir');
    }

    /** @test */
    public function test_tabla_detalle_pedido_existe()
    {
        $stmt = $this->pdo->query("SHOW TABLES LIKE 'detalle_pedido'");
        $this->assertNotFalse($stmt->fetch(), 'La tabla detalle_pedido debe existir');
    }

    // ── PRUEBAS DE OPERACIONES CRUD ───────────────────────

    /** @test */
    public function test_insertar_medicamento()
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO medicamento (nombre, descripcion, precio, stock) 
             VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE nombre=nombre"
        );
        $result = $stmt->execute(['Paracetamol Test', 'Analgesico de prueba', 5.50, 100]);
        $this->assertTrue($result, 'Debe poder insertar un medicamento');
    }

    /** @test */
    public function test_consultar_medicamentos()
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM medicamento");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->assertArrayHasKey('total', $row, 'Debe poder consultar medicamentos');
    }

    /** @test */
    public function test_estructura_tabla_usuario()
    {
        $stmt = $this->pdo->query("DESCRIBE usuario");
        $columnas = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $this->assertNotEmpty($columnas, 'La tabla usuario debe tener columnas definidas');
    }
}
