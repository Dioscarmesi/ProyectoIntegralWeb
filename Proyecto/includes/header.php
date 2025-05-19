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
    <style>
        .cart-dropdown {
            position: absolute;
            right: 0;
            top: 100%;
            width: 350px;
            background: black;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            padding: 1rem;
            display: none;
            z-index: 1000;
            max-height: 70vh;
            overflow-y: auto;
        }
        
        .cart-dropdown.open {
            display: block;
        }
        
        .cart-icon {
            position: relative;
            cursor: pointer;
            font-size: 1.5rem;
            padding: 0.5rem;
        }
        
        .cart-count {
            background: #ff4757;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 12px;
            position: absolute;
            top: -5px;
            right: -5px;
        }
        
        .ci-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #eee;
            position: relative;
        }
        
        .btn-remove {
            position: absolute;
            top: 0;
            right: 0;
            background: #ff4757;
            color: white;
            border: none;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            cursor: pointer;
        }
    </style>
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
                        echo '<button class="btn-remove" onclick="removeFromCart('.$p['id'].')">×</button>';
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
// Funciones mejoradas para el carrito
function toggleCartDropdown() {
    const dropdown = document.getElementById('cart-dropdown');
    dropdown.classList.toggle('open');
}

async function addToCart(productId, quantity = 1) {
    try {
        const response = await fetch('/UrbanJ/api/cart_add.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({id: productId, qty: quantity})
        });
        const result = await response.json();
        
        updateCartCount(result.totalQty);
        Swal.fire('¡Producto añadido!', '', 'success');
    } catch (error) {
        console.error('Error:', error);
    }
}

async function removeFromCart(productId) {
    try {
        const response = await fetch('/UrbanJ/api/cart_remove.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({id: productId})
        });
        const result = await response.json();
        
        updateCartCount(result.totalQty);
        // Recargar el dropdown del carrito
        location.reload();
    } catch (error) {
        console.error('Error:', error);
    }
}

function updateCartCount(count) {
    const cartCount = document.querySelector('.cart-count');
    if (count > 0) {
        if (!cartCount) {
            const cartIcon = document.querySelector('.cart-icon');
            const countSpan = document.createElement('span');
            countSpan.className = 'cart-count';
            countSpan.textContent = count;
            cartIcon.appendChild(countSpan);
        } else {
            cartCount.textContent = count;
        }
    } else if (cartCount) {
        cartCount.remove();
    }
}

// Cerrar dropdowns al hacer clic fuera
document.addEventListener('click', function(event) {
    const cartIcon = document.querySelector('.cart-icon');
    const cartDropdown = document.getElementById('cart-dropdown');
    if (!cartDropdown.contains(event.target) && !cartIcon.contains(event.target)) {
        cartDropdown.classList.remove('open');
    }
});
function toggleAccountMenu() {
    const menu = document.getElementById('account-dropdown');
    menu.classList.toggle('show');
}
</script>