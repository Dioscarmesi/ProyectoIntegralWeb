<?php
// /UrbanJ/includes/header.php

// Iniciar o reanudar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Conexión a la base de datos
require __DIR__ . '/conexion.php';

/**
 * Obtiene el carrito desde la sesión y devuelve
 * los ítems, la cantidad total y el costo total.
 */
function getCartData(PDO $pdo): array {
    $cart = $_SESSION['cart'] ?? [];
    $items = [];
    $totalQty = 0;
    $totalCost = 0.0;

    if ($cart) {
        $ids = array_keys($cart);
        $in  = str_repeat('?,', count($ids) - 1) . '?';
        $stmt = $pdo->prepare("SELECT id, nombre, precio FROM productos WHERE id IN ($in)");
        $stmt->execute($ids);

        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $p) {
            $qty = $cart[$p['id']] ?? 0;
            if ($qty > 0) {
                $cost = $p['precio'] * $qty;
                $items[] = [
                    'id'     => $p['id'],
                    'nombre' => $p['nombre'],
                    'qty'    => $qty,
                    'cost'   => $cost
                ];
                $totalQty  += $qty;
                $totalCost += $cost;
            }
        }
    }

    return ['items' => $items, 'totalQty' => $totalQty, 'totalCost' => $totalCost];
}

$cartData = getCartData($pdo);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= htmlspecialchars($pageTitle ?? 'UrbanJ') ?></title>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;700&family=Roboto:wght@300;400&display=swap" rel="stylesheet">

  <!-- Estilos globales y de Home -->
  <link rel="stylesheet" href="/UrbanJ/css/styles.css">
  <link rel="stylesheet" href="/UrbanJ/css/Home.css">

  <!-- Scripts globales -->
  <script defer src="/UrbanJ/js/script.js"></script>
  <script defer src="/UrbanJ/js/Home.js"></script>
  <script defer src="/UrbanJ/js/cart.js"></script>
</head>
<body>
  <header class="header">
    <div class="banner container"><h1>UrbanJ</h1></div>
    <nav class="nav container">
      <a href="/UrbanJ/index.php" class="nav__logo">UrbanJ</a>
      <ul class="nav__links">
        <li><a href="/UrbanJ/gorras.php">Gorras</a></li>
        <li><a href="/UrbanJ/ropa.php">Ropa</a></li>
        <li><a href="/UrbanJ/novedades.php">Novedades</a></li>
        <li><a href="/UrbanJ/acerca_de_nosotros.php">Acerca de Nosotros</a></li>
        <?php if (!empty($_SESSION['admin'])): ?>
          <li><a href="/UrbanJ/inventario.php">Inventario</a></li>
        <?php endif; ?>
      </ul>
      <div class="nav__actions">
        <div class="search-container">
          <input type="text" id="search-box" placeholder="Buscar productos…">
          <button onclick="buscarProducto()">Buscar</button>
        </div>
        <div class="nav__user">
          <button class="nav__icon btn--icon">👤</button>
          <div class="nav__dropdown">
            <?php if (!empty($_SESSION['user_id'])): ?>
              <a href="/UrbanJ/SesionIniciada.php">Mi cuenta</a>
              <a href="/UrbanJ/logout.php">Cerrar sesión</a>
            <?php else: ?>
              <a href="/UrbanJ/login.php">Iniciar sesión</a>
              <a href="/UrbanJ/RegistrarUsuario.php">Registrarse</a>
            <?php endif; ?>
          </div>
        </div>
        <div class="nav__cart">
          <button id="cart-btn" class="nav__icon btn--icon" aria-label="Carrito">
            🛒<span class="nav__badge"><?= $cartData['totalQty'] ?></span>
          </button>
          <div id="cart-dropdown" class="cart-dropdown">
            <?php if (empty($cartData['items'])): ?>
              <p class="empty">Tu carrito está vacío.</p>
            <?php else: ?>
              <ul class="cart-items">
                <?php foreach ($cartData['items'] as $it): ?>
                  <li>
                    <span class="ci-name"><?= htmlspecialchars($it['nombre']) ?></span>
                    <span class="ci-qty">x<?= $it['qty'] ?></span>
                    <span class="ci-cost">$<?= number_format($it['cost'],2) ?></span>
                  </li>
                <?php endforeach; ?>
              </ul>
              <div class="cart-total">Total: $<?= number_format($cartData['totalCost'],2) ?></div>
              <a href="/UrbanJ/carrito.php" class="btn btn--primary btn--small">Ver Carrito</a>
            <?php endif; ?>
          </div>
        </div>
        <button class="nav__toggle btn--icon">☰</button>
      </div>
    </nav>
    <div class="nav__mobile-menu">
      <ul>
        <li><a href="/UrbanJ/gorras.php">Gorras</a></li>
        <li><a href="/UrbanJ/ropa.php">Ropa</a></li>
        <li><a href="/UrbanJ/novedades.php">Novedades</a></li>
        <li><a href="/UrbanJ/acerca_de_nosotros.php">Acerca de Nosotros</a></li>
        <?php if (!empty($_SESSION['admin'])): ?>
          <li><a href="/UrbanJ/inventario.php">Inventario</a></li>
        <?php endif; ?>
        <?php if (!empty($_SESSION['user_id'])): ?>
          <li><a href="/UrbanJ/SesionIniciada.php">Mi cuenta</a></li>
          <li><a href="/UrbanJ/logout.php">Cerrar sesión</a></li>
        <?php else: ?>
          <li><a href="/UrbanJ/login.php">Iniciar sesión</a></li>
          <li><a href="/UrbanJ/RegistrarUsuario.php">Registrarse</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </header>
  <main>
