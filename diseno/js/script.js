document.addEventListener("DOMContentLoaded", function () {

    const cartIcon = document.querySelector('.content-shopping-cart .number');
    const cartBasket = document.querySelector('.fa-basket-shopping');
    const cartPanel = document.querySelector('.cart-panel');
    const cartPanelItems = document.querySelector('.cart-items');
    const totalQuantityDisplay = document.querySelector('.total-quantity');
    const totalPriceDisplay = document.querySelector('.total-price');
    const closeCartButton = document.querySelector('.close-cart-btn');
    const userIcon = document.querySelector('.fa-user');
    const userPanel = document.querySelector('.user-panel');
    const closeUserButton = document.querySelector('.close-user-btn');
    const checkoutButton = document.querySelector('.checkout-btn');

    let cart = [];

    if (userIcon && userPanel) {
        userIcon.addEventListener('click', function () {
            userPanel.style.display = userPanel.style.display === 'block' ? 'none' : 'block';
        });
    }

    if (closeUserButton && userPanel) {
        closeUserButton.addEventListener('click', function () {
            userPanel.style.display = 'none';
        });
    }

    if (cartBasket && cartPanel) {
        cartBasket.addEventListener('click', function () {
            cartPanel.classList.toggle('open');
        });
    }

    if (closeCartButton && cartPanel) {
        closeCartButton.addEventListener('click', function () {
            cartPanel.classList.remove('open');
        });
    }

    // Botones en tarjetas .product
    document.querySelectorAll('.product .cart').forEach(function (button) {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const productCard = button.closest('.product');
            const productId   = productCard.getAttribute('data-name');
            const productName = productCard.querySelector('h3').textContent.trim();
            const priceText   = productCard.querySelector('.price').textContent.trim();
            const productPrice = parseFloat(priceText.replace(/[^\d.]/g, ''));
            const productImg  = productCard.querySelector('img').src;
            addToCart(productId, productName, productPrice, productImg);
        });
    });

    // Botones en modales .preview
    document.querySelectorAll('.preview .cart').forEach(function (button) {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const productCard = button.closest('.preview');
            const productId   = productCard.getAttribute('data-target');
            const productName = productCard.querySelector('h3').textContent.trim();
            const priceText   = productCard.querySelector('.price').textContent.trim();
            const productPrice = parseFloat(priceText.replace(/[^\d.]/g, ''));
            const productImg  = productCard.querySelector('img').src;
            addToCart(productId, productName, productPrice, productImg);
        });
    });

    function addToCart(id, name, price, imageSrc) {
        const existing = cart.find(function (p) { return p.id === id; });
        if (existing) {
            existing.quantity++;
        } else {
            cart.push({ id: id, name: name, price: price, imageSrc: imageSrc, quantity: 1 });
        }
        updateCart();
    }

    function updateCart() {
        cartPanelItems.innerHTML = '';
        let totalQuantity = 0;
        let totalPrice = 0;

        cart.forEach(function (product) {
            const item = document.createElement('div');
            item.classList.add('cart-item');
            item.innerHTML =
                '<img src="' + product.imageSrc + '" alt="' + product.name + '" class="cart-item-image">' +
                '<div class="cart-item-info">' +
                    '<p class="cart-item-name">' + product.name + '</p>' +
                    '<p class="cart-item-price">S/ ' + product.price.toFixed(2) + '</p>' +
                '</div>' +
                '<p class="cart-item-quantity">x' + product.quantity + '</p>' +
                '<button class="remove-item-btn">&times;</button>';

            item.querySelector('.remove-item-btn').addEventListener('click', function () {
                cart = cart.filter(function (p) { return p.id !== product.id; });
                updateCart();
            });

            cartPanelItems.appendChild(item);
            totalQuantity += product.quantity;
            totalPrice += product.price * product.quantity;
        });

        totalQuantityDisplay.textContent = totalQuantity;
        totalPriceDisplay.textContent = totalPrice.toFixed(2);
        cartIcon.textContent = '(' + totalQuantity + ')';
    }

    if (checkoutButton) {
    checkoutButton.addEventListener('click', function () {
        if (cart.length === 0) {
            alert('Tu carrito está vacío.');
            return;
        }
        const cartJSON = encodeURIComponent(JSON.stringify(cart));
        window.location.href = '../pages/pago.php?cart=' + cartJSON;
    });
    }
});
