<?php
// /UrbanJ/checkout.php

session_start();
require_once __DIR__ . '/includes/conexion.php';   // $pdo
require_once __DIR__ . '/includes/header.php';     // abre <html><head>… y <body>

// 1) Cargar direcciones del usuario
$usuarioId = $_SESSION['user_id'] ?? null;
$direcciones = [];
if ($usuarioId) {
    $stmt = $pdo->prepare("SELECT * FROM direcciones WHERE usuario_id = ? ORDER BY id DESC");
    $stmt->execute([$usuarioId]);
    $direcciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// 2) Cargar carrito de sesión
$cart = $_SESSION['cart'] ?? [];
$cartItems = [];
$totalCost = 0.0;
if ($cart) {
    $ids = array_keys($cart);
    $in  = str_repeat('?,', count($ids)-1).'?' ;
    $stmt = $pdo->prepare("SELECT id, nombre, precio FROM productos WHERE id IN ($in)");
    $stmt->execute($ids);
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $p) {
        $qty  = $cart[$p['id']];
        $cost = $p['precio'] * $qty;
        $cartItems[] = [
            'id'     => $p['id'],
            'nombre' => $p['nombre'],
            'qty'    => $qty,
            'cost'   => $cost
        ];
        $totalCost += $cost;
    }
}

// 3) Procesar envío de nueva dirección
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_address') {
    $alias = trim($_POST['alias']);
    $calle = trim($_POST['calle']);
    $ciudad = trim($_POST['ciudad']);
    $estado = trim($_POST['estado']);
    $pais = trim($_POST['pais']);
    $cp   = trim($_POST['codigo_postal']);

    if ($usuarioId && $alias && $calle && $ciudad && $estado && $pais && $cp) {
        $ins = $pdo->prepare("
            INSERT INTO direcciones
              (usuario_id, alias, calle, ciudad, estado, pais, codigo_postal)
            VALUES
              (:uid, :alias, :calle, :ciudad, :estado, :pais, :cp)
        ");
        $ins->execute([
            'uid'   => $usuarioId,
            'alias' => $alias,
            'calle' => $calle,
            'ciudad'=> $ciudad,
            'estado'=> $estado,
            'pais'  => $pais,
            'cp'    => $cp
        ]);
        // recargar para actualizar lista
        header('Location: checkout.php');
        exit;
    } else {
        $error = 'Todos los campos de dirección son obligatorios.';
    }
}
?>

<main class="checkout-container">
  <h1>Finalizar compra</h1>

  <!-- Paso 1: Dirección de envío -->
  <div class="step">
    <h2>1. Dirección de envío</h2>

    <?php if (!empty($error)): ?>
      <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <?php if (empty($direcciones)): ?>
      <p>No tienes direcciones guardadas.</p>
    <?php endif; ?>

    <button id="btn-new-address" class="btn-add-address">+ Agregar dirección</button>

    <ul class="address-list" id="address-list">
      <?php foreach ($direcciones as $d): ?>
        <li class="address-item" data-id="<?= $d['id'] ?>">
          <span>
            <strong><?= htmlspecialchars($d['alias']) ?></strong> — 
            <?= htmlspecialchars($d['calle']) ?>, 
            <?= htmlspecialchars($d['ciudad']) ?> (<?= htmlspecialchars($d['estado']) ?>), 
            <?= htmlspecialchars($d['pais']) ?>, C.P. <?= htmlspecialchars($d['codigo_postal']) ?>
          </span>
          <!-- Futuras acciones: editar/borrar -->
        </li>
      <?php endforeach; ?>
    </ul>

    <form method="post" id="address-form" class="address-form" style="display:none;">
      <input type="hidden" name="action" value="save_address">
      <label for="alias">Alias</label>
      <input type="text" name="alias" id="alias" required>
      <label for="calle">Calle</label>
      <input type="text" name="calle" id="calle" required>
      <label for="ciudad">Ciudad</label>
      <input type="text" name="ciudad" id="ciudad" required>
      <label for="estado">Estado/Provincia</label>
      <input type="text" name="estado" id="estado" required>
      <label for="pais">País</label>
      <input type="text" name="pais" id="pais" required>
      <label for="codigo_postal">Código Postal</label>
      <input type="text" name="codigo_postal" id="codigo_postal" required>
      <button type="submit" class="btn-add-address">Guardar dirección</button>
    </form>
  </div>

  <!-- Paso 2: Resumen de compra -->
  <div class="step">
    <h2>2. Resumen de compra</h2>
    <ul class="summary-list">
      <?php foreach ($cartItems as $it): ?>
        <li>
          <?= htmlspecialchars($it['nombre']) ?> x<?= $it['qty'] ?> → $<?= number_format($it['cost'],2) ?>
        </li>
      <?php endforeach; ?>
    </ul>
    <div class="checkout-total">Total: $<?= number_format($totalCost,2) ?></div>
  </div>

  <!-- Paso 3: Pagar --> 
  <div class="step">
    <h2>3. Pagar</h2>
    <button onclick="openPaymentPopup()" class="btn-pago">Ir a pasarela de pago</button>
  </div>
</form>
  </div>
</main>
<script>
function openPaymentPopup() {
    <?php if(empty($cartItems)): ?>
        Swal.fire({
            icon: 'error',
            title: 'Carrito vacío',
            text: 'No hay productos en tu carrito'
        });
        return;
    <?php endif; ?>

    // Verificar que hay dirección seleccionada (opcional)
    // const selectedAddress = document.querySelector('.address-item.selected');
    // if (!selectedAddress) {
    //     Swal.fire({
    //         icon: 'error',
    //         title: 'Dirección requerida',
    //         text: 'Por favor selecciona una dirección de envío'
    //     });
    //     return;
    // }

    const totalAmount = <?= $totalCost ?>;
    const popupUrl = `/UrbanJ/Pasarela-urbanj.html?amount=${totalAmount}`;

    // Solución alternativa si el popup está bloqueado
    const popupWindow = window.open(popupUrl, 'PasarelaUrbanJ', 
        'width=800,height=600,scrollbars=yes,resizable=yes');

    if (!popupWindow || popupWindow.closed || typeof popupWindow.closed === 'undefined') {
        // Popup bloqueado - ofrecer alternativa
        Swal.fire({
            title: 'Redirección a pasarela de pago',
            text: 'El navegador bloqueó la ventana emergente. ¿Deseas ser redirigido ahora?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, redirigir',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Abrir en la misma ventana
                window.location.href = popupUrl;
            }
        });
        return;
    }

    // Manejar mensajes del popup
    window.addEventListener('message', function(event) {
        if (event.origin !== window.location.origin) return;
        
        if (event.data.paymentStatus === 'completed') {
            handleSuccessfulPayment(event.data);
        } else if (event.data.paymentStatus === 'cancelled') {
            Swal.fire('Pago cancelado', 'El pago fue cancelado', 'info');
        } else if (event.data.paymentStatus === 'error') {
            Swal.fire('Error en el pago', 'Ocurrió un error al procesar el pago', 'error');
        }
    });

    // Función para manejar pago exitoso
    async function handleSuccessfulPayment(paymentData) {
        try {
            const response = await fetch('procesar_pedido.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    transaction_id: paymentData.transactionId,
                    amount: paymentData.amount,
                    // direccion_id: selectedAddress ? selectedAddress.dataset.id : null
                })
            });

            const result = await response.json();

            if (result.success) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Pedido completado!',
                    html: `Número de pedido: <b>${result.pedido_id}</b><br>
                           Transacción ID: <b>${paymentData.transactionId}</b>`,
                    confirmButtonText: 'Ver mi pedido'
                }).then(() => {
                    window.location.href = 'mis_pedidos.php';
                });
            } else {
                throw new Error(result.message || 'Error al registrar el pedido');
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Error al procesar pedido',
                text: error.message
            });
        }
    }
}
</script>

<?php
require_once __DIR__ . '/includes/footer.php';
