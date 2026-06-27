<?php
use PHPUnit\Framework\TestCase;

class RecetasAvanzadoTest extends TestCase
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

    public function test_contar_recetas_totales()
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM receta");
        $total = $stmt->fetchColumn();
        $this->assertGreaterThan(0, $total, 'Debe haber recetas registradas');
    }

    public function test_receta_tiene_fecha_creacion()
    {
        $stmt = $this->pdo->query("SELECT * FROM receta WHERE creado_en IS NULL");
        $invalidas = $stmt->fetchAll();
        $this->assertEmpty($invalidas, 'Toda receta debe tener fecha de creacion');
    }

    public function test_receta_medicamento_tiene_nombre()
    {
        $stmt = $this->pdo->query(
            "SELECT r.id, m.nombre FROM receta r
             JOIN medicamento m ON r.id_medicamento = m.id"
        );
        $recetas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($recetas as $receta) {
            $this->assertNotEmpty($receta['nombre'], 'El medicamento de la receta debe tener nombre');
        }
    }

    public function test_receta_vinculada_a_paciente()
    {
        $stmt = $this->pdo->query(
            "SELECT r.id, c.id_paciente FROM receta r
             JOIN consulta c ON r.id_consulta = c.id"
        );
        $recetas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($recetas as $receta) {
            $this->assertNotNull($receta['id_paciente'], 'La receta debe estar vinculada a un paciente');
        }
    }

    public function test_receta_vinculada_a_doctor()
    {
        $stmt = $this->pdo->query(
            "SELECT r.id, c.id_doctor FROM receta r
             JOIN consulta c ON r.id_consulta = c.id"
        );
        $recetas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($recetas as $receta) {
            $this->assertNotNull($receta['id_doctor'], 'La receta debe estar vinculada a un doctor');
        }
    }

    public function test_medicamento_recetado_tiene_precio()
    {
        $stmt = $this->pdo->query(
            "SELECT m.precio FROM receta r
             JOIN medicamento m ON r.id_medicamento = m.id"
        );
        $precios = $stmt->fetchAll(PDO::FETCH_COLUMN);
        foreach ($precios as $precio) {
            $this->assertGreaterThan(0, $precio, 'El medicamento recetado debe tener precio mayor a 0');
        }
    }

    public function test_receta_mas_reciente()
    {
        $stmt = $this->pdo->query("SELECT * FROM receta ORDER BY creado_en DESC LIMIT 1");
        $receta = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->assertNotFalse($receta, 'Debe existir al menos una receta');
    }

    public function test_dosis_receta_no_numerica_vacia()
    {
        $stmt = $this->pdo->query("SELECT * FROM receta WHERE dosis = '' OR dosis IS NULL");
        $invalidas = $stmt->fetchAll();
        $this->assertEmpty($invalidas, 'La dosis de la receta no debe estar vacia');
    }
}
