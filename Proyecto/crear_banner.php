<?php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/conexion.php';

if (!$is_admin) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $enlace = $_POST['enlace'] ?? '#';
    $texto_alt = $_POST['texto_alt'] ?? '';
    $orden = intval($_POST['orden'] ?? 0);
    $imagen_url = '';

    // Subida de imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $nombreOriginal = $_FILES['imagen']['name'];
        $extension = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
        $nombreFinal = 'banner_' . time() . '.' . $extension;
        $rutaDestino = 'assets/' . $nombreFinal;

        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
            $imagen_url = $rutaDestino;
        }
    }

    if ($imagen_url !== '') {
        $stmt = $pdo->query("SELECT MAX(id) as max_id FROM banners");
        $lastBannerId = $stmt->fetch(PDO::FETCH_ASSOC)['max_id'] ?? 0;
        $nuevoBannerId = $lastBannerId + 1;

        $stmt = $pdo->prepare("
            INSERT INTO banners (id, imagen_url, enlace, texto_alt, orden)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$nuevoBannerId, $imagen_url, $enlace, $texto_alt, $orden]);

        $_SESSION['success'] = "¡Banner creado correctamente!";
        echo '<script>window.location.href = "admin_banners.php";</script>';
    exit;
    }
}
?>

<link rel="stylesheet" href="css/formBanners.css">

<div class="form-banner-container">
    <h1>Crear Nuevo Banner</h1>
    <form method="POST" enctype="multipart/form-data">
        <label>Selecciona una Imagen:</label>
        <input type="file" name="imagen" accept="image/*" required>

        <label>Enlace (URL destino):</label>
        <input type="text" name="enlace" value="#">

        <label>Texto Alternativo (alt):</label>
        <input type="text" name="texto_alt">

        <label>Orden de aparición:</label>
        <input type="number" name="orden" value="0" min="0">

        <button type="submit">Crear Banner</button>
    </form>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
