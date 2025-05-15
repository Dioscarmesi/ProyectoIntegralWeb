<?php
session_start();
require __DIR__ . '/includes/conexion.php';

if (empty($_SESSION['admin'])) {
    header('Location: index.php');
    exit;
}

$stmt = $pdo->query("SELECT * FROM productos");
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmtImgs = $pdo->query('SELECT producto_id, imagen_url FROM imagenes_producto ORDER BY orden');
$allImgs = [];
while ($r = $stmtImgs->fetch(PDO::FETCH_ASSOC)) {
    $allImgs[$r['producto_id']][] = $r['imagen_url'];
}

$pageTitle = 'Inventario';
require __DIR__ . '/includes/header.php';
?>

<link rel="stylesheet" href="/UrbanJ/css/TablaProductos.css">

<main class="container productos-table-container">
  <h2 class="section__title">Inventario de Productos</h2>
  <a href="CrearProducto.php" class="nuevo-btn">Crear Nuevo Producto</a>

  <table class="productos-table">
    <thead>
      <tr>
        <th>Nombre</th><th>Precio</th><th>Categoría</th>
        <th>Stock</th><th>Creado</th><th>Actualizado</th><th>Acciones</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($productos as $p): ?>
      <tr>
        <td><?= htmlspecialchars($p['nombre']) ?></td>
        <td>$<?= number_format($p['precio'], 2) ?></td>
        <td><?= htmlspecialchars($p['categoria']) ?></td>
        <td><?= (int)$p['stock'] ?></td>
        <td><?= htmlspecialchars($p['creado_at']) ?></td>
        <td><?= htmlspecialchars($p['actualizado_at']) ?></td>
        <td style="white-space: nowrap;">
          <!-- Botón Editar -->
          <a href="EditarProducto.php?id=<?= $p['id'] ?>" class="btn-action btn-edit">Editar</a>

          <!-- Botón Borrar -->
          <a href="borrar_producto.php?id=<?= $p['id'] ?>"
             onclick="return confirm('¿Estás seguro de que quieres borrar este producto?')"
             class="btn-action btn-delete">Borrar</a>

          <!-- Botón Ver -->
          <button
            type="button"
            class="btn-action btn-view"
            data-nombre="<?= htmlspecialchars($p['nombre'], ENT_QUOTES) ?>"
            data-descripcion="<?= htmlspecialchars($p['descripcion'], ENT_QUOTES) ?>"
            data-precio="<?= number_format($p['precio'], 2) ?>"
            data-categoria="<?= htmlspecialchars($p['categoria'], ENT_QUOTES) ?>"
            data-stock="<?= (int)$p['stock'] ?>"
            data-creado="<?= htmlspecialchars($p['creado_at'], ENT_QUOTES) ?>"
            data-actualizado="<?= htmlspecialchars($p['actualizado_at'], ENT_QUOTES) ?>"
            data-imagenes='<?= htmlspecialchars(json_encode($allImgs[$p['id']] ?? []), ENT_QUOTES) ?>'
          >Ver</button>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</main>

<!-- Modal para ver producto -->
<div class="modal-overlay">
  <div class="modal">
    <button id="modal-close" class="modal-close">&times;</button>
    <h3 id="modal-nombre"></h3>
    <div id="modal-images"></div>
    <p id="modal-descripcion"></p>
    <ul>
      <li><strong>Precio:</strong> $<span id="modal-precio"></span></li>
      <li><strong>Categoría:</strong> <span id="modal-categoria"></span></li>
      <li><strong>Stock:</strong> <span id="modal-stock"></span></li>
      <li><strong>Creado:</strong> <span id="modal-creado"></span></li>
      <li><strong>Actualizado:</strong> <span id="modal-actualizado"></span></li>
    </ul>
  </div>
</div>

<script defer src="/UrbanJ/js/TablaProductos.js"></script>
<?php require __DIR__ . '/includes/footer.php'; ?>
