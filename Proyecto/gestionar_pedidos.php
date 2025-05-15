<?php
session_start();
require_once __DIR__ . '/includes/conexion.php';

if (empty($_SESSION['admin'])) {
    header('Location: index.php');
    exit;
}

// Obtener todos los pedidos
$stmt = $pdo->query("
    SELECT p.id, u.usuario, p.total, p.estado, p.creado_at
    FROM pedidos p
    JOIN usuarios u ON p.usuario_id = u.id
    ORDER BY p.creado_at DESC
");
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/includes/header.php';
?>

<main class="container">
  <h2 class="section__title">Gestionar Pedidos</h2>

  <table class="productos-table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Usuario</th>
        <th>Total</th>
        <th>Estado</th>
        <th>Fecha</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($pedidos as $p): ?>
        <tr>
          <td><?= $p['id'] ?></td>
          <td><?= htmlspecialchars($p['usuario']) ?></td>
          <td>$<?= number_format($p['total'], 2) ?></td>
          <td><?= $p['estado'] ?></td>
          <td><?= $p['creado_at'] ?></td>
          <td>
            <a href="ver_pedido.php?id=<?= $p['id'] ?>" class="btn-action btn-view">Ver</a>
            <a href="editar_estado.php?id=<?= $p['id'] ?>" class="btn-action btn-edit">Editar estado</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
