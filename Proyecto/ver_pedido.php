<?php
session_start();
require_once __DIR__ . '/includes/conexion.php';

if (empty($_SESSION['admin'])) {
    header('Location: index.php');
    exit;
}

if (!isset($_GET['id'])) {
    echo "ID de pedido no proporcionado.";
    exit;
}

$id = intval($_GET['id']);

// Obtener pedido y datos del usuario
$stmt = $pdo->prepare("
    SELECT p.id, p.total, p.estado, p.creado_at, p.paqueteria, p.guia_seguimiento,
           u.usuario, u.nombre, u.apellido_paterno, u.apellido_materno,
           u.estado AS estado_u, u.ciudad, u.direccion1, u.direccion2, u.codigo_postal
    FROM pedidos p
    JOIN usuarios u ON p.usuario_id = u.id
    WHERE p.id = ?
");
$stmt->execute([$id]);
$pedido = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pedido) {
    echo "Pedido no encontrado.";
    exit;
}

// Obtener productos del pedido
$stmt = $pdo->prepare("
    SELECT d.cantidad, d.precio_unitario, pr.nombre
    FROM pedido_detalle d
    JOIN productos pr ON d.producto_id = pr.id
    WHERE d.pedido_id = ?
");
$stmt->execute([$id]);
$detalles = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/includes/header.php';
?>

<main class="container">
  <h2 class="section__title">Detalle del Pedido #<?= $pedido['id'] ?></h2>

  <p><strong>Cliente:</strong>
    <?= htmlspecialchars($pedido['nombre'] . ' ' . $pedido['apellido_paterno'] . ' ' . $pedido['apellido_materno']) ?>
    (<?= htmlspecialchars($pedido['usuario']) ?>)
  </p>

  <p><strong>Estado:</strong> <?= $pedido['estado'] ?></p>
  <p><strong>Fecha:</strong> <?= $pedido['creado_at'] ?></p>

  <p><strong>Dirección:</strong><br>
    <?= htmlspecialchars($pedido['direccion1']) ?> <?= htmlspecialchars($pedido['direccion2']) ?><br>
    <?= htmlspecialchars($pedido['ciudad']) ?>, <?= htmlspecialchars($pedido['estado_u']) ?><br>
    CP <?= htmlspecialchars($pedido['codigo_postal']) ?>
  </p>

  <p><strong>Paquetería:</strong> <?= $pedido['paqueteria'] ?: 'No asignada' ?></p>
  <p><strong>Número de seguimiento:</strong> <?= $pedido['guia_seguimiento'] ?: 'No asignado' ?></p>

  <table class="productos-table">
    <thead>
      <tr>
        <th>Producto</th>
        <th>Precio Unitario</th>
        <th>Cantidad</th>
        <th>Total</th>
      </tr>
    </thead>
    <tbody>
      <?php $suma = 0; foreach ($detalles as $d):
        $subtotal = $d['cantidad'] * $d['precio_unitario'];
        $suma += $subtotal;
      ?>
        <tr>
          <td><?= htmlspecialchars($d['nombre']) ?></td>
          <td>$<?= number_format($d['precio_unitario'], 2) ?></td>
          <td><?= $d['cantidad'] ?></td>
          <td>$<?= number_format($subtotal, 2) ?></td>
        </tr>
      <?php endforeach; ?>
      <tr>
        <td colspan="3"><strong>Total del pedido:</strong></td>
        <td><strong>$<?= number_format($suma, 2) ?></strong></td>
      </tr>
    </tbody>
  </table>

  <a href="gestionar_pedidos.php" class="btn btn--secondary">Volver</a>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
