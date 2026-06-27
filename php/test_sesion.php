<?php
session_start();
echo "<h2>Datos de la sesión actual:</h2>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

if (isset($_SESSION['logueado']) && $_SESSION['logueado'] === true) {
    echo "<p style='color:green;'>✅ Sesión activa correctamente.</p>";
} else {
    echo "<p style='color:red;'>❌ No hay sesión activa.</p>";
}
?>