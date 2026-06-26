<?php require_once __DIR__ . '/../../php/sesion.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Crear cuenta - UCSP Farmacia</title>
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

    /* Step indicator */
    .steps{
      display:flex;
      align-items:center;
      justify-content:center;
      margin-bottom:32px;
    }
    .step-dot{
      width:32px;
      height:32px;
      border-radius:50%;
      border:2px solid var(--border);
      color:var(--muted);
      display:flex;
      align-items:center;
      justify-content:center;
      font-weight:700;
      font-size:14px;
      background:#fff;
      transition: all .25s ease;
    }
    .step-dot.active{
      border-color:var(--navy);
      background:var(--navy);
      color:#fff;
    }
    .step-dot.done{
      border-color:var(--navy);
      background:#fff;
      color:var(--navy);
    }
    .step-line{
      width:48px;
      height:2px;
      background:var(--border);
      margin:0 6px;
      transition: background .25s ease;
    }
    .step-line.filled{
      background:var(--navy);
    }

    /* Form */
    .step-panel{ display:none; }
    .step-panel.active{ display:block; }

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
      gap:12px;
      margin-bottom:8px;
    }
    .role-card{
      flex:1;
      border:1.5px solid var(--border);
      border-radius:10px;
      padding:16px 8px;
      text-align:center;
      cursor:pointer;
      transition: all .15s ease;
    }
    .role-card i{
      font-size:22px;
      color:var(--muted);
      margin-bottom:8px;
      display:block;
    }
    .role-card span{
      font-size:13px;
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

    .btn-back{
      width:100%;
      background:none;
      border:none;
      color:var(--muted);
      font-size:13px;
      margin-top:14px;
      cursor:pointer;
      text-align:center;
    }
    .btn-back:hover{ color:var(--navy); }

    .login-link{
      text-align:center;
      font-size:13px;
      color:var(--muted);
      margin-top:18px;
    }
    .login-link a{ color:var(--navy); font-weight:600; text-decoration:none; }
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

  <!-- CONTENIDO PRINCIPAL  -->
  <div class="page-content">
    <div class="card">
      <h1>Crear cuenta</h1>

      <div class="steps">
        <div class="step-dot active" id="dot-0">1</div>
        <div class="step-line" id="line-0"></div>
        <div class="step-dot" id="dot-1">2</div>
        <div class="step-line" id="line-1"></div>
        <div class="step-dot" id="dot-2">3</div>
      </div>

      <form id="registerForm" method="POST" action="../../php/procesar_registro.php" novalidate>

        <!-- PASO 0: elegir el rol -->
        <div class="step-panel active" id="panel-0">
          <div class="field" id="field-rol">
            <label>¿Qué tipo de cuenta deseas crear? <span class="req">*</span></label>
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
            <div class="error-msg">Selecciona un tipo de cuenta.</div>
          </div>

          <button type="button" class="btn" id="btnSiguiente0">Siguiente paso</button>
        </div>

        <!-- PASO 1: documento -->
        <div class="step-panel" id="panel-1">
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
            <label for="numero_documento">Ingrese el documento <span class="req">*</span></label>
            <input type="text" name="numero_documento" id="numero_documento" placeholder="Ej. 70123456">
            <div class="error-msg">Ingresa tu número de documento.</div>
          </div>

          <div class="field" id="field-fecha">
            <label for="fecha_nacimiento">Fecha de nacimiento <span class="req">*</span></label>
            <input type="date" name="fecha_nacimiento" id="fecha_nacimiento">
            <div class="error-msg">Selecciona tu fecha de nacimiento.</div>
          </div>

          <button type="button" class="btn" id="btnSiguiente">Siguiente paso</button>
          <button type="button" class="btn-back" id="btnAtras0">← Volver al paso anterior</button>

          <div class="login-link">¿Ya tienes cuenta? <a href="../pages/login.php">Inicia sesión</a></div>
        </div>

        <!-- PASO 2: datos personales -->
        <div class="step-panel" id="panel-2">
          <div class="field" id="field-nombres">
            <label for="nombres">Nombres <span class="req">*</span></label>
            <input type="text" name="nombres" id="nombres" placeholder="Ej. Gabriela">
            <div class="error-msg">Ingresa tus nombres.</div>
          </div>

          <div class="field" id="field-apellidos">
            <label for="apellidos">Apellidos <span class="req">*</span></label>
            <input type="text" name="apellidos" id="apellidos" placeholder="Ej. Espinoza Torres">
            <div class="error-msg">Ingresa tus apellidos.</div>
          </div>

          <div class="field" id="field-telefono">
            <label for="telefono">Número de teléfono <span class="req">*</span></label>
            <input type="tel" name="telefono" id="telefono" placeholder="Ej. 987654321">
            <div class="error-msg">Ingresa un teléfono válido (9 dígitos).</div>
          </div>

          <!-- Campo extra, solo visible si el rol es doctor -->
          <div class="field" id="field-especialidad" style="display:none;">
            <label for="especialidad">Especialidad <span class="req">*</span></label>
            <input type="text" name="especialidad" id="especialidad" placeholder="Ej. Pediatría">
            <div class="error-msg">Ingresa tu especialidad.</div>
          </div>

          <div class="field" id="field-password">
            <label for="password">Contraseña <span class="req">*</span></label>
            <input type="password" name="password" id="password" placeholder="Mínimo 6 caracteres">
            <div class="error-msg">La contraseña debe tener al menos 6 caracteres.</div>
          </div>

          <button type="submit" class="btn">Registrarse</button>
          <button type="button" class="btn-back" id="btnAtras">← Volver al paso anterior</button>
        </div>

      </form>
    </div>
  </div>
  <!-- ══════════════════ FIN CONTENIDO ══════════════════ -->

<script>
  const dot0 = document.getElementById('dot-0');
  const dot1 = document.getElementById('dot-1');
  const dot2 = document.getElementById('dot-2');
  const line0 = document.getElementById('line-0');
  const line1 = document.getElementById('line-1');
  const panel0 = document.getElementById('panel-0');
  const panel1 = document.getElementById('panel-1');
  const panel2 = document.getElementById('panel-2');
  const rolInput = document.getElementById('rol');
  const campoEspecialidad = document.getElementById('field-especialidad');

  function showStep(step){
    panel0.classList.remove('active');
    panel1.classList.remove('active');
    panel2.classList.remove('active');

    dot0.classList.remove('active','done');
    dot1.classList.remove('active','done');
    dot2.classList.remove('active','done');
    line0.classList.remove('filled');
    line1.classList.remove('filled');

    if(step === 0){
      panel0.classList.add('active');
      dot0.classList.add('active');
    } else if(step === 1){
      panel1.classList.add('active');
      dot0.classList.add('done');
      dot1.classList.add('active');
      line0.classList.add('filled');
    } else {
      panel2.classList.add('active');
      dot0.classList.add('done');
      dot1.classList.add('done');
      dot2.classList.add('active');
      line0.classList.add('filled');
      line1.classList.add('filled');
    }
  }

  function markError(fieldId, isInvalid){
    const field = document.getElementById(fieldId);
    field.classList.toggle('invalid', isInvalid);
  }

  // Selección de tarjeta de rol
  document.querySelectorAll('.role-card').forEach(function(card){
    card.addEventListener('click', function(){
      document.querySelectorAll('.role-card').forEach(c => c.classList.remove('selected'));
      card.classList.add('selected');
      rolInput.value = card.getAttribute('data-rol');

      // Mostrar campo especialidad solo si es doctor
      campoEspecialidad.style.display = (rolInput.value === 'doctor') ? 'block' : 'none';
    });
  });

  function validateStep0(){
    const ok = rolInput.value !== '';
    markError('field-rol', !ok);
    return ok;
  }

  function validateStep1(){
    let ok = true;
    const tipoDoc = document.getElementById('tipo_documento').value.trim();
    const numDoc = document.getElementById('numero_documento').value.trim();
    const fecha = document.getElementById('fecha_nacimiento').value.trim();

    markError('field-tipoDoc', tipoDoc === ''); if(tipoDoc === '') ok = false;
    markError('field-numDoc', numDoc === ''); if(numDoc === '') ok = false;
    markError('field-fecha', fecha === ''); if(fecha === '') ok = false;

    return ok;
  }

  function validateStep2(){
    let ok = true;
    const nombres = document.getElementById('nombres').value.trim();
    const apellidos = document.getElementById('apellidos').value.trim();
    const telefono = document.getElementById('telefono').value.trim();
    const password = document.getElementById('password').value;

    markError('field-nombres', nombres === ''); if(nombres === '') ok = false;
    markError('field-apellidos', apellidos === ''); if(apellidos === '') ok = false;

    const telOk = /^[0-9]{9}$/.test(telefono);
    markError('field-telefono', !telOk); if(!telOk) ok = false;

    const passOk = password.length >= 6;
    markError('field-password', !passOk); if(!passOk) ok = false;

    // Validar especialidad solo si el rol es doctor
    if(rolInput.value === 'doctor'){
      const especialidad = document.getElementById('especialidad').value.trim();
      markError('field-especialidad', especialidad === ''); if(especialidad === '') ok = false;
    }

    return ok;
  }

  document.getElementById('btnSiguiente0').addEventListener('click', function(){
    if(validateStep0()){
      showStep(1);
    }
  });

  document.getElementById('btnAtras0').addEventListener('click', function(){
    showStep(0);
  });

  document.getElementById('btnSiguiente').addEventListener('click', function(){
    if(validateStep1()){
      showStep(2);
    }
  });

  document.getElementById('btnAtras').addEventListener('click', function(){
    showStep(1);
  });

  document.getElementById('registerForm').addEventListener('submit', function(e){
    if(!validateStep2()){
      e.preventDefault();
    }
  });
</script>

</body>
</html>