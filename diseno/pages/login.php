<?php require_once __DIR__ . '/../../php/sesion.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Ingresar - UCSP Farmacia</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
  <link rel="stylesheet" href="../css/style1.css">
  <link rel="stylesheet" href="../css/style.css">
  <script src="../js/script.js"></script>
  <style>
    :root{
      --navy: #1b2a56;
      --navy-light: #2e4a8f;
      --bg: #f3f4f8;
      --text: #1c1c22;
      --muted: #8a8f98;
      --border: #dfe2ea;
      --error: #c0392b;
    }

    *{ box-sizing: border-box; }

    body{
      margin:0;
      min-height:100vh;
      background:var(--bg);
      font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
      color:var(--text);
      display:flex;
      flex-direction:column;
      align-items:stretch;
      padding:0;
    }

    .page-content{
      width:100%;
      display:flex;
      align-items:center;
      justify-content:center;
      padding:40px 16px;
      flex:1;
    }

    .card{
      width:100%;
      max-width:420px;
      background:#fff;
      border-radius:14px;
      box-shadow: 0 12px 40px rgba(27,42,86,0.12);
      padding:36px 32px 32px;
    }

    .card h1{
      margin:0 0 28px;
      font-size:22px;
      font-weight:700;
      text-align:center;
      letter-spacing:.2px;
    }

    .field{
      margin-bottom:18px;
    }
    .field label{
      display:block;
      font-size:13px;
      font-weight:600;
      margin-bottom:6px;
      color:var(--text);
    }
    .field label .req{ color:var(--error); }

    .field input,
    .field select{
      width:100%;
      padding:11px 12px;
      border:1.5px solid var(--border);
      border-radius:8px;
      font-size:14px;
      color:var(--text);
      background:#fff;
      outline:none;
      transition: border-color .15s ease;
    }
    .field input:focus,
    .field select:focus{
      border-color: var(--navy-light);
    }
    .field input::placeholder{ color:#b3b7c0; }

    .field .error-msg{
      display:none;
      color:var(--error);
      font-size:12px;
      margin-top:5px;
    }
    .field.invalid input,
    .field.invalid select{
      border-color: var(--error);
    }
    .field.invalid .error-msg{ display:block; }

    /* Selector de rol (tarjetas) */
    .role-options{
      display:flex;
      gap:10px;
      margin-bottom:8px;
    }
    .role-card{
      flex:1;
      border:1.5px solid var(--border);
      border-radius:10px;
      padding:14px 6px;
      text-align:center;
      cursor:pointer;
      transition: all .15s ease;
    }
    .role-card i{
      font-size:20px;
      color:var(--muted);
      margin-bottom:6px;
      display:block;
    }
    .role-card span{
      font-size:12px;
      font-weight:600;
      color:var(--text);
    }
    .role-card.selected{
      border-color:var(--navy);
      background:#eef1fa;
    }
    .role-card.selected i{ color:var(--navy); }

    .btn{
      width:100%;
      padding:13px;
      border:none;
      border-radius:8px;
      background:var(--navy);
      color:#fff;
      font-size:15px;
      font-weight:700;
      cursor:pointer;
      transition: background .15s ease;
      margin-top:6px;
    }
    .btn:hover{ background:var(--navy-light); }

    .forgot-link{
      display:block;
      text-align:right;
      font-size:12px;
      color:var(--muted);
      text-decoration:none;
      margin-top:-10px;
      margin-bottom:18px;
    }
    .forgot-link:hover{ color:var(--navy); }

    .signup-link{
      text-align:center;
      font-size:13px;
      color:var(--muted);
      margin-top:18px;
    }
    .signup-link a{ color:var(--navy); font-weight:600; text-decoration:none; }

    .error-global{
      color:var(--error);
      font-size:13px;
      text-align:center;
      margin-bottom:14px;
      display:none;
    }
    .error-global.visible{ display:block; }
  </style>
</head>
<body>

  <!-- ENCABEZADO -->
  <header>
    <div class="container-hero">
        <div class="container hero">
            <div class="customer-support">
                <i class="fa-solid fa-headset"></i>
                <div class="content-customer-support">
                    <span class="text">Soporte al cliente</span>
                    <span class="number">123-456-7890</span>
                </div>
            </div>

            <div class="container-logo">
                <h1 class="logo"><a href="/">UCSP</a></h1>
                <i class="fa-solid fa-hospital"></i>
                <h1 class="logo"><a href="/">FARMACIA</a></h1>
            </div>

            <div class="container-icons">
                <div class="container-user">
                    <i class="fa-solid fa-user"></i>
                    <div class="user-panel" style="display: none;">
                        <div class="user-panel-content">
                            <button class="close-user-btn">&times;</button>
                            <?php if ($usuarioLogueado): ?>
                                <p style="padding: 10px; font-weight: bold;">Hola, <?php echo $nombreUsuario; ?> 👋</p>
                                <ul>
                                    <li><a href="/farmacia/php/logout.php">Cerrar sesión</a></li>
                                </ul>
                            <?php else: ?>
                                <ul>
                                    <li><a href="../pages/login.php">Ingresar</a></li>
                                    <li><a href="../pages/signup.php">Crear cuenta</a></li>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="container-cart">
                    <i class="fa-solid fa-basket-shopping"></i>
                    <div class="content-shopping-cart">
                        <span class="number">(0)</span>
                    </div>
                </div>
            </div>

            <div class="cart-panel">
                <div class="cart-panel-content">
                    <button class="close-cart-btn">&times;</button>
                    <h2>Carrito:</h2>
                    <div class="cart-items"></div>
                    <div class="cart-summary">
                        <p class="cart-quantity">Cantidad: <span class="total-quantity">0</span></p>
                        <p class="cart-total">Subtotal: S/ <span class="total-price">0.00</span></p>
                        <button class="checkout-btn">Finalizar pedido</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container__menu">
        <div class="menu">
            <input type="checkbox" id="check__menu" name="menu">
            <label for="check__menu" id="label__check">Abrir menú</label>
                <i class="fas fa-bars icon__menu"></i>
            </label>
            <nav>
                <ul>
                    <?php if ($rolUsuario === 'paciente'): ?>

                        <li><a href="../pages/index.php">Inicio</a></li>
                        <li><a href="../pages/farmacia.php">Farmacia</a></li>
                        <li><a href="../pages/consultapaciente.php">Resultados Médicos</a></li>
                        <li><a href="../pages/citapaciente.php">Reservar Cita</a></li>
                        <li><a href="../pages/recetapaciente.php">Receta</a></li>

                    <?php elseif ($rolUsuario === 'doctor'): ?>

                        <li><a href="../pages/index.php">Inicio</a></li>
                        <li><a href="#">Paciente</a>
                            <ul>
                                <li><a href="../pages/registro_paciente.php">Registro de Paciente</a></li>
                                <li><a href="../pages/historial_medico.php">Historial Médico del Paciente</a></li>
                            </ul>
                        </li>
                        <li><a href="#">Doctor</a>
                            <ul>
                                <li><a href="../pages/perfildoctor.php">Perfil</a></li>
                                <li><a href="../pages/citas_doctor.php">Mis Citas Pendientes</a></li>
                            </ul>
                        </li>

                    <?php elseif ($rolUsuario === 'administrador'): ?>

                        <li><a href="../pages/index.php">Inicio</a></li>
                        <li><a href="../pages/farmacia.php">Farmacia</a>
                            <ul>
                                <li><a href="../pages/agregar_medicamento.php">Agregar Medicamento</a></li>
                                <li><a href="../pages/agregar_suplemento.php">Agregar Suplemento</a></li>
                            </ul>
                        </li>

                    <?php else: ?>

                        <li><a href="../pages/index.php">Inicio</a></li>
                        <li><a href="../pages/farmacia.php">Farmacia</a></li>
                        <li><a href="../pages/login.php">Ingresar</a></li>
                        <li><a href="../pages/signup.php">Crear cuenta</a></li>

                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>
  </header>
  <!-- FIN ENCABEZADO -->

  <div class="page-content">
    <div class="card">
      <h1>Ingresar</h1>

      <div class="error-global" id="loginError"></div>

      <form id="loginForm" action="/farmacia/php/login.php" method="post" novalidate>

        <div class="field" id="field-rol">
          <label>¿Cómo deseas ingresar? <span class="req">*</span></label>
          <div class="role-options">
            <div class="role-card" data-rol="paciente">
              <i class="fa-solid fa-user-injured"></i>
              <span>Paciente</span>
            </div>
            <div class="role-card" data-rol="doctor">
              <i class="fa-solid fa-user-doctor"></i>
              <span>Doctor</span>
            </div>
            <div class="role-card" data-rol="administrador">
              <i class="fa-solid fa-user-gear"></i>
              <span>Admin</span>
            </div>
          </div>
          <input type="hidden" name="rol" id="rol" value="">
          <div class="error-msg">Selecciona cómo deseas ingresar.</div>
        </div>

        <div class="field" id="field-tipoDoc">
          <label for="tipo_documento">Tipo de documento <span class="req">*</span></label>
          <select name="tipo_documento" id="tipo_documento">
            <option value="" disabled selected>Selecciona una opción</option>
            <option value="DNI">DNI</option>
            <option value="CE">Carné de Extranjería</option>
            <option value="PASAPORTE">Pasaporte</option>
          </select>
          <div class="error-msg">Selecciona un tipo de documento.</div>
        </div>

        <div class="field" id="field-numDoc">
          <label for="numero_documento">Número de documento <span class="req">*</span></label>
          <input type="text" name="numero_documento" id="numero_documento" placeholder="Ej. 70123456">
          <div class="error-msg">Ingresa tu número de documento.</div>
        </div>

        <div class="field" id="field-password">
          <label for="contrasena">Contraseña <span class="req">*</span></label>
          <input type="password" name="contraseña" id="contrasena" placeholder="Tu contraseña">
          <div class="error-msg">Ingresa tu contraseña.</div>
        </div>

        <a href="#" class="forgot-link">¿Olvidaste tu contraseña?</a>

        <button type="submit" class="btn">Ingresar</button>

        <div class="signup-link">¿No tienes cuenta? <a href="../pages/signup.php">Crear cuenta</a></div>

      </form>
    </div>
  </div>

  <script>
    // Mostrar error desde URL (compatibilidad con flujo anterior)
    const params = new URLSearchParams(window.location.search);
    const urlError = params.get('error');
    if (urlError) {
      const el = document.getElementById('loginError');
      el.textContent = decodeURIComponent(urlError.replace(/\+/g, ' '));
      el.classList.add('visible');
    }

    const rolInput = document.getElementById('rol');

    document.querySelectorAll('.role-card').forEach(function(card){
      card.addEventListener('click', function(){
        document.querySelectorAll('.role-card').forEach(c => c.classList.remove('selected'));
        card.classList.add('selected');
        rolInput.value = card.getAttribute('data-rol');
      });
    });

    function markError(fieldId, isInvalid) {
      document.getElementById(fieldId).classList.toggle('invalid', isInvalid);
    }

    document.getElementById('loginForm').addEventListener('submit', function(e) {
      let ok = true;

      const rol = rolInput.value;
      const tipo = document.getElementById('tipo_documento').value;
      const num = document.getElementById('numero_documento').value.trim();
      const pass = document.getElementById('contrasena').value;

      markError('field-rol', rol === '');   if (rol === '') ok = false;
      markError('field-tipoDoc', tipo === ''); if (tipo === '') ok = false;
      markError('field-numDoc', num === '');   if (num === '') ok = false;
      markError('field-password', pass === ''); if (pass === '') ok = false;

      if (!ok) e.preventDefault();
    });
  </script>

</body>
</html>