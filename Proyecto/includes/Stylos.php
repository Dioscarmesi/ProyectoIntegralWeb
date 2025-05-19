<?php
// /UrbanJ/includes/stylos.php

$current = basename($_SERVER['PHP_SELF']);

// Estilos globales
echo '<link rel="stylesheet" href="/UrbanJ/css/styles.css">' . "\n";
echo '<link rel="stylesheet" href="/UrbanJ/css/nav.css">' . "\n";
echo '<link rel="stylesheet" href="/UrbanJ/css/cart-dropdown.css">' . "\n";

// Estilos por página
switch ($current) {
    case 'gestionar_pedidos.php':
    case 'ver_pedido.php':
    case 'editar_estado.php':
        echo '<link rel="stylesheet" href="/UrbanJ/css/consultaPedidos.css">' . "\n";
        break;

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
        echo '<link rel="stylesheet" href="/UrbanJ/css/checkout.css">' . "\n";
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

    // ✅ NUEVA ENTRADA PARA gestionar_usuarios.php
    case 'gestionar_usuarios.php':
        echo '<link rel="stylesheet" href="/UrbanJ/css/gestionarUsuarios.css">' . "\n";
        break;
    case 'moderar_resenas.php':
    echo '<link rel="stylesheet" href="/UrbanJ/css/moderarResenas.css">' . "\n";
    break;
}

// Scripts globales
echo '<script defer src="/UrbanJ/js/script.js"></script>' . "\n";
echo '<script defer src="/UrbanJ/js/cart.js"></script>' . "\n";

// Scripts por página
switch ($current) {
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
        case 'tienda.php':
    echo '<link rel="stylesheet" href="/UrbanJ/css/Tienda.css">' . "\n";
    break;

}
?>
