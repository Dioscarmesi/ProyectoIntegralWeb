<?php
// /UrbanJ/index.php

require __DIR__ . '/includes/conexion.php';
$pageTitle = 'Inicio';
require __DIR__ . '/includes/header.php';

// Obtener 6 productos por categoría
$cats = ['Gorras','Ropa','Tasas','Camisas'];
$carousels = [];
foreach ($cats as $c) {
    $stmt = $pdo->prepare("
        SELECT p.id, p.nombre, p.precio,
               ip.imagen_url
          FROM productos p
          LEFT JOIN imagenes_producto ip
            ON p.id = ip.producto_id AND ip.orden = 1
         WHERE p.categoria = ?
         LIMIT 6
    ");
    $stmt->execute([$c]);
    $carousels[$c] = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<div class="home-container">

  <?php foreach ($carousels as $cat => $items): ?>
    <section class="home-section container">
      <h2><?= htmlspecialchars($cat) ?></h2>
      <div class="productos-carousel carousel">
        <?php foreach ($items as $p): ?>
          <div class="producto-slide">
            <a href="/UrbanJ/VisualizarProducto.php?id=<?= $p['id'] ?>" class="prod-link">
              <img src="/UrbanJ/<?= htmlspecialchars($p['imagen_url'] ?: 'assets/placeholder.png') ?>" alt="">
              <h4><?= htmlspecialchars($p['nombre']) ?></h4>
              <div class="precio">$<?= number_format($p['precio'],2) ?></div>
            </a>
            <div class="acciones">
              <button class="btn btn--primary btn--small" onclick="comprar(<?= $p['id'] ?>)">
                Comprar
              </button>
              <button class="btn btn--secondary btn--small" onclick="addToCart(<?= $p['id'] ?>)">
                Añadir
              </button>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </section>
  <?php endforeach; ?>

</div>

</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
