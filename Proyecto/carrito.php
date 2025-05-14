<?php
// carrito.php
session_start();
require_once __DIR__ . '/includes/conexion.php';
require_once __DIR__ . '/includes/header.php';  // carga <head>, <body> y el mini‐dropdown funciona desde aquí

// Obtener datos del carrito
$cart = $_SESSION['cart'] ?? [];
if (!$cart) {
    $items = [];
    $totalCost = 0;
} else {
    // Traer datos de productos
    $ids = array_keys($cart);
    $in  = str_repeat('?,', count($ids)-1) . '?';
    $stmt = $pdo->prepare("SELECT id, nombre, precio, stock FROM productos WHERE id IN ($in)");
    $stmt->execute($ids);
    $items = [];
    $totalCost = 0;
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $p) {
        $qty = $cart[$p['id']];
        $cost = $p['precio'] * $qty;
        $items[] = array_merge($p, ['qty'=>$qty, 'cost'=>$cost]);
        $totalCost += $cost;
    }
}
?>
<main class="container carrito-container">
  <h1>Tu Carrito</h1>

  <?php if (empty($items)): ?>
    <p>Tu carrito está vacío.</p>
    <p><a href="index.php" class="btn--primary">Seguir comprando</a></p>
  <?php else: ?>
    <table class="cart-table">
      <thead>
        <tr>
          <th>Producto</th>
          <th>Precio</th>
          <th>Cantidad</th>
          <th>Subtotal</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
      <?php foreach($items as $it): ?>
        <tr data-id="<?= $it['id'] ?>">
          <td><?= htmlspecialchars($it['nombre']) ?></td>
          <td>$<?= number_format($it['precio'],2) ?></td>
          <td>
            <input 
              type="number" 
              class="cart-qty" 
              value="<?= $it['qty'] ?>" 
              min="1" max="<?= $it['stock'] ?>"
            >
          </td>
          <td class="cart-sub">$<?= number_format($it['cost'],2) ?></td>
          <td>
            <button class="btn--icon btn-remove" title="Eliminar">✕</button>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="3" class="text-right"><strong>Total</strong></td>
          <td colspan="2"><strong>$<span id="cart-total"><?= number_format($totalCost,2) ?></span></strong></td>
        </tr>
      </tfoot>
    </table>

    <div class="cart-actions">
      <a href="index.php" class="btn--secondary">Seguir comprando</a>
      <a href="checkout.php" class="btn--primary">Ir a pagar</a>
    </div>
  <?php endif; ?>
</main>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
