<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../php/helpers.php';

// Cargar .env solo si existe (local). En CI las vars vienen del entorno.
if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
}
// Poblar $_ENV desde getenv() para compatibilidad con GitHub Actions
foreach (['DB_HOST', 'DB_USER', 'DB_PASS', 'DB_NAME'] as $key) {
    $val = getenv($key);
    if ($val !== false && !isset($_ENV[$key])) {
        $_ENV[$key] = $val;
    }
}

