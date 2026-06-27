<?php
use PHPUnit\Framework\TestCase;

class UsuariosTest extends TestCase
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

    public function test_buscar_usuario_por_documento()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM usuario WHERE numero_documento = ?");
        $stmt->execute(['72471842']);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->assertNotFalse($usuario, 'Debe encontrar usuario por numero de documento');
    }

    public function test_buscar_usuario_por_rol_paciente()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM usuario WHERE rol = ?");
        $stmt->execute(['paciente']);
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->assertNotEmpty($usuarios, 'Debe haber usuarios con rol paciente');
    }

    public function test_buscar_usuario_por_rol_doctor()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM usuario WHERE rol = ?");
        $stmt->execute(['doctor']);
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->assertNotEmpty($usuarios, 'Debe haber usuarios con rol doctor');
    }

    public function test_ver_perfil_usuario()
    {
        $stmt = $this->pdo->prepare(
            "SELECT id, nombres, apellidos, telefono, rol FROM usuario WHERE id = ?"
        );
        $stmt->execute([1]);
        $perfil = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->assertNotFalse($perfil, 'Debe poder ver el perfil del usuario');
        $this->assertArrayHasKey('nombres', $perfil);
        $this->assertArrayHasKey('apellidos', $perfil);
        $this->assertArrayHasKey('rol', $perfil);
    }

    public function test_actualizar_telefono_usuario()
    {
        $stmt = $this->pdo->prepare("UPDATE usuario SET telefono = ? WHERE id = 1");
        $result = $stmt->execute(['999444777']);
        $this->assertTrue($result, 'Debe poder actualizar el telefono del usuario');
    }

    public function test_contar_pacientes_registrados()
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM usuario WHERE rol = 'paciente'");
        $total = $stmt->fetchColumn();
        $this->assertGreaterThanOrEqual(1, $total, 'Debe haber al menos 1 paciente registrado');
    }

    public function test_contar_doctores_registrados()
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM usuario WHERE rol = 'doctor'");
        $total = $stmt->fetchColumn();
        $this->assertGreaterThanOrEqual(1, $total, 'Debe haber al menos 1 doctor registrado');
    }

    public function test_usuario_tiene_fecha_creacion()
    {
        $stmt = $this->pdo->query("SELECT * FROM usuario WHERE creado_en IS NULL");
        $sinFecha = $stmt->fetchAll();
        $this->assertEmpty($sinFecha, 'Todo usuario debe tener fecha de creacion');
    }

    public function test_doctores_tienen_especialidad_no_vacia()
    {
        $stmt = $this->pdo->query("SELECT * FROM usuario WHERE rol = 'doctor' AND (especialidad IS NULL OR especialidad = '')");
        $invalidos = $stmt->fetchAll();
        $this->assertEmpty($invalidos, 'Todo doctor debe tener especialidad definida');
    }

    public function test_total_usuarios_en_sistema()
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM usuario");
        $total = $stmt->fetchColumn();
        $this->assertGreaterThanOrEqual(3, $total, 'Debe haber al menos 3 usuarios en el sistema');
    }
}
