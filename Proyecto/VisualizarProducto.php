<?php
// /UrbanJ/VisualizarProducto.php

require __DIR__ . '/includes/conexion.php';
$pageTitle = 'Producto';
require __DIR__ . '/includes/header.php';

// Validar ID
if (empty($_GET['id']) || !ctype_digit($_GET['id'])) {
    header('Location: /UrbanJ/index.php');
    exit;
}
$id = (int)$_GET['id'];

// 1) Producto
$stmt = $pdo->prepare("SELECT * FROM productos WHERE id = ?");
$stmt->execute([$id]);
$prod = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$prod) {
    echo "<p>Producto no encontrado.</p>";
    require __DIR__ . '/includes/footer.php';
    exit;
}
$pageTitle = $prod['nombre'];

// 2) Imágenes
$imgs = $pdo->prepare("
    SELECT imagen_url, orden
      FROM imagenes_producto
     WHERE producto_id = ?
     ORDER BY orden
");
$imgs->execute([$id]);
$imagenes = $imgs->fetchAll(PDO::FETCH_ASSOC);

// 3) Reseñas
$rev = $pdo->prepare("
    SELECT r.calificacion,
           r.comentario,
           r.fecha_resena AS fecha,
           u.usuario
      FROM resenas r
      JOIN usuarios u ON u.id = r.usuario_id
     WHERE r.producto_id = ?
       AND r.estado = 1
     ORDER BY r.fecha_resena DESC
");
$rev->execute([$id]);
$reseñas = $rev->fetchAll(PDO::FETCH_ASSOC);
?>

<link rel="stylesheet" href="/UrbanJ/css/VisualizarProducto.css">

<main class="vp-container">
  <section class="vp-producto">
    <div class="vp-imagenes">
      <img id="vp-main-img"
           src="/UrbanJ/<?= htmlspecialchars($imagenes[0]['imagen_url']) ?>"
           alt="<?= htmlspecialchars($prod['nombre']) ?>">
      <div class="vp-thumb-wrapper">
        <?php foreach ($imagenes as $i): ?>
          <img class="vp-thumb<?= $i['orden'] === 1 ? ' active' : '' ?>"
               src="/UrbanJ/<?= htmlspecialchars($i['imagen_url']) ?>"
               data-src="/UrbanJ/<?= htmlspecialchars($i['imagen_url']) ?>"
               alt="">
        <?php endforeach; ?>
      </div>
    </div>

    <div class="vp-info">
      <h1><?= htmlspecialchars($prod['nombre']) ?></h1>
      <div class="vp-precio">$<?= number_format($prod['precio'],2) ?></div>
      <p class="vp-desc"><?= nl2br(htmlspecialchars($prod['descripcion'])) ?></p>
      <ul class="vp-meta">
        <li><strong>Categoría:</strong> <?= htmlspecialchars($prod['categoria']) ?></li>
        <li><strong>Stock:</strong> <?= (int)$prod['stock'] ?></li>
        <li><strong>Creado:</strong> <?= htmlspecialchars($prod['creado_at']) ?></li>
        <li><strong>Actualizado:</strong> <?= htmlspecialchars($prod['actualizado_at']) ?></li>
      </ul>
      <div class="vp-acciones">
        <button class="btn btn--primary" onclick="comprar(<?= $prod['id'] ?>)">
          Comprar ahora
        </button>
        <button class="btn btn--secondary" onclick="addToCart(<?= $prod['id'] ?>)">
          Añadir al carrito
        </button>
      </div>
    </div>
  </section>

  <section class="vp-reseñas">
    <h2>Comentarios de clientes</h2>
    <?php if (empty($reseñas)): ?>
      <p>No hay reseñas para este producto.</p>
    <?php else: ?>
      <?php foreach ($reseñas as $r): ?>
        <div class="reseña">
          <div class="r-header">
            <span class="r-user"><?= htmlspecialchars($r['usuario']) ?></span>
            <span class="r-fecha"><?= htmlspecialchars($r['fecha']) ?></span>
            <span class="r-stars">
              <?php
                $full = floor($r['calificacion']);
                $half = ($r['calificacion'] - $full >= 0.5) ? 1 : 0;
                $empty = 5 - $full - $half;
                for ($i=0; $i<$full; $i++) echo '★';
                if ($half) echo '☆';
                for ($i=0; $i<$empty; $i++) echo '☆';
              ?>
            </span>
          </div>
          <p class="r-text"><?= nl2br(htmlspecialchars($r['comentario'])) ?></p>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </section>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
