<?php
$pageTitle = 'Inicio';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/conexion.php';

// Obtener banners
$stmt = $pdo->query("SELECT imagen_url, enlace, texto_alt FROM banners ORDER BY orden ASC");
$banners = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Cargar productos por categoría
function cargarProductos(PDO $pdo, string $categoria): array {
    $stmt = $pdo->prepare("
        SELECT p.id, p.nombre, p.precio, p.rating,
            (SELECT imagen_url FROM imagenes_producto ip
             WHERE ip.producto_id = p.id ORDER BY orden LIMIT 1) AS img
        FROM productos p
        WHERE p.categoria = ?
        LIMIT 6
    ");
    $stmt->execute([$categoria]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$gorras  = cargarProductos($pdo, 'Gorras');
$ropa    = cargarProductos($pdo, 'Ropa');
$tasas   = cargarProductos($pdo, 'Tasas');
$camisas = cargarProductos($pdo, 'Camisas');
?>

<!-- Owl Carousel CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
<link rel="stylesheet" href="css/Home.css">

<main class="container home-container">

  <!-- Carrusel de Banners full-width -->
<div class="banner-fullscreen-wrapper">
  <div class="owl-carousel banner-fullscreen owl-theme" id="mainBanner">
    <?php foreach ($banners as $b): ?>
      <div class="item">
        <a href="<?= htmlspecialchars($b['enlace']) ?>" class="banner-item">
          <img src="/UrbanJ/<?= htmlspecialchars($b['imagen_url']) ?>" alt="<?= htmlspecialchars($b['texto_alt']) ?>">
          <div class="banner-caption">
            <h2><?= htmlspecialchars($b['texto_alt']) ?></h2>
          </div>
        </a>
      </div>
    <?php endforeach; ?>
  </div>
  <button class="manual-nav nav-left" onclick="$('#mainBanner').trigger('prev.owl.carousel')">‹</button>
  <button class="manual-nav nav-right" onclick="$('#mainBanner').trigger('next.owl.carousel')">›</button>
</div>

  <!-- Secciones de productos -->
  <?php
  $secciones = [
    'Gorras' => $gorras,
    'Ropa' => $ropa,
    'Tasas' => $tasas,
    'Camisas' => $camisas
  ];
  foreach ($secciones as $titulo => $productos): ?>
    <section class="home-section">
      <h2><?= $titulo ?></h2>
      <div class="productos-carousel">
        <?php foreach ($productos as $p): ?>
          <div class="producto-urbanJ">
            <a class="prod-link" href="VisualizarProducto.php?id=<?= $p['id'] ?>">
              <img src="/UrbanJ/<?= htmlspecialchars($p['img'] ?? 'assets/placeholder.png') ?>" alt="">
              <h4 class="prod-name"><?= htmlspecialchars($p['nombre']) ?></h4>
            </a>

            <!-- Calificación -->
            <div class="rating">
              <?php
              $fullStars = floor($p['rating']);
              $halfStar = ($p['rating'] - $fullStars >= 0.5);
              for ($i = 0; $i < $fullStars; $i++) echo '★';
              if ($halfStar) echo '½';
              for ($i = $fullStars + $halfStar; $i < 5; $i++) echo '☆';
              ?>
              <span class="review-count">(67)</span>
            </div>

            <p class="envio">Entrega GRATIS</p>
            <p class="precio">$<?= number_format($p['precio'], 2) ?></p>

            <button class="btn btn--primary btn--small" onclick="addToCart(<?= $p['id'] ?>)">Agregar al carrito</button>
          </div>
        <?php endforeach; ?>
      </div>
    </section>
  <?php endforeach; ?>
</main>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

<script>
$(document).ready(function(){
  $("#mainBanner").owlCarousel({
    items: 1,
    loop: true,
    autoplay: true,
    autoplayTimeout: 5000,
    autoplayHoverPause: true,
    nav: false,
    dots: true
  });
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
