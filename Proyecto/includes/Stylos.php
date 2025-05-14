<?php
// /UrbanJ/includes/Stylos.php

// Determinar la página actual
$current = basename($_SERVER['PHP_SELF']); // e.g. "index.php", "carrito.php", "checkout.php", etc.

// 1) Estilos globales
echo '<link rel="stylesheet" href="/UrbanJ/css/styles.css">' . "\n";

// 2) Mini-carrito (dropdown)
echo '<link rel="stylesheet" href="/UrbanJ/css/cart-dropdown.css">' . "\n";

// 3) Estilos por página
switch ($current) {
    case 'index.php':
        echo '<link rel="stylesheet" href="/UrbanJ/css/Home.css">' . "\n";
        break;

    case 'VisualizarProducto.php':
        echo '<link rel="stylesheet" href="/UrbanJ/css/VisualizarProducto.css">' . "\n";
        break;

    case 'inventario.php':
        echo '<link rel="stylesheet" href="/UrbanJ/css/TablaProductos.css">' . "\n";
        break;

    case 'carrito.php':
        echo '<link rel="stylesheet" href="/UrbanJ/css/Cart.css">' . "\n";
        break;

    case 'checkout.php':
        echo '<link rel="stylesheet" href="/UrbanJ/css/Checkout.css">' . "\n";
        break;

    case 'login.php':
    case 'RegistrarUsuario.php':
        echo '<link rel="stylesheet" href="/UrbanJ/css/LoginRegister.css">' . "\n";
        break;

    case 'CrearProducto.php':
        echo '<link rel="stylesheet" href="/UrbanJ/css/CrearProducto.css">' . "\n";
        break;

    case 'EditarProducto.php':
        echo '<link rel="stylesheet" href="/UrbanJ/css/EditarProducto.css">' . "\n";
        break;
}

// 4) Scripts globales
echo '<script defer src="/UrbanJ/js/script.js"></script>' . "\n";
echo '<script defer src="/UrbanJ/js/cart.js"></script>' . "\n";

// 5) Scripts por página
switch ($current) {
    case 'index.php':
        echo '<script defer src="/UrbanJ/js/Home.js"></script>' . "\n";
        break;

    case 'inventario.php':
        echo '<script defer src="/UrbanJ/js/TablaProductos.js"></script>' . "\n";
        break;

    case 'CrearProducto.php':
        echo '<script defer src="/UrbanJ/js/CrearProducto.js"></script>' . "\n";
        break;

    case 'EditarProducto.php':
        echo '<script defer src="/UrbanJ/js/EditarProducto.js"></script>' . "\n";
        break;

    case 'checkout.php':
        echo '<script defer src="/UrbanJ/js/checkout.js"></script>' . "\n";
        break;

    case 'VisualizarProducto.php':
        echo '<script defer src="/UrbanJ/js/VisualizarProducto.js"></script>' . "\n";
        break;

    case 'login.php':
    case 'RegistrarUsuario.php':
        echo '<script defer src="/UrbanJ/js/LoginRegister.js"></script>' . "\n";
        break;
}
