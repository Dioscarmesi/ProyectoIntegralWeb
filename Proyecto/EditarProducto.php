<?php
session_start();
require __DIR__ . '/includes/conexion.php';

// Sólo administradores
if (empty($_SESSION['admin'])) {
    header('Location: index.php');
    exit;
}

// 1) Obtener ID y datos actuales
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare("SELECT * FROM productos WHERE id = ?");
$stmt->execute([$id]);
$producto = $stmt->fetch();
if (!$producto) {
    header('Location: inventario.php');
    exit;
}

// Recuperar imágenes existentes
$stmtImgs = $pdo->prepare("SELECT * FROM imagenes_producto WHERE producto_id = ? ORDER BY orden");
$stmtImgs->execute([$id]);
$imagenes = $stmtImgs->fetchAll(PDO::FETCH_ASSOC);

// Inicializar valores de formulario y errores
$errors      = [];
$nombre      = $producto['nombre'];
$descripcion = $producto['descripcion'];
$precio      = $producto['precio'];
$categoria   = $producto['categoria'];
$stock       = $producto['stock'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 2) Leer nuevo valor
    $nombre      = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $precio      = $_POST['precio'] ?? '';
    $categoria   = $_POST['categoria'] ?? '';
    $stock       = $_POST['stock'] ?? '';

    // Validaciones
    if ($nombre === '')      $errors[] = 'El nombre es obligatorio.';
    if ($precio === '' || !is_numeric($precio)) $errors[] = 'El precio debe ser un número.';
    if (!in_array($categoria, ['Gorras','Ropa','Novedades','Accesorios'], true)) {
        $errors[] = 'Selecciona una categoría válida.';
    }
    if ($stock === '' || !ctype_digit($stock)) $errors[] = 'El stock debe ser un entero.';

    if (empty($errors)) {
        // 3) Actualizar productos
        $stmtUp = $pdo->prepare("
          UPDATE productos
             SET nombre = ?, descripcion = ?, precio = ?, categoria = ?, stock = ?
           WHERE id = ?
        ");
        $stmtUp->execute([
            $nombre, $descripcion, $precio, $categoria, $stock, $id
        ]);

        // 4) Borrar imágenes marcadas
        if (!empty($_POST['delete_images'])) {
            $del = $pdo->prepare("DELETE FROM imagenes_producto WHERE id = ?");
            foreach ($_POST['delete_images'] as $imgId) {
                // Borrar fichero físico
                $stmtFile = $pdo->prepare("SELECT imagen_url FROM imagenes_producto WHERE id = ?");
                $stmtFile->execute([$imgId]);
                if ($row = $stmtFile->fetch()) {
                    @unlink(__DIR__ . '/' . $row['imagen_url']);
                }
                // Borrar registro
                $del->execute([$imgId]);
            }
        }

        // 5) Subir nuevas imágenes
        if (!empty($_FILES['images'])) {
            // obtener siguiente orden
            $stmtMax = $pdo->prepare("SELECT COALESCE(MAX(orden),0) FROM imagenes_producto WHERE producto_id = ?");
            $stmtMax->execute([$id]);
            $orden = $stmtMax->fetchColumn() + 1;

            $upStmt = $pdo->prepare("
              INSERT INTO imagenes_producto
                (producto_id, imagen_url, orden)
              VALUES (?, ?, ?)
            ");

            foreach ($_FILES['images']['tmp_name'] as $i => $tmpName) {
                if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                    $orig   = $_FILES['images']['name'][$i];
                    $ext    = pathinfo($orig, PATHINFO_EXTENSION);
                    $newFn  = uniqid("prd{$id}_") . ".$ext";
                    $dest   = __DIR__ . "/assets/$newFn";
                    if (move_uploaded_file($tmpName, $dest)) {
                        $imgUrl = "assets/$newFn";
                        $upStmt->execute([$id, $imgUrl, $orden++]);
                    }
                }
            }
        }

        header('Location: inventario.php');
        exit;
    }
}

$pageTitle = 'Editar Producto';
require __DIR__ . '/includes/header.php';
?>
<link rel="stylesheet" href="/UrbanJ/css/EditarProducto.css">

<main class="container editar-producto-container">
  <h2>Editar Producto</h2>

  <?php if ($errors): ?>
    <div class="errors">
      <ul>
        <?php foreach ($errors as $e): ?>
          <li><?= htmlspecialchars($e) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data">
    <label>
      Nombre *
      <input type="text" name="nombre" value="<?= htmlspecialchars($nombre) ?>">
    </label>

    <label>
      Descripción
      <textarea name="descripcion"><?= htmlspecialchars($descripcion) ?></textarea>
    </label>

    <label>
      Precio *
      <input type="number" step="0.01" name="precio" value="<?= htmlspecialchars($precio) ?>">
    </label>

    <label>
      Categoría *
      <select name="categoria">
        <option value="">-- Selecciona --</option>
        <?php foreach (['Gorras','Ropa','Novedades','Accesorios'] as $cat): ?>
          <option value="<?= $cat ?>"
            <?= $categoria === $cat ? 'selected' : '' ?>>
            <?= $cat ?>
          </option>
        <?php endforeach; ?>
      </select>
    </label>

    <label>
      Stock *
      <input type="number" name="stock" value="<?= htmlspecialchars($stock) ?>">
    </label>

    <!-- Imágenes existentes -->
    <?php if ($imagenes): ?>
      <div class="existing-images">
        <p>Imágenes actuales (marca para borrar):</p>
        <?php foreach ($imagenes as $img): ?>
          <label class="img-thumb">
            <input type="checkbox" name="delete_images[]" value="<?= $img['id'] ?>">
            <img src="/UrbanJ/<?= htmlspecialchars($img['imagen_url']) ?>" alt="" width="80">
          </label>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <!-- Nuevas subidas -->
    <div id="image-fields">
      <label>Nueva imagen 1:
        <input type="file" name="images[]" accept="image/*">
      </label>
    </div>
    <button type="button" id="add-image">+ Añadir otra imagen</button>

    <button type="submit" class="btn-submit">Guardar Cambios</button>
  </form>
</main>

<script>
// Dinámico para subir más imágenes
document.getElementById('add-image').addEventListener('click', () => {
  const cont = document.getElementById('image-fields');
  const idx  = cont.querySelectorAll('input[type=file]').length + 1;
  const lbl  = document.createElement('label');
  lbl.innerHTML = `Nueva imagen ${idx}: <input type="file" name="images[]" accept="image/*">`;
  cont.appendChild(lbl);
});
</script>

<?php require __DIR__ . '/includes/footer.php'; ?>
