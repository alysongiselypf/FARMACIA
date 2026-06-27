<?php
use PHPUnit\Framework\TestCase;

class MedicamentosTest extends TestCase
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

    public function test_hay_medicamentos_tipo_medicamento()
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM medicamento WHERE tipo = 'medicamento'");
        $total = $stmt->fetchColumn();
        $this->assertGreaterThan(0, $total, 'Debe haber al menos un medicamento de tipo medicamento');
    }

    public function test_hay_medicamentos_tipo_suplemento()
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM medicamento WHERE tipo = 'suplemento'");
        $total = $stmt->fetchColumn();
        $this->assertGreaterThan(0, $total, 'Debe haber al menos un medicamento de tipo suplemento');
    }

    public function test_medicamento_imagen_no_vacia()
    {
        $stmt = $this->pdo->query("SELECT * FROM medicamento WHERE imagen IS NULL OR imagen = ''");
        $invalidos = $stmt->fetchAll();
        $this->assertEmpty($invalidos, 'Todo medicamento debe tener imagen asignada');
    }

    public function test_buscar_medicamento_por_nombre()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM medicamento WHERE nombre LIKE ?");
        $stmt->execute(['%Paracetamol%']);
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->assertNotEmpty($resultado, 'Debe encontrar el medicamento Paracetamol');
    }

    public function test_buscar_medicamento_por_clase()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM medicamento WHERE clase = ?");
        $stmt->execute(['Analgesico']);
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->assertNotEmpty($resultado, 'Debe encontrar medicamentos de clase Analgesico');
    }

    public function test_stock_total_es_positivo()
    {
        $stmt = $this->pdo->query("SELECT SUM(stock) as total FROM medicamento");
        $total = $stmt->fetchColumn();
        $this->assertGreaterThan(0, $total, 'El stock total de medicamentos debe ser positivo');
    }

    public function test_precio_promedio_es_razonable()
    {
        $stmt = $this->pdo->query("SELECT AVG(precio) as promedio FROM medicamento");
        $promedio = $stmt->fetchColumn();
        $this->assertGreaterThan(0, $promedio, 'El precio promedio debe ser mayor a 0');
        $this->assertLessThan(1000, $promedio, 'El precio promedio no debe superar 1000');
    }

    public function test_medicamento_mas_caro_existe()
    {
        $stmt = $this->pdo->query("SELECT * FROM medicamento ORDER BY precio DESC LIMIT 1");
        $medicamento = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->assertNotFalse($medicamento, 'Debe existir al menos un medicamento');
        $this->assertGreaterThan(0, $medicamento['precio']);
    }

    public function test_medicamentos_con_stock_disponible()
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM medicamento WHERE stock > 0");
        $total = $stmt->fetchColumn();
        $this->assertGreaterThan(0, $total, 'Debe haber medicamentos con stock disponible');
    }

    public function test_actualizar_stock_medicamento()
    {
        $stmt = $this->pdo->prepare("UPDATE medicamento SET stock = stock + 1 WHERE id = 1");
        $result = $stmt->execute();
        $this->assertTrue($result, 'Debe poder actualizar el stock de un medicamento');
    }
}
