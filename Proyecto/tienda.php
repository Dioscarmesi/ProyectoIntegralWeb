<?php
require_once 'includes/header.php';
require_once 'includes/conexion.php';
include 'includes/stylos.php';
?>

<div class="tienda-container">
  <!-- FILTROS -->
  <aside class="filtros">
    <form method="GET" class="form-filtros">
      <h3>Filtrar por:</h3>

      <div class="filtro-bloque">
        <h4>Buscar</h4>
        <input type="text" name="buscar" placeholder="Producto..." value="<?= htmlspecialchars($_GET['buscar'] ?? '') ?>" style="width: 100%; padding: 6px; border-radius: 4px;">
      </div>

      <div class="filtro-bloque">
        <h4>Calificación</h4>
        <label><input type="radio" name="rating" value="5" <?= ($_GET['rating'] ?? '') == '5' ? 'checked' : '' ?>> ★★★★★</label><br>
        <label><input type="radio" name="rating" value="4" <?= ($_GET['rating'] ?? '') == '4' ? 'checked' : '' ?>> ★★★★ y más</label><br>
        <label><input type="radio" name="rating" value="3" <?= ($_GET['rating'] ?? '') == '3' ? 'checked' : '' ?>> ★★★ y más</label>
      </div>

      <div class="filtro-bloque">
        <h4>Precio</h4>
        <label><input type="radio" name="precio" value="1" <?= ($_GET['precio'] ?? '') == '1' ? 'checked' : '' ?>> Hasta $500</label><br>
        <label><input type="radio" name="precio" value="2" <?= ($_GET['precio'] ?? '') == '2' ? 'checked' : '' ?>> $500 - $1,000</label><br>
        <label><input type="radio" name="precio" value="3" <?= ($_GET['precio'] ?? '') == '3' ? 'checked' : '' ?>> $1,000 - $2,000</label><br>
        <label><input type="radio" name="precio" value="4" <?= ($_GET['precio'] ?? '') == '4' ? 'checked' : '' ?>> $2,000 o más</label>
      </div>

      <div class="filtro-bloque">
        <h4>Categorías</h4>
        <?php
        $categorias = ['Gorras', 'Ropa', 'Tazas', 'Novedades'];
        foreach ($categorias as $cat):
          $checked = (isset($_GET['categoria']) && $_GET['categoria'] === $cat) ? 'checked' : '';
        ?>
          <label><input type="radio" name="categoria" value="<?= $cat ?>" <?= $checked ?>> <?= $cat ?></label><br>
        <?php endforeach; ?>
      </div>

      <div style="margin-top: 15px;">
        <button type="submit" style="margin-right: 10px;">Aplicar filtros</button>
        <a href="tienda.php" class="btn-reset">Resetear filtros</a>
      </div>
    </form>
  </aside>

  <!-- PRODUCTOS -->
  <main class="productos">
    <h2>Resultados</h2>

    <?php
    $where = [];
    $params = [];

    if (!empty($_GET['buscar'])) {
        $where[] = "(p.nombre LIKE ? OR p.descripcion LIKE ?)";
        $buscar = '%' . $_GET['buscar'] . '%';
        $params[] = $buscar;
        $params[] = $buscar;
    }

    if (!empty($_GET['categoria'])) {
        $where[] = "p.categoria = ?";
        $params[] = $_GET['categoria'];
    }

    if (!empty($_GET['precio'])) {
        switch ($_GET['precio']) {
            case '1': $where[] = "p.precio <= 500"; break;
            case '2': $where[] = "p.precio BETWEEN 500 AND 1000"; break;
            case '3': $where[] = "p.precio BETWEEN 1000 AND 2000"; break;
            case '4': $where[] = "p.precio > 2000"; break;
        }
    }

    if (!empty($_GET['rating'])) {
        $where[] = "p.rating >= ?";
        $params[] = (float) $_GET['rating'];
    }

    $sql = "SELECT p.id, p.nombre, p.precio, p.rating, i.imagen_url
            FROM productos p
            LEFT JOIN imagenes_producto i ON p.id = i.producto_id AND i.orden = 1";

    if (!empty($where)) {
        $sql .= " WHERE " . implode(" AND ", $where);
    }

    $sql .= " ORDER BY p.creado_at DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <div class="grid-productos">
      <?php foreach ($productos as $prod): ?>
        <div class="card-producto">
          <img src="<?= htmlspecialchars($prod['imagen_url'] ?? 'assets/default.jpg') ?>" alt="<?= htmlspecialchars($prod['nombre']) ?>">
          <h4><?= htmlspecialchars($prod['nombre']) ?></h4>
          <p>$<?= number_format($prod['precio'], 2) ?></p>
          <p>★ <?= number_format($prod['rating'], 1) ?>/5</p>
          <form class="form-add-cart" data-id="<?= $prod['id'] ?>">
            <button type="submit">Añadir al carrito</button>
          </form>
        </div>
      <?php endforeach; ?>
    </div>
  </main>
</div>

<?php include 'includes/footer.php'; ?>
