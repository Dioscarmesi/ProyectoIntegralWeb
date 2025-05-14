<?php
// /UrbanJ/index.php

// Título de la página
$pageTitle = 'Inicio';

// Incluir cabecera (arranca sesión, conexión, <head> con styles.css y banner de navegación)
require_once __DIR__ . '/includes/header.php';

// 1) Obtener banners para el carrusel
$stmt = $pdo->query("
    SELECT imagen_url, enlace, texto_alt
      FROM banners
     ORDER BY orden
");
$banners = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 2) Función para cargar 6 productos de una categoría
function cargarProductos(PDO $pdo, string $categoria): array {
    $sql = "
        SELECT
            p.id,
            p.nombre,
            p.precio,
            (
                SELECT imagen_url
                  FROM imagenes_producto ip
                 WHERE ip.producto_id = p.id
                 ORDER BY orden
                 LIMIT 1
            ) AS img
        FROM productos p
       WHERE p.categoria = ?
       LIMIT 6
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$categoria]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// 3) Cargar secciones de productos
$gorras  = cargarProductos($pdo, 'Gorras');
$ropa    = cargarProductos($pdo, 'Ropa');
$tasas   = cargarProductos($pdo, 'Tasas');
$camisas = cargarProductos($pdo, 'Camisas');
?>

<main class="home-container">

  <!-- Carrusel de banners -->
  <section class="home-banner">
    <div class="carousel">
      <?php foreach ($banners as $b): ?>
        <a class="banner-slide" href="<?= htmlspecialchars($b['enlace']) ?>">
          <img src="/UrbanJ/<?= htmlspecialchars($b['imagen_url']) ?>"
               alt="<?= htmlspecialchars($b['texto_alt']) ?>">
        </a>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- Sección: Gorras -->
  <section class="home-section">
    <h2>Gorras</h2>
    <div class="productos-carousel">
      <?php foreach ($gorras as $p): ?>
        <div class="producto-slide">
          <a class="prod-link" href="VisualizarProducto.php?id=<?= $p['id'] ?>">
            <img src="/UrbanJ/<?= htmlspecialchars($p['img'] ?? 'assets/placeholder.png') ?>" alt="">
            <h4><?= htmlspecialchars($p['nombre']) ?></h4>
            <p class="precio">$<?= number_format($p['precio'], 2) ?></p>
          </a>
          <div class="acciones">
            <button class="btn btn--primary btn--small" onclick="comprar(<?= $p['id'] ?>)">COMPRAR</button>
            <button class="btn btn--secondary btn--small" onclick="addToCart(<?= $p['id'] ?>)">AÑADIR</button>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- Sección: Ropa -->
  <section class="home-section">
    <h2>Ropa</h2>
    <div class="productos-carousel">
      <?php foreach ($ropa as $p): ?>
        <div class="producto-slide">
          <a class="prod-link" href="VisualizarProducto.php?id=<?= $p['id'] ?>">
            <img src="/UrbanJ/<?= htmlspecialchars($p['img'] ?? 'assets/placeholder.png') ?>" alt="">
            <h4><?= htmlspecialchars($p['nombre']) ?></h4>
            <p class="precio">$<?= number_format($p['precio'], 2) ?></p>
          </a>
          <div class="acciones">
            <button class="btn btn--primary btn--small" onclick="comprar(<?= $p['id'] ?>)">COMPRAR</button>
            <button class="btn btn--secondary btn--small" onclick="addToCart(<?= $p['id'] ?>)">AÑADIR</button>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- Sección: Tasas -->
  <section class="home-section">
    <h2>Tasas</h2>
    <div class="productos-carousel">
      <?php foreach ($tasas as $p): ?>
        <div class="producto-slide">
          <a class="prod-link" href="VisualizarProducto.php?id=<?= $p['id'] ?>">
            <img src="/UrbanJ/<?= htmlspecialchars($p['img'] ?? 'assets/placeholder.png') ?>" alt="">
            <h4><?= htmlspecialchars($p['nombre']) ?></h4>
            <p class="precio">$<?= number_format($p['precio'], 2) ?></p>
          </a>
          <div class="acciones">
            <button class="btn btn--primary btn--small" onclick="comprar(<?= $p['id'] ?>)">COMPRAR</button>
            <button class="btn btn--secondary btn--small" onclick="addToCart(<?= $p['id'] ?>)">AÑADIR</button>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- Sección: Camisas -->
  <section class="home-section">
    <h2>Camisas</h2>
    <div class="productos-carousel">
      <?php foreach ($camisas as $p): ?>
        <div class="producto-slide">
          <a class="prod-link" href="VisualizarProducto.php?id=<?= $p['id'] ?>">
            <img src="/UrbanJ/<?= htmlspecialchars($p['img'] ?? 'assets/placeholder.png') ?>" alt="">
            <h4><?= htmlspecialchars($p['nombre']) ?></h4>
            <p class="precio">$<?= number_format($p['precio'], 2) ?></p>
          </a>
          <div class="acciones">
            <button class="btn btn--primary btn--small" onclick="comprar(<?= $p['id'] ?>)">COMPRAR</button>
            <button class="btn btn--secondary btn--small" onclick="addToCart(<?= $p['id'] ?>)">AÑADIR</button>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

</main>

<?php
// Incluir pie de página
require_once __DIR__ . '/includes/footer.php';
?>
