<?php
use PHPUnit\Framework\TestCase;

class RolesTest extends TestCase
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

    public function test_solo_existen_roles_validos()
    {
        $stmt = $this->pdo->query("SELECT DISTINCT rol FROM usuario");
        $roles = $stmt->fetchAll(PDO::FETCH_COLUMN);
        foreach ($roles as $rol) {
            $this->assertContains($rol, ['paciente', 'doctor', 'administrador']);
        }
    }

    public function test_paciente_no_tiene_especialidad()
    {
        $stmt = $this->pdo->query("SELECT * FROM usuario WHERE rol = 'paciente' AND especialidad IS NOT NULL");
        $resultado = $stmt->fetchAll();
        $this->assertEmpty($resultado, 'Un paciente no debe tener especialidad asignada');
    }

    public function test_doctor_tiene_especialidad()
    {
        $stmt = $this->pdo->query("SELECT * FROM usuario WHERE rol = 'doctor'");
        $doctores = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($doctores as $doctor) {
            $this->assertNotNull($doctor['especialidad'], "El doctor {$doctor['nombres']} debe tener especialidad");
        }
    }

    public function test_existe_al_menos_un_administrador()
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM usuario WHERE rol = 'administrador'");
        $total = $stmt->fetchColumn();
        $this->assertGreaterThan(0, $total, 'Debe existir al menos un administrador');
    }

    public function test_existe_al_menos_un_doctor()
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM usuario WHERE rol = 'doctor'");
        $total = $stmt->fetchColumn();
        $this->assertGreaterThan(0, $total, 'Debe existir al menos un doctor');
    }

    public function test_existe_al_menos_un_paciente()
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM usuario WHERE rol = 'paciente'");
        $total = $stmt->fetchColumn();
        $this->assertGreaterThan(0, $total, 'Debe existir al menos un paciente');
    }

    public function test_password_no_esta_vacia()
    {
        $stmt = $this->pdo->query("SELECT * FROM usuario WHERE password IS NULL OR password = ''");
        $sinPassword = $stmt->fetchAll();
        $this->assertEmpty($sinPassword, 'Ningun usuario debe tener password vacio');
    }

    public function test_numero_documento_es_unico()
    {
        $stmt = $this->pdo->query("SELECT numero_documento, COUNT(*) as total FROM usuario GROUP BY numero_documento HAVING total > 1");
        $duplicados = $stmt->fetchAll();
        $this->assertEmpty($duplicados, 'No debe haber numeros de documento duplicados');
    }

    public function test_telefono_no_esta_vacio()
    {
        $stmt = $this->pdo->query("SELECT * FROM usuario WHERE telefono IS NULL OR telefono = ''");
        $sinTelefono = $stmt->fetchAll();
        $this->assertEmpty($sinTelefono, 'Ningun usuario debe tener telefono vacio');
    }
}
