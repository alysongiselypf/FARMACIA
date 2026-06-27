<?php require_once __DIR__ . '/../../php/sesion.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Agregar Suplemento - UCSP Farmacia</title>
  <link rel="stylesheet" href="../css/style1.css">
  <link rel="stylesheet" href="../css/style.css">
  <style>
    .card-admin{
      max-width:480px;
      margin:60px auto;
      background:#fff;
      border-radius:14px;
      box-shadow: 0 12px 40px rgba(27,42,86,0.12);
      padding:32px;
      font-family: 'Segoe UI', sans-serif;
    }
    .card-admin h1{ text-align:center; margin-bottom:24px; font-size:22px; }
    .campo{ margin-bottom:16px; }
    .campo label{ display:block; font-weight:600; margin-bottom:6px; font-size:13px; }
    .campo input{
      width:100%; padding:10px; border:1.5px solid #dfe2ea; border-radius:8px; font-size:14px;
    }
    .btn-guardar{
      width:100%; padding:12px; background:#1b2a56; color:#fff; border:none;
      border-radius:8px; font-weight:700; cursor:pointer; font-size:15px;
    }
    .btn-guardar:hover{ background:#2e4a8f; }
    .btn-volver{
      display:block;
      text-align:center;
      margin-top:14px;
      font-size:13px;
      color:#8a8f98;
      text-decoration:none;
    }
    .btn-volver:hover{ color:#1b2a56; }
  </style>
</head>
<body>

  <div class="card-admin">
    <h1>Agregar Suplemento</h1>

    <form method="POST" action="../../php/procesar_agregar_producto.php" enctype="multipart/form-data">
      <input type="hidden" name="tipo" value="suplemento">

      <div class="campo">
        <label for="nombre">Nombre del suplemento</label>
        <input type="text" name="nombre" id="nombre" placeholder="Ej. Vitamina C" required>
      </div>

      <div class="campo">
        <label for="clase">Clase</label>
        <input type="text" name="clase" id="clase" placeholder="Ej. Suplemento vitamínico" required>
      </div>

      <div class="campo">
        <label for="stock">Stock</label>
        <input type="number" name="stock" id="stock" min="0" placeholder="Ej. 100" required>
      </div>

      <div class="campo">
        <label for="precio">Precio (S/)</label>
        <input type="number" name="precio" id="precio" step="0.01" min="0" placeholder="Ej. 35.90" required>
      </div>

      <div class="campo">
            <label for="imagen">Imagen del suplemento</label>
            <input type="file" name="imagen" id="imagen" accept="image/*" required>
        </div>

      <button type="submit" class="btn-guardar">Guardar Suplemento</button>
    </form>

    <a href="../pages/farmacia.php" class="btn-volver">← Regresar a Farmacia</a>
  </div>

</body>
</html>