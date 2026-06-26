<?php
// sesion.php
// Este archivo se incluye al inicio de las páginas para saber si hay un usuario logueado.
session_start();

$usuarioLogueado = isset($_SESSION['logueado']) && $_SESSION['logueado'] === true;
$nombreUsuario = $usuarioLogueado ? htmlspecialchars($_SESSION['nombres']) : '';
$rolUsuario = $usuarioLogueado ? htmlspecialchars($_SESSION['rol']) : '';
?>