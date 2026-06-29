<?php
// sesion.php
// Este archivo se incluye al inicio de las páginas para saber si hay un usuario logueado.

// Cabeceras de seguridad HTTP (corrige hallazgos de OWASP ZAP)
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("Content-Security-Policy: default-src 'self'; script-src 'self' https://cdnjs.cloudflare.com https://cdn.jsdelivr.net 'unsafe-inline'; style-src 'self' https://cdnjs.cloudflare.com https://fonts.googleapis.com 'unsafe-inline'; font-src 'self' https://cdnjs.cloudflare.com https://fonts.gstatic.com data:; img-src 'self' data: https:; connect-src 'self';");
$usuarioLogueado = isset($_SESSION['logueado']) && $_SESSION['logueado'] === true;
$nombreUsuario = $usuarioLogueado ? htmlspecialchars($_SESSION['nombres']) : '';
$rolUsuario = $usuarioLogueado ? htmlspecialchars($_SESSION['rol']) : '';
?>
