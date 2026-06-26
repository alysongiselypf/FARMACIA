<?php
use PHPUnit\Framework\TestCase;

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

    // ── TABLAS EXISTEN ────────────────────────────────────

    public function test_tabla_usuario_existe()
    {
        $stmt = $this->pdo->query("SHOW TABLES LIKE 'usuario'");
        $this->assertNotFalse($stmt->fetch());
    }

    public function test_tabla_medicamento_existe()
    {
        $stmt = $this->pdo->query("SHOW TABLES LIKE 'medicamento'");
        $this->assertNotFalse($stmt->fetch());
    }

    public function test_tabla_cita_existe()
    {
        $stmt = $this->pdo->query("SHOW TABLES LIKE 'cita'");
        $this->assertNotFalse($stmt->fetch());
    }

    public function test_tabla_consulta_existe()
    {
        $stmt = $this->pdo->query("SHOW TABLES LIKE 'consulta'");
        $this->assertNotFalse($stmt->fetch());
    }

    public function test_tabla_receta_existe()
    {
        $stmt = $this->pdo->query("SHOW TABLES LIKE 'receta'");
        $this->assertNotFalse($stmt->fetch());
    }

    public function test_tabla_pedido_existe()
    {
        $stmt = $this->pdo->query("SHOW TABLES LIKE 'pedido'");
        $this->assertNotFalse($stmt->fetch());
    }

    public function test_tabla_detalle_pedido_existe()
    {
        $stmt = $this->pdo->query("SHOW TABLES LIKE 'detalle_pedido'");
        $this->assertNotFalse($stmt->fetch());
    }

    // ── REGISTRO E INICIO DE SESION ───────────────────────

    public function test_registrar_paciente()
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO usuario (tipo_documento, numero_documento, fecha_nacimiento, nombres, apellidos, telefono, password, rol)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE nombres=nombres"
        );
        $result = $stmt->execute(['DNI', '99999999', '2000-01-01', 'Test', 'Paciente', '999000000', '123456', 'paciente']);
        $this->assertTrue($result, 'Debe poder registrar un paciente');
    }

    public function test_registrar_doctor()
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO usuario (tipo_documento, numero_documento, fecha_nacimiento, nombres, apellidos, telefono, password, rol, especialidad)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE nombres=nombres"
        );
        $result = $stmt->execute(['DNI', '88888888', '1985-05-10', 'Test', 'Doctor', '988000000', '123456', 'doctor', 'Medicina General']);
        $this->assertTrue($result, 'Debe poder registrar un doctor');
    }

    public function test_registrar_administrador()
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO usuario (tipo_documento, numero_documento, fecha_nacimiento, nombres, apellidos, telefono, password, rol)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE nombres=nombres"
        );
        $result = $stmt->execute(['DNI', '77777777', '1990-03-15', 'Test', 'Admin', '977000000', '123456', 'administrador']);
        $this->assertTrue($result, 'Debe poder registrar un administrador');
    }

    public function test_inicio_sesion_paciente()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM usuario WHERE numero_documento = ? AND rol = 'paciente'");
        $stmt->execute(['99999999']);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->assertNotFalse($usuario, 'Debe encontrar el paciente registrado');
        $this->assertEquals('paciente', $usuario['rol']);
    }

    public function test_roles_validos_en_sistema()
    {
        $stmt = $this->pdo->query("SELECT DISTINCT rol FROM usuario");
        $roles = $stmt->fetchAll(PDO::FETCH_COLUMN);
        foreach ($roles as $rol) {
            $this->assertContains($rol, ['paciente', 'doctor', 'administrador'], "Rol '$rol' no es valido");
        }
    }

    // ── CITAS ─────────────────────────────────────────────

    public function test_reservar_cita()
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO cita (id_paciente, id_doctor, fecha_cita, hora_cita, motivo, estado)
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        $result = $stmt->execute([1, 3, '2026-12-01', '10:00:00', 'Consulta de prueba', 'pendiente']);
        $this->assertTrue($result, 'Debe poder reservar una cita');
    }

    public function test_consultar_citas_pendientes()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM cita WHERE estado = 'pendiente'");
        $stmt->execute();
        $citas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->assertIsArray($citas, 'Debe retornar un listado de citas pendientes');
    }

    public function test_estados_validos_cita()
    {
        $stmt = $this->pdo->query("SELECT DISTINCT estado FROM cita");
        $estados = $stmt->fetchAll(PDO::FETCH_COLUMN);
        foreach ($estados as $estado) {
            $this->assertContains($estado, ['pendiente', 'atendida', 'cancelada'], "Estado '$estado' no es valido");
        }
    }

    // ── CONSULTAS MEDICAS ─────────────────────────────────

    public function test_registrar_consulta_medica()
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO consulta (id_cita, id_paciente, id_doctor, fecha_consulta, motivo, diagnostico)
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        $result = $stmt->execute([2, 1, 3, '2026-12-01', 'Dolor de cabeza', 'Migraña leve']);
        $this->assertTrue($result, 'Debe poder registrar una consulta medica');
    }

    public function test_ver_historial_medico_paciente()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM consulta WHERE id_paciente = ?");
        $stmt->execute([1]);
        $historial = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->assertIsArray($historial, 'Debe retornar el historial medico del paciente');
    }

    // ── RECETAS ───────────────────────────────────────────

    public function test_generar_receta()
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO receta (id_consulta, id_medicamento, dosis, instrucciones)
             VALUES (?, ?, ?, ?)"
        );
        $result = $stmt->execute([1, 1, '1 tableta cada 8 horas', 'Tomar con agua']);
        $this->assertTrue($result, 'Debe poder generar una receta');
    }

    public function test_ver_receta_paciente()
    {
        $stmt = $this->pdo->prepare(
            "SELECT r.*, m.nombre FROM receta r 
             JOIN medicamento m ON r.id_medicamento = m.id
             JOIN consulta c ON r.id_consulta = c.id
             WHERE c.id_paciente = ?"
        );
        $stmt->execute([1]);
        $recetas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->assertIsArray($recetas, 'Debe retornar las recetas del paciente');
    }

    // ── FARMACIA / PEDIDOS ────────────────────────────────

    public function test_realizar_pedido()
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO pedido (id_usuario, fecha, nombre_envio, direccion, ciudad, telefono, total, estado)
             VALUES (?, NOW(), ?, ?, ?, ?, ?, ?)"
        );
        $result = $stmt->execute([1, 'Test Usuario', 'Av. Test 123', 'Arequipa', '999000001', 50.00, 'completado']);
        $this->assertTrue($result, 'Debe poder realizar un pedido');
    }

    public function test_agregar_detalle_pedido()
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO detalle_pedido (id_pedido, nombre_producto, cantidad, precio_unitario, subtotal)
             VALUES (?, ?, ?, ?, ?)"
        );
        $result = $stmt->execute([1, 'Paracetamol', 2, 10.00, 20.00]);
        $this->assertTrue($result, 'Debe poder agregar detalle al pedido');
    }

    public function test_ver_pedidos_usuario()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM pedido WHERE id_usuario = ?");
        $stmt->execute([1]);
        $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->assertIsArray($pedidos, 'Debe retornar los pedidos del usuario');
    }

    // ── ADMINISTRADOR ─────────────────────────────────────

    public function test_agregar_medicamento()
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO medicamento (nombre, clase, precio, stock, imagen, tipo)
             VALUES (?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE nombre=nombre"
        );
        $result = $stmt->execute(['Medicamento Test', 'Analgesico', 5.50, 100, 'test.png', 'medicamento']);
        $this->assertTrue($result, 'Administrador debe poder agregar medicamento');
    }

    public function test_verificar_stock_medicamentos()
    {
        $stmt = $this->pdo->query("SELECT id, nombre, stock FROM medicamento WHERE stock > 0");
        $medicamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->assertNotEmpty($medicamentos, 'Debe haber medicamentos con stock disponible');
        foreach ($medicamentos as $med) {
            $this->assertGreaterThan(0, $med['stock'], "El stock de {$med['nombre']} debe ser mayor a 0");
        }
    }

    public function test_medicamentos_con_stock_bajo()
    {
        $stmt = $this->pdo->query("SELECT * FROM medicamento WHERE stock < 100");
        $bajoStock = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->assertIsArray($bajoStock, 'Debe poder consultar medicamentos con stock bajo');
    }
}

