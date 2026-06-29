<?php
use PHPUnit\Framework\TestCase;

class UsuarioRepositoryTest extends TestCase
{
    public function test_registrar_paciente_ejecuta_insert_correctamente()
    {
        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->expects($this->once())
                 ->method('execute')
                 ->willReturn(true);

        $pdoMock = $this->createMock(PDO::class);
        $pdoMock->expects($this->once())
                ->method('prepare')
                ->willReturn($stmtMock);

        $repo = new UsuarioRepository($pdoMock);

        $resultado = $repo->registrarPaciente([
            'tipo_documento' => 'DNI',
            'numero_documento' => '99999999',
            'fecha_nacimiento' => '2000-01-01',
            'nombres' => 'Test',
            'apellidos' => 'Paciente',
            'telefono' => '999000000',
            'password' => '123456',
        ]);

        $this->assertTrue($resultado);
    }

    public function test_buscar_por_documento_retorna_datos_simulados()
    {
        $datosSimulados = ['id' => 1, 'nombres' => 'Test', 'rol' => 'paciente'];

        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->method('execute')->willReturn(true);
        $stmtMock->method('fetch')->willReturn($datosSimulados);

        $pdoMock = $this->createMock(PDO::class);
        $pdoMock->method('prepare')->willReturn($stmtMock);

        $repo = new UsuarioRepository($pdoMock);
        $resultado = $repo->buscarPorDocumento('99999999');

        $this->assertEquals('Test', $resultado['nombres']);
        $this->assertEquals('paciente', $resultado['rol']);
    }
}
