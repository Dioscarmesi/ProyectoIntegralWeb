<?php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/conexion.php';

if (!$is_admin) {
    header('Location: index.php');
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: admin_banners.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM banners WHERE id = ?");
$stmt->execute([$id]);
$banner = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$banner) {
    echo "<p>Banner no encontrado.</p>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $imagen_url = $_POST['imagen_url'] ?? '';
    $enlace = $_POST['enlace'] ?? '#';
    $texto_alt = $_POST['texto_alt'] ?? '';
    $orden = intval($_POST['orden'] ?? 0);

    $stmt = $pdo->prepare("UPDATE banners SET imagen_url = ?, enlace = ?, texto_alt = ?, orden = ? WHERE id = ?");
    $stmt->execute([$imagen_url, $enlace, $texto_alt, $orden, $id]);
    header('Location: admin_banners.php');
    exit;
}
?>

<link rel="stylesheet" href="css/formBanners.css">

<div class="form-banner-container">
    <h1>Editar Banner</h1>
    <form method="POST">
        <label>URL de la Imagen:</label>
        <input type="text" name="imagen_url" value="<?= htmlspecialchars($banner['imagen_url']) ?>" required>

        <label>Enlace (URL destino):</label>
        <input type="text" name="enlace" value="<?= htmlspecialchars($banner['enlace']) ?>">

        <label>Texto Alternativo (alt):</label>
        <input type="text" name="texto_alt" value="<?= htmlspecialchars($banner['texto_alt']) ?>">

        <label>Orden de aparición:</label>
        <input type="number" name="orden" value="<?= intval($banner['orden']) ?>" min="0">

        <button type="submit">Guardar Cambios</button>
    </form>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
