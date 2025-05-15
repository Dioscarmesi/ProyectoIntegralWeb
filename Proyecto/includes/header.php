<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../conexion.php';

$is_admin = false;
$cart = $_SESSION['cart'] ?? [];
$totalQty = array_sum($cart);
$user = null;

if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT id, usuario, admin FROM usuarios WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $is_admin = $user && $user['admin'] == 1;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>UrbanJ</title>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php include_once __DIR__ . '/stylos.php'; ?>
</head>
<body>
<header>
    <nav class="nav-bar">
        <!-- Marca -->
        <div class="nav-brand">UrbanJ</div>

        <!-- Enlaces de navegación -->
        <ul class="nav-links">
            <?php if ($is_admin): ?>
                <li><a href="/UrbanJ/gestionar_pedidos.php">Gestionar Pedidos</a></li>
                <li><a href="/UrbanJ/inventario.php">Inventarios</a></li>
                <li><a href="/UrbanJ/gestionar_usuarios.php">Gestionar Usuarios</a></li>
                <li><a href="/UrbanJ/moderar_resenas.php">Moderación de Reseñas</a></li>
                <li><a href="/UrbanJ/admin_banners.php">Banners</a></li>
            <?php else: ?>
                <li><a href="/UrbanJ/index.php">Inicio</a></li>
                <li><a href="/UrbanJ/tienda.php">Tienda</a></li>
                <li><a href="/UrbanJ/mi_cuenta.php">Mi Cuenta</a></li>
                <li><a href="/UrbanJ/acerca_de_nosotros.php">Acerca de Nosotros</a></li>
            <?php endif; ?>
        </ul>

        <!-- Iconos a la derecha -->
        <div style="display: flex; align-items: center; gap: 1rem;">
            <!-- Carrito -->
            <div class="cart-icon" onclick="toggleCartDropdown()">
                🛒
                <?php if ($totalQty > 0): ?>
                    <span class="cart-count"><?= $totalQty ?></span>
                <?php endif; ?>
            </div>

            <!-- Mi cuenta o Login/Registro -->
            <?php if (isset($_SESSION['user_id']) && $user): ?>
                <div class="account-menu">
                    <button onclick="toggleAccountMenu()">Mi cuenta ⌄</button>
                    <div id="account-dropdown" class="account-dropdown">
                        <p><strong><?= htmlspecialchars($user['usuario']) ?></strong></p>
                        <p>Rol: <?= $is_admin ? 'Administrador' : 'Cliente' ?></p>
                        <a href="/UrbanJ/logout.php" class="btn-logout">Cerrar sesión</a>
                    </div>
                </div>
            <?php else: ?>
                <div class="account-menu">
                    <a href="/UrbanJ/login.php" class="btn btn--small">Iniciar sesión</a>
                    <a href="/UrbanJ/RegistrarUsuario.php" class="btn btn--secondary btn--small">Registrarse</a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Dropdown del carrito -->
        <div id="cart-dropdown" class="cart-dropdown">
            <h4 style="margin-top: 0">Tu carrito</h4>
            <ul class="cart-items">
                <?php
                $totalCost = 0;
                if (!empty($cart)) {
                    $ids = array_keys($cart);
                    $placeholders = implode(',', array_fill(0, count($ids), '?'));
                    $stmt = $pdo->prepare("SELECT id, nombre, precio FROM productos WHERE id IN ($placeholders)");
                    $stmt->execute($ids);

                    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $p) {
                        $qty = $cart[$p['id']];
                        $cost = $p['precio'] * $qty;
                        $totalCost += $cost;
                        echo '<li class="ci-item">';
                        echo '<div class="ci-left">';
                        echo '<img class="ci-thumb" src="/UrbanJ/assets/placeholder.png" alt="">';
                        echo '<div>';
                        echo '<div class="ci-name">' . htmlspecialchars($p['nombre']) . '</div>';
                        echo '<div class="ci-qty">Cantidad: ' . $qty . '</div>';
                        echo '</div>';
                        echo '</div>';
                        echo '<div class="ci-right">';
                        echo '<div class="ci-cost">$' . number_format($cost, 2) . '</div>';
                        echo '</div>';
                        echo '</li>';
                    }
                } else {
                    echo '<p>No hay productos en el carrito.</p>';
                }
                ?>
            </ul>

            <?php if ($totalQty > 0): ?>
                <div class="cart-total">Total: $<?= number_format($totalCost, 2) ?></div>
                <a href="/UrbanJ/carrito.php" class="btn btn--checkout">Ver carrito</a>
            <?php endif; ?>
        </div>
    </nav>
</header>

<script>
function toggleCartDropdown() {
    const dropdown = document.getElementById('cart-dropdown');
    dropdown.classList.toggle('open');
}

function toggleAccountMenu() {
    const menu = document.getElementById('account-dropdown');
    menu.classList.toggle('show');
}

document.addEventListener('click', function(event) {
    const cartIcon = document.querySelector('.cart-icon');
    const cartDropdown = document.getElementById('cart-dropdown');
    if (!cartDropdown.contains(event.target) && !cartIcon.contains(event.target)) {
        cartDropdown.classList.remove('open');
    }

    const accountBtn = document.querySelector('.account-menu button');
    const accountDropdown = document.getElementById('account-dropdown');
    if (accountDropdown && !accountDropdown.contains(event.target) && !accountBtn.contains(event.target)) {
        accountDropdown.classList.remove('show');
    }
});
</script>
