<?php
class UsuarioRepository
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function registrarPaciente(array $datos): bool
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO usuario (tipo_documento, numero_documento, fecha_nacimiento, nombres, apellidos, telefono, password, rol)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE nombres=nombres"
        );
        return $stmt->execute([
            $datos['tipo_documento'], $datos['numero_documento'], $datos['fecha_nacimiento'],
            $datos['nombres'], $datos['apellidos'], $datos['telefono'], $datos['password'], 'paciente'
        ]);
    }

    public function buscarPorDocumento(string $numeroDocumento)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM usuario WHERE numero_documento = ?");
        $stmt->execute([$numeroDocumento]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
