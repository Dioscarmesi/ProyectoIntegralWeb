<?php
session_start();
require __DIR__ . '/includes/conexion.php';

// Solo administradores
if (empty($_SESSION['admin'])) {
    header('Location: index.php');
    exit;
}

$errors = [];
// Valores por defecto
$nombre = $descripcion = $precio = $categoria = $stock = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1) Recoger y validar datos
    $nombre      = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $precio      = $_POST['precio'] ?? '';
    $categoria   = $_POST['categoria'] ?? '';
    $stock       = $_POST['stock'] ?? '';

    if ($nombre === '')      $errors[] = 'El nombre es obligatorio.';
    if ($precio === '' || !is_numeric($precio)) $errors[] = 'El precio debe ser un número.';
    if (!in_array($categoria, ['Gorras','Ropa','Novedades','Accesorios'], true)) {
        $errors[] = 'Selecciona una categoría válida.';
    }
    if ($stock === '' || !ctype_digit($stock)) $errors[] = 'El stock debe ser un entero.';

    // 2) Si no hay errores, insertamos
    if (empty($errors)) {
        $stmt = $pdo->prepare("
          INSERT INTO productos
            (nombre, descripcion, precio, categoria, stock)
          VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$nombre, $descripcion, $precio, $categoria, $stock]);
        $producto_id = $pdo->lastInsertId();

        // 3) Procesar imágenes
        if (!empty($_FILES['images'])) {
            $orden = 1;
            foreach ($_FILES['images']['tmp_name'] as $i => $tmpName) {
                if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                    $orig   = $_FILES['images']['name'][$i];
                    $ext    = pathinfo($orig, PATHINFO_EXTENSION);
                    $nuevo  = uniqid("prod_{$producto_id}_") . ".$ext";
                    $dest   = __DIR__ . "/assets/$nuevo";
                    if (move_uploaded_file($tmpName, $dest)) {
                        $imgUrl = "assets/$nuevo";
                        $stmtI = $pdo->prepare("
                          INSERT INTO imagenes_producto
                            (producto_id, imagen_url, orden)
                          VALUES (?, ?, ?)
                        ");
                        $stmtI->execute([$producto_id, $imgUrl, $orden++]);
                    }
                }
            }
        }

        header('Location: inventario.php');
        exit;
    }
}

$pageTitle = 'Crear Producto';
require __DIR__ . '/includes/header.php';
?>
<link rel="stylesheet" href="/UrbanJ/css/CrearProducto.css">

<main class="container crear-producto-container">
  <h2>Crear Nuevo Producto</h2>

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

    <div id="image-fields">
      <label>Imagen 1:
        <input type="file" name="images[]" accept="image/*">
      </label>
    </div>

    <button type="button" id="add-image">+ Añadir imagen</button>
    <button type="submit" class="btn-submit">Guardar Producto</button>
  </form>
</main>

<script>
// Agrega dinámicamente campos de imagen
document.getElementById('add-image').addEventListener('click', () => {
  const cont = document.getElementById('image-fields');
  const idx  = cont.querySelectorAll('input[type=file]').length + 1;
  const lbl  = document.createElement('label');
  lbl.innerHTML = `Imagen ${idx}: <input type="file" name="images[]" accept="image/*">`;
  cont.appendChild(lbl);
});
</script>

<?php require __DIR__ . '/includes/footer.php'; ?>
