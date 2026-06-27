<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../php/helpers.php';

class HelpersTest extends TestCase
{
    public function testValidarTelefonoCorrecto()
    {
        $this->assertTrue(validarTelefono('987654321'));
    }

    public function testValidarTelefonoConLetras()
    {
        $this->assertFalse(validarTelefono('98765abcd'));
    }

    public function testValidarTelefonoCortoEsInvalido()
    {
        $this->assertFalse(validarTelefono('12345'));
    }

    public function testValidarRolUsuarioValidoPaciente()
    {
        $this->assertTrue(validarRolUsuario('paciente'));
    }

    public function testValidarRolUsuarioValidoDoctor()
    {
        $this->assertTrue(validarRolUsuario('doctor'));
    }

    public function testValidarRolUsuarioInvalido()
    {
        $this->assertFalse(validarRolUsuario('superadmin'));
    }

    public function testValidarTipoDocumentoValido()
    {
        $this->assertTrue(validarTipoDocumento('DNI'));
    }

    public function testValidarTipoDocumentoInvalido()
    {
        $this->assertFalse(validarTipoDocumento('RUC'));
    }

    public function testValidarPasswordCorrecta()
    {
        $this->assertTrue(validarPassword('123456'));
    }

    public function testValidarPasswordCorta()
    {
        $this->assertFalse(validarPassword('123'));
    }

    public function testCalcularSubtotalCarrito()
    {
        $items = [
            ['precio' => 10.00, 'cantidad' => 2],
            ['precio' => 5.50, 'cantidad' => 3],
        ];
        $this->assertEquals(36.50, calcularSubtotalCarrito($items));
    }

    public function testCalcularSubtotalCarritoVacio()
    {
        $this->assertEquals(0.0, calcularSubtotalCarrito([]));
    }

    public function testFormatearNombreCompleto()
    {
        $this->assertEquals('Alyson Perez Flores', formatearNombreCompleto('Alyson', 'Perez Flores'));
    }
}