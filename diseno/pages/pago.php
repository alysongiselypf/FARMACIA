<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UCSP Farmacia - Pago</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f5f5f5;
            font-size: 12px;
        }

        html {
            font-size: 55%;
        }

        header {
            background: white;
            padding: 1.5rem 3rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        header h1 {
            font-size: 2.2rem;
            font-weight: 700;
            color: #000;
            letter-spacing: -1px;
        }

        header i {
            font-size: 2.2rem;
        }

        .navbar {
            background: #023877;
            padding: 1rem 3rem;
        }

        .navbar p {
            color: white;
            font-size: 1.3rem;
            font-weight: 500;
        }

        .navbar span {
            color: #aac4f0;
        }

        .navbar span.active {
            color: white;
            font-weight: 600;
        }

        .page-container {
            max-width: 850px;
            margin: 2rem auto;
            padding: 0 2rem;
            display: grid;
            grid-template-columns: 1.4fr 1fr;
            gap: 2rem;
        }

        .card {
            background: white;
            border-radius: 1rem;
            padding: 1.8rem;
            box-shadow: 0 2px 12px rgba(0,0,0,0.07);
            margin-bottom: 1.5rem;
        }

        .card h2 {
            font-size: 1.6rem;
            font-weight: 600;
            color: #023877;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .card h2 i {
            font-size: 1.6rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            font-size: 1.2rem;
            font-weight: 500;
            color: #555;
            margin-bottom: 0.5rem;
        }

        .form-group input {
            width: 100%;
            padding: 0.7rem 1rem;
            border: 1.5px solid #e0e0e0;
            border-radius: 0.6rem;
            font-family: 'Poppins', sans-serif;
            font-size: 1.2rem;
            color: #333;
            transition: border 0.2s;
            outline: none;
        }

        .form-group input:focus {
            border-color: #023877;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.2rem;
        }

        .card-icons {
            display: flex;
            gap: 0.8rem;
            margin-bottom: 1.5rem;
        }

        .card-icons i {
            font-size: 2.8rem;
            color: #555;
        }

        .btn-pagar {
            width: 100%;
            padding: 1rem;
            background: #023877;
            color: white;
            border: none;
            border-radius: 0.8rem;
            font-family: 'Poppins', sans-serif;
            font-size: 1.3rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            transition: background 0.2s;
            margin-top: 0.8rem;
        }

        .btn-pagar:hover {
            background: #01255a;
        }

        /* RESUMEN */
        .summary-card {
            background: white;
            border-radius: 1rem;
            padding: 1.8rem;
            box-shadow: 0 2px 12px rgba(0,0,0,0.07);
            position: sticky;
            top: 2rem;
        }

        .summary-card h2 {
            font-size: 1.6rem;
            font-weight: 600;
            color: #023877;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        #orderList {
            list-style: none;
            margin-bottom: 1.5rem;
        }

        #orderList li {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 0;
            border-bottom: 1px solid #f5f5f5;
            font-size: 1.3rem;
        }

        #orderList li img {
            width: 45px;
            height: 45px;
            object-fit: contain;
            border-radius: 0.5rem;
            background: #f5f5f5;
            padding: 3px;
        }

        .item-info {
            flex: 1;
        }

        .item-info p {
            font-weight: 500;
            color: #333;
            font-size: 1.3rem;
        }

        .item-info span {
            color: #888;
            font-size: 1.1rem;
        }

        .item-price {
            font-weight: 600;
            color: #023877;
            font-size: 1.3rem;
        }

        .summary-totals {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 2px solid #f0f0f0;
        }

        .summary-totals p {
            display: flex;
            justify-content: space-between;
            font-size: 1.3rem;
            color: #555;
            margin-bottom: 0.8rem;
        }

        .summary-totals .total-final {
            font-size: 1.6rem;
            font-weight: 700;
            color: #023877;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #e0e0e0;
        }

        .empty-cart {
            text-align: center;
            padding: 2rem;
            color: #888;
            font-size: 1.3rem;
        }

        .empty-cart i {
            font-size: 4rem;
            color: #ccc;
            display: block;
            margin-bottom: 1rem;
        }

        /* MODAL ÉXITO */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal-overlay.show {
            display: flex;
        }

        .modal {
            background: white;
            border-radius: 1.5rem;
            padding: 4rem 3rem;
            text-align: center;
            max-width: 420px;
            width: 90%;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }

        .modal i {
            font-size: 6rem;
            color: #27ae60;
            margin-bottom: 2rem;
        }

        .modal h2 {
            font-size: 2.2rem;
            color: #333;
            margin-bottom: 1rem;
        }

        .modal p {
            font-size: 1.4rem;
            color: #666;
            margin-bottom: 2rem;
        }

        .modal .orden-id {
            background: #f5f5f5;
            border-radius: 0.8rem;
            padding: 1rem;
            font-weight: 700;
            font-size: 1.5rem;
            color: #023877;
            margin-bottom: 2rem;
        }

        .btn-volver {
            padding: 1.2rem 3rem;
            background: #023877;
            color: white;
            border: none;
            border-radius: 0.8rem;
            font-family: 'Poppins', sans-serif;
            font-size: 1.4rem;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-volver:hover {
            background: #01255a;
        }
    </style>
</head>
<body>

<header>
    <i class="fas fa-hospital"></i>
    <h1>UCSP FARMACIA</h1>
</header>

<div class="navbar">
    <p>
        <span><a href="index.php" style="color:#aac4f0; text-decoration:none;">Inicio</a></span>
        &nbsp;›&nbsp;
        <span><a href="farmacia.php" style="color:#aac4f0; text-decoration:none;">Farmacia</a></span>
        &nbsp;›&nbsp;
        <span class="active">Pago</span>
    </p>
</div>

<div class="page-container">

    <!-- COLUMNA IZQUIERDA: FORMULARIOS -->
    <div>
        <!-- Dirección de envío -->
        <div class="card">
            <h2><i class="fas fa-map-marker-alt"></i> Dirección de Envío</h2>
            <div class="form-group">
                <label>Nombre Completo</label>
                <input type="text" id="nombrepe" placeholder="Ej: Juan Pérez García">
            </div>
            <div class="form-group">
                <label>Dirección</label>
                <input type="text" id="address" placeholder="Av. Principal 123">
            </div>
            <div class="form-group">
                <label>Referencia</label>
                <input type="text" id="referencia" placeholder="Frente al parque, edificio azul...">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Ciudad</label>
                    <input type="text" id="city" placeholder="Arequipa">
                </div>
                <div class="form-group">
                    <label>Teléfono</label>
                    <input type="text" id="contacto" placeholder="999 888 777">
                </div>
            </div>
        </div>

        <!-- Pago con tarjeta -->
        <div class="card">
            <h2><i class="fas fa-credit-card"></i> Datos de Pago</h2>
            <div class="card-icons">
                <i class="fab fa-cc-visa"></i>
                <i class="fab fa-cc-mastercard"></i>
                <i class="fab fa-cc-amex"></i>
            </div>
            <div class="form-group">
                <label>Número de Tarjeta</label>
                <input type="text" id="cardNumber" placeholder="1234 5678 9012 3456" maxlength="19">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Fecha de Expiración</label>
                    <input type="text" id="expiryDate" placeholder="MM/AA" maxlength="5">
                </div>
                <div class="form-group">
                    <label>CVV</label>
                    <input type="text" id="cvv" placeholder="123" maxlength="3">
                </div>
            </div>
            <div class="form-group">
                <label>Nombre en la Tarjeta</label>
                <input type="text" id="cardName" placeholder="Como aparece en la tarjeta">
            </div>

            <button class="btn-pagar" onclick="procesarPago()">
                <i class="fas fa-lock"></i> Confirmar y Pagar
            </button>
        </div>
    </div>

    <!-- COLUMNA DERECHA: RESUMEN -->
    <div>
        <div class="summary-card">
            <h2><i class="fas fa-shopping-basket"></i> Resumen del Pedido</h2>
            <ul id="orderList"></ul>
            <div class="summary-totals">
                <p><span>Subtotal</span><span id="subtotal">S/ 0.00</span></p>
                <p><span>Envío</span><span>Gratis</span></p>
                <p class="total-final"><span>Total</span><span id="totalFinal">S/ 0.00</span></p>
            </div>
        </div>
    </div>
</div>

<!-- MODAL ÉXITO -->
<div class="modal-overlay" id="modalExito">
    <div class="modal">
        <i class="fas fa-check-circle"></i>
        <h2>¡Pedido Confirmado!</h2>
        <p>Tu pedido ha sido procesado exitosamente.</p>
        <div class="orden-id" id="ordenId">Orden #000000</div>
        <p>Recibirás tu pedido pronto. Gracias por comprar en UCSP Farmacia.</p>
        <br>
        <button class="btn-volver" onclick="window.location.href='farmacia.php'">
            <i class="fas fa-arrow-left"></i> Volver a la Farmacia
        </button>
    </div>
</div>

<script>
    // Cargar productos del carrito enviados desde farmacia.php
    const params = new URLSearchParams(window.location.search);
    const cartData = params.get('cart');
    let cart = [];

    if (cartData) {
        try {
            cart = JSON.parse(decodeURIComponent(cartData));
        } catch(e) {
            cart = [];
        }
    }

    function renderResumen() {
        const orderList = document.getElementById('orderList');
        orderList.innerHTML = '';
        let total = 0;

        if (cart.length === 0) {
            orderList.innerHTML = '<li class="empty-cart"><i class="fas fa-basket-shopping"></i>No hay productos en el carrito</li>';
        } else {
            cart.forEach(function(product) {
                const li = document.createElement('li');
                li.innerHTML =
                    '<img src="' + product.imageSrc + '" alt="' + product.name + '">' +
                    '<div class="item-info">' +
                        '<p>' + product.name + '</p>' +
                        '<span>Cantidad: ' + product.quantity + '</span>' +
                    '</div>' +
                    '<span class="item-price">S/ ' + (product.price * product.quantity).toFixed(2) + '</span>';
                orderList.appendChild(li);
                total += product.price * product.quantity;
            });
        }

        document.getElementById('subtotal').textContent = 'S/ ' + total.toFixed(2);
        document.getElementById('totalFinal').textContent = 'S/ ' + total.toFixed(2);
    }

    function procesarPago() {
    const nombre   = document.getElementById('nombrepe').value.trim();
    const address  = document.getElementById('address').value.trim();
    const city     = document.getElementById('city').value.trim();
    const contacto = document.getElementById('contacto').value.trim();
    const cardNumber = document.getElementById('cardNumber').value.trim();
    const expiryDate = document.getElementById('expiryDate').value.trim();
    const cvv      = document.getElementById('cvv').value.trim();
    const cardName = document.getElementById('cardName').value.trim();

    if (!nombre || !address || !city || !contacto || !cardNumber || !expiryDate || !cvv || !cardName) {
        alert('Por favor completa todos los campos.');
        return;
    }

    if (cart.length === 0) {
        alert('Tu carrito está vacío.');
        return;
    }

    const total = cart.reduce(function(sum, p) { return sum + p.price * p.quantity; }, 0);

    fetch('../../php/procesar_pago.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            nombre_envio: nombre,
            direccion: address,
            ciudad: city,
            telefono: contacto,
            total: total,
            carrito: cart
        })
    })
    .then(function(res) { return res.json(); })
    .then(function(data) {
        if (data.success) {
            document.getElementById('ordenId').textContent = 'Orden #' + data.id_pedido;
            document.getElementById('modalExito').classList.add('show');
        } else {
            alert('Error: ' + data.mensaje);
        }
    })
    .catch(function() {
        alert('Error de conexión con el servidor.');
    });
}

renderResumen();

// Formatear número de tarjeta con espacios
document.getElementById('cardNumber').addEventListener('input', function() {
    let val = this.value.replace(/\D/g, '').substring(0, 16);
    val = val.replace(/(.{4})/g, '$1 ').trim();
    this.value = val;
});

// Formatear fecha MM/AA
document.getElementById('expiryDate').addEventListener('input', function() {
    let val = this.value.replace(/\D/g, '').substring(0, 4);
    if (val.length >= 2) val = val.substring(0,2) + '/' + val.substring(2);
    this.value = val;
});
</script>

</body>
</html>