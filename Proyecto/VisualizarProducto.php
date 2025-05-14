<?php
// VisualizarProducto.php

session_start();
require_once __DIR__ . '/includes/conexion.php';
$pageTitle = 'Ver Producto';
require_once __DIR__ . '/includes/header.php'; // abre <body> y hace el header

// 1) Obtener ID de producto
if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    header('Location: index.php');
    exit;
}
$productoId = (int)$_GET['id'];
$usuarioId  = $_SESSION['user_id'] ?? null;

// 2) Procesar POST de reseñas
if ($_SERVER['REQUEST_METHOD'] === 'POST' && in_array($_POST['action'] ?? '', ['add_review','edit_review'], true)) {
    if (!$usuarioId) {
        $error = "Debes iniciar sesión para dejar una reseña.";
    } else {
        $cal   = (int)($_POST['calificacion'] ?? 0);
        $texto = trim($_POST['comentario'] ?? '');
        if ($cal < 1 || $cal > 5 || $texto === '') {
            $error = "Calificación inválida o comentario vacío.";
        } else {
            // Verificar compra
            $chk = $pdo->prepare("
                SELECT 1
                  FROM pedidos p
                  JOIN detalles_pedido dp ON dp.pedido_id = p.id
                 WHERE p.usuario_id = :uid
                   AND dp.producto_id = :pid
                   AND p.estado = 'pagado'
                 LIMIT 1
            ");
            $chk->execute(['uid' => $usuarioId, 'pid' => $productoId]);
            if ($chk->fetchColumn()) {
                if ($_POST['action'] === 'add_review') {
                    $ins = $pdo->prepare("
                      INSERT INTO resenas
                        (usuario_id, producto_id, calificacion, comentario, fecha_resena, estado)
                      VALUES
                        (:uid, :pid, :cal, :txt, NOW(), 1)
                    ");
                    $ins->execute([
                      'uid' => $usuarioId,
                      'pid' => $productoId,
                      'cal' => $cal,
                      'txt' => $texto
                    ]);
                } else {
                    $upd = $pdo->prepare("
                      UPDATE resenas
                         SET calificacion = :cal,
                             comentario   = :txt,
                             fecha_resena = NOW()
                       WHERE usuario_id  = :uid
                         AND producto_id = :pid
                    ");
                    $upd->execute([
                      'uid' => $usuarioId,
                      'pid' => $productoId,
                      'cal' => $cal,
                      'txt' => $texto
                    ]);
                }
                header("Location: VisualizarProducto.php?id={$productoId}");
                exit;
            } else {
                $error = "Solo puedes reseñar productos que hayas comprado.";
            }
        }
    }
}

// 3) Cargar producto
$stmt = $pdo->prepare("SELECT * FROM productos WHERE id = ?");
$stmt->execute([$productoId]);
$producto = $stmt->fetch();
if (!$producto) {
    echo "<p>Producto no encontrado.</p>";
    require __DIR__ . '/includes/footer.php';
    exit;
}

// 4) Cargar imágenes
$imgs = $pdo->prepare("SELECT imagen_url FROM imagenes_producto WHERE producto_id = ? ORDER BY orden");
$imgs->execute([$productoId]);
$imagenes = $imgs->fetchAll(PDO::FETCH_COLUMN);

// 5) Verificar reseña propia
$revChk = false;
if ($usuarioId) {
    $rc = $pdo->prepare("
      SELECT calificacion, comentario
        FROM resenas
       WHERE usuario_id = ? AND producto_id = ? AND estado = 1
       LIMIT 1
    ");
    $rc->execute([$usuarioId, $productoId]);
    $revChk = $rc->fetch(PDO::FETCH_ASSOC);
}

// 6) Cargar todas las reseñas públicas
$allRev = $pdo->prepare("
  SELECT r.calificacion, r.comentario, u.usuario, r.fecha_resena
    FROM resenas r
    JOIN usuarios u ON u.id = r.usuario_id
   WHERE r.producto_id = :pid
     AND r.estado = 1
   ORDER BY r.fecha_resena DESC
");
$allRev->execute(['pid' => $productoId]);
$resenas = $allRev->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="vp-container">
  <div class="vp-producto">
    <div class="vp-imagenes">
      <?php if (!empty($imagenes)): ?>
        <img id="vp-main-img" src="assets/<?= htmlspecialchars($imagenes[0]) ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>">
        <div class="vp-thumb-wrapper">
          <?php foreach ($imagenes as $url): ?>
            <img class="vp-thumb" src="assets/<?= htmlspecialchars($url) ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>">
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <p>No hay imágenes disponibles.</p>
      <?php endif; ?>
    </div>

    <div class="vp-info">
      <h1><?= htmlspecialchars($producto['nombre']) ?></h1>
      <p class="vp-precio">$<?= number_format($producto['precio'],2) ?></p>
      <p class="vp-desc"><?= nl2br(htmlspecialchars($producto['descripcion'])) ?></p>
      <ul class="vp-meta">
        <li><strong>Categoría:</strong> <?= htmlspecialchars($producto['categoria']) ?></li>
        <li><strong>Stock:</strong> <?= (int)$producto['stock'] ?></li>
        <li><strong>Creado:</strong> <?= $producto['creado_at'] ?></li>
        <li><strong>Actualizado:</strong> <?= $producto['actualizado_at'] ?></li>
      </ul>
      <div class="vp-acciones">
        <button onclick="comprar(<?= $productoId ?>)" class="btn btn--primary">Comprar ahora</button>
        <button onclick="addToCart(<?= $productoId ?>)" class="btn btn--secondary">Añadir al carrito</button>
      </div>
    </div>
  </div>

  <!-- Reseñas -->
  <section class="vp-reseñas">
    <h2>Comentarios de clientes</h2>

    <?php if (!empty($error)): ?>
      <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <?php if ($usuarioId && $revChk === false): ?>
      <form method="post" class="review-form">
        <label>Calificación:
          <select name="calificacion" required>
            <?php for ($i=5; $i>=1; $i--): ?>
              <option value="<?= $i ?>"><?= str_repeat('★', $i) ?></option>
            <?php endfor; ?>
          </select>
        </label>
        <label>Comentario:
          <textarea name="comentario" rows="3" required></textarea>
        </label>
        <button type="submit" name="action" value="add_review">Enviar reseña</button>
      </form>
    <?php elseif ($usuarioId && $revChk): ?>
      <form method="post" class="review-form">
        <label>Tu calificación:
          <select name="calificacion" required>
            <?php for ($i=5; $i>=1; $i--): ?>
              <option value="<?= $i ?>" <?= $revChk['calificacion']===$i?'selected':'' ?>>
                <?= str_repeat('★', $i) ?>
              </option>
            <?php endfor; ?>
          </select>
        </label>
        <label>Tu comentario:
          <textarea name="comentario" rows="3" required><?= htmlspecialchars($revChk['comentario']) ?></textarea>
        </label>
        <button type="submit" name="action" value="edit_review">Actualizar reseña</button>
      </form>
    <?php elseif (!$usuarioId): ?>
      <p class="info">Inicia sesión para dejar tu reseña.</p>
    <?php else: ?>
      <p class="info">Ya publicaste tu reseña.</p>
    <?php endif; ?>

    <?php if (empty($resenas)): ?>
      <p class="info">Sé el primero en dejar una reseña.</p>
    <?php else: ?>
      <?php foreach ($resenas as $r): ?>
        <div class="reseña">
          <div class="r-header">
            <span class="r-user"><?= htmlspecialchars($r['usuario']) ?></span>
            <span class="r-stars"><?= str_repeat('★', (int)$r['calificacion']) ?></span>
          </div>
          <p class="r-text"><?= nl2br(htmlspecialchars($r['comentario'])) ?></p>
          <small><?= $r['fecha_resena'] ?></small>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </section>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
