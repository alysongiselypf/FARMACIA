<?php
/**
 * helpers.php
 * Funciones puras de validación y cálculo, sin dependencia de base de datos.
 * Estas funciones son las que se prueban con PHPUnit.
 */

function validarTelefono(string $telefono): bool
{
    return preg_match('/^[0-9]{9}$/', $telefono) === 1;
}

function validarRolUsuario(string $rol): bool
{
    $rolesValidos = ['paciente', 'doctor', 'administrador'];
    return in_array($rol, $rolesValidos, true);
}

function validarTipoDocumento(string $tipo): bool
{
    $tiposValidos = ['DNI', 'CE', 'PASAPORTE'];
    return in_array($tipo, $tiposValidos, true);
}

function validarPassword(string $password): bool
{
    return strlen($password) >= 6;
}

function calcularSubtotalCarrito(array $items): float
{
    $total = 0.0;
    foreach ($items as $item) {
        $total += $item['precio'] * $item['cantidad'];
    }
    return $total;
}

function formatearNombreCompleto(string $nombres, string $apellidos): string
{
    return trim($nombres) . ' ' . trim($apellidos);
}